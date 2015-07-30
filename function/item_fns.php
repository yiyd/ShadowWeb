<?php
/**
 * Created by PhpStorm.
 * User: Toto
 * Date: 2015/7/24
 * Time: 16:08
 */
    //insert one item into DB
    // $items is an array including all the inputs
    function new_one_item($items) {
        $conn = db_connect();
		//starting one by turning off the autocomit
		$conn->autocommit(False);

        //get the current time
        $current_time = date("Y-m-d H:i:s");

        // insert the new item into the DB
        $query = "insert into items VALUES ('', '".$items['item_name']."', '".$_SESSION['current_user_id']."',
                '".$items['item_follower_id']."','".$current_time."', '".$items['item_description']."', 
                '".$items['item_type_id']."',
                '".$items['item_state']."', '".$items['item_follow_mark']."')";

        $result = $conn->query($query);
        if (!$result) {
            throw new Exception("Could not insert the new item into the DB");
        }
        //end transaction
        $conn->commit();
        $conn->autocommit(TRUE);

        //get the new item_id
        $query = "select last_insert_id()";
        $result = $conn->query($query);
        if ($result && ($result->num_rows > 0)) {
            $row = $result->fetch_row();
            $_SESSION['current_item_id'] = $row[0];
        }
        else {
            throw new Exception("Could not get the new item_id!");  
        }
    }

    //simple display function for test
    function display_selected_item (){
        $conn = db_connect();
        $query = "select * from items where item_creator_id = '".$_SESSION['current_user_id']."' 
                    and item_id = '".$_SESSION['current_item_id']."'";
        $result = $conn->query($query);
        if (!$result) {
            throw new Exception("Could not connect to DB.");
        }
        if ($result->num_rows == 0) {
            throw new Exception("NO items records!");
        }

        $row = db_result_to_array($result);
        return $row;
    }

    //When user search all the items with confidtions
    //search the items in the db with the input conditions
    // $condition is an array
    // $condition['name'], $condition['value']
    function get_items($condition) {
        $flag = false;//

        $conn = db_connect();
        $query = "select * from items where ";
        foreach ($condition as $row) {
            //if the item_name is the search condition
            if ($row['name'] == "item_name") {
                if ($flag) $query .= " and ";
                $query .= "item_name = '".$row['value']."'";
                $flag =true;
            }

            //if the item_creator_id is the search condition
            if ($row['name'] == "item_creator_id") {
                if ($flag) $query .= " and ";
                $query .= "item_creator_id = '".$row['value']."'";
                $flag =true;
            }

            //if the item_creator_id is the search condition
            if ($row['name'] == "item_creator_id") {
                if ($flag) $query .= " and ";
                $query .= "item_creator_id = '".$row['value']."'";
                $flag =true;
            }

            //if the item_creator_id is the search condition
            if ($row['name'] == "item_creator_id") {
                if ($flag) $query .= " and ";
                $query .= "item_creator_id = '".$row['value']."'";
                $flag =true;
            }
        }
    }

    //get the item_type
    function get_item_type($item_type_id) {
        $conn = db_connect();
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

    //get all the different item_types
    function get_item_types() {
        $conn = db_connect();
        $result = $conn->query("select para_value_id, para_value_name from para_values where para_id = '1'");
        if (!$result) {  
            throw new Exception("Could not connect to the db!");
        }
        if($result->num_rows == 0) {
            throw new Exception("No item_types records!");
        }
        $result = db_result_to_array($result);
        return $result;
    }
   
    //update items
    // $change_field include three keys
    // $change_field['name'] $change_field['old_value'] $change_field['new_value'];
    function update_item($change_field) {
		$conn = db_connect();
        $conn->autocommit(false);

        // if (!(isset($item_follower_id))) {
        //     $item_follower_id = $items['item_follower_id'];
        // }

        // if (!(isset($item_type_id))) {
        //     $item_type_id = $items['item_type_id'];
        // }

        // if (!(isset($item_state))) {
        //     $item_state = $items['item_state'];
        // }

        // $query = "insert into items VALUES ('".$_SESSION['current_item_id']."', '".$items['item_name']."', 
        //             '".$_SESSION['current_user_id']."', '".$item_follower_id."', '".$items['item_create_time']."', 
        //             '".$item_description."', '".$item_type_id."', '".$item_state."', '".$item_follow_mark."')";
        
        $query = "update items set ";
        foreach ($change_field as $row) {
            $temp = $row['name']." = ".$row['new_value'];
            $query .= $temp;
        }
        $query .= "where item_id = '".$_SESSION['current_item_id']."'";    

        //end transaction
        $conn->commit();
        $conn->autocommit(TRUE);
        if (!$result) {
            throw new Exception("Could not connect to the db!");
        } else {
            return true;
        }

    }
?>