<?php
	include 'common.php';
	$sql = new DbSql();
	$sql->login();
	
	$val = $_GET["key"];
	$val1 = $_GET["key1"];
	$tmp = "tmpid";
	
	$mytable = "live_preview_table";
	
	$sql->connect_database_default();
	$mydb = $sql->get_database();
	$sql->create_database($mydb);
	$sql->create_table($mydb, $mytable, "name longtext, image longtext, url longtext, password longtext, type longtext, preview longtext, id longtext, urlid longtext, hd longtext");
	
	$sql->update_data($mydb, $mytable, "urlid", $val, $tmp);
	$sql->update_data($mydb, $mytable, "urlid", $val1, $val);
	$sql->update_data($mydb, $mytable, "urlid", $tmp, $val1);
	
	$sql->disconnect_database();
	
	header("Location: live_preview_list.php");
?>