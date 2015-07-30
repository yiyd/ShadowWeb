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

        $row = $result->fetch_assoc();
        return $row;
    }

    //When user search all the items with confidtions
    //search the items in the db with the input conditions
    // $condition is an array
    // $condition['name'], $condition['value']
    // 查询事项时用到的关键字定义，就是name
    //     事项名称 item_name （模糊查询）
    //     事项创建人 item_creator_id
    //     事项描述 item_description （模糊查询）
    //     事项跟踪人 item_follower_id
    //     事项类型 item_type_id 
    //     事项状态 item_state
    //     前后两个时间段 
    //         起始时间： start_time 
    //         终止时间： end_time
    //     事项跟踪备注 item_follow_mark (模糊查询)
    function get_items($condition) {
        $flag = false;//

        $conn = db_connect();
        $query = "select * from items where ";
        foreach ($condition as $row) {
            //if the item_name is the search condition
            if ($row['name'] == "item_name") {
                if ($flag) $query .= " and ";
                $query .= "item_name like '%".$row['value']."%'";
                $flag =true;
            }

            //if the item_creator_id is the search condition
            if ($row['name'] == "item_creator_id") {
                if ($flag) $query .= " and ";
                $query .= "item_creator_id = '".$row['value']."'";
                $flag =true;
            }

            //if the item_description is the search condition
            if ($row['name'] == "item_description") {
                if ($flag) $query .= " and ";
                $query .= "item_description like '%".$row['value']."%'";
                $flag =true;
            }

            //if the item_follower_id is the search condition
            if ($row['name'] == "item_follower_id") {
                if ($flag) $query .= " and ";
                $query .= "item_follower_id = '".$row['value']."'";
                $flag =true;
            }

            //if the item_type_id is the search condition
            if ($row['name'] == "item_type_id") {
                if ($flag) $query .= " and ";
                $query .= "item_type_id = '".$row['value']."'";
                $flag =true;
            }

            //if the item_state is the search condition
            if ($row['name'] == "item_state") {
                if ($flag) $query .= " and ";
                $query .= "item_state = '".$row['value']."'";
                $flag =true;
            }

            //if the item_follow_mark is the search condition
            if ($row['name'] == "item_follow_mark") {
                if ($flag) $query .= " and ";
                $query .= "item_follow_mark like '%".$row['value']."%'";
                $flag =true;
            }

            //if the time-duration is the search condition
            //compare the time with the start_time
            if ($row['name'] == "start_time") {
                if ($flag) $query .= " and ";
                $query .= "item_create_time > '".$row['value']."'";
                $flag =true;
            }

            //compare the time with the end_time
            if ($row['name'] == "end_time") {
                if ($flag) $query .= " and ";
                $query .= "item_create_time < '".$row['value']."'";
                $flag =true;
            }
        }

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