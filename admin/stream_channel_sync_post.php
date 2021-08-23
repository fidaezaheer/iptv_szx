<?php
	include_once 'common.php';
	$sql = new DbSql();
	$sql->login();
	
	$serverip = $_POST["serverip"];

	$cmd = "getplayxml";
	$ret = send($serverip,$cmd);
	//echo $ret;
	
	if(strstr($ret,"?xml") != false && strstr($ret,"version=") != false)
	{
		
		$sql->connect_database_default();
		$mydb = $sql->get_database();
		$sql->create_database($mydb);
		$mytable = "stream_channel_list_table";
		$sql->create_table($mydb, $mytable, "urlid int, name text, url text, online int, cache int, 
										path text, tabel text, serverip text, url1 text, url2 text, 
										url3 text, tip text, status text, receive text");
		$dnamess = $sql->fetch_datas_where($mydb, $mytable, "serverip", $serverip);
		$sql->delete_data($mydb, $mytable, "serverip", $serverip);
	
		$online = 0;
		$url1 = "";
		$url2 = "";
		$url3 = "";
		$tip = "";
		$status = "";
		$receive = "";
		
		$doc = new DOMDocument();
		$doc->loadXML($ret);
		$channels = $doc->getElementsByTagName("channel");
		foreach($channels as $channel){
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
			else if(strstr(strtolower($dir),"/tmp/gemini/gserver/") != false)
				$pcache = "5";		
				
			foreach($dnamess as $dnames)
			{
				//echo $dnames[1] . "<br/>";
				if($name == $dnames[1])
				{
					$tip = $dnames[11];
					break;	
				}
			}	
			
			/*	
			$count = $sql->count_fetch_datas_where_2($mydb, $mytable, "urlid", $urlid, "serverip", $serverip);
			if($count > 0)
			{
				
				
				$sql->update_data_2($mydb, $mytable, "urlid", $urlid, "name", $name);
				$sql->update_data_2($mydb, $mytable, "urlid", $urlid, "url", $url);
				$sql->update_data_2($mydb, $mytable, "urlid", $urlid, "path", $pcache);
				$sql->update_data_2($mydb, $mytable, "urlid", $urlid, "cache", $rtime);
				//$sql->update_data_2($mydb, $mytable, "urlid", $urlid, "disable", $disable);
				$sql->update_data_2($mydb, $mytable, "urlid", $urlid, "tabel", $tabel);
			}
			else
			*/
			
			{
				echo "insert_data<br/>";
				$sql->insert_data($mydb, $mytable, "urlid, name, url, online, cache, path, tabel, serverip, url1, url2, url3, tip, status, receive", intval($urlid) . ", " . $name . ", " . $url . ", ". $online . ", " . intval($rtime) . ", " . $pcache . ", " . $tabel . ", " . $serverip .  ", " . $url1 . ", " . $url2 . ", " . $url3 . ", " . $tip .  ", " . $status . ", " . $receive);
			}
		} 
		
		$sql->disconnect_database();
		header("Location: stream_channel_sync_list.php?success=1");
	}
	else
	{
		header("Location: stream_channel_sync_list.php?success=0");	
	}
	
	
	/*
	$sql->connect_database_default();
	$mydb = $sql->get_database();
	$sql->create_database($mydb);
	$mytable = "stream_channel_list_table";
	$sql->create_table($mydb, $mytable, "urlid int, name text, url text, online int, cache int, 
										path text, tabel text, serverip text, url1 text, url2 text, 
										url3 text, tip text, status text, receive text");
	$namess = $sql->fetch_datas_where($mydb, $mytable, "serverip", $serverip);
	foreach($namess as $names)
	{
		
		$new_url = str_replace($url0,$url1,$names[2]);
		$sql->update_data_2($mydb, $mytable,"serverip",$serverip,"url",$new_url);
		$sql->update_data_2($mydb, $mytable,"serverip",$serverip,"path",$pcache);	
		$sql->update_data_2($mydb, $mytable,"serverip",$serverip,"cache",$rtime);		
	}
	
	$sql->disconnect_database();

	echo "<script type='text/javascript'>";
	echo "window.opener.location.reload();";  
	echo "window.close();";
	echo "</script>";
	*/
?>