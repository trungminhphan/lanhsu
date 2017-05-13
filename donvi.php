<?php require_once('header.php');
if(!$users->is_admin()){
	echo '<h2>Bạn không có quyền...! <a href="index.php">Trở về</a></h2>';
	require_once('footer.php');
	exit();
}
$donvi = new DonVi();
$update = isset($_GET['update']) ? $_GET['update'] : '';
$id = isset($_GET['id']) ? $_GET['id'] : '';
$id_parent = isset($_GET['id_parent']) ? $_GET['id_parent'] : '';
$id_root = isset($_GET['id_root']) ? $_GET['id_root'] : '';
$id2 = isset($_GET['id2']) ? $_GET['id2'] : '';
$id3 = isset($_GET['id3']) ? $_GET['id3'] : '';
$id_donvi = isset($_GET['id_donvi']) ? $_GET['id_donvi'] : '';
if(isset($_GET['submit']) && $id_donvi){
	//$query = array('ten' => new MongoRegex('/'.$key_search.'/i'));
	$query = array('_id' => new MongoId($id_donvi));
	$donvi_list = $donvi->get_list_condition($query);
} else {
	if($id_root){
		$donvi_list = $donvi->get_list_condition(array('_id' => new MongoId($id_root)));
	} else {
		$donvi_list = $donvi->get_all_list();	
	}
}
if($update=='ok'){	$msg = 'Cập nhật thành công'; }
if($update=='no'){	$msg = 'Không thể xoá [Cán bộ công chức],[Đoàn ra]'; }
?>
<script type="text/javascript" src="js/select2.min.js"></script>
<script type="text/javascript" src="js/html5.messages.js"></script>
<script type="text/javascript" src="js/jquery.setcase.js"></script>
<script type="text/javascript" src="js/jquery.dataTables.min.js"></script>
<script type="text/javascript" src="js/donvi.js"></script>
<script type="text/javascript">
	$(document).ready(function(){
		themdonvi();suadonvi();timdonvi();
		<?php if(isset($msg) && $msg): ?>
			$.Notify({type: 'alert', caption: 'Thông báo', content: <?php echo "'".$msg."'"; ?>});
		<?php endif; ?>
	});
</script>
<h1><a href="index.php" class="nav-button transform"><span></span></a>&nbsp;Quản lý Đơn vị</h1>
<form action="<?php echo $_SERVER['REQUEST_URI']; ?>" method="GET" id="search_donvi" data-role="validator" data-show-required-state="false">
	<a href="donvi.php" class="button"><span class="mif-sync-problem"></span> Tải lại trang</a>
	<a href="themdonvi.php" class="button primary"><span class="mif-plus"></span> Thêm Đơn vị</a>
	<div class="input-control select" style="min-width:300px;">
		<select name="id_donvi" id="timdonvi"></select>
	</div>
	<button name="submit" id="submit" value="OK" class="button"><span class="mif-search"></span> Tìm</button>
</form>
<?php if($donvi_list && $donvi_list->count() > 0): ?>
<h3><a href="donvi.php" class="mif-earth"><span></span></a>&nbsp;<b>Danh sách các Đơn vị</b></h3>
<div class="treeview" data-role="treeview">
	<ul>
		<?php
		foreach ($donvi_list as $k1 => $a1) {
			echo '<li class="node '.($a1['_id']==$id_root ? ' active' : ' collapsed').'"><span class="leaf"><b><a href="donvi.php?id_root='.$a1['_id'].'">'.$a1['ten'].'</a></b></span><span class="node-toggle"></span>&nbsp;<a href="get.suadonvi.php?id_root='.$a1['_id'].'&id='.$a1['_id'].'&tendonvi='.$a1['ten'].'&level=1" onclick="return false;" class="suadonvi"><span class="mif-pencil"></span></a>&nbsp;<a href="get.themdonvi.php?id_root='.$a1['_id'].'&id='.$a1['_id'].'&level=1" onclick="return false;" class="themdonvi"><span class="mif-plus"></span></a><a href="chitietdonvi.php?id='.$a1['_id'].'">&nbsp;<span class="mif-compass"></span> </a>';
			if(isset($a1['level2']) && $a1['level2']){
				$arr_level2 = sort_array($a1['level2'], "ten", SORT_ASC);
				echo '<ul>';
				foreach ($arr_level2 as $k2 => $a2) {
					echo '<li class="node '.($a2['_id']==$id2 ? 'active' : 'collapsed').'"><span class="leaf">'.$a2['ten'].'</span><span class="node-toggle"></span>&nbsp;<a href="get.suadonvi.php?id_root='.$a1['_id'].'&id_parent='.$a1['_id'].'&id='.$a2['_id'].'&tendonvi='.$a2['ten'].'&id2='.$a2['_id'].'&level=2&k2='.$k2.'" class="suadonvi" onclick="return false;"><span class="mif-pencil"></span></a>&nbsp;<a href="get.themdonvi.php?id_root='.$a1['_id'].'&id_parent='.$a1['_id'].'&id='.$a2['_id'].'&id2='.$a2['_id'].'&level=2&k2='.$k2.'" class="themdonvi" onclick="return false;"><span class="mif-plus"></span></a>';
					if(isset($a2['level3']) && $a2['level3']){
						echo '<ul>';
						$arr_level3 = sort_array($a2['level3'], "ten", SORT_ASC);
						foreach ($arr_level3 as $k3 => $a3) {
							echo '<li class="node '.($a3['_id']==$id3 ? 'active' : 'collapsed').'"><span class="leaf">'.$a3['ten'].'</span><span class="node-toggle"></span>&nbsp;<a href="get.suadonvi.php?id_root='.$a1['_id'].'&id_parent='.$a2['_id'].'&id='.$a3['_id'].'&tendonvi='.$a3['ten'].'&level=3&id2='.$a2['_id'].'&id3='.$a3['_id'].'&k2='.$k2.'&k3='.$k3.'" class="suadonvi" onclick="return false;"><span class="mif-pencil"></span></a>&nbsp;<a href="get.themdonvi.php?id_root='.$a1['_id'].'&id_parent='.$a2['_id'].'&id='.$a3['_id'].'&tendonvi='.$a3['ten'].'&level=3&id2='.$a2['_id'].'&id3='.$a3['_id'].'&k2='.$k2.'&k3='.$k3.'" class="themdonvi" onclick="return false;""><span class="mif-plus"></span></a>';
							if(isset($a3['level4']) && $a3['level4']){
								echo '<ul>';
								foreach ($a3['level4'] as $k4=>$a4) {
									echo '<li><span class="leaf">'.$a4['ten'].'</span><span class="node-toggle"></span>&nbsp;<a href="get.suadonvi.php?id_root='.$a1['_id'].'&id_parent='.$a3['_id'].'&id='.$a4['_id'].'&tendonvi='.$a4['ten'].'&level=4&id2='.$a2['_id'].'&id3='.$a3['_id'].'&id4='.$a4['_id'].'&k2='.$k2.'&k3='.$k3.'&k4='.$k4.'" class="suadonvi" onclick="return false;"><span class="mif-pencil"></span></a></li>';
								}
								echo '</ul>';
							}
							echo '</li>';
						}
						echo '</ul>';
					}
					echo '</li>';
				}
				echo '</ul>';
			}
			echo '<li>';
		}
		?>
	</ul>
</div>
<?php endif; ?>
<div data-role="dialog" id="dialog_suadonvi" data-close-button="true" class="padding20" data-overlay="true" >
	<div id="content_dialog" style="width:800px;padding: 20px;"></div>
</div>
<div data-role="dialog" id="dialog_themdonvi" data-close-button="true" class="padding20" data-overlay="true" >
	<div id="content_dialog_themdonvi" style="width:800px;padding:20px;"></div>
</div>
<?php require_once('footer.php'); ?>

