<?php 
require_once('header.php');
if(!$users->is_admin()){
	echo '<h2>Bạn không có quyền...! <a href="index.php">Trở về</a></h2>';
	require_once('footer.php');
	exit();
}
$linhvuc = new LinhVuc(); $donvi = new DonVi();
$linhvuc_list = $linhvuc->get_all_list();
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
	if($donvi->check_dm_linhvuc($id)){
		$msg = 'Không thể xoá... [canbo]';
	} else {
		$linhvuc->id = $id;
		if($linhvuc->delete()){
			transfers_to('linhvuc.php?update=ok');
		} else { $msg = 'Không thể xoá'; }
	}
}
if(isset($_POST['submit'])){
	$id = isset($_POST['id']) ? $_POST['id'] : '';
	$tenlinhvuc = isset($_POST['tenlinhvuc']) ? $_POST['tenlinhvuc'] : '';
	$linhvuc->id = $id;
	$linhvuc->ten = $tenlinhvuc;
	$linhvuc->id_user = $users->get_userid();
	if($id){
		//edit
		if($linhvuc->edit()){
			transfers_to('linhvuc.php?update=ok');
		}
	} else {
		//insert
		if($linhvuc->check_exists()){
			$msg = 'Tên ngành nghề đã tồn tại.';
		} else {
			if($linhvuc->insert()){
				if($url) transfers_to($url);
				else transfers_to('linhvuc.php');
			}
		}
	}
}

if($id){
	$linhvuc->id = $id;
	$edit = $linhvuc->get_one();
	$id = $edit['_id'];
	$tenlinhvuc = $edit['ten'];
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
<h1><a href="index.php" class="nav-button transform"><span></span></a>&nbsp;Quản lý Lĩnh Vực</h1>
<?php if(($users->getRole() & ADMIN) == ADMIN): ?>
<div style="padding:5px;">
<form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="POST" id="linhvucform">
	<a href="linhvuc.php" class="button"><span class="mif-sync-problem"></span> Tải lại trang</a>
	<input type="hidden" name="id" id="id" value="<?php echo isset($id) ? $id : '';  ?>" />
	<div class="input-control text">
		<input type="text" name="tenlinhvuc" id="tenlinhvuc" value="<?php echo isset($tenlinhvuc) ? $tenlinhvuc : ''; ?>" class="edit-text" size="50" required oninvalid="InvalidMsg(this);" oninput="InvalidMsg(this);" placeholder="Tên Lĩnh vực" />
	</div>
	<button name="submit" id="submit" value="OK" class="button primary"><span class="mif-checkmark"></span> Cập nhật</button>
</form>
</div>
<?php endif; ?>
<?php if($linhvuc_list && $linhvuc_list->count() > 0): ?>
<table class="table striped hovered dataTable" data-role="datatable">
<thead>
	<tr>
		<th>STT</th>
		<!--<th>#</th>-->
		<th>Tên Lĩnh vực</th>
		<?php if(($users->getRole() & ADMIN) == ADMIN) : ?>
			<th><span class="mif-bin"></span></th>
			<th><span class="mif-pencil"></span></th>
		<?php endif; ?>
	</tr>
</thead>
	<?php
	$i = 1;
	foreach($linhvuc_list as $nn){
		echo '<tr">';
		echo '<td>'.$i.'</td>';
		//echo '<td>'.$nn['_id'].'</td>';
		echo '<td>'.$nn['ten'].'</td>';
		if(($users->getRole() & ADMIN) == ADMIN){
			echo '<td><a href="linhvuc.php?id='.$nn['_id'].'&act=del" onclick="return confirm(\'Chắc chắc xoá?\');"><span class="mif-bin"></span></a></td>';
			echo '<td><a href="linhvuc.php?id='.$nn['_id'].'"><span class="mif-pencil"></span></a></td>';
		}
		echo '</tr>';
		$i++;
	}
	?>
</table>
<?php endif; ?>
<?php require_once('footer.php'); ?>