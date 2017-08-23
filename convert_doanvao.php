<?php
require_once('header.php');
$doanvao_regis = new DoanVao_Regis();
$doanvao = new DoanVao();$canbo = new CanBo(); $donvi = new DonVi(); $chucvu = new ChucVu();
$mucdich = new MucDich();$linhvuc = new LinhVuc();
$id_regis = isset($_GET['id']) ? $_GET['id'] : '';
$act = isset($_GET['act']) ? $_GET['act'] : '';
$query = array('$or' => array(array('_id' => new MongoId('56f20d64af62da9792157931')), array('_id' => new MongoId('56f20d64af62da97921579cb'))));
$donvi = new DonVi();
$donvi_list = $donvi->get_all_list();
$donvi_list_capphep = $donvi->get_list_condition($query);
$id_donvi_tiep=''; $id_donvi_duocphep = '56f20d64af62da9792157931'; $id_donvi_chophep = '56f20d64af62da97921579cb'; $id_dmdoanvao='';

if(isset($_POST['submit'])){
	$id_dmdoanvao = isset($_POST['id_dmdoanvao']) ? $_POST['id_dmdoanvao'] : '';
	$id_mucdich = isset($_POST['id_mucdich']) ? $_POST['id_mucdich'] : '';
	$id_linhvuc = isset($_POST['$id_linhvuc']) ? $_POST['$id_linhvuc'] : '';
	$masohoso = isset($_POST['masohoso']) ? $_POST['masohoso'] : '';
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
			if(file_exists($copy_source . $value)){
				//copy($folder_regis.$target_files_regis . $value, $target_files . $value);
				copy($$copy_source . $value, $copy_desc . $value);
			}
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

	$id_canbo_2 = isset($_POST['id_canbo_2']) ? $_POST['id_canbo_2'] : '';
	if(count($id_canbo_2) > 0){
		$id_donvi_chophep_2 = isset($_POST['id_donvi_chophep_2']) ? $_POST['id_donvi_chophep_2'] : '';
	} else {
		$id_donvi_chophep_2 = '';
	}
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

	$ngayden = isset($_POST['ngayden']) ? $_POST['ngayden'] : '';
	$ngaydi = isset($_POST['ngaydi']) ? $_POST['ngaydi'] : '';
	$ghichu = isset($_POST['ghichu']) ? $_POST['ghichu'] : '';
	$noidung = isset($_POST['noidung']) ? $_POST['noidung'] : '';
	$id_canbo = isset($_POST['id_canbo']) ? $_POST['id_canbo'] : '';
	$danhsachdoan = array();
	if($id_canbo){
		foreach ($id_canbo as $k => $v) {
			if($v){
				//$canbo->id = $v; $cb = $canbo->get_one();
				$a = explode('-', $v);
				array_push($danhsachdoan, array('id_canbo' => new MongoId($a[0]), 'id_donvi' => explode(",",$a[1]), 'id_chucvu' => new MongoId($a[2]), 'id_ham' => $a[3] ? new MongoId($a[3]) : ''));
			}
		}
	}
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
	$doanvao->id = $id;
	$doanvao->masohoso = $masohoso;
	$doanvao->id_dmdoanvao = $id_dmdoanvao;
	$doanvao->id_mucdich = $id_mucdich;
	$doanvao->id_linhvuc = $id_linhvuc;
	$doanvao->congvanxinphep = $congvanxinphep;
	$doanvao->quyetdinhchophep = $quyetdinhchophep;
	$doanvao->quyetdinhchophep_2 = $quyetdinhchophep_2;
	$doanvao->ngayden = $ngayden != '' ? new MongoDate(convert_date_dd_mm_yyyy($ngayden)) : '';
	$doanvao->ngaydi = $ngaydi != '' ? new MongoDate(convert_date_dd_mm_yyyy($ngaydi)) : '';
	$doanvao->danhsachdoan = $danhsachdoan;
	$doanvao->danhsachdoan_2 = $danhsachdoan_2;
	$doanvao->noidung = $noidung;
	$doanvao->ghichu = $ghichu;
	$doanvao->id_user = $users->get_userid();
	if($doanvao->convert()){
		//$doanvao_regis->id = $id;
		//$doanvao_regis->set_status(1);
		transfers_to('doanvao_regis.php?update=convert_ok');
	} else {
		$msg = 'Không thể xử lý hồ sơ';
	}
}

if($id_regis){
	$doanvao_regis->id = $id_regis; $dv = $doanvao_regis->get_one();
	$id = $dv['_id'];
	$id_dmdoanvao = $dv['id_dmdoanvao'];
	$id_mucdich = isset($dv['id_mucdich']) ? $dv['id_mucdich'] : '';
	if($dv['congvanxinphep']['id_donvi'][0]){
		$donvi->id = $dv['congvanxinphep']['id_donvi'][0];
		$donvi_list_one = $donvi->get_one();
	} else { $donvi_list_one = ''; }
	$id_donvi_xinphep_1 = $dv['congvanxinphep']['id_donvi'][0];
	$id_donvi_xinphep_2 = $dv['congvanxinphep']['id_donvi'][1];
	$id_donvi_xinphep_3 = $dv['congvanxinphep']['id_donvi'][2];
	$id_donvi_xinphep_4 = $dv['congvanxinphep']['id_donvi'][3];
	$tencongvanxinphep = $dv['congvanxinphep']['ten'];
	$filecongvanxinphep = $dv['congvanxinphep']['attachments'];//$dv['congvanxinphep']['filename'];
	$ngaykycongvanxinphep = $dv['congvanxinphep']['ngayky'] ? date("d/m/Y", $dv['congvanxinphep']['ngayky']->sec) : '';
	//$id_donvi_chophep = $dv['quyetdinhchophep']['id_donvi'] ? $dv['quyetdinhchophep']['id_donvi'] : '56f20d64af62da9792157931';
	//$tenquyetdinhchophep = $dv['quyetdinhchophep']['ten'];
	//$filequyetdinhchophep = $dv['quyetdinhchophep']['attachments'];
	//$ngaybanhanhquyetdinhchophep = $dv['quyetdinhchophep']['ngaybanhanh'] ? date("d/m/Y", $dv['quyetdinhchophep']['ngaybanhanh']->sec) : '';
	//$id_donvi_chophep_2 = isset($dv['quyetdinhchophep_2']['id_donvi']) ? $dv['quyetdinhchophep_2']['id_donvi'] : '';
	//$tenquyetdinhchophep_2 = isset($dv['quyetdinhchophep_2']['ten']) ? $dv['quyetdinhchophep_2']['ten'] : '';
	//$filequyetdinhchophep_2 = isset($dv['quyetdinhchophep_2']['attachments']) ? $dv['quyetdinhchophep_2']['attachments'] : '';
	//$ngaybanhanhquyetdinhchophep_2 = (isset($dv['quyetdinhchophep_2']['ngaybanhanh']) && $dv['quyetdinhchophep_2']['ngaybanhanh']) ? date("d/m/Y", $dv['quyetdinhchophep_2']['ngaybanhanh']->sec) : '';
	//$danhsachdoan = $dv['danhsachdoan'];
	//$danhsachdoan_2 = isset($dv['danhsachdoan_2']) ? $dv['danhsachdoan_2'] : '';
	$id_mucdich = isset($dv['id_mucdich']) ? $dv['id_mucdich'] : '';
	$id_linhvuc = isset($dv['$id_linhvuc']) ? $dv['$id_linhvuc'] : '';
	$ngayden = $dv['ngayden'] ? date("d/m/Y", $dv['ngayden']->sec)  : '';
	$ngaydi = $dv['ngaydi'] ? date("d/m/Y", $dv['ngaydi']->sec)  : '';
	$noidung = $dv['noidung'];$ghichu = $dv['ghichu'];
	$masohoso = $dv['masohoso'];
}
?>
<link href="css/style.css" rel="stylesheet">
<script type="text/javascript" src="js/jquery.inputmask.js"></script>
<script type="text/javascript" src="js/select2.min.js"></script>
<script type="text/javascript" src="js/doanvao.js"></script>
<script type="text/javascript">
$(document).ready(function(){
	nhaplieu();hide_donvi_xinphep();
	upload_congvanxinphep(); upload_quyetdinhchophep();upload_quyetdinhchophep_2(); delete_file();
	id_donvi_xinphep_action_chitiet();thanhviendoan();
	$("#id_donvi_xinphep2").show();
	<?php if(isset($id_donvi_xinphep_3) && $id_donvi_xinphep_3): ?>
		$("#id_donvi_xinphep3").show();
	<?php endif; ?>
	<?php if(isset($id_donvi_xinphep_4) && $id_donvi_xinphep_4): ?>
		$("#id_donvi_xinphep4").show();
	<?php endif; ?>
	<?php if(isset($msg) && $msg) : ?>
        $.Notify({type: 'alert', caption: 'Thông báo', content: <?php echo "'".$msg."'"; ?>});
    <?php endif; ?>
});
</script>

<h1><a href="doanvao_regis.php" class="nav-button transform"><span></span></a>&nbsp;Xử lý đăng ký trực tuyến - Đoàn Vào</h1>
<form action="<?php echo $_SERVER['REQUEST_URI']; ?>" method="POST" id="themdoanvaoform" data-role="validator" data-show-required-state="false" enctype="multipart/form-data">
<h4 style="text-align:center;">Mã số hồ sơ: <b><?php echo $masohoso; ?></b></h4>
<input type="hidden" name="masohoso" id="masohoso" value="<?php echo $masohoso; ?>" />
<input type="hidden" name="id" id="id" value="<?php echo isset($id) ? $id : ''; ?>" />
<div class="grid">
	<div class="row cells12" >
		<div class="cell colspan2 padding-top-10 align-right"><b>1. Tên đoàn</b></div>
		<div class="cell colspan4 input-control select">
		<select name="id_dmdoanvao" id="id_dmdoanvao" class="select2">
			<?php
				$dmdoanvao = new DMDoanVao();
				$dmdoanvao_list = $dmdoanvao->get_all_list();
				if($dmdoanvao_list){
					foreach ($dmdoanvao_list as $dm) {
						echo '<option value="'.$dm['_id'].'"'.($dm['_id']==$id_dmdoanvao ? ' selected' : '').'>'.$dm['ten'].'</option>';
					}
				}
			?>
		</select>
		</div>
		<div class="cell colspan3 input-control text" data-role="datepicker" data-format="dd/mm/yyyy">
			<input type="text" name="ngayden" id="ngayden" value="<?php echo isset($ngayden) ? $ngayden : '';?>" placeholder="Ngày đến." data-inputmask="'alias': 'date'" class="ngaythangnam"/>
		</div>
		<div class="cell colspan3 input-control text" data-role="datepicker" data-format="dd/mm/yyyy">
			<input type="text" name="ngaydi" id="ngaydi" value="<?php echo isset($ngaydi) ? $ngaydi : '';?>" placeholder="Ngày đi." data-inputmask="'alias': 'date'" class="ngaythangnam"/>
		</div>
	</div>
	<div class="row cells12">
		<div class="cell colspan2 padding-top-10 align-right"><b>2. Xin phép</b></div>
		<div class="cell colspan5 input-control select">
			<select name="id_donvi_xinphep_1" id="id_donvi_xinphep_1">
				<option value="">Chọn đơn vị xin phép</option>
				<?php
					if($donvi_list){
						foreach ($donvi_list as $dv) {
							echo '<option value="'.$dv['_id'].'"'.($dv['_id']==$id_donvi_xinphep_1 ? ' selected' : '').'>'.$dv['ten'].'</option>';
						}
					}
				?>
			</select>
		</div>
		<div class="cell colspan2 input-control text">
			<input type="text" name="tencongvanxinphep" id="tencongvanxinphep" value="<?php echo isset($tencongvanxinphep) ? $tencongvanxinphep : ''; ?>" placeholder="- Số công văn xin phép -" data-validate-func="required"/>
			<span class="input-state-error mif-warning"></span><span class="input-state-success mif-checkmark"></span>
		</div>
		<div class="cell colspan2 input-control text" data-role="datepicker" data-format="dd/mm/yyyy">
			<input type="text" name="ngaykycongvanxinphep" id="ngaykycongvanxinphep" value="<?php echo isset($ngaykycongvanxinphep) ? $ngaykycongvanxinphep : ''; ?>" placeholder="- Ngày ký công văn xin phép - " data-inputmask="'alias': 'date'" class="ngaythangnam"/>
		</div>
		<div class="cell input-control file upload" data-role="input">
			<input type="file" name="dinhkem_filecongvanxinphep[]" multiple="multiple" class="dinhkem_filecongvanxinphep" accept="*" />
		</div>
	</div>
	<div class="row cells12" id="id_donvi_xinphep2">
		<div class="cell colspan2"></div>
		<div class="cell colspan4 input-control select">
			<select name="id_donvi_xinphep_2" id="id_donvi_xinphep_2">
				<option value="">Chọn đơn vị</option>
				<?php
				if($donvi_list_one && $id && $id_donvi_xinphep_2){
					foreach ($donvi_list_one['level2'] as $k2 => $a2) {
						if($donvi_list_one['_id'] == $id_donvi_xinphep_1){
							echo '<option value="'.$a2['_id'].'"'.($a2['_id'] == $id_donvi_xinphep_2 ? ' selected': '').'>'.$a2['ten'].'</div>';
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
										echo '<option value="'.$a3['_id'].'"'.($a3['_id']==$id_donvi_xinphep_3 ? ' selected' : '').'>'.$a3['ten'].'</div>';
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
													echo '<option value="'.$a4['_id'].'"'.($a4['_id']==$id_donvi_xinphep_4 ? ' selected':'').'>'.$a4['ten'].'</div>';
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
							echo '<input type="text" name="congvanxinphep_filename[]" value="'.$cvxp['filename'].'" class="bg-grayLighter" style="padding-left:50px;"/>';
							echo '<div class="button-group">';
								echo '<a href="'.$folder_regis.'get.xoataptin.php?filename='.$cvxp['alias_name'].'" onclick="return false;" class="delete_file button"><span class="mif-cross fg-red"></span></a>';
								echo '<a href="'.$folder_regis.$target_files_regis.$cvxp['alias_name'].'" class="button" target="_blank"><span class="mif-file-download fg-blue"></span></a>';
							echo '</div>';
						echo '</div>';
					echo '</div>';
				}
			}
		?>
	</div>
	<div class="row cells12">
		<div class="cell colspan2 align-right "><b>3. Cấp phép cho:</b></div>
		<div class="cell colspan5"></div>
		<div class="cell colspan3"><b>4. Cơ quan cấp phép</b></div>
	</div>
	<div class="row cells12">
		<div class="cell colspan2 align-right padding-top-10">Thành viên </div>
		<div class="cell colspan5 input-control select" style="height:100%;position:relative;">
			<select name="id_canbo[]" id="id_canbo" class="thanhviendoan" multiple>
				<?php
				$canbo_list = $canbo->get_all_list();
				$arr_cb = array();
				if(isset($danhsachdoan) && $danhsachdoan){
					foreach ($danhsachdoan as $key => $value) {
						$canbo->id = $value['id_canbo']; $cbo = $canbo->get_one();
						$count = count($cbo['donvi']) - 1;
						$donvi->id = $value['id_donvi'][0]; $dv = $donvi->get_one();
						$chucvu->id = $value['id_chucvu']; $cv = $chucvu->get_one();
						if(isset($value['id_ham']) && $value['id_ham']) {$ham->id=$value['id_ham'];$h = $ham->get_one(); $tenham=$h['ten'];} else { $tenham='';}
						//$member = $i .' - '. $cbo['code'] . ' - ' . $cbo['hoten'] .' - '.$dv['ten']. ' - ' .$tenham . ' '. $cv['ten'];
						$member = $cbo['hoten'] . ' ['.$cbo['code'].']';
						$v = $cbo['_id'] . '-' . implode(",", $value['id_donvi']) . '-' . $value['id_chucvu'] . '-'.$value['id_ham'];
						echo '<option value="'.$v.'" selected>'.$member.'</option>';
						array_push($arr_cb, $v);
					}
				}
				?>
			</select>
			<span class="tag alert" style="padding:2px;"><span class="mif-info"></span> Chú ý: Thành viên chọn đầu tiên là TRƯỞNG ĐOÀN</span>
		</div>
		<div class="cell colspan2 input-control select"  data-role="select">
			<select name="id_donvi_chophep" id="id_donvi_chophep" class="select2">
			<?php
				if($donvi_list_capphep){
					foreach ($donvi_list_capphep as $dv) {
						echo '<option value="'.$dv['_id'].'"'.($dv['_id'] == $id_donvi_chophep ? ' selected' : '').'>'.$dv['ten'].'</option>';
					}
				}
			?>
			</select>
		</div>
		<div class="cell input-control text">
			<input type="text" name="tenquyetdinhchophep" id="tenquyetdinhchophep" value="<?php echo isset($tenquyetdinhchophep) ? $tenquyetdinhchophep : ''; ?>" placeholder="Số quyết định cho phép" data-validate-func="required"/>
			<span class="input-state-error mif-warning"></span><span class="input-state-success mif-checkmark"></span>
		</div>
		<div class="cell input-control text" data-role="datepicker" data-format="dd/mm/yyyy">
			<input type="text" name="ngaybanhanhquyetdinhchophep" id="ngaybanhanhquyetdinhchophep" value="<?php echo isset($ngaybanhanhquyetdinhchophep) ? $ngaybanhanhquyetdinhchophep : '';?>" placeholder="Ngày ký" data-inputmask="'alias': 'date'" class="ngaythangnam" style="width:100px;"/>
		</div>
		<div class="cell input-control file upload" data-role="input">
			<input type="file" name="dinhkem_quyetdinhchophep[]" multiple="multiple" class="dinhkem_quyetdinhchophep" accept="*" />
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
							echo '<input type="text" name="quyetdinhchophep_filename[]" value="'.$qd['filename'].'" class="bg-grayLighter" style="padding-left:50px;"/>';
							echo '<div class="button-group">';
								echo '<a href="get.xoataptin.php?filename='.$qd['alias_name'].'" onclick="return false;" class="delete_file button"><span class="mif-cross fg-red"></span></a>';
								echo '<a href="'.$target_files.$qd['alias_name'].'" class="button" target="_blank"><span class="mif-file-download fg-blue"></span></a>';
							echo '</div>';
						echo '</div>';
					echo '</div>';
				}
			}
		?>
	</div>

	<div class="row cells12">
		<div class="cell colspan2 padding-top-10 align-right">Thành viên khác</div>
		<div class="cell colspan5 input-control select" style="height:100%;position:relative;">
			<select name="id_canbo_2[]" id="id_canbo_2" multiple class="thanhviendoan">
				<option value="">Danh sách thành viên đoàn </option>
				<?php
				$arr_cb = array();
				$i=1;
				if(isset($danhsachdoan_2) && $danhsachdoan_2){
					foreach ($danhsachdoan_2 as $key2 => $value2) {
						$canbo->id = $value2['id_canbo']; $cbo = $canbo->get_one();
						//$count = count($cbo['donvi']) - 1;
						$donvi->id = $value2['id_donvi'][0]; $dv = $donvi->get_one();
						$chucvu->id = $value2['id_chucvu']; $cv = $chucvu->get_one();
						if(isset($value2['id_ham']) && $value2['id_ham']) {$ham->id=$value2['id_ham'];$h = $ham->get_one(); $tenham=$h['ten'];} else { $tenham='';}
						//$member = $i .' - '. $cbo['code'] . ' - ' . $cbo['hoten'] .' - '.$dv['ten']. ' - ' .$tenham.' '. $cv['ten'];
						$member = $cbo['hoten'] . ' ['.$cbo['code'].']';
						$v = $cbo['_id'] . '-' . implode(",", $value2['id_donvi']) . '-' . $value2['id_chucvu'] . '-'.$value2['id_ham'];
						echo '<option value="'.$v.'" selected>'.$member.'</option>';
						//array_push($arr_cb, $v);
						$i++;
					}
				}
				?>
			</select>
		</div>
		<div class="cell colspan2 input-control select">
			<select name="id_donvi_chophep_2" id="id_donvi_chophep_2" class="select2">
			<option value="">Chọn đơn vị cho phép</option>
			<?php
				if($donvi_list_capphep){
					foreach ($donvi_list_capphep as $dv2) {
						echo '<option value="'.$dv2['_id'].'"'.($dv2['_id'] == $id_donvi_chophep_2 ? ' selected' : '').'>'.$dv2['ten'].'</option>';
					}
				}
			?>
			</select>
		</div>
		<div class="cell input-control text">
			<input type="text" name="tenquyetdinhchophep_2" id="tenquyetdinhchophep_2" value="<?php echo isset($tenquyetdinhchophep_2) ? $tenquyetdinhchophep_2 : ''; ?>" placeholder="Số quyết định cho phép" />
			<span class="input-state-error mif-warning"></span><span class="input-state-success mif-checkmark"></span>
		</div>
		<div class="cell input-control text" data-role="datepicker" data-format="dd/mm/yyyy">
			<input type="text" name="ngaybanhanhquyetdinhchophep_2" id="ngaybanhanhquyetdinhchophep_2" value="<?php echo isset($ngaybanhanhquyetdinhchophep_2) ? $ngaybanhanhquyetdinhchophep_2 : '';?>" placeholder="Ngày ký" data-inputmask="'alias': 'date'" class="ngaythangnam" style="width:100px;"/>
		</div>
		<div class="cell input-control file upload" data-role="input">
			<input type="file" name="dinhkem_quyetdinhchophep_2[]" multiple="multiple" class="dinhkem_quyetdinhchophep_2" accept="*" />
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
	<div class="row cells12">
		<div class="cell colspan2 padding-top-10 align-right"><b>5. Mục đích</b></div>
		<div class="cell colspan4 input-control select">
		<select name="id_mucdich" id="id_mucdich" class="select2">
			<option value="">Chọn mục đích</option>
			<?php
			$mucdich_list = $mucdich->get_all_list();
			if($mucdich_list){
				foreach ($mucdich_list as $md) {
					echo '<option value="'.$md['_id'].'"'.($md['_id']==$id_mucdich ? ' selected' : '').'>'.$md['ten'].'</option>';
				}
			}
			?>
		</select>
		</div>
		<div class="cell colspan2 padding-top-10 align-right"><b>Lĩnh vực</b></div>
		<div class="cell colspan4 input-control select">
		<select name=id_linhvuc id=id_linhvuc class="select2">
			<option value="">Chọn lĩnh vực</option>
			<?php
			$linhvuc_list = $linhvuc->get_all_list();
			if($linhvuc_list){
				foreach ($linhvuc_list as $lv) {
					echo '<option value="'.$lv['_id'].'"'.($lv['_id']==$id_linhvuc ? ' selected' : '').'>'.$lv['ten'].'</option>';
				}
			}
			?>
		</select>
		</div>
	</div>
	<div class="row cells12">
		<div class="cell colspan2 padding-top-10 align-right"><b>6. Nội dung</b></div>
		<div class="cell colspan10 input-control textarea">
			<textarea name="noidung" id="noidung" placeholder="Nội dung"><?php echo isset($noidung) ? $noidung : ''; ?></textarea>
		</div>
	</div>
	<div class="row cells12">
		<div class="cell colspan2 padding-top-10 align-right"><b>7. Ghi chú</b></div>
		<div class="cell colspan10 input-control textarea">
			<textarea name="ghichu" id="ghichu" placeholder="Ghi chú"><?php echo isset($ghichu) ? $ghichu : ''; ?></textarea>
		</div>
	</div>
	<div class="row cells12">
		<div class="cell colspan12 align-center">
			<button name="submit" id="submit" value="OK" class="button primary"><span class="mif-checkmark"></span> Cập nhật</button>
			<a href="doanvao_regis.php" class="button"><span class="mif-keyboard-return"></span> Trở về</a>
		</div>
	</div>
</div>
</form>
<?php require_once('footer.php'); ?>
