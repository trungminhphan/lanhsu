<?php require_once('header.php');
if(!$users->is_admin() && !$users->is_manager()){
	echo '<h2>Bạn không có quyền...! <a href="index.php">Trở về</a></h2>';
	require_once('footer.php');
	exit();
}
$users_list = $users->get_list();
$doanra=new DoanRa(); $doanvao=new DoanVao();$abtc=new ABTC();
$canbo=new CanBo();$quocgia=new QuocGia();$donvi=new DonVi();
$chucvu=new ChucVu(); $mucdich=new MucDich();$kinhphi=new KinhPhi();
$dmdoanvao=new DMDoanVao();$ham=new Ham();
$ham=new Ham();$nghenghiep = new NgheNghiep();$dantoc = new DanToc();$phanloaidonvi = new PhanLoaiDonVi();$linhvuc=new LinhVuc();
?>
<script type="text/javascript">
	$(document).ready(function(){
		$(".open_window").click(function(){
		  window.open($(this).attr("href"), '_blank', 'toolbar=yes, scrollbars=yes, resizable=yes, top=0, left=100, width=1024, height=800');
		  return false;
		})
	});
</script>
<h1><a href="index.php" class="nav-button transform"><span></span></a>&nbsp;Thống kê Nhập liệu</h1>
<div class="align-right">
	<a href="in_thongkenhaplieu.php" class="open_window button primary"><span class="mif-print"></span> In</a>
</div>
<?php if($users_list && $users_list->count() > 0) : ?>
<table class="table bodered striped">
<thead>
	<tr>
		<th>STT</th>
		<th>Username</th>
		<th>Fullname</th>
		<th>Thống kê Record nhập liệu</th>
	</tr>
</thead>
<tbody>
	<?php
	$i= 1; $total=0;
	

	foreach ($users_list as $u) {
		$doanra->id_user = $u['_id'];
		$count_doanra = $doanra->count_to_user();
		$doanvao->id_user = $u['_id'];
		$count_doanvao = $doanvao->count_to_user();
		$abtc->id_user = $u['_id'];
		$count_abtc = $abtc->count_to_user();
		$canbo->id_user = $u['_id'];
		$count_canbo = $canbo->count_to_user();

		$quocgia->id_user = $u['_id'];
		$count_quocgia = $quocgia->count_to_user();
		$donvi->id_user = $u['_id'];
		$count_donvi = $donvi->count_to_user();
		$chucvu->id_user = $u['_id'];
		$count_chucvu = $chucvu->count_to_user();
		$mucdich->id_user = $u['_id'];
		$count_mucdich = $mucdich->count_to_user();
		$kinhphi->id_user = $u['_id'];
		$count_kinhphi = $kinhphi->count_to_user();
		$dmdoanvao->id_user = $u['_id'];
		$count_dmdoanvao = $dmdoanvao->count_to_user();
		$ham->id_user = $u['_id'];
		$count_ham = $ham->count_to_user();
		$nghenghiep->id_user = $u['_id'];
		$count_nghenghiep = $nghenghiep->count_to_user();
		$dantoc->id_user = $u['_id'];
		$count_dantoc = $dantoc->count_to_user();
		$phanloaidonvi->id_user = $u['_id'];
		$count_phanloaidonvi = $phanloaidonvi->count_to_user();
		$linhvuc->id_user = $u['_id'];
		$count_linhuc = $linhvuc->count_to_user();
		//if($u['username']=='nvphuc'){
		$count = $count_doanra + $count_doanvao + $count_abtc + $count_canbo + $count_quocgia + $count_donvi + $count_chucvu + $count_mucdich + $count_kinhphi + $count_dmdoanvao + $count_ham + $count_nghenghiep + $count_dantoc + $count_phanloaidonvi + $count_linhuc;
		//} else {
		
		//$count = $count_doanra + $count_doanvao + $count_abtc + $count_canbo;
		//}
		$total += $count;
		echo '<tr>';
		echo '<td>'.$i.'</td>';
		echo '<td>'.$u['username'].'</td>';
		echo '<td>'.$u['person'].'</td>';
		echo '<td class="align-right"><b>'.format_number($count).'</b></td>';
		echo '</tr>';
		$i++;
	}
	?>
<tr>
	<td colspan="3">TỔNG CỘNG</td>
	<td class="align-right"><b><?php echo format_number($total); ?></b></td>
</tr>
</tbody>
</table>
<?php endif; ?>
<?php require_once('footer.php'); ?>