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
	$need = 0;
	$password = "";
	
	$sql->connect_database_default();
	$mydb = $sql->get_database();
	$sql->create_database($mydb);
	
	$mytable = "playback_type_table";
	$sql->create_table($mydb, $mytable, "name text, id text ,need int, typepassword text, param0 text, param1 text, param2 text, param3 text, lang text");
	if($id == "000000")
		$sql->delete_data($mydb,$mytable,"id",$id);
		
	$row = $sql->get_row($mydb,$mytable,"id",$id);
	if(count($row) <= 0)
	{
		//$sql->insert_data($mydb, $mytable, "name, id, lang", $name.", ".$id.", ".$lang);
		$sql->insert_data($mydb, $mytable, "name, id, need, typepassword, param0, param1, param2, param3, lang", 
						$name.", ".$id.", ".$need.", ".$password.", "."0".", "."0".", "."0".", "."0".", ".$lang);
	}
	else
	{
		$sql->update_data_2($mydb, $mytable, "id", $id, "lang", $lang);
	}
	
	$sql->disconnect_database();
	
	if($id == "000000")
		header("Location: playback_type_add.php");
	else
		header("Location: playback_type_edit.php?id=" . $id);
		
?>