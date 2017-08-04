<?php
require_once('header_none.php');
$id = isset($_GET['id']) ? $_GET['id'] : '';
$act = isset($_GET['act']) ? $_GET['act'] : '';
$key = isset($_GET['key']) ? $_GET['key'] : 0;

if($act == 'doanra'){
	$doan = new DoanRa_Regis();
} 
if($act == 'doanvao'){
	$doan = new DoanVao_Regis();
} 
if($act == 'abtc'){
	$doan = new ABTC_Regis();
} 

$doan->id = $id;
$d = $doan->get_one();
$arr = array(
	'id' => strval($d['_id']),
	't' => $d['status'][$key]['t'],
	'noidung' => $d['status'][$key]['noidung'],
	'k' => $key
);
echo json_encode($arr);
?>