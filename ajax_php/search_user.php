<?php
/**
 * User: ZX
 * Date: 2015/8/21
 * Time: 15:37
 */	
 	require_once('../function/shadow_fns.php');	
	session_start();

	try{
		$data = $_POST['condition'];
		
		for ($i=0; $i < count($data); $i++) { 
			$row = $data[$i];
			$new_row = array();
			$new_row['name'] = $row[0];
			$new_row['value'] = $row[1];
			$data[$i] = $new_row;
		}

		$users_array = get_users_with_condition($data);
		foreach ($users_array as &$user ) {
			$user['role_name'] = get_role_name($user['role_id']);
		}
		
		echo json_encode($users_array);
	}
	catch(Exception $e) {
		do_html_header('');
		echo $e->getMessage();
		do_html_footer();
		exit;
	}
?>