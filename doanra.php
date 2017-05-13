<?php
require_once('header.php');
$doanra = new DoanRa();$donvi = new DonVi();$canbo = new CanBo();
$doanra_list = $doanra->get_all_list();
$update = isset($_GET['update']) ? $_GET['update'] : '';
$msg = '';
if($update == 'ok') $msg = 'Xoá thành công';
if($update == 'no') $msg = 'Không thể xoá';
?>
<script type="text/javascript" src="js/jquery.dataTables.min.js"></script>
<script type="text/javascript">
	$(document).ready(function(){
		$("#doanra_list").dataTable();
		<?php if(isset($msg) && $msg) : ?>
        	$.Notify({type: 'alert', caption: 'Thông báo', content: <?php echo "'".$msg."'"; ?>});
    	<?php endif; ?>
	});
</script>
<h1><a href="index.php" class="nav-button transform"><span></span></a>&nbsp;Quản lý Đoàn Ra</h1>
<a href="themdoanra.php" class="button primary"><span class="mif-plus"></span> Thêm Quản lý Đoàn Ra</a>
<?php if($doanra_list && $doanra_list->count() > 0): ?>
<table class="table striped hovered" id="doanra_list">
<thead>
	<tr>
		<th>STT</th>
		<th>Tên trưởng đoàn</th>
		<th>Ngày đi</th>
		<th>Ngày về</th>
		<th>Văn bản xin phép</th>
		<th>Văn bản cho phép</th>
		<th>Đơn vị</th>
		<th><span class="mif-bin"></span></th>
		<th><span class="mif-pencil"></span></th>
	</tr>
</thead>
<tbody>
	<?php
		$i = 1;
		foreach ($doanra_list as $dr) {
			if(isset($dr['congvanxinphep']['id_donvi'][0]) && $dr['congvanxinphep']['id_donvi'][0]){
				$donvi->id = $dr['congvanxinphep']['id_donvi'][0];$dv = $donvi->get_one();
				$tendonvi=$dv['ten'];
			} else {$tendonvi='';}
			if($dr['danhsachdoan'] && isset($dr['danhsachdoan'][0]['id_canbo']) && $dr['danhsachdoan'][0]['id_canbo']){
				$canbo->id = $dr['danhsachdoan'][0]['id_canbo'];$cb=$canbo->get_one();
				$tentruongdoan = $cb['hoten'];
			} else { $tentruongdoan = '';}
			echo '<tr>';
			echo '<td>'.$i.'</td>';
			echo '<td>'.$tentruongdoan.'</td>';
			echo '<td>'.($dr['ngaydi'] ? date("d/m/Y",$dr['ngaydi']->sec) : '').'</td>';
			echo '<td>'.($dr['ngayve'] ? date("d/m/Y",$dr['ngayve']->sec) : '').'</td>';
			echo '<td>'.$dr['congvanxinphep']['ten'].'</td>';
			echo '<td><a href="chitietdoanra.php?id='.$dr['_id'].'">'.$dr['quyetdinhchophep']['ten'].'</a></td>';
			echo '<td>'.$tendonvi.'</td>';
			if($users->is_admin()){
				echo '<td><a href="themdoanra.php?id='.$dr['_id'].'&act=del" onclick="return confirm(\'Chắc chắc xoá?\');" title="Xoá"><span class="mif-bin"></span></td>';
			} else { echo '<td><span class="mif-bin"></span></td>'; }
			if($users->is_admin() || $users->get_userid() == $dr['id_user']) {
			echo '<td><a href="themdoanra.php?id='.$dr['_id'].'&act=edit" title="Sửa"><span class="mif-pencil"></span></a></td>';
			} else { echo '<td><span class="mif-pencil"></span></td>'; }
			echo '</tr>';
			$i++;
		}
	?>
</tbody>
</table>
<?php endif; ?>
<?php require_once('footer.php'); ?>

