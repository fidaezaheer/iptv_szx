<?php

	include_once 'common.php';
	$sql = new DbSql();
	$sql->login();
	
	$serverip = $_GET["serverip"];
	$equip = $_GET["equ"];
	$cmd = $_GET["cmd"];
	$name = $_GET["name"];
	$urlid = $_GET["urlid"];
	
	if($cmd == 1)
	{
		$url = "gp2p://" . $serverip . ":19350/?day=0&distribution=" . $name;
		$cmd = "syncedit#" . $urlid . "|" . $name . "|" . urlencode($url) . "|" . "250" . "|" . "5";
		$ret = send($equip,$cmd);
		if($ret != "ReceiveCmdSuccessful")
		{
			echo "receive fail";
		}
		else
		{
			$cmd = "syncrun#" . $urlid;
			$ret = send($equip,$cmd);
			if($ret != "ReceiveCmdSuccessful")
			{
				echo "receive fail";
			}
			else
				echo $ret;
		}
	}
	else
	{
		$cmd = "syncdel#" . $urlid;
		$ret = send($equip,$cmd);
		if($ret != "ReceiveCmdSuccessful")
		{
			echo "receive fail";
		}
		else
		{
			$cmd = "syncstop#" . $urlid;
			$ret = send($equip,$cmd);
			if($ret != "ReceiveCmdSuccessful")
			{
				echo "receive fail";
			}
			else
				echo $ret;
		}
	}
?>