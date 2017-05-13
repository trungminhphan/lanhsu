<?php
function __autoload($class_name) {
    require_once('cls/class.' . strtolower($class_name) . '.php');
}
$session = new SessionManager();
$users = new Users();
require_once('inc/functions.inc.php');
require_once('inc/config.inc.php');
if(!$users->isLoggedIn()){ transfers_to('./login.php'); }
$canbo = new CanBo();$chucvu=new ChucVu();$donvi=new DonVi();$ham=new Ham();
$q = isset($_GET['q']) ? $_GET['q'] : '';
$page = isset($_GET['page']) ? $_GET['page'] : 0;

$condition = array('$or' => array(array('hoten' => new MongoRegex('/' . $q . '/i')), array('code' => array('$eq' => intval($q)))));

$total_count = $canbo->get_list_condition($condition)->count();
$list_canbo = $canbo->get_list_to_position_condition($condition, $page, 30);

$arr = array();
if($list_canbo){
	foreach ($list_canbo as $cb) {
		array_push($arr, array(
     		'id' => $cb['_id']->{'$id'}, 
     		'hoten' => $cb['hoten'], 
     		'code' => $cb['code'], 
     		'text' => $cb['hoten'] . ' [ID: ' . $cb['code'] . ']'
     	));
    }
}
echo json_encode(array(
	"total_count" => $total_count,
  	"incomplete_results" => false,
	"items" => $arr));
?>