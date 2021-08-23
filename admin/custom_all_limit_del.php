<?php
	include 'common.php';
	$sql = new DbSql();
	$sql->login();
	
	$sql->connect_database_default();
	$mydb = $sql->get_database();
	$sql->create_database($mydb);
	$mytable = "limit_mac_table";
	
	if(isset($_GET["mac"]))
	{
		$mac = $_GET["mac"];
		$sql->create_table($mydb, $mytable, "id int, mac text");
		$sql->delete_data($mydb, $mytable, "mac", $mac);
	}
	else
	{
		$macs = $_POST["del"];
		$macss = explode("|",$macs);
		for($ii=0; $ii<count($macss); $ii++)
		{
			$sql->delete_data($mydb, $mytable, "mac", $macss[$ii]);
		}
	}		
	
	$sql->disconnect_database();
	
	header("Location: custom_all_limit_list.php");
?>
