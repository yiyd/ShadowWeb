<?php
/**
 * User: ZX
 * Date: 2015/8/25
 * Time: 15:20
 */	
 	require_once('../function/shadow_fns.php');	
	session_start();
	
	try {
		$user_name = $_POST['user_name'];
		$user_id = get_user_id($user_name);
		$val = intval($user_id);
	}
	catch(Exception $e) {
		do_html_header('');
		echo $e->getMessage();
		do_html_footer();
		exit;
	}
	echo $val;
?>