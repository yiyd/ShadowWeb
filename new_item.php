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
	$items['item_follow_mark'] = $_POST['item_follow_mark'];

	$date = $_POST['auto_notify_date'];
	$auto_type = $_POST['auto_notify_type'];
	$user_id = $_POST['auto_notify_user'];


	if ($items['item_name']) {
		try {
			new_one_item($items);
			set_notify($data, $auto_type, $user_id);
		}
		catch(Exception $e) {
			do_html_header();
			echo $e->getMessage();
			do_html_footer();
			exit;
		}
	}else
	{
			do_html_header();
			echo "请输入事项名称！";
			do_html_footer();
			exit;
	}

	do_html_header();

	$item = display_selected_item();
	display_item_form($item);

	do_html_footer();
?>