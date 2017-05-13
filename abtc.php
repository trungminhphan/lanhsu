<?php require_once('header.php');
$abtc = new ABTC();$abtc_list = $abtc->get_all_list();$quocgia = new QuocGia();
$canbo = new CanBo();$donvi=new DonVi();
$update = isset($_GET['update']) ? $_GET['update'] : '';
$msg = '';
if($update == 'ok') $msg = 'Xoá thành công';
if($update == 'no') $msg = 'Không thể xoá';
?>
<script type="text/javascript" src="js/jquery.dataTables.min.js"></script>
<script type="text/javascript">
	$(document).ready(function(){
		<?php if($msg) : ?>
        	$.Notify({type: 'alert', caption: 'Thông báo', content: '<?php echo $msg; ?>'});
    	<?php endif; ?>
	});
</script>
<h1><a href="index.php" class="nav-button transform"><span></span></a>&nbsp;Quản lý Cấp thẻ ABTC</h1>
<a href="themabtc.php" class="button primary"><span class="mif-plus"></span> Thêm Quản lý Cấp thẻ ABTC</a>
<?php if($abtc_list && $abtc_list->count() > 0): ?>
<table class="table striped hovered dataTable" data-role="datatable">
<thead>
	<tr>
		<th>STT</th>
		<th>Họ tên</th>
		<th>Đơn vị</th>
		<th>Văn bản xin phép</th>
		<th>Văn bản cho phép</th>
		<th>Số thẻ</th>
		<th><span class="mif-bin"></span></th>
		<th><span class="mif-pencil"></span></th>
	</tr>
</thead>
<tbody>
	<?php
		$i = 1;
		foreach ($abtc_list as $a) {
			if(isset($a['thongtinthanhvien'][0]['id_canbo']) && $a['thongtinthanhvien'][0]['id_canbo']){
				$canbo->id = $a['thongtinthanhvien'][0]['id_canbo']; $cb=$canbo->get_one();	
				$tencanbo = $cb['hoten'];
			} else {
				$tencanbo = '';
			}
			if(isset($a['thongtinthanhvien'][0]['id_donvi']) && $a['thongtinthanhvien'][0]['id_donvi']){
				$dv = $donvi->tendonvi($a['thongtinthanhvien'][0]['id_donvi']);
			} else { $dv = ''; }
			if(isset($a['thongtinthanhvien'][0]['sothe'])){
				$sothe = $a['thongtinthanhvien'][0]['sothe'];
			} else { $sothe = '';}
			echo '<tr>';
			echo '<td>'.$i.'</td>';
			echo '<td><a href="chitietabtc.php?id='.$a['_id'].'">'.$tencanbo.'</a></td>';
			echo '<td>'.$dv.'</td>';
			echo '<td>'.$a['congvanxinphep']['ten'].'</td>';
			echo '<td>'.$a['quyetdinhchophep']['ten'].'</td>';
			echo '<td>'.$sothe.'</td>';
			if($users->is_admin()){
				echo '<td><a href="themabtc.php?id='.$a['_id'].'&act=del" onclick="return confirm(\'Chắc chắc xoá?\');" title="Xoá"><span class="mif-bin"></span></td>';
			} else { echo '<td></td>'; }
			if($users->is_admin() || $users->get_userid() == $a['id_user']) {
				echo '<td><a href="themabtc.php?id='.$a['_id'].'&act=edit" title="Sửa"><span class="mif-pencil"></span></a></td>';
			} else { echo '<td></td>'; }
			echo '</tr>';
			$i++;
		}
	?>
</tbody>
</table>
<?php endif; ?>
<?php require_once('footer.php'); ?>