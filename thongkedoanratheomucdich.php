<?php
require_once('header.php');
$kinhphi = new KinhPhi();$quocgia = new QuocGia();$canbo = new CanBo();$doanra=new DoanRa();
$mucdich = new MucDich();
$donvi = new DonVi();
if(isset($_GET['submit'])){
	$query = array();
	$id_mucdich = isset($_GET['id_mucdich']) ? $_GET['id_mucdich'] : '';
	$tungay = isset($_GET['tungay']) ? $_GET['tungay'] : '';
	$denngay = isset($_GET['denngay']) ? $_GET['denngay'] : '';
	if(convert_date_dd_mm_yyyy($tungay) > convert_date_dd_mm_yyyy($denngay)){
		$msg = 'Chọn ngày sai hoặc chưa chọn Đơn vị thống kê';
	} else {
		$start_date = new MongoDate(convert_date_dd_mm_yyyy($tungay));
		$end_date = new MongoDate(convert_date_dd_mm_yyyy($denngay));
		array_push($query, array('ngaydi' => array('$gte' => $start_date)));
		array_push($query, array('ngaydi' => array('$lte' => $end_date)));
		//array_push($query, array('ngayve' => array('$lte' => $end_date)));
		if($id_mucdich){
			array_push($query, array('id_mucdich' => new MongoId($id_mucdich)));
		}
		$q = array('$and' => $query);
		$union_list = $doanra->get_list_condition($q);
	}
}
$mucdich_list = $mucdich->get_all_list();
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
<h1><a href="index.php" class="nav-button transform"><span></span></a>&nbsp;Thống kê Đoàn ra theo Mục đích</h1>
<form method="GET" action="<?php echo $_SERVER['PHP_SELF']; ?>" id="thongkeform" data-role="validator" data-show-required-state="false" enctype="multipart/form-data">
<div class="grid" style="margin-top:30px;">
	<div class="row cells12">
		<div class="cell colspan4 input-control select" data-role="select" data-placeholder="Chọn loại Mục đích" data-allow-clear="true">
			<label>Mục đích</label>
			<select name="id_mucdich" id="id_mucdich" class="select2" >
			<option value="">Chọn mục đích</option>
			<?php
			if($mucdich_list){
				foreach ($mucdich_list as $md) {
					echo '<option value="'.$md['_id'].'"'.($md['_id']==$id_mucdich ? ' selected' : '').'>'.$md['ten'].'</option>';
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
				<a href="in_thongkedoanratheomucdich.php?tungay=<?php echo $tungay; ?>&denngay=<?php echo $denngay; ?>&id_mucdich=<?php echo $id_mucdich; ?>&submit=OK" class="open_window button"><span class="mif-printer"></span> Print</a>
				<a href="export_thongkedoanratheomucdich.php?tungay=<?php echo $tungay; ?>&denngay=<?php echo $denngay; ?>&id_mucdich=<?php echo $id_mucdich; ?>&submit=OK" class="button success"><span class="mif-file-excel"></span> Excel</a>
			<?php endif; ?>
		</div>
	</div>
</div>
</form>
<hr />
<?php if(isset($union_list) && $union_list->count() > 0) : ?>
	<table class="table border bordered striped dataTable" data-role="dataTable">
	<thead>
		<tr>
			<th>STT</th>
			<th>Trưởng đoàn</th>
			<th>Văn bản cho phép</th>
			<th>Ngày đi</th>
			<th>Ngày về</th>
			<th>Số ngày</th>
			<th>Nước đến</th>
			<th>Kinh phí</th>
			<th>Nội dung</th>
			<th>Chi tiết</th>
		</tr>
	</thead>
	<tbody>
	<?php
		$i = 1;
		foreach ($union_list as $u) {
			if(isset($u['danhsachdoan'][0]['id_canbo']) && $u['danhsachdoan'][0]['id_canbo']){
				$canbo->id = $u['danhsachdoan'][0]['id_canbo']; $cb = $canbo->get_one();
				$truongdoan = $cb['hoten'];
			} else {
				$truongdoan = '';
			}
			$soquyetdinh = $u['quyetdinhchophep']['ten'];
			$ngaydi = $u['ngaydi'] ? date("d/m/Y", $u['ngaydi']->sec) : '';
			$ngayve = $u['ngayve'] ? date("d/m/Y", $u['ngayve']->sec) : '';
			if($u['id_quocgia']) $nuocden = $quocgia->get_quoctich($u['id_quocgia']);
			else $nuocden = '';
			if($u['id_kinhphi']){
				$kinhphi->id = $u['id_kinhphi']; $kp = $kinhphi->get_one();
				$tenkinhphi = $kp['ten'];
			} else { $tenkinhphi = ''; }
			echo '<tr>
				<td>'.$i.'</td>
				<td>'.$truongdoan.'</td>
				<td>'.$soquyetdinh.'</td>
				<td>'.$ngaydi.'</td>
				<td>'.$ngayve.'</td>
				<td class="align-right">'.$u['songay'].'</td>
				<td>'.$nuocden.'</td>
				<td>'.$tenkinhphi.'</td>
				<td>'.$u['noidung'].'</td>
				<td><a href="chitietdoanra.php?id='.$u['_id'].'" target="_blank"><span class="mif-list2"></span></a></td>
			</tr>';$i++;
		}
	?>
	</tbody>
</table>
<?php endif; ?>
<?php require_once('footer.php'); ?>
