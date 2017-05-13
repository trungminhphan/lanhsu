<?php
function __autoload($class_name) {
    require_once('cls/class.' . strtolower($class_name) . '.php');
}
$session = new SessionManager();
$users = new Users();
require_once('inc/functions.inc.php');
require_once('inc/config.inc.php');
if(!$users->isLoggedIn()){ transfers_to('./login.php'); }
$donvi = new donvi();
$id1 = isset($_GET['id1']) ? $_GET['id1'] : '';
$id2 = isset($_GET['id2']) ? $_GET['id2'] : '';
$id3 = isset($_GET['id3']) ? $_GET['id3'] : '';

$level = isset($_GET['level']) ? $_GET['level'] : '';
$donvi->id = $id1; $dv=$donvi->get_one();
//$str_null = '<option value="">Chọn đơn vị</option>';
$str_donvi_2 = '<option value="" selected>Chọn đơn vị</option>'; $str_donvi_3 = '<option value="" selected>Chọn đơn vị</option>';$str_donvi_4 = '<option value="" selected>Chọn đơn vị</option>';
if(isset($dv['level2']) && $dv['level2'] && $dv['_id'] == $id1){
	foreach ($dv['level2'] as $k2 => $a2) {
		$str_donvi_2 .= '<option value="'.$a2['_id'].'">'.$a2['ten'].'</option>';
		if(isset($a2['level3']) && $a2['level3'] && $a2['_id'] == $id2){
			foreach ($a2['level3'] as $k3 => $a3) {
				$str_donvi_3 .= '<option value="'.$a3['_id'].'">'.$a3['ten'].'</option>';
				if(isset($a3['level4']) && $a3['level4'] && $a3['_id'] == $id3){
					foreach ($a3['level4'] as $k4 => $a4) {
						$str_donvi_4 .= '<option value="'.$a4['_id'].'">'.$a4['ten'].'</option>';
					}
				}
			}
		}
	}
}
$donvi_arr = array(
				'id_donvi_1' => '',
				'id_donvi_2' => '',
				'id_donvi_3' => '',
				'id_donvi_4' => '',
				'str_donvi_2' => $str_donvi_2,
				'str_donvi_3' => $str_donvi_3,
				'str_donvi_4' => $str_donvi_4);
echo json_encode($donvi_arr);
?>