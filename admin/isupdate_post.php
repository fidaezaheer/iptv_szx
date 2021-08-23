<?php

	include_once 'common.php';

	set_zone();
	
	$sql = new DbSql();
	$sql->login();
	$sql->connect_database_default();
	$mydb = $sql->get_database();
	$sql->create_database($mydb);
	
	$value = intval($_GET["value"]);
	$cpuid = $_GET["cpuid"];
	$mac = $_GET["mac"];
	
	$mytable = "custom_table";
	$sql->create_table($mydb, $mytable, 
	"mac text,cpu text,ip text,space text, date text,
	time text,allow text, playlist text, online text, allocation text,
	proxy text, balance float,showtime text,contact text,member text,
	panal text,number text,ips text, onescrolltext text, onescrolltexttimes int, 
	numberdate int, scrollcontent text, scrolldate text, scrolltimes text, controlurl text, 		
	controltime int, unbundling int, accessdate timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP, mv text, isupdate int");
	
	$sql->update_data_3($mydb, $mytable, "cpu", $cpuid, "mac", $mac, "isupdate", $value);
	
	$sql->disconnect_database();
	header("Location: authenticate_list.php?cpuid=" . $cpuid . "&mac=" . $mac);
?>