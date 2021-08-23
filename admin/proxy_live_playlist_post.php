<?php
	include 'common.php';
	$sql = new DbSql();
	$sql->login_proxy();	

	$liveids = $_POST["liveids"];
	$playlistid = $_POST["playlistid"];
	
	echo $liveids;
	echo "<br/>";
	echo $playlistid;
	
	$sql->connect_database_default();
	$mydb = $sql->get_database();
	$sql->create_database($mydb);

	$mytable = "proxy_playlist_table";
	$sql->create_table($mydb, $mytable, "proxy text, playlist text, liveorder longtext");

	$liveorder = $sql->query_data($mydb, $mytable, "proxy", $_COOKIE["user"], "liveorder");
	$liveorderss  = explode("@",$liveorder);
	
	$allliveorder = "";
	$index = 0;
	$exist = 0;
	foreach($liveorderss as $liveorders)
	{
		
		if(strstr($liveorders,$playlistid) != false)
		{
			$allliveorder = $allliveorder . $playlistid . "#" . $liveids;
			$exist = 1;
		}
		else
		{
			$allliveorder = $allliveorder . $liveorders;
		}
		
		if($index < count($liveorderss)-1)
			$allliveorder = $allliveorder . "@";
			
		$index++;	
	}
	
	if($exist == 0)
	{
		if(strlen($allliveorder) > 0)
		{	
			$allliveorder = $allliveorder . "@" . $playlistid . "#" . $liveids;
		}
		else
		{
			$allliveorder = $playlistid . "#" . $liveids;
		}
	}
	
	echo "<br/>";
	echo $allliveorder;
	
	$sql->update_data_2($mydb, $mytable, "proxy", $_COOKIE["user"], "liveorder", $allliveorder);
	
	$sql->disconnect_database();
	
	header("Location: proxy_live_playlist_list.php");
?>