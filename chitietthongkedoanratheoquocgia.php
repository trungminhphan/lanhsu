<?php
require_once('header.php');
$doanra = new DoanRa();$quocgia=new QuocGia();$canbo = new CanBo();$kinhphi = new KinhPhi();
if(isset($_GET['submit'])){
	$query = array();
	$id_quocgia = isset($_GET['id_quocgia']) ? $_GET['id_quocgia'] : '';
	$tungay = isset($_GET['tungay']) ? $_GET['tungay'] : '';
	$denngay = isset($_GET['denngay']) ? $_GET['denngay'] : '';
	if(convert_date_dd_mm_yyyy($tungay) > convert_date_dd_mm_yyyy($denngay)){
		$msg = 'Chọn ngày sai';
	} else {
		$start_date = new MongoDate(convert_date_dd_mm_yyyy($tungay));
		$end_date = new MongoDate(convert_date_dd_mm_yyyy($denngay));
		array_push($query, array('ngaydi' => array('$gte' => $start_date)));
		array_push($query, array('ngaydi' => array('$lte' => $end_date)));
		//array_push($query, array('ngayve' => array('$lte' => $end_date)));
		array_push($query, array('id_quocgia' => $id_quocgia));
		$union_list = $doanra->get_list_condition(array('$and' => $query));

		$quocgia->id = $id_quocgia; $qg = $quocgia->get_one();
	}
}
?>
<h1><a href="index.php" class="nav-button transform"><span></span></a>&nbsp;Chi tiết thống kê Đoàn ra theo Quốc gia</h1>
<h3>Tên Quốc gia: <?php echo $qg['ten']; ?></h3>
<?php if(isset($union_list) && $union_list->count() > 0) : ?>
<table class="table border bordered striped">
	<thead>
		<tr>
			<th>STT</th>
			<th>Trưởng đoàn</th>
			<th>Văn bản xin phép</th>
			<th>Văn bản cho phép</th>
			<th>Ngày đi</th>
			<th>Ngày về</th>
			<th>Kinh phí</th>
			<th>Nội dung</th>
		</tr>
	</thead>
	<tbody>
	<?php
		$i = 1;
		foreach ($union_list as $u) {
			$canbo->id = $u['danhsachdoan'][0]['id_canbo']; $cb = $canbo->get_one();
			$congvanxinphep = $u['congvanxinphep']['ten'];
			$soquyetdinh = $u['quyetdinhchophep']['ten'];
			$ngaydi = $u['ngaydi'] ? date("d/m/Y", $u['ngaydi']->sec) : '';
			$ngayve = $u['ngayve'] ? date("d/m/Y", $u['ngayve']->sec) : '';

			if($u['id_kinhphi']){
				$kinhphi->id = $u['id_kinhphi']; $kp = $kinhphi->get_one();
				$tenkinhphi = $kp['ten'];
			} else { $tenkinhphi = ''; }
			echo '<tr>
				<td>'.$i.'</td>
				<td>'.$cb['hoten'].'</td>
				<td>'.$congvanxinphep.'</td>
				<td>'.$soquyetdinh.'</td>
				<td>'.$ngaydi.'</td>
				<td>'.$ngayve.'</td>
				<td>'.$tenkinhphi.'</td>
				<td>'.$u['noidung'].'</td>
			</tr>';$i++;
		}
	?>
	</tbody>
</table>
<?php endif; ?>
<?php require_once('footer.php');?>
