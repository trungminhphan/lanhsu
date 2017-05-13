<?php
require_once('header.php');
$users_regis = new Users_Regis();$donvi = new DonVi();$chucvu = new ChucVu();
$list = $users_regis->get_all_list();$canbo = new CanBo();
?>
<h1><a href="index.php" class="nav-button transform"><span></span></a>&nbsp;Quản lý tài khoản - Đăng ký trực tuyến</h1>
<script type="text/javascript" src="js/jquery.dataTables.min.js"></script>
<a href="users_regis_add.php" class="button primary"><span class="mif-plus"></span> Thêm tài khoản</a>
<?php if(isset($list)): ?>
<table class="table striped hovered dataTable" data-role="datatable">
<thead>
	<tr>
		<th>STT</th>
		<th>Email</th>
		<th>Họ tên</th>
		<th>Đơn vị</th>
		<th>Chức vụ</th>
		<th>Tình trạng</th>
		<th><span class="mif-bin"></span></th>
		<th><span class="mif-pencil"></span></th>
	</tr>
</thead>
<tbody>
<?php
	$i = 1;
	foreach ($list as $l) {
		if(isset($l['canbo']) && $l['canbo'] && $l['canbo']['id_canbo']){
			$canbo->id  = $l['canbo']['id_canbo']; $cb = $canbo->get_one();
			$tencanbo = $cb['hoten'];
		} else { $tencanbo =  $l['hoten'];}
		if(isset($l['canbo']) && $l['canbo'] && $l['canbo']['id_donvi']){
			$tendonvi = $donvi->tendonvi($l['canbo']['id_donvi']);
		} else { $tendonvi = $l['donvi']; }
		if(isset($l['canbo']) && $l['canbo'] && $l['canbo']['id_chucvu']){
			$chucvu->id = $l['canbo']['id_chucvu'];$cv = $chucvu->get_one();
			$tenchucvu = $cv['ten'];
		} else { $tenchucvu = $l['chucvu']; }
		$tinhtrang = $l['status'] == 1 ? '<span class="mif-checkmark fg-blue"></span>' : '<span class="mif-not fg-red"></span>';
		echo '<tr>
			<td>'.$i.'</td>
			<td>'.$l['email'].'</td>
			<td>'.$tencanbo.'</td>
			<td>'.$tendonvi.'</td>
			<td>'.$tenchucvu.'</td>
			<td><a href="users_active.php?id='.$l['_id'].'&act=edit">'.$tinhtrang.'</a></td>
			<td><a href="users_regis_add.php?id='.$l['_id'].'&act=del" Onclick="return confirm(\'Chắc chắn xóa?\');"><span class="mif-bin"></span></a></td>
			<td><a href="users_regis_add.php?id='.$l['_id'].'&act=edit"><span class="mif-pencil"></span></a></td>
		</tr>';$i++;
	}
?>
</tbody>
</table>
<?php endif; ?>
<?php require_once('footer.php'); ?>

