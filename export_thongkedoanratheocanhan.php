<?php
require_once('header_none.php');
require_once('cls/PHPExcel.php');
$kinhphi = new KinhPhi();$quocgia = new QuocGia();$canbo = new CanBo();$doanra=new DoanRa();
$id_canbo ='';$id_quocgia='';$id_kinhphi='';
if(isset($_GET['submit'])){
	$query = array();
	$id_canbo = isset($_GET['id_canbo']) ? $_GET['id_canbo']  : '';
	$tungay = isset($_GET['tungay']) ? $_GET['tungay'] : '';
	$denngay = isset($_GET['denngay']) ? $_GET['denngay'] : '';
	$id_quocgia = isset($_GET['id_quocgia']) ? $_GET['id_quocgia'] : '';
	$id_kinhphi = isset($_GET['id_kinhphi']) ? $_GET['id_kinhphi'] : '';
	$id_mucdich = isset($_GET['id_mucdich']) ? $_GET['id_mucdich'] : '';

	if(convert_date_dd_mm_yyyy($tungay) > convert_date_dd_mm_yyyy($denngay) || !$id_canbo){
		$msg = 'Chọn ngày sai hoặc chưa chọn Cá nhân thống kê';
	} else {
		$start_date = new MongoDate(convert_date_dd_mm_yyyy($tungay));
		$end_date = new MongoDate(convert_date_dd_mm_yyyy($denngay));
		array_push($query, array('ngaydi' => array('$gte' => $start_date)));
		array_push($query, array('ngayve' => array('$lte' => $end_date)));
		if($id_canbo){
			$arr_cb = array('$or' => array(array('danhsachdoan.id_canbo' => new MongoId($id_canbo)), array('danhsachdoan_2.id_canbo'=> new MongoId($id_canbo))));
			array_push($query, $arr_cb);
		}
		if($id_kinhphi){
			array_push($query, array('id_kinhphi' => new MongoId($id_kinhphi)));
		}
		if($id_quocgia){
			array_push($query, array('id_quocgia' => $id_quocgia));
		}
		if($id_mucdich){
			array_push($query, array('id_mucdich' => new MongoId($id_mucdich)));	
		}
		$q = array('$and' => $query);
		$union_list = $doanra->get_list_condition($q);
	}
}

$inputFileName = 'templates/thongkedoanratheocanhan.xlsx';
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
		$objPHPExcel->setActiveSheetIndex()->setCellValue('B1', $cb['hoten']);
		$objPHPExcel->setActiveSheetIndex()->setCellValue('F1', $union_list->count() . ' lượt xuất cảnh');
	}

	$i = 3; $stt = 1;
	$arr_union_list = iterator_to_array($union_list);
	$arr_union_list = sort_array_and_key($arr_union_list, 'ngaydi', SORT_DESC);
	foreach ($arr_union_list as $u) {
		$congvanxinphep = $u['congvanxinphep']['ten'];
		$soquyetdinh = $u['quyetdinhchophep']['ten'];
		$ngaydi = $u['ngaydi'] ? date("d/m/Y", $u['ngaydi']->sec) : '';
		$ngayve = $u['ngayve'] ? date("d/m/Y", $u['ngayve']->sec) : '';
		if($u['id_quocgia']) $nuocden = $quocgia->get_quoctich($u['id_quocgia']);
		else $nuocden = '';
		if($u['id_kinhphi']){
			$kinhphi->id = $u['id_kinhphi']; $kp = $kinhphi->get_one();
			$tenkinhphi = $kp['ten'];
		} else { $tenkinhphi = ''; }

		$objPHPExcel->setActiveSheetIndex()->setCellValue('A'.$i, $stt);
		$objPHPExcel->setActiveSheetIndex()->setCellValue('B'.$i, $congvanxinphep);
		$objPHPExcel->setActiveSheetIndex()->setCellValue('C'.$i, $soquyetdinh);
		$objPHPExcel->setActiveSheetIndex()->setCellValue('D'.$i, $ngaydi);
		$objPHPExcel->setActiveSheetIndex()->setCellValue('E'.$i, $ngayve);
		$objPHPExcel->setActiveSheetIndex()->setCellValue('F'.$i, $u['songay']);
		$objPHPExcel->setActiveSheetIndex()->setCellValue('G'.$i, $nuocden);
		$objPHPExcel->setActiveSheetIndex()->setCellValue('H'.$i, $tenkinhphi);
		$objPHPExcel->setActiveSheetIndex()->setCellValue('I'.$i, $u['noidung']);
		$i++;$stt++;
	}
}


// Redirect output to a client’s web browser (Excel2007)
//header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
header('Content-Type: application/vnd.ms-excel');
header('Content-Disposition: attachment;filename="thongkedoanratheocanhan_'.date("Ymdhis").'.xlsx"');
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