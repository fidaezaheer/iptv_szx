<?php
/*
$w = 80; 
$h = 26;
$str = Array();
$string = "123456789";
for($i = 0;$i < 4;$i++){
$str[$i] = $string[rand(0,8)];
$vcode .= $str[$i];
}
session_start();
setcookie("vcode",$vcode);
$im = imagecreatetruecolor($w,$h);
$white = imagecolorallocate($im,255,255,255);
$black = imagecolorallocate($im,0,0,0);
imagefilledrectangle($im,0,0,$w,$h,$white);
imagerectangle($im,0,0,$w-1,$h-1,$black);
for($i = 1;$i < 200;$i++){
	$x = mt_rand(1,$w-9);
	$y = mt_rand(1,$h-9);
	$color = imagecolorallocate($im,mt_rand(200,255),mt_rand(200,255),mt_rand(200,255));
	imagechar($im,1,$x,$y,"*",$color);
}
for($i = 0;$i < count($str);$i++){
	$x = 13 + $i * ($w - 15)/4;
	$y = mt_rand(3,$h / 3);
	$color = imagecolorallocate($im,mt_rand(0,225),mt_rand(0,150),mt_rand(0,225));
	imagechar($im,5,$x,$y,$str[$i],$color);
}
ob_clean();
header("Content-type:image/PNG");
imagePNG($im);
imagedestroy($im);
*/

include_once "common.php";
if(check_ip() == false)
{
	header('HTTP/1.1 403 Forbidden');  
    echo "Access forbidden";  
    exit;  
}

session_start();	

srand((double)microtime()*1000000);

$im = imagecreate(80,26) or die("Cant's initialize new GD image stream!"); 
$red = ImageColorAllocate($im, 255,0,0);
$white = ImageColorAllocate($im, 255,255,255);
$gray = ImageColorAllocate($im, 200,200,200);
imagefill($im,0,0,$white);

$vcode = "";
$authnum = "";

$ychar="1,2,3,4,5,6,7,8,9";
$list=explode(",",$ychar);
for($i=0;$i<4;$i++){
  $randnum=rand(0,8);
  $authnum.=$list[$randnum]." ";
  $vcode.=$list[$randnum];
}

//while(($authnum=rand()%100000)<10000);
setcookie("vcode",$vcode);

imagestring($im, 8, 10, 3, $authnum, $red);

for($i=0;$i<400;$i++){
$randcolor = ImageColorallocate($im,rand(0,255),rand(0,255),rand(0,255));
// imagesetpixel($im, rand()%90 , rand()%30 , $randcolor);
imagesetpixel($im, rand()%90 , rand()%30 , $gray);
} //¼ÓÈë¸É”_ÏñËØ

ob_clean();
Header("Content-type: image/PNG");
ImagePNG($im);
ImageDestroy($im); 
?>
