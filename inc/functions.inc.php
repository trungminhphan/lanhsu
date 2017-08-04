<?php
function convert_string_number($string){
	$len_of_string = strlen($string);
	$i = 0;
	$number = '';
	for($i=0; $i<$len_of_string; $i++){
		if($string[$i] != ".") $number .= $string[$i];
	}
	$number = str_replace(",",".",$number);
	doubleval($number);
	return $number;
}

function transfers_to($url){ 	header('Location: ' . $url); }

function format_number($number){
	return number_format($number, 0, ",", ".");
}

function format_decimal($number, $dec){
	return number_format($number, $dec, ",", ".");
}
function format_date($date){
	return date("d/m/Y",strtotime($date));
}

function show_gioitinh($gioitinh){
	if($gioitinh == 1) return 'Nam';
	else return 'Nữ';
}

function quote_smart($value){
    if(get_magic_quotes_gpc()){
		$value=stripcslashes($value);    
    }
	$value=addslashes($value);
	return $value;    
}

function convert_date_yyyy_mm_dd($str_date){
	$s = explode ("/", $str_date);
	return strtotime($s[2] . '-'. $s[1] . '-' . $s[0] . ' 00:00:00');
}
function convert_date_mm_yyyy($string_date){
	$s = explode ("/", $string_date);
	return strtotime($s[1] . '-'. $s[0] . '-01 00:00:00');
}

function convert_date_dd_mm_yyyy($string_date){
	//if(strlen($string_date) >= 8){
		$s = explode ("/", $string_date);
		if(count($s)==3 && $s[2] && $s[1] && $s[0]){
			return strtotime($s[2].'-'.$s[1].'-'.$s[0] . ' 00:00:00');
		} else {
			return strtotime($string_date . '-01-01 00:00:00');
		}
	//} else {
		//return strtotime($string_date . '-01-01 00:00:00');
	//}
}
function convert_date_dd_mm_yyyy_1($string_date){
	$s = explode ("/", $string_date);
	return strtotime($s[2].'-'.$s[1].'-'.$s[0] . ' 00:00:00');
}


function checkDateTime($data) {
    if (date('Y-m-d H:i:s', strtotime($data)) == $data) {
        return true;
    } else {
        return false;
    }
}

function show_icon($icon){
	$str_icon = '';
	switch(strtolower($icon)){
		case 'pdf': $str_icon = 'mif-file-pdf'; break;
		case 'doc': $str_icon = 'mif-file-word'; break;
		case 'docx': $str_icon = 'mif-file-word'; break;
		case 'ppt': $str_icon = 'mif-file-powerpoint'; break;
		case 'pptx': $str_icon = 'mif-file-powerpoint'; break;
		case 'xls': $str_icon = 'mif-file-excel'; break;
		case 'xlsx': $str_icon = 'mif-file-excel'; break;
		case 'zip': $str_icon = 'mif-file-zip'; break;
		case 'rar': $str_icon = 'mif-file-zip'; break;
		case '7z': $str_icon = 'mif-file-zip'; break;
		case 'jpg': $str_icon = 'mif-images'; break;
		case 'png': $str_icon = 'mif-images'; break;
		case 'jpeg': $str_icon = 'mif-images'; break;
		case 'gif': $str_icon = 'mif-images'; break;
		default: 
			$str_icon = 'mif-attachment';
	}

	return '<span class="'.$str_icon.'"></span>';
}

//tinh so ngay tru ngay thu 7 & CN
function tinhngay($today, $songay){
	$ngaynghi = 0;$day = '';
	for($i=0; $i<$songay; $i++){
		$day = strtotime(date("Y-m-d", strtotime($today)) . " +".$i." day");
		if(date("D", $day) == 'Sat' || date("D", $day)=='Sun'){
			$ngaynghi++;
		}
	}
	$songay = $songay + $ngaynghi;
	$day = strtotime(date("Y-m-d", strtotime($today)) . " +".$songay." day");
	//return date("Y-m-d", $day);
	return $day();
}

function dksort($array, $case){
    if(array_key_exists($case,$array)){
        $a[$case] = $array[$case];
        foreach($array as $key=>$val){
            if($case==$key){

            }else{
                $a[$key] = $array[$key];
            }
        }
    }
    return $a;
}

function sort_array($arrays, $orderby, $sortby){
	foreach ($arrays as $id => $array) {
		$array_sort[$id]   = $array[$orderby];
	}
	// Sort the data with weight descending, specialties ascending
	// Add $data as the last parameter, to sort by the common key
	$keys = array_keys($arrays);
	array_multisort(
		$array_sort, $sortby, SORT_STRING,
		$arrays, $keys
	);
	$arrays = array_combine($keys, $arrays);
	return $arrays;
}
function sort_array_and_key($arr, $orderby, $sortby){
	$sortArray = array();
	foreach($arr as $k => $a){
	    foreach($a as $key=>$value){
	        if(!isset($sortArray[$key])){
	            $sortArray[$key] = array();
	        }
	        $sortArray[$key][] = $value;
	    }
	}
	array_multisort($sortArray[$orderby],$sortby,$arr);
	return $arr;
}
function br2nl( $input ) {
 return preg_replace('/<br(\s+)?\/?>/i', "\n", $input);
}
function resize_image($file,
                              $string             = null,
                              $width              = 0, 
                              $height             = 0, 
                              $proportional       = false, 
                              $output             = 'file', 
                              $delete_original    = true, 
                              $use_linux_commands = false,
                              $quality            = 100,
                              $grayscale          = false
  		 ) {
      
    if ( $height <= 0 && $width <= 0 ) return false;
    if ( $file === null && $string === null ) return false;

    # Setting defaults and meta
    $info                         = $file !== null ? getimagesize($file) : getimagesizefromstring($string);
    $image                        = '';
    $final_width                  = 0;
    $final_height                 = 0;
    list($width_old, $height_old) = $info;
	$cropHeight = $cropWidth = 0;

    # Calculating proportionality
    if ($proportional) {
      if      ($width  == 0)  $factor = $height/$height_old;
      elseif  ($height == 0)  $factor = $width/$width_old;
      else                    $factor = min( $width / $width_old, $height / $height_old );

      $final_width  = round( $width_old * $factor );
      $final_height = round( $height_old * $factor );
    }
    else {
      $final_width = ( $width <= 0 ) ? $width_old : $width;
      $final_height = ( $height <= 0 ) ? $height_old : $height;
	  $widthX = $width_old / $width;
	  $heightX = $height_old / $height;
	  
	  $x = min($widthX, $heightX);
	  $cropWidth = ($width_old - $width * $x) / 2;
	  $cropHeight = ($height_old - $height * $x) / 2;
    }

    # Loading image to memory according to type
    switch ( $info[2] ) {
      case IMAGETYPE_JPEG:  $file !== null ? $image = imagecreatefromjpeg($file) : $image = imagecreatefromstring($string);  break;
      case IMAGETYPE_GIF:   $file !== null ? $image = imagecreatefromgif($file)  : $image = imagecreatefromstring($string);  break;
      case IMAGETYPE_PNG:   $file !== null ? $image = imagecreatefrompng($file)  : $image = imagecreatefromstring($string);  break;
      default: return false;
    }
    
    # Making the image grayscale, if needed
    if ($grayscale) {
      imagefilter($image, IMG_FILTER_GRAYSCALE);
    }    
    
    # This is the resizing/resampling/transparency-preserving magic
    $image_resized = imagecreatetruecolor( $final_width, $final_height );
    if ( ($info[2] == IMAGETYPE_GIF) || ($info[2] == IMAGETYPE_PNG) ) {
      $transparency = imagecolortransparent($image);
      $palletsize = imagecolorstotal($image);

      if ($transparency >= 0 && $transparency < $palletsize) {
        $transparent_color  = imagecolorsforindex($image, $transparency);
        $transparency       = imagecolorallocate($image_resized, $transparent_color['red'], $transparent_color['green'], $transparent_color['blue']);
        imagefill($image_resized, 0, 0, $transparency);
        imagecolortransparent($image_resized, $transparency);
      }
      elseif ($info[2] == IMAGETYPE_PNG) {
        imagealphablending($image_resized, false);
        $color = imagecolorallocatealpha($image_resized, 0, 0, 0, 127);
        imagefill($image_resized, 0, 0, $color);
        imagesavealpha($image_resized, true);
      }
    }
    imagecopyresampled($image_resized, $image, 0, 0, $cropWidth, $cropHeight, $final_width, $final_height, $width_old - 2 * $cropWidth, $height_old - 2 * $cropHeight);
	
	
    # Taking care of original, if needed
    if ( $delete_original ) {
      if ( $use_linux_commands ) exec('rm '.$file);
      else @unlink($file);
    }

    # Preparing a method of providing result
    switch ( strtolower($output) ) {
      case 'browser':
        $mime = image_type_to_mime_type($info[2]);
        header("Content-type: $mime");
        $output = NULL;
      break;
      case 'file':
        $output = $file;
      break;
      case 'return':
        return $image_resized;
      break;
      default:
      break;
    }
    
    # Writing image according to type to the output destination and image quality
    switch ( $info[2] ) {
      case IMAGETYPE_GIF:   imagegif($image_resized, $output);    break;
      case IMAGETYPE_JPEG:  imagejpeg($image_resized, $output, $quality);   break;
      case IMAGETYPE_PNG:
        $quality = 9 - (int)((0.9*$quality)/10.0);
        imagepng($image_resized, $output, $quality);
        break;
      default: return false;
    }

    return true;
  }
?>