<?php
/**
 * Coder: ZX
 * Date: 2015/8/12
 * Time: 16:45
 */
	require_once('function/shadow_fns.php');

	session_start();
	
	try {
		$result = display_selected_item();
		$auto_notify_result = get_notify();
		$follow_mark_result = get_follow_mark();
		$users_array = get_users();
		$item_types_array = get_item_types();
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

		$row['item_creator_name'] = get_user_name($row['item_creator_id']);
	}

	display_update_item($users_array, $item_types_array, $row, $auto_notify_row, $follow_mark_result);
?>