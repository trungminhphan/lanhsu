<?php
require_once('header.php');
$donvi = new DonVi();$phanloaidonvi=new PhanLoaiDonVi();$linhvuc=new LinhVuc();
$quocgia = new QuocGia();
$id = isset($_GET['id']) ? $_GET['id'] : '';
$donvi->id = $id; $dv = $donvi->get_one();

if(isset($dv['id_phanloaidonvi']) && $dv['id_phanloaidonvi']){
	$phanloaidonvi->id = $dv['id_phanloaidonvi'];$p = $phanloaidonvi->get_one();
	$tenphanloaidonvi = $p['ten'];
} else { $tenphanloaidonvi = ''; }
if(isset($dv['id_linhvuc']) && $dv['id_linhvuc']){
	$linhvuc->id = $dv['id_linhvuc'];$l = $linhvuc->get_one();
	$tenlinhvuc = $l['ten'];
} else { $tenlinhvuc = '';}

if(isset($dv['id_quocgia']) && $dv['id_quocgia']){
	$quocgia->id = $dv['id_quocgia']; $qg = $quocgia->get_one();
	$tenquocgia = $qg['ten'];
} else { $tenquocgia = '';}

?>
<h1><a href="donvi.php" class="nav-button transform"><span></span></a>&nbsp;Chi tiết Đơn vị</h1>
<div class="align-right">
	<a href="themdonvi.php?id=<?php echo $dv['_id']; ?>" class="button primary"><span class="mif-pencil"></span> Sửa</a>
	<a href="donvi.php?id_root=<?php echo $dv['_id']; ?>" class="button success"><span class="mif-pencil"></span> Sửa đơn vị trực thuộc</a>
</div>
<div class="grid example">
	<div class="row cells12">
		<div class="cell colspan3">Tên Đơn vị/ Tổ chức</div>
		<div class="cell colspan9"><?php echo $dv['ten']; ?></div>
	</div>
	<div class="row cells12">
		<div class="cell colspan3">Địa chỉ</div>
		<div class="cell colspan3"><?php echo $dv['diachi']; ?></div>
		<div class="cell colspan3">Quốc gia</div>
		<div class="cell colspan3"><?php echo $tenquocgia; ?></div>
	</div>
	<div class="row cells12">
		<div class="cell colspan3">Điện thoại</div>
		<div class="cell colspan3"><?php echo $dv['dienthoai']; ?></div>
		<div class="cell colspan3">Email</div>
		<div class="cell colspan3"><?php echo $dv['email']; ?></div>
	</div>
	<div class="row cells12">
		<div class="cell colspan3">Phân loại tổ chức</div>
		<div class="cell colspan3"><?php echo $tenphanloaidonvi; ?></div>
		<div class="cell colspan3">Lĩnh vực</div>
		<div class="cell colspan3"><?php echo $tenlinhvuc; ?></div>
	</div>
	<div class="row cells12">
		<div class="cell colspan3">Ghi chú</div>
		<div class="cell colspan9"><?php echo $dv['ghichu']; ?></div>
	</div>
</div>
<?php if(isset($dv['level2']) && $dv['level2']): ?>
<h3><span class="mif-share"></span> Các đơn vị trực thuộc</h3>
<div class="grid example">
	<div class="row cells12">
		<div class="cell colspan12">
			<div class="treeview" data-role="treeview">
				<ul>
				<?php
				foreach ($dv['level2'] as $k2 => $a2) {
					echo '<li><span class="leaf">'.$a2['ten'].'</span><span class="node-toggle"></span>';
					if(isset($a2['level3']) && $a2['level3']){
						echo '<ul>';
						foreach ($a2['level3'] as $k3 => $a3) {
							echo '<li><span class="leaf">'.$a3['ten'].'</span><span class="node-toggle"></span>';
							if(isset($a3['level4']) && $a3['level4']){
								echo '<ul>';
								foreach ($a3['level4'] as $k4=>$a4) {
									echo '<li><span class="leaf"><span class="mif-chevron-thin-right"></span>&nbsp;'.$a4['ten'].'</span></li>';
								}
								echo '</ul>';
							}
							echo '</li>';
						}
						echo '</ul>';
					}
					echo '</li>';
				}
					
				?>
				</ul>
			</div>
		</div>
	</div>
</div>
<?php endif; ?>
<?php require_once('footer.php'); ?>