<?php

	include 'common.php';
	$sql = new DbSql();
	$sql->login();
	
	$pw = $_GET["ps"];

	$sql->connect_database_default();
	$mydb = $sql->get_database();
	$sql->create_database($mydb);

	$mytable = "playback_type_table";
	$sql->create_table($mydb, $mytable, "name text, id text ,need int, typepassword text, param0 text, param1 text, param2 text, param3 text");
	$sql->update_data_2($mydb, $mytable, "need", 1 , "typepassword", $pw);

	$sql->disconnect_database();
	
	header("Location: playback_type_list.php");
?>