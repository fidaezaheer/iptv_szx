<?php
	include_once "cn_lang.php";
	include_once "common.php";
	$sql = new DbSql();
	$sql->login();

	$serverid = intval($_POST["serverid"]);
	$serverip = $_POST["serverip"];
	$tip = $_POST["tip"];
	
	$sql->connect_database_default();
	$mydb = $sql->get_database();
	$sql->create_database($mydb);
	//$sql->create_table($mydb, $mytable, "urlid int, name text, url text, online int, cache int, path text, tabel text, serverip text, url1 text, url2 text, url3 text");
	
	$mytable = "stream_distribute_server_list_table";
	$sql->create_table($mydb, $mytable, "serverid int, serverip text, tip text, os text, version int, info text, online int, seekcount int, seekid longtext");		
				
	$names = $sql->fetch_datas_where($mydb, $mytable, "serverid", $serverid);
	
	$version = 0;
	$online = 0;
	$seekcount = 0;
	if(count($names) > 0)
	{
		//$sql->query_data($mydb, $mytable,"name",$name,""
		$sql->update_data_2($mydb, $mytable,"serverid",$serverid,"serverip",$serverip);	
		$sql->update_data_2($mydb, $mytable,"serverid",$serverid,"tip",$tip);
	}
	else
	{
		//$sql->insert_data($mydb, $mytable, "name, password, namemd5, passwordmd5, needmd5", $name . ", " . "" . ", ". "" . ", " . md5($ps) . ", " . $needmd5);
		$sql->insert_data($mydb, $mytable, "serverid, serverip, tip, os, version, info, online, seekcount, seekid", $serverid . ", " . $serverip . ", ". $tip . ", " . $os . ", " . $version . ", " . "" . ", " . $online . ", " . $seekcount . ", " . "");
	}
	
	$sql->disconnect_database();
	
	header("Location: stream_distribute_server_list.php");
?>