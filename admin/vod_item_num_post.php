<?php
	include_once "common.php";
	$sql = new DbSql();
	$sql->login();
	
	$sql->connect_database_default();
	$mydb = $sql->get_database();
	$sql->create_database($mydb);
	
	include_once "gemini.php";
	$g = new Gemini();

	$number_0 = 0;
	$number_1 = 1;
	$number_2 = 2;
	$number_3 = 3;
	
	$number_total_0 = 0;
	$number_total_1 = 0;
	$number_total_2 = 0;
	$number_total_3 = 0;
	
	$mytable = "vod_table_0";
	$sql->create_table($mydb, $mytable, "name text, image text, url text, area text, year text, 
		type text, intro1 longtext, intro2 longtext, intro3 longtext, intro4 longtext,
		id int, clickrate int, recommend tinyint, chage float, updatetime int, 
		firstletter text");
	
	$number_0 = $sql->count_fetch_datas($mydb, $mytable);
	$number_0_namess = $sql->fetch_datas($mydb, $mytable);
	foreach($number_0_namess as $number_0_names) 
	{
		$vods = explode("|", $number_0_names[2]);
		$num = count($vods);
		$number_total_0 = $number_total_0 + $num;
	}
	
	$mytable = "vod_table_1";
	$sql->create_table($mydb, $mytable, "name text, image text, url text, area text, year text, 
		type text, intro1 longtext, intro2 longtext, intro3 longtext, intro4 longtext,
		id int, clickrate int, recommend tinyint, chage float, updatetime int, 
		firstletter text");
	
	$number_1 = $sql->count_fetch_datas($mydb, $mytable);
	$number_1_namess = $sql->fetch_datas($mydb, $mytable);
	foreach($number_1_namess as $number_1_names) 
	{
		$vods = explode("|", $number_1_names[2]);
		$num = count($vods);
		$number_total_1 = $number_total_1 + $num;
	}
	
	$mytable = "vod_table_2";
	$sql->create_table($mydb, $mytable, "name text, image text, url text, area text, year text, 
		type text, intro1 longtext, intro2 longtext, intro3 longtext, intro4 longtext,
		id int, clickrate int, recommend tinyint, chage float, updatetime int, 
		firstletter text");
	
	$number_2 = $sql->count_fetch_datas($mydb, $mytable);
	$number_2_namess = $sql->fetch_datas($mydb, $mytable);
	foreach($number_2_namess as $number_2_names) 
	{
		$vods = explode("|", $number_2_names[2]);
		$num = count($vods);
		$number_total_2 = $number_total_2 + $num;
	}
	
	$mytable = "vod_table_3";
	$sql->create_table($mydb, $mytable, "name text, image text, url text, area text, year text, 
		type text, intro1 longtext, intro2 longtext, intro3 longtext, intro4 longtext,
		id int, clickrate int, recommend tinyint, chage float, updatetime int, 
		firstletter text");
	
	$number_3 = $sql->count_fetch_datas($mydb, $mytable);
	$number_3_namess = $sql->fetch_datas($mydb, $mytable);
	foreach($number_3_namess as $number_3_names) 
	{
		$vods = explode("|", $number_3_names[2]);
		$num = count($vods);
		$number_total_3 = $number_total_3 + $num;
	}
	
	echo $number_0 . "<br/>";
	echo $number_1 . "<br/>";
	echo $number_2 . "<br/>";
	echo $number_3 . "<br/>";
	
	echo $number_total_0 . "<br/>";
	echo $number_total_1 . "<br/>";
	echo $number_total_2 . "<br/>";
	echo $number_total_3 . "<br/>";
	
	$mytable = "vod_name_table";
	$sql->create_table($mydb, $mytable, "id smallint,name text, needps int, password text, num int, total int");
	
	
	$id = 0;
	$name = "";
	$needps = 0;
	$password = "";
	
	$names = $sql->fetch_datas_where($mydb, $mytable, "id", 0);
	if(count($names) <= 0)
	{
		$sql->insert_data($mydb, $mytable, "id, name, needps, password, num, total", $id . ", " . $name . ", " . $needps . ", " . $password . ", " . $number_0 . ", " . $number_total_0);
	}
	else
	{
		$sql->update_data_2($mydb, $mytable,"id",0,"num",$number_0);
		$sql->update_data_2($mydb, $mytable,"id",0,"total",$number_total_0);
	}
	
	$id = 1;
	$name = "";
	$needps = 0;
	$password = "";
	$names = $sql->fetch_datas_where($mydb, $mytable, "id", 1);
	if(count($names)  <= 0)
	{
		$sql->insert_data($mydb, $mytable, "id, name, needps, password, num, total", $id . ", " . $name . ", " . $needps . ", " . $password . ", " . $number_1 . ", " . $number_total_1);
	}
	else
	{
		$sql->update_data_2($mydb, $mytable,"id",1,"num",$number_1);
		$sql->update_data_2($mydb, $mytable,"id",1,"total",$number_total_1);
	}
	
	$id = 2;
	$name = "";
	$needps = 0;
	$password = "";
	$names = $sql->fetch_datas_where($mydb, $mytable, "id", 2);
	if(count($names)  <= 0)
	{
		$sql->insert_data($mydb, $mytable, "id, name, needps, password, num, total", $id . ", " . $name . ", " . $needps . ", " . $password . ", " . $number_2 . ", " . $number_total_2);
	}
	else
	{
		$sql->update_data_2($mydb, $mytable,"id",2,"num",$number_2);
		$sql->update_data_2($mydb, $mytable,"id",2,"total",$number_total_2);
	}
	
	$id = 3;
	$name = "";
	$needps = 0;
	$password = "";
	$names = $sql->fetch_datas_where($mydb, $mytable, "id", 3);
	if(count($names) <= 0)
	{
		$sql->insert_data($mydb, $mytable, "id, name, needps, password, num, total", $id . ", " . $name . ", " . $needps . ", " . $password . ", " . $number_3 . ", " . $number_total_3);
	}
	else
	{
		$sql->update_data_2($mydb, $mytable,"id",3,"num",$number_3);
		$sql->update_data_2($mydb, $mytable,"id",3,"total",$number_total_3);
	}
	$sql->disconnect_database();	
	
	
	header("Location: vod_item_list.php");
?>