<?php
	include_once "cn_lang.php";
	include_once "common.php";

	$sql = new DbSql();
	$sql->login_proxy();

	set_time_limit(0);
	
	$mac = null;
	if(isset($_GET["mac"]))
		$mac = $_GET["mac"];
		
	$cpuid = null;
	if(isset($_GET["cpuid"]))
		$cpuid = $_GET["cpuid"];	

	$controlurl = "";
	if(isset($_GET["url"]))
		$controlurl = $_GET["url"];
	
	echo $mac;
	echo $cpuid;
	echo $controlurl;
	
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
		remarks text, startime date, model text, remotelogin int, limitmodel text, 
		modelerror int, limittimes int, limitarea text, ghost int, password text, 
		evernumber longtext, prekey text");
		
	if($mac != null && $cpuid != null)
		$sql->update_data_3($mydb, $mytable, "mac", $mac, "cpu", $cpuid, "controlurl", $controlurl);
	else
		$sql->update_data_0($mydb, $mytable,"controlurl", $controlurl);
	
	$sql->disconnect_database();
	
	header("Location: proxy_remote_list.php");

?>