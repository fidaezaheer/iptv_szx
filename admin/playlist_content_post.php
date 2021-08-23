<?php
	include 'common.php';
	$sql = new DbSql();
	$sql->login();

	$name = $_POST["name"];
	$space = $_POST["space"];
	$id = $_GET["id"];
	
	$sql->connect_database_default();
	$mydb = $sql->get_database();
	$sql->create_database($mydb);
			
	$mytable = "playlist_type_table";
	$sql->create_table($mydb, $mytable, "name text, space longtext, id text, playlist longtext");

	$sql->update_data_2($mydb, $mytable, "id", $id, "space", $space);
	$sql->update_data_2($mydb, $mytable, "id", $id, "name", $name);
	
	$sql->disconnect_database();
	
	header("Location: playlist_list.php");
?>