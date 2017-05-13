<?php
function __autoload($class_name) {
    require_once('cls/class.' . strtolower($class_name) . '.php');
}
$session = new SessionManager();
$users = new Users();
require_once('inc/functions.inc.php');
require_once('inc/config.inc.php');
if(!$users->isLoggedIn()){ transfers_to('./login.php'); }
$canbo = new CanBo(); $donvi_class = new DonVi(); $chucvu = new ChucVu();
$id = isset($_POST['id']) ? trim($_POST['id']) : ''; //id_post_donvi_chucvu.
$id_canbo = isset($_POST['id_canbo']) ? trim($_POST['id_canbo']) : '';
$id_donvi_1 = isset($_POST['id_donvi_1']) ? $_POST['id_donvi_1'] : '';
$id_donvi_2 = isset($_POST['id_donvi_2']) ? $_POST['id_donvi_2'] : '';
$id_donvi_3 = isset($_POST['id_donvi_3']) ? $_POST['id_donvi_3'] : '';
$id_donvi_4 = isset($_POST['id_donvi_4']) ? $_POST['id_donvi_4'] : '';
$id_donvi = array($id_donvi_1,$id_donvi_2,$id_donvi_3,$id_donvi_4);
$id_chucvu = isset($_POST['id_chucvu']) ? $_POST['id_chucvu'] : '';
$id_ham = isset($_POST['id_ham']) ? $_POST['id_ham'] : '';
$ngaynhap = isset($_POST['ngaynhap']) ? $_POST['ngaynhap'] : '';

$canbo->id = $id_canbo;
if($id){
	$arr_donvi = array(
		'donvi.$.id_donvi' => $id_donvi,
		'donvi.$.id_chucvu' => new MongoId($id_chucvu),
		'donvi.$.id_ham' => $id_ham ? new MongoId($id_ham) : '',
		'donvi.$.ngaynhap' => $ngaynhap ? new MongoDate(convert_date_dd_mm_yyyy($ngaynhap)) : new MongoDate()
	);
	$canbo->donvi = $arr_donvi;
	if($canbo->set_chucvu($id)){
		echo 'Sucessful';
	} else {
		echo 'Failed';
	}
} else {
	$arr_donvi = array('id' => new MongoId(),
				'id_donvi' => $id_donvi,
				'id_chucvu' => $id_chucvu ? new MongoId($id_chucvu) : '',
				'id_ham' => $id_ham ? new MongoId($id_ham) : '',
				'ngaynhap' => $ngaynhap ? new MongoDate(convert_date_dd_mm_yyyy($ngaynhap)) : new MongoDate());

	$canbo->donvi = $arr_donvi;
	if($canbo->push_chucvu()){
		echo 'Sucessful';
	} else {
		echo 'Failed';
	}
}
?>