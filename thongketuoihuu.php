<?php
require_once('header.php');
$canbo = new CanBo();$donvi = new DonVi();
$query = array('$or' => array(array('id_quoctich' => array('$eq' => '56f9fd7732341c4008002015')), array('id_quoctich' => array('$eq' => new MongoId('56f9fd7732341c4008002015')))));
$canbo_list = $canbo->get_list_condition_huu($query);
?>
<script type="text/javascript" src="js/jquery.dataTables.min.js"></script>
<h1><a href="index.php" class="nav-button transform"><span></span></a>&nbsp;Thống kê Cá nhân sắp hưu</h1>
<script type="text/javascript">
	$(document).ready(function(){
		$("#list").dataTable();
	});
</script>
<?php if($canbo_list) : ?>
<a href="export_thongketuoihuu.php" class="button success"><span class="mif mif-file-excel"></span> Xuất Excel</a>
<table class="table striped hovered border bordered" id="list">
	<thead>
		<tr>
			<th>STT</th>
			<th>Họ tên</th>
			<th>Giới tính</th>
			<th>Ngày sinh</th>
			<th>Đơn vị</th>
			<th>Tuổi</th>
			<th>Số năm sắp hưu</th>
		</tr>
	</thead>
	<tbody>
	<?php
	$i = 1;
	foreach ($canbo_list as $cb) {
		$ngaysinh = $cb['ngaysinh'] ? date("Y-m-d", $cb['ngaysinh']->sec) : '';
		if(isset($cb['donvi'][0]['id_donvi']) && $cb['donvi'][0]['id_donvi']){
			$tendonvi = $donvi->tendonvi($cb['donvi'][0]['id_donvi']);
		} else { $tendonvi = ''; }
		if($ngaysinh){
			$date1=date_create($ngaysinh);
			$date2=date_create(date("Y-m-d"));
			$diff=date_diff($date1,$date2);
			$songay = $diff->format("%a");
			$tuoi = round($songay/365, 0);
			if($cb['gioitinh'] == 'Nam'){
				$sonamsaphuu = 60 - $tuoi;
			} else {
				$sonamsaphuu = 55 - $tuoi;
			}
		} else {
			$tuoi = '';$sonamsaphuu = '';
		}
		echo '<tr>
			<td>'.$i.'</td>
			<td><a href="chitietcanbo.php?id='.$cb['_id'].'" target="_blank">'.$cb['hoten'].'</a></td>
			<td>'.$cb['gioitinh'].'</td>
			<td>'.($cb['ngaysinh'] ? date("d/m/Y", $cb['ngaysinh']->sec) : '').'</td>
			<td>'.$tendonvi.'</td>
			<td>'.$tuoi.'</td>
			<td>'.$sonamsaphuu.'</td>
		</tr>';$i++;
	}
	?>
	</tbody>
</table>
<?php endif; ?>
<?php require_once('footer.php'); ?>