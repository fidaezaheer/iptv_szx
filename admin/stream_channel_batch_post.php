<?php
	include_once 'common.php';
	$sql = new DbSql();
	$sql->login();
	
	$serverip = $_POST["serverip"];
	$url0 = $_POST["url0"];
	$url1 = $_POST["url1"];
	$pcache = $_POST["pcache"];
	$rtime = $_POST["time"];

	$cmd = "batchserver#" . $url0 . "|" . $url1 . "|" . $pcache . "|" . $rtime;
	$ret = send($serverip,$cmd);
	if($ret != "ReceiveCmdSuccessful")
	{
		header("Location: stream_channel_batch_list.php?control=0");
		return false;	
	}
	else
	{
		echo "receive ok";
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
		if(strstr($names[2],$url0) != false)
		{
			$new_url = str_replace($url0,$url1,$names[2]);
			$sql->update_data_2($mydb, $mytable,"serverip",$serverip,"url",$new_url);
		}
		
		if($pcache >= 0)	
			$sql->update_data_2($mydb, $mytable,"serverip",$serverip,"path",$pcache);	
			
		if($rtime >= 0)
			$sql->update_data_2($mydb, $mytable,"serverip",$serverip,"cache",$rtime);		
	}
	
	$sql->disconnect_database();
	*/
	
	echo "<script type='text/javascript'>";
	echo "window.opener.location.reload();";  
	echo "window.close();";
	echo "</script>";
?>