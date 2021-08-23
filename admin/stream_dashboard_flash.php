<?php

function getIP()
{
	return $_SERVER["REMOTE_ADDR"];
}

	include_once "cn_lang.php";
	include_once "common.php";
	$sql = new DbSql();
	if(getIP() != "127.0.0.1")
	{
		$sql->login();	
	}
	
	$sql->connect_database_default();
	$mydb = $sql->get_database();
	$sql->create_database($mydb);
	
	$mytable = "stream_server_list_table";
	$sql->create_table($mydb, $mytable, "serverid int, serverip text, tip text, os text, version int, info text, online int, channelcount int");	
	$serverss = $sql->fetch_datas($mydb, $mytable);

	$mytable = "stream_dashboard_table";
	$sql->create_table($mydb, $mytable, "id text, name text, serverip text, run text, receive int, online text, onlinedate datetime, dir text, current text");
	
	$date = date("Y-m-d H:i:s");
	

	$sql->delete_date_little($mydb, $mytable, "date", date('Y-m-d H:i:s',strtotime('-1 day'))); 
	
	$servers_total_online = 0;
	foreach($serverss as $servers)
	{
			
		$cmd = "getdirinfo";
		$serverip = $servers[1];
		
		$ret = send($serverip,$cmd);
		
		echo $ret;
		$total_online = 0;
		$current = "";
		
		if(strstr($ret,"?xml") != false && strstr($ret,"version=") != false)
		{
			saveXML("xml/","getdirinfo.xml",$ret);
			
			$doc = new DOMDocument();
			$doc->loadXML($ret);
			$channels = $doc->getElementsByTagName("status");
			$index = 0;
			foreach($channels as $channel){
				$dirname = "";
				$dirreceive = "";
				$dironline = "";
				$dirpath = "";
				
				
				if($channel->getElementsByTagName("name") != false)
					$dirname = $channel->getElementsByTagName("name")->item(0)->nodeValue;
					
				if($channel->getElementsByTagName("receive") != false)
					$dirreceive = $channel->getElementsByTagName("receive")->item(0)->nodeValue;
					
				if($channel->getElementsByTagName("online") != false)
					$dironline = $channel->getElementsByTagName("online")->item(0)->nodeValue;
					
				if($channel->getElementsByTagName("dir") != false)	
					$dirpath = $channel->getElementsByTagName("dir")->item(0)->nodeValue;

				if($channel->getElementsByTagName("current") != false)	
					$current = $channel->getElementsByTagName("current")->item(0)->nodeValue;
					
				$total_online = $total_online + intval($dironline);
				
				if(strlen($dirname)>=1 && strlen($dirpath)>4)
					$sql->insert_data($mydb, $mytable, "id, name, serverip, run, receive, online, onlinedate, dir, current",  md5($dirpath.$dirname).", ".$dirname.", ".$serverip.", "."1".", ".$dirreceive.", ".$dironline.", ".$date.", ".$dirpath.", ".$current);
			}
		}
		
		
		$servers_total_online = $servers_total_online + $total_online;
		
		$sql->insert_data($mydb, $mytable, "id, name, serverip, run, receive, online, onlinedate, dir, current",  "totalonline".", "."totalonline".", ".$serverip.", "."1".", "."0".", ".$total_online.", ".$date.", "."".", ".$current);
	}
	

	$sql->insert_data($mydb, $mytable, "id, name, serverip, run, receive, online, onlinedate, dir, current",  "serverstotalonline".", "."serverstotalonline".", "."".", "."1".", "."0".", ".$servers_total_online.", ".$date.", "."".", ".$current);
		
	$sql->disconnect_database();
?>