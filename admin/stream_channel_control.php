<?php
	include_once 'connect.php';
	include_once 'gemini.php';
	include_once 'common.php';

	$sql = new DbSql();
	$sql->login();

	$urlid = intval($_GET["urlid"]);
	$control = $_GET["control"];
	
	/*
	$sql->connect_database_default();
	$mydb = $sql->get_database();
	$sql->create_database($mydb);
	$mytable = "stream_channel_list_table";
	$sql->create_table($mydb, $mytable, "urlid int, name text, url text, online int, cache int, path text, tabel text, serverip text, url1 text, url2 text, url3 text, tip text");
	$serverip = $sql->query_data($mydb, $mytable, "urlid", $urlid, "serverip");
	*/
	$serverip = $_GET["serverip"];
	
	
	$cmd = "getplayxml";
	$ret = send($serverip,$cmd);
	
	$curlid = "";
	$name = "";
	$url = "";
	$dir = "";
	$path = "";
	$cache = "";
	$disable = "";
	$tabel = "";
	$equilibrium = "";
	if(strstr($ret,"?xml") != false && strstr($ret,"version=") != false)
	{
		$doc = new DOMDocument();
		$doc->loadXML($ret);
		$channels = $doc->getElementsByTagName("channel");
		foreach($channels as $channel){
			$curlid = $channel->getElementsByTagName("id")->item(0)->nodeValue;
			if($curlid == $urlid)
			{
				$name = $channel->getElementsByTagName("name")->item(0)->nodeValue;
				$url = $channel->getElementsByTagName("url")->item(0)->nodeValue;
				$dir = $channel->getElementsByTagName("dir")->item(0)->nodeValue;
				$cache = $channel->getElementsByTagName("time")->item(0)->nodeValue;
				$disable = $channel->getElementsByTagName("disable")->item(0)->nodeValue;
				$tabel = $channel->getElementsByTagName("tabel")->item(0)->nodeValue;
				$equilibrium = $channel->getElementsByTagName("equilibrium")->item(0)->nodeValue;
				$acc = $channel->getElementsByTagName("acc")->item(0)->nodeValue;
				break;
			}
		}
	}
	
	
	
	$cmd = "";
	if($control == "run")
	{
		$disable = "0";
		$cmd = "run#" . $urlid;
	}
	else if($control == "stop")
	{	
		$disable = "1";
		$cmd = "stop#" . $urlid;
	}
	
	$ret = send($serverip,$cmd);
	if($ret != "ReceiveCmdSuccessful")
	{
		//header("Location: stream_channel_live_list.php?control=1&serverip=" . $serverip);
		echo "fail";
	}
	else
	{
		$cmd = "syncrun#" . $urlid;
		$ret = send($equilibrium,$cmd);
		if($ret != "ReceiveCmdSuccessful")
		{
			echo "fail";
		}
		else
			echo $ret;	
	}

?>