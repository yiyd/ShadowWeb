<?php
/**
 * Coder: ZX
 * Date: 2015/8/17
 * Time: 14:28
 */
	require_once('function/shadow_fns.php');
	session_start();

	try {
		$users_array = get_users();
		$item_types_array = get_item_types();
	}
	catch(Exception $e) {
		do_html_header('');
		echo $e->getMessage();
		do_html_footer();
		exit;
	}
    
	display_search_item_form($users_array, $item_types_array);

?>