<?php
/**
 * Created by PhpStorm.
 * User: Toto
 * Date: 2015/7/24
 * Time: 11:27
 */
    //connect to the DB
    function db_connect() {
        $conn = new mysqli('localhost','shadow_admin', 'passwd', 'shadow');
        if (!$conn) {
            throw new Exception ('Could not connect to database server.');
        } else {
            $result = $conn->query("set names utf8");
			$conn->autocommit(TRUE);
            return $conn;
        }
    }

    //deal with the query result
    function db_result_to_array($result) {
        $res_array = array();

        for ($count = 0; $row = $result->fetch_assoc(); $count++) {
            $res_array[$count] = $row;
        }
        return $res_array;
    }

?>