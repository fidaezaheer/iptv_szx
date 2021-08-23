<?php
	include 'common.php';
	$sql = new DbSql();
	$sql->login_proxy();	
	
	$name = trim($_COOKIE["user"]);
	$ps = trim($_POST["textfield"]);
  
	$mytable = "proxy_table";
	$sql->connect_database_default();
	$mydb = $sql->get_database();
	$sql->create_database($mydb);
	$sql->create_table($mydb, $mytable, "name text, password text, ptip text, edit int, watermark text, validity date, allow int, remark text, ccount int, panal int, pwmd5 int");	
	
	if($name != "" && $ps != "")
	{
		$sql->update_data_2($mydb, $mytable, "name" , $name, "password", md5(md5($ps)));
		$sql->update_data_2($mydb, $mytable,"name",$name, "pwmd5", 2);
	}
	
	$sql->disconnect_database();
  
	header("Location: proxy_proxy_edit.php");
?>