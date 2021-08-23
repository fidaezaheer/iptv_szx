<?php

	
	include 'common.php';
	$sql = new DbSql();
	$sql->login();
	
	$val = urldecode(base64_decode($_GET["name"]));
	
	//echo $val;
	
	$mytable = "user_table";
	$sql->connect_database_default();
	$mydb = $sql->get_database();
	$sql->create_database($mydb);
	$sql->create_table($mydb, $mytable, "name longtext, password longtext");
	
	$sql->delete_data($mydb, $mytable, "name", $val);
	
	$sql->disconnect_database();
	
	header("Location: user_list.php");
	
?>