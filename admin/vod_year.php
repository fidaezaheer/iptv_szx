<?php
	include_once 'common.php';
	include_once 'gemini.php';
	$g = new Gemini();
	$g->check_version();
		
	set_zone();
		
	$sql = new DbSql();
	$sql->login();

	$sql->connect_database_default();
	$mydb = $sql->get_database();
	$sql->create_database($mydb);
	
	for($ii=0; $ii<4; $ii++)
	{	
		$mytable = "vod_type_table_".$ii;
		$sql->create_table($mydb, $mytable, "value longtext, id smallint");
		$item_years = $sql->query_data($mydb, $mytable, "id", 1 ,"value"); 
		$item_year = explode("|", $item_years);
		
		$mytable = "vod_table_".$ii;	
		$sql->create_table($mydb, $mytable, "name text, image text, url text, area text, year text, 
											type text, intro1 longtext, intro2 longtext, intro3 longtext, intro4 longtext, 
											id int, clickrate int, recommend tinyint, chage float, updatetime int, 
											firstletter text");
	
		$namess = $sql->fetch_datas($mydb, $mytable);
	
		foreach($namess as $names) 
		{
			$year = $names[4];
			if(count($item_year) > 0 && ((intval($year)-1) < count($item_year)))
			{
				$sql->update_data_2($mydb, $mytable, "id", $names[10], "year", $item_year[intval($year)-1]);
			}
		}
	}
?>