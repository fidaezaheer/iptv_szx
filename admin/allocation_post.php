<?php

	include 'common.php';
	$sql = new DbSql();
	$sql->login();
	
	$cn = $_GET["cn"];
	$hk = $_GET["hk"];
	$mo = $_GET["mo"];
	$tw = $_GET["tw"];
	$us = $_GET["us"];
	
	$sql->connect_database_default();
	$mydb = $sql->get_database();
	$mytable = "allocation_table";
	$sql->create_database($mydb);
	$sql->delete_table($mydb, $mytable);
	$sql->create_table($mydb, $mytable, "name text, value text");
	
	$sql->insert_data($mydb, $mytable, "name, value", "cn".", ".$cn); 
	$sql->insert_data($mydb, $mytable, "name, value", "hk".", ".$hk); 
	$sql->insert_data($mydb, $mytable, "name, value", "mo".", ".$mo); 
	$sql->insert_data($mydb, $mytable, "name, value", "tw".", ".$tw); 
	$sql->insert_data($mydb, $mytable, "name, value", "us".", ".$us); 

	$sql->disconnect_database();

	header("Location: playlist_auto_list.php");
?>