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
	}
	catch(Exception $e) {
		do_html_header('');
		echo $e->getMessage();
		do_html_footer();
		exit;
	}

	if (!is_array($users_array)) {
		do_html_header('');
    	echo "<p>No users currently available</p>";
    	do_html_footer();
        exit;
    }
    if (!is_array($item_types_array)) {
    	do_html_header('');
        echo "<p>No item types currently available</p>";
        do_html_footer();
        exit;
    }
    
	display_new_item_form($users_array, $item_types_array);

?>