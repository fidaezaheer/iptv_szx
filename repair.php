<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title></title>
</head>

<?php
	include 'admin/common.php';
	$sql = new DbSql();
	
	$version = 88;
	$key = "";
	if(isset($_GET["version"]) && isset($_GET["key"]))
	{
		$version = intval($_GET["version"]);
		$key = $sql->str_safe($_GET["key"]);	
	}
	check_key($version,$key);
	
	
	$sql->connect_database_default();
	$mydb = $sql->get_database();
	$sql->create_database($mydb);
	$mytable = "scroll_table";
	$sql->create_table($mydb, $mytable, "name longtext, value longtext");
	$scroll_text = $sql->query_data($mydb, $mytable, "name", "scroll_text", "value");
	$sql->disconnect_database();	
?>
<body bgcolor="white">
	<font size="+5"><marquee width="1280" border="0" align="middle" scrolldelay="120" ><?php echo $scroll_text ?></marquee></font>
	<img src="images/repair.gif" width="1280" height="720" />
</body>
</html>