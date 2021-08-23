<?php
	include 'common.php';
	$sql = new DbSql();
	$sql->login();
	
	$value = base64_decode($_GET["style"]);
	
	$mytable = "start_epg_table";
	$sql->connect_database_default();
	$mydb = $sql->get_database();
	$sql->create_database($mydb);
	$sql->create_table($mydb, $mytable, "tag text, value text");
	$style = $sql->fetch_datas_where($mydb, $mytable, "tag", "style");
	if(count($style) <= 0)
	{
		$sql->insert_data($mydb, $mytable, "tag, value", "style".", ".$value);
	}
	else
	{
		$sql->update_data_2($mydb, $mytable, "tag", "style", "value", $value);
	}
	
	$sql->disconnect_database();
	
	//header("Location: start_epg_style.php");
	echo "OK"
?>