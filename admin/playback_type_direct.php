<?php
	include_once "cn_lang.php";
	include_once "common.php";
	$sql = new DbSql();
	$sql->login();

	$id = $_GET["id"];
	
	$sql->connect_database_default();
	$mydb = $sql->get_database();
	$sql->create_database($mydb);
	
	$mytable = "playback_type_table";
	$sql->create_table($mydb, $mytable, "name text, id text ,need int, typepassword text, param0 text, param1 text, param2 text, param3 text");
	
	$namess = $sql->fetch_datas($mydb, $mytable);
	$tmp;
	$index;
	foreach($namess as $names) 
	{
		if(strcmp($names[1],$id) == 0 && $index > 0)
		{
			$sql->update_data_2($mydb, $mytable, "id", $names[1], "name", "gemini");
			$sql->update_data_2($mydb, $mytable, "id", $names[1], "need", "gemini");
			$sql->update_data_2($mydb, $mytable, "id", $names[1], "typepassword", "gemini");
			$sql->update_data_2($mydb, $mytable, "id", $names[1], "param0", "gemini");
			$sql->update_data_2($mydb, $mytable, "id", $names[1], "param1", "gemini");
			$sql->update_data_2($mydb, $mytable, "id", $names[1], "param2", "gemini");
			$sql->update_data_2($mydb, $mytable, "id", $names[1], "param3", "gemini");
			$sql->update_data_2($mydb, $mytable, "id", $names[1], "id", "gemini");
			
			$sql->update_data_2($mydb, $mytable, "id", $tmp[1], "name", $names[0]);
			$sql->update_data_2($mydb, $mytable, "id", $tmp[1], "need", $names[2]);
			$sql->update_data_2($mydb, $mytable, "id", $tmp[1], "typepassword", $names[3]);
			$sql->update_data_2($mydb, $mytable, "id", $tmp[1], "param0", $names[4]);
			$sql->update_data_2($mydb, $mytable, "id", $tmp[1], "param1", $names[5]);
			$sql->update_data_2($mydb, $mytable, "id", $tmp[1], "param2", $names[6]);
			$sql->update_data_2($mydb, $mytable, "id", $tmp[1], "param3", $names[7]);
			$sql->update_data_2($mydb, $mytable, "id", $tmp[1], "id", $names[1]);
			
			$sql->update_data_2($mydb, $mytable, "id", "gemini", "name", $tmp[0]);
			$sql->update_data_2($mydb, $mytable, "id", "gemini", "need", $tmp[2]);
			$sql->update_data_2($mydb, $mytable, "id", "gemini", "typepassword", $tmp[3]);
			$sql->update_data_2($mydb, $mytable, "id", "gemini", "param0", $tmp[4]);
			$sql->update_data_2($mydb, $mytable, "id", "gemini", "param1", $tmp[5]);
			$sql->update_data_2($mydb, $mytable, "id", "gemini", "param2", $tmp[6]);
			$sql->update_data_2($mydb, $mytable, "id", "gemini", "param3", $tmp[7]);
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
	
	header("Location: playback_type_list.php");

?>