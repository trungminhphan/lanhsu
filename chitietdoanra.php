<?php
require_once('header.php');
$doanra = new DoanRa();$donvi = new DonVi();$canbo = new CanBo();$donvi=new DonVi();$chucvu=new ChucVu();$ham=new Ham();
$id = isset($_GET['id']) ? $_GET['id'] : '';
$doanra->id = $id; $dr = $doanra->get_one();
?>
<h1><a href="doanra.php" class="nav-button transform"><span></span></a>&nbsp;Chi tiết Đoàn ra</h1>
<?php if(isset($dr['masohoso']) && $dr['masohoso']) : ?>
	<div class="align-center padding10">Mã số hồ sơ: <b><?php echo $dr['masohoso']; ?></b></div>
<?php endif; ?>

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
	foreach ($dr['danhsachdoan'] as $k => $ds) {
		$canbo->id = $ds['id_canbo'];$cb = $canbo->get_one();
		$dv = $donvi->tendonvi($ds['id_donvi']);
		$chucvu->id = $ds['id_chucvu']; $cv=$chucvu->get_one();
		if(isset($ds['id_ham']) && $ds['id_ham']) {
			$ham->id = $ds['id_ham']; $h=$ham->get_one();$tenham=$h['ten'];
		} else { $tenham = '';}
		echo '<div class="row cells12">';
			echo '<div class="cell colspan2">'.$i. '. ' .$cb['hoten'].'</div>';
			echo '<div class="cell colspan2">'.$tenham . ' '.$cv['ten'].'</div>';
			if($k > 0 ){
				echo '<div class="cell colspan4 align-center">Như trên</div>';
				echo '<div class="cell colspan4 align-center">Như trên</div>';
			} else {
				echo '<div class="cell colspan4">';
				if(isset($dr['congvanxinphep']['attachments'][0]['alias_name'])){
					echo '<span class="tag info padding5 margin5"><span class="mif-attachment"></span> <a href="'.$target_files.$dr['congvanxinphep']['attachments'][0]['alias_name'].'" class="fg-white">'.$dr['congvanxinphep']['attachments'][0]['filename'].'</a></span>';
				}
				if(isset($dr['congvanxinphep']['attachments'][1]['filename'])){
					echo '<span class="tag info padding5 margin5"><span class="mif-attachment"></span> <a href="'.$target_files.$dr['congvanxinphep']['attachments'][1]['alias_name'].'" class="fg-white">'.$dr['congvanxinphep']['attachments'][1]['filename'].'</a></span>';
				}
				echo '</div>';
				echo '<div class="cell colspan4">';
				if(isset($dr['quyetdinhchophep']['attachments'][0]['alias_name'])){
					echo '<span class="tag info padding5 margin5"><span class="mif-attachment"></span> <a href="'.$target_files.$dr['quyetdinhchophep']['attachments'][0]['alias_name'].'" class="fg-white">'.$dr['quyetdinhchophep']['attachments'][0]['filename'].'</a></span>';
				}
				echo '</div>';
			}
		echo '</div>';$i++;
	}
	if(isset($dr['danhsachdoan_2']) && $dr['danhsachdoan_2']){
		foreach ($dr['danhsachdoan_2'] as $k2 => $ds_2) {
			$canbo->id = $ds_2['id_canbo'];$cb = $canbo->get_one();
			//$donvi->id = $ds['id_donvi'][0]; $dv = $donvi->get_one();
			$dv = $donvi->tendonvi($ds_2['id_donvi']);
			$chucvu->id = $ds_2['id_chucvu']; $cv=$chucvu->get_one();
			if(isset($ds_2['id_ham']) && $ds_2['id_ham']) {
				$ham->id = $ds_2['id_ham']; $h=$ham->get_one();$tenham=$h['ten'];
			} else { $tenham = '';}
			echo '<div class="row cells12">';
				echo '<div class="cell colspan2">'.$i. '. ' .$cb['hoten'].'</div>';
				echo '<div class="cell colspan2">'.$tenham . ' '.$cv['ten'].'</div>';
				if($k2 > 0){
					echo '<div class="cell colspan4 align-center">Như trên</div>';
					echo '<div class="cell colspan4 align-center">Như trên</div>';
				} else {
					echo '<div class="cell colspan4"><span class="tag info padding5 margin5">';
					if(isset($dr['congvanxinphep']['attachments'][0]['filename'])){
						echo '<span class="mif-attachment"></span> <a href="'.$target_files.$dr['congvanxinphep']['attachments'][0]['alias_name'].'" class="fg-white">'.$dr['congvanxinphep']['attachments'][0]['filename'].'</a></span>';
					}
					echo '</div>';
					echo '<div class="cell colspan4">';
					if(isset($dr['quyetdinhchophep_2']['attachments'][0]['alias_name'])){
						echo '<span class="mif-attachment"></span> <span class="tag info padding5 margin5"><a href="'.$target_files.$dr['quyetdinhchophep_2']['attachments'][0]['alias_name'].'" class="fg-white">'.$dr['quyetdinhchophep_2']['attachments'][0]['filename'].'</a></span>';
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
		<div class="cell colspan4">Ngày đi: <?php echo $dr['ngaydi'] ? date("d/m/Y",$dr['ngaydi']->sec) : ''; ?></div>
		<div class="cell colspan4">Ngày về: <?php echo $dr['ngayve'] ? date("d/m/Y",$dr['ngayve']->sec) : ''; ?></div>
     	<div class="cell colspan4">Số ngày: <?php echo $dr['songay']; ?></div>
	</div>
	<div class="row cells12">
		<?php
		$quocgia = new QuocGia();$qg = $quocgia->get_quoctich($dr['id_quocgia']);
		$mucdich = new MucDich();
		if(isset($dr['id_mucdich']) && $dr['id_mucdich']){
			$mucdich->id = $dr['id_mucdich']; $md = $mucdich->get_one();
			$tenmucdich = $md['ten'];
		} else { $tenmucdich = ''; }
		$kinhphi = new KinhPhi();
		if(isset($dr['id_kinhphi']) && $dr['id_kinhphi']){
			$kinhphi->id = $dr['id_kinhphi']; $kp = $kinhphi->get_one();
			$tenkinhphi = $kp['ten'];
		} else { $tenkinhphi = '';}
		if(isset($dr['id_donvimoi']) && $dr['id_donvimoi'] && is_array($dr['id_donvimoi'])){
			//$donvi->id = $dr['id_donvimoi'];$dvm = $donvi->get_one();
			$tendonvimoi = $donvi->tendonvi($dr['id_donvimoi']); //$dvm['ten'];
		} else { $tendonvimoi = ''; }
		?>
		<div class="cell colspan4">Nước đến: <?php echo $qg; ?></div>
		<div class="cell colspan4">Mục đích: <?php echo $tenmucdich; ?></div>
     	<div class="cell colspan4">Kinh phí: <?php echo $tenkinhphi; ?></div>
	</div>
	<div class="row cells12">
		<div class="cell colspan6">Đơn vị mời: <?php echo $tendonvimoi; ?></div>
	</div>
	<div class="row cells12">
		<div class="cell colspan12">Nội dung: <?php echo $dr['noidung']; ?></div>
	</div>
	<div class="row cells12">
		<div class="cell colspan12">Ghi chú: <?php echo $dr['ghichu']; ?></div>
	</div>
	<div class="row cells12">
		<div class="cell colspan2">Người nhập</div>
		<div class="cell colspan4">
			<?php
				$users->id = $dr['id_user'];
				$u = $users->get_one(); echo $u['username'];
			?>
			<?php if($users->is_admin()): ?>
				<a href="logs_nhaplieu.php?id=<?php echo $id; ?>&collection=doanra"> [Xem chi tiết] </a>
			<?php endif; ?>
		</div>
	</div>
</div>
<div class="align-center">
	<?php if($users->is_admin() || $users->get_userid() == $dr['id_user']) : ?>
		<a href="themdoanra.php?id=<?php echo $id; ?>" class="button primary"><span class="mif-pencil"></span> Sửa</a>
	<?php endif; ?>
	<a href="themdoanra.php" class="button primary"><span class="mif-plus"></span> Thêm đoàn ra</a>
	<a href="themthanhviendoanra.php?id=<?php echo $id; ?>" class="button primary"><span class="mif-user-plus"></span> Thêm thành viên</a>
	<a href="doanra.php" class="button"><span class="mif-keyboard-return"></span> Trở về</a>
</div>
<?php require_once('footer.php'); ?>