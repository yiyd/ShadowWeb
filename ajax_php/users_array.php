<?php
/**
 * User: ZX
 * Date: 2015/8/13
 * Time: 16:20
 */	
 	require_once('../function/shadow_fns.php');	
	session_start();
	
	try {
		
		$users_array = get_users();
		foreach ($users_array as &$user ) {
			$user['role_name'] = get_role_name($user['role_id']);
		}
		
	}
	catch(Exception $e) {
		do_html_header('');
		echo $e->getMessage();
		do_html_footer();
		exit;
	}
	echo json_encode($users_array);
?>