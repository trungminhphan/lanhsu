<?php
require_once('header.php');
$quocgia = new QuocGia();$canbo = new CanBo();$doanvao=new DoanVao(); $donvi = new DonVi();
$linhvuc = new LinhVuc();$id_linhvuc = '';
if(isset($_GET['submit'])){
	$query = array();
	$id_linhvuc = isset($_GET['id_linhvuc']) ? $_GET['id_linhvuc'] : '';
	$tungay = isset($_GET['tungay']) ? $_GET['tungay'] : '';
	$denngay = isset($_GET['denngay']) ? $_GET['denngay'] : '';

	if(convert_date_dd_mm_yyyy($tungay) > convert_date_dd_mm_yyyy($denngay)){
		$msg = 'Chọn ngày sai hoặc chưa chọn Đơn vị thống kê';
	} else {
		$start_date = new MongoDate(convert_date_dd_mm_yyyy($tungay));
		$end_date = new MongoDate(convert_date_dd_mm_yyyy($denngay));
		array_push($query, array('ngayden' => array('$gte' => $start_date)));
		array_push($query, array('ngaydi' => array('$lte' => $end_date)));
		if($id_linhvuc){
			array_push($query, array('id_linhvuc' => new MongoId($id_linhvuc)));
		}
		$q = array('$and' => $query);
		$union_list = $doanvao->get_list_condition($q);
	}
}
?>
<script type="text/javascript" src="js/select2.min.js"></script>
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
<h1><a href="index.php" class="nav-button transform"><span></span></a>&nbsp;Thống kê Đoàn vào theo Lĩnh vực</h1>
<form method="GET" action="<?php echo $_SERVER['PHP_SELF']; ?>" id="thongkeform" data-role="validator" data-show-required-state="false" enctype="multipart/form-data">
<div class="grid" style="margin-top:30px;">
	<div class="row cells12">
		<div class="cell colspan4 input-control select">
			<label>Lĩnh vực</label>
			<select name="id_linhvuc" id="id_linhvuc" class="select2">
				<option value="">Chọn Lĩnh vực</option>
				<?php
				$linhvuc_list = $linhvuc->get_all_list();
				if($linhvuc_list){
					foreach ($linhvuc_list as $md) {
						echo '<option value="'.$md['_id'].'"'.($md['_id']==$id_linhvuc ? ' selected' : '').'>'.$md['ten'].'</option>';
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
		</div>
	</div>
</div>
</form>
<hr />
<?php if(isset($union_list) && $union_list->count() > 0) : ?>
	<table class="table border bordered striped">
	<thead>
		<tr>
			<th>STT</th>
			<th>Trưởng đoàn</th>
			<th>Quốc tịch</th>
			<th>Văn bản xin phép</th>
			<th>Văn bản cho phép</th>
			<th>Ngày đi</th>
			<th>Ngày về</th>
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
			} else { $tentruongdoan = '';}


			$soquyetdinh = $u['quyetdinhchophep']['ten'];
			$congvanxinphep = $u['congvanxinphep']['ten'];
			$ngayden = $u['ngayden'] ? date("d/m/Y", $u['ngayden']->sec) : '';
			$ngaydi = $u['ngaydi'] ? date("d/m/Y", $u['ngaydi']->sec) : '';
			if($cb['id_quoctich']){
				$quocgia->id = $cb['id_quoctich']; $qt = $quocgia->get_one();
				$tenquoctich = $qt['ten'];
			} else { $tenquoctich = ''; }
			echo '<tr>
				<td>'.$i.'</td>
				<td>'.$tentruongdoan.'</td>
				<td>'.$tenquoctich.'</td>
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
