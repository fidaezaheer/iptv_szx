<?php
	include 'common.php';
	$sql = new DbSql();
	$sql->login();
	
	$MtvVersion = 0;
	$addr="";
	
	if(isset($_POST["versionCode"]))
		$MtvVersion = $_POST["versionCode"];
		
	if(isset($_POST["updatefield"]))
		$addr = $_POST["updatefield"];
		
	echo $forceupdate;
	$mydb = $sql->get_database();
	$mytable = "version_table";
	$sql->connect_database_default();
	$sql->create_database($mydb);
	$sql->create_table($mydb, $mytable, "name longtext, value longtext");
	
	$row = $sql->get_row($mydb,$mytable,"name","MtvVersion");
	if(count($row) >= 1)
		$sql->update_data_2($mydb, $mytable, "name", "MtvVersion", "value", $MtvVersion);
	else
		$sql->insert_data($mydb, $mytable,"name,value","MtvVersion".", ".$MtvVersion);

	$row = $sql->get_row($mydb,$mytable,"name","MtvUrl");
	if(count($row) >= 1)
		$sql->update_data_2($mydb, $mytable, "name", "MtvUrl", "value", $addr);
	else
		$sql->insert_data($mydb, $mytable,"name,value","MtvUrl".", ".$addr);
		
	$sql->disconnect_database();
	
	header("Location: version_2.php");
?>