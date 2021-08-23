<?php

	include 'common.php';
	$sql = new DbSql();
	$sql->login();

	$del_name = $_GET["del"];
	
	$sql = new DbSql();
	$mytable = "start_epg_table";
	$sql->connect_database_default();
	$mydb = $sql->get_database();
	$sql->create_database($mydb);
	$sql->create_table($mydb, $mytable, "tag text, value text");
	$background = $sql->query_data($mydb, $mytable, "tag", "background", "value");
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
	
	$sql->update_data_2($mydb, $mytable, "tag", "background", "value", $value);
	
	$sql->disconnect_database();

	header("Location: start_epg_background.php");
?>