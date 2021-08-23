<?php
	include_once 'common.php';
	$sql = new DbSql();
	$sql->login();
	
	include_once 'memcache.php';
	$mem = new GMemCache();
	if($mem->used() && isset($_GET["asynupdate"]))
	{
		$asynupdate = $_GET["asynupdate"];
		$mem->connect();
		$mem->set("asynupdate",$asynupdate);
		$mem->close();
	}
	
	
	header("Location: options_list.php");
?>