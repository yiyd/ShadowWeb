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
			$priv_id_array = array();
			$role_row['role_priv'] = '';
			foreach ($priv as $key => $value) {
				array_push($priv_id_array, intval($value['priv_id']));
				$role_row['role_priv'] = $role_row['role_priv'].get_priv_name($value['priv_id']).' '; 
			}
			$role_row['role_priv_id'] = $priv_id_array;
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