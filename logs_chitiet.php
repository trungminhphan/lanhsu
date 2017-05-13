<?php
require_once('header_none.php');
if(!$users->is_admin()){
	echo 'Bạn không có quyền xem';
	exit();
}
$logs = new Logs();
$id = isset($_GET['id']) ? $_GET['id'] : '';
$id_next = isset($_GET['id_next']) ? $_GET['id_next'] : '';
$logs->id = $id; $l = $logs->get_one();
if($id_next){
	$logs->id = $id_next; $next = $logs->get_one();
}
?>
<?php if($l['collections'] == 'doanra'): ?>
<?php $donvi = new DonVi();$canbo = new CanBo();$donvi=new DonVi();$chucvu=new ChucVu();$ham=new Ham(); ?>
	<div class="grid example">
	<div class="row cells12">
		<div class="cell colspan12"><h3><span class="mif-users"></span> Danh sách thành viên</h3></div>
	</div>
	<div class="row cells12">
		<div class="cell colspan2 align-left"><b>Họ tên</b></div>
		<div class="cell colspan2"><b>Chức vụ</b></div>
		<div class="cell colspan4"><b>Đơn vị xin phép</b></div>
		<div class="cell colspan4"><b>Đơn vị cấp phép</b></div>
	</div>
	<?php
	$i = 1;
	foreach ($l['datas']['danhsachdoan'] as $k => $ds) {
		$canbo->id = $ds['id_canbo'];$cb = $canbo->get_one();
		$dv = $donvi->tendonvi($ds['id_donvi']);
		$chucvu->id = $ds['id_chucvu']; $cv=$chucvu->get_one();
		if(isset($ds['id_ham']) && $ds['id_ham']) {
			$ham->id = $ds['id_ham']; $h=$ham->get_one();$tenham=$h['ten'];
		} else { $tenham = '';}

		if($id_next && $l['datas']['danhsachdoan'] != $next['datas']['danhsachdoan']){
			$class_canbo='fg-red';
		} else {
			$class_canbo='';
		}
		echo '<div class="row cells12">';
			echo '<div class="cell colspan2 '.$class_canbo.'">'.$i. '. ' .$cb['hoten'].'</div>';
			echo '<div class="cell colspan2">'.$tenham . ' '.$cv['ten'].'</div>';
			if($k > 0 ){
				echo '<div class="cell colspan4 align-center">Như trên</div>';
				echo '<div class="cell colspan4 align-center">Như trên</div>';
			} else {
				echo '<div class="cell colspan4">';
				if(isset($l['datas']['congvanxinphep']['attachments'][0]['alias_name'])){
					echo '<span class="tag info padding5 margin5"><span class="mif-attachment"></span> <a href="'.$target_files.$l['datas']['congvanxinphep']['attachments'][0]['alias_name'].'" class="fg-white">'.$l['datas']['congvanxinphep']['attachments'][0]['filename'].'</a></span>';
				}
				if(isset($l['datas']['congvanxinphep']['attachments'][1]['filename'])){
					echo '<span class="tag info padding5 margin5"><span class="mif-attachment"></span> <a href="'.$target_files.$l['datas']['congvanxinphep']['attachments'][1]['alias_name'].'" class="fg-white">'.$l['datas']['congvanxinphep']['attachments'][1]['filename'].'</a></span>';
				}
				echo '</div>';
				echo '<div class="cell colspan4">';
				if(isset($l['datas']['quyetdinhchophep']['attachments'][0]['alias_name'])){
					echo '<span class="tag info padding5 margin5"><span class="mif-attachment"></span> <a href="'.$target_files.$l['datas']['quyetdinhchophep']['attachments'][0]['alias_name'].'" class="fg-white">'.$l['datas']['quyetdinhchophep']['attachments'][0]['filename'].'</a></span>';
				}
				echo '</div>';
			}
		echo '</div>';$i++;
	}

	if(isset($l['datas']['danhsachdoan_2']) && $l['datas']['danhsachdoan_2']){
		foreach ($l['datas']['danhsachdoan_2'] as $k2 => $ds_2) {
			$canbo->id = $ds_2['id_canbo'];$cb = $canbo->get_one();
			//$donvi->id = $ds['id_donvi'][0]; $dv = $donvi->get_one();
			$dv = $donvi->tendonvi($ds_2['id_donvi']);
			$chucvu->id = $ds_2['id_chucvu']; $cv=$chucvu->get_one();
			if(isset($ds_2['id_ham']) && $ds_2['id_ham']) {
				$ham->id = $ds_2['id_ham']; $h=$ham->get_one();$tenham=$h['ten'];
			} else { $tenham = '';}
			if($id_next && $l['datas']['danhsachdoan_2'] != $next['datas']['danhsachdoan_2']){
				$class_canbo='fg-red';
			} else {
				$class_canbo='';
			}
			echo '<div class="row cells12">';
				echo '<div class="cell colspan2 '.$class_canbo.'">'.$i. '. ' .$cb['hoten'].'</div>';
				echo '<div class="cell colspan2">'.$tenham . ' '.$cv['ten'].'</div>';
				if($k2 > 0){
					echo '<div class="cell colspan4 align-center">Như trên</div>';
					echo '<div class="cell colspan4 align-center">Như trên</div>';
				} else {
					echo '<div class="cell colspan4"><span class="tag info padding5 margin5">';
					if(isset($l['datas']['congvanxinphep']['attachments'][0]['filename'])){
						echo '<span class="mif-attachment"></span> <a href="'.$target_files.$l['datas']['congvanxinphep']['attachments'][0]['alias_name'].'" class="fg-white">'.$l['datas']['congvanxinphep']['attachments'][0]['filename'].'</a></span>';
					}
					echo '</div>';
					echo '<div class="cell colspan4">';
					if(isset($l['datas']['quyetdinhchophep_2']['attachments'][0]['alias_name'])){
						echo '<span class="mif-attachment"></span> <span class="tag info padding5 margin5"><a href="'.$target_files.$l['datas']['quyetdinhchophep_2']['attachments'][0]['alias_name'].'" class="fg-white">'.$l['datas']['quyetdinhchophep_2']['attachments'][0]['filename'].'</a></span>';
					}
					echo '</div>';
				}
			echo '</div>';$i++;
		}	
	}
	?>
</div>
<div class="grid example">
	<div class="row cells12">
		<?php
			if($id_next && date("d/m/Y",$l['datas']['ngaydi']->sec) != date("d/m/Y",$next['datas']['ngaydi']->sec)){
				$class_ngaydi = 'fg-red';
			} else { $class_ngaydi = ''; }
			if($id_next && date("d/m/Y",$l['datas']['ngayve']->sec) != date("d/m/Y", $next['datas']['ngayve']->sec)){
				$class_ngayve = 'fg-red';
			} else { $class_ngayve = ''; }
			if($id_next && $l['datas']['songay'] != $next['datas']['songay']){
				$class_songay = 'fg-red';
			} else { $class_songay = ''; }
		?>
		<div class="cell colspan4 <?php echo $class_ngaydi;?>">Ngày đi: <?php echo $l['datas']['ngaydi'] ? date("d/m/Y",$l['datas']['ngaydi']->sec) : ''; ?></div>
		<div class="cell colspan4 <?php echo $class_ngayve;?>">Ngày về: <?php echo $l['datas']['ngayve'] ? date("d/m/Y",$l['datas']['ngayve']->sec) : ''; ?></div>
     	<div class="cell colspan4 <?php echo $class_songay;?>">Số ngày: <?php echo $l['datas']['songay']; ?></div>
	</div>
	<div class="row cells12">
		<?php
		$quocgia = new QuocGia();$qg = $quocgia->get_quoctich($l['datas']['id_quocgia']);
		$mucdich = new MucDich();
		if(isset($l['datas']['id_mucdich']) && $l['datas']['id_mucdich']){
			$mucdich->id = $l['datas']['id_mucdich']; $md = $mucdich->get_one();
			$tenmucdich = $md['ten'];
		} else { $tenmucdich = ''; }
		$kinhphi = new KinhPhi();
		if(isset($l['datas']['id_kinhphi']) && $l['datas']['id_kinhphi']){
			$kinhphi->id = $l['datas']['id_kinhphi']; $kp = $kinhphi->get_one();
			$tenkinhphi = $kp['ten'];
		} else { $tenkinhphi = '';}
		if(isset($l['datas']['id_donvimoi']) && $l['datas']['id_donvimoi'] && is_array($l['datas']['id_donvimoi'])){
			//$donvi->id = $l['datas']['id_donvimoi'];$dvm = $donvi->get_one();$tendonvimoi = $dvm['ten'];
			$tendonvimoi = $donvi->tendonvi($l['datas']['id_donvimoi']);
		} else { $tendonvimoi = ''; }
		//class fg-red
		if($id_next && $l['datas']['id_quocgia'] != $next['datas']['id_quocgia']) $class_quocgia = 'fg-red';
		else $class_quocgia = '';
		if($id_next && $l['datas']['id_mucdich'] != $next['datas']['id_mucdich']) $class_mucdich = 'fg-red';
		else $class_mucdich = '';
		if($id_next && $l['datas']['id_kinhphi'] != $next['datas']['id_kinhphi']) $class_kinhphi = 'fg-red';
		else $class_kinhphi = '';
		if($id_next && $l['datas']['id_donvimoi'] != $next['datas']['id_donvimoi']) $class_donvimoi = 'fg-red';
		else $class_donvimoi = '';
		if($id_next && $l['datas']['noidung'] != $next['datas']['noidung']) $class_noidung = 'fg-red';
		else $class_noidung = '';
		if($id_next && $l['datas']['ghichu'] != $next['datas']['ghichu']) $class_ghichu = 'fg-red';
		else $class_ghichu = '';
		?>
		<div class="cell colspan4 <?php echo $class_quocgia; ?>">Nước đến: <?php echo $qg; ?></div>
		<div class="cell colspan4 <?php echo $class_mucdich; ?>">Mục đích: <?php echo $tenmucdich; ?></div>
     	<div class="cell colspan4 <?php echo $class_kinhphi; ?>">Kinh phí: <?php echo $tenkinhphi; ?></div>
	</div>
	<div class="row cells12">
		<div class="cell colspan6 <?php echo $class_donvimoi; ?>">Đơn vị mời: <?php echo $tendonvimoi; ?></div>
	</div>
	<div class="row cells12">
		<div class="cell colspan12 <?php echo $class_noidung; ?>">Nội dung: <?php echo $l['datas']['noidung']; ?></div>
	</div>
	<div class="row cells12">
		<div class="cell colspan12 <?php echo $class_ghichu; ?>">Ghi chú: <?php echo $l['datas']['ghichu']; ?></div>
	</div>
</div>
<?php endif; ?>

<!--------------------- Doan vao ------------------------>
<?php if($l['collections'] == 'doanvao'): ?>
<?php $doanvao = new DoanVao();$donvi = new DonVi();$canbo = new CanBo();$chucvu=new ChucVu();
$dmdoanvao = new DMDoanVao();$ham = new Ham();$mucdich=new MucDich(); ?>
<div class="grid example">
	<div class="row cells12">
		<div class="cell colspan12"><h3><span class="mif-users"></span> Danh sách thành viên</h3></div>
	</div>
	<div class="row cells12">
		<div class="cell colspan2 align-left"><b>Họ tên</b></div>
		<div class="cell colspan2"><b>Chức vụ</b></div>
		<div class="cell colspan4"><b>Đơn vị xin phép</b></div>
		<div class="cell colspan4"><b>Đơn vị cấp phép</b></div>
	</div>
	<?php
	$i = 1;
	foreach ($l['datas']['danhsachdoan'] as $k => $ds) {
		$canbo->id = $ds['id_canbo'];$cb = $canbo->get_one();
		//$donvi->id = $ds['id_donvi']; $dvi = $donvi->get_one();
		//$dvi = $donvi->tendonvi($ds['id_donvi']);
		if(isset($l['datas']['id_dmdoanvao']) && $l['datas']['id_dmdoanvao']){
			$dmdoanvao->id = $l['datas']['id_dmdoanvao']; $d = $dmdoanvao->get_one();
			$tendoanvao = $d['ten'];
		} else { $tendoanvao = ''; }
		if(isset($ds['id_chucvu']) && $ds['id_chucvu']){
			$chucvu->id = $ds['id_chucvu']; $cv=$chucvu->get_one();
			$tenchucvu = $cv['ten'];
		} else { $tenchucvu = ''; }
		if(isset($ds['id_ham']) && $ds['id_ham']){
			$ham->id = $ds['id_ham']; $h = $ham->get_one(); $tenham = $h['ten'];
		} else { $tenham = ''; }
		if($id_next && $l['datas']['danhsachdoan'] != $next['datas']['danhsachdoan']){
			$class_canbo = 'fg-red';
		} else { $class_canbo = ''; }
		echo '<div class="row cells12">';
			echo '<div class="cell colspan2 '.$class_canbo.'">'.$i. '. ' .$cb['hoten'].'</div>';
			echo '<div class="cell colspan2">'.($tenham ? $tenham . ',' : '') .' '. $tenchucvu.'</div>';
			if($k > 0){
				echo '<div class="cell colspan4 align-center">Như trên</div>';
				echo '<div class="cell colspan4 align-center">Như trên</div>';
			} else {
				echo '<div class="cell colspan4">';
				if(isset($l['datas']['congvanxinphep']['attachments'][0]['filename'])){
					echo '<span class="tag info padding5 margin5"><a href="'.$target_files.$l['datas']['congvanxinphep']['attachments'][0]['alias_name'].'" class="fg-white">'.$l['datas']['congvanxinphep']['attachments'][0]['filename'].'</a></span>';
				}
				if(isset($l['datas']['congvanxinphep']['attachments'][1]['filename'])){
					echo '<span class="tag info padding5 margin5"><a href="'.$target_files.$l['datas']['congvanxinphep']['attachments'][1]['alias_name'].'" class="fg-white">'.$l['datas']['congvanxinphep']['attachments'][1]['filename'].'</a></span>';
				}
				echo '</div>';
				echo '<div class="cell colspan4">';
				if(isset($l['datas']['quyetdinhchophep']['attachments'][0]['filename'])){
					echo '<span class="tag info padding5 margin5"><a href="'.$target_files.$l['datas']['quyetdinhchophep']['attachments'][0]['alias_name'].'" class="fg-white">'.$l['datas']['quyetdinhchophep']['attachments'][0]['filename'].'</a></span>';
				}
				echo '</div>';
			}
		echo '</div>';$i++;
	}

	if(isset($l['datas']['danhsachdoan_2']) && $l['datas']['danhsachdoan_2']){
		foreach ($l['datas']['danhsachdoan_2'] as $k2 => $dv_2) {
			$canbo->id = $dv_2['id_canbo'];$cb = $canbo->get_one();
			//$donvi->id = $ds['id_donvi'][0]; $dv = $donvi->get_one();
			//$dvi = $donvi->tendonvi($ds_2['id_donvi']);
			$chucvu->id = $dv_2['id_chucvu']; $cv=$chucvu->get_one();
			if(isset($dv_2['id_ham']) && $dv_2['id_ham']) {
				$ham->id = $dv_2['id_ham']; $h=$ham->get_one();$tenham=$h['ten'];
			} else { $tenham = '';}
			if($id_next && $l['datas']['danhsachdoan_2'] != $next['datas']['danhsachdoan_2']){
				$class_canbo = 'fg-red';
			} else { $class_canbo = '';}
			echo '<div class="row cells12">';
				echo '<div class="cell colspan2 '.$class_canbo.'">'.$i. '. ' .$cb['hoten'].'</div>';
				echo '<div class="cell colspan2">'.$tenham . ' '.$cv['ten'].'</div>';
				if($k2>0){
					echo '<div class="cell colspan4 align-center">Như trên</div>';
					echo '<div class="cell colspan4 align-center">Như trên</div>';
				} else {
					echo '<div class="cell colspan4">';
					if(isset($l['datas']['congvanxinphep']['attachments'][0]['filename'])){
						echo '<span class="tag info padding5 margin5"><a href="'.$target_files.$l['datas']['congvanxinphep']['attachments'][0]['alias_name'].'" class="fg-white">'.$l['datas']['congvanxinphep']['attachments'][0]['filename'].'</a></span>';
					} else { echo ''; }
					echo '</div>';
					echo '<div class="cell colspan4">';
					if(isset($l['datas']['quyetdinhchophep_2']['attachments'][0]['filename'])){
						echo '<span class="tag info padding5 margin5"><a href="'.$target_files.$l['datas']['quyetdinhchophep_2']['attachments'][0]['alias_name'].'" class="fg-white">'.$l['datas']['quyetdinhchophep_2']['attachments'][0]['filename'].'</a></span>';
					} else { echo ''; }
					echo '</div>';
				}

			echo '</div>';$i++;
		}	
	}
	?>
	<?php
	if($id_next && $l['datas']['id_dmdoanvao'] != $next['datas']['id_dmdoanvao']){
		$class_dmdoanvao = 'fg-red';
	} else { $class_dmdoanvao = ''; }
	if($id_next && date("d/m/Y",$l['datas']['ngayden']->sec) != date("d/m/Y",$next['datas']['ngayden']->sec)){
		$class_ngayden = 'fg-red';
	} else { $class_ngayden = ''; }
	if($id_next && date("d/m/Y",$l['datas']['ngaydi']->sec) != date("d/m/Y",$next['datas']['ngaydi']->sec)){
		$class_ngaydi = 'fg-red';
	} else { $class_ngaydi = ''; }
	if($id_next && $l['datas']['id_mucdich'] != $next['datas']['id_mucdich']){
		$class_mucdich = 'fg-red';
	} else { $class_mucdich = ''; }
	if($id_next && $l['datas']['noidung'] != $next['datas']['noidung']){
		$class_noidung = 'fg-red';
	} else { $class_noidung = ''; }
	if($id_next && $l['datas']['ghichu'] != $next['datas']['ghichu']){
		$class_ghichu= 'fg-red';
	} else { $class_ghichu = ''; }
	?>
	<div class="row cells12">
		<div class="cell colspan4 <?php echo $class_dmdoanvao; ?>">Tên đoàn: <?php echo $tendoanvao; ?></div>
		<div class="cell colspan4 <?php echo $class_ngayden; ?>">Ngày đến: <?php echo $l['datas']['ngayden'] ? date("d/m/Y",$l['datas']['ngayden']->sec) : ''; ?></div>
		<div class="cell colspan4 <?php echo $class_ngaydi; ?>">Ngày đi: <?php echo $l['datas']['ngaydi'] ? date("d/m/Y",$l['datas']['ngaydi']->sec) : ''; ?></div>
	</div>
	<div class="row cells12">
		<div class="cell colspan2">Mục đích:</div>
		<div class="cell colspan10 <?php echo $class_mucdich; ?>">
		<?php
			if(isset($l['datas']['id_mucdich']) && $l['datas']['id_mucdich']){
				$mucdich->id = $l['datas']['id_mucdich'];$md=$mucdich->get_one();
				echo $md['ten'];
			}
		?>
		</div>
	</div>
	<div class="row cells12">
		<div class="cell colspan2">Nội dung:</div>
		<div class="cell colspan10 <?php echo $class_noidung; ?>"><?php echo nl2br($l['datas']['noidung']); ?></div>
	</div>
	<div class="row cells12">
		<div class="cell colspan2">Ghi chú:</div>
		<div class="cell colspan10 <?php echo $class_ghichu; ?>"><?php echo nl2br($l['datas']['ghichu']); ?></div>
	</div>
</div>
<?php endif; ?>

<?php if($l['collections'] == 'abtc'): ?>
<?php $abtc = new ABTC();$canbo = new CanBo();$donvi= new DonVi();$chucvu=new ChucVu();$quocgia = new QuocGia(); ?>
<div class="grid example">
	<div class="row cells12">
		<div class="cell colspan12"><h3><span class="mif-users"></span> Danh sách thành viên</h3></div>
	</div>
	<div class="row cells12">
		<div class="cell colspan6"><b>Đơn vị xin phép</b></div>
		<div class="cell colspan6"><b>Đơn vị cấp phép</b></div>
	</div>
	<?php
	$i = 1;
		echo '<div class="row cells12">';
			echo '<div class="cell colspan6">';
			if(isset($l['datas']['congvanxinphep']['attachments'])){
				foreach ($l['datas']['congvanxinphep']['attachments'] as $key => $value) {
					echo '<span class="tag info padding5 margin5"><a href="'.$target_files.$value['alias_name'].'" class="fg-white">'.$value['filename'].'</a></span>';
				}
			}
			echo '</div>';
			echo '<div class="cell colspan6">';
			if(isset($l['datas']['quyetdinhchophep']['attachments'])){
				foreach ($l['datas']['quyetdinhchophep']['attachments'] as $key => $value) {
					echo '<span class="tag info padding5 margin5"><a href="'.$target_files.$value['alias_name'].'" class="fg-white">'.$value['filename'].'</a></span>';
				}
			} else { echo ''; }
			echo '</div>';
		echo '</div>';$i++;
	

	?>
</div>
<div class="grid example">
	<div class="row cells12">
		<div class="cell colspan12">
			<h3><span class="mif-users"></span> Thông tin thành viên</h3>
		</div>
		<div class="row cells12">
			<div class="cell colspan2"><b>Họ tên</b></div>
			<div class="cell colspan2"><b>Đơn vị</b></div>
			<div class="cell colspan2"><b>Chức vụ</b></div>
			<div class="cell"><b>Số hộ chiếu</b></div>
			<div class="cell"><b>Tình trạng</b></div>
			<div class="cell colspan2"><b>Ngày cấp</b></div>
			<div class="cell colspan2"><b>Ngày hết hạn</b></div>
		</div>
		<?php
		if($id_next && $l['datas']['thongtinthanhvien'] != $next['datas']['thongtinthanhvien']){
			$class_canbo = 'fg-red';
		} else { $class_canbo = ''; }
		if($l['datas']['thongtinthanhvien']){
			foreach ($l['datas']['thongtinthanhvien'] as $member) {
				$canbo->id = $member['id_canbo'];$cb=$canbo->get_one();
				//$donvi->id = $member['id_donvi']; $dv=$donvi->get_one();
				$dv = $donvi->tendonvi($member['id_donvi']);
				$chucvu->id= $member['id_chucvu']; $cv=$chucvu->get_one();
				$count = count($cb['passport']) - 1;
				if($member['tinhtrang'] == 1) $tinhtrang = 'Gia hạn';
				else $tinhtrang = 'Cấp mới';
				echo '<div class="row cells12">';
				echo '<div class="cell colspan2 '.$class_canbo.'">'.$cb['hoten'].'</div>';
				echo '<div class="cell colspan2">'.$dv.'</div>';
				echo '<div class="cell colspan2">'.$cv['ten'].'</div>';
				echo '<div class="cell">'.$cb['passport'][$count].'</div>';
				echo '<div class="cell">'.$tinhtrang.'</div>';
				echo '<div class="cell colspan2">'.($member['ngaycap'] ? date("d/m/Y", $member['ngaycap']->sec) : '').'</div>';
				echo '<div class="cell colspan2">'.($member['ngayhethan'] ? date("d/m/Y", $member['ngayhethan']->sec) : '').'</div>';
				echo '</div>';
			}
		}
		?>
	</div>
</div>
<?php
	if($id_next && $l['datas']['id_quocgia'] != $next['datas']['id_quocgia']){
		$class_quocgia = 'fg-red';
	} else { $class_quocgia = ''; }
	if($id_next && $l['datas']['chophep'] != $next['datas']['chophep']){
		$class_chophep = 'fg-red';
	} else { $class_chophep = ''; }
	if($id_next && $l['datas']['ghichu'] != $next['datas']['ghichu']){
		$class_ghichu = 'fg-red';
	} else { $class_ghichu = ''; }
	if($id_next && $l['datas']['giaytolienquan'] != $next['datas']['giaytolienquan']){
		$class_giaytolienquan = 'fg-red';
	} else { $class_giaytolienquan = '';}
?>
<div class="grid example">
	<div class="row cells12">
		<div class="cell colspan2">Nước cấp thẻ: </div>
		<div class="cell colspan6 <?php echo $class_quocgia; ?>"><?php echo $quocgia->get_quoctich($l['datas']['id_quocgia']); ?></div>
		<div class="cell colspan4 <?php echo $class_chophep; ?>">Tình trạng: 
			<?php
			if($l['datas']['chophep'] == 1) echo '<span class="mif-checkmark fg-blue"></span> Cho phép';
			else echo '<span class="mif-cancel fg-red"></span> Không cho phép';
			?>
		</div>
	</div>
	<div class="row cells12">
		<div class="cell colspan12 <?php echo $class_ghichu; ?>">Ghi chú: <?php echo $l['datas']['ghichu']; ?></div>
	</div>
	<div class="row cells12">
		<div class="cell colspan12">
			<b class="<?php echo $class_giaytolienquan; ?>">Hồ sơ kèm theo</b>
			<?php
			if(isset($l['datas']['giaytolienquan'])){
				$arr_giaytolienquan = sort_array($l['datas']['giaytolienquan'], 'order', SORT_ASC);
				foreach ($arr_giaytolienquan as $gt) {
					echo '<span class="tag info padding5 margin5"><a href="'.$target_files.$gt['alias_name'].'" class="fg-white">'.$gt['filename'].'</a></span>';
				}
			}
			?>
		</div>
	</div>
</div>
<?php endif; ?>

<?php if($l['collections'] == 'canbo'): ?>
<?php
	$canbo = new CanBo();$quocgia = new QuocGia();$doanra=new DoanRa();$doanvao = new DoanVao();$ham=new Ham();
	$chucvu = new ChucVu();$nghenghiep=new NgheNghiep(); $dantoc=new DanToc();
	if($l['datas']['id_quoctich']){
		$quocgia->id = $l['datas']['id_quoctich'];$qg = $quocgia->get_one();
		$tenquoctich = $qg['ten'];
	} else { $tenquoctich = '';}
?>
<div class="grid example">
	<div class="row cells12">
		<div class="cell colspan3">
		<?php 
			if(file_exists($target_images . $l['datas']['hinhanh']) && $l['datas']['hinhanh']){
				$hinh = $target_images . $l['datas']['hinhanh'];
			} else {
				$hinh = 'images/no_pic.png';
			}
		?>
		<img src="<?php echo $hinh; ?>" style="max-height: 200px; max-width: 150px;" />
		</div>
		<div class="cell colspan9">
			<div class="grid">
				<div class="row cells9">
					<?php
						if($id_next &&  $l['datas']['cmnd']!= $next['datas']['cmnd']){
							$class_cmnd='fg-red';
						} else $class_cmnd = '';
						if($id_next &&  $l['datas']['passport']!= $next['datas']['passport']){
							$class_passport='fg-red';
						} else $class_passport = '';

					?>
					<div class="cell colspan2">CMND</div>
					<div class="cell colspan3 <?php echo $class_cmnd; ?>"><b><?php echo $l['datas']['cmnd']; ?></b></div>
					<div class="cell colspan2">Passport</div>
					<div class="cell colspan2 <?php echo $class_passport; ?>">
						<?php
							$count = count($l['datas']['passport'])-1;
						 	for($i=$count; $i>=0; $i--){
								echo '<b>'.$l['datas']['passport'][$i] . '</b><br />';
							}		 
						?>
					</div>
				</div>
				<?php
					if($id_next &&  $l['datas']['hoten']!= $next['datas']['hoten']){
						$class_hoten='fg-red';
					} else $class_hoten = '';
					if($id_next && $l['datas']['ngaysinh'] && date("d/m/Y",$l['datas']['ngaysinh']->sec)!= date("d/m/Y",$next['datas']['ngaysinh']->sec)){
						$class_ngaysinh='fg-red';
					} else $class_ngaysinh = '';
				?>
				<div class="row cells9">
					<div class="cell colspan2">Họ tên</div>
					<div class="cell colspan3 <?php echo $class_hoten;?>"><b><?php echo $l['datas']['hoten']; ?></b></div>
					<div class="cell colspan2">Ngày sinh</div>
					<div class="cell colspan2 <?php echo $class_ngaysinh;?>"><b><?php echo $l['datas']['ngaysinh'] ? date('d/m/Y', $l['datas']['ngaysinh']->sec) : ''; ?></b></div>
				</div>
				<?php
					if($id_next &&  $l['datas']['gioitinh']!= $next['datas']['gioitinh']){
						$class_gioitinh='fg-red';
					} else $class_gioitinh = '';
					if($id_next &&  $l['datas']['id_quoctich']!= $next['datas']['id_quoctich']){
						$class_quoctich='fg-red';
					} else $class_quoctich = '';
				?>
				<div class="row cells9">
					<div class="cell colspan2">Giới tính</div>
					<div class="cell colspan3 <?php echo $class_gioitinh;?>"><b><?php echo $l['datas']['gioitinh']; ?></b></div>
					<div class="cell colspan2">Quốc tịch</div>
					<div class="cell colspan2 <?php echo $class_quoctich;?>"><b><?php echo $tenquoctich; ?></b></div>
				</div>
				<?php
					if($id_next &&  $l['datas']['diachi']!= $next['datas']['diachi']){
						$class_diachi='fg-red';
					} else $class_diachi = '';
					if($id_next &&  $l['datas']['dangvien']!= $next['datas']['dangvien']){
						$class_dangvien='fg-red';
					} else $class_dangvien = '';
				?>
				<div class="row cells9">
					<div class="cell colspan2">Địa chỉ</div>
					<div class="cell colspan3 <?php echo $class_diachi;?>"><b><?php echo $l['datas']['diachi']; ?></b></div>
					<div class="cell colspan2 <?php echo $class_dangvien;?>">Đảng viên</div>
					<div class="cell colspan2">
						<?php
						if($l['datas']['dangvien'] == 1){
							echo '<span class="mif-checkmark fg-blue"></span>';
						} else {
							echo '';
						}
						?>
					</div>
				</div>
				<?php
					if($id_next && isset($next['datas']['loaicongchuc']) && $l['datas']['loaicongchuc'] != $next['datas']['loaicongchuc']){
						$class_loaicongchuc='fg-red';
					} else $class_loaicongchuc = '';
					if($id_next && isset($next['datas']['tinhuyvien']) && $l['datas']['tinhuyvien']!= $next['datas']['tinhuyvien']){
						$class_tinhuyvien='fg-red';
					} else $class_tinhuyvien = '';
				?>
				<div class="row cells9">
					<div class="cell colspan2">Loại công chức</div>
					<div class="cell colspan3 <?php echo $class_loaicongchuc;?>"><b>
						<?php
						if(isset($l['datas']['loaicongchuc']) && $l['datas']['loaicongchuc']=='CC') echo 'Công chức';
						else if(isset($l['datas']['loaicongchuc']) && $l['datas']['loaicongchuc']=='VC') echo 'Viên chức';
						else echo '';
						?></b>
					</div>
					<div class="cell colspan2 <?php echo $class_tinhuyvien;?>">Tỉnh ủy viên</div>
					<div class="cell colspan2">
						<?php
						if(isset($l['datas']['tinhuyvien']) && $l['datas']['tinhuyvien'] == 1){
							echo '<span class="mif-checkmark fg-blue"></span>';
						} else {
							echo '';
						}
						?>
					</div>
				</div>
				<?php
					if($id_next &&  $l['datas']['dienthoai']!= $next['datas']['dienthoai']){
						$class_dienthoai='fg-red';
					} else $class_dienthoai = '';
					if($id_next &&  $l['datas']['email']!= $next['datas']['email']){
						$class_email='fg-red';
					} else $class_email = '';
				?>
				<div class="row cells9">
					<div class="cell colspan2">Điện thoại</div>
					<div class="cell colspan3 <?php echo $class_dienthoai;?>"><b><?php echo $l['datas']['dienthoai']; ?></b></div>
					<div class="cell colspan2">Email</div>
					<div class="cell colspan2 <?php echo $class_email;?>"><b><?php echo $l['datas']['email']; ?></b></div>
				</div>
				<?php
					if(isset($l['datas']['id_nghenghiep']) && $l['datas']['id_nghenghiep']){
						$nghenghiep->id = $l['datas']['id_nghenghiep']; $nn = $nghenghiep->get_one();
						$tennghenghiep = $nn['ten'];
					} else { $tennghenghiep = ''; }
					if(isset($l['datas']['id_dantoc']) && $l['datas']['id_dantoc']){
						$dantoc->id = $l['datas']['id_dantoc']; $dt = $dantoc->get_one();
						$tendantoc = $dt['ten'];
					} else { $tendantoc = ''; }

					if($id_next && isset($next['datas']['id_nghenghiep']) && $l['datas']['id_nghenghiep']!= $next['datas']['id_nghenghiep']){
						$class_nghenghiep='fg-red';
					} else $class_nghenghiep = '';
					if($id_next && isset($next['datas']['id_dantoc']) && $l['datas']['id_dantoc']!= $next['datas']['id_dantoc']){
						$class_dantoc='fg-red';
					} else $class_dantoc = '';
					if($id_next &&  $l['datas']['ghichu']!= $next['datas']['ghichu']){
						$class_ghichu='fg-red';
					} else $class_ghichu = '';
				?>
				<div class="row cells9">
					<div class="cell colspan2">Nghề nghiệp</div>
					<div class="cell colspan3 <?php echo $class_nghenghiep; ?>"><b><?php echo $tennghenghiep; ?></b></div>
					<div class="cell colspan2">Dân tộc</div>
					<div class="cell colspan2 <?php echo $class_dantoc; ?>"><b><?php echo $tendantoc; ?></b></div>
				</div>
				<div class="row cells9">
					<div class="cell colspan2">Ghi chú</div>
					<div class="cell colspan3 <?php echo $class_ghichu; ?>"><?php echo $l['datas']['ghichu']; ?></div>
				</div>
			</div>
		</div>
	</div>
	<div class="row cells12">
		<div class="cell colspan12">
			<table class="table border" id="chucvu_list">
			<thead>
				<tr>
					<th><a href="#" id="themchucvu"><span class="mif-plus"></span></a>&nbsp;&nbsp;Đơn vị công tác</th>
					<th>Chức vụ</th>
					<th>Ngày thêm</th>
				</tr>
			</thead>
			<tbody>
				<?php
				if(isset($l['datas']['donvi']) && $l['datas']['donvi']){
					$donvi = new DonVi();$chucvu = new ChucVu();
					$arr_donvi = sort_array($l['datas']['donvi'], "ngaynhap", SORT_DESC);
					foreach ($arr_donvi as $k => $v) {
						$donvi->id = $v['id_donvi'];$dv=$donvi->tendonvi($v['id_donvi']);
						if(isset($v['id_chucvu']) && $v['id_chucvu']){
							$chucvu->id = $v['id_chucvu']; $cv = $chucvu->get_one();
							$tenchucvu = $cv['ten'];
						} else { $tenchucvu = '';}
						if(isset($v['id_ham']) && $v['id_ham']) {
							$ham->id = $v['id_ham']; $h = $ham->get_one(); $tenham = $h['ten'];
						} else { $tenham = ''; }
						if($v['ngaynhap']) $ngaynhap = date("d/m/Y", $v['ngaynhap']->sec);
						else $ngaynhap = '';
						echo '<tr class="items">';
						echo '<td>'.$dv.'</td>';
						echo '<td>'.($tenham ? $tenham . ',' : '').' '.$tenchucvu.'</td>';
						echo '<td>'.date("d/m/Y",$v['ngaynhap']->sec).'</td>';
						echo '</tr>';
					}
				}
				?>
			</tbody>
			</table>
		</div>
	</div>
</div>
<?php endif; ?>