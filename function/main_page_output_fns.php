<?php
/**
 * User: ZX
 * Date: 2015/8/4
 * Time: 17:20
 */
    // require_once('function/item_fns.php');
    function display_main_page() {
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
    </head>
    <body class="easyui-layout">

		<div data-options="region:'north',border:false" style="height:40px;padding:10px">待完善</div>
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
		<div data-options="region:'south',border:false" style="height:30px;padding:10px;">待完善</div>
		<div data-options="region:'center'">
			<div id="tabs" class="easyui-tabs" data-options="fit:true,border:false,plain:true">
				<div title="欢迎使用" style="padding:10px">
					<p id="welcome-content">欢迎使用事项管理系统</p>
				</div>
			</div>
		</div>

	</body>
	<script>
		

		$(function(){

			$('#tt').tree({
				
				onClick:function(node){
					var id;
					if (node.id) {
						id = node.id;
						$.ajax({
							url:"ajax_php/change_current_item_id.php",
							type:"POST",
							data:{current_item_id:node.id},
						});

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
											id:1 + row['priv_id'],
											text:row['priv_name'],
											
										}]
									});
									$('#tt').tree('append',{
										parent:$('#tt').tree('find', 51).target,
										data:[{
											id:51 + row['priv_id'],
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
				            			
										var manageNode = $('#tt').tree('find', '1' + item.item_type_id);	   
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
				            		}else if (item.item_state == 'FINISH') {
										var manageNode = $('#tt').tree('find', '51' + item.item_type_id);	   
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
			// $.ajax({
			// 	url:'ajax_php/get_user_privileges.php',
			// 	type:'POST',
			// 	data:{
			// 		user_id:<?php echo $_SESSION['current_user_id'] ?>
			// 	},
			// 	success:function(json){
			// 		// alert(json);
			// 		var rows = [];
	  //           	$.each($.parseJSON(json), function(idx,item){
		 //                rows.push({
		 //                	priv_id:item.priv_id,
		 //                    priv_name:item.priv_name,
		                    
		 //                });

		 //            });

	  //           	var node = $('#tt').tree('find', 90);
			// 		if (rows.length > 0) {
						
			// 			if (node){
							
			// 				$('#tt').tree('append',{
			// 					parent:node.target,
			// 					data:[{
			// 						id:1,
			// 						text:"未完成事务",
									
			// 					}]
			// 				});

			// 				$('#tt').tree('append',{
			// 					parent:node.target,
			// 					data:[{
			// 						id:51,
			// 						text:"已完成事务",
									
			// 					}]
			// 				});
							
							
			// 			}
						
			// 		}else{
			// 			if (node){
							
			// 				$('#tt').tree('append',{
			// 					parent:node.target,
			// 					data:[{
			// 						id:90,
			// 						text:"无此权限",
									
			// 					}]
			// 				});
			// 			}
			// 			$('#ea').accordion('remove','系统管理');
			// 		}

			// 		var adminFlag = false;

		 //            for (var i = 0; i < rows.length; i++) {
		            	
		 //            	row = rows[i];
		 //            	switch(row['priv_name'])
		 //            	{
		 //            		case '系统管理':
		 //            			adminFlag = true;
		 //            			break;
		 //            		default:
		 //            			$('#tt').tree('append',{
			// 						parent:$('#tt').tree('find', 1).target,
			// 						data:[{
			// 							id:1 + row['priv_id'],
			// 							text:row['priv_name'],
										
			// 						}]
			// 					});
			// 					$('#tt').tree('append',{
			// 						parent:$('#tt').tree('find', 51).target,
			// 						data:[{
			// 							id:51 + row['priv_id'],
			// 							text:row['priv_name'],
										
			// 						}]
			// 					});
		 //            			break;

		 //            	}
		 //            	if (i == rows.length - 1 && !adminFlag) {
		 //            		$('#ea').accordion('remove','系统管理');
		 //            	}
		 //            }

	  //               $.ajax({
			//             url:"ajax_php/items_array.php",
			//             type:"POST",
			//             success:function(data){
			//             	$.each($.parseJSON(data), function(idx,item){
			//             		// alert(data);

			//             		if (item.item_state == 'PROCESSING') {
			            			
			// 						var manageNode = $('#tt').tree('find', item.item_type_id);	   
			// 						$('#tt').tree('append',{
			// 							parent:manageNode.target,
			// 							data:[{
			// 								id:item.item_id,
			// 								text:item.item_name,
			// 								attributes:{
			// 									url:'display_item.php'
			// 								}
			// 							}]
			// 						});
			//             		}else if (item.item_state == 'FINISH') {
			// 						var manageNode = $('#tt').tree('find', '4' + item.item_type_id);	   
			// 						$('#tt').tree('append',{
			// 							parent:manageNode.target,
			// 							data:[{
			// 								id:item.item_id,
			// 								text:item.item_name,
			// 								attributes:{
			// 									url:'display_item.php'
			// 								}
			// 							}]
			// 						});
			//             		}
			            		
			//             	});

			//             }
			//         });
			// 	}

			// });
			
            		 	
		}
	

    </script>
</html>
<?php
	}

?>



