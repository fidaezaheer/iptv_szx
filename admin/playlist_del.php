<?php
	include 'common.php';
	$sql = new DbSql();
	$sql->login();
	$val = $_GET["id"];
	
	$sql->connect_database_default();
	$mydb = $sql->get_database();
	$sql->create_database($mydb);
	$mytable = "playlist_type_table";
	$sql->create_table($mydb, $mytable, "name text, space longtext, id text, playlist longtext");
	
	$sql->delete_data($mydb, $mytable, "id", $val);
	
	$sql->disconnect_database();
	
	header("Location: playlist_list.php");
?>