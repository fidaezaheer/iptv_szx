<?php
	include_once 'common.php';
	include_once "cn_lang.php";
	set_time_limit(1800);
	$sql = new DbSql();
	$sql->login();

	$sql->connect_database_default();
	$mydb = $sql->get_database();
	$sql->create_database($mydb);
	
	$type = $_GET["type"];
	$day = intval($_GET["day"]);
	
	$name = array();
	$mytable = "account_table";
	$sql->create_table($mydb, $mytable, "id int, playlist text, days text, proxy text, export int, used int, mac text, cpuid text, cdkey text, showtime text, type text");
	
	if($type == "yes")
		$namess = $sql->fetch_datas_where($mydb, $mytable, "used", 1); 
	else
	{
		$namess = $sql->fetch_datas_where_little($mydb, $mytable, "used", 1, "days", $day);
	}
	
	$del_cdkeys = "";
	foreach($namess as $names) 
	{
		$del_cdkeys = $del_cdkeys . $names[8]. "|";
	}
	
	if($type == "yes")
		$namess = $sql->delete_data($mydb, $mytable, "used", 1); 
	else
	{
		$namess = $sql->delete_data_2_less($mydb, $mytable, "used", 1, "days", $day);
	}
	//$sql->delete_data($mydb, $mytable, "used", 1);
	
	$content =  $lang_array["account_del_text1"] . ":" . $del_cdkeys;
	$date = date("Y-m-d H:i:s");
	$mytable = "log_record_table";
	$sql->create_table($mydb, $mytable, "date datetime, user text, mac text, cpuid text, content text, other text, ip text");	
	$sql->insert_data($mydb, $mytable, "date, user, mac, cpuid, content, other, ip", $date.", ".$_COOKIE["user"].", "."".", "."".", ".$content.", "."null".", "."");
	
	$sql->disconnect_database();
	
	//header("Location: account_list.php?page=". $_GET["page"]);
	echo "<script type='text/javascript'>";
	echo "window.opener.location.reload();";  
	echo "window.close();";
	echo "</script>";
?>