<?php
require_once('header_none.php');
$id  = isset($_GET['id']) ? $_GET['id'] : '';
$act = isset($_GET['act']) ? $_GET['act'] : '';

if($act == 'doanra'){
	$doan = new DoanRa_Regis();
	$doan->id = $id; $d = $doan->get_one();
	if(isset($d['congvanxinphep']['attachments']) && $d['congvanxinphep']['attachments']){
		foreach($d['congvanxinphep']['attachments'] as $dk){
			if(file_exists($folder_regis.$target_files_regis.$dk['alias_name'])){
				@unlink($folder_regis.$target_files_regis.$dk['alias_name']);
			}
		}
	}
	if($doan->delete()) transfers_to('doanra_regis.php?update=ok');
}

if($act == 'doanvao'){
	$doan = new DoanVao_Regis();
	$doan->id = $id; $d = $doan->get_one();
	if(isset($d['congvanxinphep']['attachments']) && $d['congvanxinphep']['attachments']){
		foreach($d['congvanxinphep']['attachments'] as $dk){
			if(file_exists($folder_regis.$target_files_regis.$dk['alias_name'])){
				@unlink($folder_regis.$target_files_regis.$dk['alias_name']);
			}
		}
	}
	if($doan->delete()) transfers_to('doanvao_regis.php?update=ok');
}

if($act=='abtc'){
	$doan = new ABTC_Regis();
	$doan->id = $id; $d = $doan->get_one();
	if(isset($d['congvanxinphep']['attachments']) && $d['congvanxinphep']['attachments']){
		foreach($d['congvanxinphep']['attachments'] as $dk){
			if(file_exists($folder_regis.$target_files_regis.$dk['alias_name'])){
				@unlink($folder_regis.$target_files_regis.$dk['alias_name']);
			}
		}
	}
	if(isset($d['giaytolienquan']) && $d['giaytolienquan']){
		foreach($d['giaytolienquan'] as $gt){
			if(file_exists($folder_regis.$target_files_regis.$gt['alias_name'])){
				@unlink($folder_regis.$target_files_regis.$gt['alias_name']);
			}
		}
	}
	if($doan->delete()) transfers_to('doanvao_regis.php?update=ok');
}
?>