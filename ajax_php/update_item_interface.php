<?php
/**
 * User: ZX
 * Date: 2015/8/14
 * Time: 11:24
 */	
 	require_once('../function/shadow_fns.php');	
	session_start();

	try{
		$data = $_POST['change_field'];
		
		for ($i=0; $i < count($data); $i++) { 
			$row = $data[$i];
			$new_row = array();
			$new_row['name'] = $row[0];
			$new_row['new_value'] = $row[1];
			$new_row['old_value'] = $row[2];
			$data[$i] = $new_row;
		}

		update_item($data);
	}
	catch(Exception $e) {
		do_html_header('');
		echo $e->getMessage();
		do_html_footer();
		exit;
	}
?>