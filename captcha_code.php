<?php

//$random_alpha = md5(rand());
//$captcha_code = substr($random_alpha, 0, 3);
//$_SESSION["captcha_code"] = $captcha_code;
$captcha_code = isset($_GET['captcha_code']) ? $_GET['captcha_code'] : '';
$target_layer = imagecreatetruecolor(50,36);
$captcha_background = imagecolorallocate($target_layer, 255, 160, 119);
imagefill($target_layer,0,0,$captcha_background);
$captcha_text_color = imagecolorallocate($target_layer, 0, 0, 0);
imagestring($target_layer, 10, 10, 10, $captcha_code, $captcha_text_color);
header("Content-type: image/jpeg");
imagejpeg($target_layer);

?>