<?php
	include 'common.php';
	$sql = new DbSql();
	$sql->login();

	$mytable = "live_panal_table";
	$sql->connect_database_default();
	$mydb = $sql->get_database();
	$sql->create_database($mydb);
	$sql->create_table($mydb, $mytable, "tag longtext, value longtext");
	
	$id = intval($_GET["id"]);
	$liveuitype = "liveuitype";

	if(count($sql->fetch_datas_where($mydb, $mytable,"tag",$liveuitype)) <= 0)
	{
		$sql->insert_data($mydb, $mytable, "tag, value", $liveuitype.", ".$id);
	}
	else
	{	
		$sql->update_data_2($mydb, $mytable,"tag",$liveuitype,"value",$id);
	}

	$sql->disconnect_database();
	
	header("Location: live_panal_2.php");

?>