<?php
	include 'common.php';
	$sql = new DbSql();
	$sql->login();
	
	$versionCode = 0;
	$versionName = "";
	$addr="";
	$forceupdate="0";
	$updateTime = date("Y-m-d H:m:s",time());
	
	if(isset($_POST["versionCode"]))
		$versionCode = $_POST["versionCode"];
		
	if(isset($_POST["versionName"]))
		$versionName = $_POST["versionName"];
		
	if(isset($_POST["radio_update_model"]))
		$forceupdate = $_POST["radio_update_model"];	
		
	if(isset($_POST["message"]))
		$message = $_POST["message"];
			
	if(isset($_POST["fileSize"]))
		$fileSize = $_POST["fileSize"];
		
	if(isset($_POST["filemd5"]))
		$filemd5 = $_POST["filemd5"];
		
	if(isset($_POST["updatefield"]))
		$addr = $_POST["updatefield"];
		
	echo $forceupdate;
	$mydb = $sql->get_database();
	$mytable = "version_table";
	$sql->connect_database_default();
	$sql->create_database($mydb);
	$sql->create_table($mydb, $mytable, "name longtext, value longtext");
	
	$row = $sql->get_row($mydb,$mytable,"name","versionCode");
	if(count($row) >= 1)
		$sql->update_data_2($mydb, $mytable, "name", "versionCode", "value", $versionCode);
	else
		$sql->insert_data($mydb, $mytable,"name,value","versionCode".", ".$versionCode);

	$row = $sql->get_row($mydb,$mytable,"name","apkUrl");
	if(count($row) >= 1)
		$sql->update_data_2($mydb, $mytable, "name", "apkUrl", "value", $addr);
	else
		$sql->insert_data($mydb, $mytable,"name,value","apkUrl".", ".$addr);
		

	$row = $sql->get_row($mydb,$mytable,"name","versionName");
	if(count($row) >= 1)
		$sql->update_data_2($mydb, $mytable, "name", "versionName", "value", $versionName);
	else
		$sql->insert_data($mydb, $mytable,"name,value","versionName".", ".$versionName);
		
	$row = $sql->get_row($mydb,$mytable,"name","message");
	if(count($row) >= 1)
		$sql->update_data_2($mydb, $mytable, "name", "message", "value", $message);
	else
		$sql->insert_data($mydb, $mytable,"name,value","message".", ".$message);
		
	$row = $sql->get_row($mydb,$mytable,"name","fileSize");
	if(count($row) >= 1)
		$sql->update_data_2($mydb, $mytable, "name", "fileSize", "value", $fileSize);
	else
		$sql->insert_data($mydb, $mytable,"name,value","fileSize".", ".$fileSize);
		
	$row = $sql->get_row($mydb,$mytable,"name","forceupdate");
	if(count($row) >= 1)
		$sql->update_data_2($mydb, $mytable, "name", "forceupdate", "value", $forceupdate);
	else
		$sql->insert_data($mydb, $mytable,"name,value","forceupdate".", ".$forceupdate);
		
	$row = $sql->get_row($mydb,$mytable,"name","filemd5");
	if(count($row) >= 1)
		$sql->update_data_2($mydb, $mytable, "name", "filemd5", "value", strtolower($filemd5));
	else
		$sql->insert_data($mydb, $mytable,"name,value","filemd5".", ".strtolower($filemd5));
		
	$row = $sql->get_row($mydb,$mytable,"name","updateTime");
	if(count($row) >= 1)
		$sql->update_data_2($mydb, $mytable, "name", "updateTime", "value", $updateTime);
	else
		$sql->insert_data($mydb, $mytable,"name,value","updateTime".", ".$updateTime);
	
	$sql->disconnect_database();
	
	header("Location: version_1.php");
?>