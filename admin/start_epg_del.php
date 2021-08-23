<?php

	include 'common.php';
	$sql = new DbSql();
	$sql->login();
	
	$mytable = "start_epg_table";
	$sql->connect_database_default();
	$mydb = $sql->get_database();
	$sql->create_database($mydb);
	$sql->create_table($mydb, $mytable, "tag text, value text");
	
	$sql->delete_data($mydb, $mytable, "tag", "background");
	
	$sql->disconnect_database();	

	header("Location: start_epg_background.php");
?>