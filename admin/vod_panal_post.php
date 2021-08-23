<?php
	include 'common.php';
	$sql = new DbSql();
	$sql->login();

	$mytable = "vod_panal_table";
	$sql->connect_database_default();
	$mydb = $sql->get_database();
	$sql->create_database($mydb);
	$sql->create_table($mydb, $mytable, "tag longtext, value longtext");

	if(isset($_POST["showpage"]))
	{
		$tag = "showpage";
		$showpage = $_POST["showpage"];
		if(count($sql->fetch_datas_where($mydb, $mytable,"tag",$tag)) <= 0)
		{
			$sql->insert_data($mydb, $mytable, "tag, value", $tag.", ".$showpage);
		}
		else
		{	
			$sql->update_data_2($mydb, $mytable,"tag",$tag,"value",$showpage);
		}		
		$sql->disconnect_database();
		header("Location: vod_item_list.php");
		exit;
	}


	$sql->disconnect_database();

?>