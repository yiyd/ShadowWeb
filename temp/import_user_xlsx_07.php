<?php
	require_once('../function/shadow_fns.php');
	require_once('../resources/PHPExcel/PHPExcel.php');
	require_once('../resources/PHPExcel/PHPExcel/IOFactory.php');
	require_once('../resources/PHPExcel/PHPExcel/Reader/Excel5.php');
	require_once('../resources/PHPExcel/PHPExcel/Reader/Excel2007.php');

	$conn = db_connect();
	$conn->autocommit(false);

	$inputFileName = '../user.xlsx';

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
		$query = "insert into users values ('', '".$strArray[0]."', sha1('".$strArray[1]."'), '".$strArray[2]."', '".$strArray[3]."')";
		$result = $conn->query("set names utf8");
		echo $query."<br />";
		$result = $conn->query($query);

		if (!$result) {
			throw new Exception("Error Processing Request", 1);
		}
	}

	$conn->commit();
	$conn->autocommit(true);

	echo "import success";
?> 