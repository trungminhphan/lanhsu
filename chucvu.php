<?php 
require_once('header.php');
if(!$users->is_admin()){
	echo '<h2>Bạn không có quyền...! <a href="index.php">Trở về</a></h2>';
	require_once('footer.php');
	exit();
}
$chucvu = new ChucVu();$canbo = new CanBo();$doanra = new DoanRa();
$chucvu_list = $chucvu->get_all_list();
$id = isset($_GET['id']) ? $_GET['id'] : '';
$act = isset($_GET['act']) ? $_GET['act'] : '';
$url = isset($_GET['url']) ? $_GET['url'] : '';
$update = isset($_GET['update']) ? $_GET['update'] : ''; 
if($update=='ok'){ $msg = 'Cập nhật thành công.'; }
if($update=='no'){ $msg = 'Không thể xoá.'; }
if($id && $act=='del'){
	if($canbo->check_dm_chucvu($id) || $doanra->check_dm_chucvu($id)){
		$msg = 'Không thể xoá... [Cán bộ công chức],[Đoàn ra]';
	} else {
		$chucvu->id = $id;
		if($chucvu->delete()){
			transfers_to('chucvu.php?update=ok');
		} else {
			$msg = 'Không thể xoá';
		}
	}
}
if(isset($_POST['submit'])){
	$id = isset($_POST['id']) ? $_POST['id'] : '';
	$ten = isset($_POST['ten']) ? $_POST['ten'] : '';
	$chucvu->ten = $ten;
	$chucvu->id_user = $users->get_userid();
	if($id){
		//edit
		$chucvu->id = $id;
		if($chucvu->edit()){
			transfers_to('chucvu.php?update=ok');
		}
	} else {
		//insert
		$_id = new MongoId(); $chucvu->id = $_id;
		if($chucvu->check_exists()){
			$msg = '<div class="alert error">Tên Chức vụ đã tồn tại.</div>';
		} else {
			if($chucvu->insert()){
				if($url) transfers_to($url);
				else transfers_to('chucvu.php?update=ok');
			}
		}
	}
}

if($id){
	$chucvu->id = $id;
	$edit = $chucvu->get_one();
	$id = $edit['_id'];
	$ten = $edit['ten'];
}	
?>
<script type="text/javascript" src="js/html5.messages.js"></script>
<script type="text/javascript" src="js/jquery.setcase.js"></script>
<script type="text/javascript" src="js/jquery.dataTables.min.js"></script>
<script type="text/javascript">
	$(document).ready(function(){
		$("input[type='text']").toCapitalize();
		<?php if(isset($msg) && $msg): ?>
			$.Notify({type: 'alert', caption: 'Thông báo', content: <?php echo "'".$msg."'"; ?>});
		<?php endif; ?>
	});
</script>
<h1><a href="index.php" class="nav-button transform"><span></span></a>&nbsp;Quản lý Danh mục Chức vụ</h1>
<?php if(($users->getRole() & ADMIN) == ADMIN): ?>
<div style="padding:5px;">
<form action="<?php echo $_SERVER['REQUEST_URI']; ?>" method="POST" id="chucvuform">
	<a href="chucvu.php" class="button"><span class="mif-sync-problem"></span> Tải lại trang</a>
	<input type="hidden" name="id" id="id" value="<?php echo isset($id) ? $id : '';  ?>" />
	<div class="input-control text">
		<input type="text" name="ten" id="ten" value="<?php echo isset($ten) ? $ten : ''; ?>" class="edit-text" size="50" required oninvalid="InvalidMsg(this);" oninput="InvalidMsg(this);" placeholder="Tên Chức vụ" />
	</div>
	<button name="submit" id="submit" value="OK" class="button primary"><span class="mif-checkmark"></span> Cập nhật</button>
</form>
</div>
<?php endif; ?>
<?php if($chucvu_list && $chucvu_list->count() > 0): ?>
<table class="table striped hovered dataTable" data-role="datatable">
<thead>
	<tr>
		<th>STT</th>
		<th>Tên Chức vụ</th>
		<?php if(($users->getRole() & ADMIN) == ADMIN) : ?>
			<th><span class="mif-bin"></span></th>
			<th><span class="mif-pencil"></span></th>
		<?php endif; ?>
	</tr>
</thead>
<tbody>
<?php
$i = 1;
foreach($chucvu_list as $cv){
	echo '<tr>';
	echo '<td>'.$i.'</td>';
	//echo '<td>'.$qg['_id'].'</td>';
	echo '<td>'.$cv['ten'].'</td>';
	if(($users->getRole() & ADMIN) == ADMIN){
		echo '<td><a href="chucvu.php?id='.$cv['_id'].'&act=del" onclick="return confirm(\'Chắc chắc xoá?\');" title="Xoá"><span class="mif-bin"></span></td>';
		echo '<td><a href="chucvu.php?id='.$cv['_id'].'" title="Sủa"><span class="mif-pencil"></span></a></td>';
	}
	echo '</tr>';
	$i++;
}
?>
</tbody>
</table>
<?php endif; ?>
<?php require_once('footer.php'); ?>