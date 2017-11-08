<?php
require_once('header.php');
$doanvao = new DoanVao();$donvi = new DonVi();$canbo = new CanBo();$chucvu=new ChucVu();
$dmdoanvao = new DMDoanVao();$ham = new Ham();$mucdich=new MucDich();
$id = isset($_GET['id']) ? $_GET['id'] : '';
$doanvao->id = $id; $dv = $doanvao->get_one();
?>
<h1><a href="doanra.php" class="nav-button transform"><span></span></a>&nbsp;Chi tiết Đoàn Vào</h1>
<!-----------------------------------------------------bsbskwerw jwej rwej lrjewl ------------------------- -->
<div class="grid example">
	<div class="row cells12">
		<div class="cell colspan12"><h3><span class="mif-users"></span> Danh sách thành viên</h3></div>
	</div>
	<div class="row cells12">
		<div class="cell colspan2 align-left"><b>Họ tên</b></div>
		<div class="cell colspan4"><b>Đơn vị</b></div>
		<div class="cell colspan2"><b>Chức vụ</b></div>
		<div class="cell colspan2"><b>Đơn vị xin phép</b></div>
		<div class="cell colspan2"><b>Đơn vị cấp phép</b></div>
	</div>
	<?php
	$i = 1;
	if(isset($dv['danhsachdoan']) && $dv['danhsachdoan']){
		foreach ($dv['danhsachdoan'] as $k => $ds) {
			$canbo->id = $ds['id_canbo'];$cb = $canbo->get_one();
			$dvi = $donvi->tendonvi($ds['id_donvi']);
			//$donvi->id = $ds['id_donvi']; $dvi = $donvi->get_one();
			//$dvi = $donvi->tendonvi($ds['id_donvi']);
			if(isset($dv['id_dmdoanvao']) && $dv['id_dmdoanvao']){
				//$dmdoanvao->id = $dv['id_dmdoanvao']; $d = $dmdoanvao->get_one();
				$tendoanvao = $dmdoanvao->tendoan($dv['id_dmdoanvao']);
			} else { $tendoanvao = ''; }
			if(isset($ds['id_chucvu']) && $ds['id_chucvu']){
				$chucvu->id = $ds['id_chucvu']; $cv=$chucvu->get_one();
				$tenchucvu = $cv['ten'];
			} else { $tenchucvu = ''; }
			if(isset($ds['id_ham']) && $ds['id_ham']){
				$ham->id = $ds['id_ham']; $h = $ham->get_one(); $tenham = $h['ten'];
			} else { $tenham = ''; }
			echo '<div class="row cells12">';
				echo '<div class="cell colspan2">'.$i. '. ' .$cb['hoten'].'</div>';
				echo '<div class="cell colspan4">'.$dvi.'</div>';
				echo '<div class="cell colspan2">'.($tenham ? $tenham . ',' : '') .' '. $tenchucvu.'</div>';
				if($k > 0){
					echo '<div class="cell colspan2 align-center">Như trên</div>';
					echo '<div class="cell colspan2 align-center">Như trên</div>';
				} else {
					echo '<div class="cell colspan2">';
					if(isset($dv['congvanxinphep']['attachments'][0]['filename'])){
						echo '<span class="tag info padding5 margin5"><a href="'.$target_files.$dv['congvanxinphep']['attachments'][0]['alias_name'].'" class="fg-white">'.$dv['congvanxinphep']['attachments'][0]['filename'].'</a></span>';
					}
					if(isset($dv['congvanxinphep']['attachments'][1]['filename'])){
						echo '<span class="tag info padding5 margin5"><a href="'.$target_files.$dv['congvanxinphep']['attachments'][1]['alias_name'].'" class="fg-white">'.$dv['congvanxinphep']['attachments'][1]['filename'].'</a></span>';
					}
					echo '</div>';
					echo '<div class="cell colspan2">';
					if(isset($dv['quyetdinhchophep']['attachments'][0]['filename'])){
						echo '<span class="tag info padding5 margin5"><a href="'.$target_files.$dv['quyetdinhchophep']['attachments'][0]['alias_name'].'" class="fg-white">'.$dv['quyetdinhchophep']['attachments'][0]['filename'].'</a></span>';
					}
					echo '</div>';
				}
			echo '</div>';$i++;
		}
	}

	if(isset($dv['danhsachdoan_2']) && $dv['danhsachdoan_2']){
		foreach ($dv['danhsachdoan_2'] as $k2 => $dv_2) {
			$canbo->id = $dv_2['id_canbo'];$cb = $canbo->get_one();
			$dvi = $donvi->tendonvi($ds_2['id_donvi']);
			//$donvi->id = $ds['id_donvi'][0]; $dv = $donvi->get_one();
			//$dvi = $donvi->tendonvi($ds_2['id_donvi']);
			$chucvu->id = $dv_2['id_chucvu']; $cv=$chucvu->get_one();
			if(isset($dv_2['id_ham']) && $dv_2['id_ham']) {
				$ham->id = $dv_2['id_ham']; $h=$ham->get_one();$tenham=$h['ten'];
			} else { $tenham = '';}
			echo '<div class="row cells12">';
				echo '<div class="cell colspan2">'.$i. '. ' .$cb['hoten'].'</div>';
				echo '<div class="cell colspan4">'.$dvi.'</div>';
				echo '<div class="cell colspan2">'.$tenham . ' '.$cv['ten'].'</div>';
				if($k2>0){
					echo '<div class="cell colspan2 align-center">Như trên</div>';
					echo '<div class="cell colspan2 align-center">Như trên</div>';
				} else {
					echo '<div class="cell colspan2">';
					if(isset($dv['congvanxinphep']['attachments'][0]['filename'])){
						echo '<span class="tag info padding5 margin5"><a href="'.$target_files.$dv['congvanxinphep']['attachments'][0]['alias_name'].'" class="fg-white">'.$dv['congvanxinphep']['attachments'][0]['filename'].'</a></span>';
					} else { echo ''; }
					echo '</div>';
					echo '<div class="cell colspan2">';
					if(isset($dv['quyetdinhchophep_2']['attachments'][0]['filename'])){
						echo '<span class="tag info padding5 margin5"><a href="'.$target_files.$dv['quyetdinhchophep_2']['attachments'][0]['alias_name'].'" class="fg-white">'.$dv['quyetdinhchophep_2']['attachments'][0]['filename'].'</a></span>';
					} else { echo ''; }
					echo '</div>';
				}

			echo '</div>';$i++;
		}
	}
	?>
	<div class="row cells12">
		<div class="cell colspan4">Tên đoàn: <?php echo $tendoanvao; ?></div>
		<div class="cell colspan4">Ngày đến: <?php echo $dv['ngayden'] ? date("d/m/Y",$dv['ngayden']->sec) : ''; ?></div>
		<div class="cell colspan4">Ngày đi: <?php echo $dv['ngaydi'] ? date("d/m/Y",$dv['ngaydi']->sec) : ''; ?></div>
	</div>
	<div class="row cells12">
		<div class="cell colspan2">Mục đích:</div>
		<div class="cell colspan4">
		<?php
			if(isset($dv['id_mucdich']) && $dv['id_mucdich']){
				$mucdich->id = $dv['id_mucdich'];$md=$mucdich->get_one();
				echo $md['ten'];
			}
		?>
		</div>
		<div class="cell colspan2">Lĩnh vực:</div>
		<div class="cell colspan4">
		<?php
			if(isset($dv['id_linhvuc']) && $dv['id_linhvuc']){
				$linhvuc = new LinhVuc();
				$linhvuc->id = $dv['id_linhvuc'];$lv=$linhvuc->get_one();
				echo $lv['ten'];
			}
		?>
		</div>
	</div>
	<div class="row cells12">
		<div class="cell colspan2">Nội dung:</div>
		<div class="cell colspan10"><?php echo nl2br($dv['noidung']); ?></div>
	</div>
	<div class="row cells12">
		<div class="cell colspan2">Ghi chú:</div>
		<div class="cell colspan10"><?php echo nl2br($dv['ghichu']); ?></div>
	</div>
	<div class="row cells12">
		<div class="cell colspan2">Người nhập</div>
		<div class="cell colspan4">
			<?php
				$users->id = $dv['id_user'];
				$u = $users->get_one(); echo $u['username'];
			?>
			<?php if($users->is_admin()): ?>
				<a href="logs_nhaplieu.php?id=<?php echo $id; ?>&collection=doanvao"> [Xem chi tiết] </a>
			<?php endif; ?>
		</div>
	</div>
</div>
<div class="align-center">
	<?php if($users->is_admin() || $users->get_userid() == $dv['id_user']): ?>
		<a href="themdoanvao.php?id=<?php echo $id; ?>" class="button primary"><span class="mif-pencil"></span> Sửa</a>
		<a href="themthanhviendoanvao.php?id=<?php echo $id; ?>" class="button primary"><span class="mif-user-plus"></span> Thêm thành viên</a>
	<?php endif; ?>
	<a href="doanvao.php" class="button"><span class="mif-keyboard-return"></span> Trở về</a>
</div>
<?php require_once('footer.php'); ?>
