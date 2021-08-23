<?php

	
	include 'common.php';
	$sql = new DbSql();
	$sql->login();
	
	$serverid = $_GET["serverid"];
	
	$mytable = "stream_server_list_table";
	$sql->connect_database_default();
	$mydb = $sql->get_database();
	$sql->create_database($mydb);
	$sql->create_table($mydb, $mytable, "serverid int, serverip text, tip text, os text, version int");
	
	$serverip = $sql->query_data($mydb, $mytable, "serverid", $serverid, "serverip");
	$sql->delete_data($mydb, $mytable, "serverid", $serverid);
	
	
	$mytable = "stream_channel_list_table";
	$sql->create_table($mydb, $mytable, "urlid int, name text, url text, online int, cache int, 
										path text, tabel text, serverip text, url1 text, url2 text, 
										url3 text, tip text, status text, receive text");
	
	$sql->delete_data($mydb, $mytable,"serverip", $serverip);
										
	$sql->disconnect_database();
	
	header("Location: stream_server_list.php");
	
?>