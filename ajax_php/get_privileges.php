<?php
/**
 * User: ZX
 * Date: 2015/8/24
 * Time: 9:20
 */	
 	require_once('../function/shadow_fns.php');	
	session_start();
	
	try {
		
		$roles_array = get_roles();
		
	}
	catch(Exception $e) {
		do_html_header('');
		echo $e->getMessage();
		do_html_footer();
		exit;
	}
	echo json_encode($roles_array);
?>