<?php
	include_once "cn_lang.php";
	include_once "common.php";
	include_once "gemini.php";
	$sql = new DbSql();
	$sql->login();
	
	set_time_limit(1800);
	
	//$num = 0;
	//if(isset($_GET["num"]))
	//	$num = intval($_GET["num"]);
	
	$serverip = $_GET["serverip"];	
	
	$sql->connect_database_default();
	$mydb = $sql->get_database();
	$sql->create_database($mydb);
	
	$mytable = "stream_channel_batch_import_tmp_table";
	$sql->create_table($mydb, $mytable, "urlid int, name text, url text, online int, cache int, 
										path text, tabel text, serverip text, url1 text, url2 text, 
										url3 text, tip text, status text, receive text");		
	$namess = $sql->fetch_datas($mydb, $mytable);
	
	$count = count($namess);
	
	$index = 0;
	
	//if($num < count($namess))
	/*
	if(intval($_GET["itype"]) == 0)
	{
		$cmd = "playdelall";
		$ret = send($serverip,$cmd);
	}
	*/
	/*
	$mytable = "stream_channel_list_table";
	$sql->create_table($mydb, $mytable, "urlid int, name text, url text, online int, cache int, 
									path text, tabel text, serverip text, url1 text, url2 text, 
									url3 text, tip text, status text, receive text");
									
	if(intval($_GET["itype"]) == 0)								
	{
		$sql->delete_table($mydb, $mytable);
	}
	*/
	
	//foreach($namess as $names)
	
	if($count > 0)
	{
		$names = $namess[0];
		$add = rand(1,1024*1024);
		$cmd = "playedit#" . $add . "|" . $names[1] . "|" . trim(str_replace("&#x0A;","",str_replace("&#x0D;","",$names[2]))) . "|" . $names[5] . "|" . $names[4] . "|" . "0" . "|" . urlencode($names[11]);
		$ret = send($serverip,$cmd);
		if($ret != "ReceiveCmdSuccessful")
		{
			//$sql->disconnect_database();
			//header("Location: stream_channel_import_run.php?num=" . $num);
			
			echo $serverip . " fail " .  $names[1]  . "<br/>";
		}
		else
		{
			$mytable = "stream_channel_batch_import_tmp_table";
			$sql->create_table($mydb, $mytable, "urlid int, name text, url text, online int, cache int, 
										path text, tabel text, serverip text, url1 text, url2 text, 
										url3 text, tip text, status text, receive text");	
										
			$sql->delete_data($mydb, $mytable, "urlid", $names[0]);
			
			/*
			$mytable = "stream_channel_list_table";
			$sql->create_table($mydb, $mytable, "urlid int, name text, url text, online int, cache int, 
										path text, tabel text, serverip text, url1 text, url2 text, 
										url3 text, tip text, status text, receive text");
			
			$urlid = $names[0];
			$name = $names[1];
			$url = str_replace("&#x0D;&#x0A;","",$names[2]);
			$rtime = $names[4];
			$pcache = $names[5];
			$tip = $names[11];
			
			$online = "";
			$url1 = "";
			$url2 = "";
			$url3 = "";
			$tabel = "";
			$status = "";
			$receive = "";
	
			$sql->insert_data($mydb, $mytable, "urlid, name, url, online, cache, path, tabel, serverip, url1, url2, url3, tip, status, receive", $urlid . ", " . $name . ", " . $url . ", ". $online . ", " . $rtime . ", " . $pcache . ", " . $tabel . ", " . $serverip .  ", " . $url1 . ", " . $url2 . ", " . $url3 . ", " . $tip .  ", " . $status . ", " . $receive);
			*/
			echo $serverip . " receive ok " .  $count . "<br/>";
		}
	}
	else
	{
		echo "finish";	
	}
	
	$sql->disconnect_database();
	return;
	
	//header("Location: stream_channel_import_run.php?num=" . (intval($num)+1));
?>