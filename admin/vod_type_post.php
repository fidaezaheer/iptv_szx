<?php
	include 'common.php';
	$sql = new DbSql();
	$sql->login();
	
	$value0 = $_POST["vtype"];
	$value1 = $_POST["year"];
	$value2 = $_POST["area"];
	
	//echo $value1;
	/*
	$value1s = explode("|",$value1);
	sort($value1s);
	$value1 = "";
	for($ii=0; $ii<count($value1s); $ii++)
	{
		if($ii < count($value1s)-1)
			$value1 = $value1 . $value1s[$ii] . "|";
		else
			$value1 = $value1 . $value1s[$ii];
	}
	*/
	
	$mytable = "vod_type_table_" . $_GET["type"];
	$sql->connect_database_default();
	$mydb = $sql->get_database();
	$sql->create_database($mydb);
	$sql->create_table($mydb, $mytable, "value longtext, id smallint");
	
	if(count($sql->fetch_datas_where($mydb,$mytable, "id", 0)) <= 0)
	{
		$sql->insert_data($mydb,$mytable, "value, id", $value0 . ", " . "0");
	}
	else
	{
		$sql->update_data_2($mydb,$mytable, "id", 0, "value", $value0);
	}
	
	if(count($sql->fetch_datas_where($mydb,$mytable, "id", 1)) <= 0)
	{
		$sql->insert_data($mydb,$mytable, "value, id", $value1 . ", " . 1);
	}
	else
	{
		$sql->update_data_2($mydb,$mytable, "id", 1, "value", $value1);
	}
	
	if(count($sql->fetch_datas_where($mydb,$mytable, "id", 2)) <= 0)
	{
		$sql->insert_data($mydb,$mytable, "value, id", $value2 . ", " . 2);
	}
	else
	{
		$sql->update_data_2($mydb,$mytable, "id", 2, "value", $value2);
	}
	
	$sql->disconnect_database();
  
	header("Location: vod_item_list.php?type=" . $_GET["type"]);
?>