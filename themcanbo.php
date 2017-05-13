<?php
require_once('header.php');
$act = isset($_GET['act']) ? $_GET['act'] : '';
if(!$users->is_admin() && !$users->is_student()){
	echo '<h2>Bạn không có quyền...! <a href="index.php">Trở về</a></h2>';
	require_once('footer.php');
	exit();
}
if($users->is_student() && $act=='edit'){
	echo '<h2>Bạn không có quyền...! <a href="index.php">Trở về</a></h2>';
	require_once('footer.php');
	exit();	
}
$id = isset($_GET['id']) ? $_GET['id'] : '';
$canbo = new CanBo();$doanra = new DoanRa();$ham=new Ham();$nghenghiep=new NgheNghiep();$dantoc = new DanToc();
$msg = '';$hinhanh = '';$gioitinh='';$id_quoctich='56f9fd7732341c4008002015';$id_donvi='';$id_chucvu='';$dangvien=0;$tinhuyvien=0;$loaicongchuc='';
$id_nghenghiep='';$id_dantoc='';
$act = isset($_GET['act']) ? $_GET['act'] : '';
$update = isset($_GET['update']) ? $_GET['update'] : '';
if($update=='insert_ok') $msg = 'Thêm thành công';
if($update=='insert_no') $msg = 'Không thể thêm...';
if($id && $act=='del'){
	if($doanra->check_dm_canbo($id)){
		transfers_to('canbo.php?update=no');
	} else {
		$canbo->id = $id; $cb = $canbo->get_one();
		$canbo->id_user = $users->get_userid();
		if($canbo->delete()){
			if($cb['hinhanh']) unlink($target_images . $cb['hinhanh']);
			transfers_to('canbo.php');
		} else {
			$msg = 'Không thể xoá';
		}
	}
}
if(isset($_POST['submit'])){
	$passport = isset($_POST['passport']) ? $_POST['passport'] : '';
	$file_name = $_FILES['hinhanh']['name'];
	$old_hinhanh = isset($_POST['old_hinhanh']) ? $_POST['old_hinhanh'] : '';
	if($file_name){
		$file_extension =  pathinfo($file_name, PATHINFO_EXTENSION);
		$filename =  date('Ymdhs') .'_'. $file_name;
		if(in_array(strtolower($file_extension), $images_extension)){
			if(move_uploaded_file($_FILES["hinhanh"]["tmp_name"], $target_images . $filename)){
				if($old_hinhanh) unlink($old_hinhanh);
				$hinhanh = $filename;
				$msg = 'Upload hình ảnh thành công';
			} else {
				$msg = 'Hình ảnh không thể Upload';
			}
		} else {
			$hinhanh = $old_hinhanh;
		}
	} else {
		$hinhanh = $old_hinhanh;
	}
	$id = isset($_POST['id']) ? $_POST['id'] : '';
	$code = isset($_POST['code']) ? $_POST['code'] : 0;
	$dangvien = isset($_POST['dangvien']) ? $_POST['dangvien'] : 0;
	$loaicongchuc = isset($_POST['loaicongchuc']) ? $_POST['loaicongchuc'] : '';
	$tinhuyvien = isset($_POST['tinhuyvien']) ? $_POST['tinhuyvien'] : 0;
	$cmnd = isset($_POST['cmnd']) ? $_POST['cmnd'] : '';
	$passport = isset($_POST['passport']) ? $_POST['passport'] : '';
	$ngayhethan = isset($_POST['ngayhethan']) ? $_POST['ngayhethan'] : '';
	$passport_arr = array();
	if($passport && is_array($passport)){
		foreach ($passport as $key => $value) {
			$nhh = $ngayhethan[$key] ? new MongoDate(convert_date_dd_mm_yyyy($ngayhethan[$key])) : '';
			array_push($passport_arr, array($value, $nhh));
		}
	}
	$hoten = isset($_POST['hoten']) ? $_POST['hoten'] : '';
	$ngaysinh = isset($_POST['ngaysinh']) ? $_POST['ngaysinh'] : '';
	$gioitinh = isset($_POST['gioitinh']) ? $_POST['gioitinh'] : '';
	$id_quoctich = isset($_POST['id_quoctich']) ? $_POST['id_quoctich'] : '';
	$diachi = isset($_POST['diachi']) ? $_POST['diachi'] : '';
	$dienthoai = isset($_POST['dienthoai']) ? $_POST['dienthoai'] : '';
	$email = isset($_POST['email']) ? $_POST['email'] : '';
	$id_nghenghiep = isset($_POST['id_nghenghiep']) ? $_POST['id_nghenghiep'] : '';
	$id_dantoc = isset($_POST['id_dantoc']) ? $_POST['id_dantoc'] : '';
	$id_donvi_1 = isset($_POST['id_donvi_1']) ? $_POST['id_donvi_1'] : '';
	$id_donvi_2 = isset($_POST['id_donvi_2']) ? $_POST['id_donvi_2'] : '';
	$id_donvi_3 = isset($_POST['id_donvi_3']) ? $_POST['id_donvi_3'] : '';
	$id_donvi_4 = isset($_POST['id_donvi_4']) ? $_POST['id_donvi_4'] : '';
	$id_donvi = array($id_donvi_1,$id_donvi_2,$id_donvi_3,$id_donvi_4);
	$id_chucvu = isset($_POST['id_chucvu']) ? $_POST['id_chucvu'] : '';
	$id_ham = isset($_POST['id_ham']) ? $_POST['id_ham'] : '';
	//$ngaynhap = isset($_POST['ngaynhap']) ? $_POST['ngaynhap'] : '';
	if(!$id && $act != 'edit'){
		$donvi = array(array('id' => new MongoId(), 'id_donvi' => $id_donvi,'id_chucvu' => $id_chucvu ? new MongoId($id_chucvu) : '','id_ham' => $id_ham ? new MongoId($id_ham) : '', 'ngaynhap' => new MongoDate()));
		$canbo->donvi = $donvi;
	}
	$ghichu = isset($_POST['ghichu']) ? $_POST['ghichu'] : '';
	$canbo->code = $canbo->get_maxcode();//$code;
	$canbo->dangvien = $dangvien;
	$canbo->loaicongchuc = $loaicongchuc;
	$canbo->tinhuyvien = $tinhuyvien;
	$canbo->hinhanh = $hinhanh; $canbo->cmnd = $cmnd;
	$canbo->passport = $passport_arr;
	$canbo->hoten = $hoten;
	$canbo->ngaysinh = $ngaysinh ? new MongoDate(convert_date_dd_mm_yyyy_1($ngaysinh)) : '';
	$canbo->gioitinh = $gioitinh;
	$canbo->id_quoctich = $id_quoctich; $canbo->diachi = $diachi;
	$canbo->dienthoai = $dienthoai; $canbo->email = $email;
	$canbo->id_nghenghiep = $id_nghenghiep;
	$canbo->id_dantoc = $id_dantoc;
	$canbo->ghichu = $ghichu;
	$canbo->id_user = $users->get_userid();
	if($canbo->check_exists($id_donvi)){
		$msg = 'Thông tin CÁ NHÂN đã tồn tại trong CSDL...';
	} else {
		if($id && $act=='edit'){
			//edit
			$canbo->id = $id;
			if($canbo->edit()){
				transfers_to('chitietcanbo.php?id='.$id);
				$msg = 'Chỉnh sửa thành công.';
			} else {
				$msg = 'Không thể chỉnh sửa.';
			}
		} else {
			//insert
			$id = new MongoId();
			$canbo->id = $id;
			if($canbo->insert()){
				transfers_to('chitietcanbo.php?id='.$id);
			} else {
				@unlink($target_images . $hinhanh);
				transfers_to('themcanbo.php?id=&update='.$id.'insert_no');
			}
			
		}
	}
}

if($id && $act == 'edit'){
	$canbo->id =$id; $cb = $canbo->get_one();
	$code = $cb['code'];
	$cmnd = $cb['cmnd'];
	$passport=$cb['passport'];
	$dangvien = $cb['dangvien'];
	$loaicongchuc = isset($cb['loaicongchuc']) ? $cb['loaicongchuc'] : '';
	$tinhuyvien = isset($cb['tinhuyvien']) ? $cb['tinhuyvien'] : '';
	$hoten = $cb['hoten'];
	$ngaysinh = $cb['ngaysinh'] ? date("d/m/Y", $cb['ngaysinh']->sec) : '';
	$gioitinh = isset($cb['gioitinh']) ? $cb['gioitinh'] : '';
	$id_quoctich = isset($cb['id_quoctich']) ? $cb['id_quoctich'] : '';
	$diachi = isset($cb['diachi']) ? $cb['diachi'] : '';
	$id_nghenghiep = isset($cb['id_nghenghiep']) ? $cb['id_nghenghiep'] : '';
	$dienthoai = isset($cb['dienthoai']) ? $cb['dienthoai'] : '';
	$email = isset($cb['email']) ? $cb['email'] : '';
	$ghichu = isset($cb['ghichu']) ? $cb['ghichu'] : '';
} else {
	$code = $canbo->get_maxcode();
}
?>
<script type="text/javascript" src="js/select2.min.js"></script>
<script type="text/javascript" src="js/jquery.inputmask.js"></script>
<script type="text/javascript" src="js/jquery.setcase.js"></script>
<script type="text/javascript" src="js/canbo.js"></script>
<script type="text/javascript">
	$(document).ready(function(){
		$(".select2").select2(); id_donvi_action_chitiet();hide_donvi();
		$(".ngaythangnam").inputmask();
		<?php if(isset($msg)  && $msg): ?>
            $.Notify({type: 'alert', caption: 'Thông báo', content:  '<?php echo $msg; ?>'});
        <?php endif; ?>
        $("#add_passport").click(function(){
        	$("#passport_list").append($("#passport_more").html());
        	$(".delete_passport").click(function(){
        		$(this).parents(".items").remove();
        	});
        });
        $(".delete_passport").click(function(){
    		$(this).parents(".items").remove();
    	});
    	$("#hoten").toCapitalize();
    	<?php if(isset($id_donvi_1) && $id_donvi_1) : ?>
    		$.getJSON("get.donvi.php?id1=" + $("#id_donvi_1").val() + "&level=2", function(d){
	            $("#id_donvi_2").html(d.str_donvi_2);
	            if(id_donvi_2) $("#id_donvi_2").select2("val", id_donvi_2);$("#id_donvi2").show();
	            $("#id_donvi_2").select2({ placeholder : "Chọn đơn vị", allowClear: true });
	            $("#id_donvi_3").select2({ placeholder : "Chọn đơn vị", allowClear: true });
	            $("#id_donvi_4").select2({ placeholder : "Chọn đơn vị", allowClear: true });
       		 });
    	<?php endif; ?>
    	radioButton('loaicongchuc');
	});
</script>
<h1><a href="canbo.php" class="nav-button transform"><span></span></a>&nbsp;Thêm Cá Nhân - ID: <?php echo $code; ?></h1>
<form method="POST" action="<?php echo $_SERVER['REQUEST_URI']; ?>" id="themcanboform" data-role="validator" data-show-required-state="false" enctype="multipart/form-data">
<div class="grid" style="margin-top: 30px;">
	<input type="hidden" name="id" id="id" value="<?php echo $id; ?>" />
	<div class="row cells12">
		<div class="cell colspan2 padding-top-10 align-right">
		<input type="hidden" name="code" id="code" value="<?php echo isset($code) ? $code : '';?>" placeholder="ID" disabled/>
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
	<?php if(!$id): ?>
	<div class="row cells12">
		<div class="cell colspan2 padding-top-10 align-right">Số hộ chiếu</div>
		<div class="cell colspan4 input-control text">
			<input type="text" name="passport[]" value="" />
			<a href="#" onclick="return false;" class="button" id="add_passport"><span class="mif-plus"></span></a>
		</div>
		<div class="cell colspan2 padding-top-10 align-right">Ngày hết hạn</div>
		<div class="cell colspan4 input-control text" data-role="datepicker" data-format="dd/mm/yyyy">
			<input type="text" name="ngayhethan[]" value="" data-inputmask="'alias': 'date'" class="ngaythangnam"/>
			<button class="button"><span class="mif-calendar"></span></button>
		</div>
	</div>
	<?php endif; ?>
	<div id="passport_list" style="margin-bottom: 15px;">
		<?php
		if(isset($passport) && $passport && $act == 'edit'){
			foreach ($passport as $k => $p) {
				if(is_array($p)){
					$passport_number = $p[0] ? $p[0] : '';
					$nhh = $p[1] ? date("d/m/Y", $p[1]->sec) : '';
				} else {
					$passport_number = $p;
					$nhh = '';
				}
				if($k == 0) {
					echo '<div class="items row cells12">';
					echo '	<div class="cell colspan2 padding-top-10 align-right">Số hộ chiếu</div>';
					echo '	<div class="cell colspan4 input-control text">';
					echo '	<input type="text" name="passport[]" value="'.$passport_number.'" placeholder="Hộ chiếu" />';
					echo '	<a href="#" onclick="return false;" class="button" id="add_passport"><span class="mif-plus"></span></a>';
					echo '</div>';
					echo '<div class="cell colspan2 padding-top-10 align-right">Ngày hết hạn</div>';
					echo '<div class="cell colspan4 input-control text" data-role="datepicker" data-format="dd/mm/yyyy">';
						echo '<input type="text" name="ngayhethan[]" value="'. $nhh.'" data-inputmask="\'alias\': \'date\'" class="ngaythangnam"/>';
						echo '<button class="button"><span class="mif-calendar"></span></button>';
					echo '</div>';
					echo '	</div>';
				} else {
					echo '<div class="items row cells12">';
					echo '	<div class="cell colspan2 padding-top-10 align-right">Passport</div>';
					echo '	<div class="cell colspan4 input-control text">';
					echo '	<input type="text" name="passport[]" value="'.$passport_number.'" placeholder="Hộ chiếu" />';
					echo '	<a href="#" onclick="return false;" class="button delete_passport"><span class="mif-bin"></span></a>';
					echo '</div>';
					echo '<div class="cell colspan2 padding-top-10 align-right">Ngày hết hạn</div>';
					echo '<div class="cell colspan4 input-control text" data-role="datepicker" data-format="dd/mm/yyyy">';
						echo '<input type="text" name="ngayhethan[]" value="'. $nhh.'" data-inputmask="\'alias\': \'date\'" class="ngaythangnam"/>';
						echo '<button class="button"><span class="mif-calendar"></span></button>';
					echo '</div>';
					echo '	</div>';
				}
			}
		}
		?>
	</div>
	<div class="row cells12">
		<div class="cell colspan2 padding-top-10 align-right">CMND</div>
		<div class="cell colspan4 input-control text">
			<input type="text" name="cmnd" id="cmnd" value="<?php echo isset($cmnd) ? $cmnd : '';?>" placeholder="" />
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
		<div class="cell colspan2 padding-top-10 align-right">Giới tính</div>
		<div class="cell colspan4 input-control select">
			<select name="gioitinh" id="gioitinh" class="select2">
				<option value="Nam" <?php echo $gioitinh=='Nam' ? ' selected' : ''; ?>>Nam</option>
				<option value="Nữ" <?php echo $gioitinh=='Nữ' ? ' selected' : ''; ?>>Nữ</option>
			</select>
		</div>
		<div class="cell colspan2 padding-top-10 align-right">Quốc tịch</div>
		<div class="cell colspan4 input-control select">
			<select name="id_quoctich" id="id_quoctich" class="select2" tabindex="11">
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
		<div class="cell colspan2 padding-top-10 align-right">Địa chỉ</div>
		<div class="cell colspan4 input-control text">
			<input type="text" name="diachi" id="diachi" value="<?php echo isset($diachi) ? $diachi : '';?>" />
		</div>
		<div class="cell colspan2 padding-top-10 align-right">Điện thoại</div>
		<div class="cell colspan4 input-control text">
			<input type="text" name="dienthoai" id="dienthoai" value="<?php echo isset($dienthoai) ? $dienthoai : '';?>" />
		</div>
	</div>
	<div class="row cells12">
		<div class="cell colspan2 padding-top-10 align-right">Email</div>
		<div class="cell colspan4 input-control text">
			<input type="text" name="email" id="email" value="<?php echo isset($email) ? $email : '';?>" />
		</div>
		<div class="cell colspan2 padding-top-10 align-right">Nghề nghiệp</div>
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
	</div>
	<?php if(!$id) : ?>
	<div class="row cells12">
		<div class="cell colspan2 padding-top-10 align-right">Đơn vị công tác</div>
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
		<div class="cell colspan2 padding-top-10 align-right">Chức vụ</div>
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
		<div class="cell colspan2 padding-top-10 align-right">Đơn vị trực thuộc 1</div>
		<div class="cell colspan4 input-control" data-role="select" data-allow-clear="true">
			<select name="id_donvi_2" id="id_donvi_2" class="select2">
				<option value="">Chọn đơn vị</option>
			</select>
		</div>
		<div class="cell colspan2 padding-top-10 align-right">Hàm</div>
		<div class="cell colspan4 input-control select">
			<select name="id_ham" id="id_ham" class="select2">
				<option value=""></option>
				<?php
				$ham_list = $ham->get_all_list();
				if($ham_list){
					foreach ($ham_list as $h) {
						echo '<option value="'.$h['_id'].'"'.($id_ham==$h['_id'] ? ' selected' : '').'>'.$h['ten'].'</option>';
					}
				}
				?>
			</select>			
		</div>
	</div>
	<div id="id_donvi3" class="row cells12">
		<div class="cell colspan2 padding-top-10 align-right">Đơn vị trực thuộc 2</div>
		<div class="cell colspan4 input-control" data-role="select" data-allow-clear="true">
			<select name="id_donvi_3" id="id_donvi_3" class="select2">
				<option value="">Chọn đơn vị</option>
			</select>
		</div>
	</div>
	<div id="id_donvi4" class="row cells12">
		<div class="cell colspan2 padding-top-10 align-right">Đơn vị trực thuộc 3</div>
		<div class="cell colspan4 input-control" data-role="select" data-allow-clear="true">
			<select name="id_donvi_4" id="id_donvi_4" class="select2">
				<option value="">Chọn đơn vị</option>
			</select>
		</div>
	</div>
	<?php endif; ?>
	<div class="row cells12">
		<div class="cell colspan2 padding-top-10 align-right">Hình ảnh</div>
		<div class="cell colspan4 input-control file" data-role="input">
			<?php if($id && isset($cb['hinhanh']) && $cb['hinhanh']) : ?>
				<input type="hidden" name="old_hinhanh" id="old_hinhanh" value="<?php echo $cb['hinhanh']; ?>" />
			<?php endif; ?>
			<input type="file" name="hinhanh" id="hinhanh" accept="image/*" />
			<button class="button"><span class="mif-file-image"></span></button>
		</div>
		<div class="cell colspan2 padding-top-10 align-right">Dân tộc</div>
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
		<div class="cell colspan2 padding-top-10 align-right">Ghi chú</div>
		<div class="cell colspan10 input-control textarea">
			<textarea name="ghichu" id="ghichu"><?php echo isset($ghichu) ? $ghichu : ''; ?></textarea>
		</div>
	</div>
	<div class="row cells12">
		<div class="cell colspan12 align-center">
			<button name="submit" id="submit" value="OK" class="button primary"><span class="mif-checkmark"></span> Cập nhật</button>
			<a href="canbo.php" class="button"><span class="mif-keyboard-return"></span> Trở về</a>
			<input type="reset" class="button" value="Hủy">
			<?php if($id) : ?>
				<a href="chitietcanbo.php?id=<?php echo $id; ?>" class="button bg-pink bg-active-darkPink fg-white"><span class="mif-organization"></span> Chỉnh sửa Đơn vị công tác</a>
			<?php endif; ?>
		</div>
	</div>
</div>
</form>

<div id="passport_more" style="display: none;">
	<div class="items row cells12">
		<div class="cell colspan2 padding-top-10 align-right">Passport</div>
		<div class="cell colspan4 input-control text">
			<input type="text" name="passport[]" value="" placeholder="Hộ chiếu" />
			<a href="#" onclick="return false;" class="button delete_passport"><span class="mif-bin"></span></a>
		</div>
		<div class="cell colspan2 padding-top-10 align-right">Ngày hết hạn</div>
		<div class="cell colspan4 input-control text" data-role="datepicker" data-format="dd/mm/yyyy">
			<input type="text" name="ngayhethan[]" value="<?php echo isset($ngayhethan) ? $ngayhethan : '';?>" data-inputmask="'alias': 'date'" class="ngaythangnam"/>
			<button class="button"><span class="mif-calendar"></span></button>
		</div>
	</div>
</div>
<?php require_once('footer.php'); ?>