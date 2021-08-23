<?php

function createFolder($path)
{
   	mkdir($path,0777,true);
}

 
	include_once 'common.php';
	include_once 'FileUtil.php';
	$sql = new DbSql();
	$sql->login();
	
	set_time_limit(0);
	if(strlen($_POST["files"]) < 3)
	{
		header("Location: update_list.php");
		exit;
	}

	$files = explode("|",$_POST["files"]);
	$server = $_SERVER['SERVER_NAME'];
	
	$date = date("Y-m-d-H-i-s");
	
	for($ii=0; $ii<count($files); $ii++)
	{
		$s = file_get_contents("http://www.gemini-iptv.com/update_get.php?area=" . $server . "&file=" . $files[$ii]);
		$s1_start = strripos($s,"geministart");
		$s = substr($s,$s1_start+strlen("geministart"));
		$pmd5_start = strripos($s,"<!--");
		$pmd5_start = $pmd5_start+4;
		$pmd5_end = strripos($s,"-->");
		$md5_v = substr($s,$pmd5_start,$pmd5_end-$pmd5_start);
		$s = substr($s,0,$pmd5_start-4);
		$md5_c = md5($s);

		echo $md5_v . "#";
		echo $md5_c;
		
		if($md5_v != $md5_c)
		{
			continue;
		}
		
		if(strcmp(dirname($files[$ii]),"admin") == 0)
		{
			$copy_target_path = "backup/" . $date . "/admin/";
			$filer = new FileUtil();
			$filer->createDir($copy_target_path);
			rename(basename($files[$ii]),$copy_target_path.basename($files[$ii]));
		}
		else
		{
			$copy_target_path = "backup/" . $date . "/";
			$filer = new FileUtil();
			$filer->createDir($copy_target_path);
			rename("../".$files[$ii],$copy_target_path.$files[$ii]);
		}
		
		
		if(strcmp(dirname($files[$ii]),"admin") == 0)
		{
			if(strstr($s,"hello(__FILE__,") != false)
				file_put_contents(basename($files[$ii]),$s."<!--PlrbTII=-->");
			else
				file_put_contents(basename($files[$ii]),$s);
			//$fpp1 = fopen(basename($files[$ii]), 'w');
			//fwrite($fpp1, $s) or die('写文件错误');
			//fclose($fpp1);
		}
		else
		{
			if(strstr($s,"hello(__FILE__,") != false)
				file_put_contents("../".$files[$ii],$s."<!--PlrbTII=-->");
			else
				file_put_contents("../".$files[$ii],$s);
			//$fpp1 = fopen("../".$files[$ii], 'w');
			//fwrite($fpp1, $s) or die('写文件错误');
			//fclose($fpp1);
		}
	}

	header("Location: update_list.php");
?>