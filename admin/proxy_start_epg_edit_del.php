<?php

	include 'common.php';
	$sql = new DbSql();
	$sql->login_proxy();

	$del_name = $_GET["del"];
	$proxy = $_COOKIE["user"];
	
	$sql = new DbSql();
	$sql->connect_database_default();
	$mydb = $sql->get_database();
	$sql->create_database($mydb);
	$mytable = "proxy_download_table";
	$sql->create_table($mydb, $mytable, "name text, sbackground text, sepg text, ebackground text, livepanel text, vodpanel text, download text, allow text");	
	$background = $sql->query_data($mydb, $mytable, "name", $proxy, "ebackground");
	$backgroundss = explode("|",$background);
	
	$value = "";
	if(count($backgroundss) > 0)
	{
		$index = 0;
		for($ii=0; $ii<count($backgroundss); $ii++)
		{
			if(strlen($backgroundss[$ii]) > 3 && strcmp($backgroundss[$ii],$del_name) != 0)
			{
				if($index == 0)
					$value = $value . $backgroundss[$ii];
				else
					$value = $value . "|" . $backgroundss[$ii];
				$index++;
			}
		}
	}
	
	$sql->update_data_2($mydb, $mytable, "name", $proxy, "ebackground", $value);
	
	$sql->disconnect_database();

	header("Location: proxy_start_epg_background.php");
?>