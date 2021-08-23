<?php
	include 'common.php';
	$sql = new DbSql();
	$sql->login();
	
	$txt = $_POST["txt_subtitle"];
	$txt_invalid = $_POST["txt_invalid"];
	$add_mac = strval($_POST["mac_checkbox"]);
	$add_cpuid = strval($_POST["cpuid_checkbox"]);
	
	$mytable = "scroll_table";
	$sql->connect_database_default();
	$mydb = $sql->get_database();
	$sql->create_database($mydb);
	$sql->create_table($mydb, $mytable, "name longtext, value longtext");
	//$sql->delete_data($mydb, $mytable, "name", "scroll_text");
	
	$row = $sql->get_row($mydb,$mytable,"name","scroll_text");
	if(count($row) > 0)
	{
		$sql->update_data_2($mydb, $mytable, "name", "scroll_text", "value", $txt);
	}
	else
	{
		$sql->insert_data($mydb, $mytable, "name, value", "scroll_text, ".$txt);
	}
	
	$row = $sql->get_row($mydb,$mytable,"name","txt_invalid");
	if(count($row) > 0)
	{
		$sql->update_data_2($mydb, $mytable, "name", "txt_invalid", "value", $txt_invalid);
	}
	else
	{
		$sql->insert_data($mydb, $mytable, "name, value", "txt_invalid, ".$txt_invalid);
	}
	
	
	$row = $sql->get_row($mydb,$mytable,"name","add_mac");
	if(count($row) > 0)
	{
		$sql->update_data_2($mydb, $mytable, "name", "add_mac", "value", $add_mac);
	}
	else
	{
		$sql->insert_data($mydb, $mytable, "name, value", "add_mac, ".$add_mac);
	}
	
	$row = $sql->get_row($mydb,$mytable,"name","add_cpuid");
	if(count($row) > 0)
	{
		$sql->update_data_2($mydb, $mytable, "name", "add_cpuid", "value", $add_cpuid);
	}
	else
	{
		$sql->insert_data($mydb, $mytable, "name, value", "add_cpuid, ".$add_cpuid);
	}
	
	$sql->disconnect_database();
	
	header("Location: scroll_edit.php");
?>
