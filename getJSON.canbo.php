<?php
require_once('header_none.php');
$canbo = new CanBo();$chucvu=new ChucVu();$donvi=new DonVi();$ham=new Ham();
$q = isset($_GET['q']) ? $_GET['q'] : '';
$page = isset($_GET['page']) ? $_GET['page'] : 0;
$condition = array('$or' => array(array('hoten' => new MongoRegex('/' . $q . '/i')), array('code' => array('$eq' => intval($q)))));
$total_count = $canbo->get_list_condition($condition)->count();
$list_canbo = $canbo->get_list_to_position_condition($condition, $page, 30);
$arr = array();

if($list_canbo){
	foreach ($list_canbo as $cb) {//$id_congdan = $cd['_id']->$id;
        //echo json_encode($cd['_id']->{'$id'});
		$total_count += count($cb['donvi'])	- 1;
		foreach ($cb['donvi'] as $key => $value) {
			if(isset($value['id_ham'])) $id_ham = $value['id_ham']; else $id_ham='';
			$v = $cb['_id'] . '-' . implode(",", $value['id_donvi']) . '-' . $value['id_chucvu'] . '-'.$id_ham;
			if($value['id_donvi'][0]){
				$tendonvi = $donvi->tendonvi($value['id_donvi']);
			} else { $tendonvi = 'Không biết'; }
			if(isset($value['id_chucvu']) && $value['id_chucvu']){
				$chucvu->id = $value['id_chucvu']; $cv = $chucvu->get_one();
				$tenchucvu = $cv['ten'];
			} else {
				$tenchucvu = '';
			}
			array_push($arr, array(
	     		'id' => $v, 
	     		'hoten' => $cb['hoten'], 
	     		'code' => $cb['code'], 
	     		'text' => $cb['hoten'] . ' [ID: ' . $cb['code'] . ']',
	     		'donvi' => $tendonvi,
	     		'chucvu' => $tenchucvu
	     	));
		}
    }
}

echo json_encode(array(
	"total_count" => $total_count,
  	"incomplete_results" => false,
	"items" => $arr));
?>