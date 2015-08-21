<?php

	require_once('shadow_fns.php');	
	
	// import the excel file to the database support excel5
	function import_excel_to_items ($filename) {
		$conn = db_connect();
		// $conn->autocommit(false);

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

		        // $conn->commit();
		        // $conn->autocommit(true);
			}
		} catch (Exception $e) {
			echo $e.getMessage();
		}
	}

	// import different types of excel into DB 
	// support excel5 excel2007
	function import_items ($filename) {
		$inputFileName = $filename;

		//check the file type 
		$inputFileType = PHPExcel_IOFactory::identify($inputFileName);

		// create a reader
		$objReader = PHPExcel_IOFactory::createReader($inputFileType);
		$objPHPExcel = $objReader->load($inputFileName);

		$sheet = $objPHPExcel->getSheet();
		$highestRow = $sheet->getHighestRow();
		$highestColumn = $sheet->getHighestColumn();

		try {
			for ($i = 2; $i <= $highestRow; $i++) { 
				$conn = db_connect();
				//$conn->autocommit(false);
				$str = '';
				
				for ($j = 'A'; $j <= $highestColumn; $j++) {
					// reader one row
					$str .= iconv('utf-8', 'utf-8', $objPHPExcel->getActiveSheet()->getCell("$j$i")->getValue()).'&&';
				}

				$strArray = explode("&&", $str);
				$items = array();

				// set the item array
				$items['item_name'] = $strArray[0];
				$items['item_creator_id'] = get_user_id($strArray[1]);
				$items['item_follower_id'] = get_user_id($strArray[2]);
				$items['item_description'] = $strArray[3];
				echo $strArray[4]."<br />";
				$items['item_type_id'] = get_para_id($strArray[4]);

				if ($strArray[5] == '进行中') {
					$items['item_state'] = 'PROCESSING';
				} else {
					$items['item_state'] = 'FINISH';
				}

				// new one item 
				new_one_item($items);

				echo "insert the item_follow_mark<br />";
				// insert the item_follow_mark information
		        $follow_mark = $strArray[6];
		        $follow_mark_array = explode(";", $follow_mark);
		        foreach ($follow_mark_array as $key) {
		        	$mark_content = explode("|", $key);
		        	$query = "insert into item_follow_marks values ('', '".$_SESSION['current_item_id']."', 
		        		'".$mark_content[0]."', '".get_user_id($mark_content[1])."', '".$mark_content[2]."')";
					$result = $conn->query("set names utf8");
					echo $query."<br />"; 
					$result = $conn->query($query);
					if (!$result) {
						throw new Exception("Insert follow_mark Error!");
					}
		        }

		        echo "insert the notify<br />";
		        // insert the notify into the auto_notify
		        $auto_notify = $strArray[7];
		        $auto_notify_array = explode("；", $auto_notify);
		        $auto_notifies = array();
		        foreach ($auto_notify_array as $key) {
		        	$notify_content = explode("|", $key);

		        	switch ($notify_content[0]) {
		        		case '单次提醒':
		        			$notify_content[0] = 'ONCE';
		        			break;

		        		case '每日提醒':
		        			$notify_content[0] = 'DAILY';
		        			break;

		        		case '每周提醒':
		        			$notify_content[0] = 'WEEKLY';
		        			break;

		        		case '每月提醒':
		        			$notify_content[0] = 'MONTHLY';
		        			break;

		        		case '每季度提醒':
		        			$notify_content[0] = 'QUARTERLY';
		        			break;

		        		case '每年提醒':
		        			$notify_content[0] = 'YEARLY';
		        			break;

		        		default:
		        			$notify_content[0] = 'ONCE';
		        			break;
		        	}

		        	array_push($auto_notifies, array(
		        		'auto_type' => $notify_content[0],
		        		'auto_date' => $notify_content[2],
		        		'user_id' => get_user_id($notify_content[1])
		        	));
		        }

		        print_r($auto_notifies);
		        // call the setting function
		        set_notify($auto_notifies);

		        // $conn->commit();
		        // $conn->autocommit(true);
			}
		} catch (Exception $e) {
			echo $e."<br />";
		}

	}
	
?>