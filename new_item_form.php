<?php
/**
 * Coder: ZX
 * Date: 2015/8/5
 * Time: 11:20
 */
	require_once('function/shadow_fns.php');
	session_start();

	try {
		$users_array = get_users();
		$item_types_array = get_item_types();
		$item_priorities_array = get_item_priorities();
	}
	catch(Exception $e) {
		do_html_header('');
		echo $e->getMessage();
		do_html_footer();
		exit;
	}
    
	display_new_item_form($users_array, $item_types_array, $item_priorities_array);

?>