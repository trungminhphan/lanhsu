<?php require_once('header.php');
$donvi = new DonVi();
$id = isset($_GET['id']) ? $_GET['id'] : '';
$doanra_regis->id = $id; $dr = $doanra_regis->get_one();
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
<h1><a href="doanra_regis.php" class="nav-button transform"><span></span></a>&nbsp;Đăng ký trực tuyến - Chi tiết Đoàn Ra</h1>
<div class="padding10 align-center"><h3>Mã số hồ sơ: <b><?php echo $dr['masohoso']; ?></b></h3></div>
<div class="grid example">
	<div class="row cells12">
		<div class="cell colspan4 align-center"><b>Đơn vị</b></div>
		<div class="cell colspan4 align-center"><b>Số công văn</b></div>
		<div class="cell colspan4 align-center"><b>Ngày ký</b></div>
	</div>
	<?php
		//$donvi->id = $dr['congvanxinphep']['id_donvi'][0];$dvt = $donvi->get_one();
		$dvt = $donvi->tendonvi($dr['congvanxinphep']['id_donvi']);
	?>
	<div class="row cells12">
		<div class="cell colspan4">Xin phép: <?php echo $dvt; ?></div>
		<div class="cell colspan4 align-center"><?php echo $dr['congvanxinphep']['ten']; ?></div>
		<div class="cell colspan4 align-center"><?php echo $dr['congvanxinphep']['ngayky'] ? date("d/m/Y",$dr['congvanxinphep']['ngayky']->sec) : ''; ?></div>
	</div>
	<div class="row cells12">
		<div class="cell colspan12" style="padding-left:20px;">
		<?php
		if($dr['congvanxinphep']['attachments'] && count($dr['congvanxinphep']['attachments'])>0){
			foreach ($dr['congvanxinphep']['attachments'] as $key => $value) {
				$icon = show_icon($value['filetype']);
				echo '<span class="tag info padding5 margin5">'.$icon.' <a href="'.$folder_regis.$target_files_regis.$value['alias_name'].'" class="fg-white">'.$value['filename'].'</a></span>';
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
		<div class="cell colspan4">Ngày đi: <?php echo $dr['ngaydi'] ? date("d/m/Y",$dr['ngaydi']->sec) : ''; ?></div>
		<div class="cell colspan4">Ngày về: <?php echo $dr['ngayve'] ? date("d/m/Y",$dr['ngayve']->sec) : ''; ?></div>
     	<div class="cell colspan4">Số ngày: <?php echo $dr['songay']; ?></div>
	</div>
	<div class="row cells12">
		<?php
		$quocgia = new QuocGia();$qg = $quocgia->get_quoctich($dr['id_quocgia']);
		$mucdich = new MucDich();$mucdich->id = $dr['id_mucdich']; $md = $mucdich->get_one();
		$kinhphi = new KinhPhi();$kinhphi->id = $dr['id_kinhphi']; $kp = $kinhphi->get_one();
		?>
		<div class="cell colspan4">Nước đến: <?php echo $qg; ?></div>
		<div class="cell colspan4">Mục đích: <?php echo $md['ten']; ?></div>
     	<div class="cell colspan4">Kinh phí: <?php echo $kp['ten']; ?></div>
	</div>
	
	<div class="row cells12">
		<div class="cell colspan12">Nội dung: <?php echo $dr['noidung']; ?></div>
	</div>
	<div class="row cells12">
		<div class="cell colspan12">Ghi chú: <?php echo $dr['ghichu']; ?></div>
	</div>
</div>
<?php if($dr['status']): ?>
<div class="grid example">
<div class="row cells12">
		<div class="cell colspan12">
		<h3>Tình trạng xử lý</h3>
			<u>
			<?php
			foreach($dr['status'] as $k => $t){
				if($t['t'] == 0) {
					echo '<li>'.date("d/m/Y", $t['date_post']->sec) . ' - '. $arr_tinhtrang[$t['t']].' ['.$t['noidung'].']</li>';
				} else {
					echo '<li>'.date("d/m/Y", $t['date_post']->sec) . ' - '. $arr_tinhtrang[$t['t']].' ['.$t['noidung'].'] <a href="get.xoadangkytructuyen.php?id='.$dr['_id'].'&act=doanra&key='.$k.'" class="xoadangky" onclick="return false;"><span class="mif-bin"></span></a></li>';
				}
			}
			?>
			</u>
		</div>
	</div>
</div>
<?php endif; ?>
<div class="align-center">
	<a href="convert_doanra.php?id=<?php echo $id; ?>" class="button primary"><span class="mif-creative-cloud"></span> Xử lý hồ sơ</a>
	<a href="doanra_regis.php" class="button"><span class="mif-keyboard-return"></span> Trở về</a>
</div>
<?php require_once('footer.php'); ?>