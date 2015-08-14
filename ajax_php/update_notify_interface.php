<?php
/**
 * User: ZX
 * Date: 2015/8/14
 * Time: 16:51
 */	
 	require_once('../function/shadow_fns.php');	
	session_start();

	try{
		$data = $_POST['notify'];

		for ($i=0; $i < count($data); $i++) { 
			$row = $data[$i];
			$new_row = array();
			$new_row['auto_id'] = $row[0];
			$new_row['auto_type'] = $row[1];
			$new_row['user_id'] = $row[2];
			$new_row['auto_date'] = $row[3];
			$data[$i] = $new_row;
		}

		update_notify($data);
	}
	catch(Exception $e) {
		do_html_header('');
		echo $e->getMessage();
		do_html_footer();
		exit;
	}
?>