<?php 
require_once('header.php');
if(!$users->is_admin()){
	echo '<h2>Bạn không có quyền...! <a href="index.php">Trở về</a></h2>';
	require_once('footer.php');
	exit();
}
$phanloaidonvi = new PhanLoaiDonVi(); $donvi = new DonVi();
$phanloaidonvi_list = $phanloaidonvi->get_all_list();
$id = isset($_GET['id']) ? $_GET['id'] : '';
$act = isset($_GET['act']) ? $_GET['act'] : '';
$url = isset($_GET['url']) ? $_GET['url'] : ''; 
$update = isset($_GET['update']) ? $_GET['update'] : ''; 
if($update=='ok'){
	$msg = 'Cập nhật thành công';
}
if($update=='no'){
	$msg ='Không thể cập nhật nhật';
}
if($id && $act=='del'){
	if($donvi->check_dm_phanloaidonvi($id)){
		$msg = 'Không thể xoá... [canbo]';
	} else {
		$phanloaidonvi->id = $id;
		if($phanloaidonvi->delete()){
			transfers_to('phanloaidonvi.php?update=ok');
		} else { $msg = 'Không thể xoá'; }
	}
}
if(isset($_POST['submit'])){
	$id = isset($_POST['id']) ? $_POST['id'] : '';
	$tenphanloaidonvi = isset($_POST['tenphanloaidonvi']) ? $_POST['tenphanloaidonvi'] : '';
	$phanloaidonvi->id = $id;
	$phanloaidonvi->ten = $tenphanloaidonvi;
	$phanloaidonvi->id_user = $users->get_userid();
	if($id){
		//edit
		if($phanloaidonvi->edit()){
			transfers_to('phanloaidonvi.php?update=ok');
		}
	} else {
		//insert
		if($phanloaidonvi->check_exists()){
			$msg = 'Tên ngành nghề đã tồn tại.';
		} else {
			if($phanloaidonvi->insert()){
				if($url) transfers_to($url);
				else transfers_to('phanloaidonvi.php');
			}
		}
	}
}

if($id){
	$phanloaidonvi->id = $id;
	$edit = $phanloaidonvi->get_one();
	$id = $edit['_id'];
	$tenphanloaidonvi = $edit['ten'];
}	
?>
<link rel="stylesheet" type="text/css" href="css/jquery.dataTables.css" />
<script type="text/javascript" src="js/jquery.dataTables.min.js"></script>
<script type="text/javascript" src="js/html5.messages.js"></script>
<script type="text/javascript" src="js/jquery.setcase.js"></script>
<script type="text/javascript">
	$(document).ready(function(){
		$("input[type='text']").toCapitalize();
		<?php if(isset($msg) && $msg): ?>
			$.Notify({type: 'alert', caption: 'Thông báo', content: <?php echo "'".$msg."'"; ?>});
		<?php endif; ?>
	});
</script>
<h1><a href="index.php" class="nav-button transform"><span></span></a>&nbsp;Quản lý Phân Loại tổ chức</h1>
<?php if(($users->getRole() & ADMIN) == ADMIN): ?>
<div style="padding:5px;">
<form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="POST" id="phanloaidonviform">
	<a href="phanloaidonvi.php" class="button"><span class="mif-sync-problem"></span> Tải lại trang</a>
	<input type="hidden" name="id" id="id" value="<?php echo isset($id) ? $id : '';  ?>" />
	<div class="input-control text">
		<input type="text" name="tenphanloaidonvi" id="tenphanloaidonvi" value="<?php echo isset($tenphanloaidonvi) ? $tenphanloaidonvi : ''; ?>" class="edit-text" size="50" required oninvalid="InvalidMsg(this);" oninput="InvalidMsg(this);" placeholder="Tên Phân loại tổ chức" />
	</div>
	<button name="submit" id="submit" value="OK" class="button primary"><span class="mif-checkmark"></span> Cập nhật</button>
</form>
</div>
<?php endif; ?>
<?php if($phanloaidonvi_list && $phanloaidonvi_list->count() > 0): ?>
<table class="table striped hovered dataTable" data-role="datatable">
<thead>
	<tr>
		<th>STT</th>
		<!--<th>#</th>-->
		<th>Phân loại đơn vị</th>
		<?php if(($users->getRole() & ADMIN) == ADMIN) : ?>
			<th><span class="mif-bin"></span></th>
			<th><span class="mif-pencil"></span></th>
		<?php endif; ?>
	</tr>
</thead>
	<?php
	$i = 1;
	foreach($phanloaidonvi_list as $nn){
		echo '<tr">';
		echo '<td>'.$i.'</td>';
		//echo '<td>'.$nn['_id'].'</td>';
		echo '<td>'.$nn['ten'].'</td>';
		if(($users->getRole() & ADMIN) == ADMIN){
			echo '<td><a href="phanloaidonvi.php?id='.$nn['_id'].'&act=del" onclick="return confirm(\'Chắc chắc xoá?\');"><span class="mif-bin"></span></a></td>';
			echo '<td><a href="phanloaidonvi.php?id='.$nn['_id'].'"><span class="mif-pencil"></span></a></td>';
		}
		echo '</tr>';
		$i++;
	}
	?>
</table>
<?php endif; ?>
<?php require_once('footer.php'); ?>