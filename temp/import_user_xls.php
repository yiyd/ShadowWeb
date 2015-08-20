<?php
	include('function\shadow_fns.php');
	//session_start();

	// import users 
	$conn = db_connect();
	$data = new Spreadsheet_Excel_Reader();
	$data->setOutputEncoding("utf-8");
	$data->read('..//user.xls');
	error_reporting(E_ALL ^ E_NOTICE);

	for ($i=2; $i <= $data->sheets[0]['numRows']; $i++) { 
		// import the setting to the users
		$query = "insert into users values ('', '".
			$data->sheets[0]['cells'][$i][1]."', sha1('".
			$data->sheets[0]['cells'][$i][2]."'), '".
			$data->sheets[0]['cells'][$i][3]."', '".
			$data->sheets[0]['cells'][$i][4]."')";
		
		$result = $conn->query("set names utf8");
		echo $query."<br /><hr /><br />";
		@$result = $conn->query($query);
		if (!$result) {
			throw new Exception("Error Processing Request");
		}
	}

	echo "import sucess";

?>