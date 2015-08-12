<?php
/**
 * User: ZX
 * Date: 2015/8/12
 * Time: 14:41
 */	
 	require_once('../function/shadow_fns.php');	
	session_start();

	$item_follow_mark = $_POST['item_follow_mark'];
	$mark_create_time = $_POST['mark_create_time'];
	try{
		new_follow_mark($item_follow_mark, $mark_create_time);
	}
	catch(Exception $e) {
		do_html_header('');
		echo $e->getMessage();
		do_html_footer();
		exit;
	}
?>