<?php require_once('header.php');
$abtc = new ABTC();$canbo = new CanBo();$donvi= new DonVi();$chucvu=new ChucVu();$quocgia = new QuocGia();
$id = isset($_GET['id']) ? $_GET['id'] : '';
$abtc->id = $id; $a = $abtc->get_one();
?>
<h1><a href="index.php" class="nav-button transform"><span></span></a>&nbsp;Chi tiết cấp thẻ ABTC</h1>

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
			if(isset($a['congvanxinphep']['attachments'])){
				foreach ($a['congvanxinphep']['attachments'] as $key => $value) {
					echo '<span class="tag info padding5 margin5"><a href="'.$target_files.$value['alias_name'].'" class="fg-white">'.$value['filename'].'</a></span>';
				}
			}
			echo '</div>';
			echo '<div class="cell colspan6">';
			if(isset($a['quyetdinhchophep']['attachments'])){
				foreach ($a['quyetdinhchophep']['attachments'] as $key => $value) {
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
		if($a['thongtinthanhvien']){
			foreach ($a['thongtinthanhvien'] as $member) {
				$canbo->id = $member['id_canbo'];$cb=$canbo->get_one();
				//$donvi->id = $member['id_donvi']; $dv=$donvi->get_one();
				$dv = $donvi->tendonvi($member['id_donvi']);
				$chucvu->id= $member['id_chucvu']; $cv=$chucvu->get_one();
				$count = count($cb['passport']) - 1;
				if($member['tinhtrang'] == 1) $tinhtrang = 'Gia hạn';
				else $tinhtrang = 'Cấp mới';
				echo '<div class="row cells12">';
				echo '<div class="cell colspan2">'.$cb['hoten'].'</div>';
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
<div class="grid example">
	<div class="row cells12">
		<div class="cell colspan2">Nước cấp thẻ: </div>
		<div class="cell colspan6"><?php echo $quocgia->get_quoctich($a['id_quocgia']); ?></div>
		<div class="cell colspan4">Tình trạng: 
			<?php
			if($a['chophep'] == 1) echo '<span class="mif-checkmark fg-blue"></span> Cho phép';
			else echo '<span class="mif-cancel fg-red"></span> Không cho phép';
			?>
		</div>
	</div>
	<div class="row cells12">
		<div class="cell colspan12">Ghi chú: <?php echo $a['ghichu']; ?></div>
	</div>
	<div class="row cells12">
		<div class="cell colspan12">
			<b>Hồ sơ kèm theo</b>
			<?php
			if(isset($a['giaytolienquan'])){
				$arr_giaytolienquan = sort_array($a['giaytolienquan'], 'order', SORT_ASC);
				foreach ($arr_giaytolienquan as $gt) {
					echo '<span class="tag info padding5 margin5"><a href="'.$target_files.$gt['alias_name'].'" class="fg-white">'.$gt['filename'].'</a></span>';
				}
			}
			?>
		</div>
	</div>
	<div class="row cells12">
		<div class="cell colspan2">Người nhập</div>
		<div class="cell colspan4">
			<?php
				$users->id = $a['id_user'];
				$u = $users->get_one(); echo $u['username'];
			?>
			<?php if($users->is_admin()): ?>
				<a href="logs_nhaplieu.php?id=<?php echo $id; ?>&collection=abtc"> [Xem chi tiết] </a>
			<?php endif; ?>
		</div>
	</div>
</div>
<div class="align-center">
	<a href="themabtc.php?id=<?php echo $id; ?>" class="button primary"><span class="mif-pencil"></span> Sửa</a>
	<a href="abtc.php" class="button"><span class="mif-keyboard-return"></span> Trở về danh sách</a>
</div>
<?php require_once('footer.php'); ?>