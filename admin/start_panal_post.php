<?php
	include 'common.php';
	$sql = new DbSql();
	$sql->login();

	$value = $_GET["id"];
	$tag = "panal";

	$mytable = "start_panal_table";
	$sql->connect_database_default();
	$mydb = $sql->get_database();
	$sql->create_database($mydb);
	$sql->delete_table($mydb, $mytable);
	$sql->create_table($mydb, $mytable, "tag longtext, value longtext");
	$sql->insert_data($mydb, $mytable, "tag, value",$tag.", ".$value);

	$sql->disconnect_database();
	
	header("Location: start_set.php");

?>