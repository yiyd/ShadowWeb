<?php
/**
 * User: ZX
 * Date: 2015/8/25
 * Time: 15:14
 */	
 	require_once('../function/shadow_fns.php');	
	session_start();

	try{
		$oldpwd = $_POST['oldpwd'];
		$newpwd = $_POST['newpwd'];

		change_passwd($oldpwd, $newpwd);
		
	}
	catch(Exception $e) {
		// do_html_header('');
		echo strval($e->getMessage());
		// do_html_footer();
		exit;
	}
?>