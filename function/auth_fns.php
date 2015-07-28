<?php
/**
 * Created by PhpStorm.
 * User: Toto
 * Date: 2015/7/24
 * Time: 11:02
 */
    function login($username, $passwd) {
        $conn = db_connect();
        $result = $conn->query("select * from users where user_name = '".$username."'
                and user_passwd = sha1('".$passwd."')");
        if (!$result) {
            throw new Exception('No such user!');
        }
        if ($result->num_rows > 0) return true;
        else {
            throw new Exception('Could not log you in.');
        }
    }

    //change common users` password
    function change_passwd($username, $old_passwd, $new_passwd) {
        if (login($username, $old_passwd)) {

            if (!($conn = db_connect())) {
                return false;
            }

            $result = $conn->query("update users
                                set user_passwd = sha1('".$new_passwd."')
                                where user_name = '".$username."'");
            if (!$result) {
                return false;  // not changed
            } else {
                return true;  // changed successfully
            }
        } else {
            return false; // old password was wrong
        }
    }

    function check_valid_user() {
        if (isset($_SESSION['current_user'])) {
               echo "Logged in as ".$_SESSION['current_user']."";
           }   
    }

?>