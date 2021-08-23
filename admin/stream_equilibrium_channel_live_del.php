<?php
	include_once 'common.php';
	$sql = new DbSql();
	$sql->login();
	
	$urlid = $_GET["urlid"];
	$serverip = $_GET["serverip"];

	$cmd = "syncstop#" . $urlid;
	$ret = send($serverip,$cmd);
	if($ret != "ReceiveCmdSuccessful")
	{
		header("Location: stream_equilibrium_channel_live_list.php?control=" . $ret . "&serverip=" . $serverip);
		return false;	
	}
	else
	{
		echo "receive ok";
	}	
	
	$cmd = "syncdel#" . $urlid;
	$ret = send($serverip,$cmd);
	if($ret != "ReceiveCmdSuccessful")
	{
		header("Location: stream_channel_live_list.php?control=" . $ret . "&serverip=" . $serverip);
		return false;	
	}
	else
	{
		echo "receive ok";
	}
	
	header("Location: stream_equilibrium_channel_live_list.php?serverip=" . $serverip);
	
?>