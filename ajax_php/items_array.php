<?php
/**
 * User: ZX
 * Date: 2015/8/13
 * Time: 16:20
 */	
 	require_once('../function/shadow_fns.php');	
	session_start();
	
	try {
		
		$items_array = get_related_items();
		
	}
	catch(Exception $e) {
		do_html_header('');
		echo $e->getMessage();
		do_html_footer();
		exit;
	}
	echo json_encode($items_array);
?>