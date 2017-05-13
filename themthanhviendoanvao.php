<?php
require_once('header.php');
$doanvao = new DoanVao();$donvi = new DonVi();$canbo = new CanBo();$chucvu=new ChucVu();
$dmdoanvao = new DMDoanVao();$ham = new Ham();$mucdich=new MucDich();
$id = isset($_GET['id']) ? $_GET['id'] : '';
$act = isset($_GET['act']) ? $_GET['act'] : '';
$key = isset($_GET['key']) ? $_GET['key'] : 0;
if($id && $act == 'del1'){
	$doanvao->id = $id; 
	if($doanvao->unset_danhsachdoan('danhsachdoan', $key) && $doanvao->pull_danhsachdoan('danhsachdoan', $key)){
		transfers_to('themthanhviendoanvao.php?id=' . $id);
	}
}
if($id && $act == 'del2'){
	$doanvao->id = $id; 
	if($doanvao->unset_danhsachdoan('danhsachdoan_2', $key) && $doanvao->pull_danhsachdoan('danhsachdoan_2', $key)){
		transfers_to('themthanhviendoanvao.php?id=' . $id);
	}
}
$doanvao->id = $id; $dv = $doanvao->get_one();
if(isset($dv['id_dmdoanvao']) && $dv['id_dmdoanvao']){
	$dmdoanvao->id = $dv['id_dmdoanvao']; $d = $dmdoanvao->get_one();
	$tendoanvao = $d['ten'];
} else { $tendoanvao = ''; }

if(isset($_POST['submit'])){
	$id = isset($_POST['id']) ? $_POST['id'] : '';
	$id_canbo = isset($_POST['id_canbo']) ? $_POST['id_canbo'] : '';
	$loaidanhsachdoan = isset($_POST['loaidanhsachdoan']) ? $_POST['loaidanhsachdoan'] : '';
	$a = explode('-', $id_canbo);
	$danhsachdoan = array('id_canbo' => $a[0] ? new MongoId($a[0]) : '', 'id_donvi' => explode(",",$a[1]), 'id_chucvu' => $a[2] ? new MongoId($a[2]) : '', 'id_ham' => $a[3] ? new MongoId($a[3]) : '');
	$doanvao->id = $id;
	$doanvao->danhsachdoan = $danhsachdoan;
	if($doanvao->push_danhsachdoan($loaidanhsachdoan)) transfers_to('themthanhviendoanvao.php?id=' . $id);
}

if(isset($_POST['sort'])){
	$doanvao->id = $id;
	$id_danhsachdoan = isset($_POST['id_danhsachdoan']) ? $_POST['id_danhsachdoan'] : '';
	$id_danhsachdoan_2 = isset($_POST['id_danhsachdoan_2']) ? $_POST['id_danhsachdoan_2'] : '';
	if($id_danhsachdoan){
		$doanvao->unset_danhsachdoan_all('danhsachdoan');
		foreach($id_danhsachdoan as $key => $value){
			$a1 = explode('-', $value);
			$danhsachdoan = array('id_canbo' => $a1[0] ? new MongoId($a1[0]) : '', 'id_donvi' => explode(",",$a1[1]), 'id_chucvu' => $a1[2] ? new MongoId($a1[2]) : '', 'id_ham' => $a1[3] ? new MongoId($a1[3]) : '');
			$doanvao->danhsachdoan = $danhsachdoan;
			$doanvao->push_danhsachdoan('danhsachdoan');
		}
	}
	if($id_danhsachdoan_2){
		$doanvao->unset_danhsachdoan_all('danhsachdoan_2');
		foreach($id_danhsachdoan_2 as $key2 => $value2){
			$a2 = explode('-', $value2);
			$danhsachdoan_2 = array('id_canbo' => $a2[0] ? new MongoId($a2[0]) : '', 'id_donvi' => explode(",",$a2[1]), 'id_chucvu' => $a2[2] ? new MongoId($a2[2]) : '', 'id_ham' => $a2[3] ? new MongoId($a2[3]) : '');
			$doanvao->danhsachdoan_2 = $danhsachdoan_2;
			$doanvao->push_danhsachdoan('danhsachdoan_2');
		}
	}
	transfers_to('themthanhviendoanvao.php?id=' . $id);	
}
?>
<script type="text/javascript" src="js/select2.min.js"></script>
<script type="text/javascript" src="js/doanvao.js"></script>
<script type="text/javascript" src="js/RowSorter.js"></script>
<script type="text/javascript">
	$(document).ready(function(){
		$("#loaidanhsachdoan").select2();
		thanhviendoan();
		RowSorter("#thanhvien_list");
		RowSorter("#thanhvien_list_2");
	});
</script>
<h1><a href="chitietdoanvao.php?id=<?php echo $id; ?>" class="nav-button transform"><span></span></a>&nbsp;Thêm thành viên Đoàn vào</h1>
<form action="<?php echo $_SERVER['REQUEST_URI']; ?>" method="POST" accept-charset="utf-8">
<input type="hidden" name="id" id="id" value="<?php echo isset($id) ? $id : ''; ?>">
Thành viên: 
	<div class="input-control select" style="width:300px;">
		<select name="id_canbo" id="id_canbo" class="thanhviendoan"></select>
	</div>
	<div class="input-control select" style="width:200px;">
		<select name="loaidanhsachdoan" id="loaidanhsachdoan">
			<option value="danhsachdoan">Danh sách đoàn 1</option>
			<option value="danhsachdoan_2">Danh sách đoàn 2</option>
		</select>
	</div>
	<button name="submit" id="submit" value="OK" class="button primary"><span class="mif-checkmark"></span> Cập nhật</button>
</form>
<hr />
<h3 class="align-center fg-red">Tên đoàn: <?php echo $tendoanvao; ?></h3>
<form action="<?php echo $_SERVER['REQUEST_URI']; ?>" method="POST" accept-charset="utf-8">
<h3>Danh sách 1</h3>
<table class="table striped border bordered hovered" id="thanhvien_list">
	<thead>
		<tr>
			<th>STT</th>
			<th>Họ tên</th>
			<th>Chức vụ</th>
			<th style="text-align:center;"><span class="mif mif-bin"></span></th>
		</tr>
	</thead>
	<tbody>
	<?php
	if($dv['danhsachdoan']){
		$i = 1;
		foreach ($dv['danhsachdoan'] as $k => $ds) {
			$canbo->id = $ds['id_canbo'];$cb = $canbo->get_one();
			if(isset($dv['id_dmdoanvao']) && $dv['id_dmdoanvao']){
				$dmdoanvao->id = $dv['id_dmdoanvao']; $d = $dmdoanvao->get_one();
				$tendoanvao = $d['ten'];
			} else { $tendoanvao = ''; }
			if(isset($ds['id_chucvu']) && $ds['id_chucvu']){
				$chucvu->id = $ds['id_chucvu']; $cv=$chucvu->get_one();
				$tenchucvu = $cv['ten'];
			} else { $tenchucvu = ''; }
			if(isset($ds['id_ham']) && $ds['id_ham']){
				$ham->id = $ds['id_ham']; $h = $ham->get_one(); $tenham = $h['ten'];
			} else { $tenham = ''; }
			$v = $ds['id_canbo'] . '-' . implode(",", $ds['id_donvi']) . '-' . $ds['id_chucvu'] . '-'.$ds['id_ham'];
			echo '<tr>
				<td>'.$i.'<input type="hidden" name="id_danhsachdoan[]" value="'.$v.'"></td>
				<td>'.$cb['hoten'].'</td>
				<td>'.$tenchucvu.'</td>
				<td class="align-center"><a href="themthanhviendoanvao.php?id='.$id.'&key='.$k.'&act=del1" onclick="return confirm(\'Chắc chắn muốn xoá?\');"><span class="mif mif-bin"></span></a></td>
			</tr>';	$i++;
		}
	}
?>
</tbody>
</table>

<h3>Danh sách 2</h3>
<table class="table striped border bordered hovered" id="thanhvien_list_2">
	<thead>
		<tr>
			<th>STT</th>
			<th>Họ tên</th>
			<th>Chức vụ</th>
			<th style="text-align:center;"><span class="mif mif-bin"></span></th>
		</tr>
	</thead>
	<tbody>
<?php
	if(isset($dv['danhsachdoan_2']) && $dv['danhsachdoan_2']){
		foreach ($dv['danhsachdoan_2'] as $k2 => $dv_2) {
			$canbo->id = $dv_2['id_canbo'];$cb = $canbo->get_one();
			//$donvi->id = $ds['id_donvi'][0]; $dv = $donvi->get_one();
			//$dvi = $donvi->tendonvi($ds_2['id_donvi']);
			$chucvu->id = $dv_2['id_chucvu']; $cv=$chucvu->get_one();
			if(isset($dv_2['id_ham']) && $dv_2['id_ham']) {
				$ham->id = $dv_2['id_ham']; $h=$ham->get_one();$tenham=$h['ten'];
			} else { $tenham = '';}
			$v2 = $dv_2['id_canbo'] . '-' . implode(",", $dv_2['id_donvi']) . '-' . $dv_2['id_chucvu'] . '-'.$dv_2['id_ham'];
			echo '<tr>
				<td>'.$i.'<input type="hidden" name="id_danhsachdoan_2[]" value="'.$v2.'"</td>
				<td>'.$cb['hoten'].'</td>
				<td>'.$cv['ten'].'</td>
				<td class="align-center"><a href="themthanhviendoanvao.php?id='.$id.'&key='.$k2.'&act=del2" onclick="return confirm(\'Chắc chắn muốn xoá?\');"><span class="mif mif-bin"></span></a></td>
			</tr>';	$i++;
		}	
	}
	?>
	</tbody>
</table>

<button type="submit" name="sort" class="button primary"><span class="mif-sync-problem"></span> Cập nhật sắp xếp</button>
</form>
<?php require_once('footer.php'); ?>
