<?php

	include 'common.php';
	$sql = new DbSql();
	$sql->login();
	
	$pw = $_GET["ps"];

	$sql->connect_database_default();
	$mydb = $sql->get_database();
	$sql->create_database($mydb);

	$mytable = "live_type_table2";
	$sql->create_table($mydb, $mytable, "id text, need int, typepassword text, param0 text, param1 text, param2 text, param3 text");
	$sql->update_data_2($mydb, $mytable, "need", 1 , "typepassword", $pw);

	$sql->disconnect_database();
	
	header("Location: live_type_list.php");
	
	/*
	if($name != "")
		$sql->insert_data($mydb, $mytable, "name, id", $name.", ".$addr);
	$sql->disconnect_database();
  
	header("Location: live_preview_list.php");
	*/
?>