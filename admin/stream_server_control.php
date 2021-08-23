<?php
	include_once 'connect.php';
	include_once 'gemini.php';
	include_once 'common.php';


	$sql = new DbSql();
	$sql->login();

	$serverid = $_GET["serverid"];
	$control = $_GET["control"];

	$mytable = "stream_server_list_table";
	$sql->connect_database_default();
	$mydb = $sql->get_database();
	$sql->create_database($mydb);
	$sql->create_table($mydb, $mytable, "serverid int, serverip text, tip text, os text, version int");
	$serverip = $sql->query_data($mydb, $mytable, "serverid", $serverid, "serverip");
	
	$cmd = "";
	if($control == "runall")
		$cmd = "run_all";
	else if($control == "stopall")
		$cmd = "stop_all";
	else if($control == "reboot")
		$cmd = "reboot";
		
	$ret = send($serverip,$cmd);
	
	$sql->disconnect_database();
	
	echo $ret;
	
	//header("Location: stream_server_list.php?control=" . $ret);

?>