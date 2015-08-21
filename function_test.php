<?php
	//include('function\shadow_fns.php');
	//session_start();
	require_once('function/shadow_fns.php');
	
	echo $_SERVER['DOCUMENT_ROOT'];

	$change_field = array();
	array_push($change_field, array(
		'name' => 'role_id',
		'new_value' => '2'
		));
	array_push($change_field, array(
		'name' => 'user_name',
		'new_value' => 'jsdkalfjkl@dsf.com'
		));
	update_user ('4', $change_field);
	echo "sucess";
	// import users 
	// $conn = db_connect();
	// $data = new Spreadsheet_Excel_Reader();
	// $data->setOutputEncoding("utf-8");
	// $data->read('user.xls');
	// error_reporting(E_ALL ^ E_NOTICE);

	// for ($i=2; $i <= $data->sheets[0]['numRows']; $i++) { 
	// 	// import the setting to the users
	// 	$query = "insert into users values ('', '".
	// 		$data->sheets[0]['cells'][$i][1]."', sha1('".
	// 		$data->sheets[0]['cells'][$i][2]."'), '".
	// 		$data->sheets[0]['cells'][$i][3]."', '".
	// 		$data->sheets[0]['cells'][$i][4]."')";
		
	// 	$result = $conn->query("set names utf8");
	// 	echo $query."<br /><hr /><br />";
	// 	@$result = $conn->query($query);
	// 	if (!$result) {
	// 		throw new Exception("Error Processing Request");
	// 	}
	// }

	// $filename = "item_test.xlsx";
	// import_items($filename);
	// echo "import sucess";



	// $conn = db_connect();
	// $query = "select auto_id from auto_notify where item_id = '3' ";

	// //$result = $conn->query("set names utf8");
	
	// $result = $conn->query($query);
	// $result = db_result_to_array($result); 
	// //$result = $result->fetch_assoc();
	// print_r($result);


	// $_SESSION['current_item_id'] = '1';
	// $_SESSION['current_user_id'] = '4';
	//$change_notify = array();

	// array_push($change_notify, array(
	// 	'auto_id' => '3',
	// 	'item_id' => '2',
	// 	'auto_date' => '2015-08-01 00:30:11',
	// 	'auto_type' => 'DAILY',
	// 	'user_id' => '1'
	// 	));

	// array_push($change_notify, array(
	// 	'auto_id' => 'null',
	// 	'item_id' => '2',
	// 	'auto_date' => '2015-08-01 00:30:46',
	// 	'auto_type' => 'DAILY',
	// 	'user_id' => '1'
	// 	));

	// array_push($change_notify, array(
	// 	'auto_id' => '49',
	// 	'item_id' => '2',
	// 	'auto_date' => '2015-08-01 00:30:12',
	// 	'auto_type' => 'ONCE',
	// 	'user_id' => '2'
	// 	));

	// update_notify ($change_notify);



	// $condition = array();
	//$change_field = array();

	
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

	// array_push($condition, array(
	// 	'name' => 'start_time',
	// 	'value' => '2015-07-30 10:44:59'
	// 	));

	// array_push($condition, array(
	// 	'name' => 'end_time',
	// 	'value' => '2015-07-30 11:09:28'
	// 	));


	// array_push($change_field, array(
	// 	'name' => 'item_state',
	// 	'old_value' => 'PROCESSING',
	// 	'new_value' => 'FINISH'
	// 	));
	// array_push($change_field, array(
	// 	'name' => 'item_description',
	// 	'old_value' => 'sdkjfklasdfjkl',
	// 	'new_value' => 'PROCESSINGfasdfasdfasdfasd'
	// 	));

	// try {
	// 	log_item($change_field);

	// $result = display_selected_item();
	// foreach ($result as $key) {
	// 	echo "item_id: ".$key['item_id']."<br />";
	// 	echo "item_name: ".$key['item_name']."<br />";
	// 	echo "item_creator_id: ".$key['item_creator_id']."<br />";
	// 	echo "item_follower_id: ".$key['item_follower_id']."<br />";
	// 	echo "item_create_time: ".$key['item_create_time']."<br />";
	// 	echo "item_description: ".$key['item_description']."<br />";
	// 	echo "item_type_id: ".$key['item_type_id']."<br />";
	// 	echo "item_state: ".$key['item_state']."<br />";
	// 	echo "<hr />";
	// }

	// $result = get_items($condition);

	// foreach ($result as $key) {
	// 	echo "item_id: ".$key['item_id']."<br />";
	// 	echo "item_name: ".$key['item_name']."<br />";
	// 	echo "item_creator_id: ".$key['item_creator_id']."<br />";
	// 	echo "item_follower_id: ".$key['item_follower_id']."<br />";
	// 	echo "item_create_time: ".$key['item_create_time']."<br />";
	// 	echo "item_description: ".$key['item_description']."<br />";
	// 	echo "item_type_id: ".$key['item_type_id']."<br />";
	// 	echo "item_state: ".$key['item_state']."<br />";
	// 	echo "<hr />";
	// }
	
	// } catch(Exception $e) {
	// 	echo $e->getMessage();
	// }

	// if (unlink("..\\..\\..\\tmp\\sess_25236cgsikjl6epkujo4933727")) {
	// 	echo "fjasdklfjaskldf";
	// }
	// $conn = db_connect();
	// $query = "select * from admin_logs";

	// $result = $conn->query("set names utf8");
	
	// $result = $conn->query($query);
	// $result = db_result_to_array($result); 

	// foreach ($result as $key) {
	// 	echo $key['admin_log_id']." ".$key['admin_log_time']." ".
	// 		$key['admin_log_object']." ".$key['admin_log_object_id']."<br />";
	// }

	//---------------------------------------------------------------------------
	// 管理员操作日志展示方式
	// $result = get_admin_log('角色', '1');
	// foreach ($result as $key) {
	// 	echo $key['admin_log_time']." ".$key['admin_log_object']." ".$key['admin_log_object_id']." ";
	// 	$log_detail = get_admin_log_detail($key['admin_log_id']);
	// 	foreach ($log_detail as $key) {
	// 		echo $key['admin_log_field_name']." ".$key['admin_log_field_old']." 改成 "
	// 		.$key['admin_log_field_new']." ";
	// 	}
	// 	echo "<hr /><br />";
	// }

	// 日志记录展示方式
	// $result = get_log();
	// foreach ($result as $key) {
	// 	echo $key['item_id']." ".get_user_name($key['log_changer_id'])." ".$key['log_time']." ";
	// 	$log_detail = get_log_detail($key['log_id']);
	// 	foreach ($log_detail as $key) {
	// 		echo $key['log_field_name']." ".$key['log_field_old']." 改成 ".$key['log_field_new']." ";
	// 	}
	// 	echo "<hr /><br />";
	// }
	// echo "Function test! <br />";
	// $row = get_item_types();
	// foreach ($row as $key) {
	// 	echo $key['para_value_id']." ".$key['para_value_name']."<br />";
	// }

// 	session_destroy();
// 	echo session_id();
// 
?>