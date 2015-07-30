<?php
/**
 * Created by PhpStorm.
 * User: Toto
 * Date: 2015/7/24
 * Time: 17:06
 * log all the changes
 */
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
            return false;
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
            $row = $result->fetch_object();
            $current_log_id = $row->log_id;
        }

        if (is_array($change_field)) {
            foreach ($change_field as $row) {
                $query = "insert into log_fields VALUES ('".$current_log_id."', '".$row['name']."',
                            '".$row['old_value']."', '".$row['new_value']."')";
                $result = $conn->query($query);
                if(!$result) {
                    return false;
                }
            }
        }
    }


?>