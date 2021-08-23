<?php
	include_once 'common.php';
	$sql = new DbSql();
	$sql->login_proxy();
	
	set_zone();
	
	$value = base64_decode($_GET["value"]);
	$cpuid = $_GET["cpuid"];
	$mac = $_GET["mac"];
	
	
	$sql->connect_database_default();
	$mydb = $sql->get_database();
	$sql->create_database($mydb);

	$mytable = "custom_table";
	$sql->create_table($mydb, $mytable, 
		"mac text,cpu text,ip text,space text, date text,
		time text,allow text, playlist text, online text, allocation text,
		proxy text, balance float,showtime text,contact text,member text,
		panal text,number text,ips text, onescrolltext text, onescrolltexttimes int, 
		numberdate int, scrollcontent text, scrolldate text, scrolltimes text, controlurl text, 		
		controltime int, unbundling int, accessdate timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP, mv text, isupdate int,
		remarks text, startime date, model text, remotelogin int, limitmodel text");
		
	$sql->update_data_4($mydb, $mytable, "cpu", $cpuid, "mac", $mac, "proxy", $_COOKIE["user"], "limitmodel", $value);
		
	$sql->disconnect_database();
	
	header("Location: proxy_custom_edit.php?mac=" . $mac . "&cpuid=" . $cpuid . "");
?>