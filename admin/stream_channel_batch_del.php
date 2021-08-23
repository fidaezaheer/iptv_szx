<?php
	include 'common.php';
	$sql = new DbSql();
	$sql->login();
	
	$serverip = $_GET["serverip"];
	
	
	$cmd = "stop_all";
	$ret = send($serverip,$cmd);
	if($ret != "ReceiveCmdSuccessful")
	{	
		header("Location: stream_channel_list.php?control=0&serverip=" . $serverip);
		return false;	
	}
	else
	{
	
		$cmd = "playdelall";
		$ret = send($serverip,$cmd);
		if($ret != "ReceiveCmdSuccessful")
		{	
			header("Location: stream_channel_list.php?control=0&serverip=" . $serverip);
			return false;	
		}
		else
		{
			header("Location: stream_channel_list.php?serverip=" . $serverip);
		}
	}
?>