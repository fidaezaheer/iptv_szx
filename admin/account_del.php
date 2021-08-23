<?php
	include_once 'common.php';
	include_once "cn_lang.php";
	
	$sql = new DbSql();
	$sql->login();
	
	set_time_limit(1800);
	$del_ids = $_GET["del"];
	
	$sql->connect_database_default();
	$mydb = $sql->get_database();
	$sql->create_database($mydb);
	
	$mytable = "account_table";
	$sql->create_table($mydb, $mytable, "id int, playlist text, days text, proxy text, export int, used int, mac text, cpuid text, cdkey text, showtime text, type text");
	$del_cdkeys = "";
	$del_id = explode("|",$del_ids);
	for($ii=0; $ii<count($del_id); $ii++)
	{
		$cdkey = $sql->query_data($mydb, $mytable, "id", $del_id[$ii],"cdkey");
		$sql->delete_data($mydb, $mytable, "id", $del_id[$ii]);
		$del_cdkeys = $del_cdkeys . $cdkey . "|";
	}
	
	$content =  $lang_array["account_del_text1"] . ":" . $del_cdkeys;
	$date = date("Y-m-d H:i:s");
	$mytable = "log_record_table";
	$sql->create_table($mydb, $mytable, "date datetime, user text, mac text, cpuid text, content text, other text, ip text");	
	$sql->insert_data($mydb, $mytable, "date, user, mac, cpuid, content, other, ip", $date.", ".$_COOKIE["user"].", "."".", "."".", ".$content.", "."null".", "."");
	
	$sql->disconnect_database();
	
	header("Location: account_list.php?page=". $_GET["page"]);
?>