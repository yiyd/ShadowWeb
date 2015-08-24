<?php
/**
 * User: ZX
 * Date: 2015/8/24
 * Time: 13:22
 */	
 	require_once('../function/shadow_fns.php');	
	session_start();

	try{
		$role_id = $_POST['role_id'];
		delete_role($role_id);
	}
	catch(Exception $e) {
		do_html_header('');
		echo $e->getMessage();
		do_html_footer();
		exit;
	}
?>