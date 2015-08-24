<?php
/**
 * Coder: ZX
 * Date: 2015/8/24
 * Time: 8:50
 */
	require_once('function/shadow_fns.php');
	session_start();

	check_valid_user();	

	display_role_manage_page();

?>