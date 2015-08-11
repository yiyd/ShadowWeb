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
            $result1 = $conn->query("select user_id from users where user_name = 
                '".$username."'");
            if ($result1 && $result1->num_rows > 0) {
                throw new Exception("Wrong Password!");
            } else {
                throw new  Exception("No such user."); 
            }      
        }
        if ($result->num_rows > 0) {
            $row = $result->fetch_object();
            $old_user_id = $row->user_id;

            $query = "select session_id from user_access where user_id = '".$old_user_id."'";
            $result = $conn->query($query);
            if (!$result) {
                throw new Exception("Could not connect to the DB!");
            }
            if ($result->num_rows > 0 ) {
                $row = $result->fetch_object();
                $old_session_id = $row->session_id;
                $new_session_id = session_id();

                // if ($old_session_id == $new_session_id) {
                //     $_SESSION['current_user_id'] =  $old_user_id;
                //     return true;
                // }

                // check the session file is exsit or not 
                if (@fopen("..\\..\\..\\tmp\\sess_".$old_session_id, 'r')) {
                    // //delete the session files
                    if (!@unlink("..\\..\\..\\tmp\\sess_".$old_session_id)) {  
                        throw new Exception("Could not delete the session files! ".$old_session_id);
                    }
                }
                
                // delete the login info 
                $result = $conn->query("delete from user_access where user_id = '".$old_user_id."'");
            }
            
            $_SESSION['current_user_id'] =  $old_user_id;
            $query = "insert into user_access values ('', '".$_SESSION['current_user_id']."', '".session_id()."')";
            $result = $conn->query($query);
            if (!$result) {
                throw new Exception("Could not insert into the user_access table!");
            }
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

    //注销所有session变量
    function logout() {
        $old_user = $_SESSION['current_user_id'];
        unset($_SESSION['current_user_id']);

        $result_dest = session_destroy();
                
        if (!empty($old_user)) {
            if ($result_dest) {
                return true;
            }
            else {
                throw new Exception("Could not log you out!");
            }
        }
        else {
            throw new Exception("You have not logged in!");
        }

    }

    function check_valid_user() {
        $current_session_id = session_id();
        $conn = db_connect();

        if (!isset($_SESSION['current_user_id'])) {
            do_html_header('');
            //throw new Exception("Notice: You have logged in other place! Please login again!");
            echo "<h1>Notice: You have logged in other place!<br /></h1>";
            do_html_url('login.php', 'Back to Login');
            do_html_footer();
            exit;
        }
        //check the uesr login_info table 
        $result = $conn->query("select * from user_access where user_id = '".$_SESSION['current_user_id']."' 
                            and session_id = '".$current_session_id."'");
        if ($result->num_rows == 1) {
            return true;
        } else {
            do_html_header('');
            //throw new Exception("Notice: You have not logged in! Please login again!");
            echo "<h1>Notice: You have not logged in!<br /></h1>";
            do_html_url('login.php', 'Back to Login');
            do_html_footer();
            exit;
        }


    }

    // get the users` name through given id
    function get_user_name ($user_id) {
        $conn = db_connect();
        $query = "select user_name from users where user_id = '".$user_id."'";
        $result = $conn->query("set names utf8");
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