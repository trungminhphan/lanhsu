<?php
function __autoload($class_name) {
    require_once('cls/class.' . strtolower($class_name) . '.php');
}
require_once('inc/config.inc.php');
$count = 0;$donvi = new DonVi();
$id_donvi_xinphep_1 = isset($_POST['id_donvi_xinphep_1']) ? $_POST['id_donvi_xinphep_1'] : '';
$id_donvi_xinphep_2 = isset($_POST['id_donvi_xinphep_2']) ? $_POST['id_donvi_xinphep_2'] : '';
$id_donvi_xinphep_3 = isset($_POST['id_donvi_xinphep_3']) ? $_POST['id_donvi_xinphep_3'] : '';
$id_donvi_xinphep_4 = isset($_POST['id_donvi_xinphep_4']) ? $_POST['id_donvi_xinphep_4'] : '';
$tencongvanxinphep = isset($_POST['tencongvanxinphep']) ? $_POST['tencongvanxinphep'] : '';
$ngaykycongvanxinphep = isset($_POST['ngaykycongvanxinphep']) ? $_POST['ngaykycongvanxinphep'] : '';
$arr_donvi = array($id_donvi_xinphep_1, $id_donvi_xinphep_2,$id_donvi_xinphep_3,$id_donvi_xinphep_4);
//$id_donvi_tiep = isset($_POST['id_donvi_tiep']) ? $_POST['id_donvi_tiep'] : '';

/*if($id_donvi_tiep){
	$donvi->id = $id_donvi_tiep; $dvt = $donvi->get_one();$tendonvitiep = $dvt['ten'];
} else { $tendonvitiep = '';}*/

if($tencongvanxinphep) $ten_dinh_kem = $donvi->tendonvi($arr_donvi) .' - ' . $tencongvanxinphep. ' - ' . $ngaykycongvanxinphep;
else $ten_dinh_kem = $donvi->tendonvi($arr_donvi) . ' - ' .$ngaykycongvanxinphep;

if(isset($_POST) and $_SERVER['REQUEST_METHOD'] == "POST"){
	if(isset($_FILES['dinhkem_filecongvanxinphep']['name']) && $_FILES['dinhkem_filecongvanxinphep']['name']){
		// Loop $_FILES to exeicute all files
		foreach ($_FILES['dinhkem_filecongvanxinphep']['name'] as $f => $name) {   
		    if ($_FILES['dinhkem_filecongvanxinphep']['error'][$f] == 4) {
		        echo 'Failed';
		        continue; // Skip file if any error found
		    } 
		    if ($_FILES['dinhkem_filecongvanxinphep']['error'][$f] == 0) {	           
		        if ($_FILES['dinhkem_filecongvanxinphep']['size'][$f] > $max_file_size) {
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
				}
		        else{ // No error found! Move uploaded files 
					$extension = pathinfo($name, PATHINFO_EXTENSION);
					$alias = md5($name);
					$alias_name = $alias . '_'. date("Ymdhms") . '.' . $extension;
		            if(move_uploaded_file($_FILES["dinhkem_filecongvanxinphep"]["tmp_name"][$f], $target_files.$alias_name)){
			            $count++; // Number of successfully uploaded file
			        	echo '<div class="row cells12" style="padding:0px 0px 10px 0px;margin:0px;">';
							echo '<div class="cell colspan2"></div>';
							echo '<div class="cell colspan10 input-control text">';
								echo '<input type="hidden" name="congvanxinphep_alias_name[]" value="'.$alias_name.'" readonly/>';
								echo '<input type="hidden" name="congvanxinphep_filetype[]" value="'.$extension.'" readonly/>';
								echo '<span class="mif-attachment prepend-icon"></span>';
								echo '<input type="text" name="congvanxinphep_filename[]" value="'.$ten_dinh_kem.'" class="bg-grayLighter" style="padding-left:50px;"/>';
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
?>