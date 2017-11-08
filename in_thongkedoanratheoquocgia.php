<?php
require_once('header_none.php');
$doanra = new DoanRa();$quocgia=new QuocGia();
$quocgia_list = $quocgia->get_all_list();

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
        array_push($query, array('$or' => array(array('ngaydi' => array('$gte' => $start_date)), array('ngaydi' => array('$lte' => $end_date)))));
		//array_push($query, array('ngaydi' => array('$gte' => $start_date)));
		//array_push($query, array('ngayve' => array('$lte' => $end_date)));
		//$query = array('$and' => $query);
		$danhsachquocgia = $id_quocgia ? $quocgia->get_list_condition(array('_id' => new MongoId($id_quocgia))) : $quocgia->get_all_list();
	}
}
?>
<!DOCTYPE html>
<html>
<head lang="en">
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <meta name="description" content="Phần mềm quản lý Lãnh sự , Sở Ngoại vụ.">
    <meta name="keywords" content="Phần mêm, Lãnh sự, Sở NGoại vụ An Giang">
    <meta name="author" content="Trung tâm Tin học Trường Đai học An Giang, 18 Ung Văn Khiêm, Tp Long Xuyên, An Giang">
    <link rel='shortcut icon' type='image/x-icon' href="images/favicon.ico" />
    <title>Phần mềm Quản lý Lãnh sự - Sở Ngoại vụ An Giang</title>
    <link href="css/metro.css" rel="stylesheet">
    <link href="css/metro-icons.css" rel="stylesheet">
    <link href="css/metro-responsive.css" rel="stylesheet">
    <link href="css/metro-schemes.css" rel="stylesheet">
    <script src="js/jquery-2.1.3.min.js"></script>
    <script src="js/metro.js"></script>
</head>
<body>
<div class="place-left align-center">
	<b>UBND TỈNH AN GIANG <br />
	SỞ NGOẠI VỤ</b> <br />_____________

</div>
<div class="place-right align-center">
	<b>CỘNG HOÀ XÃ HỘI CHỦ NGHĨA VIỆT NAM <br /> Độc lập - Tự do - Hạnh phúc</b> <br />_______________________________<br />
	<br /><i>An Giang, ngày <?php echo date("d"); ?> tháng <?php echo date("m"); ?> năm <?php echo date("Y"); ?></i>
</div>
<div style="clear: both;padding-top:20px;">
<h4 class="align-center">THỐNG KÊ ĐOÀN RA THEO QUỐC GIA</h4>
<h5 class="align-center">Từ ngày: <b><?php echo $tungay; ?></b> Đến ngày: <b><?php echo $denngay; ?></b></h5>
</div>

<?php if(isset($danhsachquocgia) && $danhsachquocgia): ?>
<table class="table border bordered striped">
	<thead>
		<tr>
			<th>STT</th>
			<th>Tên Quốc gia</th>
			<th>Lượt xuất cảnh</th>
		</tr>
	</thead>
	<tbody>
	<?php
	$i =1;
	foreach ($danhsachquocgia as $ds) {
		$q = $query;
		array_push($q, array('id_quocgia' => $ds['_id']->{'$id'}));
		$count = $doanra->get_list_condition(array('$and' => $q))->count();
        if($count){
    		echo '<tr>
    			<td>'.$i.'</td>
    			<td>'.$ds['ten'].'</td>
    			<td>'.$count.'</td>
    		</tr>';	$i++;
        }
	}
	?>
	</tbody>
</table>
<?php endif; ?>
</body>
</html>
