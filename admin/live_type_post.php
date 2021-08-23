<?php
	include 'common.php';
	$sql = new DbSql();
	$sql->login();
	
	$name = $_POST["name"];
	$need = $_POST["need"];
	$pw = "123456";
	
	if(isset($_GET["id"]))
		$addr = $_GET["id"];
	else
		$addr = rand(1,1024*1024);

	$mytable = "live_type_table";
	$sql->connect_database_default();
	$mydb = $sql->get_database();
	$sql->create_database($mydb);
	//$sql->delete_table($mydb, $mytable);
	$sql->create_table($mydb, $mytable, "name longtext, id longtext, lang text");
	$names = $sql->get_row($mydb, $mytable, "id", $addr);
	if($names == null)
	{
		$lang = $sql->query_data($mydb,$mytable, "id", "000000" ,"lang");
		$sql->insert_data($mydb, $mytable, "name, id, lang", $name.", ".$addr.", ".$lang);
	}
	else
	{
		$sql->update_data_2($mydb, $mytable, "id", $addr, "name", $name);
	}
	$sql->delete_data($mydb, $mytable, "id", "000000");
	
	$mytable = "live_type_table2";
	$sql->create_database($mydb);
	$sql->create_table($mydb, $mytable, "id text, need int, typepassword text, param0 text, param1 text, param2 text, param3 text");
	$typepassword = $sql->query_data($mydb, $mytable, "need", "1", "typepassword");
	if($typepassword != null)
		$pw = $typepassword;
		
	$names = $sql->get_row($mydb, $mytable, "id", $addr);
	if($names == null)
	{
		$sql->insert_data($mydb, $mytable, "id, need, typepassword, param0, param1, param2, param3", $addr.", ".$need.", ".$pw.", "."null".", "."null".", "."null".", "."null");
	}
	else
	{
		$sql->update_data_2($mydb, $mytable, "id", $addr, "need", $need);
		$sql->update_data_2($mydb, $mytable, "id", $addr, "typepassword", $pw);
	}
	$sql->disconnect_database();
	
	header("Location: live_type_list.php");
	
	/*
	if($name != "")
		$sql->insert_data($mydb, $mytable, "name, id", $name.", ".$addr);
	$sql->disconnect_database();
  
	header("Location: live_preview_list.php");
	*/
?>