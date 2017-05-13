<?php
require_once('header.php');
$tintuc = new TinTuc();
$tintuc_list = $tintuc->get_all_list();
?>
<script type="text/javascript" src="js/jquery.dataTables.min.js"></script>
<h1><a href="index.php" class="nav-button transform"><span></span></a>&nbsp;Quản lý Tin lãnh sự</h1>
<a href="themtintuc.php" class="button primary"><span class="mif-plus"></span> Thêm tin lãnh sự</a>

<?php if($tintuc_list): ?>
<table class="table striped hovered dataTable" data-role="datatable">
	<thead>
		<tr>
			<th>STT</th>
			<th>Tiêu đề</th>
			<th>Ngày</th>
			<th><span class="mif-bin"></span></th>
			<th><span class="mif-pencil"></span></th>
		</tr>
	</thead>
	<tbody>
	<?php
	$i = 1;
	foreach ($tintuc_list as $tt) {
		echo '<tr>
			<td>'.$i.'</td>
			<td>'.$tt['tieude'].'</td>
			<td>'.date("d/m/Y", $tt['date_post']->sec).'</td>
			<td><a href="themtintuc.php?id='.$tt['_id'].'&act=del" onclick="return confirm(\'Chắn chắn muốn xóa?\');"><span class="mif-bin"></span></a></td>
			<td><a href="themtintuc.php?id='.$tt['_id'].'&act=edit"><span class="mif-pencil"></span></a></td>
		</tr>';$i++;
	}
	?>
	</tbody>
</table>
<?php endif;?>

<?php require_once('footer.php'); ?>