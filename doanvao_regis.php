<?php require_once('header.php');
$donvi = new DonVi();
$doanvao_regis_list = $doanvao_regis->get_all_list();
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
<h1><a href="index.php" class="nav-button transform"><span></span></a>&nbsp;Đăng ký trực tuyến - Đoàn Vào</h1>
<?php if($doanvao_regis_list && $doanvao_regis_list->count() > 0): ?>
<table class="table striped hovered dataTable" data-role="datatable">
<thead>
	<tr>
		<th>STT</th>
		<th>Mã Hồ sơ</th>
		<th>Công văn xin phép</th>
		<th>Đơn vị tiếp</th>
		<th>Ngày đến</th>
		<th>Ngày đi</th>
		<th>Ngày đăng ký</th>
		<th>Tình trạng</th>
		<?php if($users->is_manager()): ?>
			<th><span class="mif-bin"></span></th>
		<?php endif; ?>

		<?php if($users->is_manager() || $users->is_updater()): ?>
			<!--<th><span class="mif-pencil"></span></th>-->
			<th><span class="mif-creative-cloud"></span></th>
		<?php endif; ?>
	</tr>
</thead>
<tbody>
<?php
	$i=1;
	foreach ($doanvao_regis_list as $dv) {
		$donvi->id = $dv['congvanxinphep']['id_donvi'][0];$dvi = $donvi->get_one();
		$t = isset($dv['status'][0]['t'])  ? $dv['status'][0]['t'] : 0;
		/*if(isset($dv['status'][0]['t']) && $dv['status'][0]['t'] == 3) $tinhtrang = '<span class="mif-checkmark fg-blue"></span>';
		else if(isset($dv['status'][0]['t']) && $dv['status'][0]['t'] == 4) $tinhtrang = '<span class="mif-not fg-red"></span>';
		else $tinhtrang = '<span class="mif-folder-upload fg-red"></span>';*/

		echo '<td>'.$i++.'</td>';
		echo '<td><a href="chitietdoanvao_regis.php?id='.$dv['_id'].'">'.$dv['masohoso'].'</a></td>';
		echo '<td>'.$dv['congvanxinphep']['ten'].'</td>';
		echo '<td>'.$dvi['ten'].'</td>';
		echo '<td>'.($dv['ngayden'] ? date("d/m/Y",$dv['ngayden']->sec) : '').'</td>';
		echo '<td>'.($dv['ngaydi'] ? date("d/m/Y",$dv['ngaydi']->sec) : '').'</td>';
		echo '<td>'.($dv['date_post'] ? date("d/m/Y H:i",$dv['date_post']->sec) : '').'</td>';
		echo '<td class="align-center"><a href="#" onlich="return false;" class="capnhattinhtrang" name="'.$dv['_id'].'"><img src="images/status/'.$t.'.png" style="height:30px;"/></a></td>';
		if($users->is_manager()){
			echo '<td><a href="delete_regis.php?id='.$dv['_id'].'&act=doanvao" onclick="return confirm(\'Chắc chắc xoá?\');"><span class="mif-bin"></span></a></td>';
		}
		if($users->is_manager() || $users->is_updater()){
			//echo '<td><a href=""><span class="mif-pencil"></a></td>';
			echo '<td><a href="convert_doanvao.php?id='.$dv['_id'].'"><span class="mif-creative-cloud"></span></a></td>';
		}
		echo '</tr>';
	}
?>
</tbody>
</table>
<?php endif; ?>
<div data-role="dialog" id="dialog-tinhtrang" class="padding20 block-shadow-danger" data-close-button="true" data-overlay="true" data-width="700">
	<form method="POST" action="#" id="themtinhtrang">
	<input type="hidden" name="id" id="id" value="" />
	<input type="hidden" name="act" id="act" value="doanvao" />
	<h2><span class="mif-flag fg-black"></span> Cập nhật tình trạng?</h2>
	<div class="grid padding-top-10">
		<div class="row cells12">
			<div class="cell colspan2 padding-top-10 align-right">Tình trạng</div>
			<div class="cell colspan6 input-control select">
				<select name="id_tinhtrang" id="id_tinhtrang" class="select2">
				<?php
				foreach($arr_tinhtrang as $key => $value){
					if($key > 0){
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
