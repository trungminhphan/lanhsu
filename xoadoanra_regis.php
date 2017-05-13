<?php 
require_once('header_none.php');

$id = isset($_GET['id']) ? $_GET['id'] : '';
if($id){
	$doanra_regis = new DoanRa_Regis();
	$doanra_regis->id = $id;
	$dr = $doanra_regis->get_one();	
	if($dr['congvanxinphep']['attachments']){
		foreach ($dr['congvanxinphep']['attachments'] as $key => $value) {
			if(file_exists($folder_regis . $target_files_regis . $value['alias_name'])){
				@unlink($folder_regis . $target_files_regis . $value['alias_name']);
			}
		}
	}
	if($doanra_regis->delete()) transfers_to('doanra_regis.php');
	else echo 'Lỗi! Không thể xoá. <a href="doanra_regis.php">Trở về</a>';
} else {
	echo 'Lỗi! Không xác định Đoàn cần xoá. <a href="doanra_regis.php">Trở về</a>';
}


?>