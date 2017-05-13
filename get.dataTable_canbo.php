<?php
require_once('header_none.php');
$start = isset($_GET['start']) ? $_GET['start'] : 0;  
$length = isset($_GET['length']) ? $_GET['length'] : 10; 
$draw = isset($_GET['draw']) ? $_GET['draw'] : 0; 
$keysearch = isset($_GET['search']['value']) ? $_GET['search']['value'] : '';
$condition = array('$or' => array(
	array('passport' => new MongoRegex('/' . $keysearch . '/i')),
	array('cmnd' => new MongoRegex('/' . $keysearch . '/i')),
	array('hoten' => new MongoRegex('/' . $keysearch . '/i')),
	array('code' => array('$eq' => new MongoInt64($keysearch)))));

$canbo = new CanBo();$donvi = new DonVi();$chucvu = new ChucVu();
$canbo_list = $canbo->get_list_to_position_condition($condition, $start, $length);
$recordsTotal = $canbo->count_all();
$recordsFiltered = $canbo->get_totalFilter($condition);
$arr_canbo = array();

if($canbo_list){
	foreach ($canbo_list as $cb) {
		$count = count($cb['passport'])-1;
		if(isset($cb['donvi']) && $cb['donvi']){
			$arr_donvi = sort_array_and_key($cb['donvi'], "ngaynhap", SORT_DESC);
			if(isset($arr_donvi[0]['id_donvi'][0]) && $arr_donvi[0]['id_donvi'][0]){
				$donvi->id = $arr_donvi[0]['id_donvi'][0];$dv=$donvi->get_one();	
				$tendonvi = $dv['ten']; $full_donvi = $donvi->tendonvi($arr_donvi[0]['id_donvi']);
			} else { $tendonvi = ''; $full_donvi='';}
			
			if(isset($arr_donvi[0]['id_chucvu']) && $arr_donvi[0]['id_chucvu']){
				$chucvu->id = $arr_donvi[0]['id_chucvu'];$cv = $chucvu->get_one();
				$tenchucvu = $cv['ten'];
			} else{
				$tenchucvu = '';
			}
		} else {
			$tendonvi='';$tenchucvu='';$full_donvi='';
		}
		if(file_exists($target_images . $cb['hinhanh']) && $cb['hinhanh']){
				$hinh = '<img src="'.$target_images . $cb['hinhanh'] .'" style="height:50px;" />';
			} else {
				$hinh = '';
			}

		$passport = $cb['passport'][$count] ? $cb['passport'][$count] : '';
		if($users->is_student()){
			array_push($arr_canbo,
				array(
					$cb['code'],
					$cb['cmnd'],
					is_array($passport) ? $passport[0] : $passport,
					'<div class="tooltip"><a href="chitietcanbo.php?id='.$cb['_id'].'">'.$cb['hoten'].'</a>
				  		<span class="tooltiptext">'.$hinh . ' ' .$full_donvi.'</span>
					</div>',
					$tendonvi,
					$tenchucvu
				)
			);
		} else {
			array_push($arr_canbo,
				array(
					$cb['code'],
					$cb['cmnd'],
					is_array($passport) ? $passport[0] : $passport,
					'<div class="tooltip"><a href="chitietcanbo.php?id='.$cb['_id'].'">'.$cb['hoten'].'</a>
				  		<span class="tooltiptext">'.$hinh . ' ' .$full_donvi.'</span>
					</div>',
					$tendonvi,
					$tenchucvu,
					'<a href="themcanbo.php?id='.$cb['_id'].'&act=del" onclick="return confirm(\'Chắc chắc xoá?\');" title="Xoá"><span class="mif-bin"></span>',
					'<a href="themcanbo.php?id='.$cb['_id'].'&act=edit" title="Sủa"><span class="mif-pencil"></span></a>'
				)
			);
		}
	}
}
echo json_encode(
  	array('draw' => $draw,
        'recordsTotal' => $recordsTotal,
        'recordsFiltered' => $recordsFiltered,
        'data' => $arr_canbo
    )
);
?>