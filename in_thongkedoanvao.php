<?php
function __autoload($class_name) {
    require_once('cls/class.' . strtolower($class_name) . '.php');
}
$session = new SessionManager();
$users = new Users();
require_once('inc/functions.inc.php');
require_once('inc/config.inc.php');
if(!$users->isLoggedIn()){ transfers_to('./login.php'); }

$doanvao = new DoanVao(); $dmdoanvao = new DMDoanVao(); $donvi = new DonVi();
$canbo = new CanBo();$chucvu = new ChucVu();$quocgia = new QuocGia();
$msg = '';$id_dmdoanvao='';$id_donvi='';$tungay='';$denngay='';
if(isset($_GET['submit'])){
	$tungay = isset($_GET['tungay']) ? $_GET['tungay'] : '';
	$denngay = isset($_GET['denngay']) ? $_GET['denngay'] : '';
	$id_dmdoanvao = isset($_GET['id_dmdoanvao']) ? $_GET['id_dmdoanvao'] : '';
	$id_donvi = isset($_GET['id_donvi']) ? $_GET['id_donvi'] : '';
	if($tungay > $denngay){
		echo 'Chọn sai ngày thống kê...';
	} else {
		$doanvao->id_dmdoanvao = $id_dmdoanvao;
		$doanvao->id_donvi = $id_donvi;
		$start_date = new MongoDate(convert_date_dd_mm_yyyy($tungay));
		$end_date = new MongoDate(convert_date_dd_mm_yyyy($denngay));
		$union_list = $doanvao->get_union_list($start_date, $end_date);
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
	<h4 class="align-center">BÁO CÁO DANH SÁCH CÁC ĐOÀN KHÁCH QUỐC TẾ ĐẾN THĂM VÀ LÀM VIỆC</h4>
	<h5 class="align-center">Từ ngày: <b><?php echo $tungay; ?></b> Đến ngày: <b><?php echo $denngay; ?></b></h5>
<table class="table cell-hovered border bordered">
<thead>
	<tr>
		<th>STT</th>
		<th>Tên tổ chức</th>
		<th>Nội dung làm việc</th>
		<th>Thời gian đến</th>
		<th>Thời gian đi</th>
		<th>Ghi chú</th>
	</tr>
</thead>
<tbody>
<?php
	$i = 1;$sum_members=0;
	foreach ($union_list as $u) {
		$dmdoanvao->id = $u['id_dmdoanvao']; $dm = $dmdoanvao->get_one();
		$thoigianden = $u['ngayden'] ? date("d/m/Y", $u['ngayden']->sec) : '';
		$thoigiandi = $u['ngaydi'] ? date("d/m/Y", $u['ngaydi']->sec) : '';
		echo '<td>'.$i.'</td>';
		echo '<td>'.$dm['ten'].'</td>';
		echo '<td>'.$u['noidung'].'</td>';
		echo '<td>'.$thoigianden.'</td>';
		echo '<td>'.$thoigiandi.'</td>';
		echo '<td>'.$u['ghichu'].'</td>';

		echo '<tr>';
		echo '<td></td>';
		echo '<td colspan="5">';
		echo '<h4><span class="mif-users"></span> Danh sách thành viên đoàn</h4>';
		echo '<div class="grid">';
		echo '<div class="row cells12">';
			echo '<div class="cell colspan3"><b>Họ tên</b></div>';
			echo '<div class="cell colspan3"><b>Chức vụ</b></div>';
			echo '<div class="cell colspan3"><b>Quốc tịch</b></div>';
			echo '<div class="cell colspan3"><b>Số hộ chiếu</b></div>';
		echo '</div>';
		if($u['danhsachdoan']){
			$j = 1;
			foreach ($u['danhsachdoan'] as $ds) {
				$canbo->id = $ds['id_canbo']; $cb = $canbo->get_one();
				$chucvu->id = $ds['id_chucvu']; $cv=$chucvu->get_one();
				$quocgia->id = $cb['id_quoctich']; $qt=$quocgia->get_one();
				$count = count($cb['passport']) - 1;
				echo '<div class="row cells12">';
					echo '<div class="cell colspan3">'.$j .'. '.$cb['hoten'].'</div>';
					echo '<div class="cell colspan3">'.$cv['ten'].'</div>';
					echo '<div class="cell colspan3">'.$qt['ten'].'</div>';
					echo '<div class="cell colspan3">'.$cb['passport'][$count].'</div>';
				echo '</div>';
				$j++;$sum_members++;
			}
		}
		echo '</div>';

		echo '</td>';
		echo '</tr>';
	}
?>
</tbody>
</table>
<h4><span class="mif-star-full"></span> Tổng số đoàn: <?php echo $union_list->count(); ?></h4>
<h4><span class="mif-users"></span> Tổng số thành viên: <?php echo $sum_members; ?></h4>
<?php endif; ?>
</div>
</body>
</html>