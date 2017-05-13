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
?>