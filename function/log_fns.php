<?php
/**
 * Created by PhpStorm.
 * User: Toto
 * Date: 2015/7/24
 * Time: 17:06
 * log all the changes
 */


    // MAYBE WE CAN USE THE TRIGGER TO REALISE SUCH FUNCITON 
    // ALL THE ADMIN OPERATIONS ARE LOGGED BY TRIGGERS 
    // ITEMS NEED TO BE ADDED MANUALLY BY THE FOLLOWING FUNCTIONS

    //新建事项日志，插入到logs 
    // $change_field is an array stored all the change values
    //      $change_field inculdes
    //      $change_field['name'], $change_field['old_value'], $change_field['new_value']
    function log_items ($change_field) {
        $current_time = date("Y-m-d H:i:s");
        $conn = db_connect();
        $conn->autocommit(false);

        //insert the log_title into DB
        $query = "insert into logs VALUES ('', '".$_SESSION['current_item_id']."', '".$_SESSION['current_user_id']."',
                 '".$current_time."')";
        $result = $conn->query($query);
        if (!$result) {
            throw new Exception("Could not connect to the db!");
        } else {
            if ($isset($change_field)) {
                //get the new log_id
                $query1 = "select max(log_id) from admin_logs";
                $result1 = $conn->query($query1);
                if ($result1 && ($result1->num_rows > 0)) {
                    $row1 = $result1->fetch_row();
                    $current_log_id = $row1[0];
                }

                if (is_array($change_field)) {
                    foreach ($change_field as $row) {
                        $query2 = "insert into log_fields VALUES ('".$current_log_id."', '".$row['name']."',
                                    '".$row['old_value']."', '".$row['new_value']."')";
                        $result2 = $conn->query($query2);
                        if(!$result2) {
                            throw new Exception("Could not connect to the db!");
                        }
                    }
                }
            }
            else 
            $conn->commit();
            $conn->autocommit(true);
            return true;
        }
    }

    // LOG DISPLAY FUNCTIONS 
    // $log_object is "users", "roles"
    function get_admin_log($log_object, $object_id) {
        $conn = db_connect();
        $query = "select * from admin_logs where admin_log_object like '%".$log_object."%'
                and admin_log_object_id = '".$object_id."'" ;
        $result = $conn->query("set names utf8");
        $result = $conn->query($query);
        if (!$result) {
            throw new Exception("Could not connect to DB.");
        }
        if ($result->num_rows == 0) {
            throw new Exception("No such logs!");
        }
        // anrange the data into an array
        $row = db_result_to_array($result);
        return $row;
    }

    //get admin_log details
    function get_admin_log_detail ($admin_log_id) {
        $conn = db_connect();
        // get the details from the log_fields table
        $query = "select * from admin_log_fields where admin_log_id = '".$admin_log_id."'";
        $result = $conn->query("set names utf8");
        $result = $conn->query($query);
        if (!$result) {
            throw new Exception("Could not connect to DB.");
        }
        if ($result->num_rows == 0) {
            throw new Exception("No log details!");
        }
        // anrange the data into an array
        $row = db_result_to_array($result);
        return $row;
    }

    //--------------------------------------------------------------------------
    // get the item_logs 
    // log_title
    function get_log() {
        $conn = db_connect();
        $query = "select * from logs where item_id = '".$_SESSION['current_item_id']."'";
        
        $result = $conn->query("set names utf8");
        $result = $conn->query($query);
        if (!$result) {
            throw new Exception("Could not connect to DB.");
        }
        if ($result->num_rows == 0) {
            throw new Exception("No such logs!");
        }
        // anrange the data into an array
        $row = db_result_to_array($result);
        return $row;
    }

    //get the 
    function get_log_detail($log_id) {
        // get the details from the log_fields table
        $query = "select * from log_fields where log_id = '".$log_id."'";
        $result = $conn->query("set names utf8");
        $result = $conn->query($query);
        if (!$result) {
            throw new Exception("Could not connect to DB.");
        }
        if ($result->num_rows == 0) {
            throw new Exception("No log details!");
        }
        // anrange the data into an array
        $row = db_result_to_array($result);
        return $row;
    }

?>