<?php
/**
 * User: ZX
 * Date: 2015/8/24
 * Time: 14:30
 */	
 	require_once('../function/shadow_fns.php');	
	session_start();

	$role_name = $_POST['role_name'];	
	$role_priv = $_POST['role_priv'];
		
	try{
		new_role($role_name,$role_priv);
	}
	catch(Exception $e) {
		do_html_header('');
		echo $e->getMessage();
		do_html_footer();
		exit;
	}
?>