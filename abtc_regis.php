<?php require_once('header.php');
$abtc_regis_list = $abtc_regis->get_all_list();
$update = isset($_GET['update']) ? $_GET['update'] : '';
switch ($update) {
	case 'convert_ok': $msg = 'Xử lý hồ sơ thành công';	break;
}
?>
<script type="text/javascript" src="js/jquery.dataTables.min.js"></script>
<script type="text/javascript" src="js/select2.min.js"></script>
<script type="text/javascript">
	$(document).ready(function(){
		var dialog; $(".select2").select2();
		<?php if(isset($msg) && $msg) : ?>
        	$.Notify({type: 'alert', caption: 'Thông báo', content: '<?php echo $msg; ?>'});
    	<?php endif; ?>
    	$(".capnhattinhtrang").click(function(){
    		var id = $(this).attr("name");$("#id").val(id);
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

<h1><a href="index.php" class="nav-button transform"><span></span></a>&nbsp;Đăng ký trực tuyến - ABTC</h1>
<?php if($abtc_regis_list && $abtc_regis_list->count() > 0): ?>
<table class="table striped hovered dataTable" data-role="datatable">
<thead>
	<tr>
		<th>STT</th>
		<th>Mã sồ hồ sơ</th>
		<th>Công văn xin phép</th>
		<th>Ngày ký</th>
		<th>Tình trạng</th>
		<?php if($users->is_manager()): ?>
			<th><span class="mif-bin"></span></th>
		<?php endif; ?>
		<?php if($users->is_manager() || $users->is_updater()): ?>
			<th><span class="mif-creative-cloud"></span></th>
		<?php endif; ?>
	</tr>
</thead>
<tbody>
	<?php
		$i = 1;
		foreach ($abtc_regis_list as $a) {
			//$quocgia->id = $a['id_quocgia'];$qg= $quocgia->get_one();
			$count = count($a['status']) - 1;
			if(isset($a['status'][0]['t']) && $a['status'][0]['t'] == 3) $tinhtrang = '<span class="mif-checkmark fg-blue"></span>';
			else if(isset($a['status'][0]['t']) && $a['status'][0]['t'] == 4) $tinhtrang = '<span class="mif-not fg-red"></span>';
			else $tinhtrang = '<span class="mif-folder-upload fg-red"></span>';
			echo '<tr>';
			echo '<td>'.$i.'</td>';
			echo '<td><a href="chitietabct_regis.php?id='.$a['_id'].'">'.$a['masohoso'].'</a></td>';
			echo '<td>'.$a['congvanxinphep']['ten'].'</td>';
			echo '<td>'.($a['congvanxinphep']['ngayky'] ? date("d/m/Y",$a['congvanxinphep']['ngayky']->sec) : '').'</td>';
			echo '<td class="align-center"><a href="#" onlich="return false;" class="capnhattinhtrang" name="'.$a['_id'].'">'.$tinhtrang.'</a></td>';
			if($users->is_manager()){
				echo '<td><a href="delete_regis.php?id='.$a['_id'].'&act=abtc" onclick="return confirm(\'Chắc chắc xoá?\');" title="Xoá"><span class="mif-bin"></span></td>';
			}
			if($users->is_manager() || $users->id_updater()){
				echo '<td><a href="convert_abtc.php?id='.$a['_id'].'"><span class="mif-creative-cloud"></span></a></td>';
			}
			echo '</tr>';
			$i++;
		}
	?>
</tbody>
</table>
<?php endif; ?>
<div data-role="dialog" id="dialog-tinhtrang" class="padding20 block-shadow-danger" data-close-button="true" data-overlay="true" data-width="700">
	<form method="POST" action="#" id="themtinhtrang">
	<input type="hidden" name="id" id="id" value="" />
	<input type="hidden" name="act" id="act" value="abtc" />
	<h2><span class="mif-flag fg-black"></span> Cập nhật tình trạng?</h2>
	<div class="grid padding-top-10">
		<div class="row cells12">
			<div class="cell colspan2 padding-top-10 align-right">Tình trạng</div>
			<div class="cell colspan6 input-control select">
				<select name="id_tinhtrang" id="id_tinhtrang" class="select2">
				<?php
				foreach($arr_tinhtrang as $key => $value){
					echo '<option value="'.$key.'">'.$value.'</option>';
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