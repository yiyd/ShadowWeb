<?php
/**
 * User: ZX
 * Date: 2015/8/21
 * Time: 14:28
 */	
 	require_once('../function/shadow_fns.php');	
	session_start();

	try{
		$user_id = $_POST['user_id'];
		delete_user($user_id);
	}
	catch(Exception $e) {
		do_html_header('');
		echo $e->getMessage();
		do_html_footer();
		exit;
	}
?>