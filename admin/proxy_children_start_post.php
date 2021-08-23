<?php
	include_once 'common.php';
	$sql = new DbSql();
	$sql->login();
	
	$level = 2;
	
	$sql->connect_database_default();
	$mydb = $sql->get_database();
	$sql->create_database($mydb);
	
	$mytable = "proxy_download_table";
	$sql->create_table($mydb, $mytable, "name text, sbackground text, sepg text, ebackground text, livepanel text, vodpanel text, download text, allow text, watermark text, scrolltext text, version int");
	$sql->update_data_2($mydb, $mytable,"name",$_GET["proxy"],"sepg",$_GET["id"]);

	$sql->disconnect_database();

	header("Location: proxy_children_start.php?proxy=" . $_GET["proxy"] . "&level=2");
?>