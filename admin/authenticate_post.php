<?php
	include 'common.php';
	$sql = new DbSql();
	$sql->login();
	
	$key = $_POST["key_id"];
	$mac = $_GET["mac"];
	$cpuid = $_GET["cpuid"];
	
	$mytable = "authenticate_table";
	$sql->connect_database_default();
	$mydb = $sql->get_database();
	$sql->create_database($mydb);
	$sql->create_table($mydb, $mytable, "mac text, cpuid text, akey text, state text, time text");
	
	$v = $sql->query_data_2($mydb, $mytable, "mac", $mac, "cpuid", $cpuid, "akey");
	if($sql->query_data_2($mydb, $mytable, "mac", $mac, "cpuid", $cpuid, "akey") == null)
	{
		$sql->insert_data($mydb, $mytable, "mac, cpuid, akey, state, time", $mac.", ".$cpuid.", ".$key.", "."0".", "."0");
	}
	else
	{
		$sql->update_data_3($mydb, $mytable, "mac", $mac, "cpuid", $cpuid, "akey", $key);	
	}

	$sql->disconnect_database();
	
	header("Location: authenticate_list.php?mac=".$mac."&cpuid=".$cpuid);
?>