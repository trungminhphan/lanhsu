<?php
require_once('header_none.php');
require_once('cls/PHPExcel.php');

$canbo = new CanBo();$doanra=new DoanRa();$doanvao = new DoanVao(); $abtc = new ABTC();
$ham = new Ham();$donvi = new DonVi();$nghenghiep = new NgheNghiep(); $dantoc= new DanToc();$chucvu = new ChucVu();
$quocgia = new QuocGia();
$loaitimkiem = isset($_GET['loaitimkiem']) ? $_GET['loaitimkiem'] : 'doanra';
$query = array(); $arr_canbo = array();$loaicongchuc='';$dangvien='';$tinhuyvien='';$gioitinh='';
$id_quoctich='';$id_nghenghiep='';$id_donvi_1='';$id_nghenghiep='';$id_dantoc='';
$check_gioitinh='';$check_quoctich='';$check_donvicongtac='';$check_chucvu='';$check_nghenghiep='';$check_dantoc='';
$title = 'Tìm kiếm Cá nhân theo Đoàn ra';
if(isset($_GET['submit'])){
	$loaicongchuc = isset($_GET['loaicongchuc']) ? $_GET['loaicongchuc'] : '';
	$dangvien = isset($_GET['dangvien']) ? $_GET['dangvien'] : '';
	$tinhuyvien = isset($_GET['tinhuyvien']) ? $_GET['tinhuyvien'] : '';
	$cmnd = isset($_GET['cmnd']) ? $_GET['cmnd'] : '';
	$passport = isset($_GET['passport']) ? $_GET['passport'] : '';
	$hoten = isset($_GET['hoten']) ? $_GET['hoten'] : '';
	$ngaysinh = isset($_GET['ngaysinh']) ? $_GET['ngaysinh'] : '';
	$gioitinh = isset($_GET['gioitinh']) ? $_GET['gioitinh'] : '';
	$id_quoctich = isset($_GET['id_quoctich']) ? $_GET['id_quoctich'] : '';
	$id_nghenghiep = isset($_GET['id_nghenghiep']) ? $_GET['id_nghenghiep'] : '';
	$id_donvi_1 = isset($_GET['id_donvi_1']) ? $_GET['id_donvi_1'] : '';
	$id_chucvu = isset($_GET['id_chucvu']) ? $_GET['id_chucvu'] : '';
	$id_dantoc = isset($_GET['id_dantoc']) ? $_GET['id_dantoc'] : '';

	$check_gioitinh = isset($_GET['check_gioitinh']) ? $_GET['check_gioitinh'] : '';
	$check_quoctich = isset($_GET['check_quoctich']) ? $_GET['check_quoctich'] : '';
	$check_donvicongtac = isset($_GET['check_donvicongtac']) ? $_GET['check_donvicongtac'] : '';
	$check_chucvu = isset($_GET['check_chucvu']) ? $_GET['check_chucvu'] : '';
	$check_nghenghiep = isset($_GET['check_nghenghiep']) ? $_GET['check_nghenghiep'] : '';
	$check_dantoc = isset($_GET['check_dantoc']) ? $_GET['check_dantoc'] : '';

	if($loaicongchuc) array_push($query, array('loaicongchuc' => $loaicongchuc));
	if($dangvien) array_push($query, array('dangvien' => '1'));
	if($tinhuyvien) array_push($query, array('tinhuyvien' => '1'));
	if($cmnd) array_push($query, array('cmnd' => $cmnd));
	if($passport) array_push($query, array('passport' => $passport));
	if($hoten) array_push($query, array('hoten' => new MongoRegex('/'.$hoten.'/i')));
	if($ngaysinh) array_push($query, array('ngaysinh' => new MongoDate(convert_date_yyyy_mm_dd($ngaysinh))));
	if($gioitinh) array_push($query, array('gioitinh' => $gioitinh));
	if($id_quoctich) array_push($query, array('id_quoctich' => $id_quoctich));
	if($id_donvi_1) array_push($query, array('donvi.id_donvi.0' => $id_donvi_1));
	if($id_chucvu) array_push($query, array('donvi.id_chucvu' => new MongoId($id_chucvu)));
	if($id_nghenghiep) array_push($query, array('id_nghenghiep' => new MongoId($id_nghenghiep)));
	if($id_dantoc) array_push($query, array('id_dantoc' => new MongoId($id_dantoc)));
	
	if(count($query) > 0 ) $condition = array('$and' => $query);
	else $condition = array();
	$canbo_list = $canbo->get_list_condition($condition);
	if($loaitimkiem == 'doanra'){
		$title = 'Tìm kiếm Cá nhân theo Đoàn ra';
	} else if($loaitimkiem == 'doanvao'){
		$title = 'Tìm kiếm Cá nhân theo Đoàn vào';
	} else if($loaitimkiem == 'abtc'){
		$title = 'Tìm kiếm Cá nhân theo ABTC';
	} else {
		$title = 'Tìm kiếm Cá nhân';
	}
}

$inputFileName = 'templates/timkiemnangcao.xlsx';
$objPHPExcel = PHPExcel_IOFactory::load($inputFileName);

$objPHPExcel->getProperties()->setCreator("Phan Minh Trung")
							 ->setLastModifiedBy("Phan Minh Trung")
							 ->setTitle("Office 2007 XLSX Test Document")
							 ->setSubject("Office 2007 XLSX Test Document")
							 ->setDescription("Test document for Office 2007 XLSX, generated using PHP classes.")
							 ->setKeywords("office 2007 openxml php")
							 ->setCategory("Quan ly Lanh su");
$objPHPExcel->setActiveSheetIndex(0);
$colums = array('A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'I', 'J', 'K', 'L', 'M', 'N', 'O');
$index = 0;
$i= 1;
if(isset($canbo_list) && $canbo_list->count() > 0 ){
	$objPHPExcel->setActiveSheetIndex()->setCellValue($colums[$index].$i, 'STT');  $index++;
	$objPHPExcel->setActiveSheetIndex()->setCellValue($colums[$index].$i, 'ID');  $index++;
	$objPHPExcel->setActiveSheetIndex()->setCellValue($colums[$index].$i, 'Họ tên');  $index++;
	if($cmnd){
		$objPHPExcel->setActiveSheetIndex()->setCellValue($colums[$index].$i, 'CMND');  $index++;		
	}
	if($passport){
		$objPHPExcel->setActiveSheetIndex()->setCellValue($colums[$index].$i, 'Passport');  $index++;
	}
	if($ngaysinh){
		$objPHPExcel->setActiveSheetIndex()->setCellValue($colums[$index].$i, 'Ngày sinh');  $index++;	
	}
	if($gioitinh || $check_gioitinh){
		$objPHPExcel->setActiveSheetIndex()->setCellValue($colums[$index].$i, 'Giới tính');  $index++;		
	}
	if($id_quoctich || $check_quoctich){
		$objPHPExcel->setActiveSheetIndex()->setCellValue($colums[$index].$i, 'Quốc tịch');  $index++;			
	}
	if($id_donvi_1 || $check_donvicongtac){
		$objPHPExcel->setActiveSheetIndex()->setCellValue($colums[$index].$i, 'Đơn vị');  $index++;				
	} 
	if($id_chucvu || $check_chucvu){
		$objPHPExcel->setActiveSheetIndex()->setCellValue($colums[$index].$i, 'Chức vụ');  $index++;
	}
	if($id_nghenghiep || $check_nghenghiep){
		$objPHPExcel->setActiveSheetIndex()->setCellValue($colums[$index].$i, 'Nghề nghiệp');  $index++;
	}
	if($id_dantoc || $check_dantoc){
		$objPHPExcel->setActiveSheetIndex()->setCellValue($colums[$index].$i, 'Dân tộc');  $index++;	
	}
	if($loaicongchuc){
		$objPHPExcel->setActiveSheetIndex()->setCellValue($colums[$index].$i, 'Loại công chức');  $index++;		
	}
	if($dangvien){
		$objPHPExcel->setActiveSheetIndex()->setCellValue($colums[$index].$i, 'Đảng viên');  $index++;		
	}
	if($tinhuyvien){
		$objPHPExcel->setActiveSheetIndex()->setCellValue($colums[$index].$i, 'Tỉnh Ủy viên');  $index++;			
	}
	
	$index = 0; $i++;$stt=1;

	foreach ($canbo_list as $cb) {
		if($loaitimkiem == 'all'){
			if($cb['donvi']){
				$arr_donvi = sort_array_and_key($cb['donvi'], "ngaynhap", SORT_DESC);
				if(isset($arr_donvi[0]['id_donvi'][0]) && $arr_donvi[0]['id_donvi'][0]){
					$tendonvi = $donvi->tendonvi($cb['donvi'][0]['id_donvi']);
				} else { $tendonvi = ''; $full_donvi='';}
				if(isset($arr_donvi[0]['id_chucvu']) && $arr_donvi[0]['id_chucvu']){
					$chucvu->id = $arr_donvi[0]['id_chucvu'];$cv = $chucvu->get_one();
					$tenchucvu = $cv['ten'];
				} else{
					$tenchucvu = '';
				}
			} else {
				$tendonvi='';$tenchucvu='';
			}
			if(isset($cb['id_nghenghiep']) && $cb['id_nghenghiep']){
				$nghenghiep->id = $cb['id_nghenghiep']; $nn = $nghenghiep->get_one();
				$tennghenghiep = $nn['ten'];
			} else {
				$tennghenghiep = '';
			}
			if(isset($cb['id_dantoc']) && $cb['id_dantoc']){
				$dantoc->id = $cb['id_dantoc']; $dt = $dantoc->get_one();
				$tendantoc = $dt['ten'];
			} else {
				$tendantoc = '';
			}
			if($cb['id_quoctich']){
				$quocgia->id = $cb['id_quoctich']; $qt = $quocgia->get_one();
				$tenquoctich = $qt['ten'];
			} else { $tenquoctich = ''; }

			$objPHPExcel->setActiveSheetIndex()->setCellValue($colums[$index].$i, $stt);  $index++;
			$objPHPExcel->setActiveSheetIndex()->setCellValue($colums[$index].$i, $cb['code']);  $index++;
			$objPHPExcel->setActiveSheetIndex()->setCellValue($colums[$index].$i, $cb['hoten']);  $index++;
			if($cmnd){
				$objPHPExcel->setActiveSheetIndex()->setCellValue($colums[$index].$i, $cb['cmnd']);  $index++;		
			}
			if($passport){
				$objPHPExcel->setActiveSheetIndex()->setCellValue($colums[$index].$i, $cb['passport'][0]);  $index++;
			}
			if($ngaysinh){
				$objPHPExcel->setActiveSheetIndex()->setCellValue($colums[$index].$i, $ngaysinh);  $index++;	
			}
			if($gioitinh || $check_gioitinh){
				$objPHPExcel->setActiveSheetIndex()->setCellValue($colums[$index].$i, $cb['gioitinh']);  $index++;		
			}
			if($id_quoctich || $check_quoctich){
				$objPHPExcel->setActiveSheetIndex()->setCellValue($colums[$index].$i, $tenquoctich);  $index++;			
			}
			if($id_donvi_1 || $check_donvicongtac){
				$objPHPExcel->setActiveSheetIndex()->setCellValue($colums[$index].$i, $tendonvi);  $index++;				
			} 
			if($id_chucvu || $check_chucvu){
				$objPHPExcel->setActiveSheetIndex()->setCellValue($colums[$index].$i, $tenchucvu);  $index++;
			}
			if($id_nghenghiep || $check_nghenghiep){
				$objPHPExcel->setActiveSheetIndex()->setCellValue($colums[$index].$i, $tennghenghiep);  $index++;
			}
			if($id_dantoc || $check_dantoc){
				$objPHPExcel->setActiveSheetIndex()->setCellValue($colums[$index].$i, $tendantoc);  $index++;	
			}
			if($loaicongchuc){
				$objPHPExcel->setActiveSheetIndex()->setCellValue($colums[$index].$i, $cb['loaicongchuc'] =='VC' ? 'Viên chức': 'Công chức');  $index++;		
			}
			if($dangvien){
				$objPHPExcel->setActiveSheetIndex()->setCellValue($colums[$index].$i, 'Đảng viên');  $index++;		
			}
			if($tinhuyvien){
				$objPHPExcel->setActiveSheetIndex()->setCellValue($colums[$index].$i, 'Tỉnh Ủy viên');  $index++;			
			}
			$i++; $index=0; $stt++;
		} else {
			if($$loaitimkiem->check_timkiem($cb['_id'])){
				if($cb['donvi']){
					$arr_donvi = sort_array_and_key($cb['donvi'], "ngaynhap", SORT_DESC);
					if(isset($arr_donvi[0]['id_donvi'][0]) && $arr_donvi[0]['id_donvi'][0]){
						$tendonvi = $donvi->tendonvi($arr_donvi[0]['id_donvi']);
					} else { $tendonvi = ''; $full_donvi='';}
					if(isset($arr_donvi[0]['id_chucvu']) && $arr_donvi[0]['id_chucvu']){
						$chucvu->id = $arr_donvi[0]['id_chucvu'];$cv = $chucvu->get_one();
						$tenchucvu = $cv['ten'];
					} else{
						$tenchucvu = '';
					}
				} else {
					$tendonvi='';$tenchucvu='';
				}
				if(isset($cb['id_nghenghiep']) && $cb['id_nghenghiep']){
					$nghenghiep->id = $cb['id_nghenghiep']; $nn = $nghenghiep->get_one();
					$tennghenghiep = $nn['ten'];
				} else {
					$tennghenghiep = '';
				}
				if(isset($cb['id_dantoc']) && $cb['id_dantoc']){
					$dantoc->id = $cb['id_dantoc']; $dt = $dantoc->get_one();
					$tendantoc = $dt['ten'];
				} else {
					$tendantoc = '';
				}
				if($cb['id_quoctich']){
					$quocgia->id = $cb['id_quoctich']; $qt = $quocgia->get_one();
					$tenquoctich = $qt['ten'];
				} else { $tenquoctich = ''; }
				$objPHPExcel->setActiveSheetIndex()->setCellValue($colums[$index].$i, $stt);  $index++;
				$objPHPExcel->setActiveSheetIndex()->setCellValue($colums[$index].$i, $cb['code']);  $index++;
				$objPHPExcel->setActiveSheetIndex()->setCellValue($colums[$index].$i, $cb['hoten']);  $index++;
				if($cmnd){
					$objPHPExcel->setActiveSheetIndex()->setCellValue($colums[$index].$i, $cb['cmnd']);  $index++;		
				}
				if($passport){
					$objPHPExcel->setActiveSheetIndex()->setCellValue($colums[$index].$i, $cb['passport'][0]);  $index++;
				}
				if($ngaysinh){
					$objPHPExcel->setActiveSheetIndex()->setCellValue($colums[$index].$i, $ngaysinh);  $index++;	
				}
				if($gioitinh || $check_gioitinh){
					$objPHPExcel->setActiveSheetIndex()->setCellValue($colums[$index].$i, $cb['gioitinh']);  $index++;		
				}
				if($id_quoctich || $check_quoctich){
					$objPHPExcel->setActiveSheetIndex()->setCellValue($colums[$index].$i, $tenquoctich);  $index++;			
				}
				if($id_donvi_1 || $check_donvicongtac){
					$objPHPExcel->setActiveSheetIndex()->setCellValue($colums[$index].$i, $tendonvi);  $index++;				
				} 
				if($id_chucvu || $check_chucvu){
					$objPHPExcel->setActiveSheetIndex()->setCellValue($colums[$index].$i, $tenchucvu);  $index++;
				}
				if($id_nghenghiep || $check_nghenghiep){
					$objPHPExcel->setActiveSheetIndex()->setCellValue($colums[$index].$i, $tennghenghiep);  $index++;
				}
				if($id_dantoc || $check_dantoc){
					$objPHPExcel->setActiveSheetIndex()->setCellValue($colums[$index].$i, $tendantoc);  $index++;	
				}
				if($loaicongchuc){
					$objPHPExcel->setActiveSheetIndex()->setCellValue($colums[$index].$i, $cb['loaicongchuc'] =='VC' ? 'Viên chức': 'Công chức');  $index++;		
				}
				if($dangvien){
					$objPHPExcel->setActiveSheetIndex()->setCellValue($colums[$index].$i, 'Đảng viên');  $index++;		
				}
				if($tinhuyvien){
					$objPHPExcel->setActiveSheetIndex()->setCellValue($colums[$index].$i, 'Tỉnh Ủy viên');  $index++;			
				}
				$i++; $index=0; $stt++;
			}
		}
	}

}


// Redirect output to a client’s web browser (Excel2007)
//header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
header('Content-Type: application/vnd.ms-excel');
header('Content-Disposition: attachment;filename="timkiemnangcao_'.date("Ymdhis").'.xlsx"');
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