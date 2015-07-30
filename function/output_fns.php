<?php
/**
 * Created by PhpStorm.
 * User: Toto
 * Date: 2015/7/24
 * Time: 11:06
 */
    //show the page header
    function do_html_header() {
?>
    <html>
        <head>
            <meta charset="UTF-8">
            <meta name="description" content="事项跟踪工具">
            <meta name="keywords" content="事项跟踪">
            <meta http-equiv="X-UA-Compatible" content="IE=edge">
            <title>中银开放平台-事项跟踪工具</title>
        </head>
        <body>
        <header>
            <h1 align="left">事项跟踪工具</h1>
            
        </header>

<?php
    }

    function do_html_heading($title) {
        echo "";
    }
    function do_html_footer() {
?>
        </body>
        <hr />
        </html>
<?php
    }

    function display_login_form() {
?>
    <form method="post" action="index.php">
        <table bgcolor="#cccccc">
            <tr>
                <th colspan="2" align="center">事项跟踪工具登陆：</th>
            </tr>
            <tr>
                <td align="right">用户名:</td>
                <td><input type="text" name="username"/></td>
            </tr>
            <tr>
                <td align="right">密码:</td>
                <td><input type="password" name="password"/></td>
            </tr>
            <tr>
                <td colspan="2" align="center"><input type="submit" value="登陆"/></td>
            </tr>
            <tr>
                <td align="right"><a href="no_account.php">没有账号?</a></td>
                <td align="right"><a href="forgot_password.php">忘记密码?</a></td>          
            </tr>
        </table>
    </form>
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
                display_item_form_item_state($item['item_create_time']);
                display_item_form_item_name($item['item_state']);
                display_item_form_item_follower($users_array, $item['item_follower_id']);
                display_item_form_item_description($item['item_description']);
                display_item_form_item_type($item_types_array, $item['item_type_id']);
                display_item_form_auto_notify_type($notify('auto_type'));
                display_item_form_auto_notify_user($users_array, $notify['auto_user_name']);
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
        echo "<select name=\"item_follower\"";
        if ($item_follower_id == '') {
            echo " >\"";
        }else {
            echo " value=\"".$item_follower_id.">\"";
        }
        foreach ($users_array as $user) {
            echo "<option value=\"".$user['user_id']."\">".$user['user_name']."</option>";
        }
        echo "</select>";
        echo "</td>";
        echo "</tr>";
    }

    function display_item_form_item_description($item_description='')
    {
        echo "<tr>";
        echo "<td align=\"right\">事项描述:</td>";
        echo "<td><textarea name=\"item_description\" rows=\"8\" cols=\"32\" wrap=\"virtual\" value=\"".$item_description."\"></textarea></td>";
        echo "</tr>";    
    }

    function display_item_form_item_type($item_types_array, $item_type_id='')
    {
        echo "<tr>";
        echo "<td align=\"right\">事项类型:</td>";
        echo "<td>";
        echo "<select name=\"item_type\"";
        if ($item_type_id == '') {
            echo " >\"";
        }else {
            echo " value=\"".$item_type_id.">\"";
        }
        foreach ($item_types_array as $type) {
            echo "<option value=\"".$type['para_value_id']."\">".$type['para_value_name']."</option>";
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
        echo "<select name=\"auto_notify_type\"";
        if ($auto_notify_type == '') {
            echo " >\"";
        }else {
            echo " value=\"".$auto_notify_type.">\"";
        }
        echo "<option value=\"ONCE\">单次提醒</option>";
        echo "<option value=\"DAILY\">每日提醒</option>";
        echo "<option value=\"WEEKLY\">每周提醒</option>";
        echo "<option value=\"MONTHLY\">每月提醒</option>";
        echo "<option value=\"QUARTERLY\">每季度提醒</option>";
        echo "<option value=\"YEARLY\">每年提醒</option>";
        echo "</select>";
        echo "</td>";
        echo "</tr>";
    }

    function display_item_form_auto_notify_user($users_array, $auto_notify_user='')
    {
        echo "<tr>";
        echo "<td align=\"right\">自动提醒人员:</td>";
        echo "<td>";
        echo "<select name=\"auto_notify_user\"";
        if ($auto_notify_user == '') {
            echo " >\"";
        }else {
            echo " value=\"".$auto_notify_user.">\"";
        }
        foreach ($users_array as $user) {
            echo "<option value=\"".$user['user_id']."\">".$user['user_name']."</option>";
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
        echo "<td><textarea name=\"item_follow_mark\" rows=\"8\" cols=\"32\" wrap=\"virtual\" value=\"".$item_follow_mark."\"></textarea></td>";
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