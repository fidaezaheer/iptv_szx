<?php
	include 'common.php';
	$sql = new DbSql();
	$sql->login();
	
	$id = $_GET["id"];
	
	$mytable = "cj_list_table";
	$sql->connect_database_default();
	$mydb = $sql->get_database();
	$sql->create_database($mydb);
	$sql->create_table($mydb, $mytable, "id int, name text, url text, appid text, appkey text");
	$sql->delete_data($mydb, $mytable, "id", $id);
	$sql->disconnect_database();
	header("Location: cj_list.php");
	
?>