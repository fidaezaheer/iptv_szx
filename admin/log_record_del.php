<?php
	include 'common.php';
	$sql = new DbSql();
	$sql->login();
	
	$sql->connect_database_default();
	$mydb = $sql->get_database();
	
	$mytable = "log_record_table";
	$sql->create_database($mydb);
	$sql->delete_table($mydb, $mytable);
	$sql->create_table($mydb, $mytable, "date datetime, user text, mac text, cpuid text, content text, other text");
	//$sql->delete_date_little($mydb, $mytable, "date", date('Y-m-d H:i:s',strtotime('-1 day'))); 
	
	$date = date("Y-m-d H:i:s");
	$content = "清空日志";
	$sql->insert_data($mydb, $mytable, "date, user, mac, cpuid, content, other", $date.", ".$_COOKIE["user"].", "."".", "."".", ".$content.", "."null");
	$sql->disconnect_database();
	
	header("Location: log_record_list.php");
?>