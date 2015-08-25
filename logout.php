<?php
/**
 * Coder: ZX
 * Date: 2015/8/25
 * Time: 15:11
 */
	require_once('function/shadow_fns.php');
	session_start();

	try {
		logout();
	}
	catch(Exception $e) {
		do_html_header('');
		echo $e->getMessage();
		do_html_footer();
		exit;
	}
	

	header("Location:login.php");
	

?>