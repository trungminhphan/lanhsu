<?php
require_once('header_none.php');
$donvi = new DonVi();
$q = isset($_GET['q']) ? $_GET['q'] : '';
$page = isset($_GET['page']) ? $_GET['page'] : 0;
$condition = array('ten' => new MongoRegex('/'.$q.'/i'));

$total_count = $donvi->get_list_condition($condition)->count();
$list_donvi = $donvi->get_list_to_position_condition($condition, $page, 30);
$arr = array();

if($list_donvi){
	foreach ($list_donvi as $dv) {
		array_push($arr, array(
     		'id' => $dv['_id']->{'$id'},
     		'tendonvi' => $dv['ten'], 
     		'text' => $dv['ten']
     	));
	}
}
echo json_encode(array(
	"total_count" => $total_count,
  	"incomplete_results" => false,
	"items" => $arr));
?>