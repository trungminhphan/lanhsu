<?php require_once('header.php');
$donvi = new DonVi();$canbo =  new CanBo();$doanra = new DoanRa();$doanvao = new DoanVao();
$abtc = new ABTC();$phanloaidonvi = new PhanLoaiDonVi(); $linhvuc = new LinhVuc();
$quocgia = new QuocGia();
$donvi_list = $donvi->get_all_list();
$quocgia_list = $quocgia->get_all_list();
$msg = '';$id_phanloaidonvi='';$id_linhvuc='';$public='';
$id = isset($_GET['id']) ? $_GET['id']: '';
$act = isset($_GET['act']) ? $_GET['act']: '';

if($id && $act=='del'){
	$donvi->id = $id; 
	if($canbo->check_dm_donvi($id) || $doanra->check_dm_donvi($id) || $doanvao->check_dm_donvi($id) || $abtc->check_dm_donvi()){
		transfers_to('donvi.php?update=no');
	} else {
		if($donvi->delete()){
			transfers_to('donvi.php');
		}
	}
}
if(isset($_POST['submit'])){
	$id = isset($_POST['id']) ? $_POST['id']: '';
	$ten = isset($_POST['ten']) ? $_POST['ten']: '';
	$tentienganh = isset($_POST['tentienganh']) ? $_POST['tentienganh']: '';
	$diachi = isset($_POST['diachi']) ? $_POST['diachi']: '';
	$dienthoai = isset($_POST['dienthoai']) ? $_POST['dienthoai']: '';
	$email = isset($_POST['email']) ? $_POST['email']: '';
	$id_phanloaidonvi = isset($_POST['id_phanloaidonvi']) ? $_POST['id_phanloaidonvi']: '';
	$id_linhvuc = isset($_POST['id_linhvuc']) ? $_POST['id_linhvuc']: '';
	$id_quocgia = isset($_POST['id_quocgia']) ? $_POST['id_quocgia']: '';
	$ghichu = isset($_POST['ghichu']) ? $_POST['ghichu']: '';
	$public = isset($_POST['public']) ? $_POST['public'] : 0;
	$donvi->id = $id;
	$donvi->ten = $ten;
	$donvi->tentienganh = $tentienganh;
	$donvi->id_linhvuc = $id_linhvuc;
	$donvi->id_quocgia = $id_quocgia;
	$donvi->id_phanloaidonvi = $id_phanloaidonvi;
	$donvi->diachi = $diachi;
	$donvi->dienthoai = $dienthoai;
	$donvi->email = $email;
	$donvi->ghichu = $ghichu;
	$donvi->public = intval($public);
	$donvi->id_user = $users->get_userid();
	if($id){
		if($donvi->edit()) $msg = 'Chỉnh sửa thành công';
		else $msg = 'Không thể chỉnh sửa.';
	} else {
		if($donvi->check_exists()){
			$msg = 'Tên đơn vị này đã tồn tại trong Cơ sở dữ liệu.';
		} else {
			if($donvi->insert()){
				$msg = 'Thêm thành công';
			}
		}
	}
}
if($id){
	$donvi->id = $id; $dv = $donvi->get_one();
	$ten = $dv['ten']; $diachi = $dv['diachi'];$dienthoai=$dv['dienthoai'];
	$email=$dv['email'];
	$id_phanloaidonvi = isset($dv['id_phanloaidonvi']) ? $dv['id_phanloaidonvi'] : '';
	$id_linhvuc = isset($dv['id_linhvuc']) ? $dv['id_linhvuc'] : '';
	$id_quocgia = isset($dv['id_quocgia']) ? $dv['id_quocgia'] : '';
	$ghichu = $dv['ghichu'];$public = isset($dv['public']) ? $dv['public'] : 0;
}
?>
<script type="text/javascript" src="js/html5.messages.js"></script>
<script type="text/javascript" src="js/jquery.setcase.js"></script>
<script type="text/javascript" src="js/select2.min.js"></script>
<script type="text/javascript">
	$(document).ready(function(){
		$(".select2").select2();
		<?php if(isset($msg) && $msg) : ?>
            $.Notify({type: 'alert', caption: 'Thông báo', content:  <?php echo "'". $msg . "'"; ?>});
        <?php endif; ?>
	});
</script>
<h1><a href="donvi.php" class="nav-button transform"><span></span></a>&nbsp;Thêm Đơn vị</h1>
<form method="POST" action="<?php echo $_SERVER['REQUEST_URI']; ?>" id="themdonviform" data-role="validator" data-show-required-state="false">
<div class="grid">
	<div class="row cells12">
		<div class="cell colspan2 padding-top-10">Tên Đơn vị/ Tổ chức</div>
		<div class="cell colspan10 input-control text">
			<input type="hidden" name="id" id="id" value="<?php echo isset($id) ? $id : ''; ?>" />
			<input type="text" name="ten" id="ten" value="<?php echo isset($ten) ? $ten : ''; ?>" placeholder="Tên đơn vị/Tổ chức" data-validate-func="required"/>
			<span class="input-state-error mif-warning"></span><span class="input-state-success mif-checkmark"></span>
		</div>
	</div>
	<div class="row cells12">
		<div class="cell colspan2 padding-top-10">Tên tiếng Anh (hoặc bản xứ)</div>
		<div class="cell colspan10 input-control text">
			<input type="text" name="tentienganh" id="ten" value="<?php echo isset($tentienganh) ? $tentienganh : ''; ?>" placeholder="Tên tiếng Anh (hoặc bản xứ)"/>
		</div>
	</div>
	<div class="row cells12">
		<div class="cell colspan2 padding-top-10">Phân loại đơn vị</div>
		<div class="cell colspan4 input-control select">
			<select name="id_phanloaidonvi" id="id_phanloaidonvi" class="select2">
				<option value="">&nbsp;</option>
				<?php
				$phanloaidonvi_list = $phanloaidonvi->get_all_list();
				if($phanloaidonvi_list){
					foreach ($phanloaidonvi_list as $p) {
						echo '<option value="'.$p['_id'].'"'.($p['_id']==$id_phanloaidonvi ? ' selected' : '').'>'.$p['ten'].'</option>';
					}
				}
				?>
			</select>
		</div>
		<div class="cell colspan2 padding-top-10 align-right">Lĩnh vực</div>
		<div class="cell colspan4 input-control select">
			<select name="id_linhvuc" id="id_linhvuc" class="select2">
				<option value="">&nbsp;</option>
				<?php
				$linhvuc_list = $linhvuc->get_all_list();
				if($linhvuc_list){
					foreach ($linhvuc_list as $l) {
						echo '<option value="'.$l['_id'].'"'.($l['_id']==$id_linhvuc ? ' selected' : '').'>'.$l['ten'].'</option>';
					}
				}
				?>
			</select>
		</div>
	</div>
	<div class="row cells12">
		<div class="cell colspan2 padding-top-10">Địa chỉ</div>
		<div class="cell colspan4 input-control text">
			<input type="text" name="diachi" id="diachi" value="<?php echo isset($diachi) ? $diachi : ''; ?>" placeholder="Địa chỉ"/>
			<span class="input-state-error mif-warning"></span><span class="input-state-success mif-checkmark"></span>
		</div>
		<div class="cell colspan2 padding-top-10 align-right">Quốc gia</div>
		<div class="cell colspan4 input-control select">
			<select name="id_quocgia" id="id_quocgia" class="select2">
				<option value="">Chọn quốc gia</option>
				<?php
				if($quocgia_list){
					foreach ($quocgia_list as $qg) {
						echo '<option value="'.$qg['_id'].'"'.($qg['_id'] == $id_quocgia ? ' selected' : '').'>'.$qg['ten'].'</option>';
					}
				}
				?>
			</select>
		</div>
	</div>
	<div class="row cells12">
		<div class="cell colspan2 padding-top-10">Điện thoại</div>
		<div class="cell colspan4 input-control text">
			<input type="text" name="dienthoai" id="dienthoai" value="<?php echo isset($dienthoai) ? $dienthoai : ''; ?>" placeholder="Điện thoại" />
		</div>
		<div class="cell colspan2 padding-top-10 align-right">Email</div>
		<div class="cell colspan4 input-control text">
			<input type="text" name="email" id="email" value="<?php echo isset($email) ? $email : ''; ?>" placeholder="Email" />
		</div>
	</div>
	<div class="row cells12">
		<div class="cell colspan2 padding-top-10">Ghi chú</div>
		<div class="cell colspan10 input-control textarea">
			<textarea name="ghichu" id="ghichu" placeholder="Ghi chú"><?php echo isset($ghichu) ? $ghichu : ''; ?></textarea>
		</div>
	</div>
	<div class="row cells12">
		<div class="cell colspan2 padding-top-10">Public</div>
		<div class="cell colspan10">
			<label class="switch">
			    <input type="checkbox" name="public" id="public" value="1" <?php echo $public == 1 ? ' checked' : ''; ?>/>
			    <span class="check"></span>
			</label>
		</div>
	</div>
	<div class="row cells12">
		<div class="cell colspan12 align-center">
			<button name="submit" id="submit" value="OK" class="button primary"><span class="mif-checkmark"></span> Cập nhật</button>
			<a href="donvi.php" class="button"><span class="mif-keyboard-return"></span> Trở về</a>
		</div>
	</div>
</div>

</form>
<?php require_once('footer.php'); ?>