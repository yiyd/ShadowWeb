/**
 * Coder: ZX
 * Date: 2015/8/12
 * Time: 10:00
 */
function updateTab(url, title, id)
{
	// 更新选择的面板的新标题和内容

	var tab = $('#tabs').tabs('getSelected');  // 获取选择的面板
	if (title.length > 12) {
		title = title.substr(0, 12) + "...";
	}
	$('#tabs').tabs('update', {
		tab: tab,
		options: {
			id:id,
			title: title,
			content: '<iframe scrolling="auto" frameborder="0" src="' + url + '" style="width:100%;height:100%"></iframe>'  // 新内容的URL
		}
	});

	window.parent.reloadItems();
}

function addTab(url, title)
{
	var content = '<iframe scrolling="auto" frameborder="0" src="' + url + '" style="width:100%;height:100%"></iframe>';
	if (title.length > 12) {
		title = title.substr(0, 12) + "...";
	}
	$('#tabs').tabs('add',{    
    	title:title,    
	   	content:content,    
	    closable:true,         
	});

	window.parent.reloadItems(); 
}

function addListener()
{
	$('#tabs').tabs({
		onSelect:function(title,index){
			var opts = $('#tabs').tabs('getTab',index).panel('options');
			if (opts.id) {
				$.ajax({
					url:"ajax_php/change_current_item_id.php",
					type:"POST",
					data:{current_item_id:opts.id},
				});
			}
		},
	});
}

function closeCurrentTab()
{
	var tab = $('#tabs').tabs('getSelected');
	var index = $('#tabs').tabs('getTabIndex',tab);
	$('#tabs').tabs('close', index);

	window.parent.reloadItems();
}