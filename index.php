<?php
/**
 * Coder: ZX
 * Date: 2015/7/28
 * Time: 16:00
 */
	require_once('function/shadow_fns.php');
	session_start();

	$username = $_POST['username'];
	$password = $_POST['password'];

	if ($username && $password) {
		try {
			login($username, $password);

			$_SESSION['current_user'] = $username;
		}
		catch(Exception $e) {
			do_html_header();
			echo $e->getMessage();
			do_html_footer();
			exit;
		}
	}

	do_html_header();
	check_valid_user();

	display_main();

	do_html_footer();
?>