<?php
	include_once 'admindir.php';
	
	$a = new Adminer();
	$addir = $a->ad;
	include_once $addir . 'common.php';
	include_once 'gemini.php';
	include_once 'cn_lang.php';


	if(!isset($_GET["mac"]) || !isset($_GET["cpuid"]) || !isset($_GET["key"]))
		echo "FAIL";
		
	$mac = $_GET["mac"];
	$cpuid = $_GET["cpuid"];
	$key = $_GET["key"];

	$sql = new DbSql();
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
		evernumber longtext, prekey text, cpuinfo text, contactkey text");
		
	$contactkey = $sql->query_data_2($mydb, $mytable,"mac",$mac,"cpu",$cpuid,"contactkey");	
	
	$sql->disconnect_database();	

	if(md5($key) == $contactkey)
		echo "SUCCESS";
	else
		echo "FAIL";
?>