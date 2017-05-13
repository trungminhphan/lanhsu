<?php
require_once('header.php');
if(!($users->getRole() & ADMIN)){
	echo '<div class="messages">Bạn không có quyền...! <a href="index.php">Trở về</a></div>';
	require_once('footer.php');
	exit();
}
$id = isset($_GET['id']) ? $_GET['id'] : '';
$sum_roles = 0;
$roles = 0;
$password = '';

if(isset($_POST['submit'])){
	$id = isset($_POST['id']) ? $_POST['id'] : '';
	$username = isset($_POST['username']) ? $_POST['username'] : '';
	$password = isset($_POST['password']) ? $_POST['password'] : '';
	$roles = isset($_POST['roles']) ? $_POST['roles'] : '';
	$person = isset($_POST['person']) ? $_POST['person'] : '';
	if($roles){
		foreach($roles as $key=>$value){
			$sum_roles += intval($value);
		}
	}
	$users->id = $id;
	$users->username = $username;
	$users->password = $password;
	$users->roles = $sum_roles;
	$users->person = $person;
	if($id){
		$users->edit();
		transfers_to('users.php');
	} else {
		if($users->check_exists){
			echo '<div class="messages">Tài khoản đã tồn tại, vui lòng chọn tài khoản khác.</div>';
		} else {
			$users->insert();
			transfers_to('users.php');
		}
	}
}

if($id){
	$users->id = $id;
	$edit_user = $users->get_one();
	$id = $edit_user['_id'];
	$username = $edit_user['username'];
	$password = '';
	$roles = $edit_user['roles'];
	$person = $edit_user['person'];
}
?>
<script type="text/javascript" src="js/html5.messages.js"></script>
<script type="text/javascript" src="js/jquery.validate.min.js"></script>
<h1><a href="index.php" class="nav-button transform"><span></span></a>&nbsp;Thêm tài khoản người dùng</h1>
<div class="example">
<form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post" id="adduserform"  data-role="validator" data-show-required-state="false" >
<div class="grid">
	<div class="row cells12">
		<div class="cell colspan2 padding-top-10">Tài khoản</div>
		<div class="cell colspan6 input-control text" >
			<input type="hidden" name="id" id="id" value="<?php echo isset($id) ? $id : ''; ?>" />
			<input type="text" name="username" id="username" value="<?php echo isset($username) ? $username : ''; ?>" placeholder="Nhập tài khoản" class="edit-text" <?php echo $id ? 'readonly' : ''; ?>  data-validate-func="minlength" data-validate-arg="5" data-validate-hint="Tối đa 5 ký tự!"/>
			<span class="input-state-error mif-warning"></span><span class="input-state-success mif-checkmark"></span>
		</div>
		<div class="cell colspan4"></div>
	</div>
	<div class="row cells12">
		<div class="cell colspan2 padding-top-10">Mật khẩu</div>
		<div class="cell colspan6 input-control text" >
			<input type="password" name="password" id="password" value="" placeholder="Nhập mật khẩu" class="edit-text" <?php echo !$id ? 'data-validate-func="minlength" data-validate-arg="6" data-validate-hint="Tối đa 6 ký tự!' : ''; ?>" />
			<span class="input-state-error mif-warning"></span><span class="input-state-success mif-checkmark"></span>
		</div>
		<div class="cell colspan4"></div>
	</div>
	<div class="row cells12">
		<div class="cell colspan2 padding-top-10">Họ tên người được cấp tài khoản</div>
		<div class="cell colspan6 input-control text">
			<input type="text" name="person" id="person" value="<?php echo isset($person) ? $person : ''; ?>" placeholder="Nhập họ tên người được cấp" class="edit-text" size="80" data-validate-func="required" data-validate-hint="Hãy nhập họ tên!">
			<span class="input-state-error mif-warning"></span><span class="input-state-success mif-checkmark"></span>
		</div>
		<div class="cell colspan4"></div>
	</div>
	<div class="row cells12">
		<div class="cell colspan2 padding-top-10">Phân quyền</div>
		<div class="cell colspan6 input-control checkbox">
			<label class="input-control checkbox">
			    <input type="checkbox" name="roles[]" value="1" <?php echo  ($roles & ADMIN) ? ' checked': ''; ?>>
			    <span class="check"></span>
			    <span class="caption">Admin</span>
			</label>
			<label class="input-control checkbox">
			    <input type="checkbox" name="roles[]" value="2" <?php echo  ($roles & MANAGER) ? ' checked': ''; ?>>
			    <span class="check"></span>
			    <span class="caption">Manager</span>
			</label>
			<label class="input-control checkbox">
			    <input type="checkbox" name="roles[]" value="4" <?php echo  ($roles & UPDATER) ? ' checked': ''; ?>>
			    <span class="check"></span>
			    <span class="caption">Updater</span>
			</label>
			<label class="input-control checkbox">
			    <input type="checkbox" name="roles[]" value="8" <?php echo  ($roles & STUDENT) ? ' checked': ''; ?>>
			    <span class="check"></span>
			    <span class="caption">Student</span>
			</label>
		</div>
		<div class="cell colspan4"></div>
	</div>
	<div class="row cells12">
		<div class="cell colspan12 align-center">
			<button name="submit" id="submit" value="OK" class="button primary"><span class="mif-user-check"></span> Cập nhật</button>
			<a href="users.php" class="button"><span class="mif-undo"></span> Trở về danh mục Tài khoản</a>
		</div>
	</div>
</div>
</form>
</div>
<?php require_once('footer.php'); ?>