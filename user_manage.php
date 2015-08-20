<?php
/**
 * Coder: ZX
 * Date: 2015/8/19
 * Time: 16:00
 */
	require_once('function/shadow_fns.php');
	session_start();

	check_valid_user();	

	try {
		$roles_array = get_roles();
	}
	catch(Exception $e) {
		do_html_header('');
		echo $e->getMessage();
		do_html_footer();
		exit;
	}

	display_user_manage_page($roles_array);

?>