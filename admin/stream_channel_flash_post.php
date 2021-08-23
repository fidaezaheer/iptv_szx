<?php

	include_once 'common.php';
	$sql = new DbSql();
	$sql->login();
	
	$serverip = $_GET["serverip"];
	$cmd = "getstatusxml";
	$ret = send($serverip,$cmd);
	
	saveXML("xml/", $serverip."_statusall.xml",$ret);
	
	/*
	$sql->connect_database_default();
	$mydb = $sql->get_database();
	$sql->create_database($mydb);
	
	
	$mytable = "stream_server_list_table";
	$sql->create_table($mydb, $mytable, "serverid int, serverip text, tip text, os text, version int");
	$serverss = $sql->fetch_datas($mydb, $mytable);
	
	
	
	foreach($serverss as $servers) 
	{
		$serverip = $servers[1];
		$cmd = "getstatusxml";
		$ret = send($serverip,$cmd);
		
		saveXML("xml/", "statusall.xml",$ret);
		
		if(strstr($ret,"?xml") != false && strstr($ret,"version=") != false)
		{
			$mytable = "stream_channel_list_table";
			$sql->create_table($mydb, $mytable, "urlid int, name text, url text, online int, cache int, 
										path text, tabel text, serverip text, url1 text, url2 text, 
										url3 text, tip text, status text, receive text");
			
			$doc = new DOMDocument();
			$doc->loadXML($ret);
			$channels = $doc->getElementsByTagName("status");
			foreach($channels as $channel){
				$name = $channel->getElementsByTagName("id")->item(0)->nodeValue;
				$run = $channel->getElementsByTagName("run")->item(0)->nodeValue;
				$receive = $channel->getElementsByTagName("receive")->item(0)->nodeValue;
				$online = $channel->getElementsByTagName("online")->item(0)->nodeValue;

				$sql->update_data_3($mydb, $mytable, "name", $name, "serverip", $serverip, "status", $run);
				$sql->update_data_3($mydb, $mytable, "name", $name, "serverip", $serverip, "receive", $receive);
				$sql->update_data_3($mydb, $mytable, "name", $name, "serverip", $serverip, "online", $online);
			} 
		}
	}
	
	$sql->disconnect_database();
	*/
	
	if(isset($_GET["servercheck"]) && intval($_GET["servercheck"]) == 1)
	{
		echo 1;
		header("Location: stream_channel_list.php?success=1&serverip=" . $_GET["serverip"]);
	}
	else	
	{
		echo 2;
		header("Location: stream_channel_list.php?success=1");
	}
?>