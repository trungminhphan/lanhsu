<?php
require_once('header.php');
$canbo = new CanBo();$doanvao=new DoanVao();$quocgia=new QuocGia();
$id_canbo ='';$id_quocgia='';$id_kinhphi='';
if(isset($_GET['submit'])){
	$query = array();
	$a = isset($_GET['id_canbo']) ? explode("-", $_GET['id_canbo'])  : '';
	if($a && count($a) > 0) $id_canbo = $a[0];
	$tungay = isset($_GET['tungay']) ? $_GET['tungay'] : '';
	$denngay = isset($_GET['denngay']) ? $_GET['denngay'] : '';
	$id_quocgia = isset($_GET['id_quocgia']) ? $_GET['id_quocgia'] : '';
	$id_kinhphi = isset($_GET['id_kinhphi']) ? $_GET['id_kinhphi'] : '';

	if(convert_date_dd_mm_yyyy($tungay) > convert_date_dd_mm_yyyy($denngay) || !$id_canbo){
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
		$q = array('$and' => $query);
		$union_list = $doanvao->get_list_condition($q);
	}
}
?>
<script type="text/javascript" src="js/select2.min.js"></script>
<script type="text/javascript" src="js/jquery.inputmask.js"></script>
<script type="text/javascript" src="js/thongkedoanra.js"></script>
<script type="text/javascript">
	$(document).ready(function(){
		$(".select2").select2();hotencanhan();
		$(".ngaythangnam").inputmask();
		<?php if(isset($msg) && $msg) : ?>
        	$.Notify({type: 'alert', caption: 'Thông báo', content: <?php echo "'".$msg."'"; ?>});
    	<?php endif; ?>
    	$(".open_window").click(function(){
		  window.open($(this).attr("href"), '_blank', 'toolbar=yes, scrollbars=yes, resizable=yes, top=0, left=100, width=1024, height=800');
		  return false;
		});
	});
</script>
<h1><a href="index.php" class="nav-button transform"><span></span></a>&nbsp;Thống kê Đoàn vào theo Cá nhân</h1>
<form method="GET" action="<?php echo $_SERVER['PHP_SELF']; ?>" id="thongkeform" data-role="validator" data-show-required-state="false" enctype="multipart/form-data">
<div class="grid" style="margin-top: 30px;">
	<div class="row cells12">
		<div class="cell colspan4 input-control select">
			<label>Họ tên</label>
			<select name="id_canbo" id="id_canbo">
			<?php
			if(isset($id_canbo) && $id_canbo){
				$canbo->id = $id_canbo; $cb = $canbo->get_one();
				echo '<option value="'.$cb['_id'].'">'.$cb['hoten'].' [ID: '. $cb['code'] .']</option>';
			}
			?>
			</select>
		</div>
		<div class="cell colspan4 input-control text" data-role="datepicker" data-format="dd/mm/yyyy">
			<label>Từ ngày</label>
			<input type="text" name="tungay" id="tungay" value="<?php echo isset($tungay) ? $tungay : '01/01/2006'; ?>" class="ngaythangnam" data-inputmask="'alias': 'date'" data-validate-func="required" placeholder="Chọn ngày" />
			<span class="input-state-error mif-warning"></span><span class="input-state-success mif-checkmark"></span>
		</div>
		<div class="cell colspan4 input-control text" data-role="datepicker" data-format="dd/mm/yyyy">
			<label>Đến ngày</label>
			<input type="text" name="denngay" id="denngay" value="<?php echo isset($denngay) ? $denngay : date('d/m/Y'); ?>" class="ngaythangnam" data-inputmask="'alias': 'date'" data-validate-func="required" placeholder="Chọn ngày" />
			<span class="input-state-error mif-warning"></span><span class="input-state-success mif-checkmark"></span>
		</div>
	</div>
	<div class="row cells12">
		<div class="cell colspan12 align-center">
			<button name="submit" id="submit" value="OK" class="button primary"><span class="mif-checkmark"></span> Thống kê</button>
			<?php if(isset($_GET['submit'])) : ?>
				<a href="in_thongkedoanvaotheocanhan.php?tungay=<?php echo $tungay; ?>&denngay=<?php echo $denngay; ?>&id_canbo=<?php echo $id_canbo; ?>&submit=OK" class="open_window button"><span class="mif-printer"></span> Print</a>
				<a href="export_thongkedoanvaotheocanhan.php?tungay=<?php echo $tungay; ?>&denngay=<?php echo $denngay; ?>&id_canbo=<?php echo $id_canbo; ?>&submit=OK" class="button success"><span class="mif-file-excel"></span> Excel</a>
				<!--<a href="export_thongkedoanratheocanhan_word.php" class="button bg-teal fg-white"><span class="mif-file-word"></span> Word</a>-->
			<?php endif; ?>
		</div>
	</div>
</div>
</form>
<hr />
<?php if(isset($union_list) && $union_list->count() > 0) : ?>
<?php
if(isset($id_canbo) && $id_canbo){
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
			echo '<tr>
				<td>'.$i.'</td>
				<td>'.$congvanxinphep.'</td>
				<td><a href="chitietdoanvao.php?id='.$u['_id'].'" target="_blank">'.$soquyetdinh.'</a></td>
				<td>'.$ngayden.'</td>
				<td>'.$ngaydi.'</td>
				<td>'.$u['noidung'].'</td>
			</tr>';$i++;
		}
	?>
	</tbody>
</table>
<?php endif; ?>
<?php require_once('footer.php'); ?>