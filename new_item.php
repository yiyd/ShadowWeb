<?php
/**
 * Coder: ZX
 * Date: 2015/7/29
 * Time: 11:00
 */
	require_once('function/shadow_fns.php');
	
	session_start();
	check_valid_user();

	$items = array();
	$items['item_name'] = $_POST['item_name'];
	$items['item_follower_id'] = $_POST['item_follower'];
	$items['item_description'] = $_POST['item_description'];
	$items['item_type_id'] = $_POST['item_type'];

	$items['item_state'] = "PROCESSING";

	$date = $_POST['auto_notify_date'];
	$auto_type = $_POST['auto_notify'];
	$users = array();

	$users[0] = $_POST['auto_notify_user'];


	
	try {
		new_one_item($items);
		set_notify($date, $auto_type, $users);
	}
	catch(Exception $e) {
		do_html_header('');
		echo $e->getMessage();
		do_html_footer();
		exit;
	}

	do_html_header('');
	echo "貌似成功";
	do_html_footer();
?>