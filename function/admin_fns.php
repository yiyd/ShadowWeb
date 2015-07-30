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
            throw new Exception("You are not logged in as admin_user!");    
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
                throw new Exception("Could not connect to the db!");
            }

            $result = $conn->query("update admin
                            set admin_passwd = sha1('".$new_passwd."')
                            where admin_name = '".$username."'");
            if (!$result) {
                throw new Exception("The admin_passwd is not changed."); // not changed
            } else {
                return true;// changed successfully
            }
        } else {
            throw new Exception("Your old passwd is wrong!"); // old password was wrong
        }
    }

    // those funcitons below are releated to the ROLES management
    // GET/NEW/UPDATE/DELETE/
    //------------------------------------------------
    // get all the roles in the DB
    function get_roles () {
        //if (!check_admin()) return false;
        $conn = db_connect();
        $query = "select * from roles";

        $result = $conn->query($query);
        if (!$result) {
            throw new Exception("Could not connect to the db!");
        }
        if ($result->num_rows == 0) {
            throw new Exception("No records in roles table!");
        }

        $result = db_result_to_array($result);
        return $result;
    }

    //get the users with the condition
    // According to the design, the condition only includes the role_name
    // $condition['name'], $condition['value']
    // 角色名称 role_name 模糊查询 （需求书暂时就这么写的）
    function get_roles_1 ($condition) {
        //if (!check_admin()) return false;
        $conn = db_connect();
        $query = "select * from roles where ";

        foreach ($condition as $row) {
            $query .= $row['name']." like ".$row['value'];
        }

        $result = $conn->query($query);
        if (!$result) {
            throw new Exception("Could not connect to the db!");
        }
        if ($result->num_rows == 0) {
            throw new Exception("No records in roles table!");
        }

        $result = db_result_to_array($result);
        return $result;

    }

    //new one role with the input $new_role
    // $new_role is an array including all the informations 
    function new_role ($new_role) {
        //if (!check_admin()) return false;
        if (!is_array($new_role)) {
            throw new Exception("input is not an array!");
        }

        $conn = db_connect();
        $conn->autocommit(flase);

        $query = "insert into roles values ('', '".$new_role['role_name']."', '".$new_role['c_priv']."', 
                '".$new_role['u_priv']."', '".$new_role['d_priv']."', '".$new_role['s_priv']."', 
                '".$new_role['f_priv']."', '".$new_role['v_priv']."')";
        $result = $conn->query($query);
        if (!$result) {
            throw new Exception("Could not connect to the db!");
        } else {
            $conn->commit();
            $conn->autocommit(true);
            return true;
        }
    }   

    //update the role values when admin change someplace
    // $change_role is an array
    // $change_role['name'] $change_role['old_value'] $change_role['new_value'];
    function update_role ($change_role, $role_id) {
        //if (!check_admin()) return false;
        if (!is_array($change_role)) {
            throw new Exception("input is not an array!");
        }

        $conn = db_connect();
        $conn->autocommit(flase);

        // $query = "insert into roles values ('".$change_role['role_id']."', '".$change_role['role_name']."', '".$change_role['c_priv']."', 
        //         '".$change_role['u_priv']."', '".$change_role['d_priv']."', '".$change_role['s_priv']."', 
        //         '".$change_role['f_priv']."', '".$change_role['v_priv']."')";

        $query = "update roles set ";
        foreach ($change_role as $row) {
            $temp = $row['name']." = ".$row['new_value'];
            $query .= $temp;
        }
        $query .= "where role_id = '".$role_id."'";    


        $result = $conn->query($query);
        if (!$result) {
             throw new Exception("Could not connect to the db!");
        } else {
            $conn->commit();
            $conn->autocommit(true);
            return true;
        }
    }

    function delete_role ($role_id) {
        //if (!check_admin()) return false;
        $conn = db_connect();
        $conn->autocommit(flase);

        $query = "delete from roles where role_id = '".$role_id."'";
        $result = $conn->query($query);
        if (!$result) {
             throw new Exception("Could not connect to the db!");
        } else {
            $conn->commit();
            $conn->autocommit(true);
            return true;
        }
    }

    // those funcitons below are releated to the USERS management
    // GET/NEW/UPDATE/DELETE/
    //------------------------------------------------
    //get all the valid users
    function get_users () {        
        $conn = db_connect();
        $result = $conn->query("select user_id, user_name from users");
        if (!$result) {
             throw new Exception("Could not connect to the db!");
        }
        if ($result->num_rows == 0) {
            throw new Exception("No records in users table!");
        }
        $result = db_result_to_array($result);
        return $result;
    }

    //get the users with the condition
    // According to the design, the condition only includes the 
    // $condition['name'], $condition['value']
    // 查询用户时用到的关键字定义，就是name
    //     用户名称 user_name 模糊查询
    //     用户角色 role_id   直接选中输入
    //     用户邮箱 user_mail 模糊查询 
    function get_users_1 ($condition) {
        //if (!check_admin()) return false;
        $flag = false;

        $conn = db_connect();
        $query = "select * from users where ";

        foreach ($condition as $row) {
            //if the user_name is the search condition
            if ($row['name'] == "user_name") {
                if ($flag) $query .= " and ";
                $query .= "user_name like '%".$row['value']."%'";
                $flag =true;
            }

            //if the role_id is the search condition
            if ($row['name'] == "role_id") {
                if ($flag) $query .= " and ";
                $query .= "role_id = '".$row['value']."'";
                $flag =true;
            }

            //if the user_mail is the search condition
            if ($row['name'] == "user_mail") {
                if ($flag) $query .= " and ";
                $query .= "user_mail like '%".$row['value']."%'";
                $flag =true;
            }         
        }

        $result = $conn->query($query);
        if (!$result) {
            throw new Exception("Could not connect to the db!");
        }
        if ($result->num_rows == 0) {
            throw new Exception("No records in roles table!");
        }

        $result = db_result_to_array($result);
        return $result;
    }

    //new_user 
    // $new_user is an array including:
    // $new_user['user_name'], $new_user['user_passwd'], $new_user['role_id'], $new_user['user_mail']
    function new_user ($new_user) {
        if (!check_admin()) return false;
        if (!is_array($new_user)) {
            throw new Exception("input is not an array!");
        }

        $conn = db_connect();
        $conn->autocommit(flase);

        $query = "insert into roles values ('', '".$new_user['user_name']."', '".$new_user['user_passwd']."', 
                '".$new_user['role_id']."', '".$new_user['user_mail']."')";
        $result = $conn->query($query);
        if (!$result) {
            throw new Exception("Could not connect to the db!");
        } else {
            $conn->commit();
            $conn->autocommit(true);
            return true;
        }
    }


    //update the user values when admin change someplace
    // $change_user is an array
    // $change_user['name'] $change_user['old_value'] $change_user['new_value'];
    function update_user ($change_user, $user_id) {
        //if (!check_admin()) return false;
        if (!is_array($change_user)) {
            throw new Exception("input is not an array!");
        }

        $conn = db_connect();
        $conn->autocommit(flase);

        $query = "update users set ";
        foreach ($change_user as $row) {
            $temp = $row['name']." = ".$row['new_value'];
            $query .= $temp;
        }
        $query .= "where user_id = '".$user_id."'";    


        $result = $conn->query($query);
        if (!$result) {
            throw new Exception("Could not connect to the db!");
        } else {
            $conn->commit();
            $conn->autocommit(true);
            return true;
        }
    }

    function delete_user ($user_id) {
        //if (!check_admin()) return false;
        $conn = db_connect();
        $conn->autocommit(flase);

        $query = "delete from users where user_id = '".$user_id."'";
        $result = $conn->query($query);
        if (!$result) {
            throw new Exception("Could not connect to the db!");
        } else {
            $conn->commit();
            $conn->autocommit(true);
            return true;
        }
    }

    function reset_user_passwd($user_id) {
        if (!check_admin()) return false;

        //set the new password
        $new_passwd = "newpasswod";
                
        if ($new_passwd  == false) {
            throw new Exception ('Could not generate the new password.');
        }
        
        //add numbers to the new passwd to increase the securtiy
        $rand_number = rand(0, 999);
        $new_passwd . $rand_number;
    
        //update the new passwd with db
        $conn = db_connect();
        $conn->autocommit(false);

        $result = $conn->query("update users set user_passwd = sha1('".$new_passwd."') where user_id = '".$user_id."'");
        if (!$result) {
            throw new Exception ('Could not reset the password.');
        }
        else {
            $conn->commit();
            $conn->autocommit(true);
            return true; 
        }

    }




?>