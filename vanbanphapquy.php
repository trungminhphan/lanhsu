<?php
require_once('header.php');
$vanbanphapquy = new VanBanPhapQuy();
$list = $vanbanphapquy->get_all_list();
?>
<script type="text/javascript" src="js/jquery.dataTables.min.js"></script>
<h1><a href="index.php" class="nav-button transform"><span></span></a>&nbsp;Quản lý Văn bản pháp quy</h1>
<a href="themvanbanphapquy.php" class="button primary"><span class="mif-plus"></span> Thêm Văn bản pháp quy</a>
<?php if($list): ?>
<table class="table striped hovered dataTable" data-role="datatable">
	<thead>
		<tr>
			<th>STT</th>
			<th>Số văn bản</th>
			<th>Trích yếu</th>
			<th><span class="mif-bin"></span></th>
			<th><span class="mif-pencil"></span></th>
		</tr>
	</thead>
	<tbody>
	<?php
	$i = 1;
	foreach ($list as $l) {
		echo '<tr>';
		echo '<td>'.$i.'</td>';
		echo '<td>'.$l['sovanban'].'</td>';
		echo '<td>'.$l['trichyeu'].'</td>';
		echo '<td><a href="themvanbanphapquy.php?id='.$l['_id'].'&act=del" onlick="return confirm(\'Chắc chắn xóa?\');"><span class="mif-bin"></span></a></td>';
		echo '<td><a href="themvanbanphapquy.php?id='.$l['_id'].'&act=edit"><span class="mif-pencil"></span></a></td>';
		echo '</tr>';$i++;
	}
	?>
	</tbody>
</table>
<?php endif; ?>
<?php require_once('footer.php'); ?>