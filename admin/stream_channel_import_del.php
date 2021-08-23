<?php

	include_once "cn_lang.php";
	include_once "common.php";
	$sql = new DbSql();
	$sql->login();
	
	$urlid = $_GET["urlid"];
	
	$sql->connect_database_default();
	$mydb = $sql->get_database();
	$sql->create_database($mydb);
	
	$mytable = "stream_channel_batch_import_tmp_table";
	$sql->create_table($mydb, $mytable, "urlid int, name text, url text, online int, cache int, 
										path text, tabel text, serverip text, url1 text, url2 text, 
										url3 text, tip text, status text, receive text");	

	$sql->delete_data($mydb, $mytable, "urlid", $urlid);
	
	$sql->disconnect_database();

	header("Location: stream_channel_import_list.php");
?>