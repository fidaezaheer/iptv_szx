<?php

	include_once 'admindir.php';
	$a = new Adminer();
	include_once $a->ad . 'common.php';
	$version = 88;
	$key = "";
	
	$sql = new DbSql();
	
	if(isset($_GET["version"]) && isset($_GET["key"]))
	{
		$version = intval($_GET["version"]);
		$key = $sql->str_safe($_GET["key"]);	
	}
	check_key_out($version,$key);	

	$type = $sql->str_safe($_GET["type"]);
	$id = $sql->str_safe($_GET["id"]);
  	$chage = abs(floatval($_GET["chage"]));
	$mac = $sql->str_safe($_GET["mac"]);
	
	$mytable = "vod_table_".$sql->str_safe($_GET["type"]);
		
	$sql->connect_database_default();
	$mydb = $sql->get_database();
		
	$sql->create_database($mydb);
	//$sql->delete_table($mydb,$mytable);
	$sql->create_table($mydb, $mytable, "name text, image text, 
			url text, area text, year text, type text, intro1 longtext, intro2 longtext, 
			intro3 longtext, intro4 longtext, id int, clickrate int, recommend tinyint, chage float, updatetime int");
			
	$name = $sql->get_row($mydb, $mytable, "id" , $id);
	
	$clickrate = $name["clickrate"] + 1;
	$sql->update_data_2($mydb, $mytable, "id", $id, "clickrate", $clickrate);
	
	$mytable = "custom_table";
	$sql->create_table($mydb, $mytable, "mac text,cpu text,ip text,space text, date text,time text,allow text, playlist text, online text, allocation text, proxy text, balance float,showtime text,contact text,member text,panal text,number text,ips text");
	
	$name = $sql->get_row($mydb, $mytable, "mac" , $mac);
	$balance = $name["balance"] - $chage;
	$sql->update_data_2($mydb, $mytable, "mac", $mac, "balance", $balance);

	$sql->disconnect_database();
?>