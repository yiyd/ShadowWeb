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

		$item_array = get_items($data);
		foreach ($item_array as $key => &$value) {
			$value['item_creator'] = get_user_name($value['item_creator_id']);
			$value['item_follower'] = get_user_name($value['item_follower_id']);
			$value['item_type'] = get_item_type($value['item_type_id']);

		}
		echo json_encode($item_array);
	}
	catch(Exception $e) {
		do_html_header('');
		echo $e->getMessage();
		do_html_footer();
		exit;
	}
?>