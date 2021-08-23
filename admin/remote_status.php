<?php
	include_once "cn_lang.php";
	include_once "common.php";

	set_time_limit(0);  

	$sql = new DbSql();
	$sql->login();
	
	$status = $_GET["status"];
	
	echo $mac;
	echo $cpuid;
	echo $controlurl;
	
	$sql->connect_database_default();
	$mydb = $sql->get_database();
	$sql->create_database($mydb);


	$mytable = "version_table";
	$sql->create_table($mydb, $mytable, "name longtext, value longtext");

	$row = $sql->get_row($mydb,$mytable,"name","control");
	if(count($row) >= 1)
		$sql->update_data_2($mydb, $mytable, "name", "control", "value", $status);
	else
		$sql->insert_data($mydb, $mytable,"name,value","control".", ".$status);
		
	$sql->disconnect_database();
	
	header("Location: remote_list.php");

?>