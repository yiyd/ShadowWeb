<?php
/**
 * Created by PhpStorm.
 * User: Toto
 * Date: 2015/7/24
 * Time: 11:02
 */
    
    //---------------------------------ROLE MANAGEMENT---------------------------------------
    //---------------------------------------------------------------------------------------
    // GET/NEW/UPDATE/DELETE/
    //------------------------------------------------
    // get all the roles in the DB
    function get_roles () {
        //if (!check_admin()) return false;
        $conn = db_connect();
        $query = "select * from roles";
        $result = $conn->query("set names utf8");
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
    function get_roles_with_condition ($condition) {
        //if (!check_admin()) return false;
        $conn = db_connect();
        $query = "select * from roles where ";

        foreach ($condition as $row) {
            $query .= $row['name']." like '".$row['value']."'";
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
    // $new_role is an array includes roles attribute
    // $new_role['role_name'], $new_role['role_priv']
    function new_role ($new_role_name, $new_role_priv) {
        //if (!check_admin()) return false;
        if (!$new_role_name || !is_array($new_role_priv)) {
            throw new Exception("input is not an array!");
        }

        $conn = db_connect();
        $conn->autocommit(flase);

        $query = "insert into roles values ('', '".$new_role_name."')";
        $result = $conn->query("set names utf8");
        $result = $conn->query($query);
        if (!$result) {
            throw new Exception("Could not connect to the db!");
        } else {
            foreach ($new_role_priv as $key) {
                $query = "insert into role_priv values ('', (select max(role_id) from roles), '".$key."')";
                $result = $conn->query($query);
            }
            
            $conn->commit();
            $conn->autocommit(true);
            return true;
        }
    }   

    //update the role values when admin change someplace
    // $change_field is an array
    // $change_field is an array includes all the new priv_id
    function update_role ($role_id, $change_field) {
        $flag = false;

        //if (!check_admin()) return false;
        if (!is_array($change_field)) {
            throw new Exception("input is not an array!");
        }

        $conn = db_connect();
        $query = "update roles set ";
        foreach ($change_field as $row) {
            if ($flag) {
                $query .= ", ";
            }
            $temp = $row['name']." = '".$row['new_value']."'";
            $query .= $temp;
            $flag = true;
        }
        $query .= "where role_id = '".$role_id."'";    


        $result = $conn->query($query);
        if (!$result) {
             throw new Exception("Could not connect to the db!");
        } else {
            return true;
        }
    }

    function delete_role ($role_id) {
        //if (!check_admin()) return false;
        $conn = db_connect();
        $query = "delete from roles where role_id = '".$role_id."'";
        $result = $conn->query($query);
        $query = "delete from role_priv where role_id = '".$role_id."'";
        $result1 = $conn->query($query);
        if (!$result || !$result1) {
             throw new Exception("Could not connect to the db!");
        } else {
            return true;
        }
    }

    //------------------------------USER MANAGMENT--------------------------------------------
    //---------------------------------------------------------------------------------------
    // GET/NEW/UPDATE/DELETE/
    //------------------------------------------------
    //get all the valid users
    function get_users () {        
        $conn = db_connect();
        $result = $conn->query("set names utf8");
        $result = $conn->query("select * from users");
        if (!$result) {
             throw new Exception("Could not connect to the db!");
        }
        if ($result->num_rows == 0) {
            //throw new Exception("No records in users table!");
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
    function get_users_with_condition ($condition) {
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
            }         
        }
        $result = $conn->query("set names utf8");
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
    // $new_user is an 2-D array including:
    // $new_user['user_name'], $new_user['user_passwd'], $new_user['role_id'], $new_user['user_mail']
    function new_user ($new_user) {
        // if (!check_admin()) return false;
        if (!is_array($new_user)) {
            throw new Exception("input is not an array!");
        }

        $conn = db_connect();
        $query = "insert into users values ('', '".$new_user['user_name']."', sha1('".$new_user['user_passwd']."'), 
                '".$new_user['role_id']."', '".$new_user['user_mail']."')";
        $result = $conn->query("set names utf8");
        $result = $conn->query($query);
        if (!$result) {
            throw new Exception("Could not connect to the db!");
        } else {
            return true;
        }
    }


    //update the user values when admin change someplace
    // $change_field is an array
    // $change_field['name'] $change_field['old_value'] $change_field['new_value'];
    function update_user ($user_id, $change_field) {
        $flag = false;
        
        //if (!check_admin()) return false;
        if (!is_array($change_field)) {
            throw new Exception("input is not an array!");
        }

        $conn = db_connect();

        $query = "update users set ";
        foreach ($change_field as $row) {
            if ($flag) {
                $query .= ", ";
            }

            $temp = $row['name']." = '".$row['new_value']."'";
            $query .= $temp;
            $flag = true;
        }
        $query .= " where user_id = '".$user_id."'";    
        echo $query;
        $result = $conn->query("set names utf8");
        $result = $conn->query($query);
        if (!$result) {
            throw new Exception("Could not connect to the db!");
        } else {
            return true;
        }
    }
    

    function delete_user ($user_id) {
        //if (!check_admin()) return false;
        $conn = db_connect();

        $query = "delete from users where user_id = '".$user_id."'";
        $result = $conn->query($query);
        if (!$result) {
            throw new Exception("Could not connect to the db!");
        } else {
            return true;
        }
    }

    function reset_user_passwd($user_id) {
        // if (!check_admin()) return false;

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

        $result = $conn->query("update users set user_passwd = sha1('".$new_passwd."') where user_id = '".$user_id."'");
        if (!$result) {
            throw new Exception ('Could not reset the password.');
        }
        else {
            return true; 
        }

    }

    //-----------------------------PARAMETERS MANAGMENT--------------------------------------
    //---------------------------------------------------------------------------------------
    // NEW PARAMETERS
    function new_parameters() {

    }

    function update_parameters() {

    }

    // function delete_para
    //-----------------------------PRIVILEGES MANAGMENT--------------------------------------
    //---------------------------------------------------------------------------------------
    // NEW PRIV
    function new_privileges($new_priv_name) {
        $conn = db_connect();
        $result = $conn->query("set names utf8");
        $result = $conn->query("insert into privileges value ('', '".$new_priv_name."')");
        if (!$result) {
            throw new Exception("Could not connect to the DB.");
        }
        return true;
    }   

    // DELETE PRIV
    function delete_privileges($priv_id) {
        $conn = db_connect();
        // delete priv from privileges table
        $result = $conn->query("delete from privileges where priv_id = '".$priv_id."'");
        // delete priv from role_priv table 
        $result = $conn->query("delete from role_priv where priv_id = '".$priv_id."'");

        return true;
    }

    // GET ALL PRIV
    function get_privileges() {
        $conn = db_connect();
        $result = $conn->query("set names utf8");
        $result = $conn->query("select * from privileges");
        if (!$result) {
            throw new Exception("Could not connect to the DB.");
        }

        $row = db_result_to_array($result);
        return $row; 
    }

    // GET ROLE`S PRIV
    function get_privileges($role_id) {
        $conn = db_connect();
        $result = $conn->query("select priv_id from role_priv where role_id = '".$role_id."'");
        if (!$result) {
            throw new Exception("Could not connect to the DB.");
        }

        $row = db_result_to_array($result);
        return $row; 
    }
?>