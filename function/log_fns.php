<?php
/**
 * Created by PhpStorm.
 * User: Toto
 * Date: 2015/7/24
 * Time: 17:06
 * log all the changes
 */
    //新建关闭日志
    function log_new() {
        $conn = db_connect();
        $conn->autocommit(false);

        //insert the new_log into DB
        $query = "insert into logs VALUES ('', '".$item_id."', '".$log_changer."', '".$current_time."')";
        $result = $conn->query($query);
        if (!$result) {
            return false;
        } else {
            return true;
        }
    }

    //添加修改日志
    function log_update($auto_date, $auto_type, $user_id) {
        $conn = db_connect();

        //insert the update log to the log table

    }


?>