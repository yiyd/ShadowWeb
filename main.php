<?php
/**
 * Coder: ZX
 * Date: 2015/7/28
 * Time: 16:00
 */
	require_once('function/shadow_fns.php');
	session_start();

	check_valid_user();	
	try {
		$user_name = get_user_name($_SESSION['current_user_id']);		
	}
	catch(Exception $e) {
		do_html_header('');
		echo $e->getMessage();
		do_html_footer();
		exit;
	}
	display_main_page($user_name);

?>