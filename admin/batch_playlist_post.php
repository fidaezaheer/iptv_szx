<?php

	include 'common.php';
	$sql = new DbSql();
	$sql->login();
	$sql->connect_database_default();
	$mydb = $sql->get_database();
	$sql->create_database($mydb);
	
	$v1 = $_GET["v1"];
	$v2 = $_GET["v2"];
	
	$mytable = "custom_table";
	$sql->create_table($mydb, $mytable, "mac text,cpu text,ip text,space text, date text,time text,allow text, playlist text, online text, allocation text, proxy text, balance float,showtime text,contact text,member text,panal text,number text,ips text");
	
	$sql->update_data($mydb, $mytable, "playlist", $v1, $v2);

	$sql->disconnect_database();

	header("Location: custom_batch_list.php");
?>