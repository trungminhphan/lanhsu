<?php
require_once('header.php');
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
<script type="text/javascript" src="js/select2.min.js"></script>
<script type="text/javascript" src="js/jquery.inputmask.js"></script>
<script type="text/javascript">
	$(document).ready(function(){
		$(".ngaythangnam").inputmask();
		<?php if($msg) : ?>
        	$.Notify({type: 'alert', caption: 'Thông báo', content: '<?php echo $msg; ?>'});
    	<?php endif; ?>
    	$(".open_window").click(function(){
		  window.open($(this).attr("href"), '_blank', 'toolbar=yes, scrollbars=yes, resizable=yes, top=0, left=100, width=1024, height=800');
		  return false;
		});
	});
</script>
<h1><a href="index.php" class="nav-button transform"><span></span></a>&nbsp;Thống kê Đoàn ABTC</h1>
<form method="GET" action="<?php echo $_SERVER['PHP_SELF']; ?>" id="thongkeform" data-role="validator" data-show-required-state="false" enctype="multipart/form-data">
<div class="grid">
	<div class="row cells12">
		<div class="cell colspan2 padding-top-10 align-right">Từ ngày</div>
		<div class="cell colspan4 input-control text" data-role="datepicker" data-format="dd/mm/yyyy">
			<input type="text" name="tungay" id="tungay" value="<?php echo isset($tungay) ? $tungay : '01/01/2016'; ?>" class="ngaythangnam" data-inputmask="'alias': 'date'" data-validate-func="required" placeholder="Chọn ngày" />
			<span class="input-state-error mif-warning"></span><span class="input-state-success mif-checkmark"></span>
		</div>
		<div class="cell colspan2 padding-top-10 align-right">Đến ngày</div>
		<div class="cell colspan4 input-control text" data-role="datepicker" data-format="dd/mm/yyyy">
			<input type="text" name="denngay" id="denngay" value="<?php echo isset($denngay) ? $denngay : date("d/m/Y"); ?>" class="ngaythangnam" data-inputmask="'alias': 'date'" data-validate-func="required" placeholder="Chọn ngày" />
			<span class="input-state-error mif-warning"></span><span class="input-state-success mif-checkmark"></span>
		</div>
	</div>
	<div class="row cells12">
		<div class="cell colspan12 align-center">
			<button name="submit" id="submit" value="OK" class="button primary"><span class="mif-checkmark"></span> Thống kê</button>
			<?php if(isset($_GET['submit'])) : ?>
				<a href="in_thongkeabtc.php?tungay=<?php echo $tungay; ?>&denngay=<?php echo $denngay; ?>&submit=OK" class="open_window button"><span class="mif-printer"></span> Print</a>
				<!--<a href="export_thongkedoanra_word.php" class="button bg-teal fg-white"><span class="mif-file-word"></span> Word</a>-->
			<?php endif; ?>
		</div>
	</div>
</div>
</form>
<hr />
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
		<th>Số thẻ</th>
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
<?php require_once('footer.php');?>