<?php
	include 'common.php';
	$sql = new DbSql();
	$sql->login();	
	
	$name = $_POST["textfield2"];
	$addr = uniqid();
	$space = "";
	
	$mytable = "playlist_type_table";
	$sql->connect_database_default();
	$mydb = $sql->get_database();
	$sql->create_database($mydb);
	$sql->create_table($mydb, $mytable, "name text, space longtext, id text, playlist longtext");
	if($name != "")
		$sql->insert_data($mydb, $mytable, "name, space, id", $name.", ".$space.", ".$addr);
	
	$sql->disconnect_database();
	
	header("Location: playlist_list.php");
?>