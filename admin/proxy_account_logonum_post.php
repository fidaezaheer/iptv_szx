<?php
	include 'common.php';
	$sql = new DbSql();
	$sql->login_proxy();
	
	$proxy = $_COOKIE["user"];
	$logonum = $_GET["accountnum"];
	
	$mydb = $sql->get_database();
	$sql->connect_database_default();
	$sql->create_database($mydb);

	$mytable = "proxy_table";
	$sql->create_table($mydb, $mytable, "name text, password text, ptip text, edit int, watermark text, validity date, allow int, remark text, ccount int, panal int, pwmd5 int, logonum text");	

	if(strlen($logonum) > 1)
		$sql->update_data_2($mydb, $mytable,"name",$proxy,"logonum",md5(md5($logonum)));
	else
		$sql->update_data_2($mydb, $mytable, "name",$proxy,"logonum","");


	$sql->disconnect_database();
	
	header("Location: proxy_account_list.php?page=". $_GET["page"]);

?>