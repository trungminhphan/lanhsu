<?php
require_once('header_none.php');
require_once('cls/PHPExcel.php');
$canbo = new CanBo();$doanvao=new DoanVao();$donvi = new DonVi();$quocgia=new QuocGia();
$id_donvi='';
if(isset($_GET['submit'])){
	$query = array();
	$id_donvi = isset($_GET['id_donvi']) ? $_GET['id_donvi'] : '';
	$tungay = isset($_GET['tungay']) ? $_GET['tungay'] : '';
	$denngay = isset($_GET['denngay']) ? $_GET['denngay'] : '';
	if(convert_date_dd_mm_yyyy($tungay) > convert_date_dd_mm_yyyy($denngay)){
		$msg = 'Chọn ngày sai hoặc chưa chọn Đơn vị thống kê';
	} else {
		$start_date = new MongoDate(convert_date_dd_mm_yyyy($tungay));
		$end_date = new MongoDate(convert_date_dd_mm_yyyy($denngay));
		array_push($query, array('ngayden' => array('$gte' => $start_date)));
		array_push($query, array('ngaydi' => array('$lte' => $end_date)));
		if($id_donvi){
			array_push($query, array('congvanxinphep.id_donvi.0' => $id_donvi));
		}
		/*if($id_donvi){
			array_push($query, array('$or' => array(array('danhsachdoan.0.id_donvi.0' => $id_donvi), array('danhsachdoan_2.0.id_donvi.0' => $id_donvi))));
		}*/
		$q = array('$and' => $query);
		$union_list = $doanvao->get_list_condition($q);
	}
}

$inputFileName = 'templates/thongkedoanvaotheodonvi.xlsx';
$objPHPExcel = PHPExcel_IOFactory::load($inputFileName);
$objPHPExcel->getProperties()->setCreator("Phan Minh Trung")
							 ->setLastModifiedBy("Phan Minh Trung")
							 ->setTitle("Office 2007 XLSX Test Document")
							 ->setSubject("Office 2007 XLSX Test Document")
							 ->setDescription("Test document for Office 2007 XLSX, generated using PHP classes.")
							 ->setKeywords("office 2007 openxml php")
							 ->setCategory("Quan ly Lanh su");

$objPHPExcel->setActiveSheetIndex(0);


if(isset($union_list) && $union_list->count() > 0){
	if(isset($id_donvi) && $id_donvi){
		$donvi->id = $id_donvi; $dv = $donvi->get_one();
		$objPHPExcel->setActiveSheetIndex()->setCellValue('A1', $dv['ten'] . ' ' . $union_list->count() . ' lượt');
	}

	$i = 3; $stt=1;
	foreach ($union_list as $u) {
		if(isset($u['danhsachdoan'][0]['id_canbo']) && $u['danhsachdoan'][0]['id_canbo']){
			$canbo->id = $u['danhsachdoan'][0]['id_canbo']; $cb = $canbo->get_one();
			$tentruongdoan = $cb['hoten'];
		} else {
			$tentruongdoan = '';
		}
		$congvanxinphep = $u['congvanxinphep']['ten'];
		$soquyetdinh = $u['quyetdinhchophep']['ten'];
		$ngayden = $u['ngayden'] ? date("d/m/Y", $u['ngayden']->sec) : '';
		$ngaydi = $u['ngaydi'] ? date("d/m/Y", $u['ngaydi']->sec) : '';
		if(isset($cb['id_quoctich']) && $cb['id_quoctich']){
			$quocgia->id = $cb['id_quoctich']; $qt = $quocgia->get_one();
			$tenquoctich = $qt['ten'];
		} else { $tenquoctich = ''; }
		if(isset($u['danhsachdoan'][0]['id_donvi']) && $u['danhsachdoan'][0]['id_donvi']){
			$donvitruongdoan = $donvi->tendonvi($u['danhsachdoan'][0]['id_donvi']);
		} else {
			$donvitruongdoan = '';
		}
		if($u['congvanxinphep']['id_donvi']){
			$tendonvi = $donvi->tendonvi($u['congvanxinphep']['id_donvi']);
		} else { $tendonvi = '';}

		$objPHPExcel->setActiveSheetIndex()->setCellValue('A'.$i, $stt);
		$objPHPExcel->setActiveSheetIndex()->setCellValue('B'.$i, $tentruongdoan);
		$objPHPExcel->setActiveSheetIndex()->setCellValue('C'.$i, $donvitruongdoan);
		$objPHPExcel->setActiveSheetIndex()->setCellValue('D'.$i, $tenquoctich);
		$objPHPExcel->setActiveSheetIndex()->setCellValue('E'.$i, $tendonvi);
		$objPHPExcel->setActiveSheetIndex()->setCellValue('F'.$i, $congvanxinphep);
		$objPHPExcel->setActiveSheetIndex()->setCellValue('G'.$i, $soquyetdinh);
		$objPHPExcel->setActiveSheetIndex()->setCellValue('H'.$i, $ngayden);
		$objPHPExcel->setActiveSheetIndex()->setCellValue('I'.$i, $ngaydi);
		$objPHPExcel->setActiveSheetIndex()->setCellValue('J'.$i, $u['noidung']);
		$i++;$stt++;
	}
}

// Redirect output to a client’s web browser (Excel2007)
//header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
header('Content-Type: application/vnd.ms-excel');
header('Content-Disposition: attachment;filename="thongkedoanvaotheodonvi_'.date("Ymdhis").'.xlsx"');
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
