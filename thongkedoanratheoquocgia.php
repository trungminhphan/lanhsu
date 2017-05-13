<?php
require_once('header.php');
$doanra = new DoanRa();$quocgia=new QuocGia();
$quocgia_list = $quocgia->get_all_list();
if(isset($_GET['submit'])){
	$query = array();
	$id_quocgia = isset($_GET['id_quocgia']) ? $_GET['id_quocgia'] : '';
	$tungay = isset($_GET['tungay']) ? $_GET['tungay'] : '';
	$denngay = isset($_GET['denngay']) ? $_GET['denngay'] : '';
	if(convert_date_dd_mm_yyyy($tungay) > convert_date_dd_mm_yyyy($denngay)){
		$msg = 'Chọn ngày sai';
	} else {
		$start_date = new MongoDate(convert_date_dd_mm_yyyy($tungay));
		$end_date = new MongoDate(convert_date_dd_mm_yyyy($denngay));
		array_push($query, array('ngaydi' => array('$gte' => $start_date)));
		array_push($query, array('ngayve' => array('$lte' => $end_date)));
		//$query = array('$and' => $query);
		$danhsachquocgia = $id_quocgia ? $quocgia->get_list_condition(array('_id' => new MongoId($id_quocgia))) : $quocgia->get_all_list();
	}
}
?>
<script type="text/javascript" src="js/select2.min.js"></script>
<script type="text/javascript" src="js/jquery.dataTables.min.js"></script>
<script type="text/javascript" src="js/jquery.inputmask.js"></script>
<script type="text/javascript">
	$(document).ready(function(){
		$(".select2").select2();
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
<h1><a href="index.php" class="nav-button transform"><span></span></a>&nbsp;Thống kê Đoàn ra theo Quốc gia</h1>
<form method="GET" action="<?php echo $_SERVER['PHP_SELF']; ?>" id="thongkeform" data-role="validator" data-show-required-state="false" enctype="multipart/form-data">
<div class="grid" style="margin-top:30px;">
	<div class="row cells12">
		<div class="cell colspan4 input-control select" data-role="select" data-placeholder="Chọn Quốc gia" data-allow-clear="true">
			<label>Quốc gia</label>
			<select name="id_quocgia" id="id_quocgia" class="select2">
			<option value="">Chọn Quốc gia</option>
			<?php
			if($quocgia_list){
				foreach ($quocgia_list as $qg) {
					echo '<option value="'.$qg['_id'].'"'.($qg['_id']==$id_quocgia ? ' selected' : '').'>'.$qg['ten'].'</option>';
				}
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
				<a href="in_thongkedoanratheoquocgia.php?tungay=<?php echo $tungay; ?>&denngay=<?php echo $denngay; ?>&id_quocgia=<?php echo $id_quocgia; ?>&submit=OK" class="open_window button"><span class="mif-printer"></span> Print</a>
			<?php endif; ?>
		</div>
	</div>
</div>
</form>
<hr />
<?php if(isset($danhsachquocgia) && $danhsachquocgia): ?>
<table class="table border bordered striped dataTable" data-role="datatable">
	<thead>
		<tr>
			<th>STT</th>
			<th>Tên Quốc gia</th>
			<th>Lượt xuất cảnh</th>
			<th>Xem chi tiết</th>
		</tr>
	</thead>
	<tbody>
	<?php
	$i =1;
	foreach ($danhsachquocgia as $ds) {
		$q = $query;
		array_push($q, array('id_quocgia' => $ds['_id']->{'$id'}));
		$count = $doanra->get_list_condition(array('$and' => $q))->count();
		echo '<tr>
			<td>'.$i.'</td>
			<td>'.$ds['ten'].'</td>
			<td>'.$count.'</td>
			<td><a href="chitietthongkedoanratheoquocgia.php?id_quocgia='.$ds['_id'].'&tungay='.$tungay.'&denngay='.$denngay.'&submit=OK" target="_blank"><span class="mif-cloud-upload"></span></a></td>
		</tr>';	$i++;
	}
	?>
	</tbody>
</table>
<?php endif; ?>
<?php require_once('footer.php');?>