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
    </head>
    <body class="easyui-layout">

		<div data-options="region:'north',border:false" style="height:40px;padding:10px">待完善</div>
		<div data-options="region:'west',split:true,title:'系统菜单'" style="width:250px;">
			<div class="easyui-accordion" data-options="fit:true,border:false">
				<div title="事项管理系统" style="padding:10px">
					<ul id="tt" class="easyui-tree">
						<li>
							<span>事项管理系统</span>
							<ul>
								<li>日常工作事务</li>
								<li>生产问题事务</li>
								<li data-options="id:3">
									<span>事务管理</span>
									<ul>
										<li data-options="iconCls:'icon-add',text:'新建事项',attributes:{url:'new_item_form.php'}">新建事项</li>
										<li data-options="iconCls:'icon-search'">查询事项</li>
									</ul>
								</li>
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
					if (node.attributes) {
						$('#tabs').tabs('add',{    
						    title:node.text,    
						    href:node.attributes.url,    
						    closable:true,    
						       
						}); 

					}
				}
			});

			reloadItems();
		})
    	
        function reloadItems()
		{
		 // 	var manageNode = $('#tt').tree('find', 3);
			// $('#tt').tree('insert',{
			// 	before:manageNode.target,
			// 	<?php
			// 		$result = get_related_items();
			// 		$count = 0;
			// 		foreach($result as $key) {
						
			// 		}
			// 	?>
			// 	data:[{
			// 		id:11,
			// 		text:'test'
			// 	}
			// 	]
			// });
		}
	

    </script>
</html>
<?php
	}

?>



