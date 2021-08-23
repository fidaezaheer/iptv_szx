<?php
	include_once 'common.php';
	$sql = new DbSql();
	$sql->login();
	
	$dir = $_GET["dir"];
	$serverip = $_GET["serverip"];	
	
	$cmd = "dirdel#" . $dir;
	$ret = send($serverip,$cmd);
	if($ret != "dirdelok")
	{
		if(isset($_GET["servercheck"]) && intval($_GET["servercheck"]) == 1)
			header("Location: stream_channel_list.php?control=" . $ret . "&serverip=" . $serverip);
		else
			header("Location: stream_channel_list.php?control=" . $ret);
		return false;	
	}
	else
	{
		echo "receive ok";
	}
	
	if(isset($_GET["servercheck"]) && intval($_GET["servercheck"]) == 1)
		header("Location: stream_channel_list.php?serverip=" . $serverip);
	else
		header("Location: stream_channel_list.php");
	
?>