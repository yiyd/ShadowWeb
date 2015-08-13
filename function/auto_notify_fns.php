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
				$query = "insert into auto_notify values ('', ".$_SESSION['current_item_id']."', '".$auto['auto_date']."', 
				'".$auto['auto_type']."', '".$auto['user_id']."')";
			
				$result = $conn->query($query);
				if (!$result) {
					throw new Exception("Could not connect to the db!");
				}

				// arrange the notify setting information into the array
				$change_field = array();
				foreach ($users as $key) {
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
				}
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
			
			// check the $auto_notifies['auto_id'] is null 
			if ($auto_notifies['auto_id'] == 'null') {
				// need to be added 

			}
  			//Delete the orginal setting related with current item
			$query = "delete from auto_notify where item_id = '".$_SESSION['current_item_id']."'";
			$result = $conn->query($query);
			if (!$result) {
				throw new Exception("Could not delete the original notify setting!");
			}
			
			$query = "insert into auto_notify values ('".$_SESSION['current_item_id']."', '".$."')"

			// $query = "update auto_notify set ";
	  //       foreach ($change_field as $row) {
	  //           if ($flag) {
	  //               $query .= ", ";
	  //           }
	  //           $temp = $row['name']." = '".$row['new_value']."'";
	  //           $query .= $temp;
	  //           $flag = true;
	  //       }
	  //       $query .= " where item_id = '".$_SESSION['current_item_id']."'";    

			$result = $conn->query($query);
			if (!$result) {
				throw new Exception("Could not connect to the db!");
			}	
			$conn->commit();
			$conn->autocommit(true);

			// log the update information
        	log_item($change_field);
        	
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