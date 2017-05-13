<?php
require_once('header.php');

if(!($users->getRole() & ADMIN)){
	echo '<div class="messages">Bạn không có quyền...! <a href="index.php">Trở về</a></div>';
	require_once('footer.php');
	exit();
}
$id = isset($_GET['id']) ? $_GET['id'] : '';
$users->id = $id;

$u = $users->get_one();
if($u['roles'] & ADMIN) {
	echo 'Không thể xoá Admin. <a href="users.php">Trở về</a>';
} else {
	if($users->delete()){
		transfers_to('users.php');
	} else {
		echo '<div class="messages">Không thể xoá...<a herf="users.php">Trở về</a></div>';
	}	
}

?>
<?php require_once('footer.php'); ?>