<?php
/**
 * Created by PhpStorm.
 * User: Toto
 * Date: 2015/7/24
 * Time: 11:02
 */
    //login function 
    function login($username, $passwd) {
        $conn = db_connect();
        $result = $conn->query("select user_id from users where user_name = '".$username."'
                and user_passwd = sha1('".$passwd."')");
        if (!$result) {
            throw new Exception("Could not connect to the db!");
        }
        if ($result->num_rows == 0) {
            throw new  Exception("No such user."); 
        }
        if ($result->num_rows > 0) {
            $row = $result->fetch_object();
            $_SESSION['current_user_id'] =  $row->user_id;
            return true;
        }
        else {
            throw new Exception('Could not log you in.');
        }
    }

    //change common users` password
    function change_passwd($username, $old_passwd, $new_passwd) {
        if (login($username, $old_passwd)) {

            if (!($conn = db_connect())) {
                throw new Exception("Could not connect to the db!");
            }

            $result = $conn->query("update users
                                set user_passwd = sha1('".$new_passwd."')
                                where user_name = '".$username."'");
            if (!$result) {
                throw new Exception("The admin_passwd is not changed."); // not changed
            } else {
                return true;  // changed successfully
            }
        } else {
            throw new Exception("Your old passwd is wrong!");// old password was wrong
        }
    }

    function check_valid_user() {
        if (isset($_SESSION['current_user_id'])) {
            // echo "Logged in as ".$_SESSION['current_user'].".<br />";
        } else {
            do_html_header();
            do_html_url('login,php', 'Login');
            do_html_footer();
            exit;
        }


    }

    function get_user_name ($user_id) {
        $conn = db_connect();
        $query = "select user_name from users where user_id = '".$user_id."'";
        $result = $conn->query($query);
        if (!$result) {
            throw new Exception("Could not connect to the db!");
        }
        else if ($result->num_rows == 0) {
            throw new Exception("No such user!");
        }
        else {
            $row = $result->fetch_object();
            $result = $row->user_name;
            return $result;
        }
    }
?>