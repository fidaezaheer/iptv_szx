<?php 
	include 'common.php';
	$sql = new DbSql();
	$sql->login();

	$urlid = $_GET["urlid"];
	$serverip = $_GET["serverip"];
	$name = $_GET["name"];
	$path = $_GET["path"];
	
	/*
	$sql->connect_database_default();
	$mydb = $sql->get_database();
	$sql->create_database($mydb);
	$mytable = "stream_channel_list_table";
	$sql->create_table($mydb, $mytable, "urlid int, name text, url text, online int, cache int, 
										path text, tabel text, serverip text, url1 text, url2 text, 
										url3 text, tip text, status text, receive text");
										
	//$serverip = $sql->query_data($mydb, $mytable, "urlid", $urlid, "serverip");
	$path = $sql->query_data_2($mydb, $mytable, "urlid", $urlid, "serverip", $serverip, "path");
	$name = $sql->query_data_2($mydb, $mytable, "urlid", $urlid, "serverip", $serverip, "name");
	*/
	
	$cmd = "status#" . $path . "|" . $name;
	$xml = send($serverip,$cmd);
	
	if(strstr($xml,"<?xml") != false)	
	{
		saveXML("xml/",$serverip."_".$name."_".$path."_status.xml",$xml);
	}
	
	/*	
	if(strstr($xml,"<?xml") != false)	
	{
		$dom = new domDocument;
		$dom->loadXML($xml); 
		$statusstores = $dom->getElementsByTagName("status"); 
		foreach($statusstores as $statusstore)
		{
			$run = $statusstore->getElementsByTagName("run")->item(0)->nodeValue;
			$receive = $statusstore->getElementsByTagName("receive")->item(0)->nodeValue;
			
			
			$elements = $statusstore->getElementsByTagName("online");
			if(count($elements) > 0)
			{
				$online = $elements->item(0)->nodeValue;
			}
		}
	}
	*/
	
	//$sql->disconnect_database();
	
	header("Location: stream_channel_list.php?control=1&serverip=" . $serverip);
								
?>