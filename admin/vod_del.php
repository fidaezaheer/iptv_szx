<?php
	echo "del live channel:" . $_GET["id"];
	echo "del live type:" . $_GET["type"];
	
	/*$doc = new DOMDocument();
	
	if(isset($_GET["vod"]))
		$doc->load('vod' . $_GET["vod"] . '.xml');
	else
		$doc->load('vod0.xml');
		
	$root = $doc -> documentElement;
	$channels = $doc->getElementsByTagName("content"); 
	
	foreach( $channels as $channel )
	{
		//$image = $channel->getElementsByTagName("image")->item(0)->nodeValue;
		//$url =  $channel->getElementsByTagName("url")->item(0)->nodeValue;
		$name = $channel->getElementsByTagName("name")->item(0)->nodeValue;
		
		if($name == $_GET["name"])
		{
			$root->removeChild($channel);	
		}
	}
	
	if(isset($_GET["vod"]))
		$doc->save('vod' . $_GET["vod"] . '.xml');
	else
		$doc->save('vod0.xml');*/
		
		
	  	include 'common.php';
		$sql = new DbSql();
		$sql->login();
		
		$mytable = "vod_table_".$_GET["type"];
		
		$sql->connect_database_default();
		$mydb = $sql->get_database();
		$sql->create_database($mydb);
		$sql->create_table($mydb, $mytable, "name text, image text, 
			url text, area text, year text, type text, intro1 longtext, intro2 longtext, 
			intro3 longtext, intro4 longtext, id int, clickrate int, recommend tinyint, chage float, updatetime int");
		
		$sql->delete_data($mydb, $mytable, "id", $_GET["id"]);
		
		$sql->disconnect_database();
	
		header("Location: vod_list.php?type=" . $_GET["type"]);
?>