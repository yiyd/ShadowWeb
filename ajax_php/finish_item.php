<?php
/**
 * User: ZX
 * Date: 2015/8/17
 * Time: 9:00
 */	
 	require_once('../function/shadow_fns.php');	
	session_start();

	try{
		finish_selected_item();
	}
	catch(Exception $e) {
		do_html_header('');
		echo $e->getMessage();
		do_html_footer();
		exit;
	}
?>