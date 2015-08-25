<?php
/**
 * User: ZX
 * Date: 2015/8/25
 * Time: 8:50
 */	
 	require_once('../function/shadow_fns.php');	
	session_start();
	
	try {
		$user_id = $_POST['user_id'];
		$privs_array = get_privileges();
		for ($i=0; $i < count($privs_array); $i++) { 
			$row = $privs_array[$i];
			$new_row = array();
			$new_row['id'] = $row['priv_id'];
			$new_row['text'] = $row['priv_name'];			
			$privs_array[$i] = $new_row;
		}
	}
	catch(Exception $e) {
		do_html_header('');
		echo $e->getMessage();
		do_html_footer();
		exit;
	}
	echo json_encode($privs_array);
?>