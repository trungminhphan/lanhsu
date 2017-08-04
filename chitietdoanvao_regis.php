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
		$(".suadangky").click(function(){
			var _this = $(this); var _link = _this.attr("href");
			$.getJSON(_link, function(data){
				$("#id").val(data.id);
				$("#noidung").val(data.noidung);
				$("#key").val(data.k);
				$("#id_tinhtrang").val(data.t);$("#id_tinhtrang").select2();
			});
			dialog = $("#dialog-tinhtrang").data('dialog');
			dialog.open();
			$("#themtinhtrang_no").click(function(){
				dialog.close();
			});
			$("#themtinhtrang_ok").click(function(){
				$.ajax({
		            type: "POST",
		            url: "post.themtinhtrang.php",
		            data: $("#themtinhtrang").serialize(),
		            success: function(datas) {
		                if(datas=='Failed'){
		                    $.Notify({type: 'alert', caption: 'Thông báo', content: 'Cập nhật thất bại.'});
		                } else {
		                    location.reload();
		                }
		           }
		        }).fail(function() {
		            $.Notify({type: 'alert', caption: 'Thông báo', content: "Cập nhật thất bại."});
		        });
				dialog.close();
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
					echo '<li>'.date("d/m/Y", $t['date_post']->sec) . ' - '. $arr_tinhtrang[$t['t']].' ['.$t['noidung'].'] <a href="get.xoadangkytructuyen.php?id='.$dv['_id'].'&act=doanvao&key='.$k.'" class="xoadangky" onclick="return false;"><span class="mif-bin"></span></a><a href="get.suadangkytructuyen.php?id='.$dv['_id'].'&act=doanvao&key='.$k.'" class="suadangky" onclick="return false;"><span class="mif-pencil"></span></a></li>';
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

<div data-role="dialog" id="dialog-tinhtrang" class="padding20 block-shadow-danger" data-close-button="true" data-overlay="true" data-width="700">
	<form method="POST" action="#" id="themtinhtrang">
	<input type="hidden" name="id" id="id" value="" />
	<input type="hidden" name="act" id="act" value="doanvao" />
	<input type="hidden" name="edit" id="edit" value="edit" />
	<input type="hidden" name="key" id="key" value="" />
	<h2><span class="mif-flag fg-black"></span> Cập nhật tình trạng?</h2>
	<div class="grid padding-top-10">
		<div class="row cells12">
			<div class="cell colspan2 padding-top-10 align-right">Tình trạng</div>
			<div class="cell colspan6 input-control select">
				<select name="id_tinhtrang" id="id_tinhtrang" class="select2">
				<?php
				foreach($arr_tinhtrang as $key => $value){
					if($key > 0) {
						echo '<option value="'.$key.'">'.$value.'</option>'; 
					}
				}
				?>
				</select>
			</div>
		</div>
		<div class="row cells12">
			<div class="cell colspan2 padding-top-10 align-right">Nội dung</div>
			<div class="cell colspan6 input-control textarea">
				<textarea name="noidung" id="noidung" placeholder="Nội dung" style="width:450px;"></textarea>
			</div>
		</div>
		<div class="row cells12">
			<div class="cell colspan12 align-center">
				<a href="#" id="themtinhtrang_ok" value="OK" class="button primary"><span class="mif-checkmark"></span> Cập nhật</a>
				<a href="#" id="themtinhtrang_no" class="button"><span class="mif-keyboard-return"></span> Huỷ</a>
			</div>
		</div>
	</div>
	</form>
</div>
<?php require_once('footer.php'); ?>