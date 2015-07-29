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

	$users_array = get_users();
	$item_types_array = get_item_types();
	display_new_item_form($users_array, $item_types_array);

	do_html_footer();
?>