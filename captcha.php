<?php

/*
session_start();
//生成验证码图片
header("Content-type: image/png");
// 全数字
$str = "1,2,3,4,5,6,7,8,9";      //要显示的字符，可自己进行增删
$list = explode(",", $str);
$cmax = count($list) - 1;
$verifyCode = '';
for ( $i=0; $i < 4; $i++ ){
      $randnum = mt_rand(0, $cmax);
      $verifyCode .= $list[$randnum];           //取出字符，组合成为我们要的验证码字符
}
setcookie("vcode",$verifyCode);       //将字符放入SESSION中
  
$im = imagecreate(512,128);    //生成图片
$black = imagecolorallocate($im, 0,0,0);     //此条及以下三条为设置的颜色
$white = imagecolorallocate($im, 255,255,255);
$gray = imagecolorallocate($im, 200,200,200);
$red = imagecolorallocate($im, 255, 0, 0);
imagefill($im,0,0,$white);     //给图片填充颜色
  
//将验证码绘入图片
imagettftext ($im, 65, 0, 150, 100, $black, "kablam!_.dat", $verifyCode);    //将验证码写入到图片中
  

imagepng($im);
imagedestroy($im);

*/

include_once "admin/common.php";
$w = 512; //璁剧疆鍥剧墖瀹藉拰楂?
$h = 128;
$str = Array(); //鐢ㄦ潵瀛樺偍闅忔満鐮?
$string = "123456789";//闅忔満鎸戦€夊叾涓?涓瓧绗︼紝涔熷彲浠ラ€夋嫨鏇村锛屾敞鎰忓惊鐜殑鏃跺€欏姞涓婏紝瀹藉害閫傚綋璋冩暣
$vcode = "";
for($i = 0;$i < 4;$i++){
	$str[$i] = $string[rand(0,8)];
	$vcode .= $str[$i];
}
session_start(); //鍚敤瓒呭叏灞€鍙橀噺session
//$_SESSION["vcode"] = $vcode;
setcookie("vcode",$vcode);
$im = imagecreatetruecolor($w,$h);
$white = imagecolorallocate($im,255,255,255); //绗竴娆¤皟鐢ㄨ缃儗鏅壊
$black = imagecolorallocate($im,0,0,0); //杈规棰滆壊
imagefilledrectangle($im,0,0,$w,$h,$white); //鐢讳竴鐭╁舰濉厖
imagerectangle($im,0,0,$w-1,$h-1,$black); //鐢讳竴鐭╁舰妗?
//鐢熸垚闆姳鑳屾櫙
for($i = 1;$i < 200;$i++){
	$x = mt_rand(1,$w-9);
	$y = mt_rand(1,$h-9);
	$color = imagecolorallocate($im,mt_rand(200,255),mt_rand(200,255),mt_rand(200,255));
	imagechar($im,1,$x,$y,"*",$color);
}
//灏嗛獙璇佺爜鍐欏叆鍥炬

$black = imagecolorallocate($im, 0,0,0);
if(get_server_os() == "WINNT")
{
	imagettftext ($im, 65, 0, 150, 100, $black, "kablam!_.dat", $vcode);
}
else
{
	imagettftext ($im, 65, 0, 150, 100, $black, "./kablam!_.dat", $vcode);	
}

ob_clean();
header("Content-type:image/PNG"); //浠peg鏍煎紡杈撳嚭锛屾敞鎰忎笂闈笉鑳借緭鍑轰换浣曞瓧绗︼紝鍚﹀垯鍑洪敊
imagePNG($im);
imagedestroy($im);
?>
