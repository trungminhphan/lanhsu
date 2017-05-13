<?php 
require_once('header.php');
if(!$users->is_admin()){
	echo '<h2>Bạn không có quyền...! <a href="index.php">Trở về</a></h2>';
	require_once('footer.php');
	exit();
}
$nghenghiep = new NgheNghiep();
$nghenghiep_list = $nghenghiep->get_all_list();
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
	if($canbo->check_dm_nghenghiep($id)){
		$msg = 'Không thể xoá... [canbo]';
	} else {
		$nghenghiep->id = $id;
		if($nghenghiep->delete()){
			transfers_to('nghenghiep.php?update=ok');
		} else { $msg = 'Không thể xoá'; }
	}
}
if(isset($_POST['submit'])){
	$id = isset($_POST['id']) ? $_POST['id'] : '';
	$tennghenghiep = isset($_POST['tennghenghiep']) ? $_POST['tennghenghiep'] : '';
	$nghenghiep->id = $id;
	$nghenghiep->ten = $tennghenghiep;
	$nghenghiep->id_user = $users->get_userid();
	if($id){
		//edit
		if($nghenghiep->edit()){
			transfers_to('nghenghiep.php?update=ok');
		}
	} else {
		//insert
		if($nghenghiep->check_exists()){
			$msg = 'Tên ngành nghề đã tồn tại.';
		} else {
			if($nghenghiep->insert()){
				if($url) transfers_to($url);
				else transfers_to('nghenghiep.php');
			}
		}
	}
}

if($id){
	$nghenghiep->id = $id;
	$edit = $nghenghiep->get_one();
	$id = $edit['_id'];
	$tennghenghiep = $edit['ten'];
}	
?>

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
<h1><a href="index.php" class="nav-button transform"><span></span></a>&nbsp;Quản lý Ngành nghề</h1>
<?php if(($users->getRole() & ADMIN) == ADMIN): ?>
<div style="padding:5px;">
<form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="POST" id="nghenghiepform">
	<a href="quocgia.php" class="button"><span class="mif-sync-problem"></span> Tải lại trang</a>
	<input type="hidden" name="id" id="id" value="<?php echo isset($id) ? $id : '';  ?>" />
	<div class="input-control text">
		<input type="text" name="tennghenghiep" id="tennghenghiep" value="<?php echo isset($tennghenghiep) ? $tennghenghiep : ''; ?>" class="edit-text" size="50" required oninvalid="InvalidMsg(this);" oninput="InvalidMsg(this);" placeholder="Tên ngành nghề" />
	</div>
	<button name="submit" id="submit" value="OK" class="button primary"><span class="mif-checkmark"></span> Cập nhật</button>
</form>
</div>
<?php endif; ?>
<?php if($nghenghiep_list && $nghenghiep_list->count() > 0): ?>
<table class="table striped hovered dataTable" data-role="datatable">
<thead>
	<tr>
		<th>STT</th>
		<!--<th>#</th>-->
		<th>Tên Ngành nghề</th>
		<?php if(($users->getRole() & ADMIN) == ADMIN) : ?>
			<th><span class="mif-bin"></span></th>
			<th><span class="mif-pencil"></span></th>
		<?php endif; ?>
	</tr>
</thead>
	<?php
	$i = 1;
	foreach($nghenghiep_list as $nn){
		echo '<tr">';
		echo '<td>'.$i.'</td>';
		//echo '<td>'.$nn['_id'].'</td>';
		echo '<td>'.$nn['ten'].'</td>';
		if(($users->getRole() & ADMIN) == ADMIN){
			echo '<td><a href="nghenghiep.php?id='.$nn['_id'].'&act=del" onclick="return confirm(\'Chắc chắc xoá?\');"><span class="mif-bin"></span></a></td>';
			echo '<td><a href="nghenghiep.php?id='.$nn['_id'].'"><span class="mif-pencil"></span></a></td>';
		}
		echo '</tr>';
		$i++;
	}
	?>
</table>
<?php endif; ?>
<?php require_once('footer.php'); ?>