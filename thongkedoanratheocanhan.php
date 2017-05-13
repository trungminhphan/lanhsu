<?php
require_once('header.php');
$kinhphi = new KinhPhi();$quocgia = new QuocGia();$canbo = new CanBo();$doanra=new DoanRa();$mucdich= new MucDich();
$id_canbo ='';$id_quocgia='';$id_kinhphi='';$id_mucdich = '';
if(isset($_GET['submit'])){
	$query = array();
	$id_canbo = isset($_GET['id_canbo']) ? $_GET['id_canbo']  : '';
	$tungay = isset($_GET['tungay']) ? $_GET['tungay'] : '';
	$denngay = isset($_GET['denngay']) ? $_GET['denngay'] : '';
	$id_quocgia = isset($_GET['id_quocgia']) ? $_GET['id_quocgia'] : '';
	$id_kinhphi = isset($_GET['id_kinhphi']) ? $_GET['id_kinhphi'] : '';
	$id_mucdich = isset($_GET['id_mucdich']) ? $_GET['id_mucdich'] : '';
	if(convert_date_dd_mm_yyyy($tungay) > convert_date_dd_mm_yyyy($denngay) || !$id_canbo){
		$msg = 'Chọn ngày sai hoặc chưa chọn Cá nhân thống kê';
	} else {
		$start_date = new MongoDate(convert_date_dd_mm_yyyy($tungay));
		$end_date = new MongoDate(convert_date_dd_mm_yyyy($denngay));
		array_push($query, array('ngaydi' => array('$gte' => $start_date)));
		array_push($query, array('ngayve' => array('$lte' => $end_date)));
		if($id_canbo){
			$arr_cb = array('$or' => array(array('danhsachdoan.id_canbo' => new MongoId($id_canbo)), array('danhsachdoan_2.id_canbo'=> new MongoId($id_canbo))));
			array_push($query, $arr_cb);
		}
		if($id_kinhphi){
			array_push($query, array('id_kinhphi' => new MongoId($id_kinhphi)));
		}
		if($id_quocgia){
			array_push($query, array('id_quocgia' => $id_quocgia));
		}
		if($id_mucdich){
			array_push($query, array('id_mucdich' => new MongoId($id_mucdich)));	
		}
		$q = array('$and' => $query);
		$union_list = $doanra->get_list_condition($q);
	}
}
$kinhphi_list = $kinhphi->get_all_list();
$quocgia_list = $quocgia->get_all_list();
$mucdich_list = $mucdich->get_all_list();
?>
<script type="text/javascript" src="js/select2.min.js"></script>
<script type="text/javascript" src="js/jquery.inputmask.js"></script>
<script type="text/javascript" src="js/jquery.dataTables.min.js"></script>
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
		$('#list_result').dataTable();
	});
</script>
<h1><a href="index.php" class="nav-button transform"><span></span></a>&nbsp;Thống kê Đoàn ra theo Cá nhân</h1>
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
			<input type="text" name="tungay" id="tungay" value="<?php echo isset($tungay) ? $tungay : '01/01/2006'; ?>" class="ngaythangnam" data-inputmask="'alias': 'date'" data-validate-func="required" placeholder="Chọn ngày" value="01/01/2006" />
			<span class="input-state-error mif-warning"></span><span class="input-state-success mif-checkmark"></span>
		</div>
		<div class="cell colspan4 input-control text" data-role="datepicker" data-format="dd/mm/yyyy">
			<label>Đến ngày</label>
			<input type="text" name="denngay" id="denngay" value="<?php echo isset($denngay) ? $denngay : date('d/m/Y'); ?>" class="ngaythangnam" data-inputmask="'alias': 'date'" data-validate-func="required" placeholder="Chọn ngày" />
			<span class="input-state-error mif-warning"></span><span class="input-state-success mif-checkmark"></span>
		</div>
	</div>
	<div class="row cells12" style="padding-top:10px;">
		<div class="cell colspan4 input-control select">
			<label>Nước đến</label>
			<select name="id_quocgia" id="id_quocgia" class="select2" >
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
		<div class="cell colspan4 input-control select">
			<label>Kinh phí</label>
			<select name="id_kinhphi" id="id_kinhphi" class="select2" >
			<option value="">Chọn Kinh phí</option>
			<?php
			if($kinhphi_list){
				foreach ($kinhphi_list as $kp) {
					echo '<option value="'.$kp['_id'].'"'.($kp['_id']==$id_kinhphi ? ' selected' : '').'>'.$kp['ten'].'</option>';
				}
			}
			?>
			</select>
		</div>
		<div class="cell colspan4 input-control select">
			<label>Mục đích</label>
			<select name="id_mucdich" id="id_mucdich" class="select2">
				<option value="">Chọn Mục đích</option>
				<?php
				if($mucdich_list){
					foreach ($mucdich_list as $md) {
						echo '<option value="'.$md['_id'].'"'.($md['_id']==$id_mucdich ? ' selected' : '').'>'.$md['ten'].'</option>';
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
				<a href="in_thongkedoanratheocanhan.php?<?php echo $_SERVER['QUERY_STRING']; ?>" class="open_window button"><span class="mif-printer"></span> Print</a>
				<a href="export_thongkedoanratheocanhan.php?<?php echo $_SERVER['QUERY_STRING']; ?>" class="button success"><span class="mif-file-excel"></span> Excel</a>
			<?php endif; ?>
		</div>
	</div>
</div>
</form>
<hr />
<?php if(isset($union_list) && $union_list->count() > 0) : ?>
<?php
if(isset($id_canbo) && $id_canbo){
	echo '<h4>' . $cb['hoten'] .': <span class="fg-blue">'.$union_list->count().' lượt xuất cảnh</span></h4>';
}
?>
<table class="table border bordered striped" id="list_result">
	<thead>
		<tr>
			<th>STT</th>
			<th>Văn bản xin phép</th>
			<th>Văn bản cho phép</th>
			<th>Ngày đi</th>
			<th>Ngày về</th>
			<th>Số ngày</th>
			<th>Nước đến</th>
			<th>Kinh phí</th>
			<th>Nội dung</th>
		</tr>
	</thead>
	<tbody>
	<?php
		$i = 1;
		$arr_union_list = iterator_to_array($union_list);
		$arr_union_list = sort_array_and_key($arr_union_list, 'ngaydi', SORT_DESC);
		foreach ($arr_union_list as $u) {
			$congvanxinphep = $u['congvanxinphep']['ten'];
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
				<td>'.$congvanxinphep.'</td>
				<td><a href="chitietdoanra.php?id='.$u['_id'].'" target="_blank">'.$soquyetdinh.'</a></td>
				<td>'.$ngaydi.'</td>
				<td>'.$ngayve.'</td>
				<td>'.$u['songay'].'</td>
				<td>'.$nuocden.'</td>
				<td>'.$tenkinhphi.'</td>
				<td>'.$u['noidung'].'</td>
			</tr>';$i++;
		}
	?>
	</tbody>
</table>
<?php endif; ?>
<?php require_once('footer.php'); ?>