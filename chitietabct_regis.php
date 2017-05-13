<?php require_once('header.php');
$quocgia = new QuocGia();
$id = isset($_GET['id']) ? $_GET['id'] : '';
$abtc_regis->id = $id; $a = $abtc_regis->get_one();

?>
<h1><a href="abtc_regis.php" class="nav-button transform"><span></span></a>&nbsp;Đăng ký trực tuyến - Chi tiết ABTC (APEC)</h1>
<div class="padding10 align-center"><h3>Mã số hồ sơ: <b><?php echo $a['masohoso']; ?></b></h3></div>
<div class="grid example">
	<div class="row cells12">
		<div class="cell colspan2"></div>
		<div class="cell colspan2"><b>Số</b></div>
		<div class="cell colspan2"><b>Ngày ký</b></div>
		<div class="cell colspan6"><b>Đính kèm</b></div>
	</div>
	<div class="row cells12">
		<div class="cell colspan2">Xin phép</div>
		<div class="cell colspan2"><?php echo $a['congvanxinphep']['ten']; ?></div>
		<div class="cell colspan2"><?php echo $a['congvanxinphep']['ngayky'] ? date("d/m/Y",$a['congvanxinphep']['ngayky']->sec) : ''; ?></div>
		<div class="cell colspan6">
		<?php
			if($a['congvanxinphep']['attachments'] && count($a['congvanxinphep']['attachments'])>0){
				foreach ($a['congvanxinphep']['attachments'] as $key => $value) {
					$icon = show_icon($value['filetype']);
					echo '<span class="tag info padding5" style="margin:0px 5px 5px 0px;">'.$icon.' <a href="'.$folder_regis.$target_files_regis.$value['alias_name'].'" class="fg-white">'.$value['filename'].'</a></span>';
				}	
			} else {
				echo '<span class="tag alert padding5"><span class="mif-not"></span> Không có tập tin đính kèm...</span>';
			}
		?>
		</div>
	</div>
</div>
<div class="grid example">
	<div class="row cells12">
		<div class="cell colspan2">Nước cấp thẻ: </div>
		<div class="cell colspan10"><?php echo $quocgia->get_quoctich($a['id_quocgia']); ?></div>
		
	</div>
	<div class="row cells12">
		<div class="cell colspan2">Ghi chú:</div>
		<div class="cell colspan10"> <?php echo nl2br($a['ghichu']); ?></div>
	</div>
</div>
<?php if($a['status']): ?>
<div class="grid example">
<div class="row cells12">
		<div class="cell colspan12">
		<h3>Tình trạng xử lý</h3>
			<u>
			<?php
			foreach($a['status'] as $t){
				echo '<li>'.date("d/m/Y", $t['date_post']->sec) . ' - '. $arr_tinhtrang[$t['t']].' ['.$t['noidung'].']</li>';
			}
			?>
			</u>
		</div>
	</div>
</div>
<?php endif; ?>
<div class="align-center">
	<a href="convert_abtc.php?id=<?php echo $id; ?>" class="button primary"><span class="mif-creative-cloud"></span> Xử lý hồ sơ</a>
	<a href="abtc_regis.php" class="button"><span class="mif-keyboard-return"></span> Trở về danh sách</a>
</div>
<?php require_once('header.php');