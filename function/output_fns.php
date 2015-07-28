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
?>