<?php
	include_once 'common.php';
	$sql = new DbSql();
	$sql->login();
	
	$name = $_GET["name"];
	$playlist = $_GET["playlist"];
	
	$sql->connect_database_default();
	$mydb = $sql->get_database();
	$sql->create_database($mydb);
	$mytable = "proxy_playlist_table";
	$sql->create_table($mydb, $mytable, "proxy text, playlist text");	
	
	$names = $sql->fetch_datas_where($mydb, $mytable, "proxy", $name);
	if(count($names) > 0)
	{
		$sql->update_data_2($mydb, $mytable,"proxy",$name,"playlist",$playlist);
	}
	else
	{
		$sql->insert_data($mydb, $mytable, "proxy, playlist", $name.", ".$playlist);
	}
		
	$sql->disconnect_database();
	
	header("Location: proxy_playlist_list.php");
?>