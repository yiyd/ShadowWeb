<?php
/**
 * User: ZX
 * Date: 2015/8/25
 * Time: 8:50
 */	
 	require_once('../function/shadow_fns.php');	
	session_start();
	
	try {
		$item_types = get_item_types();
		
		$user_id = $_POST['user_id'];
		$role_id = get_role_id($user_id);
		$privs = get_privileges_by_id($role_id);
		foreach ($privs as $key => $value) {
			if ($value['priv_id'] == 1) {
				$row = array();
				$row['para_value_id'] = 0;
				$row['para_value_name'] = '系统管理';
				$count = count($item_types);
				$item_types[$count] = $row;
			}
		}
	}
	catch(Exception $e) {
		do_html_header('');
		echo $e->getMessage();
		do_html_footer();
		exit;
	}
	echo json_encode($item_types);

?> 