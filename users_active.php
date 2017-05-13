<?php
require_once('header.php');
$users_regis = new Users_Regis();$canbo = new CanBo();
$id = isset($_GET['id']) ? $_GET['id'] : '';
$act = isset($_GET['act']) ? $_GET['act'] : '';
$status=1;
if($act=='del' && $id){
	//kiem tra dang ky doanra_regis, doanvao_regis, abtc_regis
	if($doanra_regis->check_users($id) || $doanvao_regis->check_users($id) || $abtc_regis->check_users($id)){
		$msg = 'Không thể xóa, vì tài khoản này có đăng ký';
	} else {
		$users_regis->id = $id;
		if($users_regis->delete()){
			transfers_to('users_regis.php');
		} else {
			$msg = 'Không thể xóa';
		}
	}
}

if(isset($_POST['submit'])){
	$id = isset($_POST['id']) ? $_POST['id'] : '';
	$id_canbo = isset($_POST['canbo']) ? $_POST['canbo'] : '';
	if($id_canbo){
		$a = explode('-', $id_canbo);
		$arr_canbo = array('id_canbo' => new MongoId($a[0]), 
						'id_donvi' => explode(",", $a[1]),
						'id_chucvu' => $a[2] ? new MongoId($a[2]) : '',
						'id_ham' => $a[3] ? new MongoId($a[3]) : '');
	} else { $arr_canbo = ''; }

	$email = isset($_POST['email']) ? $_POST['email'] : '';
	$password = isset($_POST['password']) ? $_POST['password'] : '';
	$status = isset($_POST['status']) ? $_POST['status'] : 0;

	$users_regis->email = $email;
	$users_regis->status = $status;
	$users_regis->canbo = $arr_canbo;
	$users_regis->id_user = $users->get_userid();
	if($id){
		$users_regis->id = $id;
		if($users_regis->actived_account()){
			transfers_to('users_regis.php');
		} else {
			$msg = 'Không thể chỉnh sửa tài khoản';
		}
	} 
}

if($id){
	$users_regis->id = $id;$u = $users_regis->get_one();
	$email = $u['email'];
	$arr_canbo = isset($u['canbo']) ? $u['canbo'] : '';
	$status = isset($u['status']) ? $u['status'] : 0;
	$hoten = $u['hoten'];$donvi = $u['donvi']; $chucvu=$u['chucvu']; $dienthoai=$u['dienthoai'];
}
?>
<script type="text/javascript" src="js/select2.min.js"></script>
<script type="text/javascript" src="js/users_regis.js"></script>
<script type="text/javascript">
	$(document).ready(function(){
		canboduoccap();
		<?php if(isset($msg) && $msg): ?>
       		$.Notify({type: 'alert', caption: 'Thông báo', content: <?php echo "'".$msg. "'"; ?>});
    	<?php endif; ?>
	});
</script>
<h1><a href="index.php" class="nav-button transform"><span></span></a>&nbsp;Kích hoạt tài khoản người dùng đăng ký trực tuyến</h1>
<form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="POST" data-role="validator" data-show-required-state="false" data-hint-mode="line" data-hint-background="bg-red" data-hint-color="fg-white" data-hide-error="5000" id="register">
	<div class="grid">
		<div class="row cells12">
			<div class="cell colspan2"></div>
			<div class="cell colspan2 padding-top-10 align-right">Email</div>
			<div class="cell colspan6 input-control text">
				<input type="hidden" name="id" id="id" value="<?php echo isset($id) ? $id : ''; ?>">
				<input type="text" name="email" id="email" value="<?php echo isset($email) ? $email : ''; ?>" data-validate-func="email" data-validate-hint="Địa chỉ email sai --> ten@angiang.gov.vn" placeholder="Địa chỉ email: ten@angiang.gov.vn" <?php echo $id ? 'disabled' : '';?> />
				<span class="input-state-error mif-warning"></span><span class="input-state-success mif-checkmark"></span>
			</div>
		</div>
		
		<div class="row cells12">
			<div class="cell colspan2"></div>
			<div class="cell colspan2 padding-top-10 align-right">Người được cấp</div>
			<div class="cell colspan6 input-control select">
				<select name="canbo" id="canbo">
				<?php
				if($arr_canbo){
					$v = $arr_canbo['id_canbo'] . '-' . implode(",", $arr_canbo['id_donvi']) . '-' . $arr_canbo['id_chucvu'] . '-' . $arr_canbo['id_ham'];
					$canbo->id = $arr_canbo['id_canbo'];$cb = $canbo->get_one();
					echo '<option value="'.$v.'" selected>'.$cb['hoten'] .' [ID:'.$cb['code']  .']</option>';
				}
				?>
				</select>
			</div>
		</div>
		<div class="row cells12">
			<div class="cell colspan2"></div>
			<div class="cell colspan2 align-right">Họ tên</div>
			<div class="cell colspan6"><b><?php echo $hoten; ?></b></div>
		</div>
		<div class="row cells12">
			<div class="cell colspan2"></div>
			<div class="cell colspan2 align-right">Đơn vị</div>
			<div class="cell colspan6"><b><?php echo $donvi; ?></b></div>
		</div>
		<div class="row cells12">
			<div class="cell colspan2"></div>
			<div class="cell colspan2 align-right">Chức vụ</div>
			<div class="cell colspan6"><b><?php echo $chucvu; ?></b></div>
		</div>
		<div class="row cells12">
			<div class="cell colspan2"></div>
			<div class="cell colspan2 align-right">Điện thoại</div>
			<div class="cell colspan6"><b><?php echo $dienthoai; ?></b></div>
		</div>
		<div class="row cells12">
			<div class="cell colspan2"></div>
			<div class="cell colspan2 align-right">Tình trạng</div>
			<div class="cell colspan6">
				<label class="switch">
				    <input type="checkbox" name="status" id="status" value="1" <?php echo $status==1 ? 'checked' : ''; ?> />
				    <span class="check"></span>
				</label>
			</div>
		</div>
		<div class="row cells12">
		<div class="cell colspan12 align-center">
			<button name="submit" id="submit" value="OK" class="button primary"><span class="mif-checkmark"></span> Cập nhật</button>
			<a href="users_regis.php" class="button"><span class="mif-keyboard-return"></span> Trở về</a>
		</div>
	</div>
	</div>
</form>

<?php require_once('footer.php'); ?>

