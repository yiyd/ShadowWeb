<?php
/**
 * User: ZX
 * Date: 2015/8/17
 * Time: 16:07
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

		echo json_encode(get_items($data));
	}
	catch(Exception $e) {
		do_html_header('');
		echo $e->getMessage();
		do_html_footer();
		exit;
	}
?>