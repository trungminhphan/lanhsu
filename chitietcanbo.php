<?php
require_once('header.php');
if(!$users->is_admin() && !$users->is_student()){
	echo '<h2>Bạn không có quyền...! <a href="index.php">Trở về</a></h2>';
	require_once('footer.php');
	exit();
}
$canbo = new CanBo();$quocgia = new QuocGia();$doanra=new DoanRa();$doanvao = new DoanVao();$ham=new Ham();
$chucvu = new ChucVu();$nghenghiep=new NgheNghiep(); $dantoc=new DanToc();
$id = isset($_GET['id']) ? $_GET['id'] : '';
$canbo->id = $id; $cb = $canbo->get_one();
if(isset($cb['id_quoctich']) && $cb['id_quoctich']){
	$quocgia->id = $cb['id_quoctich'];$qg = $quocgia->get_one();
	$tenquoctich = $qg['ten'];
} else { $tenquoctich = '';}
?>
<script type="text/javascript" src="js/select2.min.js"></script>
<script type="text/javascript" src="js/jquery.inputmask.js"></script>
<script type="text/javascript" src="js/canbo.js"></script>
<script type="text/javascript">
	$(document).ready(function(){
		id_donvi_action_chitiet();chucvu();
		$("#id_donvi_1").select2({placeholder: "Chọn đơn vị"});
		$("#id_donvi_2").select2({placeholder: "Chọn đơn vị"});
		$("#id_donvi_3").select2({placeholder: "Chọn đơn vị"});
		$("#id_donvi_4").select2({placeholder: "Chọn đơn vị"});
		$("#id_ham").select2({placeholder: "Chọn Hàm"});
		$("#id_chucvu").select2({placeholder: "Chọn chức vụ"});
		$(".ngaythangnam").inputmask();
	});
</script>
<h1><a href="canbo.php" class="nav-button transform"><span></span></a>&nbsp;Thông tin chi tiết Cá Nhân - ID: <b><?php echo $cb['code']; ?></b></h1>
<div class="grid example">
	<div class="row cells12">
		<div class="cell colspan3">
		<?php 
			if(file_exists($target_images . $cb['hinhanh']) && $cb['hinhanh']){
				$hinh = $target_images . $cb['hinhanh'];
			} else {
				$hinh = 'images/no_pic.png';
			}
		?>
		<img src="<?php echo $hinh; ?>" style="max-height: 200px; max-width: 150px;" />
		</div>
		<div class="cell colspan9">
			<div class="grid">
				<?php
				if(isset($cb['passport']) && $cb['passport']){
					foreach ($cb['passport'] as $p) {
						echo '<div class="row cells9">';
						if(is_array($p)){
							$ngayhethan = $p[1] ? date("d/m/Y", $p[1]->sec) : '';
							echo '<div class="cell colspan2">Passport</div>';
							echo '<div class="cell colspan3"><b>'.$p[0].'</b></div>';
							echo '<div class="cell colspan2">Ngày hết hạn</div>';
							echo '<div class="cell colspan2"><b>'.$ngayhethan.'</b></div>';
						} else {
							echo '<div class="cell colspan2">Passport</div>';
							echo '<div class="cell colspan3"><b>'.($p ? $p : '').'</b></div>';
						}
						echo '</div>';
					}
				}
				?>
				<div class="row cells9">
					<div class="cell colspan2">CMND</div>
					<div class="cell colspan3"><b><?php echo $cb['cmnd']; ?></b></div>
				</div>
				<div class="row cells9">
					<div class="cell colspan2">Họ tên</div>
					<div class="cell colspan3"><b><?php echo $cb['hoten']; ?></b></div>
					<div class="cell colspan2">Ngày sinh</div>
					<div class="cell colspan2"><b><?php echo $cb['ngaysinh'] ? date('d/m/Y', $cb['ngaysinh']->sec) : ''; ?></b></div>
				</div>
				<div class="row cells9">
					<div class="cell colspan2">Giới tính</div>
					<div class="cell colspan3"><b><?php echo isset($cb['gioitinh']) ? $cb['gioitinh']: ''; ?></b></div>
					<div class="cell colspan2">Quốc tịch</div>
					<div class="cell colspan2"><b><?php echo $tenquoctich; ?></b></div>
				</div>
				<div class="row cells9">
					<div class="cell colspan2">Địa chỉ</div>
					<div class="cell colspan3"><b><?php echo isset($cb['diachi']) ? $cb['diachi']: ''; ?></b></div>
					<div class="cell colspan2">Đảng viên</div>
					<div class="cell colspan2">
						<?php
						if($cb['dangvien'] == 1){
							echo '<span class="mif-checkmark fg-blue"></span>';
						} else {
							echo '';
						}
						?>
					</div>
				</div>
				<div class="row cells9">
					<div class="cell colspan2">Loại công chức</div>
					<div class="cell colspan3"><b>
						<?php
						if(isset($cb['loaicongchuc']) && $cb['loaicongchuc']=='CC') echo 'Công chức';
						else if(isset($cb['loaicongchuc']) && $cb['loaicongchuc']=='VC') echo 'Viên chức';
						else echo '';
						?></b>
					</div>
					<div class="cell colspan2">Tỉnh ủy viên</div>
					<div class="cell colspan2">
						<?php
						if(isset($cb['tinhuyvien']) && $cb['tinhuyvien'] == 1){
							echo '<span class="mif-checkmark fg-blue"></span>';
						} else {
							echo '';
						}
						?>
					</div>
				</div>
				<div class="row cells9">
					<div class="cell colspan2">Điện thoại</div>
					<div class="cell colspan3"><b><?php echo isset($cb['dienthoai']) ? $cb['dienthoai'] : ''; ?></b></div>
					<div class="cell colspan2">Email</div>
					<div class="cell colspan2"><b><?php echo isset($cb['email']) ? $cb['email'] : ''; ?></b></div>
				</div>
				<?php
					if(isset($cb['id_nghenghiep']) && $cb['id_nghenghiep']){
						$nghenghiep->id = $cb['id_nghenghiep']; $nn = $nghenghiep->get_one();
						$tennghenghiep = $nn['ten'];
					} else { $tennghenghiep = ''; }
					if(isset($cb['id_dantoc']) && $cb['id_dantoc']){
						$dantoc->id = $cb['id_dantoc']; $dt = $dantoc->get_one();
						$tendantoc = $dt['ten'];
					} else { $tendantoc = ''; }
				?>
				<div class="row cells9">
					<div class="cell colspan2">Nghề nghiệp</div>
					<div class="cell colspan3"><b><?php echo $tennghenghiep; ?></b></div>
					<div class="cell colspan2">Dân tộc</div>
					<div class="cell colspan2"><b><?php echo $tendantoc; ?></b></div>
				</div>
				<div class="row cells9">
					<div class="cell colspan2">Ghi chú</div>
					<div class="cell colspan3"><?php echo isset($cb['ghichu']) ? $cb['ghichu'] : ''; ?></div>
					<div class="cell colspan2">Người nhập</div>
					<div class="cell colspan2">
						<?php
							if(isset($cb['id_user']) && $cb['id_user']){
								$users->id = $cb['id_user'];
								$u = $users->get_one(); echo $u['username'];
							}
						?>
						<br />
						<?php if($users->is_admin()): ?>
							<a href="logs_nhaplieu.php?id=<?php echo $id; ?>&collection=canbo"> [Xem chi tiết] </a>
						<?php endif; ?>
					</div>
				</div>
			</div>
		</div>
	</div>
	<div class="row cells12">
		<div class="cell colspan12">
			<table class="table border" id="chucvu_list">
			<thead>
				<tr>
					<th><a href="#" id="themchucvu"><span class="mif-plus"></span></a>&nbsp;&nbsp;Đơn vị công tác</th>
					<th>Chức vụ</th>
					<th>Ngày thêm</th>
					<th>Xoá</th>
					<th>Sửa</th>
				</tr>
			</thead>
			<tbody>
				<?php
				if(isset($cb['donvi']) && $cb['donvi']){
					$donvi = new DonVi();$chucvu = new ChucVu();
					$arr_donvi = sort_array($cb['donvi'], "ngaynhap", SORT_DESC);
					foreach ($arr_donvi as $k => $v) {
						$donvi->id = $v['id_donvi'];$dv=$donvi->tendonvi($v['id_donvi']);
						if(isset($v['id_chucvu']) && $v['id_chucvu']){
							$chucvu->id = $v['id_chucvu']; $cv = $chucvu->get_one();
							$tenchucvu = $cv['ten'];
						} else { $tenchucvu = '';}
						if(isset($v['id_ham']) && $v['id_ham']) {
							$ham->id = $v['id_ham']; $h = $ham->get_one(); $tenham = $h['ten'];
						} else { $tenham = ''; }
						if($v['ngaynhap']) $ngaynhap = date("d/m/Y", $v['ngaynhap']->sec);
						else $ngaynhap = '';
						echo '<tr class="items">';
						echo '<td>'.$dv.'</td>';
						echo '<td>'.($tenham ? $tenham . ',' : '').' '.$tenchucvu.'</td>';
						echo '<td>'.date("d/m/Y",$v['ngaynhap']->sec).'</td>';
						if($users->is_student()){
							echo '<td></td><td></td>';
						} else {
							if($doanra->check_canbo_donvi($cb['_id'], $v['id_donvi'], $v['id_chucvu']) || $doanvao->check_canbo_donvi($cb['_id'], $v['id_donvi'], $v['id_chucvu'])){
								echo '<td><span class="mif-bin fg-gray"></span></td>';echo '<td><span class="mif-pencil fg-gray"></td>';
							} else {
								echo '<td><a href="get.xoachucvu.php?id_canbo='.$cb['_id'].'&id_donvi='.$v['id_donvi'][0].','.$v['id_donvi'][1].','.$v['id_donvi'][2].','.$v['id_donvi'][3].'&id_chucvu='.$v['id_chucvu'].'&id='.$v['id'].'&id_ham='.(isset($v['id_ham']) ? $v['id_ham'] : '').'" onclick="return false;" class="xoachucvu"><span class="mif-bin"></span></a></td>';
								echo '<td><a href="get.suachucvu.php?id='.$v['id'].'.id_canbo='.$cb['_id'].'&id_donvi='.$v['id_donvi'][0].','.$v['id_donvi'][1].','.$v['id_donvi'][2].','.$v['id_donvi'][3].'&id_chucvu='.$v['id_chucvu'].'&id='.$v['id'].'&ngaynhap='.$ngaynhap.'&id_ham='.(isset($v['id_ham']) ? $v['id_ham'] : '').'" onclick="return false;" class="suachucvu"><span class="mif-pencil"></span></a></td>';	
							}
						}
						echo '</tr>';
					}
				}
				?>
			</tbody>
			</table>
		</div>
	</div>
	<div class="row cells12">
		<div class="cell colspan12 align-center ">
			<?php if(!$users->is_student()): ?>
			<a href="themcanbo.php?id=<?php echo $cb['_id']; ?>&act=edit" class="button primary"><span class="mif-pencil"></span> Sửa</a>
			<?php endif; ?>
			<a href="themcanbo.php" class="button primary"><span class="mif-user-plus"></span> Thêm mới</a>
			<a href="canbo.php" class="button"><span class="mif-keyboard-return"></span> Trở về</a>
		</div>
	</div>
</div>

<div data-role="dialog" id="dialog_themchucvu" class="padding20" data-close-button="true" data-overlay="true" data-overlay-color="op-dark" data-width="920">
	<h2><span class="mif-organization mif-2x"></span> Thêm chức vụ</h2>
	<form method="POST" action="#" id="themchucvuform">
		<input type="hidden" name="id" id="id" value="" />
		<input type="hidden" name="id_canbo" id="id_canbo" value="<?php echo trim($id); ?> " />
		<div class="grid padding-top-10">
			<div class="row cells12">
				<?php
					$donvi = new DonVi(); $donvi_list = $donvi->get_all_list();
				?>
				<div class="cell colspan3 input-control" data-role="select">
					<label>Đơn vị cấp 1</label>
					<select name="id_donvi_1" id="id_donvi_1" class="select2">
					<option value="">Chọn đơn vị</option>
						<?php
							if($donvi_list){
								foreach ($donvi_list as $dv) {
									echo '<option value="'.$dv['_id'].'">'.$dv['ten'].'</option>';
								}
							}
						?>
					</select>
				</div>
				<div class="cell colspan3 input-control" data-role="select" data-allow-clear="true">
					<label>Đơn vị cấp 2</label>
					<select name="id_donvi_2" id="id_donvi_2" class="select2">
						<option value="">Chọn đơn vị</option>
					</select>
				</div>
				<div class="cell colspan3 input-control" data-role="select" data-allow-clear="true">
					<label>Đơn vị cấp 3</label>
					<select name="id_donvi_3" id="id_donvi_3" class="select2">
						<option value="">Chọn đơn vị</option>
					</select>
				</div>
				<div class="cell colspan3 input-control" data-role="select" data-allow-clear="true">
					<label>Đơn vị cấp 4</label>
					<select name="id_donvi_4" id="id_donvi_4" class="select2">
						<option value="">Chọn đơn vị</option>
					</select>
				</div>
			</div>
			<div class="row cells12 padding-top-10">
				<div class="cell colspan4 input-control select">
					<label>Hàm</label>
					<select name="id_ham" id="id_ham" class="select2">
					<option value="">Chọn Hàm</option>
						<?php
						$ham_list = $ham->get_all_list();
						if($ham_list){
							foreach ($ham_list as $h) {
								echo '<option value="'.$h['_id'].'">'.$h['ten'].'</option>';
							}
						}
						?>
					</select>			
				</div>
				<div class="cell colspan4 input-control select">
					<label>Chức vụ</label>
					<select name="id_chucvu" id="id_chucvu" class="select2">
					<option value="">Chọn chức vụ</option>
						<?php
						$chucvu_list = $chucvu->get_all_list();
						if($chucvu_list){
							foreach ($chucvu_list as $cv) {
								echo '<option value="'.$cv['_id'].'">'.$cv['ten'].'</option>';
							}
						}
						?>
					</select>			
				</div>
				<div class="cell colspan3 input-control text padding-left-10" data-role="datepicker" data-format="dd/mm/yyyy">
					<label class="padding-left-10">Ngày nhập</label>
					<input type="text" name="ngaynhap" id="ngaynhap" value="<?php echo isset($ngaynhap) ? $ngaynhap : '';?>" placeholder="Ngày thêm" data-inputmask="'alias': 'date'" class="ngaythangnam"/>
					<button class="button"><span class="mif-calendar"></span></button>
				</div>
			</div>
		</div>
		<div class="row cells12">
			<div class="cell colspan12 align-center">
				<a href="#" id="themchucvu_ok" value="OK" class="button primary"><span class="mif-checkmark"></span> Cập nhật</a>
				<a href="#" id="themchucvu_no" class="button"><span class="mif-keyboard-return"></span> Huỷ</a>
			</div>
		</div>
	</form>
</div>
<!------------------------------ dialog confirm delete ----------------------- -->
<div data-role="dialog" id="confirm_delete" class="padding20 block-shadow-danger" data-close-button="true" data-overlay="true">
    <h2><span class="mif-bin fg-black"></span> Chắc chắn xoá?</h2>
    <p>Nếu chắc xoá sẽ mất thông tin đơn vị công tác và chức vụ.</p>
    <div class="align-center">
        <a href="#" onclick="return false;" class="button primary fg-white" id="delete_ok"><span class="mif-bin"></span> Đồng ý</a>
        <a href="#" onclick="return false;" class="button" id="delete_no"><span class="mif-not"></span> Huỷ không xoá</a>
    </div>
</div>
<?php require_once('footer.php'); ?>