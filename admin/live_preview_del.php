<?php
	include 'common.php';
	$sql = new DbSql();
	$sql->login();
	
	$val = $_GET["id"];	
	$page = 0;
	if(isset($_GET["page"]))
		$page = $_GET["page"];
			
	$mytable = "live_preview_table";
	$sql->connect_database_default();
	$mydb = $sql->get_database();
	$sql->create_database($mydb);
	$sql->create_table($mydb, $mytable, "name longtext, image longtext, url longtext, password longtext, type longtext, preview longtext, id longtext, urlid smallint, hd longtext");
	
	$sql->delete_data($mydb, $mytable, "urlid", $val);
	
	$sql->disconnect_database();
	
	header("Location: live_preview_list.php?page=" . $page);
?>