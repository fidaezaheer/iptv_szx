<?php
	include 'common.php';
	$sql = new DbSql();
	$sql->login();

	$mytable = "ad_live_list_table";
	$sql->connect_database_default();
	$mydb = $sql->get_database();
	$sql->create_database($mydb);
	$sql->create_table($mydb, $mytable, "id int, image text");
	$sql->delete_data($mydb, $mytable,"id",$_GET["id"]);
	$sql->disconnect_database();
	
	header("Location: update_ad_live_list.php");

?>