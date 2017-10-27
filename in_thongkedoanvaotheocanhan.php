<?php
require_once('header_none.php');
$canbo = new CanBo();$doanvao=new DoanVao();$quocgia=new QuocGia();
if(isset($_GET['submit'])){
	$query = array();
	$id_canbo = isset($_GET['id_canbo']) ? $_GET['id_canbo']  : '';
	$tungay = isset($_GET['tungay']) ? $_GET['tungay'] : '';
	$denngay = isset($_GET['denngay']) ? $_GET['denngay'] : '';
	$id_quocgia = isset($_GET['id_quocgia']) ? $_GET['id_quocgia'] : '';
	$id_mucdich = isset($_GET['id_mucdich']) ? $_GET['id_mucdich'] : '';
	$id_linhvuc = isset($_GET['id_linhvuc']) ? $_GET['id_linhvuc'] : '';
	if(convert_date_dd_mm_yyyy($tungay) > convert_date_dd_mm_yyyy($denngay)){
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
		if($id_mucdich){
			array_push($query, array('id_mucdich' => new MongoId($id_mucdich)));
		}
		if($id_linhvuc){
			array_push($query, array('id_linhvuc' => new MongoId($id_linhvuc)));
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
	<h4 class="align-center">THỐNG KÊ ĐOÀN VÀO THEO CÁ NHÂN</h4>
	<h5 class="align-center">Từ ngày: <b><?php echo $tungay; ?></b> Đến ngày: <b><?php echo $denngay; ?></b></h5>
</div>
<?php if(isset($union_list) && $union_list->count() > 0) : ?>
<?php
if(isset($id_canbo) && $id_canbo){
	$canbo->id = $id_canbo; $cb = $canbo->get_one();
	if($cb['id_quoctich']){
		$quocgia->id = $cb['id_quoctich']; $qt = $quocgia->get_one();
		$tenquoctich = $qt['ten'];
	}  else {
		$tenquoctich = '';
	}
	echo '<h4>' . $cb['hoten'] .': <span class="fg-blue">'.$union_list->count().' lượt nhập cảnh</span></h4>';
	echo '<h4>Quốc tịch: '.$tenquoctich.'</h4>';
}
?>
<table class="table border bordered striped">
	<thead>
		<tr>
			<th>STT</th>
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
			$congvanxinphep = $u['congvanxinphep']['ten'];
			$soquyetdinh = $u['quyetdinhchophep']['ten'];
			$ngayden = $u['ngayden'] ? date("d/m/Y", $u['ngayden']->sec) : '';
			$ngaydi = $u['ngaydi'] ? date("d/m/Y", $u['ngaydi']->sec) : '';
			$blnQuocGia = false;
			if($id_quocgia){
				if($u['danhsachdoan']){
					foreach($u['danhsachdoan'] as $ds){
						$canbo->id = $ds['id_canbo']; $cb = $canbo->get_one();
						if($id_quocgia == strval($cb['id_quoctich'])){
							$blnQuocGia = true;
						}
					}
				}
				if($u['danhsachdoan_2']){
					foreach($u['danhsachdoan_2'] as $ds2){
						$canbo->id = $ds2['id_canbo']; $cb = $canbo->get_one();
						if($id_quocgia == strval($cb['id_quoctich'])){
							$blnQuocGia = true;
						}
					}
				}
			}
			if(!$id_quocgia || ($id_quocgia && $blnQuocGia==true)){
				echo '<tr>
					<td>'.$i.'</td>
					<td>'.$congvanxinphep.'</td>
					<td>'.$soquyetdinh.'</td>
					<td>'.$ngayden.'</td>
					<td>'.$ngaydi.'</td>
					<td>'.$u['noidung'].'</td>
				</tr>';$i++;
			}
		}
	?>
	</tbody>
</table>
<?php endif; ?>
</body>
</html>
