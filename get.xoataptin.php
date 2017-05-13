<?php
require_once('inc/config.inc.php');
$filename = isset($_GET['filename']) ? $_GET['filename'] : '';
if(file_exists($target_files.$filename)){
	if(unlink($target_files.$filename)){
		echo 'Đã xoá thành công';
	} else {
		echo 'Không thể xoá';
	}
}
?>