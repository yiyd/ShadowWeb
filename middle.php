<?php
/**
 * Coder: ZX
 * Date: 2015/8/11
 * Time: 10:52
 */
	require_once('function/shadow_fns.php');
	session_start();

	
	$username = $_POST['username'];
	$password = $_POST['password'];

	if ($username && $password) {
		try {
			login($username, $password);
		}
		catch(Exception $e) {
			do_html_header('');
			echo $e->getMessage();
			do_html_footer();
			exit;
		}
	}else
	{
		do_html_header('');
		echo "请输入用户名和密码！！！";
		do_html_footer();
		exit;
	}

	header("Location:main.php");
	

?>