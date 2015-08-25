<?php
    //get the item_type
    function get_item_type($item_type_id) {
        $conn = db_connect();
        $result = $conn->query("set names utf8");
        $result = $conn->query("select para_value_name from para_values where para_value_id = '".$item_type_id."'");
        if (!$result) {
            throw new Exception("Could not connect to the db!");
        }
        else {
            $temp = $result->fetch_object();
            $temp_para_value = $temp->para_value_name;
            return $temp_para_value;
        }
    }

    // get user_name
    // $user_id $item_creator_id $item_follower_id $mark_creator_id
    function get_user_name($user_id) {
    	$conn = db_connect();
    	$query = "select user_name from users where user_id = '".$user_id."'";
        $result = $conn->query("set names utf8");
    	$result = $conn->query($query);
    	if (!$result) {
            throw new Exception("Could not connect to the db!");
        }
        if ($result->num_rows == 0) {
            //throw new Exception("No such user!");
        }

        $row = $result->fetch_row();
        return $row[0];
    }

    // get user_id 
    function get_user_id ($user_name) {
        $conn = db_connect();
        $query = "select user_id from users where user_name = '".trim($user_name)."'";
        $result = $conn->query("set names utf8");
        $result = $conn->query($query);
        if (!$result) {
            throw new Exception("Could not connect to the db!");
        }
        if ($result->num_rows == 0) {
            //throw new Exception("No such user!");
            //return false;
        }

        $row = $result->fetch_row();
        return $row[0];
    }

    // get item_name
    function get_item_name($item_id) {
    	$conn = db_connect();
    	$query = "select item_name from items where item_id = '".$item_id."'";
        $result = $conn->query("set names utf8");
    	$result = $conn->query($query);
    	if (!$result) {
            throw new Exception("Could not connect to the db!");
        }
        if ($result->num_rows == 0) {
            //throw new Exception("No such item!");
        }

        $row = $result->fetch_row();
        return $row[0];
    }

    // get role_name
    function get_role_name($role_id) {
    	$conn = db_connect();
    	$query = "select role_name from roles where role_id = '".$role_id."'";
        $result = $conn->query("set names utf8");
    	$result = $conn->query($query);
    	if (!$result) {
            throw new Exception("Could not connect to the db!");
        }
        if ($result->num_rows == 0) {
            //throw new Exception("No such role!");
        }

        $row = $result->fetch_row();
        return $row[0];
    }

    // get para_name
    function get_para_name($para_id) {
    	$conn = db_connect();
    	$query = "select para_name from parameters where para_id = '".$para_id."'";
        $result = $conn->query("set names utf8");
    	$result = $conn->query($query);
    	if (!$result) {
            throw new Exception("Could not connect to the db!");
        }
        if ($result->num_rows == 0) {
            //throw new Exception("No such para!");
        }

        $row = $result->fetch_row();
        return $row[0];

    }

    function get_para_id ($para_name) {
        $conn = db_connect();
        $query = "select para_id from para_values where para_value_name = '".trim($para_name)."'";
        $result = $conn->query("set names utf8");
        $result = $conn->query($query);
        if (!$result) {
            throw new Exception("Could not connect to the db!");
        }
        if ($result->num_rows == 0) {
            //throw new Exception("No such para_id!");
        }
        
        $row = $result->fetch_row();
        return $row[0];
    }

    // get privileges_name
    function get_priv_name($priv_id) {
    	$conn = db_connect();
    	$query = "select priv_name from privileges where priv_id = '".$priv_id."'";
        $result = $conn->query("set names utf8");
    	$result = $conn->query($query);
    	if (!$result) {
            throw new Exception("Could not connect to the db!");
        }
        if ($result->num_rows == 0) {
            //throw new Exception("No such priv!");
        }

        $row = $result->fetch_row();
        return $row[0];
    }

    function get_role_id ($user_id) {
        $conn = db_connect();
        $query = "select role_id from users where user_id = '".$user_id."'";
        $result = $conn->query("set names utf8");
        $result = $conn->query($query);
        if (!$result) {
            throw new Exception("Could not connect to the db!");
        }
        if ($result->num_rows == 0) {
            //throw new Exception("No such priv!");
        }

        $row = $result->fetch_row();
        return $row[0];
    }
?>