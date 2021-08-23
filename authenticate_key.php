<?php
	include_once 'admindir.php';
	$a = new Adminer();
	include_once $a->ad . 'common.php';
	$sql = new DbSql();
	
	$version = 88;
	$key = "";
	if(isset($_GET["version"]) && isset($_GET["key"]))
	{
		$version = intval($_GET["version"]);
		$key = $sql->str_safe($_GET["key"]);	
	}
	check_key_out($version,$key);
	
	$setstate = $sql->str_safe($_GET["state"]);
	$mac = $sql->str_safe($_GET["mac"]);
	$cpuid = $sql->str_safe($_GET["cpuid"]);
	
	$mytable = "authenticate_table";
	$sql->connect_database_default();
	$mydb = $sql->get_database();
	$sql->create_database($mydb);
	$sql->create_table($mydb, $mytable, "mac text, cpuid text, akey text, state text, time text");
	
	$state = $sql->query_data_2($mydb, $mytable, "mac", $mac, "cpuid", $cpuid, "state");
	if($state == null)
	{
		$sql->insert_data($mydb, $mytable, "mac, cpuid, akey, state, time", $mac.", ".$cpuid.", "."0".", ".$setstate.", "."0");
	}
	else
	{
		$sql->update_data_3($mydb, $mytable, "mac", $mac, "cpuid", $cpuid, "state", $setstate);
	}
	
	$sql->disconnect_database();

?>