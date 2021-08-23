<?php

	include 'common.php';
	$sql = new DbSql();
	$sql->login();	
	
	$state = intval($_GET["state"]);
	$mac = $_GET["mac"];
	$cpuid = $_GET["cpuid"];
	
	$mytable = "authenticate_table_stop";
	$sql->connect_database_default();
	$mydb = $sql->get_database();
	$sql->create_database($mydb);
	$sql->create_table($mydb, $mytable, "mac text, cpuid text, state int");
	
	if($sql->query_data_2($mydb, $mytable, "mac", $mac, "cpuid", $cpuid, "state") == null)
	{
		$sql->insert_data($mydb, $mytable, "mac, cpuid, state", $mac.", ".$cpuid.", ".$state);
	}
	else
	{
		$sql->update_data_3($mydb, $mytable, "mac", $mac, "cpuid", $cpuid, "state", $state);	
	}

	$sql->disconnect_database();
	
	header("Location: authenticate_list.php?mac=".$mac."&cpuid=".$cpuid);


?>