<?php require_once('header.php');
$canbo = new CanBo(); $doanra=new DoanRa();$doanvao = new DoanVao(); $quocgia = new QuocGia();
$query_canbo = array();
$quocgia_list = $quocgia->get_all_list();
$congchuc='';$vienchuc='';$dangvien=0;$tinhuyvien=0;$ngoainuoc=0;
if(isset($_GET['submit'])){
	$congchuc = isset($_GET['congchuc']) ? $_GET['congchuc'] : '';
	$vienchuc = isset($_GET['vienchuc']) ? $_GET['vienchuc'] : '';
	$dangvien = isset($_GET['dangvien']) ? $_GET['dangvien'] : '';
	$tinhuyvien = isset($_GET['tinhuyvien']) ? $_GET['tinhuyvien'] : '';
	$ngoainuoc = isset($_GET['ngoainuoc']) ? $_GET['ngoainuoc'] : '';

	$tungay = isset($_GET['tungay']) ? $_GET['tungay'] : '';
	$denngay = isset($_GET['denngay']) ? $_GET['denngay'] : '';
	if(convert_date_dd_mm_yyyy($tungay) > convert_date_dd_mm_yyyy($denngay)){
		$msg = 'Chọn sai ngày thống kê.';
	} else {
		if($ngoainuoc){
			$query = array('$and' => array(array('id_quoctich' => array('$ne' => '56f9fd7732341c4008002015')), array('id_quoctich' => array('$ne' => new MongoId('56f9fd7732341c4008002015')))));
			$canbo_list = $canbo->get_list_condition($query);
		} else {
			if($congchuc == 'CC'){
				array_push($query_canbo, array('loaicongchuc' => 'CC'));
			}
			if($vienchuc == 'VC'){
				array_push($query_canbo, array('loaicongchuc' => 'VC'));
			}
			if($dangvien == 1){
				array_push($query_canbo, array('dangvien' => '1'));
			}
			if($tinhuyvien == 1){
				array_push($query_canbo, array('tinhuyvien' => '1'));
			}
			if(count($query_canbo) > 0)
			$canbo_list = $canbo->get_list_condition(array('$and' => $query_canbo));
		}
		$start_date = new MongoDate(convert_date_dd_mm_yyyy($tungay));
		$end_date = new MongoDate(convert_date_dd_mm_yyyy($denngay));
	}
} else {
	$canbo_list = '';
}

?>
<script type="text/javascript">
	$(document).ready(function(){
		$(".ngaythangnam").inputmask();
		<?php if(isset($msg) && $msg) : ?>
        	$.Notify({type: 'alert', caption: 'Thông báo', content: <?php echo "'".$msg."'"; ?>});
    	<?php endif; ?>

    	$(".trongnuoc").click(function(){
    		if($(this).is(":checked")){
    			$('.ngoainuoc').prop('disabled', true);
    			$('.ngoainuoc').prop('checked', false);
    		} else {
    			$('.ngoainuoc').prop('disabled', false);
    		}
    	});
    	$(".ngoainuoc").click(function(){
    		if($(this).is(":checked")){
    			$('.trongnuoc').prop('disabled', true);
    			$('.trongnuoc').prop('checked', false);
    		} else {
    			$('.trongnuoc').prop('disabled', false);
    		}
    	});
	});
</script>
<script type="text/javascript" src="js/jquery.inputmask.js"></script>
<h1><a href="index.php" class="nav-button transform"><span></span></a>&nbsp;Thống kê Phân loại</h1>
<form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="get" accept-charset="utf-8" id="thongkephanloaiform">
<div class="grid">
	<div class="row cells12 align-center">
		<div class="cell colspan12">
			<label class="input-control checkbox">
		    	<input type="checkbox" class="trongnuoc" name="congchuc" value="CC" id="congchuc" <?php echo $congchuc=='CC' ? ' checked' : ''; ?>>
		        <span class="check"></span>
				<span class="caption">Công chức</span>
			</label>
			&nbsp;&nbsp;&nbsp;
			<label class="input-control checkbox">
		    	<input type="checkbox" class="trongnuoc" name="vienchuc" value="VC" id="vienchuc" <?php echo $vienchuc=='VC' ? ' checked': ''; ?>>
		        <span class="check"></span>
				<span class="caption">Viên chức</span>
			</label>
			&nbsp;&nbsp;&nbsp;
			<label class="input-control checkbox">
		    	<input type="checkbox" class="trongnuoc" name="dangvien" id="dangvien" value="1" <?php echo $dangvien==1 ? ' checked' : ''; ?>>
		        <span class="check"></span>
				<span class="caption">Đảng viên</span>
			</label>
			&nbsp;&nbsp;&nbsp;
			<label class="input-control checkbox">
		    	<input type="checkbox" class="trongnuoc" name="tinhuyvien" id="tinhuyvien" value="1" <?php echo $tinhuyvien==1 ? ' checked' : ''; ?>>
		        <span class="check"></span>
				<span class="caption">Tỉnh uỷ viên</span>
			</label>
			&nbsp;&nbsp;&nbsp;
			<label class="input-control checkbox">
		    	<input type="checkbox" class="ngoainuoc" name="ngoainuoc" id="ngoainuoc" value="1" <?php echo $ngoainuoc==1 ? ' checked' : ''; ?>>
		        <span class="check"></span>
				<span class="caption">Người nước ngoài</span>
			</label>
		</div>
	</div>
	<div class="row cells12">
		<div class="cell colspan12 align-center">
			Từ ngày:
			<div class="input-control text" data-role="datepicker" data-format="dd/mm/yyyy">
				<input type="text" name="tungay" id="tungay" value="<?php echo isset($tungay) ? $tungay : '01/01/2006'; ?>" class="ngaythangnam" data-inputmask="'alias': 'date'" data-validate-func="required" placeholder="Chọn ngày" value="01/01/2006" />
				<span class="input-state-error mif-warning"></span><span class="input-state-success mif-checkmark"></span>
			</div>
			&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Đến ngày:
			<div class="input-control text" data-role="datepicker" data-format="dd/mm/yyyy">
				<input type="text" name="denngay" id="denngay" value="<?php echo isset($denngay) ? $denngay : date('d/m/Y'); ?>" class="ngaythangnam" data-inputmask="'alias': 'date'" data-validate-func="required" placeholder="Chọn ngày" />
				<span class="input-state-error mif-warning"></span><span class="input-state-success mif-checkmark"></span>
			</div>
		</div>
	</div>
	<div class="row cells12">
		<div class="cell colspan12 align-center">
			<button name="submit" id="submit" value="OK" class="button primary"><span class="mif-checkmark"></span> OK</button>
			<?php if(isset($_GET['submit'])) : ?>
				<a href="export_thongkephanloai.php?<?php echo $_SERVER['QUERY_STRING']; ?>" class="button success"><span class="mif-file-excel"></span> Excel</a>
			<?php endif; ?>
		</div>
	</div>
</div>
</form>
<hr />

<?php if(isset($canbo_list) && $canbo_list && $canbo_list->count() >0): ?>
	<?php if($ngoainuoc): ?>
		<table class="table striped hovered border bordered">
			<thead>
				<tr>
					<th>STT</th>
					<th>ID</th>
					<th>Họ tên</th>
					<th>Đơn vị</th>
					<th>Chức vụ</th>
					<th>Thống kê</th>
				</tr>
			</thead>
			<tbody>
			<?php
			$i = 1;$donvi = new DonVi();$chucvu = new ChucVu();$count=0;
			$query_date = array(array('ngayden' => array('$gte' => $start_date)), array('ngaydi' => array('$lte' => $end_date)));
			foreach ($canbo_list as $cb) {
				$query_check = $query_date;
				$arr_cb = array('$or' => array(array('danhsachdoan.id_canbo' => new MongoId($cb['_id'])), array('danhsachdoan_2.id_canbo'=> new MongoId($cb['_id']))));
				array_push($query_check, $arr_cb);
				$q = array('$and' => $query_check);
				$count = $doanvao->count_soluong($q);
				if($count){
					if(isset($cb['donvi']) && $cb['donvi']){
							if(isset($cb['donvi'][0]['id_donvi'][0]) && $cb['donvi'][0]['id_donvi'][0]){
							$tendonvi = $donvi->tendonvi($cb['donvi'][0]['id_donvi']);
						} else { $tendonvi = ''; $full_donvi='';}
						if(isset($cb['donvi'][0]['id_chucvu']) && $cb['donvi'][0]['id_chucvu']){
							$chucvu->id = $cb['donvi'][0]['id_chucvu'];$cv = $chucvu->get_one();
							$tenchucvu = $cv['ten'];
						} else{
							$tenchucvu = '';
						}
					} else {
						$tendonvi='';$tenchucvu='';
					}
					echo '<tr>';
					echo '<td>'.$i.'</td>';
					echo '<td>'.$cb['code'].'</td>';
					echo '<td>'.$cb['hoten'].'</td>';
					echo '<td>'.$tendonvi.'</td>';
					echo '<td>'.$tenchucvu.'</td>';
					echo '<td class="align-right">'.$count.'</td>';
					echo '</td>';
					echo '<tr>';$i++;
				}
			}

			?>
			</tbody>
		</table>
	<?php else : ?>
		<table class="table striped hovered border bordered">
			<thead>
				<tr>
					<th>STT</th>
					<th>ID</th>
					<th>Họ tên</th>
					<th>Đơn vị</th>
					<th>Chức vụ</th>
					<th>Quốc gia</th>
				</tr>
			</thead>
			<tbody>
			<?php
			$query_date = array(array('ngaydi' => array('$gte' => $start_date)), array('ngayve' => array('$lte' => $end_date)));
			$i = 1;
			foreach ($canbo_list as $cb) {
				$query_check = $query_date;
				$arr_cb = array('$or' => array(array('danhsachdoan.id_canbo' => new MongoId($cb['_id'])), array('danhsachdoan_2.id_canbo'=> new MongoId($cb['_id']))));
				array_push($query_check, $arr_cb);
				$q = array('$and' => $query_check);
				if($doanra->check_thongkephanloai($q)){
					if($cb['donvi']){
						$donvi = new DonVi();$chucvu = new ChucVu();
						if(isset($cb['donvi'][0]['id_donvi'][0]) && $cb['donvi'][0]['id_donvi'][0]){
							$count = count($cb['donvi']) - 1;
							$tendonvi = $donvi->tendonvi($cb['donvi'][$count]['id_donvi']);
						} else { $tendonvi = ''; $full_donvi='';}
						if(isset($cb['donvi'][0]['id_chucvu']) && $cb['donvi'][0]['id_chucvu']){
							$count = count($cb['donvi']) - 1;
							$chucvu->id = $cb['donvi'][$count]['id_chucvu'];$cv = $chucvu->get_one();
							$tenchucvu = $cv['ten'];
						} else{
							$tenchucvu = '';
						}
					} else {
						$tendonvi='';$tenchucvu='';
					}
					echo '<tr>';
					echo '<td>'.$i.'</td>';
					echo '<td>'.$cb['code'].'</td>';
					echo '<td>'.$cb['hoten'].'</td>';
					echo '<td>'.$tendonvi.'</td>';
					echo '<td>'.$tenchucvu.'</td>';
					echo '<td style="min-width:300px;">';
					if($quocgia_list){
						foreach ($quocgia_list as $qg) {
							$query_count = $query_check;
							array_push($query_count, array('id_quocgia' => $qg['_id']->{'$id'}));
							$qc = array('$and' => $query_count);
							$count = $doanra->count_thongkephanloai_quocgia($qc);
							if($count){
								echo $qg['ten'] . '<sup><span class="tag alert"><a href="thongkedoanratheocanhan.php?id_canbo='.$cb['_id'].'&tungay='.$tungay.'&denngay='.$denngay.'&id_quocgia='.$qg['_id'].'&submit=OK" class="fg-white" target="_blank">'.$count.'</a></span></sup>&nbsp;&nbsp;';
							}
						}
					}
					echo '</td>';
					echo '<tr>';$i++;
				}
			}
			?>
			</tbody>
		</table>
	<?php endif; ?>
<?php endif; ?>
<?php require_once('footer.php'); ?>
