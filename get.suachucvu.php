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
$id = isset($_GET['id']) ? trim($_GET['id']) : '';
$id_canbo = isset($_GET['id_canbo']) ? trim($_GET['id_canbo']) : '';
$id_donvi = isset($_GET['id_donvi']) ? $_GET['id_donvi'] : '';
$arr_donvi = explode(",", $id_donvi);
$id_chucvu = isset($_GET['id_chucvu']) ? $_GET['id_chucvu'] : '';
$id_ham = isset($_GET['id_ham']) ? $_GET['id_ham'] : '';
$ngaynhap = isset($_GET['ngaynhap']) ? $_GET['ngaynhap'] : '';

$donvi_class->id = $arr_donvi[0]; $dv = $donvi_class->get_one();

$str_donvi_2 = '';$str_donvi_3 = '';$str_donvi_4 = '';

if(isset($dv['level2']) && $dv['level2']){
	foreach ($dv['level2'] as $k2=> $a2) {
		$str_donvi_2 .= '<option value="'.$a2['_id'].'"'.($a2['_id'] == $arr_donvi[1] ? ' selected' :'').'>'.$a2['ten'].'</option>';
		if(isset($a2['level3']) && $a2['level3'] && $a2['_id'] == $arr_donvi[1]){
			foreach ($a2['level3'] as $k3 => $a3) {
				$str_donvi_3 .= '<option value="'.$a3['_id'].'">'.$a3['ten'].'</option>';
				if(isset($a3['level4']) && $a3['level4'] && $a3['_id'] == $arr_donvi[2]){
					foreach ($a3['level4'] as $k4=>$a4) {
						$str_donvi_4 .= '<option value="'.$a4['_id'].'">'.$a4['ten'].'</option>';
					}
				}
			}
		}
	}
}

$donvi = array('id' => $id,
				'id_donvi_1' => $arr_donvi[0],
				'id_donvi_2' => $arr_donvi[1],
				'id_donvi_3' => $arr_donvi[2],
				'id_donvi_4' => $arr_donvi[3],
				'id_chucvu' => $id_chucvu,
				'id_ham' => $id_ham,
				'ngaynhap' => $ngaynhap,
				'str_donvi_2' => $str_donvi_2,
				'str_donvi_3' => $str_donvi_3,
				'str_donvi_4' => $str_donvi_4);
echo json_encode($donvi);
?>