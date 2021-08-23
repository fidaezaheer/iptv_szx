<?php

	include_once "cn_lang.php";
	include_once "common.php";
	$sql = new DbSql();
	
	$sql->connect_database_default();
	$mydb = $sql->get_database();
	$sql->create_database($mydb);
	
	$mytable = "stream_dashboard_table";
	$sql->create_table($mydb, $mytable, "id text, name text, serverip text, run text, receive int, online text, onlinedate datetime, dir text, current text");
	
	$sql->clear_datas($mydb, $mytable);
	
	$sql->disconnect_database();
	
	echo "OK"

?>