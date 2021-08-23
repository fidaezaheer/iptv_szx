<?php		
	include_once 'common.php';
	$sql = new DbSql();
	$sql->login();
	
	set_zone();
	
	$allow = $_GET["allow"];
	
	$days = null;
	if(isset($_GET["days"]))
		$days = $_GET["days"];
		
	$allocation = null;
	if(isset($_GET["allocation"]))	
		$allocation = $_GET["allocation"];
		
	$playlist = null;
	if(isset($_GET["playlist"]))		
		$playlist = $_GET["playlist"];

	$contact = null;
	if(isset($_GET["contact"]))		
		$contact = $_GET["contact"];
			
	$limit = "0";
	if(isset($_GET["limit"]))		
		$limit = $_GET["limit"];
	
	$show = "no";
	if(isset($_GET["show"]))		
		$show = $_GET["show"];
		
	$samemac = "no";
	if(isset($_GET["samemac"]))		
		$samemac = $_GET["samemac"];
	
	$startup = "3";
	if(isset($_GET["startup"]))		
		$startup = $_GET["startup"];
			
	$mytable = "custom_close_table";
	$sql->connect_database_default();
	$mydb = $sql->get_database();
	$sql->create_database($mydb);
	$sql->delete_table($mydb,$mytable);
	$sql->create_table($mydb, $mytable, "allow longtext, value longtext");
	
	$sql->insert_data($mydb, $mytable, "allow, value", "allow".", ".$allow);

	if($days != null)
	{
		$sql->insert_data($mydb, $mytable, "allow, value", "days".", ".$days);
	}
	
	if($allocation != null)
	{
		$sql->insert_data($mydb, $mytable, "allow, value", "allocation".", ".$allocation);
	}
	
	if($playlist != null)
		$sql->insert_data($mydb, $mytable, "allow, value", "playlist".", ".$playlist);
	
	if($contact != null)
	{
		$sql->insert_data($mydb, $mytable, "allow, value", "contact".", ".$contact);
	}
	
	$sql->insert_data($mydb, $mytable, "allow, value", "limit".", ".$limit);
	
	$sql->insert_data($mydb, $mytable, "allow, value", "show".", ".$show);
	
	$sql->insert_data($mydb, $mytable, "allow, value", "samemac".", ".$samemac);
	
	$sql->insert_data($mydb, $mytable, "allow, value", "startup".", ".$startup);
	
	$sql->disconnect_database();
	
	header("Location: custom_list.php");
	
?>