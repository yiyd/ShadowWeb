<?php
/**
 * Created by PhpStorm.
 * User: Toto
 * Date: 2015/7/24
 * Time: 16:08
 */

    function new_one_item($item_name, $item_follower, $item_description, $item_type_id, $item_state, $item_follow_mark) {
        $conn = db_connect();
		//starting one by turning off the autocomit
		$conn->autocommit(False);

        //get the current time
        $current_time = date("Y-m-d H:i:s");

        // insert the new item into the DB
        $query = "insert into items VALUES ('', '".$item_name."', '".$_SESSION['current_user'].",
                '".$item_follower."','".$current_time."', ".$item_description."', '".$item_type_id."',
                '".$item_state."', '".$item_follow_mark."')";

        $result = $conn->query($query);
        //end transaction
        $conn->commit();

        //get the new item_id
        $query = "select last_insert_id()";
        $result = $conn->query($query);
        if ($result && ($result->num_rows > 0)) {
            $row = $result->fetch_object();
            $_SESSION['current_item_id'] = $row->item_id;
        }

        $conn->autocommit(TRUE);
        if (!$result) {
            return false;
        } else {
            return true;
        }
    }

    //simple display function for test
    function display_selected_item (){
        $conn = db_connect();
        $query = "select * from items where item_creater = '".$_SESSION['curretn_user']."'";
        $result = $conn->query($query);
        if (!$result) {
            throw new Exception("Could not display the item.");
        }
        if ($result->num_rows == 0) return false;

        $row = db_result_to_array($result);
        return $row;

    }

    //search the items in the db with the input conditions
    function get_items($condition) {

    }

    //get the item_type
    function get_item_type($item_type_id) {
        $conn = db_connect();
        $result = $conn->query("select para_value_name from para_values where para_value_id = '".$item_type_id."'");
        if (!$result) return false;
        else {
            $temp = $result->fetch_object();
            $temp_para_value = $temp->para_value_name;
            return $temp_para_value;
        }
    }

    //get all the different item_types
    function get_item_types() {
        $conn = db_connect();
        $result = $conn->query("select para_value_id, para_value_name from para_values where para_id = '1'");
        if (!$result) {  
            return false;
        }
        if($result->num_rows == 0) return false;
        $result = db_result_to_array($result);
        return $result;
    }
   
    //update items(insert one rather than delete the original one)
    //$items is an array include all the original information
    function update_item($item_follower, $item_description, $item_type_id, $item_state,
                        $item_follow_mark, $items) {
		$conn = db_connect();
        $conn->autocommit(false);

        if (!(isset($item_follower))) {
            $item_follower = $items['item_follower'];
        }

        if (!(isset($item_type_id))) {
            $item_type_id = $items['item_type_id'];
        }

        if (!(isset($item_state))) {
            $item_state = $items['item_state'];
        }

        $query = "insert into items VALUES ('".$_SESSION['current_item_id']."', '".$items['item_name']."', 
                    '".$_SESSION['current_user']."', '".$item_follower."', '".$items['item_create_time']."', 
                    '".$item_description."', '".$item_type_id."', '".$item_state."', '".$item_follow_mark."')";
        $result = $conn->query($query);
        
        //end transaction
        $conn->commit();
        $conn->autocommit(TRUE);
        if (!$result) {
            return false;
        } else {
            return true;
        }

    }
?>