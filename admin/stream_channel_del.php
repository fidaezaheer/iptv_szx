<?php
	include_once 'common.php';
	$sql = new DbSql();
	$sql->login();
	
	$urlid = $_GET["urlid"];
	$serverip = $_GET["serverip"];
	$urldir = $_GET["urldir"];
	$equilibriumss = urldecode($_GET["equilibrium"]);
	
	//$serverip = $sql->query_data($mydb, $mytable, "urlid", $urlid, "serverip");
	
	$cmd = "stop#" . $urlid;
	$ret = send($serverip,$cmd);
	if($ret != "ReceiveCmdSuccessful")
	{
		if(isset($_GET["servercheck"]) && intval($_GET["servercheck"]) == 1)
			header("Location: stream_channel_live_list.php?control=" . $ret . "&serverip=" . $serverip);
		else		
			header("Location: stream_channel_live_list.php?control=" . $ret);
		return false;	
	}
	else
	{
		echo "receive ok";
	}	
	
	$cmd = "playdel#" . $urlid;
	$ret = send($serverip,$cmd);
	if($ret != "ReceiveCmdSuccessful")
	{
		if(isset($_GET["servercheck"]) && intval($_GET["servercheck"]) == 1)
			header("Location: stream_channel_live_list.php?control=" . $ret . "&serverip=" . $serverip);
		else
			header("Location: stream_channel_live_list.php?control=" . $ret);
		return false;	
	}
	else
	{
		
		echo "receive ok";
	}

	$equilibriums = explode("|",$equilibriumss);
	foreach($equilibriums as $equilibrium) 
	{
		$cmd = "syncstop#" . $urlid;
		$ret = send($equilibrium,$cmd);
		if($ret != "ReceiveCmdSuccessful")
		{
			echo "receive fail";
		}
		else
		{
			echo $ret;
		}
		
		$cmd = "syncdel#" . $urlid;
		$ret = send($equilibrium,$cmd);
		if($ret != "ReceiveCmdSuccessful")
		{
			echo "receive fail";
		}
		else
		{
			echo $ret;
		}
	}
	
	if(isset($_GET["servercheck"]) && intval($_GET["servercheck"]) == 1)
		header("Location: stream_channel_live_list.php?serverip=" . $serverip);
	else
		header("Location: stream_channel_live_list.php");
	
?>