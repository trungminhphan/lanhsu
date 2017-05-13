<?php
function __autoload($class_name) {
    require_once('cls/class.' . strtolower($class_name) . '.php');
}
$session = new SessionManager();
$users = new Users();
require_once('inc/functions.inc.php');
require_once('inc/config.inc.php');
if(!$users->isLoggedIn()){ transfers_to('./login.php'); }
$donvi = new donvi();$canbo = new CanBo();
$tendonvi = isset($_POST['tendonvi']) ? $_POST['tendonvi'] : '';
$id = isset($_POST['id']) ? $_POST['id'] : '';
$id_root = isset($_POST['id_root']) ? $_POST['id_root'] : '';
$key1 = isset($_POST['key1']) ? $_POST['key1'] : 0;
$key2 = isset($_POST['key2']) ? $_POST['key2'] : 0;
$key3 = isset($_POST['key3']) ? $_POST['key3'] : 0;
$key4 = isset($_POST['key4']) ? $_POST['key4'] : 0;
$level = isset($_POST['level']) ? $_POST['level'] : 0;
$action = isset($_POST['action']) ? $_POST['action'] : '';
$id2 = isset($_POST['id2']) ? $_POST['id2'] : '';
$id3 = isset($_POST['id3']) ? $_POST['id3'] : '';
$id4 = isset($_POST['id4']) ? $_POST['id4'] : '';

$donvi->id = $id_root ? $id_root : $id;
$donvi->ten = trim($tendonvi);

if($action == 'delete'){
	if($canbo->check_dm_donvi($id, $level-1)){
		$arr = array('update' => 'no');
		echo json_encode($arr);
	} else {
		if($level==1){
			if($donvi->delete_level1()){
				$arr = array('id_root' => $id_root, 'txt' => 'Xoá thành công', 'update' => 'ok', 'id2'=>$id2, 'id3'=>$id3,'id4'=>$id4);
				echo json_encode($arr);
			} else {
				$arr = array('id_root' => $id_root, 'txt' => 'Xoá thất bại', 'update'=> 'no', 'id2'=>$id2, 'id3'=>$id3,'id4'=>$id4);
				echo json_encode($arr);
			}
		} else if($level==2){
			$donvi->k2 = $key2; $donvi->id_delete = $id;
			if($donvi->delete_level2()){
				$arr = array('id_root' => $id_root, 'txt' => 'Xoá thành công', 'update' => 'ok', 'id2'=>$id2, 'id3'=>$id3,'id4'=>$id4);
				echo json_encode($arr);
			} else {
				$arr = array('id_root' => $id_root, 'txt' => 'Xoá thất bại', 'update'=> 'no', 'id2'=>$id2, 'id3'=>$id3,'id4'=>$id4);
				echo json_encode($arr);
			}
		} else if($level==3){
			$donvi->k2 = $key2; $donvi->id_delete = $id;
			if($donvi->delete_level3()){
				$arr = array('id_root' => $id_root, 'txt' => 'Xoá thành công', 'update' => 'ok', 'id2'=>$id2, 'id3'=>$id3,'id4'=>$id4);
				echo json_encode($arr);
			} else {
				$arr = array('id_root' => $id_root, 'txt' => 'Xoá thất bại', 'update'=> 'no', 'id2'=>$id2, 'id3'=>$id3,'id4'=>$id4);
				echo json_encode($arr);
			}
		} else if($level==4){
			$donvi->k2 = $key2; $donvi->k3 = $key3; $donvi->id_delete = $id;
			if($donvi->delete_level4()){
				$arr = array('id_root' => $id_root, 'txt' => 'Xoá thành công', 'update' => 'ok', 'id2'=>$id2, 'id3'=>$id3,'id4'=>$id4);
				echo json_encode($arr);
			} else {
				$arr = array('id_root' => $id_root, 'txt' => 'Xoá thất bại', 'update'=> 'no', 'id2'=>$id2, 'id3'=>$id3,'id4'=>$id4);
				echo json_encode($arr);
			}
		} else {
			$arr = array('id_root' => $id_root, 'txt' => 'Xoá thất bại', 'update'=> 'no', 'id2'=>$id2, 'id3'=>$id3,'id4'=>$id4);
			echo json_encode($arr);
		}
	}
} else if($action == 'add'){
	$donvi->id = $id;
	$donvi->ten = trim($tendonvi);
	if($level==1){
		if($donvi->insert_level2()){
			$arr = array('id_root' => $id_root, 'txt' => 'Thêm thành công', 'update' => 'ok', 'id2'=>$id2, 'id3'=>$id3,'id4'=>$id4);
			echo json_encode($arr);
		} else {
			$arr = array('id_root' => $id_root, 'txt' => 'Thêm thất bại', 'update'=> 'no', 'id2'=>$id2, 'id3'=>$id3,'id4'=>$id4);
			echo json_encode($arr);
		}
	} else if($level == 2){
		$donvi->k2 = $key2;
		if($donvi->insert_level3()){
			$arr = array('id_root' => $id_root, 'txt' => 'Thêm thành công', 'update' => 'ok', 'id2'=>$id2, 'id3'=>$id3,'id4'=>$id4);
			echo json_encode($arr);
		} else {
			$arr = array('id_root' => $id_root, 'txt' => 'Thêm thất bại', 'update'=> 'no', 'id2'=>$id2, 'id3'=>$id3,'id4'=>$id4);
			echo json_encode($arr);
		}
	} else if($level==3){
		$donvi->k2 = $key2;$donvi->k3 = $key3;
		if($donvi->insert_level4()){
			$arr = array('id_root' => $id_root, 'txt' => 'Thêm thành công', 'update' => 'ok', 'id2'=>$id2, 'id3'=>$id3,'id4'=>$id4);
			echo json_encode($arr);
		} else {
			$arr = array('id_root' => $id_root, 'txt' => 'Thêm thất bại', 'update'=> 'no', 'id2'=>$id2, 'id3'=>$id3,'id4'=>$id4);
			echo json_encode($arr);
		}
	} else {

	}
} else {
	if($level==1){
		if($donvi->edit_level1()){
			$arr = array('id_root' => $id_root, 'txt' => 'Chỉnh sửa thành công', 'update'=>'ok', 'id2'=>$id2, 'id3'=>$id3,'id4'=>$id4);
			echo json_encode($arr);
		} else{
			$arr = array('id_root' => $id_root, 'txt' => 'Cập nhật không thành công', 'update'=>'no', 'id2'=>$id2, 'id3'=>$id3,'id4'=>$id4);
			echo json_encode($arr);
		}
	} else if($level==2){
		$donvi->k2 = $key2;
		if($donvi->edit_level2()){
			$arr = array('id_root' => $id_root, 'txt' => 'Chỉnh sửa thành công', 'update'=>'ok', 'id2'=>$id2, 'id3'=>$id3,'id4'=>$id4);
			echo json_encode($arr);
		} else{
			$arr = array('id_root' => $id_root, 'txt' => 'Cập nhật không thành công', 'update'=>'no', 'id2'=>$id2, 'id3'=>$id3,'id4'=>$id4);
			echo json_encode($arr);
		}
	} else if($level==3){
		$donvi->k2 = $key2;$donvi->k3 = $key3;
		if($donvi->edit_level3()){
			$arr = array('id_root' => $id_root, 'txt' => 'Chỉnh sửa thành công', 'update'=>'ok', 'id2'=>$id2, 'id3'=>$id3,'id4'=>$id4);
			echo json_encode($arr);
		} else{
			$arr = array('id_root' => $id_root, 'txt' => 'Cập nhật không thành công', 'update'=>'no', 'id2'=>$id2, 'id3'=>$id3,'id4'=>$id4);
			echo json_encode($arr);
		}
	} else if($level==4){
		$donvi->k2 = $key2;$donvi->k3 = $key3;
		$donvi->k4 = $key4;
		if($donvi->edit_level4()){
			$arr = array('id_root' => $id_root, 'txt' => 'Chỉnh sửa thành công', 'update'=>'ok', 'id2'=>$id2, 'id3'=>$id3,'id4'=>$id4);
			echo json_encode($arr);
		} else{
			$arr = array('id_root' => $id_root, 'txt' => 'Cập nhật không thành công', 'update'=>'no', 'id2'=>$id2, 'id3'=>$id3,'id4'=>$id4);
			echo json_encode($arr);
		}
	} 
}

?>