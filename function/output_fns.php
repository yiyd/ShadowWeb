<?php
/**
 * Created by PhpStorm.
 * User: Toto
 * Date: 2015/7/24
 * Time: 11:06
 */
    //show the page header
    function do_html_header($css_file_name) {
?>
<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
        <meta name="description" content="事项跟踪工具">
        <meta name="keywords" content="事项跟踪">
        <!-- <meta http-equiv="X-UA-Compatible" content="IE=edge"> -->
        <?php
            if($css_file_name) {
                echo "<link rel=\"stylesheet\" type=\"type/css\" href=\"".$css_file_name."\" >\n";
            }
        ?>
        <title>中银开放平台-事项跟踪工具</title>
        <link rel="stylesheet" type="type/css" href="resources/jquery-easyui/themes/default/easyui.css">
        <link rel="stylesheet" type="type/css" href="resources/jquery-easyui/themes/icon.css">

        <script type="text/javascript" src="resources/jquery-easyui/jquery.min.js"></script>
        <script type="text/javascript" src="resources/jquery-easyui/jquery.easyui.min.js"></script>
    </head>
    <body>

<?php
    }

    function do_html_heading($title) {
        echo "";
    }
    function do_html_footer() {
?>
    </body>
</html>
<?php
    }

    function display_login_form() {
?>
    <div style="margin:20px 0;"></div>
    <div id="container">
        <div id="page_content" >
            <img id="icon" src="resources/icon_title.png">
            <div class="easyui-panel" title="用户登陆" style="width:300px">
                <div id="form">
                    <form id="ff" method="post" action="main.php">
                        <table cellpadding="5">
                            <tr>
                                <td align="right">用户名:</td>
                                <td><input class="easyui-textbox" type="text" name="username" data-options="required:true"/></td>
                            </tr>
                            <tr>
                                <td align="right">密码:</td>
                                <td><input class="easyui-textbox" type="password" name="password" data-options="required:true"/></td>
                            </tr>
                            <tr>
                                <td td colspan="2" align="center">
                                    <a id="submit_button" href="javascript:void(0)" class="easyui-linkbutton" onclick="submitForm()">登陆</a>
                                    <a id="reset_button" href="javascript:void(0)" class="easyui-linkbutton" onclick="clearForm()">清除</a>
                                </td>          
                            </tr>
                            <tr>
                                <td align="right"><a href="contact_admin.php">没有账号?</a></td>
                                <td align="right"><a href="contact_admin.php">忘记密码?</a></td>          
                            </tr>
                            

                        </table>
                    </form>
                </div>
            </div>
        </div>
        <div id="page_foot">Copyright 2015 © 中国银行 版权所有</div>
    </div>
    <script>
        function submitForm(){
            $('#ff').submit();
        }
        function clearForm(){
            $('#ff').form('clear');
        }
    </script>

<?php
    }

    function do_html_URL($url, $name) {
?>
    <br /><a href="<?php echo $url; ?>"><?php echo $name; ?></a><br />
<?php
    }

    function display_new_item_form($users_array, $item_types_array) {
        if (!is_array($users_array)) {
            echo "<p>No users currently available</p>";
            return;
        }
        if (!is_array($item_types_array)) {
            echo "<p>No item types currently available</p>";
            return;
        }
?>

    <form method="post" action="new_item.php">
        <table bgcolor="#cccccc">
            <?php
                display_item_form_title("新建事项");
                display_item_form_item_name();
                display_item_form_item_follower($users_array);
                display_item_form_item_description();
                display_item_form_item_type($item_types_array);
                display_item_form_auto_notify_type();
                display_item_form_auto_notify_user($users_array);
                display_item_form_auto_notify_date();
                display_item_form_item_follow_mark();

            ?>
            <tr>
                <td colspan="2" align="center"><input type="submit" value="提交"/></td>
            </tr>
            
        </table>
    </form>
<?php
    }        
    function display_item_form($notify, $item, $users_array, $item_types_array) {
        if (!is_array($notify)) {
            echo "<p>No notify currently available</p>";
            return;
        }
        if (!is_array($item)) {
            echo "<p>No item currently available</p>";
            return;
        }
        if (!is_array($users_array)) {
            echo "<p>No users currently available</p>";
            return;
        }
        if (!is_array($item_types_array)) {
            echo "<p>No item types currently available</p>";
            return;
        }
?>   
        <table bgcolor="#cccccc">
            <?php
                display_item_form_title("已成功新建事项");
                display_item_form_item_id($item['item_id']);
                display_item_form_item_creator_name($item['item_creator_name']);
                display_item_form_item_create_time($item['item_create_time']);
                display_item_form_item_state($item['item_state']);
                display_item_form_item_name($item['item_name']);
                display_item_form_item_follower($users_array, $item['item_follower_id']);
                display_item_form_item_description($item['item_description']);
                display_item_form_item_type($item_types_array, $item['item_type_id']);
                display_item_form_auto_notify_type($notify['auto_type']);
                display_item_form_auto_notify_user($users_array, $notify['user_id']);
                display_item_form_auto_notify_date($notify['auto_date']);
                display_item_form_item_follow_mark($item['item_follow_mark']);

            ?>               
        </table>

<?php
    }

    function display_item_form_title($title='')
    {
        echo "<tr>";
        echo "<th colspan=\"2\" align=\"center\">".$title."</th>";
        echo "</tr>";
    }

    function display_item_form_item_name($item_name='')
    {
        echo "<tr>";
        echo "<td align=\"right\">事项名称:</td>";
        echo "<td><input type=\"text\" name=\"item_name\" size=\"32\" maxlength=\"32\" value=\"".$item_name."\"/></td>";
        echo "</tr>";
    }

    function display_item_form_item_follower($users_array, $item_follower_id='')
    {
        echo "<tr>";
        echo "<td align=\"right\">跟踪人:</td>";
        echo "<td>";
        echo "<select name=\"item_follower\" >";
        foreach ($users_array as $user) {
            echo "<option value=\"".$user['user_id']."\"";
            if ($item_follower_id == $user['user_id']) {
                echo "selected=\"selected\"";
            }
            echo ">".$user['user_name']."</option>";
        }
        echo "</select>";
        echo "</td>";
        echo "</tr>";
    }

    function display_item_form_item_description($item_description='')
    {
        echo "<tr>";
        echo "<td align=\"right\">事项描述:</td>";
        echo "<td><textarea name=\"item_description\" rows=\"8\" cols=\"32\" wrap=\"virtual\" >".$item_description."</textarea></td>";
        echo "</tr>";    
    }

    function display_item_form_item_type($item_types_array, $item_type_id='')
    {
        echo "<tr>";
        echo "<td align=\"right\">事项类型:</td>";
        echo "<td>";
        echo "<select name=\"item_type\" >";
        
        foreach ($item_types_array as $type) {
            echo "<option value=\"".$type['para_value_id']."\"";
            if ($item_type_id == $type['para_value_id']) {
                echo "selected=\"selected\"";
            }
            echo ">".$type['para_value_name']."</option>";
        }
        echo "</select>";
        echo "</td>";
        echo "</tr>";
    }

    function display_item_form_auto_notify_type($auto_notify_type='')
    {
        echo "<tr>";
        echo "<td align=\"right\">自动提醒类型:</td>";
        echo "<td>";

        echo "<select name=\"auto_notify_type\" >";
        $auto_notify_type_array = array("ONCE"=>"单次提醒", "DAILY"=>"每日提醒","WEEKLY"=>"每周提醒","MONTHLY"=>"每月提醒","QUARTERLY"=>"每季度提醒","YEARLY"=>"每年提醒");
        foreach ($auto_notify_type_array as $key => $value) {
            echo "<option value=\"".$key."\"";
            if ($auto_notify_type == $key) {
                echo "selected=\"selected\"";
            }
            echo ">".$value."</option>";
        }
        
        echo "</select>";
        echo "</td>";
        echo "</tr>";
    }

    function display_item_form_auto_notify_user($users_array, $auto_notify_user='')
    {
        echo "<tr>";
        echo "<td align=\"right\">自动提醒人员:</td>";
        echo "<td>";
        echo "<select name=\"auto_notify_user\" >";
        
        foreach ($users_array as $user) {
            echo "<option value=\"".$user['user_id']."\"";
            if ($auto_notify_user == $user['user_id']) {
                echo "selected=\"selected\"";
            }
            echo ">".$user['user_name']."</option>";
        }
        echo "</select>";
        echo "</td>";
        echo "</tr>";
    }

    function display_item_form_auto_notify_date($auto_notify_date='')
    {
        echo "<tr>";
        echo "<td align=\"right\">自动提醒时间:</td>";
        if ($auto_notify_date=='') {
            $auto_notify_date= date("Y-m-d H:i:s");
        }
        echo "<td><input type=\"text\" name=\"auto_notify_date\" value=\"".$auto_notify_date."\"/></td>";
        echo "</tr>";
    }

    function display_item_form_item_follow_mark($item_follow_mark='')
    {
        echo "<tr>";
        echo "<td align=\"right\">跟踪备注:</td>";
        echo "<td><textarea name=\"item_follow_mark\" rows=\"8\" cols=\"32\" wrap=\"virtual\" >".$item_follow_mark."</textarea></td>";
        echo "</tr>";    
    }

    function display_item_form_item_id($item_id)
    {
        echo "<tr>";
        echo "<td align=\"right\">事项编号:</td>";
        echo "<td>".$item_id."</td>";
        echo "</tr>";
    }

    function display_item_form_item_creator_name($item_creator_name)
    {
        echo "<tr>";
        echo "<td align=\"right\">创建人:</td>";
        echo "<td>".$item_creator_name."</td>";
        echo "</tr>";
    }

    function display_item_form_item_create_time($item_create_time)
    {
        echo "<tr>";
        echo "<td align=\"right\">创建时间:</td>";
        echo "<td>".$item_create_time."</td>";
        echo "</tr>";
    }

    function display_item_form_item_state($item_state)
    {
        echo "<tr>";
        echo "<td align=\"right\">事项状态:</td>";
        echo "<td>".$item_state."</td>";
        echo "</tr>";
    }
?>