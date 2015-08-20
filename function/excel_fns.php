<?php
	require_once('shadow_fns.php');
	session_start();

	// upload the file from front-end 
	function upload_excel_file () {

	}
	
	// import the excel file to the database
	function import_excel_to_items ($filename) {
		$conn = db_connect();
		$conn->autocommit(false);

		$data = new Spreadsheet_Excel_Reader();
		// set encoding format
		$data->setOutputEncoding("utf-8");

		// check the file format. If uncorrect it will throw exception
		$data->read($filename);
		error_reporting(E_ALL ^ E_NOTICE);
		try {
			for ($i = 2 ; $i < $data->sheets[0]['numRows']; $i++) {
				$current_time = date("Y-m-d H:i:s");

				$items = array();

				// set the item array
				$items['item_name'] = $data->sheets[0]['cells'][$i][1];
				$items['item_creator_id'] = get_user_id($data->sheets[0]['cells'][$i][2]);
				$items['item_follower_id'] = get_user_id($data->sheets[0]['cells'][$i][3]);
				$items['item_description'] = $data->sheets[0]['cells'][$i][4];
				$items['item_type_id'] = get_para_id($data->sheets[0]['cells'][$i][5]);

				if ($data->sheets[0]['cells'][$i][6] == '进行中') {
					$items['item_state'] = 'PROCESSING';
				} else {
					$items['item_state'] = 'FINISH';
				}

				// new one item 
				new_one_item($items);

				// insert the item_follow_mark information
		        $follow_mark = $data->sheets[0]['cells'][$i][7];
		        $follow_mark_array = explode(";", $follow_mark);
		        foreach ($follow_mark_array as $key) {
		        	$mark_content = explode("|", $key);
		        	$query = "insert into item_follow_marks values ('', '".$_SESSION['current_item_id']."', 
		        		'".$mark_content[0]."', '".$mark_content[1]."', '".$mark_content[2]."')";
					$result = $conn->query("set names utf8");
					$result = $conn->query($query);
					if (!$result) {
						throw new Exception("Insert follow_mark Error!");
					}
		        }

		        // insert the notify into the auto_notify
		        $auto_notify = $data->sheets[0]['cells'][$i][8];
		        $auto_notify_array = explode(";", $auto_notify);
		        $auto_notifies = array();
		        foreach ($auto_notify_array as $key) {
		        	$notify_content = explode("|", $key);
		        	array_push($auto_notifies, array(
		        		'auto_type' => $notify_content[0],
		        		'auto_date' => $notify_content[2],
		        		'user_id' => get_user_id($notify_content[1])
		        	));
		        }

		        // call the setting function
		        set_notify($auto_notifies);
			}
		} catch (Exception $e) {
			echo $e.getMessage();
		}
		

	}

	
?>