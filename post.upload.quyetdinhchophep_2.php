<?php
function __autoload($class_name) {
    require_once('cls/class.' . strtolower($class_name) . '.php');
}
require_once('inc/config.inc.php');
$count = 0;$donvi = new DonVi();
$id_donvi_chophep_2 = isset($_POST['id_donvi_chophep_2']) ? $_POST['id_donvi_chophep_2'] : '';
$ngaybanhanhquyetdinhchophep_2 = isset($_POST['ngaybanhanhquyetdinhchophep_2']) ? $_POST['ngaybanhanhquyetdinhchophep_2'] : '';

if(!$id_donvi_chophep_2){
	echo '<div class="info row cells12" style="padding:0px 0px 10px 0px;">';
		echo '<div class="cell colspan2"></div>';
		echo '<div class="cell colspan10 input-control text">';
		echo '<div class="bg-red padding10 fg-white"><span class="mif-blocked"></span> Vui lòng chọn Đơn vị cho phép.</div>';
		echo '</div>';
	echo '</div>';
} else {
	$tenquyetdinhchophep_2 = isset($_POST['tenquyetdinhchophep_2']) ? $_POST['tenquyetdinhchophep_2'] : '';
	$donvi->id = $id_donvi_chophep_2; $dv=$donvi->get_one();
	if($tenquyetdinhchophep_2 || $ngaybanhanhquyetdinhchophep_2) $tendinhkem = $dv['ten'] . ' - ' . $tenquyetdinhchophep_2 . ' - ' . $ngaybanhanhquyetdinhchophep_2;
	else $tendinhkem = $dv['ten'];

	if(isset($_POST) and $_SERVER['REQUEST_METHOD'] == "POST"){
		if(isset($_FILES['dinhkem_quyetdinhchophep_2']['name']) && $_FILES['dinhkem_quyetdinhchophep_2']['name']){
			// Loop $_FILES to exeicute all files
			foreach ($_FILES['dinhkem_quyetdinhchophep_2']['name'] as $f => $name) {   
			    if ($_FILES['dinhkem_quyetdinhchophep_2']['error'][$f] == 4) {
			        echo 'Failed';
			        continue; // Skip file if any error found
			    }
			    if ($_FILES['dinhkem_quyetdinhchophep_2']['error'][$f] == 0) {	           
			        if ($_FILES['dinhkem_quyetdinhchophep_2']['size'][$f] > $max_file_size) {
			        	echo '<div class="row cells12" style="padding:0px 0px 10px 0px;">';
						echo '<div class="cell colspan2"></div>';
						echo '<div class="cell colspan10">';
			            echo '<div class="info bg-red padding10 fg-white"><span class="mif-blocked"></span> '. $name.' quá lớn!.';
			            echo '</div>';
						echo '</div>';
			            continue; // Skip large files
			        } elseif(!in_array(strtolower(pathinfo($name, PATHINFO_EXTENSION)), $valid_formats) ){
						echo '<div class="row info cells12" style="padding:0px 0px 10px 0px;">';
						echo '<div class="cell colspan2"></div>';
						echo '<div class="cell colspan10">';
						echo '<div class="bg-red padding10 fg-white"><span class="mif-blocked"></span> ' . $name .' không được phép';
						echo '</div>';
						echo '</div>';
						continue; // Skip invalid file formats
					} else{ // No error found! Move uploaded files 
						$extension = pathinfo($name, PATHINFO_EXTENSION);
						$alias = md5($name);
						$alias_name =  $alias . '_'. date("Ymdhms") . '.' . $extension;
			            if(move_uploaded_file($_FILES["dinhkem_quyetdinhchophep_2"]["tmp_name"][$f], $target_files.$alias_name)){
				            $count++; // Number of successfully uploaded file
				        	echo '<div class="row cells12" style="padding:0px 0px 10px 0px;margin:0px;">';
								echo '<div class="cell colspan2"></div>';
								echo '<div class="cell colspan10 input-control text">';
									echo '<input type="hidden" name="quyetdinhchophep_alias_name_2[]" value="'.$alias_name.'" readonly/>';
									echo '<input type="hidden" name="quyetdinhchophep_filetype_2[]" value="'.$extension.'" readonly/>';
									echo '<span class="mif-attachment prepend-icon"></span>';
									echo '<input type="text" name="quyetdinhchophep_filename_2[]" value="'.$tendinhkem.'" class="bg-grayLighter" style="padding-left:50px;"/>';
									echo '<div class="button-group">';
										echo '<a href="get.xoataptin.php?filename='.$alias_name.'" onclick="return false;" class="delete_file button"><span class="mif-cross fg-red"></span></a>';
										echo '<a href="'.$target_files.$alias_name.'" class="button" target="_blank"><span class="mif-file-download fg-blue"></span></a>';
									echo '</div>';
								echo '</div>';
							echo '</div>';
						} else {
							echo '<div class="info row cells12" style="padding:0px 0px 10px 0px;">';
							echo '<div class="cell colspan2"></div>';
							echo '<div class="cell colspan10 input-control text">';
							echo '<div class="bg-red padding10 fg-white"><span class="mif-blocked"></span> Không đủ bộ nhớ để upload, vui lòng chọn lại ít tập tin hơn</div>';
							echo '</div>';
							echo '</div>';
						}
			        }
			    }
			}
		} else {
			echo '<div class="info row cells12" style="padding:0px 0px 10px 0px;">';
			echo '<div class="cell colspan2"></div>';
			echo '<div class="cell colspan10 input-control text">';
			echo '<div class="bg-red padding10 fg-white"><span class="mif-blocked"></span> Không đủ bộ nhớ để upload, vui lòng chọn lại ít tập tin hơn</div>';
			echo '</div>';
			echo '</div>';
		}
	}
}
?>