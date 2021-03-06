<?php
/**
 * Created by PhpStorm.
 * User: Toto
 * Date: 2015/7/24
 * Time: 11:02
 */
    
    
    //----------------------------------COMMON USER------------------------------------------
    //---------------------------------------------------------------------------------------
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

                // check the session file is exsit or not 
                if (@fopen("..\\..\\..\\tmp\\sess_".$old_session_id, 'r')) {
                    // //delete the session files
                    if (!@unlink("..\\..\\..\\tmp\\sess_".$old_session_id)) {  
                        throw new Exception("<h1>EXCEPTION: Could not delete the session files! ".$old_session_id."</h1><br /><br />请重启浏览器！");
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
    function change_passwd($old_passwd, $new_passwd) {     
        $conn = db_connect();
        $result = $conn->query("select user_name from users where user_id = '"
            .$_SESSION['current_user_id']."' and user_passwd = sha1('".$old_passwd."')");

        if ($result->num_rows == 1) {
            $result = $conn->query("update users
                                set user_passwd = sha1('".$new_passwd."')
                                where user_id = '".$_SESSION['current_user_id']."'");
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

    //----------------------------------ADMIN USER------------------------------------------
    //---------------------------------------------------------------------------------------
    //check the current user
    // function check_admin() {
    //     if (isset($_SESSION['admin_user'])) {
    //         return true;
    //     } else {
    //         throw new Exception("You are not logged in as admin_user!");    
    //     }
    // }

    // // OLD VERSION ------------------------------------------
    // function login_admin($username, $passwd) {
    //     $conn = db_connect();
    //     $result = $conn->query("select * from admin where admin_name = '".$username."'
    //                 and admin_passwd = '".$passwd."'");
    //     if (!$result) {
    //         throw new Exception('Search failed!');
    //     }
    //     if ($result->num_rows > 0) return true;
    //     else {
    //         throw new Exception('Could not log you in.');
    //     }
    // }

    // //change administrator`s password
    // function change_admin_passwd($username, $old_passwd, $new_passwd) {
    //     if (login_admin($username, $old_passwd)) {

    //         if (!($conn = db_connect())) {
    //             throw new Exception("Could not connect to the db!");
    //         }

    //         $result = $conn->query("update admin
    //                         set admin_passwd = sha1('".$new_passwd."')
    //                         where admin_name = '".$username."'");
    //         if (!$result) {
    //             throw new Exception("The admin_passwd is not changed."); // not changed
    //         } else {
    //             return true;// changed successfully
    //         }
    //     } else {
    //         throw new Exception("Your old passwd is wrong!"); // old password was wrong
    //     }
    // }

?>