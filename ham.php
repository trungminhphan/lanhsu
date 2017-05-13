<?php 
require_once('header.php');
if(!$users->is_admin()){
	echo '<h2>Bạn không có quyền...! <a href="index.php">Trở về</a></h2>';
	require_once('footer.php');
	exit();
}
$ham = new Ham();
$canbo = new CanBo();$doanra = new DoanRa();
$ham_list = $ham->get_all_list();
$id = isset($_GET['id']) ? $_GET['id'] : '';
$act = isset($_GET['act']) ? $_GET['act'] : '';
$url = isset($_GET['url']) ? $_GET['url'] : '';
$update = isset($_GET['update']) ? $_GET['update'] : ''; 
if($update=='ok'){ $msg = 'Cập nhật thành công'; }
if($update=='no'){ $msg = 'Không thể cập nhật'; }
if($id && $act=='del'){
	if($canbo->check_dm_ham($id) || $doanra->check_dm_ham($id) || $doanvao->check_dm_ham($id)){
		$msg = '<div class="alert error">Không thể xoá... [Cán bộ công chức], [Đoàn ra]</div>';
	} else {
		$ham->id = $id;
		if($ham->delete()){
			transfers_to('ham.php?update=ok');
		} else {
			$msg = 'Không thể xoá';
		}
	}
}
if(isset($_POST['submit'])){
	$id = isset($_POST['id']) ? $_POST['id'] : '';
	$tenham = isset($_POST['tenham']) ? $_POST['tenham'] : '';
	$ham->ten = $tenham;
	$ham->id_user = $users->get_userid();
	if($id){
		//edit
		$ham->id = $id;
		if($ham->edit()){
			transfers_to('ham.php?update=ok');
		}
	} else {
		//insert
		$_id = new MongoId(); $ham->id = $_id;
		if($ham->check_exists()){
			$msg = '<div class="alert error">Tên quốc gia đã tồn tại.</div>';
		} else {
			if($ham->insert()){
				if($url) transfers_to($url);
				else transfers_to('ham.php?update=ok');
			}
		}
	}
}

if($id){
	$ham->id = $id;
	$edit = $ham->get_one();
	$id = $edit['_id'];
	$tenham = $edit['ten'];
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
<h1><a href="index.php" class="nav-button transform"><span></span></a>&nbsp;Quản lý Hàm</h1>
<?php if(($users->getRole() & ADMIN) == ADMIN): ?>
<div style="padding:5px;">
<form action="<?php echo $_SERVER['REQUEST_URI']; ?>" method="POST" id="hamform">
	<a href="ham.php" class="button"><span class="mif-sync-problem"></span> Tải lại trang</a>
	<input type="hidden" name="id" id="id" value="<?php echo isset($id) ? $id : '';  ?>" />
	<div class="input-control text">
		<input type="text" name="tenham" id="tenham" value="<?php echo isset($tenham) ? $tenham : ''; ?>" class="edit-text" size="50" required oninvalid="InvalidMsg(this);" oninput="InvalidMsg(this);" placeholder="Tên Hàm" />
	</div>
	<button name="submit" id="submit" value="OK" class="button primary"><span class="mif-checkmark"></span> Cập nhật</button>
</form>
</div>
<?php endif; ?>
<?php if($ham_list && $ham_list->count() > 0): ?>
<table class="table striped hovered dataTable" data-role="datatable">
<thead>
	<tr>
		<th>STT</th>
		<th>Tên Hàm</th>
		<?php if($users->is_admin()) : ?>
			<th><span class="mif-bin"></span></th>
			<th><span class="mif-pencil"></span></th>
		<?php endif; ?>
	</tr>
</thead>
<tbody>
<?php
$i = 1;
foreach($ham_list as $qg){
	echo '<tr>';
	echo '<td>'.$i.'</td>';
	//echo '<td>'.$qg['_id'].'</td>';
	echo '<td>'.$qg['ten'].'</td>';
	if($users->is_admin()){
		echo '<td><a href="ham.php?id='.$qg['_id'].'&act=del" onclick="return confirm(\'Chắc chắc xoá?\');" title="Xoá"><span class="mif-bin"></span></td>';
		echo '<td><a href="ham.php?id='.$qg['_id'].'" title="Sủa"><span class="mif-pencil"></span></a></td>';
	}
	echo '</tr>';
	$i++;
}
?>
</tbody>
</table>
<?php endif; ?>
<?php require_once('footer.php'); ?>