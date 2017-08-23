<?php
require_once('header_none.php');
require_once('cls/PHPExcel.php');
$canbo = new CanBo();$doanvao=new DoanVao();$quocgia=new QuocGia();
$id_canbo ='';$id_quocgia='';$id_kinhphi='';
if(isset($_GET['submit'])){
	$query = array();
	$id_canbo = isset($_GET['id_canbo']) ? $_GET['id_canbo']  : '';
	$tungay = isset($_GET['tungay']) ? $_GET['tungay'] : '';
	$denngay = isset($_GET['denngay']) ? $_GET['denngay'] : '';
	$id_quocgia = isset($_GET['id_quocgia']) ? $_GET['id_quocgia'] : '';
	$id_kinhphi = isset($_GET['id_kinhphi']) ? $_GET['id_kinhphi'] : '';

	if(convert_date_dd_mm_yyyy($tungay) > convert_date_dd_mm_yyyy($denngay) || !$id_canbo){
		$msg = 'Chọn ngày sai hoặc chưa chọn Cá nhân thống kê';
	} else {
		$start_date = new MongoDate(convert_date_dd_mm_yyyy($tungay));
		$end_date = new MongoDate(convert_date_dd_mm_yyyy($denngay));
		array_push($query, array('ngayden' => array('$gte' => $start_date)));
		array_push($query, array('ngaydi' => array('$lte' => $end_date)));
		if($id_canbo){
			$arr_cb = array('$or' => array(array('danhsachdoan.id_canbo' => new MongoId($id_canbo)), array('danhsachdoan_2.id_canbo'=> new MongoId($id_canbo))));
			array_push($query, $arr_cb);
		}
		$q = array('$and' => $query);
		$union_list = $doanvao->get_list_condition($q);
	}
}

$inputFileName = 'templates/thongkedoanvaotheocanhan.xlsx';
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
	if(isset($id_canbo) && $id_canbo){
		$canbo->id = $id_canbo; $cb = $canbo->get_one();
		if($cb['id_quoctich']){
			$quocgia->id = $cb['id_quoctich']; $qt = $quocgia->get_one();
			$tenquoctich = $qt['ten'];
		}  else {
			$tenquoctich = '';
		}
		$objPHPExcel->setActiveSheetIndex()->setCellValue('A1', $cb['hoten'] . ' ' .$union_list->count() . ' lượt nhập cảnh');
		$objPHPExcel->setActiveSheetIndex()->setCellValue('A2', 'Quốc tịch: '.$tenquoctich);
	}
	$i = 4; $stt=1;
	foreach ($union_list as $u) {
		$congvanxinphep = $u['congvanxinphep']['ten'];
		$soquyetdinh = $u['quyetdinhchophep']['ten'];
		$ngayden = $u['ngayden'] ? date("d/m/Y", $u['ngayden']->sec) : '';
		$ngaydi = $u['ngaydi'] ? date("d/m/Y", $u['ngaydi']->sec) : '';

		$objPHPExcel->setActiveSheetIndex()->setCellValue('A'.$i, $stt);
		$objPHPExcel->setActiveSheetIndex()->setCellValue('B'.$i, $congvanxinphep);
		$objPHPExcel->setActiveSheetIndex()->setCellValue('C'.$i, $soquyetdinh);
		$objPHPExcel->setActiveSheetIndex()->setCellValue('D'.$i, $ngayden);
		$objPHPExcel->setActiveSheetIndex()->setCellValue('E'.$i, $ngaydi);
		$objPHPExcel->setActiveSheetIndex()->setCellValue('F'.$i, $u['noidung']);
		$i++;$stt++;
	}
}

// Redirect output to a client’s web browser (Excel2007)
//header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
header('Content-Type: application/vnd.ms-excel');
header('Content-Disposition: attachment;filename="thongkedoanvaotheocanhan_'.date("Ymdhis").'.xlsx"');
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