<?php require_once('header.php');
$doanvao = new DoanVao();$donvi = new DonVi();$dmdoanvao = new DMDoanVao();$canbo = new CanBo();
$doanvao_list = $doanvao->get_all_list();
$update = isset($_GET['update']) ? $_GET['update'] : '';
$msg = '';
if($update == 'ok') $msg = 'Xoá thành công';
if($update == 'no') $msg = 'Không thể xoá';
?>
<script type="text/javascript" src="js/jquery.dataTables.min.js"></script>
<script type="text/javascript">
	$(document).ready(function(){
		$("#doanvao_list").dataTable();
		<?php if(isset($msg) && $msg) : ?>
        	$.Notify({type: 'alert', caption: 'Thông báo', content: '<?php echo $msg; ?>'});
    	<?php endif; ?>
	});
</script>
<h1><a href="index.php" class="nav-button transform"><span></span></a>&nbsp;Quản lý Đoàn Vào</h1>
<a href="themdoanvao.php" class="button primary"><span class="mif-plus"></span> Thêm Quản lý Đoàn Vào</a>
<a href="export_doanvao.php" class="button success"><span class="mif-file-excel"></span> Xuất Excel</a>
<?php if($doanvao_list && $doanvao_list->count() > 0): ?>
<table class="table striped hovered" id="doanvao_list">
<thead>
	<tr>
		<th>STT</th>
		<th>Tên Đoàn</th>
		<th>Tên trưởng đoàn</th>
		<th>Ngày đến</th>
		<th>Ngày đi</th>
		<th>Tên đơn vị</th>
		<th>Văn bản xin phép</th>
		<th>Văn bản cho phép</th>
		<th><span class="mif-bin"></span></th>
		<th><span class="mif-pencil"></span></th>
	</tr>
</thead>
<tbody>
	<?php
		$i = 1;
		foreach ($doanvao_list as $dv) {
			if(isset($dv['id_dmdoanvao']) && $dv['id_dmdoanvao']){
				//$dmdoanvao->id = $dv['id_dmdoanvao'];$dm=$dmdoanvao->get_one();
				$tendoanvao = $dmdoanvao->tendoan($dv['id_dmdoanvao']);
			} else {
				$tendoanvao = '';
			}
			if(isset($dv['danhsachdoan']) && $dv['danhsachdoan']){
				$canbo->id = $dv['danhsachdoan'][0]['id_canbo'];$cb=$canbo->get_one();
				$tentruongdoan = $cb['hoten'];
			} else { $tentruongdoan = '';}
			if($dv['congvanxinphep']['id_donvi']){
				$tendonvi = $donvi->tendonvi($dv['congvanxinphep']['id_donvi']);
			} else { $tendonvi = ''; }
			echo '<tr>';
			echo '<td>'.$i.'</td>';
			echo '<td><a href="chitietdoanvao.php?id='.$dv['_id'].'">'.$tendoanvao.'</a></td>';
			echo '<td>'.$tentruongdoan.'</td>';
			echo '<td>'.(isset($dv['ngayden']) ? date("d/m/Y", $dv['ngayden']->sec) : '').'</td>';
			echo '<td>'.(isset($dv['ngaydi']) ? date("d/m/Y", $dv['ngaydi']->sec) : '').'</td>';
			echo '<td>'.$tendonvi.'</td>';
			echo '<td><a href="chitietdoanvao.php?id='.$dv['_id'].'">'.$dv['congvanxinphep']['ten'].'</a></td>';
			echo '<td>'.(isset($dv['quyetdinhchophep']['ten']) ? $dv['quyetdinhchophep']['ten'] : '').'</td>';
			if($users->is_admin()){
				echo '<td><a href="themdoanvao.php?id='.$dv['_id'].'&act=del" onclick="return confirm(\'Chắc chắc xoá?\');" title="Xoá"><span class="mif-bin"></span></td>';
			} else { echo '<td></td>'; }
			if($users->is_admin() || $users->get_userid() == $dv['id_user']) {
				echo '<td><a href="themdoanvao.php?id='.$dv['_id'].'&act=edit" title="Sửa"><span class="mif-pencil"></span></a></td>';
			} else { echo '<td></td>'; }
			echo '</tr>';
			$i++;
		}
	?>
</tbody>
</table>
<?php endif; ?>
<?php require_once('footer.php'); ?>
