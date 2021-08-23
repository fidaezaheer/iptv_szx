<?php
	include 'common.php';
	$sql = new DbSql();
	$sql->login();

	$value = "";
	if(isset($_GET["accountnum"]))
		$value = $_GET["accountnum"];
	
	$sselect = 0;
	if(isset($_GET["sselect"]))
		$sselect = intval($_GET["sselect"]);
			
	$mydb = $sql->get_database();
	$mytable = "version_table";
	$sql->connect_database_default();
	$sql->create_database($mydb);
	$sql->create_table($mydb, $mytable, "name longtext, value longtext");
	
	$row = $sql->get_row($mydb,$mytable,"name","accountnum");
	if(count($row) >= 1)
	{
		if(strlen($value) > 1)
			$sql->update_data_2($mydb, $mytable, "name", "accountnum", "value", md5(md5($value)));
		else
			$sql->update_data_2($mydb, $mytable, "name", "accountnum", "value", "");
	}
	else
	{
		$sql->insert_data($mydb, $mytable,"name,value","accountnum".", ".md5(md5($value)));	
	}
	
	$row = $sql->get_row($mydb,$mytable,"name","accountselect");
	if($sselect == 2)
	{
		if(count($row) >= 1)
		{
			$sql->update_data_2($mydb, $mytable, "name", "accountselect", "value", "2");
		}
		else
		{
			$sql->insert_data($mydb, $mytable,"name,value","accountselect".", "."2");	
		}
	}
	else
	{
		$sql->update_data_2($mydb, $mytable, "name", "accountselect", "value", "");	
	}
	
	$sql->disconnect_database();
	header("Location: account_list.php");
?>