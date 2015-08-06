<?php
	include('function\shadow_fns.php');
	session_start();

	if (!login('yiyd', '123456')) {
		throw new Exception("Can not login!");
	}

	$condition = array();
	$change_field = array();


	// array_push($condition, array(
	// 	'name' => 'item_name',
	// 	'value' => '11'
	// 	));

	// array_push($condition, array(
	// 	'name' => 'item_description',
	// 	'value' => '11'
	// 	));

	// array_push($condition, array(
	// 	'name' => 'item_follow_mark',
	// 	'value' => '1'
	// 	));

	array_push($condition, array(
		'name' => 'start_time',
		'value' => '2015-07-30 10:44:59'
		));

	array_push($condition, array(
		'name' => 'end_time',
		'value' => '2015-07-30 11:09:28'
		));


	array_push($change_field, array(
		'name' => 'item_state',
		'new_value' => 'PROCESSING'
		));
	array_push($change_field, array(
		'name' => 'item_description',
		'new_value' => 'PROCESSINGfasdfasdfasdfasd'
		));

	try {
	update_item($change_field);

	$result = display_selected_item();
	foreach ($result as $key) {
		echo "item_id: ".$key['item_id']."<br />";
		echo "item_name: ".$key['item_name']."<br />";
		echo "item_creator_id: ".$key['item_creator_id']."<br />";
		echo "item_follower_id: ".$key['item_follower_id']."<br />";
		echo "item_create_time: ".$key['item_create_time']."<br />";
		echo "item_description: ".$key['item_description']."<br />";
		echo "item_type_id: ".$key['item_type_id']."<br />";
		echo "item_state: ".$key['item_state']."<br />";
		echo "<hr />";
	}

	$result = get_items($condition);

	foreach ($result as $key) {
		echo "item_id: ".$key['item_id']."<br />";
		echo "item_name: ".$key['item_name']."<br />";
		echo "item_creator_id: ".$key['item_creator_id']."<br />";
		echo "item_follower_id: ".$key['item_follower_id']."<br />";
		echo "item_create_time: ".$key['item_create_time']."<br />";
		echo "item_description: ".$key['item_description']."<br />";
		echo "item_type_id: ".$key['item_type_id']."<br />";
		echo "item_state: ".$key['item_state']."<br />";
		echo "<hr />";
	}
	
	} catch(Exception $e) {
		echo $e->getMessage();
	}
?>