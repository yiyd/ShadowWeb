<?php
/**
 * User: ZX
 * Date: 2015/8/20
 * Time: 9:30
 */	
 	require_once('../function/shadow_fns.php');	
	session_start();

	$new_user = array();
	$new_user['user_name'] = $_POST['user_name'];	
	$new_user['user_passwd'] = $_POST['password'];	
	$new_user['role_id'] = $_POST['role'];	
	$new_user['user_mail'] = $_POST['email'];
		
	try{
		new_user($new_user);
	}
	catch(Exception $e) {
		do_html_header('');
		echo $e->getMessage();
		do_html_footer();
		exit;
	}
?>