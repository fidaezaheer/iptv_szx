<?php
	include_once 'common.php';
	$sql = new DbSql();
	$sql->login();
	
	$serverid = intval($_POST["serverid"]);
	$serverip = $_POST["serverip"];
	$tip = $_POST["tip"];
	$os = "";
	$version = 1;

	$sql->connect_database_default();
	$mydb = $sql->get_database();
	$sql->create_database($mydb);
	$mytable = "stream_equilibrium_server_list_table";
	$sql->create_table($mydb, $mytable, "serverid int, serverip text, tip text, os text, version int");
	$names = $sql->fetch_datas_where($mydb, $mytable, "serverid", $serverid);
	if(count($names) > 0)
	{
		$sql->update_data_2($mydb, $mytable,"serverid",$serverid,"serverip",$serverip);	
		$sql->update_data_2($mydb, $mytable,"serverid",$serverid,"tip",$tip);
	}
	else
	{
		$sql->insert_data($mydb, $mytable, "serverid, serverip, tip, os, version", $serverid . ", " . $serverip . ", ". $tip . ", " . $os . ", " . $version);
	}
	
	$sql->disconnect_database();
	
	header("Location: stream_equilibrium_server_list.php");
?>