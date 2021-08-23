<?php
	include_once "cn_lang.php";
	include_once "common.php";
	$sql = new DbSql();
	$sql->login();

	$serverid = $_GET["id"];
	
	$sql->connect_database_default();
	$mydb = $sql->get_database();
	$sql->create_database($mydb);	
	
	$mytable = "stream_distribute_server_list_table";
	$sql->create_table($mydb, $mytable, "serverid int, serverip text, tip text, os text, version int, info text, online int, seekcount int, seekid longtext");

	$sql->delete_data($mydb, $mytable,"serverid", $serverid);
	
	$sql->disconnect_database();
	
	header("Location: stream_distribute_server_list.php");
?>