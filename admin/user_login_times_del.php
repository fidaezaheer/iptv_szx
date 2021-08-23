<?php
	include_once 'common.php';
	$sql = new DbSql();
	$sql->login();
	
	$mac = $_GET["mac"];
	$cpuid = $_GET["cpuid"];
		
	$sql->connect_database_default();
	$mydb = $sql->get_database();
	$sql->create_database($mydb);
	
	$mytable = "custom_tree_table";
	$sql->create_table($mydb, $mytable, "mac text,cpuid text,date text,timers int");
	
	$sql->update_data_3($mydb, $mytable, "mac", $mac, "cpuid", $cpuid, "timers", 0);
	
	header("Location:user_login_times.php");
?>