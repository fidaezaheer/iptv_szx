<?php
	include_once "common.php";
	$sql = new DbSql();
	$sql->login();
	
	set_time_limit(1800);

	$serverip = $_GET["serverip"];	
	
	if(intval($_GET["itype"]) == 0)
	{
		$cmd = "playdelall";
		$ret = send($serverip,$cmd);
		echo $ret;
	}
	
?>