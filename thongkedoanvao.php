<?php
require_once('header.php');
$doanvao = new DoanVao(); $dmdoanvao = new DMDoanVao(); $donvi = new DonVi();
$canbo = new CanBo();$chucvu = new ChucVu();$quocgia = new QuocGia();
$msg = '';$id_dmdoanvao='';$id_donvi='';
if(isset($_GET['submit'])){
	$tungay = isset($_GET['tungay']) ? $_GET['tungay'] : '';
	$denngay = isset($_GET['denngay']) ? $_GET['denngay'] : '';
	$id_dmdoanvao = isset($_GET['id_dmdoanvao']) ? $_GET['id_dmdoanvao'] : '';
	$id_donvi = isset($_GET['id_donvi']) ? $_GET['id_donvi'] : '';
	if($tungay > $denngay){
		$msg = 'Chọn ngày sai';
	} else {
		$doanvao->id_dmdoanvao = $id_dmdoanvao;
		$doanvao->id_donvi = $id_donvi;
		$start_date = new MongoDate(convert_date_dd_mm_yyyy($tungay));
		$end_date = new MongoDate(convert_date_dd_mm_yyyy($denngay));
		$union_list = $doanvao->get_union_list($start_date, $end_date);
	}
}
?>
<script type="text/javascript" src="js/select2.min.js"></script>
<script type="text/javascript" src="js/jquery.inputmask.js"></script>
<script type="text/javascript">
	$(document).ready(function(){
		$(".select2").select2();
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
<h1><a href="index.php" class="nav-button transform"><span></span></a>&nbsp;Thống kê Đoàn vào</h1>
<form method="GET" action="<?php echo $_SERVER['PHP_SELF']; ?>" id="thongkeform" data-role="validator" data-show-required-state="false" enctype="multipart/form-data">
<div class="grid">
	<div class="row cells12">
		<div class="cell colspan2 padding-top-10 align-right">Từ ngày</div>
		<div class="cell colspan4 input-control text" data-role="datepicker" data-format="dd/mm/yyyy">
			<input type="text" name="tungay" id="tungay" value="<?php echo isset($tungay) ? $tungay : '01/01/2006'; ?>" class="ngaythangnam" data-inputmask="'alias': 'date'" data-validate-func="required" placeholder="Chọn ngày" />
			<span class="input-state-error mif-warning"></span><span class="input-state-success mif-checkmark"></span>
		</div>
		<div class="cell colspan2 padding-top-10 align-right">Đến ngày</div>
		<div class="cell colspan4 input-control text" data-role="datepicker" data-format="dd/mm/yyyy">
			<input type="text" name="denngay" id="denngay" value="<?php echo isset($denngay) ? $denngay : date('d/m/Y'); ?>" class="ngaythangnam" data-inputmask="'alias': 'date'" data-validate-func="required" placeholder="Chọn ngày" />
			<span class="input-state-error mif-warning"></span><span class="input-state-success mif-checkmark"></span>
		</div>
	</div>
	<div class="row cells12">
		<div class="cell colspan2 padding-top-10 align-right">Tên đoàn</div>
		<div class="cell colspan4 input-control select" data-role="select" data-placeholder="Chọn tên đoàn vào" data-allow-clear="true">
			<select name="id_dmdoanvao" id="id_dmdoanvao" class="select2">
			<option value=""></option>
			<?php
				$dmdoanvao_list = $dmdoanvao->get_all_list();
				if($dmdoanvao_list){
					foreach ($dmdoanvao_list as $dm) {
						echo '<option value="'.$dm['_id'].'"'.($dm['_id']==$id_dmdoanvao ? ' selected' : '').'>'.$dm['ten'].'</option>';
					}
				}
			?>
			</select>
		</div>
		<div class="cell colspan2 padding-top-10 align-right">Đơn vị tiếp</div>
		<div class="cell colspan4 input-control select" data-role="select" data-placeholder="Chọn tên đơn vị tiếp" data-allow-clear="true">
			<select name="id_donvi" id="id_donvi" class="select2">
				<option value=""></option>
				<?php
				$donvi_list = $donvi->get_all_list();
				if($donvi_list){
					foreach ($donvi_list as $dvi) {
						echo '<option value="'.$dvi['_id'].'"'.($dvi['_id']==$id_donvi ? ' selected' : '').'>'.$dvi['ten'].'</option>';
					}
				}
				?>
			</select>
		</div>

	</div>
	<div class="row cells12">
		<div class="cell colspan12 align-center">
			<button name="submit" id="submit" value="OK" class="button primary"><span class="mif-checkmark"></span> Thống kê</button>
			<?php if(isset($_GET['submit'])) : ?>
				<a href="in_thongkedoanvao.php?tungay=<?php echo $tungay; ?>&denngay=<?php echo $denngay; ?>&id_dmdoanvao=<?php echo $id_dmdoanvao; ?>&id_donvi=<?php echo $id_donvi; ?>&submit=OK" class="open_window button"><span class="mif-printer"></span> Print</a>
				<!--<a href="export_thongkedoanra_word.php" class="button bg-teal fg-white"><span class="mif-file-word"></span> Word</a>-->
			<?php endif; ?>
		</div>
	</div>
</div>
</form>
<hr />
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
		echo '</tr>';$i++;
	}
?>
</tbody>
</table>
<h4><span class="mif-star-full"></span> Tổng số đoàn: <?php echo $union_list->count(); ?></h4>
<h4><span class="mif-users"></span> Tổng số thành viên: <?php echo $sum_members; ?></h4>
<?php endif; ?>
<?php require_once('footer.php');?>