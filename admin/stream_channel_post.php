<?php
	include_once 'common.php';
	$sql = new DbSql();
	$sql->login();
	
	$urlid = intval($_POST["urlid"]);
	$tip = $_POST["tip"];
	$name = urlencode($_POST["nname"]);
	$url = $_POST["url"];
	$pcache = $_POST["pcache"];
	$rtime = $_POST["time"];
	$equilibriumss = $_POST["equilibrium"];
	$acc = $_POST["acc"];
	
	$serverip = $_POST["serverip"];
	
	if($acc == "me")
		$acc = $serverip;
	else if($acc == "no")
		$acc = "";
			
	//echo "serverip:" . $serverip;
	
	$online = "";
	$url1 = "";
	$url2 = "";
	$url3 = "";
	$tabel = "";
	$status = "";
	$receive = "";
	
	$cmd = "playedit#" . $urlid . "|" . $name . "|" . $url . "|" . $pcache . "|" . $rtime . "|" . "0" . "|" . urlencode($tip) . "|" . base64_encode($equilibriumss) . "|" . $acc;
	
	$ret = send($serverip,$cmd);
	if($ret != "ReceiveCmdSuccessful")
	{
		header("Location: stream_channel_live_list.php?control=1&serverip=" . $serverip);
		echo "receive fail";	
	}
	else
	{
		echo "receive ok";
	}
	
	header("Location: stream_channel_live_list.php?control=1&serverip=" . $serverip);

?>