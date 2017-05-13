<?php 
require_once('header.php');
if(!$users->is_admin()){
	echo '<h2>Bạn không có quyền...! <a href="index.php">Trở về</a></h2>';
	require_once('footer.php');
	exit();
}
$kinhphi = new KinhPhi();$doanra= new DoanRa();
$kinhphi_list = $kinhphi->get_all_list();
$id = isset($_GET['id']) ? $_GET['id'] : '';
$act = isset($_GET['act']) ? $_GET['act'] : '';
$url = isset($_GET['url']) ? $_GET['url'] : '';
$update = isset($_GET['update']) ? $_GET['update'] : ''; 
if($update=='ok'){ $msg = 'Cập nhật thành công'; }
if($update=='no'){ $msg = 'Không thể cập nhật'; }
if($id && $act=='del'){
	//if($congdan->check_dm_kinhphi($id)){
	if($doanra->check_dm_kinhphi($id)){ 
		$msg = 'Không thể xoá... [Đoàn ra]';
	} else {
		$kinhphi->id = $id;
		if($kinhphi->delete()){
			transfers_to('kinhphi.php?update=ok');
		} else {
			$msg = 'Không thể xoá';
		}
	}
}
if(isset($_POST['submit'])){
	$id = isset($_POST['id']) ? $_POST['id'] : '';
	$ten = isset($_POST['ten']) ? $_POST['ten'] : '';
	$kinhphi->ten = $ten;
	$kinhphi->id_user = $users->get_userid();
	if($id){
		//edit
		$kinhphi->id = $id;
		if($kinhphi->edit()){
			transfers_to('kinhphi.php?update=ok');
		}
	} else {
		//insert
		$_id = new MongoId(); $kinhphi->id = $_id;
		if($kinhphi->check_exists()){
			$msg = '<div class="alert error">Tên Chức vụ đã tồn tại.</div>';
		} else {
			if($kinhphi->insert()){
				if($url) transfers_to($url);
				else transfers_to('kinhphi.php?update=ok');
			}
		}
	}
}

if($id){
	$kinhphi->id = $id;
	$edit = $kinhphi->get_one();
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
<h1><a href="index.php" class="nav-button transform"><span></span></a>&nbsp;Quản lý Danh mục Kinh Phí</h1>
<?php if(($users->getRole() & ADMIN) == ADMIN): ?>
<div style="padding:5px;">
<form action="<?php echo $_SERVER['REQUEST_URI']; ?>" method="POST" id="kinhphiform">
	<a href="kinhphi.php" class="button"><span class="mif-sync-problem"></span> Tải lại trang</a>
	<input type="hidden" name="id" id="id" value="<?php echo isset($id) ? $id : '';  ?>" />
	<div class="input-control text">
		<input type="text" name="ten" id="ten" value="<?php echo isset($ten) ? $ten : ''; ?>" class="edit-text" size="50" required oninvalid="InvalidMsg(this);" oninput="InvalidMsg(this);" placeholder="Tên Kinh phí" />
	</div>
	<button name="submit" id="submit" value="OK" class="button primary"><span class="mif-checkmark"></span> Cập nhật</button>
</form>
</div>
<?php endif; ?>
<?php if($kinhphi_list && $kinhphi_list->count() > 0): ?>
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
foreach($kinhphi_list as $kp){
	echo '<tr>';
	echo '<td>'.$i.'</td>';
	//echo '<td>'.$qg['_id'].'</td>';
	echo '<td>'.$kp['ten'].'</td>';
	if(($users->getRole() & ADMIN) == ADMIN){
		echo '<td><a href="kinhphi.php?id='.$kp['_id'].'&act=del" onclick="return confirm(\'Chắc chắc xoá?\');" title="Xoá"><span class="mif-bin"></span></td>';
		echo '<td><a href="kinhphi.php?id='.$kp['_id'].'" title="Sủa"><span class="mif-pencil"></span></a></td>';
	}
	echo '</tr>';
	$i++;
}
?>
</tbody>
</table>
<?php endif; ?>
<?php require_once('footer.php'); ?>