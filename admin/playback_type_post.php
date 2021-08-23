<?php
	include 'common.php';
	$sql = new DbSql();
	$sql->login();
	
	$name = $_POST["name"];
	$need = $_POST["need"];
	$password = "123456";
	
	echo $need;
	echo $name;
	
	$sql->connect_database_default();
	$mydb = $sql->get_database();
	$sql->create_database($mydb);
	
	$mytable = "playback_type_table";
	$sql->create_table($mydb, $mytable, "name text, id text ,need int, typepassword text, param0 text, param1 text, param2 text, param3 text, lang text");
	
	$typepassword = $sql->query_data($mydb, $mytable, "need", "1", "typepassword");
	if($typepassword != null)
		$password = $typepassword;
		
	if(!isset($_GET["id"]))
	{
		$lang = $sql->query_data($mydb,$mytable, "id", "000000" ,"lang");
		
		$addr = rand(1,1024*1024);
		$sql->insert_data($mydb, $mytable, "name, id, need, typepassword, param0, param1, param2, param3, lang", 
						$name.", ".$addr.", ".$need.", ".$password.", "."0".", "."0".", "."0".", "."0".", ".$lang);
	}
	else
	{
		$sql->update_data_2($mydb, $mytable, "id", $_GET["id"],"name", $name);
		$sql->update_data_2($mydb, $mytable, "id", $_GET["id"],"need", $need);
		//$sql->update_data_2($mydb, $mytable, "need", $need, "id", $_GET["id"]);
	}

	$sql->delete_data($mydb, $mytable, "id", "000000");
	
	$sql->disconnect_database();
	
	header("Location: playback_type_list.php");
?>