<?php
	include_once "cn_lang.php";
	include_once "common.php";

	$sql = new DbSql();
	$sql->login_proxy();

	set_time_limit(0);
	
	$macss = null;
	if(isset($_POST["macs"]))
		$macss = $_POST["macs"];
		
	$url = null;
	if(isset($_POST["url"]))
		$url = urldecode($_POST["url"]);	

	$ctr = "";
	if(isset($_POST["ctr"]))
		$ctr = intval($_POST["ctr"]);
	
	//echo $macs;
	//echo $url;
	//echo $ctr;
	
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
		
	$macs = explode("|",$macss);
	foreach($macs as $mac) 
	{
		echo $mac;
		if($ctr == 1)
			$sql->update_data_2($mydb, $mytable, "mac", $mac, "controlurl", $url);
		else
			$sql->update_data_2($mydb, $mytable, "mac", $mac, "controlurl", "");
		
	}
	
	$sql->disconnect_database();
	
	header("Location: proxy_remote_list.php");

?>