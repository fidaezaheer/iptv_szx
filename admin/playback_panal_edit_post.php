<?php
	include 'common.php';
	$sql = new DbSql();
	$sql->login();

	$mytable = "playback_panal_table";
	$sql->connect_database_default();
	$mydb = $sql->get_database();
	$sql->create_database($mydb);
	$sql->create_table($mydb, $mytable, "tag longtext, value longtext");

	if(isset($_POST["daytime"]))
	{
		if(count($sql->fetch_datas_where($mydb, $mytable,"tag","daytime")) <= 0)
		{
			$sql->insert_data($mydb, $mytable, "tag, value", "daytime".", ".$_POST["daytime"]);
		}
		else
		{	
			$sql->update_data_2($mydb, $mytable,"tag","daytime","value",$_POST["daytime"]);
		}			
	}
	$sql->disconnect_database();
	
	header("Location: playback_panal_edit.php");	
?>