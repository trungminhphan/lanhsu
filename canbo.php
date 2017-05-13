<?php
require_once('header.php');
if(!$users->is_admin() && !$users->is_student()){
	echo '<h2>Bạn không có quyền...! <a href="index.php">Trở về</a></h2>';
	require_once('footer.php');
	exit();
}
$canbo = new CanBo();
$canbo_list = $canbo->get_all_list();
$update = isset($_GET['update']) ? $_GET['update'] : '';
if($update=='no') $msg = 'Không thể xoá [Đoàn ra].';
?>
<link href="css/style.css" rel="stylesheet">
<script type="text/javascript" src="js/jquery.dataTables.min.js"></script>
<script type="text/javascript">
    $(document).ready(function() {
	    $('#canbo_list').DataTable( {
	        "processing": true,
	        "serverSide": true,
	        "ajax": "get.dataTable_canbo.php"
	    } );
	<?php if(isset($msg) && $msg): ?>
        $.Notify({type: 'alert', caption: 'Thông báo', content: <?php echo "'". $msg . "'"; ?>});
    <?php endif; ?>
});
</script>
<h1><a href="index.php" class="nav-button transform"><span></span></a>&nbsp;Quản lý Cá Nhân</h1>
<a href="themcanbo.php" class="button primary"><span class="mif-plus"></span> Thêm Cá Nhân</a>

<table class="table striped hovered" id="canbo_list">
<thead>
	<tr>
		<th>ID</th>
		<th>CMND</th>
		<th>Passport</th>
		<th width="200">Họ tên</th>
		<th>Đơn vị</th>
		<th width="150">Chức vụ</th>
		<?php if(!$users->is_student()): ?>
		<th><span class="mif-bin"></span></th>
		<th><span class="mif-pencil"></span></th>
		<?php endif; ?>
	</tr>
</thead>
<tbody></tbody>
<tfoot>
	<tr>
		<th>ID</th>
		<th>CMND</th>
		<th>Passport</th>
		<th>Họ tên</th>
		<th>Đơn vị</th>
		<th>Chức vụ</th>
		<?php if(!$users->is_student()): ?>
		<th><span class="mif-bin"></span></th>
		<th><span class="mif-pencil"></span></th>
		<?php endif; ?>
	</tr>
</tfoot>

</table>
<?php require_once('footer.php'); ?>

