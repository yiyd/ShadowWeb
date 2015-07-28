<?php
/**
 * Created by PhpStorm.
 * User: Toto
 * Date: 2015/7/24
 * Time: 11:02
 */
    //check the current user
    function check_admin() {
        if (isset($_SESSION['admin_user'])) {
            return true;
        } else {
            return false;
        }
    }

    function login_admin($username, $passwd) {
        $conn = db_connect();
        $result = $conn->query("select * from admin where admin_name = '".$username."'
                    and admin_passwd = '".$passwd."'");
        if (!$result) {
            throw new Exception('Search failed!');
        }
        if ($result->num_rows > 0) return true;
        else {
            throw new Exception('Could not log you in.');
        }
    }
    //change administrator`s password
    function change_admin_passwd($username, $old_passwd, $new_passwd) {
        if (login_admin($username, $old_passwd)) {

            if (!($conn = db_connect())) {
                return false;
            }

            $result = $conn->query("update admin
                            set admin_passwd = sha1('".$new_passwd."')
                            where admin_name = '".$username."'");
            if (!$result) {
                return false;  // not changed
            } else {
                return true;  // changed successfully
            }
        } else {
            return false; // old password was wrong
        }
    }

    // those funcitons below are releated to the ROLES management
    // GET/NEW/UPDATE/DELETE/
    //------------------------------------------------
    // get all the roles in the DB
    function get_roles () {
        $conn = db_connect();
        $query = "select * from roles";

        $result = $conn->query($query);
        if (!$result) return false;
        if ($result->num_rows == 0) return false;

        $result = db_result_to_array($result);
        return $result;
    }

     //get the users with the conditions
    function get_roles_1 ($conditions) {

    }

    //new one role with the input $new_role
    function new_role ($new_role) {
        if (!is_array($new_role)) return false;

        $conn = db_connect();
        $conn->autocommit(flase);

        $query = "insert into roles values ('', '".$new_role['role_name']."', '".$new_role['c_priv']."', 
                '".$new_role['u_priv']."', '".$new_role['d_priv']."', '".$new_role['s_priv']."', 
                '".$new_role['f_priv']."', '".$new_role['v_priv']."')";
        $result = $conn->query($query);
        if (!$result) {
            return false;
        } else {
            $conn->commit();
            $conn->autocommit(true);
            return true;
        }
    }   

    function update_role () {

    }

    function delete_role () {

    }

    // those funcitons below are releated to the USERS management
    // GET/NEW/UPDATE/DELETE/
    //------------------------------------------------
    //get all the valid users
    function get_users () {
        $conn = db_connect();
        $result = $conn->query("select user_id, user_name from users");
        if (!$result) {
            return false;
        }
        if ($result->num_rows == 0) {
            return false;
        }
        $result = db_result_to_array($result);
        return $result;
    }

    //get the users with the conditions
    function get_users_1 ($conditions) {

    }

    function new_user ($new_user) {
        if (!is_array($new_user)) return false;

        $conn = db_connect();
        $conn->autocommit(flase);

        $query = "insert into roles values ('', '".$new_user['user_name']."', '".$new_user['user_passwd']."', 
                '".$new_user['role_id']."', '".$new_user['user_mail']."')";
        $result = $conn->query($query);
        if (!$result) {
            return false;
        } else {
            $conn->commit();
            $conn->autocommit(true);
            return true;
        }
    }

    function update_user () {

    }

    function delete_user () {

    }

    function reset_user_passwd() {

    }




?>