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
			<div class="easyui-accordion" data-options="fit:true,border:false">
				<div title="事项管理系统" style="padding:10px">
					<ul id="tt" class="easyui-tree">
						<li>
							<span>事项管理</span>
							<ul>
								<li data-options="id:90">无此权限</li>
								<!-- <li data-options="id:91">
									<span>未完成事务</span>
									<ul>
										<li data-options="id:11">日常工作事务</li>
										<li data-options="id:12">生产问题事务</li>
									</ul>
								</li> -->
								
								<!-- <li data-options="id:92">
									<span>已完成事务</span>
									<ul>
										<li data-options="id:21">已完成日常工作事务</li>
										<li data-options="id:22">已完成生产问题事务</li>
									</ul>
								</li> -->
							</ul>
						</li>
						<li>
							<span>事项操作</span>
							<ul>
								<li data-options="id:93,iconCls:'icon-add',text:'新建事项',attributes:{url:'new_item_form.php'}">新建事项</li>
								<li data-options="id:94,iconCls:'icon-search',text:'查询事项',attributes:{url:'search_item_form.php'}">查询事项</li>
							</ul>
						</li>
					</ul>
				</div>
				<div id="manageDiv" title="系统管理" style="padding:10px">
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
			
			$('#tt').tree('remove', $('#tt').tree('find', 1).target);
			$('#tt').tree('remove', $('#tt').tree('find', 2).target);
			$('#tt').tree('remove', $('#tt').tree('find', 4).target);			
			var node = $('#tt').tree('find', 3);
			if (node){
				$('#tt').tree('insert', {
					before: node.target,
					data: [{
					    	id: 1,
					    	text: '日常工作事务'
				    }]
				});
				$('#tt').tree('insert', {
					before: node.target,
					data: [{
					    	id: 2,
					    	text: '生产问题事务'
				    }]
				});
				$('#tt').tree('insert', {
					after: node.target,
					data: [{
					    	id: 4,
					    	text: '已完成事务',
					    	state: 'closed',
					    	children: [{
					    		id: 41,
					    		text: '已完成日常工作事务',
					    	},{
					    		id: 42,
					    		text: '已完成生产问题事务',
					    	}
					    	]
				    }]
				});
			}

            $.ajax({
	            url:"ajax_php/items_array.php",
	            type:"POST",
	            success:function(data){
	            	$.each($.parseJSON(data), function(idx,item){
	            		// alert(data);

	            		if (item.item_state == 'PROCESSING') {
	            			
							var manageNode = $('#tt').tree('find', item.item_type_id);	   
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
							var manageNode = $('#tt').tree('find', '4' + item.item_type_id);	   
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
            
            
		 	
		}
	

    </script>
</html>
<?php
	}

?>



