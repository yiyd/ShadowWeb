<?php
/**
 * Coder: ZX
 * Date: 2015/8/19
 * Time: 16:00
 */
	require_once('id_name_changer_fns.php');

	function display_user_manage_page($roles_array){
?>
<!DOCTYPE html>
<html>
    <head>

    <meta charset="UTF-8">

    <title>中银开放平台-事项跟踪工具</title>
    <link rel="stylesheet" type="type/css" href="resources/jquery-easyui/themes/default/easyui.css">
    <link rel="stylesheet" type="type/css" href="resources/jquery-easyui/themes/icon.css">
    <link rel="stylesheet" type="type/css" href="resources/common/css/style.css">
    <script type="text/javascript" src="resources/jquery-easyui/jquery.min.js"></script>
    <script type="text/javascript" src="resources/jquery-easyui/jquery.easyui.min.js"></script>
    <script type="text/javascript" src="resources/jquery-easyui/locale/easyui-lang-zh_CN.js"></script>
	</head>
	<body>
		<div>
            <table id="dg" class="easyui-datagrid" width="100%" data-options="singleSelect:true, rownumbers:true,nowrap:false,pagination:true,pageSize:50,pageList:[30,50,80,100],toolbar:'#tb'">
                <thead>
                    <tr>
                        <th data-options="field:'id', hidden:true"></th>
                    	<th data-options="field:'check', checkbox:true"></th>
                        <th width="8%" data-options="field:'user_name'">用户名</th>
                        <th width="15%" data-options="field:'role'">角色</th>
                        <th data-options="field:'role_id', hidden:true"></th>
                        <th width="65%" data-options="field:'email'">邮箱</th>
                    </tr>
                </thead>
            </table>   
        </div>
        <div id="tb" style="padding:2px 5px;">
        	<div>
                <a href="javascript:void(0)" class="easyui-linkbutton" onclick="handlerAdd()" data-options="iconCls:'icon-add'">新建</a>
				<a href="javascript:void(0)" class="easyui-linkbutton" onclick="handlerEdit()" data-options="iconCls:'icon-edit'">编辑</a>
				<a href="javascript:void(0)" class="easyui-linkbutton" onclick="handlerDelete()" data-options="iconCls:'icon-remove'">删除</a>
				<a href="javascript:void(0)" class="easyui-linkbutton" onclick="handlerResetPassword()" data-options="iconCls:'icon-reload'">重置密码</a>
			</div>
			<div>
				用户名：<input id="search_user_name" style="width:110px">
				角色：<input id="search_role" class="easyui-combobox" name="search_role" data-options="valueField:'role_id',textField:'role_name',url:'ajax_php/get_roles.php',editable:false, panelHeight:'auto'" />   
				邮箱：<input id="search_email" style="width:110px">
				<a href="javascript:void(0)" class="easyui-linkbutton" onclick="handlerSearch()" data-options="iconCls:'icon-search'">查询</a>
				
			</div>
		</div>

    	<div id="dlg" class="easyui-dialog" title="新增用户" style="width:auto;height:auto;" data-options="iconCls: 'icon-add',closed:true,modal:true,buttons: '#dlg-buttons'">
    		<table class="table_list" width="100%" border="0" align="center" cellpadding="0" cellspacing="1">
				
				<tr height="26">
					<td nowrap="nowrap">
						<div align="right" style="padding-right:2px;">
							用户名：<font color="red">*</font>
						</div>
					</td>
					<td>
						<div align="left" style="padding-left:2px;">
	
							<input id="user_name" class="easyui-validatebox" data-options="required:true,validType:'length[1,32]'">
						</div>
					</td>
					
				</tr>
                <tr id="password_block" height="26">
					<td nowrap="nowrap">
						<div align="right" style="padding-right:2px;">
							初始密码：<font color="red">*</font>
						</div>
					</td>
					<td>
						<div align="left" style="padding-left:2px;">
							<input id="password" type="password" class="easyui-validatebox" data-options="required:true,validType:'length[1,40]'">
						</div>
					</td>
					
				</tr>
				<tr height="26">
					<td nowrap="nowrap">
						<div align="right" style="padding-right:2px;">
							所属角色：<font color="red">*</font>
						</div>
					</td>
					<td>
						<div align="left" style="padding-left:2px;">
							<select id="role" name="role">
								<?php
									foreach ($roles_array as $role) {
        								echo "<option value=\"".$role['role_id']."\" >".$role['role_name']."</option>";
    								}
    							?>
							</select>
						</div>
					</td>
					
				</tr>
				<tr height="26">
					<td nowrap="nowrap">
						<div align="right" style="padding-right:2px;">
							绑定邮箱：<font color="red">*</font>
						</div>
					</td>
					<td>
						<div align="left" style="padding-left:2px;">
							<input id="email" class="easyui-validatebox" data-options="required:true,validType:['email','length[0,100]']">

						</div>
					</td>
					
				</tr>
			</table>
		</div>
		<div id='dlg-buttons' style="padding-top:10px;text-align:center;">
                <a href="javascript:void(0)" class="easyui-linkbutton" onclick="save()" data-options="iconCls:'icon-save'">保存</a>
                <a href="javascript:void(0)" class="easyui-linkbutton" onclick="cancel()" data-options="iconCls:'icon-undo'">取消</a>
        </div>

        <script>


        	(function($){
	            function pagerFilter(data){
	                if ($.isArray(data)){   // is array
	                    data = {
	                        total: data.length,
	                        rows: data
	                    }
	                }
	                var dg = $(this);
	                var state = dg.data('datagrid');
	                var opts = dg.datagrid('options');
	                if (!state.allRows){
	                    state.allRows = (data.rows);
	                }
	                var start = (opts.pageNumber-1)*parseInt(opts.pageSize);
	                var end = start + parseInt(opts.pageSize);
	                data.rows = $.extend(true,[],state.allRows.slice(start, end));
	                return data;
	            }

	            var loadDataMethod = $.fn.datagrid.methods.loadData;
	            $.extend($.fn.datagrid.methods, {
	                clientPaging: function(jq){
	                    return jq.each(function(){
	                        var dg = $(this);
	                        var state = dg.data('datagrid');
	                        var opts = state.options;
	                        opts.loadFilter = pagerFilter;
	                        var onBeforeLoad = opts.onBeforeLoad;
	                        opts.onBeforeLoad = function(param){
	                            state.allRows = null;
	                            return onBeforeLoad.call(this, param);
	                        }
	                        dg.datagrid('getPager').pagination({
	                            onSelectPage:function(pageNum, pageSize){
	                                opts.pageNumber = pageNum;
	                                opts.pageSize = pageSize;
	                                $(this).pagination('refresh',{
	                                    pageNumber:pageNum,
	                                    pageSize:pageSize
	                                });
	                                dg.datagrid('loadData',state.allRows);
	                            }
	                        });
	                        $(this).datagrid('loadData', state.data);
	                        if (opts.url){
	                            $(this).datagrid('reload');
	                        }
	                    });
	                },
	                loadData: function(jq, data){
	                    jq.each(function(){
	                        $(this).data('datagrid').allRows = null;
	                    });
	                    return loadDataMethod.call($.fn.datagrid.methods, jq, data);
	                },
	                getAllRows: function(jq){
	                    return jq.data('datagrid').allRows;
	                }
	            })
	        })(jQuery);

        	$(function(){
        		$.ajax({
                    url:"ajax_php/users_array.php",
                    type:"POST",
                    success:function(json){
                        // alert(json);      
                        $('#dg').datagrid('loadData', getData(json)).datagrid('clientPaging');
                        var rows = $('#dg').datagrid('getRows');
                        for (var i = 0; i < rows.length; i++) {
                        	$('#dg').datagrid('beginEdit',i);
                        };
                                
                    }
                });

        		
        	});

        	function getData(json){
	            var rows = [];
	            $.each($.parseJSON(json), function(idx,item){
	                
	                rows.push({
	                	id:item.user_id,
	                    user_name:item.user_name,
	                    password:item.user_passwd,
	                    role_id:item.role_id,
	                    role: item.role_name,
	                    email: item.user_mail,
	                });

	            });
	            return rows;
	        }
	        
			function handlerSearch(){
	        	
	            var condition_array = [];
	            
	            var search_user_name = $('#search_user_name').val();
	            if ('' != search_user_name) {
	                var condition_row = [];
	                condition_row[0] = 'user_name';
	                condition_row[1] = search_user_name;
	                var length = condition_array.length;
	                condition_array[length] = condition_row;   
	            }

	            var search_role = $('#search_role').combobox('getValue');
	            if ('' != search_role) {
	                var condition_row = [];
	                condition_row[0] = 'role_id';
	                condition_row[1] = search_role;
	                var length = condition_array.length;
	                condition_array[length] = condition_row;  

	            }

	            var search_email = $('#search_email').val();
	            if ('' != search_email) {
	                var condition_row = [];
	                condition_row[0] = 'user_mail';
	                condition_row[1] = search_email;
	                var length = condition_array.length;
	                condition_array[length] = condition_row;  

	            }


	            if (condition_array.length > 0) {
	                // alert(condition_array.length);
	                $.ajax({
	                    url:"ajax_php/search_user.php",
	                    type:"POST",
	                    data:{
	                        condition:condition_array
	                    },
	                    success:function(json){
	                        // alert(json);      
	                        $('#dg').datagrid('loadData', getData(json)).datagrid('clientPaging');
	                    }
	                }); 
	            }
            
        
	        }



	        function handlerAdd(){
	        	$('#dlg').dialog('open');
	        			
    			$('#user_name').val('');
    			$('#user_name').validatebox('validate');
    			
    			$('#password_block').show();
    			
    			$('#password').val('');
    			$('#password').validatebox('validate');

    			$('#role').val('1');
    			
    			$('#email').val('');
    			$('#email').validatebox('validate');
    			isNew = true;
	        }

	        function handlerEdit(){
	        	var row = $('#dg').datagrid('getSelected');
    			if (row != null)
    			{
    				$('#dlg').dialog('open');

        			$('#user_name').val(row.user_name);
        			$('#user_name').validatebox('validate');
        			
        			$('#password_block').hide();
        			
        			$('#role').val(row.role_id);
        			
        			
        			$('#email').val(row.email);
        			$('#email').validatebox('validate');
        			isNew = false;
    			} 
	        }

	        function handlerDelete(){
	        	var row = $('#dg').datagrid('getSelected');
    			if (row != null)
    			{
    				$.messager.confirm('确认','您确认想要删除该用户吗？删除后，该用户将无法恢复！',function(r){    
		                if (r){
		                    $.ajax({
			                    url:"ajax_php/delete_user.php",
			                    type:"POST",
			                    data:{
			                    	user_id:row.id
			                	},
			                	success:function(){
			                		
					                window.parent.refreshTabs(); 
			                	}
		                	});
		                }    
		            });
    				
    			}
	        }

	        function handlerResetPassword() {
	        	var row = $('#dg').datagrid('getSelected');
    			if (row != null)
    			{
    				$.messager.confirm('确认','您确认想要重置该用户的密码吗？',function(r){    
		                if (r){
		                    $.ajax({
			                    url:"ajax_php/reset_password.php",
			                    type:"POST",
			                    data:{
			                    	user_id:row.id
			                	},
			                	
		                	});
		                }    
		            });
    			} 
	        }

			$.extend($.fn.validatebox.defaults.rules, {    
			    minLength: {    
			        validator: function(value, param){    
			            return value.length >= param[0];    
			        },    
			        message: '请输入至少{0}个字符.'   
			    },
			    maxLength: {    
			        validator: function(value, param){    
			            return value.length <= param[0];    
			        },    
			        message: '请不要输入超过{0}个字符.'   
			    }     
			}); 

        	function save() {
        		if (!validateData()) {
	                return;
	            }

	            var user_name = $('#user_name').val();
	            var password = $('#password').val();
	            var role = $('#role').val();
	            var email = $('#email').val();
	            if (isNew) {
	            	if (!$('#password').validatebox('isValid')) {
	        			$.messager.alert('提示信息', '密码长度为1到40位');
	                	$('#password').focus();
	                	return;
	        		}
	            	$.ajax({
	                    url:"ajax_php/new_user.php",
	                    type:"POST",
	                    data:{
	                    	user_name:user_name,
	                    	password:password,
	                    	role:role,
	                    	email:email
	                	},
	                	success:function(){
	                		
			                window.parent.refreshTabs(); 
	                	}
                	});
	            }else {
	            	var change_field_array = [];
        			
        			var row = $('#dg').datagrid('getSelected');
		            
		            var old_user_name = row.user_name;
		            if (user_name != old_user_name) {
		                var change_field_row = [];
		                change_field_row[0] = 'user_name';
		                change_field_row[1] = user_name;
		                change_field_row[2] = old_user_name;
		                var length = change_field_array.length;
		                change_field_array[length] = change_field_row;
		            }

		            var old_role_id = row.role_id;
		            if (role != old_role_id) {
		                var change_field_row = [];
		                change_field_row[0] = 'role_id';
		                change_field_row[1] = role;
		                change_field_row[2] = old_role_id;
		                var length = change_field_array.length;
		                change_field_array[length] = change_field_row;
		            }

		            var old_email = row.email;
		            if (email != old_email) {
		                var change_field_row = [];
		                change_field_row[0] = 'user_mail';
		                change_field_row[1] = email;
		                change_field_row[2] = old_email;
		                var length = change_field_array.length;
		                change_field_array[length] = change_field_row;
		            }

		            if (change_field_array.length > 0) {
		            	$.ajax({
		            		url:"ajax_php/update_user.php",
		            		type:"POST",
		            		data:{
		            			user_id:row.id,
		            			change_field:change_field_array
		            		},
		            		success:function(){
	                		
				                window.parent.refreshTabs(); 
		                	}
		            	});
		            }
	            	
	            }
	            
	    		$('#dlg').dialog('close');        
        	}

        	function cancel() {
        		$('#dlg').dialog('close');
        	}

        	function validateData() {
        		if (!$('#user_name').validatebox('isValid')) {
        			$.messager.alert('提示信息', '用户名长度为1到32位，其中一个汉字是2位');
                	$('#user_name').focus();
                	return false;
        		}

        		

        		if (!$('#email').validatebox('isValid')) {
        			$.messager.alert('提示信息', '请输入正确格式的邮箱地址，最大长度100位，其中一个汉字是2位');
                	$('#email').focus();
                	return false;
        		}
        		return true;
        	}


        </script>
	</body>
</html>
<?php
	}

	function display_role_manage_page(){
?>
<!DOCTYPE html>
<html>
    <head>

    <meta charset="UTF-8">

    <title>中银开放平台-事项跟踪工具</title>
    <link rel="stylesheet" type="type/css" href="resources/jquery-easyui/themes/default/easyui.css">
    <link rel="stylesheet" type="type/css" href="resources/jquery-easyui/themes/icon.css">
    <link rel="stylesheet" type="type/css" href="resources/common/css/style.css">
    <script type="text/javascript" src="resources/jquery-easyui/jquery.min.js"></script>
    <script type="text/javascript" src="resources/jquery-easyui/jquery.easyui.min.js"></script>
    <script type="text/javascript" src="resources/jquery-easyui/locale/easyui-lang-zh_CN.js"></script>
	</head>
	<body>
		<div>
            <table id="dg" class="easyui-datagrid" width="100%" data-options="singleSelect:true, rownumbers:true,nowrap:false,pagination:true,pageSize:10,pageList:[5,10,15,20],toolbar:'#tb'">
                <thead>
                    <tr>
                        <th data-options="field:'id', hidden:true"></th>
                    	<th data-options="field:'check', checkbox:true"></th>
                        <th width="15%" data-options="field:'role_name'">角色名称</th>
                        <th width="15%" data-options="field:'role_priv'">角色权限</th>
                        
                    </tr>
                </thead>
            </table>   
        </div>
        <div id="tb" style="padding:2px 5px;">
        	<div>
                <a href="javascript:void(0)" class="easyui-linkbutton" onclick="handlerAdd()" data-options="iconCls:'icon-add'">新建</a>
				<a href="javascript:void(0)" class="easyui-linkbutton" onclick="handlerEdit()" data-options="iconCls:'icon-edit'">编辑</a>
				<a href="javascript:void(0)" class="easyui-linkbutton" onclick="handlerDelete()" data-options="iconCls:'icon-remove'">删除</a>
			</div>
			<div>
				角色名称：<input id="search_role_name" style="width:110px">
				<a href="javascript:void(0)" class="easyui-linkbutton" onclick="handlerSearch()" data-options="iconCls:'icon-search'">查询</a>
				
			</div>
		</div>

    	<div id="dlg" class="easyui-dialog" title="新建角色" style="width:auto;height:auto;" data-options="iconCls: 'icon-add',closed:true,modal:true,buttons: '#dlg-buttons'">
    		<table class="table_list" width="100%" border="0" align="center" cellpadding="0" cellspacing="1">
				
				<tr height="26">
					<td nowrap="nowrap">
						<div align="right" style="padding-right:2px;">
							角色名称：<font color="red">*</font>
						</div>
					</td>
					<td>
						<div align="left" style="padding-left:2px;">
	
							<input id="role_name" class="easyui-validatebox" data-options="required:true,validType:'length[1,32]'">
						</div>
					</td>
					
				</tr>
                <tr height="26">
					<td nowrap="nowrap">
						<div align="right" style="padding-right:2px;">
							角色权限：<font color="red">*</font>
						</div>
					</td>
					<td>
						<div align="left" style="padding-left:2px;">
							<select id="cc" class="easyui-combotree" style="width:200px;" multiple data-options="url:'ajax_php/get_privileges.php',required:true"></select>  
						</div>
					</td>
					
				</tr>
				
			</table>
		</div>
		<div id='dlg-buttons' style="padding-top:10px;text-align:center;">
                <a href="javascript:void(0)" class="easyui-linkbutton" onclick="save()" data-options="iconCls:'icon-save'">保存</a>
                <a href="javascript:void(0)" class="easyui-linkbutton" onclick="cancel()" data-options="iconCls:'icon-undo'">取消</a>
        </div>

        <script>


        	(function($){
	            function pagerFilter(data){
	                if ($.isArray(data)){   // is array
	                    data = {
	                        total: data.length,
	                        rows: data
	                    }
	                }
	                var dg = $(this);
	                var state = dg.data('datagrid');
	                var opts = dg.datagrid('options');
	                if (!state.allRows){
	                    state.allRows = (data.rows);
	                }
	                var start = (opts.pageNumber-1)*parseInt(opts.pageSize);
	                var end = start + parseInt(opts.pageSize);
	                data.rows = $.extend(true,[],state.allRows.slice(start, end));
	                return data;
	            }

	            var loadDataMethod = $.fn.datagrid.methods.loadData;
	            $.extend($.fn.datagrid.methods, {
	                clientPaging: function(jq){
	                    return jq.each(function(){
	                        var dg = $(this);
	                        var state = dg.data('datagrid');
	                        var opts = state.options;
	                        opts.loadFilter = pagerFilter;
	                        var onBeforeLoad = opts.onBeforeLoad;
	                        opts.onBeforeLoad = function(param){
	                            state.allRows = null;
	                            return onBeforeLoad.call(this, param);
	                        }
	                        dg.datagrid('getPager').pagination({
	                            onSelectPage:function(pageNum, pageSize){
	                                opts.pageNumber = pageNum;
	                                opts.pageSize = pageSize;
	                                $(this).pagination('refresh',{
	                                    pageNumber:pageNum,
	                                    pageSize:pageSize
	                                });
	                                dg.datagrid('loadData',state.allRows);
	                            }
	                        });
	                        $(this).datagrid('loadData', state.data);
	                        if (opts.url){
	                            $(this).datagrid('reload');
	                        }
	                    });
	                },
	                loadData: function(jq, data){
	                    jq.each(function(){
	                        $(this).data('datagrid').allRows = null;
	                    });
	                    return loadDataMethod.call($.fn.datagrid.methods, jq, data);
	                },
	                getAllRows: function(jq){
	                    return jq.data('datagrid').allRows;
	                }
	            })
	        })(jQuery);

        	$(function(){
        		$.ajax({
                    url:"ajax_php/get_roles.php",
                    type:"POST",
                    success:function(json){
                        // alert(json);      
                        $('#dg').datagrid('loadData', getData(json)).datagrid('clientPaging');
                        var rows = $('#dg').datagrid('getRows');
                        for (var i = 0; i < rows.length; i++) {
                        	$('#dg').datagrid('beginEdit',i);
                        };
                                
                    }
                });

        		
        	});

        	function getData(json){
	            var rows = [];
	            $.each($.parseJSON(json), function(idx,item){
	                
	                rows.push({
	                	id:item.user_id,
	                    user_name:item.user_name,
	                    password:item.user_passwd,
	                    role_id:item.role_id,
	                    role: item.role_name,
	                    email: item.user_mail,
	                });

	            });
	            return rows;
	        }
	        
			function handlerSearch(){
	        	
	            var condition_array = [];
	            
	            var search_user_name = $('#search_user_name').val();
	            if ('' != search_user_name) {
	                var condition_row = [];
	                condition_row[0] = 'user_name';
	                condition_row[1] = search_user_name;
	                var length = condition_array.length;
	                condition_array[length] = condition_row;   
	            }

	            var search_role = $('#search_role').val();
	            if ('' != search_role) {
	                var condition_row = [];
	                condition_row[0] = 'role_id';
	                condition_row[1] = search_role;
	                var length = condition_array.length;
	                condition_array[length] = condition_row;  

	            }

	            var search_email = $('#search_email').val();
	            if ('' != search_email) {
	                var condition_row = [];
	                condition_row[0] = 'user_mail';
	                condition_row[1] = search_email;
	                var length = condition_array.length;
	                condition_array[length] = condition_row;  

	            }


	            if (condition_array.length > 0) {
	                // alert(condition_array.length);
	                $.ajax({
	                    url:"ajax_php/search_user.php",
	                    type:"POST",
	                    data:{
	                        condition:condition_array
	                    },
	                    success:function(json){
	                        // alert(json);      
	                        $('#dg').datagrid('loadData', getData(json)).datagrid('clientPaging');
	                    }
	                }); 
	            }
            
        
	        }



	        function handlerAdd(){
	        	$('#dlg').dialog('open');
	        			
    			$('#user_name').val('');
    			$('#user_name').validatebox('validate');
    			
    			$('#password_block').show();
    			
    			$('#password').val('');
    			$('#password').validatebox('validate');

    			$('#role').val('1');
    			
    			$('#email').val('');
    			$('#email').validatebox('validate');
    			isNew = true;
	        }

	        function handlerEdit(){
	        	var row = $('#dg').datagrid('getSelected');
    			if (row != null)
    			{
    				$('#dlg').dialog('open');

        			$('#user_name').val(row.user_name);
        			$('#user_name').validatebox('validate');
        			
        			$('#password_block').hide();
        			
        			$('#role').val(row.role_id);
        			
        			
        			$('#email').val(row.email);
        			$('#email').validatebox('validate');
        			isNew = false;
    			} 
	        }

	        function handlerDelete(){
	        	var row = $('#dg').datagrid('getSelected');
    			if (row != null)
    			{
    				$.messager.confirm('确认','您确认想要删除该用户吗？删除后，该用户将无法恢复！',function(r){    
		                if (r){
		                    $.ajax({
			                    url:"ajax_php/delete_user.php",
			                    type:"POST",
			                    data:{
			                    	user_id:row.id
			                	},
			                	success:function(){
			                		
					                window.parent.refreshTabs(); 
			                	}
		                	});
		                }    
		            });
    				
    			}
	        }

	        function handlerResetPassword() {
	        	var row = $('#dg').datagrid('getSelected');
    			if (row != null)
    			{
    				$.messager.confirm('确认','您确认想要重置该用户的密码吗？',function(r){    
		                if (r){
		                    $.ajax({
			                    url:"ajax_php/reset_password.php",
			                    type:"POST",
			                    data:{
			                    	user_id:row.id
			                	},
			                	
		                	});
		                }    
		            });
    			} 
	        }

			$.extend($.fn.validatebox.defaults.rules, {    
			    minLength: {    
			        validator: function(value, param){    
			            return value.length >= param[0];    
			        },    
			        message: '请输入至少{0}个字符.'   
			    },
			    maxLength: {    
			        validator: function(value, param){    
			            return value.length <= param[0];    
			        },    
			        message: '请不要输入超过{0}个字符.'   
			    }     
			}); 

        	function save() {
        		if (!validateData()) {
	                return;
	            }

	            var user_name = $('#user_name').val();
	            var password = $('#password').val();
	            var role = $('#role').val();
	            var email = $('#email').val();
	            if (isNew) {
	            	if (!$('#password').validatebox('isValid')) {
	        			$.messager.alert('提示信息', '密码长度为1到40位');
	                	$('#password').focus();
	                	return;
	        		}
	            	$.ajax({
	                    url:"ajax_php/new_user.php",
	                    type:"POST",
	                    data:{
	                    	user_name:user_name,
	                    	password:password,
	                    	role:role,
	                    	email:email
	                	},
	                	success:function(){
	                		
			                window.parent.refreshTabs(); 
	                	}
                	});
	            }else {
	            	var change_field_array = [];
        			
        			var row = $('#dg').datagrid('getSelected');
		            
		            var old_user_name = row.user_name;
		            if (user_name != old_user_name) {
		                var change_field_row = [];
		                change_field_row[0] = 'user_name';
		                change_field_row[1] = user_name;
		                change_field_row[2] = old_user_name;
		                var length = change_field_array.length;
		                change_field_array[length] = change_field_row;
		            }

		            var old_role_id = row.role_id;
		            if (role != old_role_id) {
		                var change_field_row = [];
		                change_field_row[0] = 'role_id';
		                change_field_row[1] = role;
		                change_field_row[2] = old_role_id;
		                var length = change_field_array.length;
		                change_field_array[length] = change_field_row;
		            }

		            var old_email = row.email;
		            if (email != old_email) {
		                var change_field_row = [];
		                change_field_row[0] = 'user_mail';
		                change_field_row[1] = email;
		                change_field_row[2] = old_email;
		                var length = change_field_array.length;
		                change_field_array[length] = change_field_row;
		            }

		            if (change_field_array.length > 0) {
		            	$.ajax({
		            		url:"ajax_php/update_user.php",
		            		type:"POST",
		            		data:{
		            			user_id:row.id,
		            			change_field:change_field_array
		            		},
		            		success:function(){
	                		
				                window.parent.refreshTabs(); 
		                	}
		            	});
		            }
	            	
	            }
	            
	    		$('#dlg').dialog('close');        
        	}

        	function cancel() {
        		$('#dlg').dialog('close');
        	}

        	function validateData() {
        		if (!$('#user_name').validatebox('isValid')) {
        			$.messager.alert('提示信息', '用户名长度为1到32位，其中一个汉字是2位');
                	$('#user_name').focus();
                	return false;
        		}

        		

        		if (!$('#email').validatebox('isValid')) {
        			$.messager.alert('提示信息', '请输入正确格式的邮箱地址，最大长度100位，其中一个汉字是2位');
                	$('#email').focus();
                	return false;
        		}
        		return true;
        	}


        </script>
	</body>
</html>	
<?php
	}
?>