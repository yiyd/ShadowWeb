<?php
	echo dirname(__FILE__);
	require_once('../function/shadow_fns.php');	

	$conn = db_connect();

	$inputFileName = 'user.xlsx';

	//check the file type 
	$inputFileType = PHPExcel_IOFactory::identify($inputFileName);

	// create a reader
	$objReader = PHPExcel_IOFactory::createReader($inputFileType);
	$objPHPExcel = $objReader->load($inputFileName);

	$sheet = $objPHPExcel->getSheet();
	$highestRow = $sheet->getHighestRow();
	$highestColumn = $sheet->getHighestColumn();

	for ($i = 2; $i <= $highestRow; $i++) { 
		$str = '';
		for ($j = 'A'; $j <= $highestColumn; $j++) {
			// reader one area
			$str .= iconv('utf-8', 'utf-8', $objPHPExcel->getActiveSheet()->getCell("$j$i")->getValue()).';';
			//echo $str."<br />";
		}

		$strArray = explode(";", $str);
		// check if the user exsit
		$result = $conn->query("select user_id from users where user_name = '".$strArray[0]."'");
		if ($result->num_rows == 0) {
			// not exsit, then new one
			$query = "insert into users values ('', '".$strArray[0]."', sha1('".$strArray[1]."'), '".$strArray[2]."', '".$strArray[3]."')";
			$result = $conn->query("set names utf8");
			//echo $query."<br />";
			$result = $conn->query($query);

			if (!$result) {
				throw new Exception("Error Processing Request", 1);
			}
		}
	}

	echo "import success";
?> 