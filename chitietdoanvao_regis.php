<?php
require_once('header.php');
$donvi = new DonVi();$canbo = new CanBo();$chucvu=new ChucVu();
$dmdoanvao = new DMDoanVao();
$id = isset($_GET['id']) ? $_GET['id'] : '';
$doanvao_regis->id = $id; $dv = $doanvao_regis->get_one();
?>
<script type="text/javascript">
	$(document).ready(function(){
		$(".xoadangky").click(function(){
			_link = $(this).attr("href"); _this = $(this);
			$.get(_link, function(d){
				_this.parent("li").remove();
			});
		});
	});
</script>
<h1><a href="doanvao_regis.php" class="nav-button transform"><span></span></a>&nbsp;Đăng ký - Chi tiết Đoàn Vào</h1>
<div class="padding10 align-center"><h3>Mã số hồ sơ: <b><?php echo $dv['masohoso']; ?></b></h3></div>
<div class="grid example">
	<div class="row cells12">
		<div class="cell colspan4 align-center"><b>Đơn vị</b></div>
		<div class="cell colspan4 align-center"><b>Số công văn</b></div>
		<div class="cell colspan4 align-center"><b>Ngày ký</b></div>
	</div>
	<?php
		if(isset($dv['congvanxinphep']['id_donvi'][0]) && $dv['congvanxinphep']['id_donvi']){
			$donvi->id = $dv['congvanxinphep']['id_donvi'][0];$dvt = $donvi->get_one();
			$tendonvi = $dvt['ten'];
		} else { $tendonvi = ''; }
	?>
	<div class="row cells12">
		<div class="cell colspan4">Đơn vị tiếp: <?php echo $tendonvi; ?></div>
		<div class="cell colspan4 align-center"><?php echo $dv['congvanxinphep']['ten']; ?></div>
		<div class="cell colspan4 align-center"><?php echo $dv['congvanxinphep']['ngayky'] ? date("d/m/Y",$dv['congvanxinphep']['ngayky']->sec) : ''; ?></div>
	</div>
	<div class="row cells12">
		<div class="cell colspan12" style="padding-left:20px;">
		<?php
		if($dv['congvanxinphep']['attachments'] && count($dv['congvanxinphep']['attachments']) > 0){
			foreach ($dv['congvanxinphep']['attachments'] as $key => $value) {
				$icon = show_icon($value['filetype']);
				echo '<span class="tag info padding5 margin5">'.$icon.' <a href="'.$folder_regis . $target_files_regis.$value['alias_name'].'" class="fg-white">'.$value['filename'].'</a></span>';
			}	
		} else {
			echo '<span class="tag alert padding5 margin5"><span class="mif-not"></span> Không có tập tin đính kèm...</span>';
		}
		?>
		</div>
	</div>
</div>
<div class="grid example">
	<div class="row cells12">
		<div class="cell colspan6">Ngày đến: <?php echo $dv['ngayden'] ? date("d/m/Y",$dv['ngayden']->sec) : ''; ?></div>
		<div class="cell colspan6">Ngày đi: <?php echo $dv['ngaydi'] ? date("d/m/Y",$dv['ngaydi']->sec) : ''; ?></div>
	</div>
	<div class="row cells12">
		<div class="cell colspan2">Nội dung:</div>
		<div class="cell colspan10"><?php echo nl2br($dv['noidung']); ?></div>
	</div>
	<div class="row cells12">
		<div class="cell colspan2">Ghi chú:</div>
		<div class="cell colspan10"><?php echo nl2br($dv['ghichu']); ?></div>
	</div>
</div>
<?php if($dv['status']): ?>
<div class="grid example">
<div class="row cells12">
		<div class="cell colspan12">
		<h3>Tình trạng xử lý</h3>
			<u>
			<?php
			foreach($dv['status'] as $k => $t){
				if($t['t'] == 0) {
					echo '<li>'.date("d/m/Y", $t['date_post']->sec) . ' - '. $arr_tinhtrang[$t['t']].' ['.$t['noidung'].']</li>';
				} else {
					echo '<li>'.date("d/m/Y", $t['date_post']->sec) . ' - '. $arr_tinhtrang[$t['t']].' ['.$t['noidung'].'] <a href="get.xoadangkytructuyen.php?id='.$dv['_id'].'&act=doanvao&key='.$k.'" class="xoadangky" onclick="return false;"><span class="mif-bin"></span></a></li>';
				}
			}
			?>
			</u>
		</div>
	</div>
</div>
<?php endif; ?>
<div class="align-center">
	<a href="convert_doanvao.php?id=<?php echo $id; ?>" class="button primary"><span class="mif-creative-cloud"></span> Xử lý hồ sơ</a>
	<a href="doanvao_regis.php" class="button"><span class="mif-keyboard-return"></span> Trở về</a>
</div>
<?php require_once('footer.php'); ?>