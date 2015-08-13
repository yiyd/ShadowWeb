<?php
/**
 * User: ZX
 * Date: 2015/8/12
 * Time: 16:08
 */	
 	require_once('../function/shadow_fns.php');	
	session_start();

	try{
		delete_selected_item();
	}
	catch(Exception $e) {
		do_html_header('');
		echo $e->getMessage();
		do_html_footer();
		exit;
	}
?>