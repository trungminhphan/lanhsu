<?php
require_once('header_none.php');
$msg = '';$abtc = new ABTC();$canbo = new CanBo(); $donvi = new DonVi(); $chucvu = new ChucVu();$quocgia = new QuocGia();
if(isset($_GET['submit'])){
	$tungay = isset($_GET['tungay']) ? $_GET['tungay'] : '';
	$denngay = isset($_GET['denngay']) ? $_GET['denngay'] : '';
	if($tungay > $denngay){
		$msg = 'Chọn ngày sai';
	} else {
		$start_date = new MongoDate(convert_date_dd_mm_yyyy($tungay));
		$end_date = new MongoDate(convert_date_dd_mm_yyyy($denngay));
		$union_list = $abtc->get_union_list($start_date, $end_date);
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
<?php if(isset($union_list) && $union_list) : ?>
<h4 class="align-center">THỐNG KÊ DOANH NHÂN SỬ DỤNG THẺ ABTC</h4>
<h5 class="align-center">Từ ngày: <b><?php echo $tungay; ?></b> Đến ngày: <b><?php echo $denngay; ?></b></h5>
<table class="table cell-hovered border bordered">
<thead>
	<tr>
		<th>STT</th>
		<th width="160">Họ tên</th>
		<th width="80">Văn bản xin phép</th>
		<th>Đơn vị</th>
		<th>Chức vụ</th>
		<th>Số hộ chiếu</th>
		<th>Quyết định cho phép</th>
		<th>Thẻ ABTC</th>
		<th>Nước cấp</th>
	</tr>
</thead>
<tbody>
	<?php
	$i=1;$sum_duoccap = 0; $sum_khongcap=0;$sum_members=0;
	foreach ($union_list as $u) {
		if($u['thongtinthanhvien']){
			foreach ($u['thongtinthanhvien'] as $ds) {
				$canbo->id = $ds['id_canbo']; $cb = $canbo->get_one();
				//$donvi->id = $ds['id_donvi']; $dv = $donvi->get_one();
				$tendonvi = $donvi->tendonvi($ds['id_donvi']);
				$chucvu->id = $ds['id_chucvu']; $cv = $chucvu->get_one();
				$count = count($cb['passport']) - 1;
				//$quocgia->id = $u['id_quocgia']; $qg = $quocgia->get_one();
				$qg = $quocgia->get_quoctich($u['id_quocgia']);
				echo '<tr>';
				echo '<td>'.$i.'</td>';
				echo '<td>'.$cb['hoten'].'</td>';
				echo '<td>'.$u['congvanxinphep']['ten'].'</td>';
				echo '<td>'.$tendonvi.'</td>';
				echo '<td>'.$cv['ten'].'</td>';
				echo '<td>'.$cb['passport'][$count].'</td>';
				echo '<td>'.$u['quyetdinhchophep']['ten'].'</td>';
				echo '<td>'.$ds['sothe'].'</td>';
				echo '<td>'.$qg.'</td>';
				echo '</tr>';
				$i++;$sum_members++;
			}
		}
	}
	?>
</tbody>
</table>
<h4><span class="mif-checkmark"></span> Tổng số được cấp: <?php echo $sum_duoccap; ?></h4>
<h4><span class="mif-cancel"></span> Tổng số không được cấp: <?php echo $sum_khongcap; ?></h4>
<h4><span class="mif-users"></span> Tổng số doanh nhân: <?php echo $sum_members; ?></h4>
<?php endif; ?>
</div>
</body>
</html>
