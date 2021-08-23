<?php

	include 'common.php';
	$sql = new DbSql();
	$sql->login();
	
	$mac = $_GET["mac"];
	$cpuid = $_GET["cpuid"];
	
	$page = 0;
	if(isset($_GET["page"]))
		$page = $_GET["page"];
		
	$sql->connect_database_default();
	$mydb = $sql->get_database();
	$sql->create_database($mydb);
	$mytable = "custom_table";
	$mytable = "custom_table";
	$sql->create_table($mydb, $mytable, 
		"mac text,cpu text,ip text,space text, date text,
		time text,allow text, playlist text, online text, allocation text,
		proxy text, balance float,showtime text,contact text,member text,
		panal text,number text,ips text, onescrolltext text, onescrolltexttimes int, 
		numberdate int, scrollcontent text, scrolldate text, scrolltimes text, controlurl text, 		
		controltime int, unbundling int, accessdate timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP, mv text, isupdate int,
		remarks text, startime date, model text, remotelogin int, limitmodel text, 
		modelerror int, limittimes int, limitarea text, ghost int");
		
	$sql->update_data_3($mydb, $mytable, "mac", $mac, "cpu", $cpuid, "ghost", 0);
	
	$sql->disconnect_database();
	
	header("Location: custom_list.php?page=" . $page);
?>