<?php
function __autoload($class_name) {
    require_once('cls/class.' . strtolower($class_name) . '.php');
}
$session = new SessionManager();
$users = new Users();
require_once('inc/functions.inc.php');
require_once('inc/config.inc.php');
if(!$users->isLoggedIn()){ transfers_to('./login.php'); }

$id = isset($_POST['id']) ? $_POST['id'] : '';
$act = isset($_POST['act']) ? $_POST['act'] : '';

$id_tinhtrang = isset($_POST['id_tinhtrang']) ? $_POST['id_tinhtrang'] : '';
$noidung = isset($_POST['noidung']) ? $_POST['noidung'] : '';
$id_user = $users->get_userid();
$arr_tinhtrang = array(
	't' => intval($id_tinhtrang),
	'noidung' => $noidung,
	'date_post' => new MongoDate(),
	'id_user' => new MongoId($id_user)
);

if($act == 'doanra'){
	$doanra = new DoanRa_Regis();
	$doanra->id = $id;
	$doanra->status = $arr_tinhtrang;
	$doanra->push_tinhtrang();
}

if($act=='doanvao'){
	$doanvao = new DoanVao_Regis();
	$doanvao->id = $id;
	$doanvao->status = $arr_tinhtrang;
	$doanvao->push_tinhtrang();
}

if($act=='abtc'){
	$abct = new ABTC_Regis();
	$abct->id = $id;
	$abct->status = $arr_tinhtrang;
	$abct->push_tinhtrang();
}