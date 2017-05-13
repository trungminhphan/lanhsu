<?php
require_once('header.php');
if(!($users->getRole() & ADMIN)){
	echo '<div class="messages">Bạn không có quyền...! <a href="index.php">Trở về</a></div>';
	require_once('footer.php');
	exit();
}

if(isset($_GET['submit'])){
	$keysearch = isset($_GET['keysearch']) ? $_GET['keysearch'] : '';
	$users_list = $users->get_list_condition(array('username'=> new MongoRegex('/'.$keysearch.'/')));

} else {
	$users_list = $users->get_list_50();	
}
?>

<div style="padding:5px;">
<form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="GET" id="findusers">
<a href="users_add.php" class="button primary fg-white"><span class="mif-user-plus"></span> Thêm tài khoản</a>
<div class="input-control text">
	<input type="text" name="keysearch" id="keysearch" value="" size="50" placeholder="Tìm tài khoản người dùng">
</div>
<button name="submit" value="OK" class="button danger"><span class="mif-search"></span> Tìm</button>
</form>
</div>
<?php if($users_list): ?>
<table class="table striped hovered">
<thead>
<tr>
	<th>#</th>
	<th>Tài khoản người dùng</th>
	<th>Họ tên</th>
	<th>ADMIN</th>
	<th>MANAGER</th>
	<th>UPDATER</th>
	<th>STUDENT</th>
	<th><span class="mif-bin"></th>
	<th><span class="mif-pencil"></span></th>
</tr>
</thead>
<?php 
$i =1;
foreach($users_list as $ul){
	if($i%2==0) $class='eve';
	else $class = 'odd';
	echo '<tr class="'.$class.'">';
	echo '<td align="center">'.$i.'</td>';
	echo '<td align="center">'.$ul['username'].'</td>';
	echo '<td>'.$ul['person'].'</td>';
	echo '<td>'.(($ul['roles'] & ADMIN) ? '<img src="images/icon_yes.png" />' : '<img src="images/icon_no.png" />').'</td>';
	echo '<td>'.(($ul['roles'] & MANAGER) ? '<img src="images/icon_yes.png" />' : '<img src="images/icon_no.png" />').'</td>';
	echo '<td>'.(($ul['roles'] & UPDATER) ? '<img src="images/icon_yes.png" />' : '<img src="images/icon_no.png" />').'</td>';
	echo '<td>'.(($ul['roles'] & STUDENT) ? '<img src="images/icon_yes.png" />' : '<img src="images/icon_no.png" />').'</td>';
	if($ul['roles'] & ADMIN){
		echo '<td></td>';
	} else {
		echo '<td><a href="users_delete.php?id='.$ul['_id'].'" onclick="return confirm(\'Chắc chắn xoá?\');"><span class="mif-bin"></a></td>';
	}
	echo '<td><a href="users_add.php?id='.$ul['_id'].'"><span class="mif-pencil"></a></td>';
	echo '</tr>';
	$i++;
}
?>
</table>
<?php endif; ?>

<?php require_once('footer.php'); ?>