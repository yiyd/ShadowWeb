<?php
/**
 * Coder: ZX
 * Date: 2015/7/28
 * Time: 16:00
 */
	require_once('function/shadow_fns.php');
	session_start();

	do_html_header();
	display_login_form();
	do_html_footer();

?>