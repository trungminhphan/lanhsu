<?php 
require_once('header.php');
if(!$users->is_admin()){
	echo 'Bạn không có quyền xem';
	exit();
}
$id = isset($_GET['id']) ? $_GET['id'] : '';
$collection = isset($_GET['collection']) ? $_GET['collection'] : '';
$logs = new Logs();
$arr = array('doanra' => 'Đoàn ra', 'doanvao' => 'Đoàn vào', 'abtc' => 'ABCT', 'canbo' => 'Cá nhân');
$logs_list = $logs->get_List_condition(array('datas._id' => new MongoId($id)));
?>
<script type="text/javascript">
	$(document).ready(function(){
		$(".logs_chitiet").click(function(){
			var link = $(this).attr("href");
			$.get(link, function(data){
				$("#log_content").html(data);
				dialog_logs = $("#logs").data('dialog');
				dialog_logs.open();
			});
		});
	});
</script>
<h1><a href="chitiet<?php echo $collection; ?>.php?id=<?php echo $id;?>" class="nav-button transform"><span></span></a>&nbsp;Nhật ký nhập liệu</h1>
<?php if($logs_list && $logs_list->count() > 0): ?>
<table class="table hovered border bordered">
	<thead>
		<tr>
			<th>STT</th>
			<th>Người nhập</th>
			<th>Thao tác</th>
			<th>Ngày thực hiện</th>
			<th>Quản lý</th>
			<th class="align-center">Chi tiết</th>
		</tr>
	</thead>
	<tbody>
	<?php
	$arr_id = array();$count=0;
	foreach ($logs_list as $l) {
		array_push($arr_id, $l['_id']);$count++;
	}
	$i = 1;$index = 0;
	foreach ($logs_list as $l) {
		$users->id = $l['id_user'];
		$u = $users->get_one();
		$ngay = $l['date_post'] ? date("d/m/Y H:s", $l['date_post']->sec) : '';
		echo '<tr>';
		echo '<td>'.$i.'</td>';
		echo '<td>'.$u['username'].'</td>';
		echo '<td>'.$l['action'].'</td>';
		echo '<td>'.$ngay.'</td>';
		echo '<td>'.$arr[$l['collections']].'</td>';
		$j = $index + 1;
		if($index < $count-1)
			echo '<td class="align-center"><a href="logs_chitiet.php?id='.$l['_id'].'&collection='.$l['collections'].'&id_next='.$arr_id[$j].'" onclick="return false;" class="logs_chitiet"><span class="mif-list"></span></a></td>';
		else 
			echo '<td class="align-center"><a href="logs_chitiet.php?id='.$l['_id'].'&collection='.$l['collections'].'" onclick="return false;" class="logs_chitiet"><span class="mif-list"></span></a></td>';
		echo '<tr>';$i++;$index++;
	}
	?>
	</tbody>
</table>
<div data-role="dialog" id="logs" class="padding20 block-shadow-danger" data-close-button="true" data-windows-style="true" data-height="600" style="overflow:scroll;">
	<div id="log_content"></div>
</div>
<?php endif; ?>
<?php require_once('footer.php'); ?>