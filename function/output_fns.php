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
            <tr>
                <th colspan="2" align="center">新建事项：</th>
            </tr>
    
            <tr>
                <td align="right">事项名称:</td>
                <td><input type="text" name="item_name" size="32" maxlength="32"/></td>
            </tr>
            <tr>
                <td align="right">跟踪人:</td>
                <td>
                    <select name="item_follower">
                    <?php
                        
                        foreach ($users_array as $user) {
                            echo "<option value=\"".$user['user_id']."\">".$user['user_name']."</option>";
                        }
                    ?>
                    </select>
                </td>
            </tr>
            <tr>
                <td align="right">事项描述:</td>
                <td><textarea name="item_description" rows="8" cols="32" wrap="virtual" ></textarea></td>
            </tr>
            <tr>
                <td align="right">事项类型:</td>
                <td>
                    <select name="item_type">
                    <?php
                        
                        foreach ($item_types_array as $type) {
                            echo "<option value=\"".$type['para_value_id']."\">".$type['para_value_name']."</option>";
                        }
                    ?>
                    </select>
                </td>
            </tr>
            <tr>
                <td align="right">自动提醒类型:</td>
                <td>
                    <select name="auto_notify_type">
                        <option value="ONCE">单次提醒</option>
                        <option value="DAILY">每日提醒</option>
                        <option value="WEEKLY">每周提醒</option>
                        <option value="MONTHLY">每月提醒</option>
                        <option value="QUARTERLY">每季度提醒</option>
                        <option value="YEARLY">每年提醒</option>
                    </select>
                </td>
            </tr>
            <tr>
                <td align="right">自动提醒人员:</td>
                <td>
                    <select name="auto_notify_user">
                    <?php
                        
                        foreach ($users_array as $user) {
                            echo "<option value=\"".$user['user_id']."\">".$user['user_name']."</option>";
                        }
                    ?>
                    </select>
                </td>
            </tr>
            <tr>
                <td align="right">自动提醒时间:</td>
                <td><input type="text" name="auto_notify_date" value="<?php echo date("Y-m-d H:i:s"); ?>"/></td>
            </tr>
            <tr>
                <td align="right">跟踪备注:</td>
                <td><textarea name="item_follow_mark" rows="8" cols="32" wrap="virtual" ></textarea></td>
            </tr>
            <tr>
                <td colspan="2" align="center"><input type="submit" value="提交"/></td>
            </tr>
            
        </table>
    </form>
<?php
    }        
    function display_item_form($item) {
?>

    
        <table bgcolor="#cccccc">
            <tr>
                <th colspan="2" align="center">已成功新建事项：</th>
            </tr>
            <tr>
                <td align="right">事项编号:</td>
                <td align="left"><?php echo $item['item_id'] ?></td>
            </tr>
            <tr>
                <td align="right">创建人:</td>
                <td align="left">
                    <?php 
                        echo $item[''] 
                    ?>
                </td>
            </tr>    
            <tr>
                <td align="right">事项名称:</td>
                <td><input type="text" name="item_name" size="32" maxlength="32" value=""/></td>
            </tr>
            <tr>
                <td align="right">跟踪人:</td>
                <td>
                    <select name="item_follower">
                    <?php
                        
                        foreach ($users_array as $user) {
                            echo "<option value=\"".$user['user_id']."\">".$user['user_name']."</option>";
                        }
                    ?>
                    </select>
                </td>
            </tr>
            <tr>
                <td align="right">事项描述:</td>
                <td><textarea name="item_description" rows="8" cols="32" wrap="virtual" ></textarea></td>
            </tr>
            <tr>
                <td align="right">事项类型:</td>
                <td>
                    <select name="item_type">
                    <?php
                        
                        foreach ($item_types_array as $type) {
                            echo "<option value=\"".$type['para_value_id']."\">".$type['para_value_name']."</option>";
                        }
                    ?>
                    </select>
                </td>
            </tr>
            <tr>
                <td align="right">自动提醒类型:</td>
                <td>
                    <select name="auto_notify_type">
                        <option value="ONCE">单次提醒</option>
                        <option value="DAILY">每日提醒</option>
                        <option value="WEEKLY">每周提醒</option>
                        <option value="MONTHLY">每月提醒</option>
                        <option value="QUARTERLY">每季度提醒</option>
                        <option value="YEARLY">每年提醒</option>
                    </select>
                </td>
            </tr>
            <tr>
                <td align="right">自动提醒人员:</td>
                <td>
                    <select name="auto_notify_user">
                    <?php
                        
                        foreach ($users_array as $user) {
                            echo "<option value=\"".$user['user_id']."\">".$user['user_name']."</option>";
                        }
                    ?>
                    </select>
                </td>
            </tr>
            <tr>
                <td align="right">自动提醒时间:</td>
                <td><input type="text" name="auto_notify_date" value="<?php echo date("Y-m-d H:i:s"); ?>"/></td>
            </tr>
            <tr>
                <td align="right">跟踪备注:</td>
                <td><textarea name="item_follow_mark" rows="8" cols="32" wrap="virtual" ></textarea></td>
            </tr>
            <tr>
                <td colspan="2" align="center"><input type="submit" value="提交"/></td>
            </tr>
            
        </table>

<?php
    }        
?>