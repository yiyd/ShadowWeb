<?php
/**
 * User: ZX
 * Date: 2015/8/20
 * Time: 11:30
 */	
 	require_once('../function/shadow_fns.php');	
	session_start();
	
	try{
		echo get_role_name($_POST['role_id']);
	}
	catch(Exception $e) {
		do_html_header('');
		echo $e->getMessage();
		do_html_footer();
		exit;
	}
?>