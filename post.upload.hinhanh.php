<?php
require_once('header_none.php'); 
$count = 0;
if(isset($_POST) and $_SERVER['REQUEST_METHOD'] == "POST"){
	if(isset($_FILES['dinhkem']['name']) && $_FILES['dinhkem']['name']){
		// Loop $_FILES to exeicute all files
		foreach ($_FILES['dinhkem']['name'] as $f => $name) {   
		    if ($_FILES['dinhkem']['error'][$f] == 4) {
		        echo 'Failed';
		        continue; // Skip file if any error found
		    } 
		    if ($_FILES['dinhkem']['error'][$f] == 0) {	           
		        if ($_FILES['dinhkem']['size'][$f] > $max_file_size) {
		        	echo 'Failed';
		            continue; // Skip large files
		        } elseif(!in_array(strtolower(pathinfo($name, PATHINFO_EXTENSION)), $valid_formats) ){
					echo 'Failed';
					continue; // Skip invalid file formats
				} else{ // No error found! Move uploaded files 
					$extension = pathinfo($name, PATHINFO_EXTENSION);
					$alias = md5($name);
					$alias_name = $alias . '_'. date("Ymdhms") . '.' . $extension;
		            if(move_uploaded_file($_FILES["dinhkem"]["tmp_name"][$f], $target_files_public.$alias_name)){
			            $count++; // Number of successfully uploaded file
			        	echo '<div class="row cells12">
			                <div class="cell colspan2"></div>
			                <div class="cell colspan10 input-control text">
			                    <span class="input-group-addon"><i class="fa fa-image"></i></span>
			                    <input type="hidden" name="hinhanh_aliasname[]" value="'.$alias_name.'" readonly/>
			                    <input type="text" name="hinhanh_filename[]" value="'.$name.'" class="form-control"/>
			                    <div class="button-group">
			                    	<a href="'.$target_files_public.$alias_name.'" onclick="return false;" class="button"><span class="mif-image"></span></a>
			                    	<a href="get.xoahinhanh.php?filename='.$alias_name.'" onclick="return false;" class="delete_file button"><span class="mif-bin"></span></a>
			                    </div>
			                </div>
			            </div>';
			        } else {
			        	echo 'Failed';
			        }
		        }
		    }
		}
	} else {
		echo 'Failed';
	}
}
?>