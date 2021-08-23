<?php
	include 'common.php';
	$sql = new DbSql();
	$sql->login();

	set_time_limit(1800) ;
			
	echo $_POST["num"];
	echo $_POST["type"];
	
	$nums = explode("|",$_POST["num"]);
	
	$mytable = "live_preview_table";
	$sql->connect_database_default();
	$mydb = $sql->get_database();
	$sql->create_database($mydb);
	$sql->create_table($mydb, $mytable, "name longtext, image longtext, url longtext, password longtext, type longtext, preview longtext, id longtext, urlid smallint, hd longtext");

	for($ii=0; $ii<count($nums); $ii++)
	{
		$sql->update_data_2($mydb, $mytable, "urlid", $nums[$ii], "type", $_POST["type"]);
	}
	
	$sql->disconnect_database();
	
	echo "<script type='text/javascript'>";
	echo "window.opener.location.reload();";  
	echo "window.close();";
	echo "</script>";
?>