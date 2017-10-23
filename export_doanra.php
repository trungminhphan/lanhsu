<?php
require_once('header_none.php');
$doanra = new DoanRa();$donvi = new DonVi();$canbo = new CanBo();
$quocgia = new QuocGia();$mucdich = new MucDich();$kinhphi = new KinhPhi();
$doanra_list = $doanra->get_all_list();
require_once('cls/PHPExcel.php');
$inputFileName = 'templates/export_doanra.xlsx';
$objPHPExcel = PHPExcel_IOFactory::load($inputFileName);
$objPHPExcel->getProperties()->setCreator("Phan Minh Trung")
							 ->setLastModifiedBy("Phan Minh Trung")
							 ->setTitle("Office 2007 XLSX Test Document")
							 ->setSubject("Office 2007 XLSX Test Document")
							 ->setDescription("Test document for Office 2007 XLSX, generated using PHP classes.")
							 ->setKeywords("office 2007 openxml php")
							 ->setCategory("Quan ly Lanh su");

$objPHPExcel->setActiveSheetIndex(0);
if($doanra_list){
	$i=3; $stt=1;
	foreach ($doanra_list as $dr) {
		if(isset($dr['congvanxinphep']['id_donvi'][0]) && $dr['congvanxinphep']['id_donvi'][0]){
			$donvi->id = $dr['congvanxinphep']['id_donvi'][0];$dv = $donvi->get_one();
			$tendonvi=$dv['ten'];
		} else {$tendonvi='';}
		if($dr['danhsachdoan'] && isset($dr['danhsachdoan'][0]['id_canbo']) && $dr['danhsachdoan'][0]['id_canbo']){
			$canbo->id = $dr['danhsachdoan'][0]['id_canbo'];$cb=$canbo->get_one();
			$tentruongdoan = $cb['hoten'];
		} else { $tentruongdoan = '';}
		if(isset($dr['id_mucdich']) && $dr['id_mucdich']){
			$mucdich->id = $dr['id_mucdich']; $md = $mucdich->get_one();
			$tenmucdich = $md['ten'];
		} else { $tenmucdich = '';  }
		if(isset($dr['sotien']['VND'])) $sotien = $dr['sotien']['VND']; else $sotien = '';
		if(isset($dr['id_kinhphi']) && $dr['id_kinhphi']){
			$kinhphi->id = $dr['id_kinhphi']; $kp = $kinhphi->get_one();
			$tenkinhphi = $kp['ten'];
		} else { $tenkinhphi = '';  }
		$ngaydi = $dr['ngaydi'] ? date("d/m/Y",$dr['ngaydi']->sec) : '';
		$ngayve = $dr['ngayve'] ? date("d/m/Y",$dr['ngayve']->sec) : '';
		$nuocden = $quocgia->get_quoctich($dr['id_quocgia']);
		if(isset($dr['id_donvimoi']) && is_array($dr['id_donvimoi'])){
			$donvimoi = $donvi->tendonvi($dr['id_donvimoi']);
		} else { $donvimoi = ''; }
		if(isset($dr['baocao']['noidung'])) $baocao = $dr['baocao']['noidung'];else $baocao = '';

		$objPHPExcel->setActiveSheetIndex()->setCellValue('A'.$i, $stt);
		$objPHPExcel->setActiveSheetIndex()->setCellValue('B'.$i, $tentruongdoan);
		$objPHPExcel->setActiveSheetIndex()->setCellValue('C'.$i, $ngaydi);
		$objPHPExcel->setActiveSheetIndex()->setCellValue('D'.$i, $ngayve);
		$objPHPExcel->setActiveSheetIndex()->setCellValue('E'.$i, $dr['congvanxinphep']['ten']);
		$objPHPExcel->setActiveSheetIndex()->setCellValue('F'.$i, $dr['quyetdinhchophep']['ten']);
		$objPHPExcel->setActiveSheetIndex()->setCellValue('G'.$i, $tendonvi);
		$objPHPExcel->setActiveSheetIndex()->setCellValue('H'.$i, $nuocden);
		$objPHPExcel->setActiveSheetIndex()->setCellValue('I'.$i, $tenmucdich);
		$objPHPExcel->setActiveSheetIndex()->setCellValue('J'.$i, $sotien);
		$objPHPExcel->setActiveSheetIndex()->setCellValue('K'.$i, $tenkinhphi);
		$objPHPExcel->setActiveSheetIndex()->setCellValue('L'.$i, $donvimoi);
		$objPHPExcel->setActiveSheetIndex()->setCellValue('L'.$i, $donvimoi);
		$objPHPExcel->setActiveSheetIndex()->setCellValue('M'.$i, $baocao);
		$objPHPExcel->setActiveSheetIndex()->setCellValue('N'.$i, $dr['ghichu']);
		$i++; $stt++;
	}
}
header('Content-Type: application/vnd.ms-excel');
header('Content-Disposition: attachment;filename="DoanRa_'.date("Ymdhis").'.xlsx"');
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
