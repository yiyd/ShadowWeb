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
                echo "<link rel=\"stylesheet\" type=\"type/css\" href=\"css/".$css_file_name."\" >\n";
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
                    <form onkeydown=enter() id="ff" method="post" action="main.php">
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
        function enter(){
            if (event.keyCode == 13)
                submitForm();
        }
    </script>

<?php
    }

    function do_html_URL($url, $name) {
?>
    <br /><a href="<?php echo $url; ?>"><?php echo $name; ?></a><br />
<?php
    }

?>