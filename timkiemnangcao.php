<?php
require_once('header.php');
$canbo = new CanBo();$doanra=new DoanRa();$doanvao = new DoanVao(); $abtc = new ABTC();
$ham = new Ham();$donvi = new DonVi();$nghenghiep = new NgheNghiep(); $dantoc= new DanToc();
$loaitimkiem = isset($_GET['loaitimkiem']) ? $_GET['loaitimkiem'] : 'doanra';
$query = array(); $arr_canbo = array();$loaicongchuc='';$dangvien='';$tinhuyvien='';$gioitinh='';
$id_quoctich='';$id_nghenghiep='';$id_donvi_1='';$id_nghenghiep='';$id_dantoc='';
$check_gioitinh='';$check_quoctich='';$check_donvicongtac='';$check_chucvu='';$check_nghenghiep='';$check_dantoc='';
$title = 'Tìm kiếm Cá nhân theo Đoàn ra';
if(isset($_GET['submit'])){
	$loaicongchuc = isset($_GET['loaicongchuc']) ? $_GET['loaicongchuc'] : '';
	$dangvien = isset($_GET['dangvien']) ? $_GET['dangvien'] : '';
	$tinhuyvien = isset($_GET['tinhuyvien']) ? $_GET['tinhuyvien'] : '';
	$cmnd = isset($_GET['cmnd']) ? $_GET['cmnd'] : '';
	$passport = isset($_GET['passport']) ? $_GET['passport'] : '';
	$hoten = isset($_GET['hoten']) ? $_GET['hoten'] : '';
	$ngaysinh = isset($_GET['ngaysinh']) ? $_GET['ngaysinh'] : '';
	$gioitinh = isset($_GET['gioitinh']) ? $_GET['gioitinh'] : '';
	$id_quoctich = isset($_GET['id_quoctich']) ? $_GET['id_quoctich'] : '';
	$id_nghenghiep = isset($_GET['id_nghenghiep']) ? $_GET['id_nghenghiep'] : '';
	$id_donvi_1 = isset($_GET['id_donvi_1']) ? $_GET['id_donvi_1'] : '';
	$id_chucvu = isset($_GET['id_chucvu']) ? $_GET['id_chucvu'] : '';
	$id_dantoc = isset($_GET['id_dantoc']) ? $_GET['id_dantoc'] : '';

	$check_gioitinh = isset($_GET['check_gioitinh']) ? $_GET['check_gioitinh'] : '';
	$check_quoctich = isset($_GET['check_quoctich']) ? $_GET['check_quoctich'] : '';
	$check_donvicongtac = isset($_GET['check_donvicongtac']) ? $_GET['check_donvicongtac'] : '';
	$check_chucvu = isset($_GET['check_chucvu']) ? $_GET['check_chucvu'] : '';
	$check_nghenghiep = isset($_GET['check_nghenghiep']) ? $_GET['check_nghenghiep'] : '';
	$check_dantoc = isset($_GET['check_dantoc']) ? $_GET['check_dantoc'] : '';

	if($loaicongchuc) array_push($query, array('loaicongchuc' => $loaicongchuc));
	if($dangvien) array_push($query, array('dangvien' => '1'));
	if($tinhuyvien) array_push($query, array('tinhuyvien' => '1'));
	if($cmnd) array_push($query, array('cmnd' => $cmnd));
	if($passport) array_push($query, array('passport' => $passport));
	if($hoten) array_push($query, array('hoten' => new MongoRegex('/'.$hoten.'/i')));
	if($ngaysinh) array_push($query, array('ngaysinh' => new MongoDate(convert_date_yyyy_mm_dd($ngaysinh))));
	if($gioitinh) array_push($query, array('gioitinh' => $gioitinh));
	if($id_quoctich) array_push($query, array('id_quoctich' => $id_quoctich));
	if($id_donvi_1) array_push($query, array('donvi.id_donvi.0' => $id_donvi_1));
	if($id_chucvu) array_push($query, array('donvi.id_chucvu' => new MongoId($id_chucvu)));
	if($id_nghenghiep) array_push($query, array('id_nghenghiep' => new MongoId($id_nghenghiep)));
	if($id_dantoc) array_push($query, array('id_dantoc' => new MongoId($id_dantoc)));
	
	if(count($query) > 0 ) $condition = array('$and' => $query);
	else $condition = array();
	$canbo_list = $canbo->get_list_condition($condition);
	if($loaitimkiem == 'doanra'){
		$title = 'Tìm kiếm Cá nhân theo Đoàn ra';
	} else if($loaitimkiem == 'doanvao'){
		$title = 'Tìm kiếm Cá nhân theo Đoàn vào';
	} else if($loaitimkiem == 'abtc'){
		$title = 'Tìm kiếm Cá nhân theo ABTC';
	} else {
		$title = 'Tìm kiếm Cá nhân';
	}
}
?>
<script type="text/javascript" src="js/select2.min.js"></script>
<script type="text/javascript" src="js/jquery.inputmask.js"></script>
<script type="text/javascript" src="js/timkiemnangcao.js"></script>
<script type="text/javascript">
	$(document).ready(function(){
		$(".select2").select2();$(".ngaythangnam").inputmask();
		$(".loaitimkiem").click(function(){
	    	if($(this).val() == 'doanra') $("#title_timkiem").html("<h2>Tìm kiếm Cá nhân theo Đoàn ra</h2>");
	    	else if($(this).val() == 'doanvao') $("#title_timkiem").html("<h2>Tìm kiếm Cá nhân theo Đoàn vào</h2>");
	    	else if($(this).val() == 'abtc') $("#title_timkiem").html("<h2>Tìm kiếm Cá nhân theo ABTC</h2>");
	    	else $("#title_timkiem").html("<h2>Tìm kiếm Cá nhân</h2>");
		});
		radioButton('loaicongchuc');
	});
</script>
<h1><a href="index.php" class="nav-button transform"><span></span></a>&nbsp;Tìm kiếm nâng cao</h1>
<form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="GET" id="timkiemnangcao">
<label class="input-control radio">
    <input type="radio" name="loaitimkiem" class="loaitimkiem" value="doanra" <?php echo $loaitimkiem=='doanra' ? 'checked' : ''; ?> />
    <span class="check"></span>
    <span class="caption">Đoàn ra</span>
</label>&nbsp;&nbsp;&nbsp;
<label class="input-control radio">
    <input type="radio" name="loaitimkiem" class="loaitimkiem" value="doanvao" <?php echo $loaitimkiem=='doanvao' ? 'checked' : ''; ?> />
    <span class="check"></span>
    <span class="caption">Đoàn vào</span>
</label>&nbsp;&nbsp;&nbsp;
<label class="input-control radio">
    <input type="radio" name="loaitimkiem" class="loaitimkiem" value="abtc" <?php echo $loaitimkiem=='abtc' ? 'checked' : ''; ?> />
    <span class="check"></span>
    <span class="caption">ABTC</span>
</label>
&nbsp;&nbsp;&nbsp;
<label class="input-control radio">
    <input type="radio" name="loaitimkiem" class="loaitimkiem" value="all" <?php echo $loaitimkiem=='all' ? 'checked' : ''; ?> />
    <span class="check"></span>
    <span class="caption">Tất cả</span>
</label>
<hr />
<div class="grid">
	<div class="row cells12">
		<div class="cell colspan12 align-center" id="title_timkiem">
			<h2><?php echo $title; ?></h2>
		</div>
	</div>
	<div class="row cells12">
		<div class="cell colspan2 padding-top-10 align-right">
			Loại công chức
		</div>
		<div class="cell colspan4">
			<label class="input-control radio">
                <input type="radio" name="loaicongchuc" value="CC" <?php echo $loaicongchuc=='CC' ? 'checked' : ''; ?> />
                <span class="check"></span>
                <span class="caption">Công chức</span>
            </label>
            &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
            <label class="input-control radio">
                <input type="radio" name="loaicongchuc" value="VC" <?php echo $loaicongchuc=='VC' ? 'checked' : ''; ?>/>
                <span class="check"></span>
                <span class="caption">Viên chức</span>
            </label>
		</div>
		<div class="cell colspan2 padding-top-10 align-right">Đảng viên</div>
		<div class="cell padding-top-10">
			<label class="switch">
			    <input type="checkbox" name="dangvien" id="dangvien" value="1" <?php echo $dangvien==1 ? 'checked' : ''; ?> />
			    <span class="check"></span>
			</label>
		</div>
		<div class="cell colspan2 padding-top-10 align-right">Tỉnh ủy viên</div>
		<div class="cell padding-top-10">
			<label class="switch">
			    <input type="checkbox" name="tinhuyvien" id="tinhuyvien" value="1" <?php echo $tinhuyvien==1 ? 'checked' : ''; ?> />
			    <span class="check"></span>
			</label>
		</div>
	</div>
	<div class="row cells12">
		<div class="cell colspan2 padding-top-10 align-right">CMND</div>
		<div class="cell colspan4 input-control text">
			<input type="text" name="cmnd" id="cmnd" value="<?php echo isset($cmnd) ? $cmnd : '';?>" placeholder="" />
		</div>
		<div class="cell colspan2 padding-top-10 align-right">Số hộ chiếu</div>
		<div class="cell colspan4 input-control text">
			<input type="text" name="passport" value="<?php echo isset($passport) ? $passport : '';?>" />
		</div>
	</div>
	<div class="row cells12">
		<div class="cell colspan2 padding-top-10 align-right">Họ tên</div>
		<div class="cell colspan4 input-control text">
			<input type="text" name="hoten" id="hoten" value="<?php echo isset($hoten) ? $hoten : '';?>" data-validate-func="required" />
			<span class="input-state-error mif-warning"></span><span class="input-state-success mif-checkmark"></span>
		</div>
		<div class="cell colspan2 padding-top-10 align-right">Ngày sinh</div>
		<div class="cell colspan4 input-control text" data-role="datepicker" data-format="dd/mm/yyyy">
			<input type="text" name="ngaysinh" id="ngaysinh" value="<?php echo isset($ngaysinh) ? $ngaysinh : '';?>" data-inputmask="'alias': 'date'" class="ngaythangnam"/>
			<button class="button"><span class="mif-calendar"></span></button>
		</div>
	</div>
	<div class="row cells12">
		<div class="cell colspan2 align-right">
			Giới tính
			<label class="input-control checkbox small-check">
    			<input type="checkbox" name="check_gioitinh" id="check_gioitinh" value="1" <?php echo $check_gioitinh==1 ? ' checked' : ''; ?>>
    		<span class="check"></span>
		</div>
		<div class="cell colspan4 input-control select">
			<select name="gioitinh" id="gioitinh" class="select2">
				<option value="">&nbsp;</option>
				<option value="Nam" <?php echo $gioitinh=='Nam' ? ' selected' : ''; ?>>Nam</option>
				<option value="Nữ" <?php echo $gioitinh=='Nữ' ? ' selected' : ''; ?>>Nữ</option>
			</select>
			</label>
		</div>
		<div class="cell colspan2 align-right">
			Quốc tịch
			<label class="input-control checkbox small-check">
    			<input type="checkbox" name="check_quoctich" id="check_quoctich" value="1" <?php echo $check_quoctich==1 ? ' checked' : ''; ?>>
    		<span class="check"></span>
		</div>
		<div class="cell colspan4 input-control select">
			<select name="id_quoctich" id="id_quoctich" class="select2" tabindex="11">
			<<option value="">&nbsp;</option>}
			option
				<?php
					$quocgia = new QuocGia();$quocgia_list = $quocgia->get_all_list();
					if($quocgia_list){
						foreach ($quocgia_list as $qg) {
							echo '<option value="'.$qg['_id'].'"'.($id_quoctich==$qg['_id'] ? ' selected' : '').'>'.$qg['ten'].'</option>';
						}
					}
				?>
			</select>
		</div>
	</div>
	<div class="row cells12">
		<div class="cell colspan2 align-right">
			Đơn vị công tác
			<label class="input-control checkbox small-check">
    			<input type="checkbox" name="check_donvicongtac" id="check_donvicongtac" value="1" <?php echo $check_donvicongtac==1 ? ' checked' : ''; ?>>
    		<span class="check"></span>
		</div>
		<div class="cell colspan4 input-control select">
			<select name="id_donvi_1" id="id_donvi_1" class="select2" tabindex="12">
				<option value=""></option>
				<?php
					$donvi = new DonVi(); $donvi_list = $donvi->get_all_list();
					if($donvi_list){
						foreach ($donvi_list as $dv) {
							echo '<option value="'.$dv['_id'].'"'.($id_donvi_1==$dv['_id'] ? ' selected' : '').'>'.$dv['ten'].'</option>';
						}
					}
				?>
			</select>
		</div>
		<div class="cell colspan2 align-right">
			Chức vụ
			<label class="input-control checkbox small-check">
    			<input type="checkbox" name="check_chucvu" id="check_chucvu" value="1" <?php echo $check_chucvu==1 ? ' checked' : ''; ?>>
    		<span class="check"></span>
		</div>
		<div class="cell colspan4 input-control select">
			<select name="id_chucvu" id="id_chucvu" class="select2">
				<option value=""></option>
				<?php
				$chucvu = new ChucVu();$chucvu_list = $chucvu->get_all_list();
				if($chucvu_list){
					foreach ($chucvu_list as $cv) {
						echo '<option value="'.$cv['_id'].'"'.($id_chucvu==$cv['_id'] ? ' selected' : '').'>'.$cv['ten'].'</option>';
					}
				}
				?>
			</select>			
		</div>
	</div>
	<div id="id_donvi2" class="row cells12">
		<div class="cell colspan2 align-right">
			Nghề nghiệp
			<label class="input-control checkbox small-check">
    			<input type="checkbox" name="check_nghenghiep" id="check_nghenghiep" value="1" <?php echo $check_nghenghiep==1 ? ' checked' : ''; ?>>
    		<span class="check"></span>
		</div>
		<div class="cell colspan4 input-control select">
			<select name="id_nghenghiep" id="id_nghenghiep" class="select2">
				<option value=""></option>
				<?php
					$nghenghiep_list = $nghenghiep->get_all_list();
					if($nghenghiep_list){
						foreach ($nghenghiep_list as $nn) {
							echo '<option value="'.$nn['_id'].'"'.($id_nghenghiep==$nn['_id'] ? ' selected' : '').'>'.$nn['ten'].'</option>';
						}
					}
				?>
			</select>			
		</div>
		<div class="cell colspan2 align-right">
			Dân tộc
			<label class="input-control checkbox small-check">
    			<input type="checkbox" name="check_dantoc" id="check_dantoc" value="1" <?php echo $check_dantoc==1 ? ' checked' : ''; ?>>
    		<span class="check"></span>
		</div>
		<div class="cell colspan4 input-control select">
			<select name="id_dantoc" id="id_dantoc" class="select2">
				<option value="">&nbsp;</option>
				<?php
				$dantoc_list = $dantoc->get_all_list();
				if($dantoc_list){
					foreach ($dantoc_list as $dt) {
						echo '<option value="'.$dt['_id'].'"'.($id_dantoc==$dt['_id'] ? ' selected' : '').'>'.$dt['ten'].'</option>';
					}
				}
				?>
			</select>
		</div>
	</div>
	<div class="row cells12">
		<div class="cell colspan12 align-left">
			<button class="button primary" name="submit" value="OK" id="submit"><span class="mif-search"></span> Tìm kiếm</button>
			<?php if(isset($_GET['submit'])): 
				
			?>
				<a href="export_timkiemnangcao.php?<?php echo $_SERVER['QUERY_STRING']; ?>" class="button success"><span class="mif-file-excel"></span> Excel</a>
			<?php endif; ?>
			<hr />
		</div>
	</div>
</div>
</form>
<?php if(isset($canbo_list) && $canbo_list->count() > 0 ): ?>
	<table class="table">
		<thead>
			<tr>
				<th>STT</th>
				<th>ID</th>
				<th>Họ tên</th>
			<?php
				if($cmnd) echo '<th>CMND</th>';
				if($passport) echo '<th>Passport</th>';
				if($ngaysinh) echo '<th>Ngày sinh</th>';
				if($gioitinh || $check_gioitinh) echo '<th>Giới tính</th>';
				if($id_quoctich || $check_quoctich) echo '<th>Quốc tịch</th>';
				if($id_donvi_1 || $check_donvicongtac) echo '<th>Đơn vị</th>';
				if($id_chucvu || $check_chucvu) echo '<th>Chức vụ</th>';
				if($id_nghenghiep || $check_nghenghiep) echo '<th>Nghề nghiệp</th>';
				if($id_dantoc || $check_dantoc) echo '<th>Dân tộc</th>';
				if($loaicongchuc) echo '<th>Loại công chức</th>';
				if($dangvien) echo '<th>Đảng viên</th>';
				if($tinhuyvien) echo '<th>Tỉnh Ủy viên</th>';
			?>
			</tr>
		</thead>
		<tbody>
		<?php
		$total = 0; $i = 1;
		foreach ($canbo_list as $cb) {
			if($loaitimkiem == 'all'){
				$total++;
					if($cb['donvi']){
						$arr_donvi = sort_array_and_key($cb['donvi'], "ngaynhap", SORT_DESC);
						if(isset($arr_donvi[0]['id_donvi'][0]) && $arr_donvi[0]['id_donvi'][0]){
							$tendonvi = $donvi->tendonvi($cb['donvi'][0]['id_donvi']);
						} else { $tendonvi = ''; $full_donvi='';}
						if(isset($arr_donvi[0]['id_chucvu']) && $arr_donvi[0]['id_chucvu']){
							$chucvu->id = $arr_donvi[0]['id_chucvu'];$cv = $chucvu->get_one();
							$tenchucvu = $cv['ten'];
						} else{
							$tenchucvu = '';
						}
					} else {
						$tendonvi='';$tenchucvu='';
					}
					if(isset($cb['id_nghenghiep']) && $cb['id_nghenghiep']){
						$nghenghiep->id = $cb['id_nghenghiep']; $nn = $nghenghiep->get_one();
						$tennghenghiep = $nn['ten'];
					} else {
						$tennghenghiep = '';
					}
					if(isset($cb['id_dantoc']) && $cb['id_dantoc']){
						$dantoc->id = $cb['id_dantoc']; $dt = $dantoc->get_one();
						$tendantoc = $dt['ten'];
					} else {
						$tendantoc = '';
					}
					if($cb['id_quoctich']){
						$quocgia->id = $cb['id_quoctich']; $qt = $quocgia->get_one();
						$tenquoctich = $qt['ten'];
					} else { $tenquoctich = ''; }
					echo '<tr>
						<td>'.$i.'</td>
						<td>'.$cb['code'].'</td>
						<td><a href="chitietcanbo.php?id='.$cb['_id'].'" target="_blank">'.$cb['hoten'].'</td>';
						if($cmnd) echo '<td>'.$cb['cmnd'].'</td>';
						if($passport) echo '<td>'.$cb['passport'][0].'</td>';
						if($ngaysinh) echo '<td>'.$ngaysinh.'</td>';
						if($gioitinh || $check_gioitinh) echo '<td>'.$cb['gioitinh'].'</td>';
						if($id_quoctich || $check_quoctich) echo '<td>'.$tenquoctich.'</td>';
						if($id_donvi_1 || $check_donvicongtac) echo '<td>'.$tendonvi.'</td>';
						if($id_chucvu || $check_chucvu) echo '<td>'.$tenchucvu.'</td>';
						if($id_nghenghiep || $check_nghenghiep) echo '<td>'.$tennghenghiep.'</td>';
						if($id_dantoc || $check_dantoc) echo '<td>'.$tendantoc.'</td>';
						if($loaicongchuc) echo '<td>'.($cb['loaicongchuc'] =='VC' ? 'Viên chức': 'Công chức').'</td>';
						if($dangvien) echo '<td>Đảng viên</td>';
						if($tinhuyvien) echo '<td>Tỉnh Ủy viên</td>';
					echo '</tr>';$i++;
			} else {
				if($$loaitimkiem->check_timkiem($cb['_id'])){
					$total++;
					if($cb['donvi']){
						$arr_donvi = sort_array_and_key($cb['donvi'], "ngaynhap", SORT_DESC);
						if(isset($arr_donvi[0]['id_donvi'][0]) && $arr_donvi[0]['id_donvi'][0]){
							$tendonvi = $donvi->tendonvi($arr_donvi[0]['id_donvi']);
						} else { $tendonvi = ''; $full_donvi='';}
						if(isset($arr_donvi[0]['id_chucvu']) && $arr_donvi[0]['id_chucvu']){
							$chucvu->id = $arr_donvi[0]['id_chucvu'];$cv = $chucvu->get_one();
							$tenchucvu = $cv['ten'];
						} else{
							$tenchucvu = '';
						}
					} else {
						$tendonvi='';$tenchucvu='';
					}
					if(isset($cb['id_nghenghiep']) && $cb['id_nghenghiep']){
						$nghenghiep->id = $cb['id_nghenghiep']; $nn = $nghenghiep->get_one();
						$tennghenghiep = $nn['ten'];
					} else {
						$tennghenghiep = '';
					}
					if(isset($cb['id_dantoc']) && $cb['id_dantoc']){
						$dantoc->id = $cb['id_dantoc']; $dt = $dantoc->get_one();
						$tendantoc = $dt['ten'];
					} else {
						$tendantoc = '';
					}
					if($cb['id_quoctich']){
						$quocgia->id = $cb['id_quoctich']; $qt = $quocgia->get_one();
						$tenquoctich = $qt['ten'];
					} else { $tenquoctich = ''; }
					echo '<tr>
						<td>'.$i.'</td>
						<td>'.$cb['code'].'</td>
						<td><a href="chitietcanbo.php?id='.$cb['_id'].'" target="_blank">'.$cb['hoten'].'</td>';
						if($cmnd) echo '<td>'.$cb['cmnd'].'</td>';
						if($passport) echo '<td>'.$cb['passport'][0].'</td>';
						if($ngaysinh) echo '<td>'.$ngaysinh.'</td>';
						if($gioitinh || $check_gioitinh) echo '<td>'.$cb['gioitinh'].'</td>';
						if($id_quoctich || $check_quoctich) echo '<td>'.$tenquoctich.'</td>';
						if($id_donvi_1 || $check_donvicongtac) echo '<td>'.$tendonvi.'</td>';
						if($id_chucvu || $check_chucvu) echo '<td>'.$tenchucvu.'</td>';
						if($id_nghenghiep || $check_nghenghiep) echo '<td>'.$tennghenghiep.'</td>';
						if($id_dantoc || $check_dantoc) echo '<td>'.$tendantoc.'</td>';
						if($loaicongchuc) echo '<td>'.$cb['loaicongchuc'].'</td>';
						if($dangvien) echo '<td>Đảng viên</td>';
						if($tinhuyvien) echo '<td>Tỉnh Ủy viên</td>';
					echo '</tr>';$i++;
				}
			}
		}
		?>
		</tbody>
	</table>
	<h3><span class="mif-search"></span> Kết quả tìm kiếm: có <?php echo $total; ?> Cá nhân.</h3>
<?php elseif(isset($_GET['submit'])): ?>
	<h3><span class="mif-search"></span> Không tìm thấy Cá nhân muốn tìm.</h3>
<?php endif; ?>

<?php require_once('footer.php'); ?>
