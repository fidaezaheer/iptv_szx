<?php
	include_once "cn_lang.php";
	include_once "common.php";
	include_once "gemini.php";
	$sql = new DbSql();
	$sql->login();

	$mytable = "live_preview_table";
	$sql->connect_database_default();
	$mydb = $sql->get_database();
		
	$sql->create_database($mydb);
	$sql->create_table($mydb, $mytable, "name longtext, image longtext, url longtext, password longtext, type longtext, preview longtext, id longtext, urlid smallint, hd longtext, watermark text, lang text");
	
	$sql->update_data_2($mydb, $mytable, "urlid", $_GET["urlid"], "preview", "");
	
	$sql->disconnect_database();
	
	if(isset($_GET["date"]))
	{
		echo "=2=";
		echo "<script language='javascript' type='text/javascript'>";
		echo "window.location.href='timer_preview_all.php?id=". $previewid . "&urlid=" . $urlid . "'";
		echo "</script>";
	}
	else
	{
		echo "=3=";
		echo "<script language='javascript' type='text/javascript'>";
		echo "window.location.href='timer_preview.php'";
		echo "</script>";
	}
?>