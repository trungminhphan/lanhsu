<?php
require_once('header_none.php');
$doanvao = new DoanVao();$donvi = new DonVi();$dmdoanvao = new DMDoanVao();$canbo = new CanBo();$mucdich = new MucDich();$linhvuc = new LinhVuc();
$doanvao_list = $doanvao->get_all_list();
require_once('cls/PHPExcel.php');
$inputFileName = 'templates/export_doanvao.xlsx';
$objPHPExcel = PHPExcel_IOFactory::load($inputFileName);
$objPHPExcel->getProperties()->setCreator("Phan Minh Trung")
							 ->setLastModifiedBy("Phan Minh Trung")
							 ->setTitle("Office 2007 XLSX Test Document")
							 ->setSubject("Office 2007 XLSX Test Document")
							 ->setDescription("Test document for Office 2007 XLSX, generated using PHP classes.")
							 ->setKeywords("office 2007 openxml php")
							 ->setCategory("Quan ly Lanh su");

$objPHPExcel->setActiveSheetIndex(0);
if($doanvao_list){
	$i=3; $stt=1;
	foreach ($doanvao_list as $dv) {
		if(isset($dv['id_dmdoanvao']) && $dv['id_dmdoanvao']){
			$dmdoanvao->id = $dv['id_dmdoanvao'];$dm=$dmdoanvao->get_one();
			$tendoanvao = $dm['ten'];
		} else {
			$tendoanvao = '';
		}
		if(isset($dv['danhsachdoan']) && $dv['danhsachdoan']){
			$canbo->id = $dv['danhsachdoan'][0]['id_canbo'];$cb=$canbo->get_one();
			$tentruongdoan = $cb['hoten'];
		} else { $tentruongdoan = '';}
		$ngayden = isset($dv['ngayden']) ? date("d/m/Y", $dv['ngayden']->sec) : '';
		$ngaydi = isset($dv['ngaydi']) ? date("d/m/Y", $dv['ngaydi']->sec) : '';

		if(isset($dv['id_mucdich']) && $dv['id_mucdich']){
			$mucdich->id = $dv['id_mucdich']; $md = $mucdich->get_one();
			$tenmucdich = $md['ten'];
		} else { $tenmucdich = '';  }

		if(isset($dv['id_linhvuc']) && $dv['id_linhvuc']){
			$linhvuc->id = $dv['id_linhvuc']; $lv = $linhvuc->get_one();
			$tenlinhvuc = $lv['ten'];
		} else { $tenlinhvuc = '';  }
		if(isset($dv['noidung'])) $noidung = $dv['noidung'];
		else $noidung = '';
		$objPHPExcel->setActiveSheetIndex()->setCellValue('A'.$i, $stt);
		$objPHPExcel->setActiveSheetIndex()->setCellValue('B'.$i, $tendoanvao);
		$objPHPExcel->setActiveSheetIndex()->setCellValue('C'.$i, $tentruongdoan);
		$objPHPExcel->setActiveSheetIndex()->setCellValue('D'.$i, $ngayden);
		$objPHPExcel->setActiveSheetIndex()->setCellValue('E'.$i, $ngaydi);
		$objPHPExcel->setActiveSheetIndex()->setCellValue('F'.$i, $dv['congvanxinphep']['ten']);
		$objPHPExcel->setActiveSheetIndex()->setCellValue('G'.$i, $dv['quyetdinhchophep']['ten']);
		$objPHPExcel->setActiveSheetIndex()->setCellValue('H'.$i, $tenmucdich);
		$objPHPExcel->setActiveSheetIndex()->setCellValue('I'.$i, $tenlinhvuc);
		$objPHPExcel->setActiveSheetIndex()->setCellValue('J'.$i, $noidung);
		$i++; $stt++;
	}
}
header('Content-Type: application/vnd.ms-excel');
header('Content-Disposition: attachment;filename="DoanVao_'.date("Ymdhis").'.xlsx"');
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
