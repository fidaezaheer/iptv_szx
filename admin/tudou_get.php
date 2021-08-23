<?php
	$file=file_get_contents($_GET["purl"]);
	
	$ffile=strstr($file,"iid:");
	$pos = strpos($ffile,",");
	$iid=substr($ffile,strlen("iid:"),$pos-strlen("iid:"));
	
	/*
	$ffile=strstr($file,"mp4segs: '");
	$pos = strpos($ffile,",");
	$st=substr($ffile,strlen("mp4segs: '"),$pos-strlen("mp4segs: '")-2);
	$st = str_replace("'","",$st);
	if(strlen($st) <= 0)
		$st = "52";
	*/
		
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

	echo "http://vr.tudou.com/v2proxy/v2" . $m3u8 . "?it=" . trim($iid) . "&st=" . trim($st) . "&pw=";
	//echo "http://vr.tudou.com/v2proxy/v2?it=" . trim($iid) . "&st=" . trim($st) . "&pw=";
?>