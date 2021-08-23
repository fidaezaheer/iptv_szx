<?php
	include_once 'common.php';
	$sql = new DbSql();
	$sql->login();
	
	$serverid = intval($_GET["serverid"]);

	$sql->connect_database_default();
	$mydb = $sql->get_database();
	$sql->create_database($mydb);
	$mytable = "stream_server_list_table";
	$sql->create_table($mydb, $mytable, "serverid int, serverip text, tip text, os text, version int");
	$serverip = $sql->query_data($mydb, $mytable, "serverid", $serverid, "serverip");
	
	$cmd = "updateprogress";
		
	$ret = send($serverip,$cmd);
	
	$sql->disconnect_database();
	
	header("Location: stream_update_list.php");
?>