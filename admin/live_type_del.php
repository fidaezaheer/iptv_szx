<?php

	
	include 'common.php';
	$sql = new DbSql();
	$sql->login();

	$id = $_GET["id"];
	
	$sql->connect_database_default();
	$mydb = $sql->get_database();
	$sql->create_database($mydb);
	
	$mytable = "live_type_table";
	$sql->create_table($mydb, $mytable, "name longtext, id longtext");
	$sql->delete_data($mydb, $mytable, "id", $id);
	
	$mytable = "live_type_table2";
	$sql->create_table($mydb, $mytable, "id text, need int, typepassword text, param0 text, param1 text, param2 text, param3 text");
	$sql->delete_data($mydb, $mytable, "id", $id);
	
	$sql->disconnect_database();
	//$sql->clear_datas($mydb,"vod_table" . $id);
	
	header("Location: live_type_list.php");
	
?>