<?php
	include 'common.php';
	$sql = new DbSql();
	$sql->login();
	
	$id = $_GET["id"];
	$name = $_POST["name"];
	$needps = $_POST["need"];
	$password = $_POST["password"];
	
	$sql->connect_database_default();
	$mydb = $sql->get_database();
	$sql->create_database($mydb);
	$mytable = "vod_name_table";
	$sql->create_table($mydb, $mytable, "id smallint,name text, needps int, password text");
	$names = $sql->fetch_datas_where($mydb, $mytable, "id", $id);
	if(count($names) == 0)
	{
		$sql->insert_data($mydb, $mytable, "id, name, needps, password", $id . ", " . $name . ", " . $needps . ", " . $password);
	}
	else
	{
		$sql->update_data_2($mydb, $mytable, "id", $id, "name", $name);
		$sql->update_data_2($mydb, $mytable, "id", $id, "needps", $needps);
		$sql->update_data_2($mydb, $mytable, "id", $id, "password", $password);
	}

	$sql->disconnect_database();

	header("Location: vod_item_list.php");
?>