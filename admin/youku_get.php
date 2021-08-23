<?php
$url = $_GET["url"];
if(strstr($url,"tudou") != false)
{
	//header("Location: tudou_get.php?purl=" . $_GET["url"]);
	$file=file_get_contents($_GET["url"]);
	
	$ffile=strstr($file,"iid:");
	$pos = strpos($ffile,",");
	$iid=substr($ffile,strlen("iid:"),$pos-strlen("iid:"));
		
	$m3u8="";
	$ffile=strstr($file,",segs: '");
	$pos = strpos($ffile,"}'");
	$segs=substr($ffile,strlen(",segs: '"),$pos-strlen(",segs: '")+1);
	//echo $segs;
	$segs = str_replace("'","",$segs);
	$js_segs = json_decode($segs,true);
	//print_r($js_segs["2"]["0"]["baseUrl"]);
	if(isset($js_segs["5"]))
	{
		$m3u8 = ".m3u8";
		$st = "5";
	}
	else if(isset($js_segs["10"]))
	{
		$m3u8 = ".m3u8";
		$st = "10";
	}
	else if(isset($js_segs["99"]))
	{
		$m3u8 = ".m3u8";
		$st = "99";
	}
	else if(isset($js_segs["4"]))
	{
		$m3u8 = ".m3u8";
		$st = "4";
	}
	else if(isset($js_segs["3"]))
	{
		$m3u8 = "";
		$st = "3";
	}
	else if(isset($js_segs["2"]))
	{
		$m3u8 = ".m3u8";
		$st = "2";
	}
	else if(isset($js_segs["1"]))
	{
		$m3u8 = ".m3u8";
		$st = "1";
	}
	else
	{
		$m3u8 = "";
		$st = "52";
	}

	$tudou_url = "http://vr.tudou.com/v2proxy/v2" . $m3u8 . "?it=" . trim($iid) . "&st=" . trim($st) . "&pw=";	
	echo trim($tudou_url);
	return;
}
else if(strstr($url,"baidu") != false)
{
	header("Location: baidu_get.php?purl=" . $_GET["url"]);
	return;
}
else if(strstr($url,"youku") != false)
{
	$key = "";
	$id_=strpos($url,"id_");	
	$html=strpos($url,".html?");
	$id = substr($url,$id_+3,$html-$id_-3);
	$json=file_get_contents("http://139.129.25.160:12135/api/i.php?url=" . $id . "_youku&token=" . $key . "&ctype=m3u8&hd=3");
	$ajson = json_decode($json);
	echo file_get_contents($ajson->m);
	return;
}
?>