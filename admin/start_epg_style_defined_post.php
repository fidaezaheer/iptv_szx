<?php
	include 'common.php';
	$sql = new DbSql();
	$sql->login();
	
	
	$item = "style_item" . $_GET["item"];
	$value = "defined*" . urlencode($_GET["name"]) . "*" . urlencode(base64_decode(urldecode($_GET["url"]))) . "*" . $_GET["id"];
	
	$mytable = "start_epg_table";
	$sql->connect_database_default();
	$mydb = $sql->get_database();
	$sql->create_database($mydb);
	$sql->create_table($mydb, $mytable, "tag text, value text");
	$style = $sql->fetch_datas_where($mydb, $mytable, "tag", $item);
	if(count($style) <= 0)
	{
		$sql->insert_data($mydb, $mytable, "tag, value", $item.", ".$value);
	}
	else
	{
		$sql->update_data_2($mydb, $mytable, "tag", $item, "value", $value);
	}
	
	$sql->disconnect_database();
	header("Location: start_epg_style.php");
?>