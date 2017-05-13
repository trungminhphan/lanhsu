<?php 
require_once('header.php');
if(!$users->is_admin()){
	echo '<h2>Bạn không có quyền...! <a href="index.php">Trở về</a></h2>';
	require_once('footer.php');
	exit();
}
$quocgia = new QuocGia();$canbo = new CanBo();$doanra = new DoanRa();
$quocgia_list = $quocgia->get_all_list();
$id = isset($_GET['id']) ? $_GET['id'] : '';
$act = isset($_GET['act']) ? $_GET['act'] : '';
$url = isset($_GET['url']) ? $_GET['url'] : '';
$update = isset($_GET['update']) ? $_GET['update'] : ''; 
if($update=='ok'){ $msg = 'Cập nhật thành công'; }
if($update=='no'){ $msg = 'Không thể cập nhật'; }
if($id && $act=='del'){
	$canbo->id_quoctich = $id;
	if($canbo->check_dm_quocgia() || $doanra->check_dm_quocgia($id)){
		$msg = '<div class="alert error">Không thể xoá... [Cán bộ công chức], [Đoàn ra]</div>';
	} else {
		$quocgia->id = $id;
		if($quocgia->delete()){
			transfers_to('quocgia.php?update=ok');
		} else {
			$msg = 'Không thể xoá';
		}
	}
}
if(isset($_POST['submit'])){
	$id = isset($_POST['id']) ? $_POST['id'] : '';
	$tenquocgia = isset($_POST['tenquocgia']) ? $_POST['tenquocgia'] : '';
	$quocgia->ten = $tenquocgia;
	$quocgia->id_user = $users->get_userid();
	if($id){
		//edit
		$quocgia->id = $id;
		if($quocgia->edit()){
			transfers_to('quocgia.php?update=ok');
		}
	} else {
		//insert
		$_id = new MongoId(); $quocgia->id = $_id;
		if($quocgia->check_exists()){
			$msg = '<div class="alert error">Tên quốc gia đã tồn tại.</div>';
		} else {
			if($quocgia->insert()){
				if($url) transfers_to($url);
				else transfers_to('quocgia.php?update=ok');
			}
		}
	}
}

if($id){
	$quocgia->id = $id;
	$edit = $quocgia->get_one();
	$id = $edit['_id'];
	$tenquocgia = $edit['ten'];
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
<h1><a href="index.php" class="nav-button transform"><span></span></a>&nbsp;Quản lý Quốc gia</h1>
<?php if(($users->getRole() & ADMIN) == ADMIN): ?>
<div style="padding:5px;">
<form action="<?php echo $_SERVER['REQUEST_URI']; ?>" method="POST" id="quocgiaform">
	<a href="quocgia.php" class="button"><span class="mif-sync-problem"></span> Tải lại trang</a>
	<input type="hidden" name="id" id="id" value="<?php echo isset($id) ? $id : '';  ?>" />
	<div class="input-control text">
		<input type="text" name="tenquocgia" id="tenquocgia" value="<?php echo isset($tenquocgia) ? $tenquocgia : ''; ?>" class="edit-text" size="50" required oninvalid="InvalidMsg(this);" oninput="InvalidMsg(this);" placeholder="Tên Quốc gia" />
	</div>
	<button name="submit" id="submit" value="OK" class="button primary"><span class="mif-checkmark"></span> Cập nhật</button>
</form>
</div>
<?php endif; ?>
<?php if($quocgia_list && $quocgia_list->count() > 0): ?>
<table class="table striped hovered dataTable" data-role="dataTable">
<thead>
	<tr>
		<th>STT</th>
		<th>Tên Quốc gia</th>
		<?php if(($users->getRole() & ADMIN) == ADMIN) : ?>
			<th><span class="mif-bin"></span></th>
			<th><span class="mif-pencil"></span></th>
		<?php endif; ?>
	</tr>
</thead>
<tbody>
<?php
$i = 1;
foreach($quocgia_list as $qg){
	echo '<tr>';
	echo '<td>'.$i.'</td>';
	//echo '<td>'.$qg['_id'].'</td>';
	echo '<td>'.$qg['ten'].'</td>';
	if(($users->getRole() & ADMIN) == ADMIN){
		echo '<td><a href="quocgia.php?id='.$qg['_id'].'&act=del" onclick="return confirm(\'Chắc chắc xoá?\');" title="Xoá"><span class="mif-bin"></span></td>';
		echo '<td><a href="quocgia.php?id='.$qg['_id'].'" title="Sủa"><span class="mif-pencil"></span></a></td>';
	}
	echo '</tr>';
	$i++;
}
?>
</tbody>
</table>
<?php endif; ?>
<?php require_once('footer.php'); ?>