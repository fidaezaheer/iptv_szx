<?php
	include 'common.php';
	$sql = new DbSql();
	$sql->login();

	$enable = $_GET["enable"];
	$ssid = $_GET["ssid"];
	$password = $_GET["password"];
	
	$mytable = "version_table";
	$sql->connect_database_default();
	$mydb = $sql->get_database();
	$sql->create_database($mydb);
	$sql->create_table($mydb, $mytable, "name longtext, value longtext");
	
	$ap_enable = $sql->fetch_datas_where($mydb, $mytable, "name", "ap_enable");
	if(count($ap_enable) > 0)
	{
		$sql->update_data_2($mydb, $mytable, "name" , "ap_enable", "value", $enable);
	}
	else
	{
		$sql->insert_data($mydb, $mytable, "name, value", "ap_enable".", ".$enable);
	}

	$ap_ssid = $sql->fetch_datas_where($mydb, $mytable, "name", "ap_ssid");
	if(count($ap_ssid) > 0)
	{
		$sql->update_data_2($mydb, $mytable, "name" , "ap_ssid", "value", $ssid);
	}
	else
	{
		$sql->insert_data($mydb, $mytable, "name, value", "ap_ssid".", ".$ssid);
	}
	
	$ap_password = $sql->fetch_datas_where($mydb, $mytable, "name", "ap_password");
	if(count($ap_password) > 0)
	{
		$sql->update_data_2($mydb, $mytable, "name" , "ap_password", "value", $password);
	}
	else
	{
		$sql->insert_data($mydb, $mytable, "name, value", "ap_password".", ".$password);
	}
	
	$sql->disconnect_database();
	
	header("Location: wifiap_list.php");
?>