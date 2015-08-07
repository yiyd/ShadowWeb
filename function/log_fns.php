<?php
/**
 * Created by PhpStorm.
 * User: Toto
 * Date: 2015/7/24
 * Time: 17:06
 * log all the changes
 */


    // MAYBE WE CAN USE THE TRIGGER TO REALISE SUCH FUNCITON 
    // INSTED OF THE FOLLOWING THINGS.

    //新建基本日志头，插入logs 
    function log_basic() {
        $current_time = date("Y-m-d H:i:s");
        $conn = db_connect();
        $conn->autocommit(false);

        //insert the new_log into DB
        $query = "insert into logs VALUES ('', '".$_SESSION['current_item_id']."', '".$_SESSION['current_user_id']."',
                 '".$current_time."')";
        $result = $conn->query($query);
        if (!$result) {
            throw new Exception("Could not connect to the db!");
        } else {
            $conn->commit();
            $conn->autocommit(true);
            return true;
        }
    }

    //添加修改日志
    function log_update($change_field) {
        //insert the update log to the log table
        log_basic();
        $conn = db_connect();
        $conn->autocommit(false);

        //get the new log_id
        $query = "select last_insert_id()";
        $result = $conn->query($query);
        if ($result && ($result->num_rows > 0)) {
            $row = $result->fetch_row();
            $current_log_id = $row[0];
        }

        if (is_array($change_field)) {
            foreach ($change_field as $row) {
                $query = "insert into log_fields VALUES ('".$current_log_id."', '".$row['name']."',
                            '".$row['old_value']."', '".$row['new_value']."')";
                $result = $conn->query($query);
                if(!$result) {
                    throw new Exception("Could not connect to the db!");
                }
            }
        }
    }



    // LOG DISPLAY FUNCTIONS 
    // $log_object is "users", "roles"
    function get_admin_log_title($log_object, $object_id) {
        $conn = db_connect();
        $query = "select * from admin_logs where admin_log_object like '".$log_object."'
                and admin_log_object_id = '".$object_id."'" ;
        $result = $conn->query($query);
        if (!$result) {
            throw new Exception("Could not connect to DB. Please check the input.");
        }
        if ($result->num_rows == 0) {
            throw new Exception("No such logs!");
        }

        $row = db_result_to_array($result);
        return $row;
    }

    function get_admin_log_content($log_object, $object_id) {

    }

    function get_log_title() {

    }

    function get_log_content() {

    }

?>