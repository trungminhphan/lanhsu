<?php
require_once('header_none.php');
$canbo = new CanBo();$doanvao=new DoanVao();$quocgia = new QuocGia();
$donvi = new DonVi();
$id_canbo ='';
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
		/*if($id_donvi){
			array_push($query, array('$or' => array(array('danhsachdoan.0.id_donvi.0' => $id_donvi), array('danhsachdoan_2.0.id_donvi.0' => $id_donvi))));
		}*/
		if($id_donvi){
			array_push($query, array('congvanxinphep.id_donvi.0' => $id_donvi));
		}
		$q = array('$and' => $query);
		$union_list = $doanvao->get_list_condition($q);
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
	<h4 class="align-center">THỐNG KÊ ĐOÀN VÀO THEO ĐƠN VỊ TIẾP</h4>
	<h5 class="align-center">Từ ngày: <b><?php echo $tungay; ?></b> Đến ngày: <b><?php echo $denngay; ?></b></h5>
</div>

<?php if(isset($union_list) && $union_list->count() > 0) : ?>
<?php
if(isset($id_donvi) && $id_donvi){
	$donvi->id = $id_donvi; $dv = $donvi->get_one();
	echo '<h4>' . $dv['ten'] .': <span class="fg-blue">'.$union_list->count().' lượt</span></h4>';
	if(isset($dv['level2']) && $dv['level2']){
		foreach ($dv['level2'] as $a2) {
			$query_2 = $query;
			array_push($query_2, array('congvanxinphep.id_donvi.1' => $a2['_id']->{'$id'}));
			$q2 = array('$and' => $query_2);
			$c2 = $doanvao->get_list_condition($q2)->count();
			if($c2)	echo '<h5><span class="mif-arrow-right"></span> '.$a2['ten'].': '.$c2.'</h5>';
			if(isset($a2['level3']) && $a2['level3']){
				echo '<ul>';
				foreach ($a2['level3'] as $a3) {
					$query_3 = $query_2;
					array_push($query_3, array('congvanxinphep.id_donvi.2' => $a3['_id']->{'$id'}));
					$q3 = array('$and' => $query_3);
					$c3 = $doanvao->get_list_condition($q3)->count();
					if($c3)	echo '<li>'.$a3['ten'].': '.$c3.'</li>';
					if(isset($a3['level4']) && $a3['level4']){
						echo '<ul>';
						foreach ($a3['level4'] as $a4) {
							$query_4 = $query_3;
							array_push($query_4, array('congvanxinphep.id_donvi.3' => $a4['_id']->{'$id'}));
							$q4 = array('$and' => $query_4);
							$c4 = $doanvao->get_list_condition($q4)->count();
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
			<th>Trưởng đoàn</th>
			<th>Quốc tịch</th>
			<th>Văn bản xin phép</th>
			<th>Văn bản cho phép</th>
			<th>Ngày đến</th>
			<th>Ngày đi</th>
			<th>Nội dung</th>
		</tr>
	</thead>
	<tbody>
	<?php
		$i = 1;
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
			echo '<tr>
				<td>'.$i.'</td>
				<td>'.$tentruongdoan.'</td>
				<td>'.$tenquoctich.'</td>
				<td>'.$congvanxinphep.'</td>
				<td>'.$soquyetdinh.'</td>
				<td>'.$ngayden.'</td>
				<td>'.$ngaydi.'</td>
				<td>'.$u['noidung'].'</td>
			</tr>';$i++;
		}
	?>
	</tbody>
</table>
<?php endif; ?>
</body>
</html>
