<?php
/**
 * User: ZX
 * Date: 2015/8/4
 * Time: 17:20
 */
    // require_once('function/item_fns.php');
    function display_main_page($user_name) {
?>
<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">

        <title>中银开放平台-事项跟踪工具</title>
        <link rel="stylesheet" type="type/css" href="resources/jquery-easyui/themes/default/easyui.css">
        <link rel="stylesheet" type="type/css" href="resources/jquery-easyui/themes/icon.css">
        <link rel="stylesheet" type="type/css" href="css/main.css">
        <script type="text/javascript" src="resources/jquery-easyui/jquery.min.js"></script>
        <script type="text/javascript" src="resources/jquery-easyui/jquery.easyui.min.js"></script>
        <script type="text/javascript" src="resources/common/js/tab.js"></script>
        <script type="text/javascript" src="resources/jquery-easyui/locale/easyui-lang-zh_CN.js"></script>

    </head>
    <body class="easyui-layout">
    	<form id="mainForm">
		<div data-options="region:'north',border:false" style="height:auto;padding:10px">
			<span style="float:right; padding-right:20px; padding-top: 6px;"> 
				<a href="javascript:changePwd()">修改密码</a> 
				<a href="javascript:logOut()">安全退出</a>
			</span>

			<span style="padding-left:10px; font-size: 14px;"> 欢迎: </span>
			<span style="padding-left:2px; padding-top: 10px; font-size: 20px;"> <?php echo $user_name ?> </span>
			<span style="padding-left:2px; font-size: 14px;"> 使用事项跟踪工具 </span> 
			<span style="padding-left:15px; font-size: 14px;"> </span>

		</div>
		<div data-options="region:'west',split:true,title:'系统菜单'" style="width:250px;">
			<div id="ea" class="easyui-accordion" data-options="fit:true,border:false">
				<div title="事项管理系统" style="padding:10px">
					<ul id="tt" class="easyui-tree">
						<li data-options="id:90">
							<span>事项管理</span>
							<ul>
								
								
							</ul>
						</li>
						<li data-options="id:92">
							<span>事项操作</span>
							<ul>
								<li data-options="id:93,iconCls:'icon-add',text:'新建事项',attributes:{url:'new_item_form.php'}">新建事项</li>
								<li data-options="id:94,iconCls:'icon-search',text:'查询事项',attributes:{url:'search_item_form.php'}">查询事项</li>
							</ul>
						</li>
					</ul>
				</div>
				<div title="系统管理" style="padding:10px">
					<ul id="manage" class="easyui-tree">
						<li>
							<span>系统管理</span>
							<ul>
								<li data-options="id:95,text:'角色管理',attributes:{url:'role_manage.php'}">角色管理</li>
<!-- 								<li data-options="id:96,text:'权限管理',attributes:{url:'privileges_manage.php'}">权限管理</li>-->
								<li data-options="id:97,text:'用户管理',attributes:{url:'user_manage.php'}">用户管理</li> 
								<li data-options="id:98,text:'参数管理',attributes:{url:'parameters_manage.php'}">参数管理</li>
								
							</ul>
						</li>
					</ul>
				</div>
				</div>

		</div>
		<div data-options="region:'south',border:false" style="height:auto;padding:10px;">
			<div class="footer">Copyright 2015 © 中国银行 版权所有 版本:V1.0</div>
		</div>
		<div data-options="region:'center'">
			<div id="tabs" class="easyui-tabs" data-options="fit:true,border:false,plain:true">
				<div title="欢迎使用" style="padding:10px">
					<p id="welcome-content">欢迎使用事项管理系统</p>
				</div>
			</div>
		</div>
		<div id="win" class="easyui-window" title="修改密码" style="width:300px;height:200px" data-options="modal:true,closed:true,collapsible:false,minimizable:false,maximizable:false">
			<form>
				<table width="100%" border="0" align="center" cellpadding="1" cellspacing="1" style="border-collapse: collapse;">
					<tr>
						<td width="45%" height="26">
							<div align="right" style="padding-right: 4px;">原始密码:</div>
						</td>
						<td>
							<div align="left" style="padding-left: 4px;">
								<input id="oldpwd" type="password" class="easyui-validatebox" data-options="required:true,validType:'length[1,40]'">							</div>
						</td>
					</tr>
					<tr>
						<td width="45%" height="26">
							<div align="right" style="padding-right: 4px;">新密码:</div>
						</td>
						<td>
							<div align="left" style="padding-left: 4px;">
								<input id="newpwd" type="password" class="easyui-validatebox" data-options="required:true,validType:'length[1,40]'">
							</div>
						</td>
					</tr>
					<tr>
						<td width="45%" height="26">
							<div align="right" style="padding-right: 4px;">重复新密码:</div>
						</td>
						<td>
							<div align="left" style="padding-left: 4px;">
								<input id="repeatnewpwd" type="password" class="easyui-validatebox" required=true validType="equals['#newpwd']">
							</div>
						</td>
					</tr>
					<tr>
						
						<td colspan="2">
							<div  align="center" style="padding-left: 4px;">
								<span id="msgTip" style="font-size:12px; color:red"></span>
							</div>
						</td>
					</tr>
				</table>
				<div align="center" style="padding-top: 8px;">
					<a href="#" id="saveBtn" name="rstBtn" class="easyui-linkbutton" data-options="iconCls:'icon-save'">保存</a> 
					<a href="#" id="cancelBth" name="rstBtn" class="easyui-linkbutton" data-options="iconCls:'icon-redo'">返回</a>
				</div>
			</form>
		</div>
		</form>
	</body>
	<script>

		function changePwd(){
			$('#win').window('open');
			$('#oldpwd').val('');
    		$('#oldpwd').validatebox('validate');
    		$('#newpwd').val('');
    		$('#newpwd').validatebox('validate');
    		$('#repeatnewpwd').val('');
    		$('#repeatnewpwd').validatebox('validate');
    		$("#msgTip").html('');
		}		

		function logOut() {
			with (document.forms["mainForm"]) {
				action = "logout.php";
				submit();
			}
		}

		$.extend($.fn.validatebox.defaults.rules, {    
		    equals: {    
		        validator: function(value, param){    
		            return value == $(param[0]).val();    
		        },    
		        message: '两次输入的密码不相同'   
		    }    
		});


		$(function(){
			$("#saveBtn").click(function() {
				if(!validateData()){
					return;
				}
				$.ajax({
					url:'ajax_php/update_password.php',
					type:'POST',
					data:{
						oldpwd:$('#oldpwd').val(),
						newpwd:$('#newpwd').val()
					},
					success : function(data) {
						// alert(data);
						// var d = eval("(" + data + ")");
						var newdata = data.replace(/\s/g,'');
						if (newdata == 'Youroldpasswdiswrong!') {
							$("#msgTip").html('您输入的原始密码错误，请重试！');
						}else{
							with (document.forms["mainForm"]) {
								action = "logout.php";
								submit();
							}
						}
						
					}
				});
	
			});
		
			$("#cancelBth").click(function() {
				$('#win').window('close');
			});

			$('#tt').tree({
				
				onClick:function(node){
					var id;
					if (node.id) {
						id = node.id;
						if (node.id > 99) {
							$.ajax({
								url:"ajax_php/change_current_item_id.php",
								type:"POST",
								data:{current_item_id:node.id},
							});
						}
						

					}else {
						id = 99;
					}
					if (node.attributes) {
						addTab(id,node.attributes.url, node.text);
					}
				}
			});

			$('#manage').tree({
				
				onClick:function(node){
					
					if (node.attributes) {
						addTab(node.id,node.attributes.url, node.text);
					}
				}
			});
			addListener();
			reloadItems();
		})

		function validateData() {
    		if (!$('#oldpwd').validatebox('isValid')) {
    			$.messager.alert('提示信息', '原有密码长度为1到40位，其中一个汉字是2位');
            	$('#oldpwd').focus();
            	return false;
    		}

    		if (!$('#newpwd').validatebox('isValid')) {
    			$.messager.alert('提示信息', '新密码长度为1到40位，其中一个汉字是2位');
            	$('#newpwd').focus();
            	return false;
    		}

    		if (!$('#repeatnewpwd').validatebox('isValid')) {
    			$.messager.alert('提示信息', '两次输入的密码不相同');
            	$('#repeatnewpwd').focus();
            	return false;
    		}

    		return true;
    	}

        function reloadItems()
		{
			var n = $('#tt').tree('find', 1);
			if (n) {
				$('#tt').tree('remove',n.target);
			}
			n = $('#tt').tree('find', 51);
			if (n) {
				$('#tt').tree('remove',n.target);
			}

			$.ajax({
				url:'ajax_php/get_user_privileges.php',
				type:'POST',
				data:{
					user_id:<?php echo $_SESSION['current_user_id'] ?>
				},
				success:function(json){
					// alert(json);
					var rows = [];
	            	$.each($.parseJSON(json), function(idx,item){
		                rows.push({
		                	priv_id:item.para_value_id,
		                    priv_name:item.para_value_name,
		                    
		                });

		            });

	            	var node = $('#tt').tree('find', 90);
					if (rows.length > 0) {
						
						if (node){
							
							$('#tt').tree('append',{
								parent:node.target,
								data:[{
									id:1,
									text:"未完成事务",
									
								}]
							});

							$('#tt').tree('append',{
								parent:node.target,
								data:[{
									id:51,
									text:"已完成事务",
									
								}]
							});
							
							
						}

						var adminFlag = false;

			            for (var i = 0; i < rows.length; i++) {
			            	
			            	row = rows[i];
			            	// alert(row['priv_name']);
			            	switch(row['priv_name'])
			            	{
			            		case '系统管理':
			            			adminFlag = true;
			            			break;
			            		default:
			            			$('#tt').tree('append',{
										parent:$('#tt').tree('find', 1).target,
										data:[{
											id:1 + parseInt(row['priv_id']),
											text:row['priv_name'],
											
										}]
									});
									$('#tt').tree('append',{
										parent:$('#tt').tree('find', 51).target,
										data:[{
											id:51 + parseInt(row['priv_id']),
											text:row['priv_name'],
											
										}]
									});
			            			break;

			            	}
			            	if (i == rows.length - 1 && !adminFlag) {
			            		var panel = $('#ea').accordion('getPanel','系统管理');
								if (panel) {
									$('#ea').accordion('remove','系统管理');
								}
			            	}
			            }

			            $.ajax({
				            url:"ajax_php/items_array.php",
				            type:"POST",
				            success:function(data){
				            	$.each($.parseJSON(data), function(idx,item){
				            		// alert(data);

				            		if (item.item_state == 'PROCESSING') {
				            			
										var manageNode = $('#tt').tree('find', 1 + parseInt(item.item_type_id));	   
										$('#tt').tree('append',{
											parent:manageNode.target,
											data:[{
												id:item.item_id,
												text:item.item_name,

												attributes:{
													url:'display_item.php'
												}
											}]
										});
										var newNode = $('#tt').tree('find', item.item_id);
										switch(item.item_priority_name)
										{
											case '最高级':
												newNode.target.style.background = 'red';
												break;
											case '较高级':
												newNode.target.style.color = 'orange';
												break;
											case '正常':
												newNode.target.style.color = 'green';
												break;
											case '较低级':
												newNode.target.style.color = 'blue';
												break;
											case '最低级':
												break;
										}
				            		}else if (item.item_state == 'FINISH') {
										var manageNode = $('#tt').tree('find', 51 + parseInt(item.item_type_id));
											   
										$('#tt').tree('append',{
											parent:manageNode.target,
											data:[{
												id:item.item_id,
												text:item.item_name,

												attributes:{
													url:'display_item.php'
												}
											}]
										});
				            		}
				            		
				            	});

				            }
				        });
						
					}else{
						if (node){
							
							$('#tt').tree('append',{
								parent:node.target,
								data:[{
									id:90,
									text:"无此权限",
									
								}]
							});
							var nn = $('#tt').tree('find', 93);
							if (nn) {
								$('#tt').tree('remove',nn.target);
							}
							nn = $('#tt').tree('find', 94);
							if (nn) {
								$('#tt').tree('remove',nn.target);
							}
							nn = $('#tt').tree('find', 92);
							$('#tt').tree('append',{
								parent:nn.target,
								data:[{
									id:93,
									text:"无此权限",
									
								}]
							});
						}
						var panel = $('#ea').accordion('getPanel','系统管理');
						if (panel) {
							$('#ea').accordion('remove','系统管理');
						}
						
					}

	                
				}

			});

			
            		 	
		}
	

    </script>
</html>
<?php
	}

?>



