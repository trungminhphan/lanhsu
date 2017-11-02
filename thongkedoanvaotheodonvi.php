<?php
require_once('header.php');
$canbo = new CanBo();$doanvao=new DoanVao();$donvi = new DonVi();$quocgia=new QuocGia();
$linhvuc = new LinhVuc();$mucdich = new MucDich();
$id_donvi='';
if(isset($_GET['submit'])){
	$query = array();
	$id_donvi = isset($_GET['id_donvi']) ? $_GET['id_donvi'] : '';
	$tungay = isset($_GET['tungay']) ? $_GET['tungay'] : '';
	$denngay = isset($_GET['denngay']) ? $_GET['denngay'] : '';
	$id_mucdich = isset($_GET['id_mucdich']) ? $_GET['id_mucdich'] : '';
	$id_linhvuc = isset($_GET['id_linhvuc']) ? $_GET['id_linhvuc'] : '';
	if(convert_date_dd_mm_yyyy($tungay) > convert_date_dd_mm_yyyy($denngay)){
		$msg = 'Chọn ngày sai hoặc chưa chọn Đơn vị thống kê';
	} else {
		$start_date = new MongoDate(convert_date_dd_mm_yyyy($tungay));
		$end_date = new MongoDate(convert_date_dd_mm_yyyy($denngay));
		array_push($query, array('ngayden' => array('$gte' => $start_date)));
		array_push($query, array('ngaydi' => array('$lte' => $end_date)));
		if($id_donvi){
			array_push($query, array('congvanxinphep.id_donvi.0' => $id_donvi));
		}
		/*if($id_donvi){
			array_push($query, array('$or' => array(array('danhsachdoan.0.id_donvi.0' => $id_donvi), array('danhsachdoan_2.0.id_donvi.0' => $id_donvi))));
		}*/
		if($id_mucdich){
			array_push($query, array('id_mucdich' => new MongoId($id_mucdich)));
		}
		if($id_linhvuc){
			array_push($query, array('id_linhvuc' => new MongoId($id_linhvuc)));
		}
		$q = array('$and' => $query);
		$union_list = $doanvao->get_list_condition($q);
	}
}
?>
<script type="text/javascript" src="js/select2.min.js"></script>
<script type="text/javascript" src="js/jquery.inputmask.js"></script>
<script type="text/javascript" src="js/thongkedoanra.js"></script>
<script type="text/javascript">
	$(document).ready(function(){
		$(".select2").select2();timdonvi();
		$(".ngaythangnam").inputmask();
		<?php if(isset($msg) && $msg) : ?>
        	$.Notify({type: 'alert', caption: 'Thông báo', content: <?php echo "'".$msg."'"; ?>});
    	<?php endif; ?>
    	$(".open_window").click(function(){
		  window.open($(this).attr("href"), '_blank', 'toolbar=yes, scrollbars=yes, resizable=yes, top=0, left=100, width=1024, height=800');
		  return false;
		});
	});
</script>
<h1><a href="index.php" class="nav-button transform"><span></span></a>&nbsp;Thống kê Đoàn vào theo Đơn vị tiếp</h1>
<form method="GET" action="<?php echo $_SERVER['PHP_SELF']; ?>" id="thongkeform" data-role="validator" data-show-required-state="false" enctype="multipart/form-data">
<div class="grid" style="margin-top: 30px;">
	<div class="row cells12">
		<div class="cell colspan4 input-control select">
			<label>Đơn vị</label>
			<select name="id_donvi" id="id_donvi">
			<?php
			if(isset($id_donvi) && $id_donvi){
				$donvi->id = $id_donvi; $dv = $donvi->get_one();
				echo '<option value="'.$dv['_id'].'">'.$dv['ten'].'</option>';
			}
			?>
			</select>
		</div>
		<div class="cell colspan4 input-control text" data-role="datepicker" data-format="dd/mm/yyyy">
			<label>Từ ngày</label>
			<input type="text" name="tungay" id="tungay" value="<?php echo isset($tungay) ? $tungay : '01/01/2006'; ?>" class="ngaythangnam" data-inputmask="'alias': 'date'" data-validate-func="required" placeholder="Chọn ngày" />
			<span class="input-state-error mif-warning"></span><span class="input-state-success mif-checkmark"></span>
		</div>
		<div class="cell colspan4 input-control text" data-role="datepicker" data-format="dd/mm/yyyy">
			<label>Đến ngày</label>
			<input type="text" name="denngay" id="denngay" value="<?php echo isset($denngay) ? $denngay : date('d/m/Y'); ?>" class="ngaythangnam" data-inputmask="'alias': 'date'" data-validate-func="required" placeholder="Chọn ngày" />
			<span class="input-state-error mif-warning"></span><span class="input-state-success mif-checkmark"></span>
		</div>
	</div>
	<div class="row cells12">
		<div class="cell colspan4 input-control select">
			<label>Mục đích</label>
			<select name="id_mucdich" id="id_mucdich" data-placeholder="Chọn mục đích" data-allow-clear="true" class="select2">
				<option value=""></option>
				option
			<?php
			$mucdich_list = $mucdich->get_all_list();
			if($mucdich_list){
				foreach($mucdich_list as $md){
					echo '<option value="'.$md['_id'].'"'.($md['_id'] == $id_mucdich ? ' selected' : '').'>'.$md['ten'].'</option>';
				}
			}
			?>
			</select>
		</div>
		<div class="cell colspan4 input-control select">
			<label>Lĩnh vực</label>
			<select name="id_linhvuc" id="id_linhvuc" data-placeholder="Chọn lĩnh vực" data-allow-clear="true" class="select2">
				<option value=""></option>
			<?php
			$linhvuc_list = $linhvuc->get_all_list();
			if($linhvuc_list){
				foreach($linhvuc_list as $lv){
					echo '<option value="'.$lv['_id'].'"'.($lv['_id'] == $id_linhvuc ? ' selected' : '').'>'.$lv['ten'].'</option>';
				}
			}
			?>
			</select>
		</div>
	</div>
	<div class="row cells12">
		<div class="cell colspan12 align-center">
			<button name="submit" id="submit" value="OK" class="button primary"><span class="mif-checkmark"></span> Thống kê</button>
			<?php if(isset($_GET['submit'])) : ?>
				<a href="in_thongkedoanvaotheodonvi.php?<?php echo $_SERVER['QUERY_STRING']; ?>" class="open_window button"><span class="mif-printer"></span> Print</a>
				<a href="export_thongkedoanvaotheodonvi.php??<?php echo $_SERVER['QUERY_STRING']; ?>" class="button success"><span class="mif-file-excel"></span> Excel</a>
				<!--<a href="export_thongkedoanratheocanhan_word.php" class="button bg-teal fg-white"><span class="mif-file-word"></span> Word</a>-->
			<?php endif; ?>
		</div>
	</div>
</div>
</form>
<hr />
<?php if(isset($union_list) && $union_list->count() > 0) : ?>
<?php
if(isset($id_donvi) && $id_donvi){
	echo '<h4>' . $dv['ten'] .': <span class="fg-blue">'.$union_list->count().' lượt</span></h4>';
	if(isset($dv['level2']) && $dv['level2']){
		foreach ($dv['level2'] as $a2) {
			$query_2 = $query;
			array_push($query_2, array('congvanxinphep.id_donvi.1' => $a2['_id']->{'$id'}));
			$q2 = array('$and' => $query_2);
			$c2 = $doanvao->get_list_condition($q2)->count();
			if($c2)	echo '<h5><span class="mif-arrow-right"></span> '.$a2['ten'].': '.$c2.'</h5>';
			if(isset($a2['level3']) && $a2['level3']){
				echo '<ul>';
				foreach ($a2['level3'] as $a3) {
					$query_3 = $query_2;
					array_push($query_3, array('congvanxinphep.id_donvi.2' => $a3['_id']->{'$id'}));
					$q3 = array('$and' => $query_3);
					$c3 = $doanvao->get_list_condition($q3)->count();
					if($c3)	echo '<li>'.$a3['ten'].': '.$c3.'</li>';
					if(isset($a3['level4']) && $a3['level4']){
						echo '<ul>';
						foreach ($a3['level4'] as $a4) {
							$query_4 = $query_3;
							array_push($query_4, array('congvanxinphep.id_donvi.3' => $a4['_id']->{'$id'}));
							$q4 = array('$and' => $query_4);
							$c4 = $doanvao->get_list_condition($q4)->count();
							if($c4)	echo '<li>'.$a4['ten'].': '.$c4.'</li>';
						}
						echo '</ul>';
					}
				}
				echo '</ul>';
			}
		}
	}
}
?>
<table class="table border bordered striped">
	<thead>
		<tr>
			<th>STT</th>
			<th>Trưởng đoàn</th>
			<th>Quốc tịch</th>
			<th>Văn bản xin phép</th>
			<th>Văn bản cho phép</th>
			<th>Ngày đến</th>
			<th>Ngày đi</th>
			<th>Nội dung</th>
		</tr>
	</thead>
	<tbody>
	<?php
		$i = 1;
		foreach ($union_list as $u) {
			if(isset($u['danhsachdoan'][0]['id_canbo']) && $u['danhsachdoan'][0]['id_canbo']){
				$canbo->id = $u['danhsachdoan'][0]['id_canbo']; $cb = $canbo->get_one();
				$tentruongdoan = $cb['hoten'];
			} else {
				$tentruongdoan = '';
			}

			$congvanxinphep = $u['congvanxinphep']['ten'];
			$soquyetdinh = $u['quyetdinhchophep']['ten'];
			$ngayden = $u['ngayden'] ? date("d/m/Y", $u['ngayden']->sec) : '';
			$ngaydi = $u['ngaydi'] ? date("d/m/Y", $u['ngaydi']->sec) : '';
			if(isset($cb['id_quoctich']) && $cb['id_quoctich']){
				$quocgia->id = $cb['id_quoctich']; $qt = $quocgia->get_one();
				$tenquoctich = $qt['ten'];
			} else { $tenquoctich = ''; }
			echo '<tr>
				<td>'.$i.'</td>
				<td>'.$tentruongdoan.'</td>
				<td>'.$tenquoctich.'</td>
				<td><a href="chitietdoanvao.php?id='.$u['_id'].'">'.$congvanxinphep.'</a></td>
				<td>'.$soquyetdinh.'</td>
				<td>'.$ngayden.'</td>
				<td>'.$ngaydi.'</td>
				<td>'.$u['noidung'].'</td>
			</tr>';$i++;
		}
	?>
	</tbody>
</table>
<?php endif; ?>
<?php require_once('footer.php'); ?>
