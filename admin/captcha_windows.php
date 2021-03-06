<?php

	include_once "common.php";
	if(check_ip() == false)
	{
		header('HTTP/1.1 403 Forbidden');  
        echo "Access forbidden";  
        exit;  
	}
	
	$str = "23456789";
	session_start();
    //生成验证码图片
    Header("Content-type: image/PNG");
    $im = imagecreate(64,38); // 画一张指定宽高的图片
    $back = ImageColorAllocate($im, 245,245,245); // 定义背景颜色
    imagefill($im,0,0,$back); //把背景颜色填充到刚刚画出来的图片中
    $vcodes = "";
    srand((double)microtime()*1000000);
    //生成4位数字
    for($i=0;$i<4;$i++){
    	$font = ImageColorAllocate($im, rand(100,255),rand(0,100),rand(100,255)); // 生成随机颜色
    	$authnum=$str{rand(0, strlen($str) - 1)};
    	$vcodes.=$authnum;
    	imagestring($im, 5, 12+$i*10, 10, $authnum, $font);
    }
	
    //$_SESSION['VCODE'] = $vcodes;
	setcookie("vcode",$vcodes);
	
    for($i=0;$i<200;$i++) //加入干扰象素
    {
    	$randcolor = ImageColorallocate($im,rand(0,255),rand(0,255),rand(0,255));
    	imagesetpixel($im, rand()%70 , rand()%30 , $randcolor); // 画像素点函数
    }
	ob_clean();
    ImagePNG($im);
    ImageDestroy($im);
?>