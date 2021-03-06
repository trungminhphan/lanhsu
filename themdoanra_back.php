<?php
require_once('header.php');
$doanra = new DoanRa();$canbo = new CanBo();$chucvu = new ChucVu();$donvi = new DonVi();$ham=new Ham();
$id = isset($_GET['id']) ? $_GET['id'] : '';
$act = isset($_GET['act']) ? $_GET['act'] : '';
$update = isset($_GET['update']) ? $_GET['update'] : '';
$id_donvi_chophep = '56f20d64af62da9792157931'; $id_donvi_xinphep = '';
$donvi = new DonVi();$donvi_list = $donvi->get_all_list();$id_quocgia= array();
$id_donvi_xinphep_1='';$id_donvi_xinphep_2='';$id_donvi_xinphep_3='';$id_donvi_xinphep_4='';
$id_donvimoi='';
if($id && $act=='del'){
	$doanra->id = $id; $dr = $doanra->get_one();
	if($doanra->delete()){
		if($dr['congvanxinphep']['attachments']){
			foreach ($dr['congvanxinphep']['attachments'] as $key => $value) {
				@unlink($target_files . $value['alias_name']);
			}	
		} 
		if($dr['quyetdinhchophep']['attachments']){
			foreach ($dr['quyetdinhchophep']['attachments'] as $key => $value) {
				@unlink($target_files . $value['alias_name']);
			}
		}
		if($dr['quyetdinhchophep_2']['attachments']){
			foreach ($dr['quyetdinhchophep_2']['attachments'] as $key => $value) {
				@unlink($target_files . $value['alias_name']);
			}
		}
		transfers_to('doanra.php?update=ok');
	} else {
		transfers_to('doanra.php?update=no');
	}
}
switch ($update) {
	case 'edit_ok': $msg = 'Chỉnh sửa thành công'; break;
	case 'edit_no': $msg = 'Không thể chỉnh sửa';  break;
	case 'insert_ok': $msg = 'Thêm thành công';  break;
	case 'insert_no': $msg = 'Không thể thêm mới';  break;
	default: $msg = ''; break;
}
if(isset($_POST['submit'])){
	$id_donvi_xinphep_1 = isset($_POST['id_donvi_xinphep_1']) ? $_POST['id_donvi_xinphep_1'] : '';
	$id_donvi_xinphep_2 = isset($_POST['id_donvi_xinphep_2']) ? $_POST['id_donvi_xinphep_2'] : '';
	$id_donvi_xinphep_3 = isset($_POST['id_donvi_xinphep_3']) ? $_POST['id_donvi_xinphep_3'] : '';
	$id_donvi_xinphep_4 = isset($_POST['id_donvi_xinphep_4']) ? $_POST['id_donvi_xinphep_4'] : '';
	$id_donvi_xinphep = array($id_donvi_xinphep_1, $id_donvi_xinphep_2, $id_donvi_xinphep_3, $id_donvi_xinphep_4);
	$tencongvanxinphep = isset($_POST['tencongvanxinphep']) ? $_POST['tencongvanxinphep'] : '';
	$ngaykycongvanxinphep = isset($_POST['ngaykycongvanxinphep']) ? $_POST['ngaykycongvanxinphep'] : '';
	$filecongvanxinphep = array();
	$congvanxinphep_alias_name = isset($_POST['congvanxinphep_alias_name']) ? $_POST['congvanxinphep_alias_name'] : '';
	$congvanxinphep_filename = isset($_POST['congvanxinphep_filename']) ? $_POST['congvanxinphep_filename'] : '';
	$congvanxinphep_filetype = isset($_POST['congvanxinphep_filetype']) ? $_POST['congvanxinphep_filetype'] : '';
	if($congvanxinphep_alias_name){
		foreach ($congvanxinphep_alias_name as $key => $value) {
			array_push($filecongvanxinphep, array('alias_name' => $value, 'filename' => $congvanxinphep_filename[$key], 'filetype'=>$congvanxinphep_filetype[$key]));
		}
	}
	$congvanxinphep = array(
		'id_donvi' => $id_donvi_xinphep ? $id_donvi_xinphep : '',
		'ten' => $tencongvanxinphep,
		'attachments' => $filecongvanxinphep,
		'ngayky' => $ngaykycongvanxinphep ? new MongoDate(convert_date_dd_mm_yyyy($ngaykycongvanxinphep)) : '');
	$id_donvi_chophep = isset($_POST['id_donvi_chophep']) ? $_POST['id_donvi_chophep'] : '';
	$tenquyetdinhchophep = isset($_POST['tenquyetdinhchophep']) ? $_POST['tenquyetdinhchophep'] : '';
	$ngaybanhanhquyetdinhchophep = isset($_POST['ngaybanhanhquyetdinhchophep']) ? $_POST['ngaybanhanhquyetdinhchophep'] : '';
	$filequyetdinhchophep = array();
	$quyetdinhchophep_alias_name = isset($_POST['quyetdinhchophep_alias_name']) ? $_POST['quyetdinhchophep_alias_name'] : '';
	$quyetdinhchophep_filename = isset($_POST['quyetdinhchophep_filename']) ? $_POST['quyetdinhchophep_filename'] : '';
	$quyetdinhchophep_filetype = isset($_POST['quyetdinhchophep_filetype']) ? $_POST['quyetdinhchophep_filetype'] : '';
	if($quyetdinhchophep_alias_name){
		foreach ($quyetdinhchophep_alias_name as $key => $value) {
			array_push($filequyetdinhchophep, array('alias_name' => $value, 'filename' => $quyetdinhchophep_filename[$key], 'filetype'=>$quyetdinhchophep_filetype[$key]));
		}
	}
	$quyetdinhchophep = array(
		'id_donvi' => $id_donvi_chophep ? new MongoId($id_donvi_chophep) : '',
		'ten' => $tenquyetdinhchophep,
		'attachments' => $filequyetdinhchophep,
		'ngaybanhanh' => $ngaybanhanhquyetdinhchophep ? new MongoDate(convert_date_dd_mm_yyyy($ngaybanhanhquyetdinhchophep)) : '');

	$id_donvi_chophep_2 = isset($_POST['id_donvi_chophep_2']) ? $_POST['id_donvi_chophep_2'] : '';
	$tenquyetdinhchophep_2 = isset($_POST['tenquyetdinhchophep_2']) ? $_POST['tenquyetdinhchophep_2'] : '';
	$ngaybanhanhquyetdinhchophep_2 = isset($_POST['ngaybanhanhquyetdinhchophep_2']) ? $_POST['ngaybanhanhquyetdinhchophep_2'] : '';
	$filequyetdinhchophep_2 = array();
	$quyetdinhchophep_alias_name_2 = isset($_POST['quyetdinhchophep_alias_name_2']) ? $_POST['quyetdinhchophep_alias_name_2'] : '';
	$quyetdinhchophep_filename_2 = isset($_POST['quyetdinhchophep_filename_2']) ? $_POST['quyetdinhchophep_filename_2'] : '';
	$congvanchophep_filetype_2 = isset($_POST['congvanchophep_filetype_2']) ? $_POST['congvanchophep_filetype_2'] : '';
	if($quyetdinhchophep_alias_name_2){
		foreach ($quyetdinhchophep_alias_name_2 as $key => $value) {
			array_push($filequyetdinhchophep_2, array('alias_name' => $value, 'filename' => $quyetdinhchophep_filename_2[$key], 'filetype'=>$congvanchophep_filetype_2[$key]));
		}
	}
	$quyetdinhchophep_2 = array(
		'id_donvi' => $id_donvi_chophep_2 ? new MongoId($id_donvi_chophep_2) : '',
		'ten' => $tenquyetdinhchophep_2,
		'attachments' => $filequyetdinhchophep_2,
		'ngaybanhanh' => $ngaybanhanhquyetdinhchophep_2 ? new MongoDate(convert_date_dd_mm_yyyy($ngaybanhanhquyetdinhchophep_2)) : '');

	$ngaydi = isset($_POST['ngaydi']) ? $_POST['ngaydi'] : '';
	$ngayve = isset($_POST['ngayve']) ? $_POST['ngayve'] : '';
	$songay = isset($_POST['songay']) ? $_POST['songay'] : 0;
	$id_donvimoi = isset($_POST['id_donvimoi']) ? $_POST['id_donvimoi'] : '';
	$id_quocgia = isset($_POST['id_quocgia']) ? $_POST['id_quocgia'] : '';
	$id_mucdich = isset($_POST['id_mucdich']) ? $_POST['id_mucdich'] : '';
	$id_kinhphi = isset($_POST['id_kinhphi']) ? $_POST['id_kinhphi'] : '';
	$noidung = isset($_POST['noidung']) ? $_POST['noidung'] : '';
	$ghichu = isset($_POST['ghichu']) ? $_POST['ghichu'] : '';

	$id_canbo = isset($_POST['id_canbo']) ? $_POST['id_canbo'] : '';
	$danhsachdoan = array();
	if($id_canbo && count($id_canbo) > 0){
		foreach ($id_canbo as $k => $v) {
			if($v){
				//$canbo->id = $v; $cb = $canbo->get_one();
				//$count = count($cb['donvi']) - 1 ;
				$a = explode('-', $v);
				array_push($danhsachdoan, array('id_canbo' => new MongoId($a[0]), 'id_donvi' => explode(",", $a[1]), 'id_chucvu' => new MongoId($a[2]), 'id_ham'=>$a[3] ? new MongoId($a[3]) : ''));
			}
		}
	}

	$id_canbo_2 = isset($_POST['id_canbo_2']) ? $_POST['id_canbo_2'] : '';
	$danhsachdoan_2 = array();
	if($id_canbo_2 && count($id_canbo_2) > 0){
		foreach ($id_canbo_2 as $k => $v) {
			if($v){
				//$canbo->id = $v; $cb = $canbo->get_one();
				//$count = count($cb['donvi']) - 1 ;
				$a = explode('-', $v);
				array_push($danhsachdoan_2, array('id_canbo' => new MongoId($a[0]), 'id_donvi' => explode(",", $a[1]), 'id_chucvu' => new MongoId($a[2]), 'id_ham' => $a[3] ? new MongoId($a[3]) : ''));
			}
		}
	}

	$id = isset($_POST['id']) ? $_POST['id'] : '';
	$doanra->congvanxinphep = $congvanxinphep;
	$doanra->quyetdinhchophep = $quyetdinhchophep;
	$doanra->quyetdinhchophep_2 = $quyetdinhchophep_2;
	$doanra->ngaydi = $ngaydi != '' ? new MongoDate(convert_date_dd_mm_yyyy($ngaydi)) : '';
	$doanra->ngayve = $ngayve != '' ? new MongoDate(convert_date_dd_mm_yyyy($ngayve)) : '';
	$doanra->songay = $songay;
	$doanra->id_donvimoi = $id_donvimoi;
	$doanra->id_quocgia = $id_quocgia;
	$doanra->id_mucdich = $id_mucdich;
	$doanra->id_kinhphi = $id_kinhphi;
	$doanra->danhsachdoan = $danhsachdoan;
	$doanra->danhsachdoan_2 = $danhsachdoan_2;
	$doanra->noidung = $noidung;
	$doanra->ghichu = $ghichu;
	$doanra->id_user = $users->get_userid();
	if($id){
		//edit
		$doanra->id = $id;
		if($doanra->edit()){
			transfers_to('themdoanra.php?id='.$id.'&update=edit_ok');
		} else {
			transfers_to('themdoanra.php?id='.$id.'&update=edit_no');
		}
	} else {
		//insert
		$id = new MongoId();
		$doanra->id = $id;
		if($doanra->insert()){
			transfers_to('themdoanra.php?id='.$id.'&update=insert_ok');
		} else {
			transfers_to('themdoanra.php?id='.$id.'&update=insert_no');
		}
	}
} //end submit
if($id){
	$doanra->id = $id; $dr = $doanra->get_one();
	$donvi->id = $dr['congvanxinphep']['id_donvi'][0];
	$donvi_list_one = $donvi->get_one();
	$id_donvi_xinphep_1 = $dr['congvanxinphep']['id_donvi'][0];
	$id_donvi_xinphep_2 = $dr['congvanxinphep']['id_donvi'][1];
	$id_donvi_xinphep_3 = $dr['congvanxinphep']['id_donvi'][2];
	$id_donvi_xinphep_4 = $dr['congvanxinphep']['id_donvi'][3];
	$tencongvanxinphep = $dr['congvanxinphep']['ten'];
	$filecongvanxinphep = $dr['congvanxinphep']['attachments'];//$dr['congvanxinphep']['filename'];
	$ngaykycongvanxinphep = $dr['congvanxinphep']['ngayky'] ? date("d/m/Y", $dr['congvanxinphep']['ngayky']->sec) : '';
	$id_donvi_chophep = $dr['quyetdinhchophep']['id_donvi'] ? $dr['quyetdinhchophep']['id_donvi'] : '56f20d64af62da9792157931';
	$tenquyetdinhchophep = $dr['quyetdinhchophep']['ten'];
	$filequyetdinhchophep = $dr['quyetdinhchophep']['attachments'];
	$ngaybanhanhquyetdinhchophep = $dr['quyetdinhchophep']['ngaybanhanh'] ? date("d/m/Y", $dr['quyetdinhchophep']['ngaybanhanh']->sec) : '';
	$id_donvi_chophep_2 = isset($dr['quyetdinhchophep_2']['id_donvi']) ? $dr['quyetdinhchophep']['id_donvi'] : '';
	$tenquyetdinhchophep_2 = isset($dr['quyetdinhchophep_2']['ten']) ? $dr['quyetdinhchophep_2']['ten'] : '';
	$filequyetdinhchophep_2 = isset($dr['quyetdinhchophep_2']['attachments']) ? $dr['quyetdinhchophep_2']['attachments'] : '';
	$ngaybanhanhquyetdinhchophep_2 = (isset($dr['quyetdinhchophep_2']['ngaybanhanh']) && $dr['quyetdinhchophep_2']['ngaybanhanh']) ? date("d/m/Y", $dr['quyetdinhchophep']['ngaybanhanh']->sec) : '';
	$ngaydi = date("d/m/Y", $dr['ngaydi']->sec);
	$ngayve = date("d/m/Y", $dr['ngayve']->sec);
	$songay = $dr['songay'];
	$id_donvimoi = isset($dr['id_donvimoi']) ? $dr['id_donvimoi'] : '';
	$id_quocgia = $dr['id_quocgia'];
	$id_mucdich = $dr['id_mucdich'];
	$id_kinhphi = $dr['id_kinhphi'];
	$danhsachdoan = $dr['danhsachdoan'];
	$danhsachdoan_2 = isset($dr['danhsachdoan_2']) ? $dr['danhsachdoan_2'] : '';
	$noidung = $dr['noidung'];
	$ghichu = $dr['ghichu'];
}
?>
<script type="text/javascript" src="js/select2.min.js"></script>
<script type="text/javascript" src="js/jquery.inputmask.js"></script>
<script type="text/javascript" src="js/doanra.js"></script>
<script type="text/javascript">
$(document).ready(function(){
	nhaplieu();	upload_congvanxinphep(); upload_quyetdinhchophep();upload_quyetdinhchophep_2(); delete_file();
	hide_donvi_xinphep();id_donvi_xinphep_action_chitiet();
	$("#show_doan_2").click(function(){
		$(".doan_2").slideToggle();
		$("#id_canbo_2").select2();
	});
	<?php if(isset($id_donvi_chophep_2) && $id_donvi_chophep_2): ?>
		$(".doan_2").show();
	<?php else : ?>
		$(".doan_2").hide();
	<?php endif; ?>
	<?php if(isset($id_donvi_xinphep_1) && $id_donvi_xinphep_1): ?>
		$("#id_donvi_xinphep_1").select2("val", <?php echo "'".$id_donvi_xinphep_1."'"; ?>);
	<?php endif; ?>
	<?php if($id_donvi_xinphep_2): ?>
		$("#id_donvi_xinphep_2").select2("val", <?php echo "'".$id_donvi_xinphep_2."'"; ?>);
		$("#id_donvi_xinphep2").show();
	<?php endif; ?>
	<?php if($id_donvi_xinphep_3): ?>
		$("#id_donvi_xinphep_3").select2("val", <?php echo "'".$id_donvi_xinphep_3."'"; ?>);
		$("#id_donvi_xinphep3").show();
	<?php endif; ?>
	<?php if($id_donvi_xinphep_4): ?>
		$("#id_donvi_xinphep_4").select2("val", <?php echo "'".$id_donvi_xinphep_4."'"; ?>);
		$("#id_donvi_xinphep4").show();
	<?php endif; ?>
	<?php if($msg) : ?>
        $.Notify({type: 'alert', caption: 'Thông báo', content: '<?php echo $msg; ?>'});
    <?php endif; ?>
});
</script>
<h1><a href="doanra.php" class="nav-button transform"><span></span></a>&nbsp;Cập nhật thông tin Đoàn Ra</h1>
<form action="<?php echo $_SERVER['REQUEST_URI']; ?>" method="POST" id="themdoanraform" data-role="validator" data-show-required-state="false" enctype="multipart/form-data">
<input type="hidden" name="id" id="id" value="<?php echo isset($id) ? $id : ''; ?>" />
<div class="grid">
	<div class="row cells12">
		<div class="cell colspan2 padding-top-10 align-right">Xin phép</div>
		<div class="cell colspan4 input-control select">
			<select name="id_donvi_xinphep_1" id="id_donvi_xinphep_1" class="select2">
			<?php
				if($donvi_list){
					foreach ($donvi_list as $dv) {
						echo '<option value="'.$dv['_id'].'">'.$dv['ten'].'</option>';
					}
				}
			?>
			</select>
		</div>
		<div class="cell colspan2 input-control text">
			<input type="text" name="tencongvanxinphep" id="tencongvanxinphep" value="<?php echo isset($tencongvanxinphep) ? $tencongvanxinphep : ''; ?>" placeholder="Số công văn xin phép" data-validate-func="required"/>
			<span class="input-state-error mif-warning"></span><span class="input-state-success mif-checkmark"></span>
		</div>
		<div class="cell colspan2 input-control text" data-role="datepicker" data-format="dd/mm/yyyy">
			<input type="text" name="ngaykycongvanxinphep" id="ngaykycongvanxinphep" value="<?php echo isset($ngaykycongvanxinphep) ? $ngaykycongvanxinphep : ''; ?>" placeholder="Ngày ký công văn xin phép." data-inputmask="'alias': 'date'" class="ngaythangnam"/>
		</div>
		<div class="cell colspan2 input-control file" data-role="input">
			<input type="file" name="dinhkem_filecongvanxinphep[]" multiple="multiple" class="dinhkem_filecongvanxinphep" placeholder="Tập tin đính kèm" accept="*" />
			<button class="button"><span class="mif-folder"></span></button>
		</div>
	</div>
	<div class="row cells12" id="id_donvi_xinphep2">
		<div class="cell colspan2"></div>
		<div class="cell colspan4 input-control" data-placeholder="Chọn đơn vị" data-role="select" data-allow-clear="true">
			<select name="id_donvi_xinphep_2" id="id_donvi_xinphep_2" class="select2">
				<option value="">Chọn đơn vị</option>
				<?php
				if($donvi_list_one && $id && $id_donvi_xinphep_2){
					foreach ($donvi_list_one['level2'] as $k2 => $a2) {
						if($donvi_list_one['_id'] == $id_donvi_xinphep_1){
							echo '<option value="'.$a2['_id'].'">'.$a2['ten'].'</div>';
						}
					}
				}
				?>
			</select>
		</div>
		<div class="cell colspan2 align-left padding-top-10">(Cấp 2)</div>
	</div>
	<div class="row cells12" id="id_donvi_xinphep3">
		<div class="cell colspan2"></div>
		<div class="cell colspan4 input-control" data-placeholder="Chọn đơn vị" data-role="select" data-allow-clear="true">
			<select name="id_donvi_xinphep_3" id="id_donvi_xinphep_3" class="select2">
				<option value="">Chọn đơn vị</option>
				<?php
				if($donvi_list_one && $id && $id_donvi_xinphep_2 && $id_donvi_xinphep_3){
					foreach ($donvi_list_one['level2'] as $k2 => $a2) {
						if($donvi_list_one['_id'] == $id_donvi_xinphep_1){
							if(isset($a2['level3']) && $a2['level3']){
								foreach ($a2['level3'] as $k3 => $a3) {
									if($a2['_id'] == $id_donvi_xinphep_2){
										echo '<option value="'.$a3['_id'].'">'.$a3['ten'].'</div>';
									}
								}
							}
						}
					}
				}
				?>
			</select>
		</div>
		<div class="cell colspan2 align-left padding-top-10">(Cấp 3)</div>
	</div>
	<div class="row cells12" id="id_donvi_xinphep4">
		<div class="cell colspan2"></div>
		<div class="cell colspan4 input-control" data-placeholder="Chọn đơn vị" data-role="select" data-allow-clear="true">
			<select name="id_donvi_xinphep_4" id="id_donvi_xinphep_4" class="select2">
				<option value="">Chọn đơn vị</option>
				<?php
				if($donvi_list_one && $id && $id_donvi_xinphep_2 && $id_donvi_xinphep_3 && $id_donvi_xinphep_4){
					foreach ($donvi_list_one['level2'] as $k2 => $a2) {
						if($donvi_list_one['_id'] == $id_donvi_xinphep_1){
							if(isset($a2['level3']) && $a2['level3']){
								foreach ($a2['level3'] as $k3 => $a3) {
									if($a2['_id'] == $id_donvi_xinphep_2){
										if(isset($a3['level4']) && $a3['level4']){
											foreach ($a3['level4'] as $k4=>$a4) {
												if($a3['_id'] == $id_donvi_xinphep_3){
													echo '<option value="'.$a4['_id'].'">'.$a4['ten'].'</div>';
												}
											}
										}
									}
								}
							}
						}
					}
				}
				?>
			</select>
		</div>
		<div class="cell colspan2 align-left padding-top-10">(Cấp 4)</div>
	</div>
	<div id="files_congvanxinphep">
		<?php
			if(isset($filecongvanxinphep) && $filecongvanxinphep){
				foreach ($filecongvanxinphep as $cvxp) {
					echo '<div class="row cells12" style="padding:0px 0px 10px 0px;margin:0px;">';
						echo '<div class="cell colspan2"></div>';
						echo '<div class="cell colspan10 input-control text">';
							echo '<input type="hidden" name="congvanxinphep_alias_name[]" value="'.$cvxp['alias_name'].'" readonly/>';
							echo '<input type="hidden" name="congvanxinphep_filetype[]" value="'.$cvxp['filetype'].'" readonly/>';
							echo '<span class="mif-attachment prepend-icon"></span>';
							echo '<input type="text" name="congvanxinphep_filename[]" value="'.$cvxp['filename'].'" class="bg-grayLighter"/>';
							echo '<a href="get.xoataptin.php?filename='.$cvxp['alias_name'].'" onclick="return false;" class="delete_file button"><span class="mif-cross fg-red"></span></a>';
						echo '</div>';
					echo '</div>';
				}
			}
		?>
	</div>
	<div class="row cells12">
		<div class="cell colspan2 padding-top-10 align-right">Cho phép</div>
		<div class="cell colspan4 input-control select">
			<select name="id_donvi_chophep" id="id_donvi_chophep" class="select2">
			<?php
				if($donvi_list){
					foreach ($donvi_list as $dv) {
						echo '<option value="'.$dv['_id'].'"'.($dv['_id'] == $id_donvi_chophep ? ' selected' : '').'>'.$dv['ten'].'</option>';
					}
				}
			?>
			</select>
		</div>
		<div class="cell colspan2 input-control text">
			<input type="text" name="tenquyetdinhchophep" id="tenquyetdinhchophep" value="<?php echo isset($tenquyetdinhchophep) ? $tenquyetdinhchophep : ''; ?>" placeholder="Số quyết định cho phép" data-validate-func="required"/>
			<span class="input-state-error mif-warning"></span><span class="input-state-success mif-checkmark"></span>
		</div>
		<div class="cell colspan2 input-control text" data-role="datepicker" data-format="dd/mm/yyyy">
			<input type="text" name="ngaybanhanhquyetdinhchophep" id="ngaybanhanhquyetdinhchophep" value="<?php echo isset($ngaybanhanhquyetdinhchophep) ? $ngaybanhanhquyetdinhchophep : '';?>" placeholder="Ngày ban hành quyết định." data-inputmask="'alias': 'date'" class="ngaythangnam"/>
		</div>
		<div class="cell colspan2 input-control file" data-role="input">
			<input type="file" name="dinhkem_quyetdinhchophep[]" multiple="multiple" class="dinhkem_quyetdinhchophep" placeholder="Tập tin đính kèm Quyết định" accept="*" />
			<button class="button"><span class="mif-folder"></span></button>
		</div>
	</div>
	<div id="files_quyetdinhchophep">
		<?php
			if(isset($filequyetdinhchophep) && $filequyetdinhchophep){
				foreach ($filequyetdinhchophep as $qd) {
					echo '<div class="row cells12" style="padding:0px 0px 10px 0px;margin:0px;">';
						echo '<div class="cell colspan2"></div>';
						echo '<div class="cell colspan10 input-control text">';
							echo '<input type="hidden" name="quyetdinhchophep_alias_name[]" value="'.$qd['alias_name'].'" readonly/>';
							echo '<input type="hidden" name="quyetdinhchophep_filetype[]" value="'.$qd['filetype'].'" readonly/>';
							echo '<span class="mif-attachment prepend-icon"></span>';
							echo '<input type="text" name="quyetdinhchophep_filename[]" value="'.$qd['filename'].'" class="bg-grayLighter"/>';
							echo '<a href="get.xoataptin.php?filename='.$qd['alias_name'].'" onclick="return false;" class="delete_file button"><span class="mif-cross fg-red"></span></a>';
						echo '</div>';
					echo '</div>';
				}
			}
		?>
	</div>
	<div class="row cells12">
		<div class="cell colspan2 padding-top-10 align-right"><a href="#" onclick="return false;" id="show_doan_2"><span class="mif-plus"></span></a> Danh sách đoàn</div>	
		<div class="cell colspan10 input-control select" data-role="select" data-multiple="true" data-placeholder="Danh sách thành viên đoàn">
			<select name="id_canbo[]" id="id_canbo" class="select2" multiple>
				<option value="">Danh sách thành viên đoàn</option>
				<?php
				$canbo_list = $canbo->get_all_list();
				$arr_cb = array();
				$i=1;
				if(isset($danhsachdoan) && $danhsachdoan){
					foreach ($danhsachdoan as $key => $value) {
						$canbo->id = $value['id_canbo']; $cbo = $canbo->get_one();
						//$count = count($cbo['donvi']) - 1;
						$donvi->id = $value['id_donvi'][0]; $dv = $donvi->get_one();
						$chucvu->id = $value['id_chucvu']; $cv = $chucvu->get_one();
						if(isset($value['id_ham']) && $value['id_ham']) {$ham->id=$value['id_ham'];$h = $ham->get_one(); $tenham=$h['ten'];} else { $tenham='';}
						$member = $i .' - '. $cbo['code'] . ' - ' . $cbo['hoten'] .' - '.$dv['ten']. ' - ' .$tenham . ' '. $cv['ten'];
						$v = $cbo['_id'] . '-' . implode(",", $value['id_donvi']) . '-' . $value['id_chucvu'] . '-'.$value['id_ham'];
						echo '<option value="'.$v.'" selected>'.$member.'</option>';
						array_push($arr_cb, $v);
						$i++;
					}
				}
				if(isset($canbo_list) && $canbo_list){
					foreach ($canbo_list as $cb) {
						//$count = count($cb['donvi']) - 1;
						foreach ($cb['donvi'] as $key => $value) {
							if(!in_array($cb['_id'].'-'.$value['id_donvi'].'-'.$value['id_chucvu'], $arr_cb)){
								if($value['id_donvi'][0]){
									$donvi->id = $value['id_donvi'][0]; $dv = $donvi->get_one();
									$tendonvi = $dv['ten'];
								} else { $tendonvi = 'Không biết'; }
								$chucvu->id = $value['id_chucvu']; $cv = $chucvu->get_one();
								if(isset($value['id_ham']) && $value['id_ham']) {$ham->id=$value['id_ham'];$h = $ham->get_one(); $tenham=$h['ten'];} else { $tenham='';}
								$member = $i . ' - '.$cb['code'] . ' - ' . $cb['hoten'] .' - '.$tendonvi. ' - ' .$tenham .'  '. $cv['ten'];
								$v = $cb['_id'] . '-' . implode(",", $value['id_donvi']) . '-' . $value['id_chucvu'] . '-'.$value['id_ham'];
								echo '<option value="'.$v.'">'.$member.'</option>';
								$i++;
							}
						}
					}
				}
				?>
			</select>
			<span class="tag alert" style="padding:2px;"><span class="mif-info"></span> Chú ý: Thành viên chọn đầu tiên là TRƯỞNG ĐOÀN</span>
		</div>
	</div>
	<div class="row cells12 doan_2">
		<div class="cell colspan2 padding-top-10 align-right">Cho phép</div>
		<div class="cell colspan4 input-control select">
			<select name="id_donvi_chophep_2" id="id_donvi_chophep_2" class="select2">
			<option value="">Chọn đơn vị cho phép</option>
			<?php
				if($donvi_list){
					foreach ($donvi_list as $dv2) {
						echo '<option value="'.$dv2['_id'].'"'.($dv2['_id'] == $id_donvi_chophep_2 ? ' selected' : '').'>'.$dv2['ten'].'</option>';
					}
				}
			?>
			</select>
		</div>
		<div class="cell colspan2 input-control text">
			<input type="text" name="tenquyetdinhchophep_2" id="tenquyetdinhchophep_2" value="<?php echo isset($tenquyetdinhchophep_2) ? $tenquyetdinhchophep_2 : ''; ?>" placeholder="Số quyết định cho phép" />
			<span class="input-state-error mif-warning"></span><span class="input-state-success mif-checkmark"></span>
		</div>
		<div class="cell colspan2 input-control text" data-role="datepicker" data-format="dd/mm/yyyy">
			<input type="text" name="ngaybanhanhquyetdinhchophep_2" id="ngaybanhanhquyetdinhchophep_2" value="<?php echo isset($ngaybanhanhquyetdinhchophep_2) ? $ngaybanhanhquyetdinhchophep_2 : '';?>" placeholder="Ngày ban hành quyết định." data-inputmask="'alias': 'date'" class="ngaythangnam"/>
		</div>
		<div class="cell colspan2 input-control file" data-role="input">
			<input type="file" name="dinhkem_quyetdinhchophep_2[]" multiple="multiple" class="dinhkem_quyetdinhchophep_2" placeholder="Tập tin đính kèm Quyết định" accept="*" />
			<button class="button"><span class="mif-folder"></span></button>
		</div>
	</div>
	<div id="files_quyetdinhchophep_2" class="doan_2">
		<?php
			if(isset($filequyetdinhchophep_2) && $filequyetdinhchophep_2){
				foreach ($filequyetdinhchophep_2 as $qd_2) {
					echo '<div class="row cells12" style="padding:0px 0px 10px 0px;margin:0px;">';
						echo '<div class="cell colspan2"></div>';
						echo '<div class="cell colspan10 input-control text">';
							echo '<input type="hidden" name="quyetdinhchophep_alias_name[]" value="'.$qd_2['alias_name'].'" readonly/>';
							echo '<input type="hidden" name="quyetdinhchophep_filetype[]" value="'.$qd_2['filetype'].'" readonly/>';
							echo '<span class="mif-attachment prepend-icon"></span>';
							echo '<input type="text" name="quyetdinhchophep_filename[]" value="'.$qd_2['filename'].'" class="bg-grayLighter"/>';
							echo '<a href="get.xoataptin.php?filename='.$qd_2['alias_name'].'" onclick="return false;" class="delete_file button"><span class="mif-cross fg-red"></span></a>';
						echo '</div>';
					echo '</div>';
				}
			}
		?>
	</div>
	<div class="row cells12 doan_2">
		<div class="cell colspan2 padding-top-10 align-right">Danh sách đoàn</div>	
		<div class="cell colspan10 input-control select" data-role="select" data-multiple="true" data-placeholder="Danh sách thành viên đoàn">
			<select name="id_canbo_2[]" id="id_canbo_2" multiple class="select2">
				<option value="">Danh sách thành viên đoàn </option>
				<?php
				$canbo = new CanBo();$canbo_list = $canbo->get_all_list();
				$arr_cb = array();
				$i=1;
				if(isset($danhsachdoan_2) && $danhsachdoan_2){
					foreach ($danhsachdoan_2 as $key2 => $value2) {
						$canbo->id = $value2['id_canbo']; $cbo = $canbo->get_one();
						//$count = count($cbo['donvi']) - 1;
						$donvi->id = $value2['id_donvi'][0]; $dv = $donvi->get_one();
						$chucvu->id = $value2['id_chucvu']; $cv = $chucvu->get_one();
						if(isset($value2['id_ham']) && $value2['id_ham']) {$ham->id=$value2['id_ham'];$h = $ham->get_one(); $tenham=$h['ten'];} else { $tenham='';}
						$member = $i .' - '. $cbo['code'] . ' - ' . $cbo['hoten'] .' - '.$dv['ten']. ' - ' .$tenham.' '. $cv['ten'];
						$v = $cbo['_id'] . '-' . implode(",", $value2['id_donvi']) . '-' . $value2['id_chucvu'] . '-'.$value2['id_ham'];
						echo '<option value="'.$v.'" selected>'.$member.'</option>';
						array_push($arr_cb, $v);
						$i++;
					}
				}
				if(isset($canbo_list) && $canbo_list){
					foreach ($canbo_list as $cb) {
						//$count = count($cb['donvi']) - 1;
						foreach ($cb['donvi'] as $key => $value) {
							if(!in_array($cb['_id'].'-'.$value['id_donvi'].'-'.$value['id_chucvu'], $arr_cb)){
								if($value['id_donvi'][0]){
									$donvi->id = $value['id_donvi'][0]; $dv = $donvi->get_one();
									$tendonvi = $dv['ten'];
								} else { $tendonvi = 'Không biết'; }
								$chucvu->id = $value['id_chucvu']; $cv = $chucvu->get_one();
								if(isset($value['id_ham']) && $value['id_ham']) {$ham->id=$value['id_ham'];$h = $ham->get_one(); $tenham=$h['ten'];} else { $tenham='';}
								$member = $i . ' - '.$cb['code'] . ' - ' . $cb['hoten'] .' - '.$tendonvi. ' - ' . $tenham.' '. $cv['ten'];
								$v = $cb['_id'] . '-' . implode(",", $value['id_donvi']) . '-' . $value['id_chucvu'] . '-'.$value['id_ham']; 
								echo '<option value="'.$v.'">'.$member.'</option>';
								$i++;
							}
						}
					}
				}
				?>
			</select>
		</div>
	</div>
	<div class="row cells12">
		<div class="cell colspan2 padding-top-10 align-right">Ngày đi</div>
		<div class="cell colspan4 input-control text" data-role="datepicker" data-format="dd/mm/yyyy">
			<input type="text" name="ngaydi" id="ngaydi" value="<?php echo isset($ngaydi) ? $ngaydi : '';?>" placeholder="Ngày đi." data-inputmask="'alias': 'date'" class="ngaythangnam"/>
		</div>
		<div class="cell colspan2 padding-top-10 align-right">Ngày về</div>
		<div class="cell colspan3 input-control text" data-role="datepicker" data-format="dd/mm/yyyy">
			<input type="text" name="ngayve" id="ngayve" value="<?php echo isset($ngayve) ? $ngayve : '';?>" placeholder="Ngày về." data-inputmask="'alias': 'date'" class="ngaythangnam"/>
		</div>
		<div class="cell input-control text">
			<input type="text" name="songay" id="songay" value="<?php echo isset($songay) ? $songay : 0; ?>" readonly data-validate-func="min" data-validate-arg="1"/>
			 <span class="input-state-error mif-warning"></span><span class="input-state-success mif-checkmark"></span>
		</div>
	</div>
	<div class="row cells12">	
		<div class="cell colspan2 padding-top-10 align-right">Nước đến</div>
		<div class="cell colspan4 input-control select" data-role="select" data-multiple="true" data-placeholder="Quốc gia đến">
			<select name="id_quocgia[]" id="id_quocgia" class="select2" multiple>
			<option value="">Quốc Gia đến</option>
			<?php
				$quocgia = new QuocGia();$quocgia_list = $quocgia->get_all_list();
				if($quocgia_list){
					foreach ($quocgia_list as $qg) {
						echo '<option value="'.$qg['_id'].'"'.(in_array($qg['_id'] ,$id_quocgia) ? ' selected' :'').'>'.$qg['ten'].'</option>';
					}
				}
			?>
			</select>
		</div>
		<div class="cell colspan2 padding-top-10 align-right">Mục đích</div>
		<div class="cell colspan4 input-control select" data-role="select">
			<select name="id_mucdich" id="id_mucdich" class="select2">
			<?php
				$mucdich = new MucDich();$mucdich_list = $mucdich->get_all_list();
				if($mucdich_list){
					foreach ($mucdich_list as $md) {
						echo '<option value="'.$md['_id'].'"'.($id_mucdich==$md['_id']?' selected' : '').'>'.$md['ten'].'</option>';
					}
				}
			?>
			<div class="cell colspan3 padding-top-10 align-right">Mục đích</div>	
			</select>
		</div>
	</div>
	
	<div class="row cells12">
		<div class="cell colspan2 padding-top-10 align-right">Kinh phí</div>
		<div class="cell colspan4 input-control select" data-role="select">
			<select name="id_kinhphi" id="id_kinhphi" class="select2">
			<?php
				$kinhphi = new KinhPhi();$kinhphi_list = $kinhphi->get_all_list();
				if($kinhphi_list){
					foreach ($kinhphi_list as $kp) {
						echo '<option value="'.$kp['_id'].'"'.($id_kinhphi==$kp['_id'] ? ' selected' : '').'>'.$kp['ten'].'</option>';
					}
				}
			?>
			<div class="cell colspan3 padding-top-10 align-right">Mục đích</div>	
			</select>
		</div>
		<div class="cell colspan2 padding-top-10 align-right">Đơn vị mời: </div>
		<div class="cell colspan4 input-control select" data-role="select">
			<select name="id_donvimoi" id="id_donvimoi" class="select2">
			<option value="">Chọn đơn vị mời</option>
			<?php
				if($donvi_list){
					foreach ($donvi_list as $dvm) {
						echo '<option value="'.$dvm['_id'].'"'.($id_donvimoi==$dvm['_id'] ? ' selected' : '').'>'.$dvm['ten'].'</option>';
					}
				}
			?>
			<div class="cell colspan3 padding-top-10 align-right">Mục đích</div>	
			</select>
		</div>
	</div>
	<div class="row cells12">
		<div class="cell colspan2 padding-top-10 align-right">Nội dung</div>	
		<div class="cell colspan10 input-control textarea">
			<textarea name="noidung" id="noidung" placeholder="Nội dung"><?php echo isset($noidung) ? $noidung : ''; ?></textarea>
		</div>
	</div>
	<div class="row cells12">
		<div class="cell colspan2 padding-top-10 align-right">Ghi chú</div>	
		<div class="cell colspan10 input-control textarea">
			<textarea name="ghichu" id="ghichu" placeholder="Ghi chú"><?php echo isset($ghichu) ? $ghichu : ''; ?></textarea>
		</div>
	</div>
	<div class="row cells12">
		<div class="cell colspan12 align-center">
			<button name="submit" id="submit" value="OK" class="button primary"><span class="mif-checkmark"></span> Cập nhật</button>
			<a href="doanra.php" class="button"><span class="mif-keyboard-return"></span> Trở về</a>
		</div>
	</div>
</div>
</form>
<?php require_once('footer.php'); ?>