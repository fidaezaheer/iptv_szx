<?php
	include_once 'common.php';
	$sql = new DbSql();
	$sql->login();
	
	$serverid = intval($_POST["serverid"]);
	$serverip = trim($_POST["serverip"]);
	$tip = trim($_POST["tip"]);
	$os = "";
	$version = 1;

	$sql->connect_database_default();
	$mydb = $sql->get_database();
	$sql->create_database($mydb);
	$mytable = "stream_server_list_table";
	$sql->create_table($mydb, $mytable, "serverid int, serverip text, tip text, os text, version int");
	$names = $sql->fetch_datas_where($mydb, $mytable, "serverid", $serverid);
	if(count($names) > 0)
	{
		//$sql->query_data($mydb, $mytable,"name",$name,""
		$sql->update_data_2($mydb, $mytable,"serverid",$serverid,"serverip",$serverip);	
		$sql->update_data_2($mydb, $mytable,"serverid",$serverid,"tip",$tip);
	}
	else
	{
		//$sql->insert_data($mydb, $mytable, "name, password, namemd5, passwordmd5, needmd5", $name . ", " . "" . ", ". "" . ", " . md5($ps) . ", " . $needmd5);
		$sql->insert_data($mydb, $mytable, "serverid, serverip, tip, os, version", $serverid . ", " . $serverip . ", ". $tip . ", " . $os . ", " . $version);
	}
	
	$sql->disconnect_database();
	
	header("Location: stream_server_list.php");
?>