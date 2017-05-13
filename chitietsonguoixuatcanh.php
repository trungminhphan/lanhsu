<?php
require_once('header.php');
$donvi = new DonVi();$canbo = new CanBo();$doanra = new DoanRa();
if(isset($_GET['submit'])){
	$query = array();
	$id_donvi = isset($_GET['id_donvi']) ? $_GET['id_donvi'] : '';
	$tungay = isset($_GET['tungay']) ? $_GET['tungay'] : '';
	$denngay = isset($_GET['denngay']) ? $_GET['denngay'] : '';
	$id_quocgia = isset($_GET['id_quocgia']) ? $_GET['id_quocgia'] : '';
	$id_kinhphi = isset($_GET['id_kinhphi']) ? $_GET['id_kinhphi'] : '';
	$id_mucdich = isset($_GET['id_mucdich']) ? $_GET['id_mucdich'] : '';
	$start_date = new MongoDate(convert_date_dd_mm_yyyy($tungay));
	$end_date = new MongoDate(convert_date_dd_mm_yyyy($denngay));
	array_push($query, array('ngaydi' => array('$gte' => $start_date)));
	array_push($query, array('ngayve' => array('$lte' => $end_date)));
	if($id_donvi){
		//array_push($query, array('congvanxinphep.id_donvi.0' => $id_donvi));
		array_push($query, array('$or' => array(array('danhsachdoan.id_donvi.0' => $id_donvi), array('danhsachdoan_2.id_donvi.0' => $id_donvi))));
	} 
	if($id_kinhphi){
		array_push($query, array('id_kinhphi' => new MongoId($id_kinhphi)));
	}
	if($id_mucdich){
		array_push($query, array('id_mucdich' => new MongoId($id_mucdich)));
	}
	if($id_quocgia){
		array_push($query, array('id_quocgia' => $id_quocgia));
	}
	$donvi->id = $id_donvi; $dv = $donvi->get_one();
	$q = array('$and' => $query);
	$songuoi_list = $doanra->get_list_songuoi($q, $id_donvi);
}
?>
<h1><a href="index.php" class="nav-button transform"><span></span></a>&nbsp;Chi tiết số người xuất cảnh</h1>
<h3 class="align-center">Đơn vị: <?php echo $dv['ten']; ?></h3>

<?php if(isset($songuoi_list) && count($songuoi_list) > 0): ?>
<table class="table border bordered striped hovered">
	<thead>
		<tr>
			<th>STT</th>
			<th>Họ tên</th>
			<th>Số lượt</th>
		</tr>
	</thead>
	<tbody>
	<?php
	$i = 1;$total = 0;
	foreach ($songuoi_list as $key => $value) {
		$canbo->id = $value; $cb = $canbo->get_one();
		$count = $doanra->count_soluot_to_nguoi($q, $id_donvi, $value);
		$total += $count;
		echo '<tr>
			<td>'.$i.'</td>
			<td>'.$cb['hoten'].'</td>
			<td class="align-right"><a href="thongkedoanratheocanhan.php?id_canbo='.$value.'&'.$_SERVER['QUERY_STRING'].'" target="_blank">'.$count.'</a></td>
		</tr>';$i++;
	}
	?>
	</tbody>
	<tr>
		<th colspan="2">TỔNG CỘNG</th>
		<th class="align-right"><?php echo $total; ?></th>
	</tr>
</table>
<?php endif; ?>
<?php require_once('footer.php'); ?>