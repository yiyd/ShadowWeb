<?php
	require_once('function/shadow_fns.php');
	header('Content-Type:text/html;charset=utf-8');

	$allow_file_type = array("xls", "xlsx");
	$max_file_size = 20480000;
	// SET THE SAVE PATH
	$dest_folder = "temp/";


	if ($_SERVER['REQUEST_METHOD'] == 'POST') {
		$file = $_FILES['upload_file'];

		if (!is_uploaded_file($file['tmp_name'])) {
			// check if the file exsit
			echo "文件不存在！";
			exit();
		}
		else {
			$torrent = explode(".", $file['name']);
			// get the file type
			$file_end = end($torrent);
			$file_end = strtolower($file_end);
			if (!in_array($file_end, $allow_file_type)) {
				echo "文件类型不对！";
				exit();
			}

			if ($max_file_size < $file['size']) {
				echo "文件过大！";
				exit();
			}

			// build the upload folder if not exsit
			if (!file_exists($dest_folder)) {
				mkdir($dest_folder);
			}

			$temp_name = $file['tmp_name'];
			$file_name = $file['name'];

			if(!move_uploaded_file ($temp_name, $dest_folder.$file_name)) {
				echo "移动文件出错！";
				exit;
			}

			echo "上传成功！";
		}
	}
?>