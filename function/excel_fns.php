<?php
	require_once('shadow_fns.php');

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

				$query = "insert into items values ('', '".$data->sheets[0]['cells'][$i][1]."', '
					".get_user_id($data->sheets[0]['cells'][$i][2])."' , '
					".get_user_id($data->sheets[0]['cells'][$i][3])."' , '
					".$current_time."', '".$data->sheets[0]['cells'][$i][4]."', '
					".get_para_id($data->sheets[0]['cells'][$i][5])."' , ";
				if ($data->sheets[0]['cells'][$i][6] == '进行中') {
					$query .= "'PROCESSING')";
				} else {
					$query .= "'FINISH')";
				}

				$result = $conn->query("set names utf8");
				$result = $conn->query($query);
				if (!$result) {
					throw new Exception("Could not insert into the database");
				}

				$result = $conn->query("select max(item_id) from itmes");
				if ($result && ($result->num_rows > 0)) {
		            $row = $result->fetch_row();
		            $current_item_id = $row[0];
		        }

		        

			}
		} catch (Exception $e) {
			echo $e.getMessage();
		}
		

	}

	
?>