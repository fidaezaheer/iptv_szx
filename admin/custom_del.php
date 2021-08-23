<?php

	include_once 'common.php';
	include_once "cn_lang.php";
	
	$sql = new DbSql();
	$sql->login();
	
	$mac = $_GET["name"];
	$cpuid = $_GET["cpuid"];
	
	$page = 0;
	if(isset($_GET["page"]))
		$page = $_GET["page"];
		
	$sql->connect_database_default();
	$mydb = $sql->get_database();
	$sql->create_database($mydb);
	$mytable = "custom_table";
	$sql->create_table($mydb, $mytable, "mac text,cpu text,ip text,space text, date text,time text,allow text, playlist text, online text, allocation text, proxy text, balance float,showtime text,contact text,member text,panal text,number text,ips text");
	
	//if(strcmp($sql->query_data($mydb,$mytable,"cpu", $cpuid, "mac"),$val) == 0)
	{
		if(stristr($cpuid,"GEMINI") != false && strlen($cpuid) > 22)
		{
			$sql->delete_data($mydb, $mytable, "mac", $mac);
		}
		
		if(strlen($cpuid) < 7)
		{
			$sql->delete_data($mydb, $mytable, "cpu", $cpuid);
		}		
		else if(count(explode(":",$mac)) != 6)
		{
			$sql->delete_data($mydb, $mytable, "mac", $mac);
		}
		else if(stristr($cpuid,"and") == false)
			$sql->delete_data_2($mydb, $mytable, "cpu", $cpuid, "mac", $mac);
		else
		{
			$sql->delete_data_2_like($mydb, $mytable, "mac", $mac, "cpu", "and");
			$sql->delete_data_2_like($mydb, $mytable, "mac", $mac, "cpu", "*/#");
		}
	}
	
	$mytable = "custom_download_table";
	$sql->create_table($mydb, $mytable, "mac text,cpu text,tip text,url text,date text,state text,param text");	
	$sql->delete_data_2($mydb, $mytable, "mac", $mac, "cpu", $cpuid);
	
	
	$mytable = "custom_scroll_table";	
	$sql->create_table($mydb, $mytable, "mac text, cpuid text, scroll longtext,times int");
	$sql->delete_data_2($mydb, $mytable, "mac", $mac, "cpuid", $cpuid);
	
	$date = date("Y-m-d H:i:s");
	$mytable = "log_record_table";
	$content = $lang_array["del_text1"] . ":" . $mac;
	$sql->create_table($mydb, $mytable, "date datetime, user text, mac text, cpuid text, content text, other text, ip text");	
	$sql->insert_data($mydb, $mytable, "date, user, mac, cpuid, content, other, ip", $date.", ".$_COOKIE["user"].", "."".", "."".", ".$content.", "."null".", "."");
	
	$sql->disconnect_database();
	header("Location: custom_list.php?page=" . $page);
?>