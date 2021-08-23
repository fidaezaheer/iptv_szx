<?php
	include 'common.php';
	$sql = new DbSql();
	$sql->login();

	$id = $_GET["id"];
	
	$mytable = "playback_type_table";
	$sql->connect_database_default();
	$mydb = $sql->get_database();
	$sql->create_database($mydb);
	$sql->create_table($mydb, $mytable, "name text, id text ,need int, typepassword text, param0 text, param1 text, param2 text, param3 text");
	$sql->delete_data($mydb, $mytable, "id", $id);
	$sql->disconnect_database();

	header("Location: playback_type_list.php");
	
?>