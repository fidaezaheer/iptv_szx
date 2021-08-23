<?php
	include 'common.php';
	$sql = new DbSql();
	$sql->login();
	
	$new_server = $_GET["url0"];
	$old_server = $_GET["url1"];
	
	include_once 'gemini.php';
	$g = new Gemini();
	
	$mytable = "live_preview_table";
	$sql->connect_database_default();
	$mydb = $sql->get_database();
	$sql->create_database($mydb);
	$sql->create_table($mydb, $mytable, "name longtext, image longtext, url longtext, password longtext, type longtext, preview longtext, id longtext, urlid smallint, hd longtext");
	$namess = $sql->fetch_datas($mydb, $mytable);
	foreach($namess as $names)
	{
		if(intval($names[7]) > 10000)
		{
			$js_url = base64_decode($g->j2($names[2]));
			$js_url = str_replace($old_server,$new_server,$js_url);
			$sql->update_data_2($mydb, $mytable, "urlid", $names[7], "url", $g->j1(base64_encode($js_url)));
		}
	}

	$sql->disconnect_database();
	
	header("Location: playback_list.php");
?>