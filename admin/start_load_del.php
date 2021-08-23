<?php

	include 'common.php';
	$sql = new DbSql();
	$sql->login();

	$value = $_GET["value"];
	
	$mytable = "start_load_table";
	$sql->connect_database_default();
	$mydb = $sql->get_database();
	$sql->create_database($mydb);
	//$sql->delete_table($mydb, $mytable);
	$sql->delete_data($mydb,$mytable,"tag",$value);
	
	$sql->disconnect_database();	

	header("Location: background_list.php");
?>