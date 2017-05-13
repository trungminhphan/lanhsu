<?php
require_once('header_none.php');

$donvi = new DonVi();$quocgia = new QuocGia();$chucvu = new ChucVu();
$mucdich = new MucDich(); $kinhphi = new KinhPhi(); $dmdoanvao = new DMDoanVao();
$ham = new Ham(); $nghenghiep = new NgheNghiep(); $dantoc = new DanToc();
$phanloaidonvi = new PhanLoaiDonVi(); $linhvuc = new LinhVuc();

$arr_id_user = array('donvi', 'quocgia', 'chucvu', 'mucdich', 'kinhphi', 'dmdoanvao', 'ham', 'nghenghiep', 'dantoc', 'phanloaidonvi', 'linhvuc');

foreach ($arr_id_user as $a) {
	$$a->id_user = '573c11d65c1e8852088b4567';
	$list = $$a->get_all_list();
	if($list){
		foreach($list as $l){
			$$a->id = $l['_id'];
			$$a->set_id_user();
		}
	}
}



//$logs = new Logs();
//$doanvao = new DoanVao();
//$doanra = new DoanRa();
//$abtc = new ABTC();
/*$canbo = new CanBo();

$canbo_list = $canbo->get_all_list();

if($canbo_list){
	foreach ($canbo_list as $cb) {
		$logs->id = new MongoId();
		$logs->action = 'ADD';
		$logs->collections = 'canbo';
		$logs->datas = $cb;
		$logs->id_user = $cb['id_user'] ? $cb['id_user'] : '573c11d65c1e8852088b4567';
		$logs->insert();
	}
}*/
/*$doanvao_list = $doanvao->get_all_list();
if($doanvao_list){
	foreach ($doanvao_list as $dv) {
		$logs->id = new MongoId();
		$logs->action = 'ADD';
		$logs->collections = 'doanvao';
		$logs->datas = $dv;
		$logs->id_user = $dv['id_user'];
		$logs->insert();
	}
}

$doanra_list = $doanra->get_all_list();
if($doanra_list){
	foreach ($doanra_list as $dr) {
		$logs->id = new MongoId();
		$logs->action = 'ADD';
		$logs->collections = 'doanra';
		$logs->datas = $dr;
		$logs->id_user = $dr['id_user'];
		$logs->insert();
	}
}

$abtc_list = $abtc->get_all_list();
if($abtc_list){
	foreach ($abtc_list as $a) {
		$logs->id = new MongoId();
		$logs->action = 'ADD';
		$logs->collections = 'abtc';
		$logs->datas = $a;
		$logs->id_user = $a['id_user'];
		$logs->insert();
	}
}*/



?>