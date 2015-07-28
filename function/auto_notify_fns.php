<?php
	//set the auto_notify ( NEW)
	function set_notify ($date, $auto_type, $user_id) {
		//insert the notify setting into the db
		$conn = db_connect();
		$conn->autocommit(false);	

		if ($date && $auto_type && $user_id && (isset($_SESSION['current_item_id']))) {
			//check all the input 
			$query = "insert into auto_notify values ('".$_SESSION['current_item_id']."', '".$date."', 
				'".$auto_type."', '".$user_id."')";
			
			$result = $conn->query($query);
			if (!$result) return false;	
			$conn->commit();
			$conn->autocommit(true);
		} else {
			return false;
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
			if (!$result) return false;	
			$conn->commit();
			$conn->autocommit(true);
		} else {
			return false;
		}
	}

?>