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

	if (count($result) == 1) {
		$row = $result[0];

		$row['item_follower_name'] = get_user_name($row['item_follower_id']);
		$row['item_creator_name'] = get_user_name($row['item_creator_id']);
		$row['item_type_name'] = get_item_type($row['item_type_id']);
		$row['item_priority_name'] = get_priority_name($row['item_priority_id']);
	}	

	if ($row['item_state'] == 'PROCESSING') {
		display_new_item($row, $auto_notify_result, $follow_mark_result);
	}else if ($row['item_state'] == 'FINISH') {
		display_finished_item($row, $auto_notify_result, $follow_mark_result);
	}
	
?>