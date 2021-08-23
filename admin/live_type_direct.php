<?php
	include_once "cn_lang.php";
	include_once "common.php";
	$sql = new DbSql();
	$sql->login();

	$id = $_GET["id"];
	
	$mytable = "live_type_table";
	$sql->connect_database_default();
	$mydb = $sql->get_database();
	$sql->create_database($mydb);
	$sql->create_table($mydb, $mytable, "name longtext, id longtext, lang text");
	$namess = $sql->fetch_datas($mydb, $mytable);
	$tmp;
	$index;
	foreach($namess as $names) 
	{
		if(strcmp($names[1],$id) == 0 && $index > 0)
		{
			$sql->update_data_2($mydb, $mytable, "id", $names[1], "name", "gemini");
			$sql->update_data_2($mydb, $mytable, "id", $names[1], "lang", "gemini");
			$sql->update_data_2($mydb, $mytable, "id", $names[1], "id", "gemini");
			
			$sql->update_data_2($mydb, $mytable, "id", $tmp[1], "name", $names[0]);
			$sql->update_data_2($mydb, $mytable, "id", $tmp[1], "lang", $names[2]);
			$sql->update_data_2($mydb, $mytable, "id", $tmp[1], "id", $names[1]);
			
			$sql->update_data_2($mydb, $mytable, "id", "gemini", "name", $tmp[0]);
			$sql->update_data_2($mydb, $mytable, "id", "gemini", "lang", $tmp[2]);
			$sql->update_data_2($mydb, $mytable, "id", "gemini", "id", $tmp[1]);
			
			break;
		}
		else
		{
			$tmp = $names;	
		}
		
		$index++;
	}
	
	$sql->disconnect_database();
	
	header("Location: live_type_list.php");

?>