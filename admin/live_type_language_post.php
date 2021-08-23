<?php
	include 'common.php';
	$sql = new DbSql();
	$sql->login();	
	
	$id = $_GET["id"];
	$name0 = $_POST["name0"];
	$name1 = $_POST["name1"];
	$name2 = $_POST["name2"];
	$name3 = $_POST["name3"];
	$name4 = $_POST["name4"];
	$name5 = $_POST["name5"];
	$name6 = $_POST["name6"];
	
	$lang = "en" . "@" . $name0 . "|";
	$lang = $lang . "es" . "@" . $name1 . "|";
	$lang = $lang . "ja" . "@" . $name2 . "|";
	$lang = $lang . "ko" . "@" . $name3 . "|";
	$lang = $lang . "cn" . "@" . $name4 . "|";
	$lang = $lang . "hk" . "@" . $name5 . "|";
	$lang = $lang . "tw" . "@" . $name6;
	
	$name = "";
	
	$sql->connect_database_default();
	$mydb = $sql->get_database();
	$sql->create_database($mydb);
	
	$mytable = "live_type_table";
	$sql->create_table($mydb, $mytable, "name longtext, id longtext, lang text");
	if($id == "000000")
		$sql->delete_data($mydb,$mytable,"id",$id);
		
	$row = $sql->get_row($mydb,$mytable,"id",$id);
	if(count($row) <= 0)
	{
		$sql->insert_data($mydb, $mytable, "name, id, lang", $name.", ".$id.", ".$lang);
	}
	else
	{
		$sql->update_data_2($mydb, $mytable, "id", $id, "lang", $lang);
	}
	
	$sql->disconnect_database();
	
	if($id == "000000")
		header("Location: live_type_add.php");
	else
		header("Location: live_type_edit.php?id=" . $id);
		
?>