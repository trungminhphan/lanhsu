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
		array_push($query, array('ngaydi' => array('$gte' => $start_date)));
		array_push($query, array('ngayve' => array('$lte' => $end_date)));
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
		$q = array('$and' => $query);
		$union_list = $doanra->get_list_condition($q);
	}
}
?>
<!DOCTYPE html>
<html>
<head lang="en">
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <meta name="description" content="Phần mềm quản lý Lãnh sự , Sở Ngoại vụ.">
    <meta name="keywords" content="Phần mêm, Lãnh sự, Sở NGoại vụ An Giang">
    <meta name="author" content="Trung tâm Tin học Trường Đai học An Giang, 18 Ung Văn Khiêm, Tp Long Xuyên, An Giang">
    <link rel='shortcut icon' type='image/x-icon' href="images/favicon.ico" />
    <title>Phần mềm Quản lý Lãnh sự - Sở Ngoại vụ An Giang</title>
    <link href="css/metro.css" rel="stylesheet">
    <link href="css/metro-icons.css" rel="stylesheet">
    <link href="css/metro-responsive.css" rel="stylesheet">
    <link href="css/metro-schemes.css" rel="stylesheet">
    <script src="js/jquery-2.1.3.min.js"></script>
    <script src="js/metro.js"></script>
</head>
<body>
<div class="place-left align-center">
	<b>UBND TỈNH AN GIANG <br /> 
	SỞ NGOẠI VỤ</b> <br />_____________

</div>
<div class="place-right align-center">
	<b>CỘNG HOÀ XÃ HỘI CHỦ NGHĨA VIỆT NAM <br /> Độc lập - Tự do - Hạnh phúc</b> <br />_______________________________<br />
	<br /><i>An Giang, ngày <?php echo date("d"); ?> tháng <?php echo date("m"); ?> năm <?php echo date("Y"); ?></i>
</div>
<div style="clear: both;padding-top:20px;">
	<h4 class="align-center">THỐNG KÊ ĐOÀN RA ĐƠN VỊ (LƯỢT XUẤT CẢNH)</h4>
	<h5 class="align-center">Từ ngày: <b><?php echo $tungay; ?></b> Đến ngày: <b><?php echo $denngay; ?></b></h5>
</div>

<?php if(isset($union_list) && $union_list->count() > 0 && $id_donvi) : ?>
<?php
if(isset($id_donvi) && $id_donvi){
	$donvi->id = $id_donvi; $dv = $donvi->get_one();
	$c1 = 0;
	foreach ($union_list as $u) {
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
	echo '<h4>' . $dv['ten'] .': <span class="fg-blue">'.$c1.' lượt xuất cảnh</span></h4>';
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
			if($c2)	echo '<h5><span class="mif-arrow-right"></span> '.$a2['ten'].': '.$c2.'</h5>';
			if(isset($a2['level3']) && $a2['level3']){
				echo '<ul>';
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
					if($c3)	echo '<li>'.$a3['ten'].': '.$c3.'</li>';
					if(isset($a3['level4']) && $a3['level4']){
						echo '<ul>';
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
							if($c4)	echo '<li>'.$a4['ten'].': '.$c4.'</li>';
						}
						echo '</ul>';
						
					}
				}
				echo '</ul>';
			}

		}
	}
}
?>
<table class="table border bordered striped">
	<thead>
		<tr>
			<th>STT</th>
			<th>Họ tên</th>
			<th>Văn bản xin phép</th>
			<th>Văn bản cho phép</th>
			<th>Ngày đi</th>
			<th>Ngày về</th>
			<th>Số ngày</th>
			<th>Nước đến</th>
			<th>Kinh phí</th>
			<th>Nội dung</th>
		</tr>
	</thead>
	<tbody>
	<?php
		$i = 1;
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
						$canbo->id = $ds['id_canbo']; $cb = $canbo->get_one();
						echo '<tr>
							<td>'.$i.'</td>
							<td>'.$cb['hoten'].'</td>
							<td>'.$cvxinphep.'</td>
							<td>'.$soquyetdinh.'</td>
							<td>'.$ngaydi.'</td>
							<td>'.$ngayve.'</td>
							<td class="align-right">'.$songay.'</td>
							<td>'.$nuocden.'</td>
							<td>'.$tenkinhphi.'</td>
							<td>'.$u['noidung'].'</td>
						</tr>';$i++;
					}
				}
			}
			if($u['danhsachdoan_2']){
				foreach ($u['danhsachdoan_2'] as $ds2) {
					if($ds2['id_donvi'][0] == $id_donvi){
						$canbo->id = $ds2['id_canbo']; $cb = $canbo->get_one();
						echo '<tr>
							<td>'.$i.'</td>
							<td>'.$cb['hoten'].'</td>
							<td>'.$cvxinphep.'</td>
							<td>'.$soquyetdinh.'</td>
							<td>'.$ngaydi.'</td>
							<td>'.$ngayve.'</td>
							<td class="align-right">'.$songay.'</td>
							<td>'.$nuocden.'</td>
							<td>'.$tenkinhphi.'</td>
							<td>'.$u['noidung'].'</td>
						</tr>';$i++;
					}
				}
			}
		}
	?>
	</tbody>
</table>
<?php endif; ?>
<?php if(isset($donvi_list) && $donvi_list && !$id_donvi): ?>
<table class="table border bordered striped hovered">
	<thead>
		<tr>
			<th>STT</th>
			<th>Đơn vị</th>
			<th>Số đoàn</th>
			<th>Số lượt</th>
			<th>Số người</th>
		</tr>
	</thead>
	<tbody>
	<?php
	$i=1;
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
			echo '<tr>
				<td>'.$i.'</td>
				<td>'.$dv['ten'].'</td>
				<td class="align-right"><a href="thongkedoanratheodonvi.php?id_donvi='.$dv['_id'].'&'.$_SERVER['QUERY_STRING'].'" target="_blank">'.$count_sodoan.'</a></td>
				<td class="align-right"><a href="thongkedoanratheoluotxuatcanh.php?id_donvi='.$dv['_id'].'&'.$_SERVER['QUERY_STRING'].'" target="_blank">'.$count_soluot.'</a></td>
				<td class="align-right"><a href="chitietsonguoixuatcanh.php?id_donvi='.$dv['_id'].'&'.$_SERVER['QUERY_STRING'].'" target="_blank">'.$count_songuoi.'</a></td>
			</tr>'; $i++;
		}
	}
	?>
	</tbody>
</table>
<?php endif; ?>
</body>
</html>

