<?php
	include_once 'common.php';
	$sql = new DbSql();
	$sql->login();
	
	$serverid = intval($_GET["serverid"]);

	$sql->connect_database_default();
	$mydb = $sql->get_database();
	$sql->create_database($mydb);
	$mytable = "stream_equilibrium_server_list_table";
	$sql->create_table($mydb, $mytable, "serverid int, serverip text, tip text, os text, version int, info text");		
	$serverip = $sql->query_data($mydb, $mytable, "serverid", $serverid, "serverip");
	
	//echo "serverip:" . $serverip;
	
	$cmd = "getinfo";
		
	$ret = send($serverip,$cmd);
	
	$sql->update_data_2($mydb, $mytable,"serverid",$serverid,"info",$ret);	
	
	$sql->disconnect_database();
	
	//echo $ret;
	
	header("Location: stream_equilibrium_server_list.php");
?>