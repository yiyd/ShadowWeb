<?php
/**
 * Created by PhpStorm.
 * User: Toto
 * Date: 2015/7/24
 * Time: 16:08
 */
    //insert one item into DB
    // $items is an array including all the inputs
    require_once('log_fns.php');

    //-----------------------------------------ITEM_OPERATIONS-------------------------------------------
    //----------------------------------------------------------------------------------------------------
    function new_one_item($items) {
        $conn = db_connect();
		//starting one by turning off the autocomit
		$conn->autocommit(false);

        //get the current time
        $current_time = date("Y-m-d H:i:s");

        // insert the new item into the DB
        if (isset($items['item_creator_id'])) {
            $query = "insert into items VALUES ('', '".addslashes($items['item_name'])."', '".$items['item_creator_id']."',
                '".$items['item_follower_id']."','".$current_time."', '".addslashes($items['item_description'])."', 
                '".$items['item_type_id']."', '".$items['item_state']."', '".$items['item_priority_id']."')";
        } else {
            $query = "insert into items VALUES ('', '".addslashes($items['item_name'])."', '".$_SESSION['current_user_id']."',
                '".$items['item_follower_id']."','".$current_time."', '".addslashes($items['item_description'])."', 
                '".$items['item_type_id']."', '".$items['item_state']."', '".$items['item_priority_id']."')";
        }
        
        $result = $conn->query("set names utf8");
        $result = $conn->query($query);
        if (!$result) {
            throw new Exception("Could not insert the new item into the DB.");
        }

        //get the new item_id
        $query = "select max(item_id) from items";
        $result = $conn->query($query);
        if ($result && ($result->num_rows > 0)) {
            $row = $result->fetch_row();
            $_SESSION['current_item_id'] = $row[0];
        }
        else {
            throw new Exception("Could not get the new item_id!");  
        }
        //end transaction
        $conn->commit();
        $conn->autocommit(TRUE);

        if (isset($items['item_creator_id'])) {
            $current_user = $items['item_creator_id'];
        } else {
            $current_user = $_SESSION['current_user_id'];
        }
        //Arrange the $items array to the $change_field
        //$change_field['name'] $change_field['old_value'] $change_field['new_value'];
        $change_field = array(
            array(
                'name' => '事项名称',
                'old_value' => '空值',
                'new_value' => $items['item_name']  
            ),
            array(
                'name' => '事项创建人',
                'old_value' => '空值',
                'new_value' =>  $current_user
            ),
            array(
                'name' => '事项跟踪人',
                'old_value' => '空值',
                'new_value' => $items['item_follower_id']   
            ),
            array(
                'name' => '创建时间',
                'old_value' => '空值',
                'new_value' => $current_time   
            ),
            array(
                'name' => '事项描述',
                'old_value' => '空值',
                'new_value' => $items['item_description']   
            ),
            array(
                'name' => '事项类型',
                'old_value' => '空值',
                'new_value' => $items['item_type_id']    
            ),
            array(
                'name' => '事项状态',
                'old_value' => '空值',
                'new_value' => $items['item_state'] 
            ),
            array(
                'name' => '事项优先级',
                'old_value' => '空值',
                'new_value' => $items['item_priority_id'] 
            )
        );

        // LOG the NEW information
        if (isset($items['item_creator_id'])) {
            $_SESSION['current_user_id'] = $current_user;
        } 
        log_item($change_field);
    }

    // delete the selected item
    function delete_selected_item () {
        $conn = db_connect();
        $query = "delete from items where item_id = '".$_SESSION['current_item_id']."'";
        $query1 = "delete from item_follow_marks where item_id = '".$_SESSION['current_item_id']."'";
        $query2 = "delete from auto_notify where item_id = '".$_SESSION['current_item_id']."'";
        $result = $conn->query($query);
        $result1 = $conn->query($query1);
        $result2 = $conn->query($query2);

        if (!$result || !$result1 || !$result2) {
            throw new Exception("Delete Error!");
        }

        // log the delete information
        $change_field = array();
        array_push($change_field, array(
            'name' => '删除事项',
            'old_value' => $_SESSION['current_item_id'],
            'new_value' => '空值'
            )
        );
        // log the delete information 
        log_item($change_field);
        return true;
    }

    // TESTED SUCCESSFULLY
    //update items
    // $change_field include three keys
    // $change_field['name'] $change_field['old_value'] $change_field['new_value'];
    function update_item($change_field) {
        $flag = false;

		$conn = db_connect();
  //       $conn->autocommit(false);

        $query = "update items set ";
        foreach ($change_field as $row) {
            if ($flag) {
                $query .= ", ";
            }
            $temp = $row['name']." = '".addslashes($row['new_value'])."'";
            $query .= $temp;
            $flag = true;
        }
        $query .= " where item_id = '".$_SESSION['current_item_id']."'";    

        $result = $conn->query("set names utf8");
        $result = $conn->query($query);
        if (!$result) {
            throw new Exception("Could not connect to the db!");
        } else {
            reset($change_field);

            // change the log information into chinese
            foreach ($change_field as &$key) {
                if ($key['name'] == 'item_name') $key['name'] = '事项名称';
                if ($key['name'] == 'item_follower_id') $key['name'] = '事项跟踪人';
                if ($key['name'] == 'item_description') $key['name'] = '事项描述';
                if ($key['name'] == 'item_type_id') $key['name'] = '事项类型';
                if ($key['name'] == 'item_state') $key['name'] = '事项状态';
                if ($key['name'] == 'item_priority_id') $key['name'] = '事项优先级';
            }
            // log the update information
            log_item($change_field);
            return true;
        }
    }

    // finish the item 
    // set the item_state to FINISH
    function finish_selected_item () {
        $conn = db_connect();
        
        $query = "update items set item_state = 'FINISH' where item_id = '".$_SESSION['current_item_id']."'";
        $result = $conn->query("set names utf8");
        $result = $conn->query($query);
        if (!$result) {
            throw new Exception("Could not finish the item.");
        }
        // delete the auto_notify setting
        $result = $conn->query("delete from auto_notify where item_id = '".$_SESSION['current_item_id']."'");

        // arrange the notify setting information into the array
        $change_field = array();
        array_push($change_field, array(
            'name' => '事项状态',
            'old_value' => '运行中',
            'new_value' => '关闭'
            )
        );
        // log the update information
        log_item($change_field);
        return true;
    }

    // check the item state 
    // This function is used to check item_state before all the actions
    function is_finished ($item_id) {
        $conn = db_connect();
        $result = $conn->query("set names utf8");
        $result = $conn->query("select item_state from items where item_id = '".$item_id."'");

        if (!$result) {
            throw new Exception("Could not connect to the db!");
        }
        if ($result->num_rows == 0) {
            //throw new Exception("No such user!");
        }

        $row = $result->fetch_row();
        if ($row[0] == 'FINISH') {
            return true;
        } else {
            return false;
        }
    }

    // check if the item is related to the current user
    // if the current user is creator , follower or the persons who need to be notified.
    function is_related ($item_id) {
        $conn = db_connect();
        $query = "select item_id from items where item_id = '".$item_id."' and item_creator_id = '".$_SESSION['current_user_id']."'";
        $query1 = "select item_id from items where item_id = '".$item_id."' and item_follower_id = '".$_SESSION['current_user_id']."'";
        $query2 = "select item_id from auto_notify where item_id = '".$item_id."' and user_id = '".$_SESSION['current_user_id']."'";

        $result = $conn->query($query);
        $result1 = $conn->query($query1);
        $result2 = $conn->query($query2);

        if (($result->num_rows > 0) || ($result1->num_rows > 0) || ($result2->num_rows > 0)) {
            return true;
        }
        else {
            return false;
        }
     }

    //-----------------------------------------ITEM GET/SEARCH-------------------------------------------
    //----------------------------------------------------------------------------------------------------
    //simple display function for test
    function display_selected_item () {
        $conn = db_connect();
        $query = "select * from items where item_id = '".$_SESSION['current_item_id']."'";
        $result = $conn->query("set names utf8");
        $result = $conn->query($query);
        if (!$result) {
            throw new Exception("Could not connect to DB.");
        }
        if ($result->num_rows == 0) {
            //throw new Exception("NO items records!");
        }

        //$row = $result->fetch_assoc();
        $row = db_result_to_array($result);
        return $row;
    }

    // GET the related items of current_user
    function get_related_items () {
        $conn = db_connect();
        $query = "select * from items where item_creator_id = '".$_SESSION['current_user_id']."'
                    or item_follower_id = '".$_SESSION['current_user_id']."'";
        $result = $conn->query("set names utf8");
        $result = $conn->query($query);
        if (!$result) {
            throw new Exception("Could not connect to DB.");
        }
        if ($result->num_rows == 0) {
            //throw new Exception("NO items records!");
        }

        $row = db_result_to_array($result);
        return $row;
    }

    // TESTED   SUCCESSFULLY
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
        $flag = false;//用于打印 and
        // $flag_start = false;//用于判断是否设定查询起始时间
        // $flag_end = false;//用于判断是否设定查询结束时间

        $conn = db_connect();
        $query = "select * from items where ";
        foreach ($condition as $row) {
            //if the item_name is the search condition
            if ($row['name'] == "item_name") {
                if ($flag) $query .= " and ";
                $query .= "item_name like '%".trim($row['value'])."%'";
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
                $query .= "item_description like '%".addslashes($row['value'])."%'";
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

            // //if the item_follow_mark is the search condition
            // if ($row['name'] == "item_follow_mark") {
            //     if ($flag) $query .= " and ";
            //     $query .= "item_follow_mark like '%".$row['value']."%'";
            //     $flag =true;
            // }

            //if the time-duration is the search condition
            //compare the time with the start_time
            if ($row['name'] == "start_time") {
                if ($flag) $query .= " and ";
                $query .= "item_create_time > '".$row['value']."'";
                $flag =true;
                //$flag_start = true;
            }

            //compare the time with the end_time
            if ($row['name'] == "end_time") {
                if ($flag) $query .= " and ";
                $query .= "item_create_time < '".$row['value']."'";
                $flag =true;
                //$flag_end =  true;
            }

            if ($row['name'] == "item_priority_id") {
                if ($flag) $query .= " and ";
                $query .= "item_priority_id = '".$row['value']."'";
                $flag =true;
                //$flag_end =  true;
            }
        }

        // //echo "Notice: ".$query."<br />";
        // if ((flag_start) && (!flag_end) || ((!flag_start) && (flag_end))) {
        //     throw new Exception("Time setting is not correct!");
        // }
        $result = $conn->query("set names utf8");
        $result = $conn->query($query);
        if (!$result) {
            throw new Exception("Could not connect to DB.");
        }
        if ($result->num_rows == 0) {
            //throw new Exception("NO items records!");
        }

        $row = db_result_to_array($result);
        return $row;  
    }

    //-----------------------------------------ITEM_FOLLOW_MARK-------------------------------------------
    //----------------------------------------------------------------------------------------------------
    //insert the follow mark into the DB
    // $follow_mark is obtained from the testarea
    function new_follow_mark($follow_mark, $current_time) {
        //$current_time = date("Y-m-d H:i:s");
        $conn = db_connect();
        $query = "insert into item_follow_marks VALUES 
                ('', '".$_SESSION['current_item_id']."', '".addslashes($follow_mark)."', 
                     '".$_SESSION['current_user_id']."', '".$current_time."')";
        $result = $conn->query("set names utf8");
        $result = $conn->query($query);
        if (!$result) {
            throw new Exception("Could not insert the mark into the DB.");
        }

        return true;
    }

    // get all the follow_marks
    function get_follow_mark() {
        $conn = db_connect();
        $query = "select * from item_follow_marks where item_id = '".$_SESSION['current_item_id']."'
                    order by item_follow_mark_id";
        $result = $conn->query("set names utf8");
        $result = $conn->query($query);
        if (!$result) {
            throw new Exception("Could not connect to DB.");
        }
        if ($result->num_rows == 0) {
            //throw new Exception("No follow mark records!");
        }

        $row = db_result_to_array($result);
        return $row;
    }

    //------------------------------------------ITEM_TYPES------------------------------------------------
    //----------------------------------------------------------------------------------------------------

    //get all the different item_types according to the current user`s priv
    function get_item_types() {
        $conn = db_connect();
        $result = $conn->query("set names utf8");
        //$result = $conn->query("select para_value_id, para_value_name from para_values where para_id = '1'");
        $result = $conn->query("select priv_id from role_priv where priv_id > 1 and role_id = (
            select role_id from users where user_id = '".$_SESSION['current_user_id']."')");
        if (!$result) {  
            throw new Exception("Could not connect to the db!");
        }
        
        // store the priv_id
        $priv_array = array();
        while (list($id) = $result->fetch_row()) {
            $priv_array[] = $id;
        }

        $type_array = array();
        foreach ($priv_array as $key) {
            $result = $conn->query("select para_value_id, para_value_name from para_values 
                where para_value_name = (select priv_name from privileges where priv_id = '".$key."')");
            $row = db_result_to_array($result);
            $type_array = array_merge($type_array, $row);
        }
        //$result = db_result_to_array($result);
        return $type_array;
    }
   
    //------------------------------------------ITEM_PRIORITY------------------------------------------------
    //----------------------------------------------------------------------------------------------------
    // GET ALL THE PRIORITIES
    function get_item_priorityies () {
        $conn = db_connect();
        $result = $conn->query("set names utf8");
        $result = $conn->query("select para_value_id, para_value_name from para_values where para_id = '2'");

        if (!$result) {  
            throw new Exception("Could not connect to the db!");
        }

        $row = db_result_to_array($result);
        return $row;
    }

?>