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
            <?php
                if (!(isset($_SESSION['current_user']))) {
                    display_login_form();
                    exit;
                }


            ?>
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

    }

?>