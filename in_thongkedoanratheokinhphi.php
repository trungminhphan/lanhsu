<?php
function __autoload($class_name) {
    require_once('cls/class.' . strtolower($class_name) . '.php');
}
$session = new SessionManager();
$users = new Users();
require_once('inc/functions.inc.php');
require_once('inc/config.inc.php');
if(!$users->isLoggedIn()){ transfers_to('./login.php'); }
$doanra = new DoanRa(); $kinhphi = new KinhPhi();$quocgia=new QuocGia();$mucdich = new MucDich();$ham = new Ham();
$msg = '';$id_kinhphi = ''; $donvi = new DonVi();$chucvu = new ChucVu();$canbo = new CanBo();

if(isset($_GET['submit'])){
	$query = array();
	$tungay = isset($_GET['tungay']) ? $_GET['tungay'] : '';
	$denngay = isset($_GET['denngay']) ? $_GET['denngay'] : '';
	$id_kinhphi = isset($_GET['id_kinhphi']) ? $_GET['id_kinhphi'] : '';

	if(convert_date_dd_mm_yyyy($tungay) > convert_date_dd_mm_yyyy($denngay)){
		$msg = 'Chọn ngày sai';
	} else {
		$start_date = new MongoDate(convert_date_dd_mm_yyyy($tungay));
		$end_date = new MongoDate(convert_date_dd_mm_yyyy($denngay));
		array_push($query, array('ngaydi' => array('$gte' => $start_date)));
		array_push($query, array('ngayve' => array('$lte' => $end_date)));
		if($id_kinhphi){
			array_push($query, array('id_kinhphi' => new MongoId($id_kinhphi)));
		}
		$query = array('$and' => $query);
		$union_list = $doanra->get_list_condition($query);
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
    <script type="text/javascript">
    	$(document).ready(function(){
    		$(".items_detail").hide();
			$(".items").click(function(){
				$(this).next(".items_detail").toggle();
			});
			$(".show_all").click(function(){
				$(".items_detail").slideToggle();				
			});
    	});
    </script>
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
<h4 class="align-center">BÁO CÁO TÌNH HÌNH CÁN BỘ, ĐẢNG VIÊN ĐI HỌC, CÔNG TÁC, DU LỊCH NƯỚC NGOÀI</h4>
<h5 class="align-center">Từ ngày: <b><?php echo $tungay; ?></b> Đến ngày: <b><?php echo $denngay; ?></b></h5>
<table class="table cell-hovered border bordered">
<thead>
	<tr class="show_all">
		<th>STT</th>
		<th>Số công văn</th>
		<th>Tên nước công tác</th>
		<th>Mục đích</th>
		<th>Thời gian đi</th>
		<th>Thời gian về</th>
		<th>Kinh phí</th>
		<th>Số tiền</th>
	</tr>
</thead>
<tbody>
<?php
$i = 1;$sum_members=0;$total_money = 0;
$arr_union_list = iterator_to_array($union_list);
$arr_union_list = sort_array_and_key($arr_union_list, 'ngaydi', SORT_DESC);
foreach ($arr_union_list as $u) {
	if(isset($u['id_quocgia']) && is_array($u['id_quocgia'])){
		$tenquocgia = $quocgia->get_quoctich($u['id_quocgia']);
		//$tenquocgia = implode(",", $u['id_quocgia']);
	} else { $tenquocgia = ''; }
	if(isset($u['id_mucdich']) && $u['id_mucdich']){
		$mucdich->id = $u['id_mucdich']; $md = $mucdich->get_one();$tenmucdich=$md['ten'];
	} else {$tenmucdich = '';}
	if(isset($u['id_kinhphi']) && $u['id_kinhphi']){
		$kinhphi->id = $u['id_kinhphi']; $kpi = $kinhphi->get_one();$tenkinhphi=$kpi['ten'];
	} else { $tenkinhphi = '';}
	$thoigiandi = $u['ngaydi'] ? date("d/m/Y", $u['ngaydi']->sec) : '';
	$thoigianve = $u['ngayve'] ? date("d/m/Y", $u['ngayve']->sec) : '';
	$total_money += isset($u['sotien']['VND']) ? convert_string_number($u['sotien']['VND']) : 0;

	echo '<tr class="items bg-grayLighter fg-black">';
	echo '<td>'.$i.'</td>';
	echo '<td>'.$u['congvanxinphep']['ten'].'</td>';
	echo '<td>'.$tenquocgia.'</td>';
	echo '<td>'.$tenmucdich.'</td>';
	echo '<td>'.$thoigiandi.'</td>';
	echo '<td>'.$thoigianve.'</td>';
	echo '<td>'.$tenkinhphi.'</td>';
	echo '<td>'.(isset($u['sotien']['VND']) ? $u['sotien']['VND'] : '').'</td>';
	echo '</tr>';
	echo '<tr class="items_detail">';
	echo '<td></td>';
	echo '<td colspan="7">';
		echo '<h4><span class="mif-users"></span> Danh sách thành viên đoàn</h4>';
		echo '<div class="grid">';
		echo '<div class="row cells12">';
			echo '<div class="cell colspan3"><b>Họ tên</b></div>';
			echo '<div class="cell colspan5"><b>Đơn vị</b></div>';
			echo '<div class="cell colspan2"><b>Chức vụ</b></div>';
			echo '<div class="cell colspan2"><b>Số hộ chiếu</b></div>';
		echo '</div>';

		if($u['danhsachdoan']){
			$j = 1;
			foreach ($u['danhsachdoan'] as $ds) {
				$canbo->id = $ds['id_canbo']; $cb = $canbo->get_one();
				$chucvu->id = $ds['id_chucvu'];$cv = $chucvu->get_one();
				$dv = $donvi->tendonvi($ds['id_donvi']);
				if(isset($ds['id_ham']) && $ds['id_ham']) {
					$ham->id = $ds['id_ham']; $h=$ham->get_one();$tenham=$h['ten'];
				} else { $tenham = '';}
				if(count($cb['passport'])){
					$count = count($cb['passport']) - 1;
					$passport = $cb['passport'][$count];
				} else {
					$passport = '';
				}
				echo '<div class="row cells12">';
					echo '<div class="cell colspan3">'.$j . '. ' . $cb['hoten'].'</div>';
					echo '<div class="cell colspan5">'.$dv.'</div>';
					echo '<div class="cell colspan2">'.($tenham ? $tenham .', ' : '') . $cv['ten'].'</div>';
					echo '<div class="cell colspan2">'.$passport.'</div>';
				echo '</div>';
				$j++;$sum_members++;
			}
		}
		if($u['danhsachdoan_2']){
			foreach ($u['danhsachdoan'] as $ds2) {
				$canbo->id = $ds2['id_canbo']; $cb = $canbo->get_one();
				$chucvu->id = $ds2['id_chucvu'];$cv = $chucvu->get_one();
				$dv = $donvi->tendonvi($ds2['id_donvi']);
				if(isset($ds2['id_ham']) && $ds2['id_ham']) {
					$ham->id = $ds2['id_ham']; $h=$ham->get_one();$tenham=$h['ten'];
				} else { $tenham = '';}
				if(count($cb['passport'])){
					$count = count($cb['passport']) - 1;
					$passport = $cb['passport'][$count];
				} else {
					$passport = '';
				}
				echo '<div class="row cells12">';
					echo '<div class="cell colspan3">'.$j . '. ' . $cb['hoten'].'</div>';
					echo '<div class="cell colspan5">'.$dv.'</div>';
					echo '<div class="cell colspan2">'.($tenham ? $tenham .', ' : '') . $cv['ten'].'</div>';
					echo '<div class="cell colspan2">'.$passport.'</div>';
				echo '</div>';
				$j++;$sum_members++;
			}
		}
		echo '</div>';
	echo '</td>';
	echo '</tr>';
	$i++;
}
?>
</tbody>
</table>
<h4><span class="mif-star-full"></span> Tổng số đoàn: <?php echo $union_list->count(); ?></h4>
<h4><span class="mif-users"></span> Tổng số thành viên: <?php echo $sum_members; ?></h4>
<h4><span class="mif-money"></span> Tổng số tiền: <?php echo format_decimal($total_money, 2); ?></h4>
<?php endif; ?>
</div>
</body>
</html>