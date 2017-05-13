<?php 
require_once('header.php');
if(!$users->is_admin()){
	echo '<h2>Bạn không có quyền...! <a href="index.php">Trở về</a></h2>';
	require_once('footer.php');
	exit();
}
$dmdoanvao = new DMDoanVao();$doanvao = new DoanVao();
$dmdoanvao_list = $dmdoanvao->get_all_list();
$id = isset($_GET['id']) ? $_GET['id'] : '';
$act = isset($_GET['act']) ? $_GET['act'] : '';
$url = isset($_GET['url']) ? $_GET['url'] : '';
$update = isset($_GET['update']) ? $_GET['update'] : ''; 
if($update=='ok'){ $msg = 'Cập nhật thành công'; }
if($update=='no'){ $msg = 'Không thể cập nhật'; }
if($id && $act=='del'){
	if($doanvao->check_dm_dmdoanvao($id)){
		$msg = 'Không thể xoá...[Đoàn vào]';
	} else {
		$dmdoanvao->id = $id;
		if($dmdoanvao->delete()){
			transfers_to('danhmucdoanvao.php?update=ok');
		} else {
			$msg = 'Không thể xoá';
		}
	}
}
if(isset($_POST['submit'])){
	$id = isset($_POST['id']) ? $_POST['id'] : '';
	$ten = isset($_POST['ten']) ? $_POST['ten'] : '';
	$dmdoanvao->ten = $ten;
	$dmdoanvao->id_user = $users->get_userid();
	if($id){
		//edit
		$dmdoanvao->id = $id;
		if($dmdoanvao->edit()){
			transfers_to('danhmucdoanvao.php?update=ok');
		}
	} else {
		//insert
		$_id = new MongoId(); $dmdoanvao->id = $_id;
		if($dmdoanvao->check_exists()){
			$msg = '<div class="alert error">Tên Đoàn vào đã tồn tại.</div>';
		} else {
			if($dmdoanvao->insert()){
				if($url) transfers_to($url);
				else transfers_to('danhmucdoanvao.php?update=ok');
			}
		}
	}
}

if($id){
	$dmdoanvao->id = $id;
	$edit = $dmdoanvao->get_one();
	$id = $edit['_id'];
	$ten = $edit['ten'];
}	
?>
<script type="text/javascript" src="js/html5.messages.js"></script>
<script type="text/javascript" src="js/jquery.dataTables.min.js"></script>
<script type="text/javascript">
	$(document).ready(function(){
		<?php if(isset($msg) && $msg): ?>
			$.Notify({type: 'alert', caption: 'Thông báo', content: <?php echo "'".$msg."'"; ?>});
		<?php endif; ?>
		$("#danhmuc_list").dataTable();
	});
</script>
<h1><a href="index.php" class="nav-button transform"><span></span></a>&nbsp;Quản lý Danh mục tên Đoàn vào</h1>
<?php if(($users->getRole() & ADMIN) == ADMIN): ?>
<div style="padding:5px;">
<form action="<?php echo $_SERVER['REQUEST_URI']; ?>" method="POST" id="dmdoanvaoform">
	<a href="danhmucdoanvao.php" class="button"><span class="mif-sync-problem"></span> Tải lại trang</a>
	<input type="hidden" name="id" id="id" value="<?php echo isset($id) ? $id : '';  ?>" />
	<div class="input-control text" style="width: 400px;">
		<input type="text" name="ten" id="ten" value="<?php echo isset($ten) ? $ten : ''; ?>" class="edit-text" size="50" required oninvalid="InvalidMsg(this);" oninput="InvalidMsg(this);" placeholder="Tên Đoàn vào"/>
	</div>
	<button name="submit" id="submit" value="OK" class="button primary"><span class="mif-checkmark"></span> Cập nhật</button>
</form>
</div>
<?php endif; ?>
<?php if($dmdoanvao_list && $dmdoanvao_list->count() > 0): ?>
<table class="table border bordered striped hovered dataTable" id="danhmuc_list">
<thead>
	<tr>
		<th>STT</th>
		<th>Tên Đoàn vào</th>
		<?php if(($users->getRole() & ADMIN) == ADMIN) : ?>
			<th><span class="mif-bin"></span></th>
			<th><span class="mif-pencil"></span></th>
		<?php endif; ?>
	</tr>
</thead>
<tbody>
<?php
$i = 1;
foreach($dmdoanvao_list as $qg){
	echo '<tr>';
	echo '<td>'.$i.'</td>';
	//echo '<td>'.$qg['_id'].'</td>';
	echo '<td>'.$qg['ten'].'</td>';
	if(($users->getRole() & ADMIN) == ADMIN){
		echo '<td><a href="danhmucdoanvao.php?id='.$qg['_id'].'&act=del" onclick="return confirm(\'Chắc chắc xoá?\');" title="Xoá"><span class="mif-bin"></span></td>';
		echo '<td><a href="danhmucdoanvao.php?id='.$qg['_id'].'" title="Sủa"><span class="mif-pencil"></span></a></td>';
	}
	echo '</tr>';
	$i++;
}
?>
</tbody>
</table>
<?php endif; ?>
<?php require_once('footer.php'); ?>