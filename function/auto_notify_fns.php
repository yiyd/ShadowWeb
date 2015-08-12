<?php

	require_once('log_fns.php');

	//set the auto_notify ( NEW)
	// $users is an array including all the persons needed to be notified
	// $users stores the user_id
	function set_notify ($date, $auto_type, $users) {
		//insert the notify setting into the db
		$conn = db_connect();
		$conn->autocommit(false);	

		if ($date && $auto_type && (isset($_SESSION['current_item_id'])) &&
				is_array($users)) {
			//check all the input 
			foreach ($users as $user) {
				$query = "insert into auto_notify values ('".$_SESSION['current_item_id']."', '".$date."', 
				'".$auto_type."', '".$user."')";
			
				$result = $conn->query($query);
				if (!$result) {
					throw new Exception("Could not connect to the db!");
				}
			}
			
			$conn->commit();
			$conn->autocommit(true);

			// log the NEW setting 

		} else {
			throw new Exception("Input Error!");			
		}
	}

	//UPDATE the setting 
	// $change_field['name'] $change_field['old_value'] $change_field['new_value'];
	function update_notify ($change_field) {
		$flag = false;
		$conn = db_connect();
		$conn->autocommit(false);

		if ($date && $auto_type && $user_id && $_SESSION['current_item_id']) {
			//check all the input 
			$query = "update auto_notify set ";
	        foreach ($change_field as $row) {
	            if ($flag) {
	                $query .= ", ";
	            }
	            $temp = $row['name']." = '".$row['new_value']."'";
	            $query .= $temp;
	            $flag = true;
	        }
	        $query .= " where item_id = '".$_SESSION['current_item_id']."'";    

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