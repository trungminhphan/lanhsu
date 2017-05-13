<?php
function __autoload($class_name) {
    require_once('cls/class.' . strtolower($class_name) . '.php');
}
$session = new SessionManager();
$users = new Users(); $doanra = new DoanRa();
require_once('inc/functions.inc.php');
require_once('inc/config.inc.php');
if(!$users->isLoggedIn()){ transfers_to('./login.php'); }
$canbo = new CanBo(); 
$id_canbo = isset($_GET['id_canbo']) ? trim($_GET['id_canbo']) : '';
$id_donvi = isset($_GET['id_donvi']) ? trim($_GET['id_donvi']) : '';
$id_chucvu = isset($_GET['id_chucvu']) ? trim($_GET['id_chucvu']) : '';
$id = isset($_GET['id']) ? trim($_GET['id']) : '';

$donvi = array('id' => new MongoId($id));
$canbo->id = $id_canbo;
$canbo->donvi = $donvi;
if($doanra->check_donvi_chucvu($id_canbo, $id_donvi, $id_chucvu)){
	echo 'Failed';
} else {
	if($canbo->pull_chucvu()){
		echo 'Sucessful';
	} else {
		echo 'Failed';
	}
}
?>