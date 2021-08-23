<?php
	include_once "cn_lang.php";
	include_once "common.php";
	include_once "gemini.php";
	$sql = new DbSql();
	$sql->login();

	set_time_limit(1800);
	if (($_FILES["file"]["type"] != "text/xml") && ($_FILES["file"]["type"] != "text/plain"))
	{
		header("Location: stream_channel_import_list2.php");
		exit;
	}
	
	if(strlen($_FILES["file"]["name"]) < 4)
	{
		header("Location: stream_channel_import_list2.php");
		return;
	}
	$saveimge = randomkeys(). "." . get_extension($_FILES["file"]["name"]);
	
	if ($_FILES["file"]["error"] > 0)
    {
    	echo "Return Code: " . $_FILES["file"]["error"] . "<br />";
    }
  	else
    {
    	if (file_exists("backup/" . $_FILES["file"]["name"]))
      	{
      		echo $_FILES["file"]["name"] . " already exists. ";
      	}
    	else
      	{
      		move_uploaded_file($_FILES["file"]["tmp_name"],"backup/" . $saveimge);
      		echo "Stored in: " . "backup/" . $saveimge;
      	}
	}
	
	
	$urlid = "";
	$tip = "";
	$name = "";
	$url = "";
	$pcache = "0";
	$rtime = "300";
	$serverip = $_GET["serverip"];
	$online = 0;
	$url1 = "";
	$url2 = "";
	$url3 = "";
	$tabel = "";
	$status = "";
	$receive = "";
	
	$serverip = $_GET["serverip"];
	
	$sql->connect_database_default();
	$mydb = $sql->get_database();
	$sql->create_database($mydb);
	
	$mytable = "stream_channel_batch_import_tmp_table";
	$sql->create_table($mydb, $mytable, "urlid int, name text, url text, online int, cache int, 
										path text, tabel text, serverip text, url1 text, url2 text, 
										url3 text, tip text, status text, receive text");	
	$sql->delete_data_all($mydb, $mytable);
	if(file_exists('backup/' . $saveimge) && get_extension($saveimge) == "xml")
	{
		$doc = new DOMDocument();
		$doc->load('backup/' . $saveimge);
		$channels = $doc->getElementsByTagName("channel");
		foreach($channels as $channel)
		{
			$urlid = $channel->getElementsByTagName("id")->item(0)->nodeValue;
			$name = $channel->getElementsByTagName("name")->item(0)->nodeValue;
			$url = $channel->getElementsByTagName("url")->item(0)->nodeValue;
			$dir = $channel->getElementsByTagName("dir")->item(0)->nodeValue;
			$rtime = $channel->getElementsByTagName("time")->item(0)->nodeValue;
			$disable = $channel->getElementsByTagName("disable")->item(0)->nodeValue;
			$tabel = $channel->getElementsByTagName("tabel")->item(0)->nodeValue;
			
			$pcache = "0";
			if(strstr(strtolower($dir),"c:") != false)
				$pcache = "0";
			else if(strstr(strtolower($dir),"d:") != false)
				$pcache = "1";
			else if(strstr(strtolower($dir),"e:") != false)
				$pcache = "2";
			else if(strstr(strtolower($dir),"f:") != false)
				$pcache = "3";
			else if(strstr(strtolower($dir),"g:") != false)
				$pcache = "4";
			else if(strstr(strtolower($dir),"h:") != false)
				$pcache = "5";
			else if(strstr(strtolower($dir),"gserver0") != false)
				$pcache = "0";
			else if(strstr(strtolower($dir),"gserver1") != false)
				$pcache = "1";
			else if(strstr(strtolower($dir),"gserver2") != false)
				$pcache = "2";
			else if(strstr(strtolower($dir),"gserver3") != false)
				$pcache = "3";
			else if(strstr(strtolower($dir),"gserver4") != false)
				$pcache = "4";		
			else if(strstr(strtolower($dir),"gserver5") != false)
				$pcache = "5";		
				
			$sql->insert_data($mydb, $mytable, "urlid, name, url, online, cache, path, tabel, serverip, url1, url2, url3, tip, status, receive", intval($urlid) . ", " . $name . ", " . $url . ", ". $online . ", " . intval($rtime) . ", " . $pcache . ", " . $tabel . ", " . $serverip .  ", " . $url1 . ", " . $url2 . ", " . $url3 . ", " . $tip .  ", " . $status . ", " . $receive);
		} 		
	}
	else if(file_exists('backup/' . $saveimge) && get_extension($saveimge) == "txt")
	{
		$ii = 0;
		$handle = fopen('backup/' . $saveimge , 'r');
    	while(!feof($handle))
		{
			$l = fgets($handle);
			$ls = explode(",",$l);
			if(count($ls) >= 2)
			{
				
				$ii++;
				$name = rand(1,1024*1024);
				$tip = $ls[0];
				$url = str_replace("&#x0D;&#x0A;","",$ls[1]);
				$pcache = "0";
				$rtime = "300";
				
				$sql->insert_data($mydb, $mytable, "urlid, name, url, online, cache, path, tabel, serverip, url1, url2, url3, tip, status, receive", $ii . ", " . $name . ", " . $url . ", ". $online . ", " . $rtime . ", " . $pcache . ", " . $tabel . ", " . $serverip .  ", " . $url1 . ", " . $url2 . ", " . $url3 . ", " . $tip .  ", " . $status . ", " . $receive);
			}
    	}
    	fclose($handle);
	}	
	
	$sql->disconnect_database();
	
	header("Location: stream_channel_import_list.php?serverip=" . $serverip);
?>