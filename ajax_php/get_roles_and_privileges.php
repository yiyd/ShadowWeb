<?php
/**
 * User: ZX
 * Date: 2015/8/24
 * Time: 9:20
 */	
 	require_once('../function/shadow_fns.php');	
	session_start();
	
	try {
		
		$roles_array = get_roles();
		for ($i=0; $i < count($roles_array); $i++) { 
			$role_row = &$roles_array[$i];
			$role_id = $role_row['role_id'];
			$priv = get_privileges_by_id($role_id);
			$role_row['role_priv'] = '';
			foreach ($priv as $key => $value) {
				echo $value; 
			}
		}
		
	}
	catch(Exception $e) {
		do_html_header('');
		echo $e->getMessage();
		do_html_footer();
		exit;
	}
	echo json_encode($roles_array);
?>