<?php
	include 'common.php';
	$sql = new DbSql();
	$sql->login();
	
	set_time_limit(1800);
	
	$mytable = "custom_table";
	$sql->connect_database_default();
	$mydb = $sql->get_database();
	$sql->create_database($mydb);
	$sql->create_table($mydb, $mytable, "mac text,cpu text,ip text,space text, date text,time text,allow text, playlist text, online text, allocation text, proxy text, balance float,showtime text,contact text,member text,panal text,number text,ips text");
	$sql->delete_data_3($mydb, $mytable, "allow", "no", "date", "null", "time", "null");
	$sql->disconnect_database();
	header("Location: custom_batch_list.php");
	
?>