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
	}

	$notify_number = count($auto_notify_result);
	if ($notify_number > 0) {
		for ($i = 0; $i < $notify_number; $i ++) { 
			$auto_notify_row = $auto_notify_result[$i];
			$auto_notify_row['user_name'] = get_user_name($auto_notify_row['user_id']);
		}
	}

	$mark_number = count($follow_mark_result);
	if ($mark_number > 0) {
		for ($i = 0; $i < $mark_number; $i ++) { 
			$follow_mark_row = $follow_mark_result[i];
			$follow_mark_row['mark_creator_name'] = get_user_name($follow_mark_row['mark_creator_id']);
		}
	}	

	display_new_item($row, $auto_notify_result, $follow_mark_result);
?>