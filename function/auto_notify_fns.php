<?php
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
			foreach ($$users as $user) {
				$query = "insert into auto_notify values ('".$_SESSION['current_item_id']."', '".$date."', 
				'".$auto_type."', '".$user."')";
			
				$result = $conn->query($query);
				if (!$result) {
					throw new Exception("Could not connect to the db!");
				}
			}
			
			$conn->commit();
			$conn->autocommit(true);
		} else {
			throw new Exception("Input Error!");			
		}
	}

	//UPDATE the setting 
	function update_notify ($date, $auto_type, $user_id) {
		$conn = db_connect();
		$conn->autocommit(false);

		if ($date && $auto_type && $user_id && (isset($_SESSION['current_item_id']))) {
			//check all the input 
			$query = "update auto_notify set auto_date = '".$date."', auto_type = '".$auto_type."', 
					user_id = '".$user_id."' where item_id = '".$_SESSION['current_item_id']."'";

			$result = $conn->query($query);
			if (!$result) {
				throw new Exception("Could not connect to the db!");
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