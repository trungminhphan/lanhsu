<?php
require_once('header_none.php');
require_once('cls/PHPExcel.php');

$canbo = new CanBo();
$query = array('$or' => array(array('id_quoctich' => array('$eq' => '56f9fd7732341c4008002015')), array('id_quoctich' => array('$eq' => new MongoId('56f9fd7732341c4008002015')))));
$canbo_list = $canbo->get_list_condition_huu($query);

if($canbo_list){
	$inputFileName = 'templates/thongketuoihuu.xlsx';
	$objPHPExcel = PHPExcel_IOFactory::load($inputFileName);

	$objPHPExcel->getProperties()->setCreator("Phan Minh Trung")
							 ->setLastModifiedBy("Phan Minh Trung")
							 ->setTitle("Office 2007 XLSX Test Document")
							 ->setSubject("Office 2007 XLSX Test Document")
							 ->setDescription("Test document for Office 2007 XLSX, generated using PHP classes.")
							 ->setKeywords("office 2007 openxml php")
							 ->setCategory("Quan ly Lanh su");
	$objPHPExcel->setActiveSheetIndex(0);

	$i = 3; $stt = 1;
	foreach ($canbo_list as $cb) {
		$ngaysinh = $cb['ngaysinh'] ? date("Y-m-d", $cb['ngaysinh']->sec) : '';
		if($ngaysinh){
			$date1=date_create($ngaysinh);
			$date2=date_create(date("Y-m-d"));
			$diff=date_diff($date1,$date2);
			$songay = $diff->format("%a");
			$tuoi = round($songay/365, 0);
			if($cb['gioitinh'] == 'Nam'){
				$sonamsaphuu = 60 - $tuoi;
			} else {
				$sonamsaphuu = 55 - $tuoi;
			}
		} else {
			$tuoi = '';$sonamsaphuu = '';
		}

		$objPHPExcel->setActiveSheetIndex()->setCellValue('A'.$i, $stt);
		$objPHPExcel->setActiveSheetIndex()->setCellValue('B'.$i, $cb['hoten']);
		$objPHPExcel->setActiveSheetIndex()->setCellValue('C'.$i, $cb['gioitinh']);
		$objPHPExcel->setActiveSheetIndex()->setCellValue('D'.$i, $cb['ngaysinh'] ? date("d/m/Y", $cb['ngaysinh']->sec) : '');
		$objPHPExcel->setActiveSheetIndex()->setCellValue('E'.$i, $tuoi);
		$objPHPExcel->setActiveSheetIndex()->setCellValue('F'.$i, $sonamsaphuu);
		$stt++; $i++;
	}
}
// Redirect output to a client’s web browser (Excel2007)
//header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
header('Content-Type: application/vnd.ms-excel');
header('Content-Disposition: attachment;filename="thongketuoihuu_'.date("Ymdhis").'.xlsx"');
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