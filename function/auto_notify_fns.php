<?php

	require_once('log_fns.php');

	//set the auto_notify ( NEW)
	// $auto_notifies is 2-D array including all the notify setting with different person
	// $auto_notifies['auto_id'], $auto_notifies['auto_date'], $auto_notifies['auto_type'], $auto_notifies['user_id']
	function set_notify ($auto_notifies) {
		//insert the notify setting into the db
		$conn = db_connect();
		$conn->autocommit(false);	

		//check all the input 
		if (is_array($auto_notifies) && (isset($_SESSION['current_item_id']))) {
			foreach ($auto_notifies as $auto) {
				// echo "<br />";
				// print_r($auto);
				$query = "insert into auto_notify values ('', '".$_SESSION['current_item_id']."', '".$auto['auto_date']."', 
				'".$auto['auto_type']."', '".$auto['user_id']."')";
				echo $query;
				$result = $conn->query($query);
				if (!$result) {
					throw new Exception("Could not connect to the db!");
				}

				// arrange the notify setting information into the array
				$change_field = array();			
				array_push($change_field, array(
					'name' => '提醒时间',
					'old_value' => 'null',
					'new_value' => $auto['auto_date']
					)
				);
				array_push($change_field, array(
					'name' => '提醒类型',
					'old_value' => 'null',
					'new_value' => $auto['auto_type']
					)
				);
				array_push($change_field, array(
					'name' => '提醒人',
					'old_value' => 'null',
					'new_value' => $auto['user_id']
					)
				);
				
				// log the NEW setting 
				log_item($change_field);
			}
			
			$conn->commit();
			$conn->autocommit(true);

		} else {
			throw new Exception("Input Error!");			
		}
	}

	// UPDATE the setting 
	// $auto_notifies['auto_id']
	// $auto_notifies['auto_date'], $auto_notifies['auto_type'], $auto_notifies['user_id']
	function update_notify ($auto_notifies) {
		//$flag = false;
		$conn = db_connect();
		$conn->autocommit(false);

		//check all the input 
		if (is_array($auto_notifies) && isset($_SESSION['current_item_id'])) {
			// get all the old setting related to the current item for check
			$result = $conn->query("select auto_id from auto_notify where item_id = '".$_SESSION['current_item_id']."'");
			$old_auto_id = $result->fetch_assoc();
			foreach ($old_auto_id as $old_id) {
				// set a flag for check if the setting need to be deleted
				$flag = false;
				foreach ($auto_notifies as $key) {

					// check the $auto_notifies['auto_id'] is null 
					if ($key['auto_id'] == 'null') {
						// need to be added 
						$new_notify = array();
						array_push($new_notify, array(
							'auto_id' => 'null',
							'item_id' => $key['item_id'],
							'auto_date' => $key['auto_date'],
							'auto_type' => $key['auto_type'],
							'user_id' => $key['user_id']
							));
						//print_r($new_notify);
						set_notify($new_notify);
						$key['auto_id'] = 'done';
						continue;
					}

					// need to be updated of do nothing
					if ($key['auto_id'] == $old_id) {
						// log the update information
						$change_field = array();
				
						$result = $conn->query("select * from auto_notify where auto_id = '".$old_id."'");
						if (!$result) {
							throw new Exception("Could not connect to the DB.");
						}
						$row = $result->fetch_assoc();

						// check the new and old setting
						if ($key['auto_date'] != $row['auto_date']) {
							array_push($change_field, array(
								'name' => '提醒时间',
								'old_value' => $row['auto_date'],
								'new_value' => $key['auto_date']
								)
							);

							$result = $conn->query("update auto_notify set auto_date = '".$key['auto_date']."' where auto_id = '".$old_id."'");
						}

						if ($key['auto_type'] != $row['auto_type']) {
							array_push($change_field, array(
								'name' => '提醒类型',
								'old_value' => $row['auto_type'],
								'new_value' => $key['auto_type']
								)
							);

							$result = $conn->query("update auto_notify set auto_type = '".$key['auto_type']."' where auto_id = '".$old_id."'");
						}

						if ($key['user_id'] != $row['user_id']) {
							array_push($change_field, array(
								'name' => '提醒人',
								'old_value' => 'null',
								'new_value' => $key['user_id']
								)
							);

							$result = $conn->query("update auto_notify set user_id = '".$key['user_id']."' where auto_id = '".$old_id."'");
						}
						// set the flag 
						$flag = true;

						// log the NEW setting 
						log_item($change_field);
						continue;
					}
				}

				// reset the $auto_notifies
				reset($auto_notifies);

				if (!$flag) {
					// need to be deleted
					$query = "delete from auto_notify where auto_id = '".$old_id."'";
					$result = $conn->query($query);
					if (!$result) {
						throw new Exception("Could not delete the notify setting!");
					}

					// log the delete information
					$change_field = array();
					array_push($change_field, array(
						'name' => '删除提醒',
						'old_value' => 'auto_id',
						'new_value' => $old_id
						)
					);
					// log the NEW setting 
					log_item($change_field);
				}
			}
			
			$conn->commit();
			$conn->autocommit(true);    	
		} else {
			throw new Exception("Input Error!");
		}
	}

	//get the auto_notify settings with $_session['item_id']
	function get_notify () {
		$conn = db_connect();
        $result = $conn->query("select * from auto_notify where item_id = '".$_SESSION['current_item_id']."'");
        if (!$result) {  
            throw new Exception("Could not connect to the db!");
        }
        if($result->num_rows == 0) {
        	throw new Exception("No notify settings with this item!");
        }
        $result = db_result_to_array($result);
        return $result;
	}

?>