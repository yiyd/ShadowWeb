/**
 * Coder: ZX
 * Date: 2015/8/5
 * Time: 11.30
 */
 function addTab(title, url)
 {
 	if ($('#tabs').tabs('exists',title)) {
 		$('#tabs').tabs('select',title);
 	} else {
	 	var content = createFrame(url);
	 	$('#tabs').tabs('add',{
	 		title: title,
	 		content: content,
	 		closable: true 
	 	});
	 }
}

 function createFrame(url)
 {
 	var s = '<iframe scrolling="auto" id="frameId" frameborder="0" src="' + url + '" style="width:100%;height:100%"></iframe>';
 	return s;
 }