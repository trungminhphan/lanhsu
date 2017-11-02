<?php
require_once('header.php');
$canbo = new CanBo();$doanvao=new DoanVao();$quocgia=new QuocGia();
$mucdich = new MucDich(); $linhvuc = new LinhVuc();$donvi = new DonVi();$dmdoanvao = new DMDoanVao();
$id_canbo ='';$id_quocgia='';$id_kinhphi='';
if(isset($_GET['submit'])){
	$query = array();
	$a = isset($_GET['id_canbo']) ? explode("-", $_GET['id_canbo'])  : '';
	if($a && count($a) > 0) $id_canbo = $a[0];
	$tungay = isset($_GET['tungay']) ? $_GET['tungay'] : '';
	$denngay = isset($_GET['denngay']) ? $_GET['denngay'] : '';
	$id_quocgia = isset($_GET['id_quocgia']) ? $_GET['id_quocgia'] : '';
	$id_mucdich = isset($_GET['id_mucdich']) ? $_GET['id_mucdich'] : '';
	$id_linhvuc = isset($_GET['id_linhvuc']) ? $_GET['id_linhvuc'] : '';

	if(convert_date_dd_mm_yyyy($tungay) > convert_date_dd_mm_yyyy($denngay)){
		$msg = 'Chọn ngày sai hoặc chưa chọn Cá nhân thống kê';
	} else {
		$start_date = new MongoDate(convert_date_dd_mm_yyyy($tungay));
		$end_date = new MongoDate(convert_date_dd_mm_yyyy($denngay));
		array_push($query, array('ngayden' => array('$gte' => $start_date)));
		array_push($query, array('ngaydi' => array('$lte' => $end_date)));
		if($id_canbo){
			$arr_cb = array('$or' => array(array('danhsachdoan.id_canbo' => new MongoId($id_canbo)), array('danhsachdoan_2.id_canbo'=> new MongoId($id_canbo))));
			array_push($query, $arr_cb);
		}

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
		$(".select2").select2();hotencanhan();
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
<h1><a href="index.php" class="nav-button transform"><span></span></a>&nbsp;Thống kê Đoàn vào theo Cá nhân</h1>
<form method="GET" action="<?php echo $_SERVER['PHP_SELF']; ?>" id="thongkeform" data-role="validator" data-show-required-state="false" enctype="multipart/form-data">
<div class="grid" style="margin-top: 30px;">
	<div class="row cells12">
		<div class="cell colspan4 input-control select">
			<label>Họ tên</label>
			<select name="id_canbo" id="id_canbo" data-placeholder="Chọn cá nhân cần thống kê" data-allow-clear="true">
			<?php
			if(isset($id_canbo) && $id_canbo){
				$canbo->id = $id_canbo; $cb = $canbo->get_one();
				echo '<option value="'.$cb['_id'].'">'.$cb['hoten'].' [ID: '. $cb['code'] .']</option>';
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
		<div class="cell colspan4 input-control select">
			<label>Quốc gia</label>
			<select name="id_quocgia" id="id_quocgia" data-placeholder="Chọn quốc gia" data-allow-clear="true" class="select2">
			<option value=""></option>
			<?php
			$quocgia_list = $quocgia->get_all_list();
			if($quocgia_list){
				foreach($quocgia_list as $qg){
					echo '<option value="'.$qg['_id'].'"'.($qg['_id'] == $id_quocgia ? ' selected' : '').'>'.$qg['ten'].'</option>';
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
				<a href="in_thongkedoanvaotheocanhan.php?<?php echo $_SERVER['QUERY_STRING']; ?>" class="open_window button"><span class="mif-printer"></span> Print</a>
				<a href="export_thongkedoanvaotheocanhan.php?<?php echo $_SERVER['QUERY_STRING']; ?>" class="button success"><span class="mif-file-excel"></span> Excel</a>
				<!--<a href="export_thongkedoanratheocanhan_word.php" class="button bg-teal fg-white"><span class="mif-file-word"></span> Word</a>-->
			<?php endif; ?>
		</div>
	</div>
</div>
</form>
<hr />
<?php if(isset($union_list) && $union_list->count() > 0) : ?>
<?php
if(isset($id_canbo) && $id_canbo){
	if($cb['id_quoctich']){
		$quocgia->id = $cb['id_quoctich']; $qt = $quocgia->get_one();
		$tenquoctich = $qt['ten'];
	}  else {
		$tenquoctich = '';
	}
	echo '<h4>' . $cb['hoten'] .': <span class="fg-blue">'.$union_list->count().' lượt nhập cảnh</span></h4>';
	echo '<h4>Quốc tịch: '.$tenquoctich.'</h4>';
}
?>
<table class="table border bordered striped">
	<thead>
		<tr>
			<th>STT</th>
			<th>Trưởng đoàn</th>
			<th>Văn bản xin phép</th>
			<th>Đơn vị tiếp</th>
			<th>Đoàn nước ngoài</th>
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
			if($u['congvanxinphep']['id_donvi']){
				$tendonvi = $donvi->tendonvi($u['congvanxinphep']['id_donvi']);
			} else { $tendonvi = '';}
			if(isset($u['id_dmdoanvao']) && $u['id_dmdoanvao']){
				$dmdoanvao->id = $u['id_dmdoanvao'];$dmdv = $dmdoanvao->get_one();
				$tendoanvao = $dmdv['ten'];
			} else { $tendoanvao = ''; }
			$congvanxinphep = $u['congvanxinphep']['ten'];
			$soquyetdinh = $u['quyetdinhchophep']['ten'];
			$ngayden = $u['ngayden'] ? date("d/m/Y", $u['ngayden']->sec) : '';
			$ngaydi = $u['ngaydi'] ? date("d/m/Y", $u['ngaydi']->sec) : '';
			$blnQuocGia = false;
			if($id_quocgia){
				if($u['danhsachdoan']){
					foreach($u['danhsachdoan'] as $ds){
						$canbo->id = $ds['id_canbo']; $cb = $canbo->get_one();
						if($id_quocgia == strval($cb['id_quoctich'])){
							$blnQuocGia = true;
						}
					}
				}
				if($u['danhsachdoan_2']){
					foreach($u['danhsachdoan_2'] as $ds2){
						$canbo->id = $ds2['id_canbo']; $cb = $canbo->get_one();
						if($id_quocgia == strval($cb['id_quoctich'])){
							$blnQuocGia = true;
						}
					}
				}
			}

			if(!$id_quocgia || ($id_quocgia && $blnQuocGia==true)){
				echo '<tr>
					<td>'.$i.'</td>
					<td>'.$tentruongdoan.'</td>
					<td>'.$congvanxinphep.'</td>
					<td>'.$tendonvi.'</td>
					<td>'.$tendoanvao.'</td>
					<td><a href="chitietdoanvao.php?id='.$u['_id'].'" target="_blank">'.$soquyetdinh.'</a></td>
					<td>'.$ngayden.'</td>
					<td>'.$ngaydi.'</td>
					<td>'.$u['noidung'].'</td>
				</tr>';
			}

			$i++;
		}
	?>
	</tbody>
</table>
<?php endif; ?>
<?php require_once('footer.php'); ?>
