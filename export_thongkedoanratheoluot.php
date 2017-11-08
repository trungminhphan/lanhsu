<?php
require_once('header_none.php');
$kinhphi = new KinhPhi();$quocgia = new QuocGia();$canbo = new CanBo();$doanra=new DoanRa();
$donvi = new DonVi();
$id_donvi ='';$id_quocgia='';$id_kinhphi='';
if(isset($_GET['submit'])){
	$query = array();
	$a = isset($_GET['id_donvi']) ? explode("-", $_GET['id_donvi']) : '';
	$id_donvi = isset($a[0]) ? $a[0] : '';
	$tungay = isset($_GET['tungay']) ? $_GET['tungay'] : '';
	$denngay = isset($_GET['denngay']) ? $_GET['denngay'] : '';
	$id_quocgia = isset($_GET['id_quocgia']) ? $_GET['id_quocgia'] : '';
	$id_kinhphi = isset($_GET['id_kinhphi']) ? $_GET['id_kinhphi'] : '';

	if(convert_date_dd_mm_yyyy($tungay) > convert_date_dd_mm_yyyy($denngay)){
		$msg = 'Chọn ngày sai hoặc chưa chọn Đơn vị thống kê';
	} else {
		$start_date = new MongoDate(convert_date_dd_mm_yyyy($tungay));
		$end_date = new MongoDate(convert_date_dd_mm_yyyy($denngay));
		array_push($query, array('$or' => array(array('ngaydi' => array('$gte' => $start_date)), array('ngaydi' => array('$lte' => $end_date)))));
		//array_push($query, array('ngaydi' => array('$gte' => $start_date)));
		//array_push($query, array('ngayve' => array('$lte' => $end_date)));
		if($id_donvi && count($a) == 1){
			array_push($query, array('$or' => array(array('danhsachdoan.id_donvi.0' => $id_donvi), array('danhsachdoan_2.id_donvi.0' => $id_donvi))));
		} else if($id_donvi && count($a) == 2){
			array_push($query, array('$or' => array(array('danhsachdoan.id_donvi.0' => $id_donvi,'danhsachdoan.id_donvi.1' => $a[1]), array('danhsachdoan_2.id_donvi.0' => $id_donvi, 'danhsachdoan_2.id_donvi.1' => $a[1]))));
		} else if($id_donvi && count($a) == 3){
			array_push($query, array('$or' => array(array('danhsachdoan.id_donvi.0' => $id_donvi,'danhsachdoan.id_donvi.1' => $a[1], 'danhsachdoan.id_donvi.2' => $a[2]), array('danhsachdoan_2.id_donvi.0' => $id_donvi, 'danhsachdoan_2.id_donvi.1' => $a[1], 'danhsachdoan_2.id_donvi.2' => $a[2]))));
		} else if($id_donvi && count($a) == 4){
			array_push($query, array('$or' => array(array('danhsachdoan.id_donvi.0' => $id_donvi,'danhsachdoan.id_donvi.1' => $a[1], 'danhsachdoan.id_donvi.2' => $a[2], 'danhsachdoan.id_donvi.3' => $a[3]), array('danhsachdoan_2.id_donvi.0' => $id_donvi, 'danhsachdoan_2.id_donvi.1' => $a[1], 'danhsachdoan_2.id_donvi.2' => $a[2], 'danhsachdoan_2.id_donvi.3' => $a[3]))));
		} else {
			//array_push($query, array('$or' => array(array('danhsachdoan.id_donvi.0' => $id_donvi), array('danhsachdoan_2.id_donvi.0' => $id_donvi))));
			$donvi_list = $donvi->get_all_list();
		}

		if($id_kinhphi){
			array_push($query, array('id_kinhphi' => new MongoId($id_kinhphi)));
		}
		if($id_quocgia){
			array_push($query, array('id_quocgia' => $id_quocgia));
		}
		$q1 = array('$and' => array(
			array('ngaydi' => array('$gte' => $start_date)),
			array('ngayve' => array('$lte' => $end_date)),
			array('$or' => array(array('danhsachdoan.id_donvi.0' => $id_donvi), array('danhsachdoan_2.id_donvi.0' => $id_donvi)))
		));
		$list_1 = $doanra->get_list_condition($q1);
		$q = array('$and' => $query);
		$union_list = $doanra->get_list_condition($q);
	}
}

if($id_donvi) {
require_once('cls/PHPExcel.php');
$inputFileName = 'templates/thongkedoanratheoluot.xlsx';
$objPHPExcel = PHPExcel_IOFactory::load($inputFileName);
$objPHPExcel->getProperties()->setCreator("Phan Minh Trung")
							 ->setLastModifiedBy("Phan Minh Trung")
							 ->setTitle("Office 2007 XLSX Test Document")
							 ->setSubject("Office 2007 XLSX Test Document")
							 ->setDescription("Test document for Office 2007 XLSX, generated using PHP classes.")
							 ->setKeywords("office 2007 openxml php")
							 ->setCategory("Quan ly Lanh su");

$objPHPExcel->setActiveSheetIndex(0);
$str_donvi = '';
if(isset($union_list) && $union_list->count() > 0){
	if(isset($id_donvi) && $id_donvi){
		$donvi->id = $id_donvi; $dv = $donvi->get_one();
		$c1 = 0;
		foreach ($list_1 as $u) {
			if($u['danhsachdoan']){
				foreach ($u['danhsachdoan'] as $ds) {
					if($ds['id_donvi'][0] == $id_donvi) $c1++;
				}
			}
			if($u['danhsachdoan_2']){
				foreach ($u['danhsachdoan_2'] as $ds2) {
					if($ds2['id_donvi'][0] == $id_donvi) $c1++;
				}
			}
		}
		$str_donvi .= $dv['ten'] . ': ' . $c1 . " lượt xuất cảnh\n";
		if(count($a) == 1){
		if(isset($dv['level2']) && $dv['level2']){
			foreach ($dv['level2'] as $a2) {
				$c2 = 0;
				foreach ($union_list as $u) {
					if($u['danhsachdoan']){
						foreach ($u['danhsachdoan'] as $ds) {
							if($ds['id_donvi'][0] == $id_donvi && $ds['id_donvi'][1]==$a2['_id']) $c2++;
						}
					}
					if($u['danhsachdoan_2']){
						foreach ($u['danhsachdoan_2'] as $ds2) {
							if($ds2['id_donvi'][0] == $id_donvi && $ds2['id_donvi'][1] == $a2['_id']) $c2++;
						}
					}
				}
				if($c2) $str_donvi .= '     - ' . $a2['ten'] . ' ' . $c2 ."\n";
				if(isset($a2['level3']) && $a2['level3']){
					foreach ($a2['level3'] as $a3) {
						$c3 = 0;
						foreach ($union_list as $u) {
							if($u['danhsachdoan']){
								foreach ($u['danhsachdoan'] as $ds) {
									if($ds['id_donvi'][0] == $id_donvi && $ds['id_donvi'][1]==$a2['_id'] && $ds['id_donvi'][2]==$a3['_id']) $c3++;
								}
							}
							if($u['danhsachdoan_2']){
								foreach ($u['danhsachdoan_2'] as $ds2) {
									if($ds2['id_donvi'][0] == $id_donvi && $ds2['id_donvi'][1] == $a2['_id'] && $ds2['id_donvi'][2] == $a3['_id']) $c3++;
								}
							}
						}
						if($c3) $str_donvi .= '          '.$a3['ten'] . ' ' . $c3. "\n";
						if(isset($a3['level4']) && $a3['level4']){
							foreach ($a3['level4'] as $a4) {
								$c4 = 0;
								foreach ($union_list as $u) {
									if($u['danhsachdoan']){
										foreach ($u['danhsachdoan'] as $ds) {
											if($ds['id_donvi'][0] == $id_donvi && $ds['id_donvi'][1]==$a2['_id'] && $ds['id_donvi'][2]==$a3['_id'] && $ds['id_donvi'][3]==$a4['_id']) $c4++;
										}
									}
									if($u['danhsachdoan_2']){
										foreach ($u['danhsachdoan_2'] as $ds2) {
											if($ds2['id_donvi'][0] == $id_donvi && $ds2['id_donvi'][1] == $a2['_id'] && $ds2['id_donvi'][2] == $a3['_id'] && $ds2['id_donvi'][3] == $a4['_id']) $c4++;
										}
									}
								}
								if($c4) $str_donvi .= '               '.$a4['ten'].' '. $c4."\n";
							}

						}
					}
				}
			}
		}
	}
		$objPHPExcel->setActiveSheetIndex()->setCellValue('B1', $str_donvi);
		$objPHPExcel->getActiveSheet()->getStyle('B1')->getAlignment()->setWrapText(true);
	}
	$i = 3; $stt = 1;
	$arr_union_list = iterator_to_array($union_list);
	$arr_union_list = sort_array_and_key($arr_union_list, 'ngaydi', SORT_DESC);
	foreach ($arr_union_list as $u) {
		$cvxinphep = $u['congvanxinphep']['ten'];
		$soquyetdinh = $u['quyetdinhchophep']['ten'];
		$ngaydi = $u['ngaydi'] ? date("d/m/Y", $u['ngaydi']->sec) : '';
		$ngayve = $u['ngayve'] ? date("d/m/Y", $u['ngayve']->sec) : '';
		$songay = $u['songay'] ? $u['songay'] : '';
		if($u['id_quocgia']) $nuocden = $quocgia->get_quoctich($u['id_quocgia']);
		else $nuocden = '';
		if($u['id_kinhphi']){
			$kinhphi->id = $u['id_kinhphi']; $kp = $kinhphi->get_one();
			$tenkinhphi = $kp['ten'];
		} else { $tenkinhphi = ''; }
		if($u['danhsachdoan']){
			foreach ($u['danhsachdoan'] as $ds) {
				if($ds['id_donvi'][0] == $id_donvi){
					$show = false;
					if(count($a) == 2 && $a[1] == $ds['id_donvi'][1]){
						$show = true;
					} else if(count($a) == 1) {
						$show = true;
					}
					if($show){
						$canbo->id = $ds['id_canbo']; $cb = $canbo->get_one();
						$objPHPExcel->setActiveSheetIndex()->setCellValue('A'.$i, $stt);
						$objPHPExcel->setActiveSheetIndex()->setCellValue('B'.$i, $cb['hoten']);
						$objPHPExcel->setActiveSheetIndex()->setCellValue('C'.$i, $cvxinphep);
						$objPHPExcel->setActiveSheetIndex()->setCellValue('D'.$i, $soquyetdinh);
						$objPHPExcel->setActiveSheetIndex()->setCellValue('E'.$i, $ngaydi);
						$objPHPExcel->setActiveSheetIndex()->setCellValue('F'.$i, $ngayve);
						$objPHPExcel->setActiveSheetIndex()->setCellValue('G'.$i, $songay);
						$objPHPExcel->setActiveSheetIndex()->setCellValue('H'.$i, $nuocden);
						$objPHPExcel->setActiveSheetIndex()->setCellValue('H'.$i, $tenkinhphi);
						$objPHPExcel->setActiveSheetIndex()->setCellValue('I'.$i, $u['noidung']);
						$i++;$stt++;
					}
				}
			}
		}
		if($u['danhsachdoan_2']){
			foreach ($u['danhsachdoan_2'] as $ds2) {
				if($ds2['id_donvi'][0] == $id_donvi){
					$show = false;
					if(count($a) == 2 && $a[1] == $ds2['id_donvi'][1]){
						$show = true;
					} else if(count($a) == 1) {
						$show = true;
					}
					if($show){
						$canbo->id = $ds2['id_canbo']; $cb = $canbo->get_one();
						$objPHPExcel->setActiveSheetIndex()->setCellValue('A'.$i, $stt);
						$objPHPExcel->setActiveSheetIndex()->setCellValue('B'.$i, $cb['hoten']);
						$objPHPExcel->setActiveSheetIndex()->setCellValue('C'.$i, $cvxinphep);
						$objPHPExcel->setActiveSheetIndex()->setCellValue('D'.$i, $soquyetdinh);
						$objPHPExcel->setActiveSheetIndex()->setCellValue('E'.$i, $ngaydi);
						$objPHPExcel->setActiveSheetIndex()->setCellValue('F'.$i, $ngayve);
						$objPHPExcel->setActiveSheetIndex()->setCellValue('G'.$i, $songay);
						$objPHPExcel->setActiveSheetIndex()->setCellValue('H'.$i, $nuocden);
						$objPHPExcel->setActiveSheetIndex()->setCellValue('H'.$i, $tenkinhphi);
						$objPHPExcel->setActiveSheetIndex()->setCellValue('I'.$i, $u['noidung']);
						$i++;$stt++;
					}
				}
			}
		}
	}
}

// Redirect output to a client’s web browser (Excel2007)
//header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
header('Content-Type: application/vnd.ms-excel');
header('Content-Disposition: attachment;filename="thongkedoanratheoluot_'.date("Ymdhis").'.xlsx"');
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
} else {
	require_once('cls/PHPExcel.php');
	$inputFileName = 'templates/thongkedoanratheoluot_1.xlsx';
	$objPHPExcel = PHPExcel_IOFactory::load($inputFileName);
	$objPHPExcel->getProperties()->setCreator("Phan Minh Trung")
								 ->setLastModifiedBy("Phan Minh Trung")
								 ->setTitle("Office 2007 XLSX Test Document")
								 ->setSubject("Office 2007 XLSX Test Document")
								 ->setDescription("Test document for Office 2007 XLSX, generated using PHP classes.")
								 ->setKeywords("office 2007 openxml php")
								 ->setCategory("Quan ly Lanh su");

	$objPHPExcel->setActiveSheetIndex(0);
	$stt=1;$i=3;
	foreach($donvi_list as $dv){
		$query_1 = $query;$query_2 = $query;
		array_push($query_1, array('congvanxinphep.id_donvi.0' => strval($dv['_id'])));
		$q_1 = array('$and' => $query_1);
		array_push($query_2, array('$or' => array(array('danhsachdoan.id_donvi.0' => strval($dv['_id'])), array('danhsachdoan_2.id_donvi.0' => strval($dv['_id'])))));
		$q_2 = array('$and' => $query_2);
		$count_sodoan = $doanra->count_sodoan($q_1);
		$count_soluot = $doanra->count_soluot($q_2, $dv['_id']);
		$count_songuoi = $doanra->count_songuoi($q_2, $dv['_id']);
		if($count_sodoan > 0 || $count_soluot > 0 || $count_songuoi){
			$objPHPExcel->setActiveSheetIndex()->setCellValue('A'.$i, $stt);
			$objPHPExcel->setActiveSheetIndex()->setCellValue('B'.$i, $dv['ten']);
			$objPHPExcel->setActiveSheetIndex()->setCellValue('C'.$i, $count_sodoan);
			$objPHPExcel->setActiveSheetIndex()->setCellValue('D'.$i, $count_soluot);
			$objPHPExcel->setActiveSheetIndex()->setCellValue('E'.$i, $count_songuoi);
			$stt++;$i++;
		}
	}

	// Redirect output to a client’s web browser (Excel2007)
	//header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
	header('Content-Type: application/vnd.ms-excel');
	header('Content-Disposition: attachment;filename="thongkedoanratheoluot_'.date("Ymdhis").'.xlsx"');
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

}
?>
