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
            <table id="dg" class="easyui-datagrid" width="100%" data-options="rownumbers:true,nowrap:false,pagination:true,pageSize:50,pageList:[30,50,80,100],toolbar:toolbar">
                <thead>
                    <tr>
                        <th data-options="field:'id', hidden:true"></th>
                        <th data-options="field:'password', hidden:true"></th>
                        <th data-options="field:'check',checkbox:'true'">是否选择</th>
                        <th width="8%" data-options="field:'user_name'">用户名</th>
                        <th width="15%" data-options="field:'role'">角色</th>
                        <th width="65%" data-options="field:'email'">邮箱</th>
                    </tr>
                </thead>
            </table>   
        </div>
    	<div id="dlg" class="easyui-dialog" title="新增用户" style="width:auto;height:auto;" data-options="iconCls: 'icon-add',closed:true,modal:true,buttons: '#dlg-buttons'">
    		<table class="table_list" width="100%" border="0" align="center" cellpadding="0" cellspacing="1">
				<tr height="26">
					<td nowrap="nowrap">
						<div align="right" style="padding-right=2px;">
							用户名：<font color="red">*</font>
						</div>
					</td>
					<td>
						<div align="left" style="padding-left:2px;">
	
							<input id="user_name" class="easyui-validatebox" data-options="required:true,validType:'length[8,32]'">
						</div>
					</td>
					
				</tr>
                <tr height="26">
					<td nowrap="nowrap">
						<div align="right" style="padding-right=2px;">
							初始密码：<font color="red">*</font>
						</div>
					</td>
					<td>
						<div align="left" style="padding-left:2px;">
							<input id="password" type="password" class="easyui-validatebox" data-options="required:true,validType:'length[8,40]'">
						</div>
					</td>
					
				</tr>
				<tr height="26">
					<td nowrap="nowrap">
						<div align="right" style="padding-right=2px;">
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
						<div align="right" style="padding-right=2px;">
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
                                $('#dg').datagrid('beginEdit', 0);
                    }
                });

        	});

        	function getData(json){
	            var rows = [];
	            $.each($.parseJSON(json), function(idx,item){
	                
	                rows.push({
	                	id:item.user_id,
	                	password:item.user_passwd,
	                    user_name:item.user_name,
	                    role: item.role_name,
	                    email: item.user_mail,
	                });

	            });
	            return rows;
	        }
        	var toolbar = [{
	        		text:'新增',
	        		iconCls:'icon-add',
	        		handler:function(){
	        			$('#dlg').dialog('open');
	        		}

        		},'-',{
        			text:'编辑',
        			iconCls:'icon-edit',
	        		handler:function(){
	        			
	        		}
        		}
        	];

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
                		// $('#dg').datagrid('appendRow',{
		                //     user_name:user_name,
	                 //    	role:role,
	                 //    	email:email

		                // });
		                window.parent.refreshTabs(); 
                	}
                });
	    		$('#dlg').dialog('close');        
        	}

        	function cancel() {
        		$('#dlg').dialog('close');
        	}

        	function validateData() {
        		if (!$('#user_name').validatebox('isValid')) {
        			$.messager.alert('提示信息', '用户名长度为8到32位，其中一个汉字是2位');
                	$('#user_name').focus();
                	return false;
        		}

        		if (!$('#password').validatebox('isValid')) {
        			$.messager.alert('提示信息', '密码长度为8到40位');
                	$('#password').focus();
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