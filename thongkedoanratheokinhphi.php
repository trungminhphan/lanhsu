<?php
require_once('header.php');
$doanra = new DoanRa(); $kinhphi = new KinhPhi();$quocgia=new QuocGia();$mucdich = new MucDich();
$msg = '';$id_kinhphi = ''; $donvi = new DonVi();$chucvu = new ChucVu();$canbo = new CanBo();$ham=new Ham();
$kinhphi_list = $kinhphi->get_all_list();

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
		array_push($query, array('ngaydi' => array('$lte' => $end_date)));
		//array_push($query, array('ngayve' => array('$lte' => $end_date)));
		if($id_kinhphi){
			array_push($query, array('id_kinhphi' => new MongoId($id_kinhphi)));
		}
		$query = array('$and' => $query);
		$union_list = $doanra->get_list_condition($query);
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
		$(".items_detail").hide();
		$(".items").click(function(){
			$(this).next(".items_detail").toggle();
		});
		$("#show_all").click(function(){
			if($(".items_detail").is(":hidden")){
				$(this).html('<span class="mif-minus"> Đóng tất cả');
				$(".items_detail").show();
			} else {
				$(this).html('<span class="mif-plus"> Hiển thị tất cả');
				$(".items_detail").hide();
			}

		});

	});
</script>
<h1><a href="index.php" class="nav-button transform"><span></span></a>&nbsp;Thống kê Đoàn ra theo Kinh phí</h1>
<form method="GET" action="<?php echo $_SERVER['PHP_SELF']; ?>" id="thongkeform" data-role="validator" data-show-required-state="false" enctype="multipart/form-data">
<div class="grid" style="margin-top:30px;">
	<div class="row cells12">
		<div class="cell colspan4 input-control select" data-role="select" data-placeholder="Chọn loại Kinh phí" data-allow-clear="true">
			<label>Kinh phí</label>
			<select name="id_kinhphi" id="id_kinhphi" class="select2" >
			<option value="">Chọn kinh phí</option>
			<?php
			if($kinhphi_list){
				foreach ($kinhphi_list as $kp) {
					echo '<option value="'.$kp['_id'].'"'.($kp['_id']==$id_kinhphi ? ' selected' : '').'>'.$kp['ten'].'</option>';
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
				<a href="in_thongkedoanratheokinhphi.php?tungay=<?php echo $tungay; ?>&denngay=<?php echo $denngay; ?>&id_kinhphi=<?php echo $id_kinhphi; ?>&submit=OK" class="open_window button"><span class="mif-printer"></span> Print</a>
				<!--<a href="export_thongkedoanra_word.php" class="button bg-teal fg-white"><span class="mif-file-word"></span> Word</a>-->
			<?php endif; ?>
		</div>
	</div>
</div>
</form>
<hr />
<?php if(isset($union_list) && $union_list) : ?>
<h4 class="align-center">BÁO CÁO TÌNH HÌNH CÁN BỘ, ĐẢNG VIÊN ĐI HỌC, CÔNG TÁC, DU LỊCH NƯỚC NGOÀI</h4>
<h5 class="align-center">Từ ngày: <b><?php echo $tungay; ?></b> Đến ngày: <b><?php echo $denngay; ?></b></h5>
<a href="#" id="show_all" class="button primary" onclick="return false;"><span class="mif-plus"></span> Hiển thị tất cả</a>
<table class="table cell-hovered border bordered">
<thead>
	<tr>
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
$i = 1;$sum_members=0; $total_money = 0;
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
				if(isset($ds['id_donvi']) && $ds['id_donvi']){
					$tendonvi = $donvi->tendonvi($ds['id_donvi']);
				} else {$tendonvi = '';}
				if(isset($ds['id_ham']) && $ds['id_ham']) {
					$ham->id = $ds['id_ham']; $h=$ham->get_one(); $tenham=isset($h['ten']) ? $h['ten'] : '';
				} else { $tenham = '';}
				if(count($cb['passport'])){
					$count = count($cb['passport']) - 1;
					$passport = $cb['passport'][$count];
				} else {
					$passport = '';
				}
				echo '<div class="row cells12">';
					echo '<div class="cell colspan3">'.$j . '. ' . $cb['hoten'].'</div>';
					echo '<div class="cell colspan5">'.$tendonvi.'</div>';
					echo '<div class="cell colspan2">'.($tenham ? $tenham .', ' : '') . $cv['ten'].'</div>';
					echo '<div class="cell colspan2">'.$passport.'</div>';
				echo '</div>';
				$j++;$sum_members++;
			}
		}
		if($u['danhsachdoan_2']){
			foreach ($u['danhsachdoan_2'] as $ds2) {
				$canbo->id = $ds2['id_canbo']; $cb = $canbo->get_one();
				$chucvu->id = $ds2['id_chucvu'];$cv = $chucvu->get_one();
				if(isset($ds2['id_donvi']) && $ds2['id_donvi']){
					$tendonvi = $donvi->tendonvi($ds2['id_donvi']);
				} else {$tendonvi = '';}
				if(isset($ds2['id_ham']) && $ds2['id_ham']) {
					$ham->id = $ds2['id_ham']; $h=$ham->get_one(); $tenham=isset($h['ten']) ? $h['ten'] : '';
				} else { $tenham = '';}
				if(count($cb['passport'])){
					$count = count($cb['passport']) - 1;
					$passport = $cb['passport'][$count];
				} else {
					$passport = '';
				}
				echo '<div class="row cells12">';
					echo '<div class="cell colspan3">'.$j . '. ' . $cb['hoten'].'</div>';
					echo '<div class="cell colspan5">'.$tendonvi.'</div>';
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
<?php require_once('footer.php');?>
