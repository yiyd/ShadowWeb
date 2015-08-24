<?php
/**
 * User: ZX
 * Date: 2015/8/24
 * Time: 11:11
 */	
 	require_once('../function/shadow_fns.php');	
	session_start();
	
	try {
		$data = $_POST['condition'];
		
		for ($i=0; $i < count($data); $i++) { 
			$row = $data[$i];
			$new_row = array();
			$new_row['name'] = $row[0];
			$new_row['value'] = $row[1];
			$data[$i] = $new_row;
		}

		$roles_array = get_roles_with_condition($data);
		for ($i=0; $i < count($roles_array); $i++) { 
			$role_row = &$roles_array[$i];
			$role_id = $role_row['role_id'];
			$priv = get_privileges_by_id($role_id);
			$role_row['role_priv'] = '';
			foreach ($priv as $key => $value) {
				$role_row['role_priv'] = $role_row['role_priv'].get_priv_name($value['priv_id']).' '; 
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