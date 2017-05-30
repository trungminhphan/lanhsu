<?php
	//DEFINE QUYEN CHO TUNG NGUOI
	define("ADMIN", 1);
	define("MANAGER", 2);
	define("UPDATER", 4);
	define('STUDENT', 8);

	//define("VIEWER", 8);
	//$g_config['db_server'] = 'mysql.agu.edu.vn';
	/*$g_config['db_server'] = 'localhost';
	$g_config['db_username'] = 'root';
	$g_config['db_password'] = 'root';
	$g_config['db_database_name'] = 'ptth';
	$g_config['image_folder'] = 'images/';*/

	//$mongo_server = 'localhost';
	//$mongo_username = 'admin';
	//$mongo_password = 'admin';
	//$mongo_database_name = 'ptth';

	//$g_config['admin_user'] = 'admin';
	//$g_config['admin_password'] = MD5('pmtrung');
	//$g_config['admin_password'] = '6701a36f285636bfbf4afc07bed8fc0c';
	
	//$hash_password["crypt"] = 'MD5';
	//$hash_password["password"] = 'PASSWORD';
	//$hash_password[""] = 'PLAIN_TEXT';
	
	//smtp config for send mail
	$items_per_group = 30;
	$items_per_group_dicu = 30;
	$items_per_group_thongke = 50;
	$items_per_group_ctcd = 300;

	$target_files = 'uploads/files/';
	$target_files_regis = 'uploads_regis/';
	$folder_regis = '../lanhsuthutuc/';
	$target_images = 'uploads/images/';
	$target_files_public = '../lanhsuthutuc/uploads/';
	$folder_files_public = 'uploads/';
	$files_extension = array('pdf', 'zip', 'rar', 'doc', 'docx', 'xls', 'png', 'gif', 'jpg', 'jpeg', 'bmp', 'rtf');
	$images_extension = array('png', 'gif', 'jpg', 'jpeg', 'bmp');
	$valid_formats = array("jpg", "png", "gif", "zip", "bmp", "doc", "docx", "pdf", "xls", "xlsx", "ppt", "pptx", 'zip', 'rar');
	$max_file_size = 50*1024*1024*1024; //50MB
	
	$arr_giaytolienquan = array('1. Giấy chứng nhận đăng ký kinh doanh','2. Giấy đăng ký thuế', '3. Các hợp đồng kinh tế với đối tác APEC', '4. Hộ chiếu – Thẻ APEC (trường hợp gia hạn)', '5. Sổ Bảo hiểm xã hội', '6. Văn bản xác nhận nghĩa vụ thuế', '7. Các giấy tờ khác');
	$arr_donvitien = array('AUD'=>'AUST.DOLLAR','CAD'=>'CANADIAN DOLLAR','CHF'=>'SWISS FRANCE','DKK'=>'DANISH KRONE','EUR'=>'EURO','GBP'=>'BRITISH POUND','HKD'=>'HONGKONG DOLLAR','INR'=>'INDIAN RUPEE','JPY'=>'JAPANESE YEN','KRW'=>'SOUTH KOREAN WON','KWD'=>'KUWAITI DINAR','MYR'=>'MALAYSIAN RINGGIT','NOK'=>'NORWEGIAN KRONER','RUB'=>'RUSSIAN RUBLE','SAR'=>'SAUDI RIAL','SEK'=>'SWEDISH KRONA','SGD'=>'SINGAPORE DOLLAR','THB'=>'THAI BAHT','USD'=>'US DOLLAR');
	$arr_loaivanban = array('Xuất cảnh', 'Nhập cảnh', 'ABTC');

	$arr_tinhtrang = array(
		0 => 'Đang xử lý',
		1 => 'Chờ bổ sung hồ sơ',
		2 => 'Đang trình UBND ký',
		3 => 'Hoàn tất hồ sơ',
		4 => 'Hồ sơ không được duyệt'
	);
?>