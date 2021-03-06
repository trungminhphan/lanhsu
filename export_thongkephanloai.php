<?php
require_once('header_none.php');
require_once('cls/PHPExcel.php');

$inputFileName = 'templates/thongkephanloai.xlsx';
$objPHPExcel = PHPExcel_IOFactory::load($inputFileName);
$objPHPExcel->getProperties()->setCreator("Phan Minh Trung")
							 ->setLastModifiedBy("Phan Minh Trung")
							 ->setTitle("Office 2007 XLSX Test Document")
							 ->setSubject("Office 2007 XLSX Test Document")
							 ->setDescription("Test document for Office 2007 XLSX, generated using PHP classes.")
							 ->setKeywords("office 2007 openxml php")
							 ->setCategory("Quan ly Lanh su");

$objPHPExcel->setActiveSheetIndex(0);

$canbo = new CanBo(); $doanra=new DoanRa();$quocgia = new QuocGia();
$query_canbo = array();
$quocgia_list = $quocgia->get_all_list();
$congchuc='';$vienchuc='';$dangvien=0;$tinhuyvien=0;
if(isset($_GET['submit'])){
	$congchuc = isset($_GET['congchuc']) ? $_GET['congchuc'] : '';
	$vienchuc = isset($_GET['vienchuc']) ? $_GET['vienchuc'] : '';
	$dangvien = isset($_GET['dangvien']) ? $_GET['dangvien'] : '';
	$tinhuyvien = isset($_GET['tinhuyvien']) ? $_GET['tinhuyvien'] : '';
	$ngoainuoc = isset($_GET['ngoainuoc']) ? $_GET['ngoainuoc'] : '';
	$tungay = isset($_GET['tungay']) ? $_GET['tungay'] : '';
	$denngay = isset($_GET['denngay']) ? $_GET['denngay'] : '';
	if(convert_date_dd_mm_yyyy($tungay) > convert_date_dd_mm_yyyy($denngay)){
		$msg = 'Chọn sai ngày thống kê.';
	} else {
		if($ngoainuoc){
			$query = array('$and' => array(array('id_quoctich' => array('$ne' => '56f9fd7732341c4008002015')), array('id_quoctich' => array('$ne' => new MongoId('56f9fd7732341c4008002015')))));
			$canbo_list = $canbo->get_list_condition($query);
		} else {
			if($congchuc == 'CC'){
				array_push($query_canbo, array('loaicongchuc' => 'CC'));
			}
			if($vienchuc == 'VC'){
				array_push($query_canbo, array('loaicongchuc' => 'VC'));
			}
			if($dangvien == 1){
				array_push($query_canbo, array('dangvien' => '1'));
			}
			if($tinhuyvien == 1){
				array_push($query_canbo, array('tinhuyvien' => '1'));
			}
			if(count($query_canbo) > 0)
			$canbo_list = $canbo->get_list_condition(array('$and' => $query_canbo));
		}
		$start_date = new MongoDate(convert_date_dd_mm_yyyy($tungay));
		$end_date = new MongoDate(convert_date_dd_mm_yyyy($denngay));
	}
} else {
	$canbo_list = '';
}

if(isset($canbo_list) && $canbo_list->count() >0){
	$i=2;$stt=1;
	if($ngoainuoc){
		$donvi = new DonVi();$chucvu = new ChucVu();$count=0;$doanvao = new DoanVao();
		$query_date = array(array('ngayden' => array('$gte' => $start_date)), array('ngaydi' => array('$lte' => $end_date)));
		foreach ($canbo_list as $cb) {
			$query_check = $query_date;
			$arr_cb = array('$or' => array(array('danhsachdoan.id_canbo' => new MongoId($cb['_id'])), array('danhsachdoan_2.id_canbo'=> new MongoId($cb['_id']))));
			array_push($query_check, $arr_cb);
			$q = array('$and' => $query_check);
			$count = $doanvao->count_soluong($q);
			if($count){
				if(isset($cb['donvi']) && $cb['donvi']){
					if(isset($cb['donvi'][0]['id_donvi'][0]) && $cb['donvi'][0]['id_donvi'][0]){
						$c = count($cb['donvi']) - 1;
						$tendonvi = $donvi->tendonvi($cb['donvi'][$c]['id_donvi']);
					} else { $tendonvi = ''; $full_donvi='';}
					if(isset($cb['donvi'][0]['id_chucvu']) && $cb['donvi'][0]['id_chucvu']){
						$c = count($cb['donvi']) - 1;
						$chucvu->id = $cb['donvi'][$c]['id_chucvu'];$cv = $chucvu->get_one();
						$tenchucvu = $cv['ten'];
					} else{
						$tenchucvu = '';
					}
				} else {
					$tendonvi='';$tenchucvu='';
				}
				$objPHPExcel->setActiveSheetIndex()->setCellValue('A'.$i, $cb['code']);
				$objPHPExcel->setActiveSheetIndex()->setCellValue('B'.$i, $cb['hoten']);
				$objPHPExcel->setActiveSheetIndex()->setCellValue('C'.$i, $tendonvi);
				$objPHPExcel->setActiveSheetIndex()->setCellValue('D'.$i, $tenchucvu);
				$objPHPExcel->setActiveSheetIndex()->setCellValue('E'.$i, $count);
				$i++;$stt++;
			}
		}
	} else {
		$query_date = array(array('ngaydi' => array('$gte' => $start_date)), array('ngayve' => array('$lte' => $end_date)));
		foreach ($canbo_list as $cb) {
			$query_check = $query_date;
			$arr_cb = array('$or' => array(array('danhsachdoan.id_canbo' => new MongoId($cb['_id'])), array('danhsachdoan_2.id_canbo'=> new MongoId($cb['_id']))));
			array_push($query_check, $arr_cb);
			$q = array('$and' => $query_check);
			if($doanra->check_thongkephanloai($q)){
				if($cb['donvi']){
					$donvi = new DonVi();$chucvu = new ChucVu();
					if(isset($cb['donvi'][0]['id_donvi'][0]) && $cb['donvi'][0]['id_donvi'][0]){
						$c = count($cb['donvi']) - 1;
						$tendonvi = $donvi->tendonvi($cb['donvi'][$c]['id_donvi']);
					} else { $tendonvi = ''; $full_donvi='';}
					if(isset($cb['donvi'][0]['id_chucvu']) && $cb['donvi'][0]['id_chucvu']){
						$c = count($cb['donvi']) - 1;
						$chucvu->id = $cb['donvi'][$c]['id_chucvu'];$cv = $chucvu->get_one();
						$tenchucvu = $cv['ten'];
					} else{
						$tenchucvu = '';
					}
				} else {
					$tendonvi='';$tenchucvu='';
				}
				$objPHPExcel->setActiveSheetIndex()->setCellValue('A'.$i, $cb['code']);
				$objPHPExcel->setActiveSheetIndex()->setCellValue('B'.$i, $cb['hoten']);
				$objPHPExcel->setActiveSheetIndex()->setCellValue('C'.$i, $tendonvi);
				$objPHPExcel->setActiveSheetIndex()->setCellValue('D'.$i, $tenchucvu);
				$i++;$stt++;
			}
		}
	}
}


// Redirect output to a client’s web browser (Excel2007)
//header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
header('Content-Type: application/vnd.ms-excel');
header('Content-Disposition: attachment;filename="thongkephanloai_'.date("Ymdhis").'.xlsx"');
header('Cache-Control: max-age=0');
// If you're serving to IE 9, then the following may be needed
header('Cache-Control: max-age=1');

// If you're serving to IE over SSL, then the following may be needed
header ('Expires: Mon, 26 Jul 1997 05:00:00 GMT'); // Date in the past
header ('Last-Modified: '.gmdate('D, d M Y H:i:s').' GMT'); // always modified
header ('Cache-Control: cache, must-revalidate'); // HTTP/1.1
header ('Pragma: public'); // HTTP/1.0
$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
//$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
$objWriter->save('php://output');
exit;
?>
