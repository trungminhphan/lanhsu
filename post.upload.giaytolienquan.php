<?php
function __autoload($class_name) {
    require_once('cls/class.' . strtolower($class_name) . '.php');
}
require_once('inc/config.inc.php');
require_once('inc/functions.inc.php');
$id_giaytolienquan = isset($_POST['select_giaytolienquan']) ? $_POST['select_giaytolienquan'] : '';
$ten_dinh_kem = $arr_giaytolienquan[$id_giaytolienquan];

if(isset($_POST) and $_SERVER['REQUEST_METHOD'] == "POST"){
	if(isset($_FILES['dinhkem_filegiaytolienquan']['name']) && $_FILES['dinhkem_filegiaytolienquan']['name']){
		// Loop $_FILES to exeicute all files
		foreach ($_FILES['dinhkem_filegiaytolienquan']['name'] as $f => $name) {   
		    if ($_FILES['dinhkem_filegiaytolienquan']['error'][$f] == 4) {
		        echo 'Failed';
		        continue; // Skip file if any error found
		    } 
		    if ($_FILES['dinhkem_filegiaytolienquan']['error'][$f] == 0) {	           
		        if ($_FILES['dinhkem_filegiaytolienquan']['size'][$f] > $max_file_size) {
		        	echo '<div class="row cells12" style="padding:0px 0px 10px 0px;">';
					echo '<div class="cell colspan12">';
		            echo '<div class="info bg-red padding10 fg-white"><span class="mif-blocked"></span> '. $name.' quá lớn!.';
		            echo '</div>';
					echo '</div>';
		            continue; // Skip large files
		        } elseif(!in_array(strtolower(pathinfo($name, PATHINFO_EXTENSION)), $valid_formats) ){
					echo '<div class="row info cells12" style="padding:0px 0px 10px 0px;">';
					echo '<div class="cell colspan12">';
					echo '<div class="bg-red padding10 fg-white"><span class="mif-blocked"></span> ' . $name .' không được phép';
					echo '</div>';
					echo '</div>';
					continue; // Skip invalid file formats
				}
		        else{ // No error found! Move uploaded files 
					$extension = pathinfo($name, PATHINFO_EXTENSION);
					$alias = md5($name);
					$alias_name = $alias . '_'. date("Ymdhms") . '.' . $extension;
		            if(move_uploaded_file($_FILES["dinhkem_filegiaytolienquan"]["tmp_name"][$f], $target_files.$alias_name))
		            //$count++; // Number of successfully uploaded file
		        	echo '<div class="row cells12" style="padding:0px 0px 10px 0px;margin:0px;">';
						echo '<div class="cell colspan12 input-control text">';
							echo '<input type="hidden" name="giaytolienquan_alias_name[]" value="'.$alias_name.'" readonly/>';
							echo '<input type="hidden" name="giaytolienquan_filetype[]" value="'.$extension.'" readonly/>';
							echo '<input type="hidden" name="giaytolienquan_key[]" value="'.$id_giaytolienquan.'" readonly/>';
							echo '<span class="mif-attachment prepend-icon"></span>';
							echo '<input type="text" name="giaytolienquan_filename[]" value="'.$ten_dinh_kem . ' - ' . $name.'" class="bg-grayLighter" style="padding-left:50px;"/>';
							echo '<div class="button-group">';
								echo '<a href="get.xoataptin.php?filename='.$alias_name.'" onclick="return false;" class="delete_file button"><span class="mif-cross fg-red"></span></a>';
								echo '<a href="'.$target_files.$alias_name.'" class="button" target="_blank"><span class="mif-file-download fg-blue"></span></a>';
							echo '</div>';
						echo '</div>';
					echo '</div>';
		        }
		    }
		}
	} else {
		echo '<div class="info row cells12" style="padding:0px 0px 10px 0px;">';
		echo '<div class="cell colspan12 input-control text">';
		echo '<div class="bg-red padding10 fg-white"><span class="mif-blocked"></span> Không đủ bộ nhớ để upload, vui lòng chọn lại ít tập tin hơn</div>';
		echo '</div>';
		echo '</div>';
	}
}
?>