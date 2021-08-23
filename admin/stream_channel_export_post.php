<?php
	include_once 'common.php';
	include_once "cn_lang.php";
	
	$sql = new DbSql();
	$sql->login();
	
	$serverip = $_POST["serverip"];
	$type = $_POST["export"];
	
	$mytable = "stream_server_list_table";
	$sql->connect_database_default();
	$mydb = $sql->get_database();
	$sql->create_database($mydb);
	$sql->create_table($mydb, $mytable, "serverid int, serverip text, tip text, os text, version int, info text, online int, channelcount int");
	$tip = $sql->query_data($mydb, $mytable, "serverip", $serverip, "tip");
	
	$cmd = "getplayxml";
	$ret = send($serverip,$cmd);
	//echo $ret;
	
	if(strstr($ret,"?xml") != false && strstr($ret,"version=") != false)
	{
		if($type == "xml")
		{
			header("Content-type:application/octet-stream");
			header("Content-Disposition:attachment;filename=play.xml");
			echo $ret;
		}
		else if($type == "txt")
		{
			
			header("Content-type:application/octet-stream");
			header("Content-Disposition:attachment;filename=live-" . $tip . ".txt");
			
			/*
			$sql->connect_database_default();
			$mydb = $sql->get_database();
			$sql->create_database($mydb);
	
			$mytable = "stream_channel_list_table";
			$sql->create_table($mydb, $mytable, "urlid int, name text, url text, online int, cache int, 
										path text, tabel text, serverip text, url1 text, url2 text, 
										url3 text, tip text, status text, receive text");	

			$namess = $sql->fetch_datas_where($mydb, $mytable, "serverip", $serverip);	
														
			$sql->disconnect_database();
			*/
			
			
			$doc = new DOMDocument();
			$doc->loadXML($ret);
			$liveurls = $doc->getElementsByTagName("channel");
			echo "\r\n";
			foreach($liveurls as $liveurl)
			{
				$name = $liveurl->getElementsByTagName("name")->item(0)->nodeValue;
				$url = $liveurl->getElementsByTagName("url")->item(0)->nodeValue;
				$tabel = $liveurl->getElementsByTagName("tabel")->item(0)->nodeValue;
				
				$tables = urldecode($tabel);
				$tabless = explode("@#@",$tables);
											
				$channel = $name;
				if(strlen($tabless[0])>0)
				{
					$channel = $tabless[0];
				}
				
				$gurl = "gp2p://" . $serverip . ":19350/?day=0&distribution=" . $name;
				
				echo $channel . "," . $gurl . "\r\n";
			}
			echo "\r\n";
		
		}
		else if($type == "source")
		{
			
			header("Content-type:application/octet-stream");
			header("Content-Disposition:attachment;filename=source.txt");
			
			/*
			$sql->connect_database_default();
			$mydb = $sql->get_database();
			$sql->create_database($mydb);
	
			$mytable = "stream_channel_list_table";
			$sql->create_table($mydb, $mytable, "urlid int, name text, url text, online int, cache int, 
										path text, tabel text, serverip text, url1 text, url2 text, 
										url3 text, tip text, status text, receive text");	

			$namess = $sql->fetch_datas_where($mydb, $mytable, "serverip", $serverip);	
														
			$sql->disconnect_database();
			*/
			
			
			$doc = new DOMDocument();
			$doc->loadXML($ret);
			$liveurls = $doc->getElementsByTagName("channel");
			echo "\r\n";
			foreach($liveurls as $liveurl)
			{
				$name = $liveurl->getElementsByTagName("name")->item(0)->nodeValue;
				$url = $liveurl->getElementsByTagName("url")->item(0)->nodeValue;
				$tabel = $liveurl->getElementsByTagName("tabel")->item(0)->nodeValue;
				
				$tables = urldecode($tabel);
				$tabless = explode("@#@",$tables);
											
				$channel = $name;
				if(strlen($tabless[0])>0)
				{
					$channel = $tabless[0];
				}
				
				$gurl = $url;
				
				echo $channel . "," . $gurl . "\r\n";
			}
			echo "\r\n";
		
		}
		//header("Location: stream_channel_export_list.php?success=1");
	}
	else
	{
		header("Location: stream_channel_export_list.php?success=0&serverip=" . $serverip);	
	}
?>