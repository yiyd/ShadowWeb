<?php
/**
 * Coder: ZX
 * Date: 2015/8/12
 * Time: 9:30
 */
	require_once('function/shadow_fns.php');

	session_start();
	
	try {
		$result = display_selected_item();
		$auto_notify_result = get_notify();
		$follow_mark_result = get_follow_mark();
	}
	catch(Exception $e) {
		do_html_header('');
		echo $e->getMessage();
		do_html_footer();
		exit;
	}

	if (count($result) == 1 && count($auto_notify_result) == 1) {
		$row = $result[0];
		$auto_notify_row = $auto_notify_result[0];

		$row['item_follower_name'] = get_user_name($row['item_follower_id']);
		$row['item_creator_name'] = get_user_name($row['item_creator_id']);
		$row['item_type_name'] = get_item_type($row['item_type_id']);
		

		$auto_notify_row['user_name'] = get_user_name($auto_notify_row['user_id']);
	}

	$follow_mark_row = '';
	if (count($follow_mark_result) == 1) {
		$follow_mark_row = $follow_mark_result[0];
		$follow_mark_row['mark_creator_name'] = get_user_name($follow_mark_row['mark_creator_id']);

	}

	display_new_item($row, $auto_notify_row, $follow_mark_row);
?>