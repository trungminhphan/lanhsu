<?php
require_once('header.php');
$id = isset($_GET['id']) ? $_GET['id'] : '';
$act = isset($_GET['act']) ? $_GET['act'] : '';
$update = isset($_GET['update']) ? $_GET['update'] : '';
$abtc=new ABTC(); $canbo = new CanBo();$donvi=new DonVi();
$canbo_list = $canbo->get_all_list();
$query = array('_id' => new MongoId('56f20d64af62da97921579cb'));
$donvi_list = $donvi->get_all_list();
$donvi_list_capphep = $donvi->get_list_condition($query);$chophep = 1;$tinhtrang=0;$msg='';
if($id && $act=='del' && $users->is_admin()){
	$abtc->id = $id; $a = $abtc->get_one();
	$id_user = $users->get_userid();
	if($abtc->delete()){
		if($a['congvanxinphep']['attachments']){
		foreach ($a['congvanxinphep']['attachments'] as $key => $value) {
				@unlink($target_files . $value['alias_name']);
			}	
		}
		if($a['quyetdinhchophep']['attachments']){
			foreach ($a['quyetdinhchophep']['attachments'] as $key => $value) {
				@unlink($target_files . $value['alias_name']);
			}
		}
		if($a['giaytolienquan']){
			foreach ($a['giaytolienquan'] as $key => $value) {
				@unlink($target_files . $value['alias_name']);
			}
		}
		transfers_to('abtc.php?update=ok');
	} else {
		transfers_to('abtc.php?update=no');
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

	$chophep = isset($_POST['chophep']) ? $_POST['chophep'] : 0;
	$id_quocgia = isset($_POST['id_quocgia']) ? $_POST['id_quocgia'] : '';
	$thongtinthanhvien = array();
	$id_canbo = isset($_POST['id_canbo']) ? $_POST['id_canbo'] : '';
	$sothe = isset($_POST['sothe']) ? $_POST['sothe'] : '';
	$tinhtrang = isset($_POST['tinhtrang']) ? $_POST['tinhtrang'] : '';
	$ngaycap = isset($_POST['ngaycap']) ? $_POST['ngaycap'] : '';
	$ngayhethan = isset($_POST['ngayhethan']) ? $_POST['ngayhethan'] : '';
	if($id_canbo){
		foreach ($id_canbo as $k => $v) {
			$a = explode('-', $v);
			//$canbo->id = $v; $cb = $canbo->get_one();
			array_push($thongtinthanhvien, array('id_canbo' => new MongoId($a[0]),
											'id_donvi' => explode(",",$a[1]),
											'id_chucvu' => new MongoId($a[2]),
											'id_ham' => $a[3] ? new MongoId($a[3]) : '',
											'tinhtrang' => $tinhtrang[$k] ? $tinhtrang[$k] : 0,
											'sothe' => $sothe[$k] ? $sothe[$k] : '',
											'ngaycap' => $ngaycap[$k] ? new MongoDate(convert_date_dd_mm_yyyy($ngaycap[$k])) : '',
											'ngayhethan' => $ngayhethan[$k] ? new MongoDate(convert_date_dd_mm_yyyy($ngayhethan[$k])) : ''));
		}
	}

	$giaytolienquan = array();
	$giaytolienquan_alias_name = isset($_POST['giaytolienquan_alias_name']) ? $_POST['giaytolienquan_alias_name'] : '';
	$giaytolienquan_filename = isset($_POST['giaytolienquan_filename']) ? $_POST['giaytolienquan_filename'] : '';
	$giaytolienquan_filetype = isset($_POST['giaytolienquan_filetype']) ? $_POST['giaytolienquan_filetype'] : '';
	$giaytolienquan_key = isset($_POST['giaytolienquan_key']) ? $_POST['giaytolienquan_key'] : '';
	if($giaytolienquan_alias_name){
		foreach ($giaytolienquan_alias_name as $key => $value) {
			array_push($giaytolienquan, array('order'=>$giaytolienquan_key[$key], 'alias_name' => $value, 'filename' => $giaytolienquan_filename[$key], 'filetype'=>$giaytolienquan_filetype[$key]));
		}
	}

	$ghichu = isset($_POST['ghichu']) ? $_POST['ghichu'] : '';
	$id_user = $users->get_userid();
	$id = isset($_POST['id']) ? $_POST['id'] : '';
	$abtc->congvanxinphep = $congvanxinphep;
	$abtc->quyetdinhchophep = $quyetdinhchophep;
	$abtc->chophep = $chophep; 
	$abtc->id_quocgia = $id_quocgia;
	$abtc->thongtinthanhvien = $thongtinthanhvien;
	$abtc->giaytolienquan = $giaytolienquan;
	$abtc->ghichu = $ghichu;
	$abtc->id_user = $id_user;
	if($id){
		//edit
		$abtc->id = $id;
		if($abtc->edit()){
			transfers_to('themabtc.php?id='.$id.'&update=edit_ok');
		} else {
			transfers_to('themabtc.php?id='.$id.'&update=edit_no');
		}
	} else {
		//insert
		$id = new MongoId();
		$abtc->id = $id;
		if($abtc->insert()){
			transfers_to('themabtc.php?id='.$id.'&update=insert_ok');
		} else {
			transfers_to('themabtc.php?id='.$id.'&update=insert_no');
		}
	}
}
if($id){
	$abtc->id = $id; $a = $abtc->get_one();
	if($a['congvanxinphep']['id_donvi'][0]){
		$donvi->id = $a['congvanxinphep']['id_donvi'][0];
		$donvi_list_one = $donvi->get_one();
	} else { $donvi_list_one = ''; }
	$id_donvi_xinphep_1 = $a['congvanxinphep']['id_donvi'][0];
	$id_donvi_xinphep_2 = $a['congvanxinphep']['id_donvi'][1];
	$id_donvi_xinphep_3 = $a['congvanxinphep']['id_donvi'][2];
	$id_donvi_xinphep_4 = $a['congvanxinphep']['id_donvi'][3];
	$tencongvanxinphep = $a['congvanxinphep']['ten'];
	$filecongvanxinphep = $a['congvanxinphep']['attachments'];
	$ngaykycongvanxinphep = $a['congvanxinphep']['ngayky'] ? date("d/m/Y", $a['congvanxinphep']['ngayky']->sec) : '';
	$id_donvi_chophep = $a['quyetdinhchophep']['id_donvi'] ? $a['quyetdinhchophep']['id_donvi'] : '56f20d64af62da9792157931';
	$tenquyetdinhchophep = $a['quyetdinhchophep']['ten'];
	$filequyetdinhchophep = $a['quyetdinhchophep']['attachments'];
	$ngaybanhanhquyetdinhchophep = $a['quyetdinhchophep']['ngaybanhanh'] ? date("d/m/Y", $a['quyetdinhchophep']['ngaybanhanh']->sec) : '';
	$tencongvanxinphep = $a['congvanxinphep']['ten'];
	$filecongvanxinphep = $a['congvanxinphep']['attachments'];
	$ngaykycongvanxinphep = $a['congvanxinphep']['ngayky'] ? date("d/m/Y", $a['congvanxinphep']['ngayky']->sec) : '';
	$tenquyetdinhchophep = $a['quyetdinhchophep']['ten'];
	$filequyetdinhchophep = $a['quyetdinhchophep']['attachments'];
	$ngaybanhanhquyetdinhchophep = $a['quyetdinhchophep']['ngaybanhanh'] ? date("d/m/Y", $a['quyetdinhchophep']['ngaybanhanh']->sec) : '';
	$chophep = $a['chophep'];
	$id_quocgia = $a['id_quocgia'];
	$thongtinthanhvien = $a['thongtinthanhvien'];
	$giaytolienquan = $a['giaytolienquan'] ? sort_array($a['giaytolienquan'], 'order', SORT_ASC) : '';
	$ghichu = $a['ghichu'];
}
?>
<link rel="stylesheet" type="text/css" href="css/style.css">
<script type="text/javascript" src="js/select2.min.js"></script>
<script type="text/javascript" src="js/jquery.inputmask.js"></script>
<script type="text/javascript" src="js/abtc.js"></script>
<script type="text/javascript">
$(document).ready(function(){
	nhaplieu();upload_congvanxinphep(); upload_quyetdinhchophep();delete_file();hide_donvi_xinphep();
	id_donvi_xinphep_action_chitiet();upload_giaytolienquan();thanhviendoan();
	<?php if(isset($msg) && $msg) : ?>
        $.Notify({type: 'alert', caption: 'Thông báo', content: <?php echo "'".$msg."'"; ?>});
    <?php endif; ?>
});
</script>
<h1><a href="abtc.php" class="nav-button transform"><span></span></a>&nbsp;Cập nhật thông Cấp thẻ ABTC</h1>
<form action="<?php echo $_SERVER['REQUEST_URI']; ?>" method="POST" id="themabtcform" data-role="validator" data-show-required-state="false" enctype="multipart/form-data">
<input type="hidden" name="id" id="id" value="<?php echo isset($id) ? $id : ''; ?>" />
<div class="grid">
	<div class="row cells12">
		<div class="cell colspan2 padding-top-10 align-right"><b>1. Đơn vị xin phép</b></div>
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
			<input type="text" name="tencongvanxinphep" id="tencongvanxinphep" value="<?php echo isset($tencongvanxinphep) ? $tencongvanxinphep : ''; ?>" placeholder="- Số văn bản xin phép -" data-validate-func="required"/>
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
								echo '<a href="get.xoataptin.php?filename='.$cvxp['alias_name'].'" onclick="return false;" class="delete_file button"><span class="mif-cross fg-red"></span></a>';
								echo '<a href="'.$target_files.$cvxp['alias_name'].'" class="button" target="_blank"><span class="mif-file-download fg-blue"></span></a>';
							echo '</div>';
						echo '</div>';
					echo '</div>';
				}
			}
		?>
	</div>
	<div class="row cells12">
		<div class="cell colspan2 padding-top-10 align-right"><b>2. Đơn vị cho phép</b></div>
		<div class="cell colspan5 input-control select"  data-role="select">
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
		<div class="cell colspan2 input-control text">
			<input type="text" name="tenquyetdinhchophep" id="tenquyetdinhchophep" value="<?php echo isset($tenquyetdinhchophep) ? $tenquyetdinhchophep : ''; ?>" placeholder="Số văn bản cho phép" data-validate-func="required"/>
			<span class="input-state-error mif-warning"></span><span class="input-state-success mif-checkmark"></span>
		</div>
		<div class="cell colspan2 input-control text" data-role="datepicker" data-format="dd/mm/yyyy">
			<input type="text" name="ngaybanhanhquyetdinhchophep" id="ngaybanhanhquyetdinhchophep" value="<?php echo isset($ngaybanhanhquyetdinhchophep) ? $ngaybanhanhquyetdinhchophep : '';?>" placeholder="Ngày ký" data-inputmask="'alias': 'date'" class="ngaythangnam"/>
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
		<div class="cell colspan2 padding-top-10 align-right">Nước cấp thẻ</div>
		<div class="cell colspan6 input-control select" data-role="select" data-multiple="true" data-placeholder="Nước cấp thẻ">
			<select name="id_quocgia[]" id="id_quocgia" class="select2" multiple>
			<?php
				$quocgia = new QuocGia();$quocgia_list = $quocgia->get_all_list();
				if($quocgia_list){
					foreach ($quocgia_list as $qg) {
						if(in_array($qg['_id'], $id_quocgia)) $selected = 'selected';
						else $selected = '';
						echo '<option value="'.$qg['_id'].'"'.$selected.'>'.$qg['ten'].'</option>';
					}
				}
			?>
			</select>
		</div>
		<div class="cell colspan2 padding-top-10 align-right">Cho phép</div>
		<div class="cell colspan2 padding-top-10">
			<label class="switch">
			    <input type="checkbox" name="chophep" id="chophep" value="1" <?php echo $chophep == 1 ? ' checked' : ''; ?>/>
			    <span class="check"></span>
			</label>			
		</div>
	</div>
	
	<div class="row cells12">
		<div class="cell colspan12">
			<b>3. DANH SÁCH CÁ NHÂN ĐƯỢC CẤP PHÉP</b>
			<table class="table striped hovered border bordered" id="member_list" style="width:100%;">
			<thead>
				<tr>
					<th><a href="#" onclick="return false;" id="add_member"><span class="mif-plus"></span></a> Họ tên</th>
					<th>Tình trạng</th>
					<th>Số thẻ</th>
					<th>Ngày cấp</th>
					<th>Ngày hết hạn</th>
					<th>Xoá</th>
				</tr>
			</thead>
			<tbody>
			<?php if($id && $thongtinthanhvien) : ?>
				<?php foreach ($thongtinthanhvien as $member) : ?>
				<tr class="items">
					<td>
					<div class="input-control select">
						<select name="id_canbo[]" class="thanhviendoan">
							<?php
								foreach ($canbo_list as $cb) {
									echo '<option value="'.$cb['_id'].'"'.($member['id_canbo'] == $cb['_id'] ? ' selected' : '').'>'.$cb['hoten'].'</option>';
								}
							?>
						</select>
					</div>
					</td>
					<td>
						<div class="input-control select">
							<select name="tinhtrang[]" class="select2">
								<option value="0" <?php echo $member['tinhtrang']==0 ? ' selected' : ''; ?>>Cấp mới</option>
								<option value="1" <?php echo $member['tinhtrang']==1 ? ' selected' : ''; ?>>Gia hạn</option>
							</select>
						</div>
					</td>
					<td>
						<div class="input-control text" style="width:100px;">
							<input type="text" name="sothe[]" value="<?php echo isset($member['sothe']) ? $member['sothe'] : ''; ?>" style="width:100px;"/>
						</div>
					</td>
					<td class="align-center">
						<div class="input-control text" data-role="datepicker" data-format="dd/mm/yyyy">
							<input type="text" name="ngaycap[]" value="<?php echo $member['ngaycap'] ? date("d/m/Y",$member['ngaycap']->sec) : ''; ?>" placeholder="Ngày cấp." data-inputmask="'alias': 'date'" class="ngaythangnam" style="width:100px;"/>
						</div>
					</td>
					<td class="align-center">
						<div class="input-control text" data-role="datepicker" data-format="dd/mm/yyyy" style="width:100px;">
							<input type="text" name="ngayhethan[]" value="<?php echo $member['ngayhethan'] ? date("d/m/Y",$member['ngayhethan']->sec) : ''; ?>" placeholder="Ngày hết hạn." data-inputmask="'alias': 'date'" class="ngaythangnam" style="width:100px;"/>
						</div>
					</td>
					<td class="align-center"><a href="#" onclick="return false;" class="remove_member"><span class="mif-bin"></span></a></td>
				</tr>
				<?php endforeach; ?>
			<?php else : ?>
				<tr class="items">
					<td>
						<div class="input-control select">
							<select name="id_canbo[]" class=" thanhviendoan">
								<?php
									//foreach ($canbo_list as $cb) {
									//	echo '<option value="'.$cb['_id'].'">'.$cb['hoten'].'</option>';
									//}
								?>
							</select>
						</div>
					</td>
					<td>
						<div class="input-control select">
							<select name="tinhtrang[]" class="select2">
								<option value="0">Cấp mới</option>
								<option value="1">Gia hạn</option>
							</select>
						</div>
					</td>
					<td>
						<div class="input-control text" style="width:100px;">
							<input type="text" name="sothe[]" value="<?php echo isset($sothe) ? $sothe : ''; ?>" style="width:100px;"/>
						</div>
					</td>
					<td class="align-center">
						<div class="input-control text" data-role="datepicker" data-format="dd/mm/yyyy" style="width:100px;">
							<input type="text" name="ngaycap[]" value="<?php echo isset($ngaycap) ? $ngaycap : ''; ?>" placeholder="Ngày cấp." data-inputmask="'alias': 'date'" class="ngaythangnam" style="width:100px;"/>
						</div>
					</td>
					<td class="align-center">
						<div class="input-control text" data-role="datepicker" data-format="dd/mm/yyyy" style="width:100px;">
							<input type="text" name="ngayhethan[]" value="<?php echo isset($ngayhethan) ? $ngayhethan : ''; ?>" placeholder="Ngày hết hạn." data-inputmask="'alias': 'date'" class="ngaythangnam" style="width:100px;"/>
						</div>
					</td>
					<td class="align-center"><a href="#" onclick="return false;" class="remove_member"><span class="mif-bin"></span></a></td>
				</tr>
			<?php endif; ?>
			</tbody>
			</table>
		</div>
	</div>
	<div class="row cells12">
		<div class="cell colspan12"><b>4. CÁC GIẤY TỜ LIÊN QUAN</b></div>
	</div>
	<div class="row cells12">
		<div class="cell colspan11 input-control select">
			<select name="select_giaytolienquan" id="select_giaytolienquan">
			<?php
				foreach ($arr_giaytolienquan as $key => $value) {
					echo '<option value="'.$key.'">'.$value.'</option>';
				}
			?>
			</select>
		</div>
		<div class="cell input-control file upload" data-role="input">
			<input type="file" name="dinhkem_filegiaytolienquan[]" multiple="multiple" class="dinhkem_giaytolienquan" accept="*" />
		</div>
	</div>
	<div id="files_giaytolienquan">
		<?php
			if(isset($giaytolienquan) && $giaytolienquan){
				foreach ($giaytolienquan as $gt) {
					echo '<div class="row cells12" style="padding:0px 0px 10px 0px;margin:0px;">';
						echo '<div class="cell colspan12 input-control text">';
							echo '<input type="hidden" name="giaytolienquan_alias_name[]" value="'.$gt['alias_name'].'" readonly/>';
							echo '<input type="hidden" name="giaytolienquan_filetype[]" value="'.$gt['filetype'].'" readonly/>';
							echo '<input type="hidden" name="giaytolienquan_key[]" value="'.$gt['order'].'" readonly/>';
							echo '<span class="mif-attachment prepend-icon"></span>';
							echo '<input type="text" name="giaytolienquan_filename[]" value="'.$gt['filename'].'" class="bg-grayLighter" style="padding-left:50px;"/>';
							echo '<div class="button-group">';
								echo '<a href="get.xoataptin.php?filename='.$gt['alias_name'].'" onclick="return false;" class="delete_file button"><span class="mif-cross fg-red"></span></a>';
								echo '<a href="'.$target_files.$gt['alias_name'].'" class="button" target="_blank"><span class="mif-file-download fg-blue"></span></a>';
							echo '</div>';
						echo '</div>';
					echo '</div>';
				}
			}
		?>
	</div>
	<div class="row cells12">
		<div class="cell colspan12 input-control textarea">
			<textarea name="ghichu" id="ghichu" placeholder="Ghi chú"><?php echo isset($ghichu) ? $ghichu : ''; ?></textarea>
		</div>
	</div>
	<div class="row cells12">
		<div class="cell colspan12 align-center">
			<button name="submit" id="submit" value="OK" class="button primary"><span class="mif-checkmark"></span> Cập nhật</button>
			<a href="abtc.php" class="button"><span class="mif-keyboard-return"></span> Trở về</a>
		</div>
	</div>
</div>
</form>
<?php require_once('footer.php'); ?>