<?php
	include_once 'common.php';
	$sql = new DbSql();
	$sql->login();
	
	if (($_FILES["file"]["type"] != "text/xml"))
	{
		echo "<script type='text/javascript'>";
		echo "window.opener.location.reload();";  
		echo "window.close();";
		echo "</script>";
		exit;
	}
	
	if(strlen($_FILES["file"]["name"]) < 4)
	{
		echo "<script type='text/javascript'>";
		echo "window.opener.location.reload();";  
		echo "window.close();";
		echo "</script>";
		exit;
	}
	
	$saveimge = randomkeys(). "." . get_extension($_FILES["file"]["name"]);
	
	if ($_FILES["file"]["error"] > 0)
    {
    	echo "Return Code: " . $_FILES["file"]["error"] . "<br />";
    }
  	else
    {
    	if (file_exists("backup/" . $_FILES["file"]["name"]))
      	{
      		echo $_FILES["file"]["name"] . " already exists. ";
      	}
    	else
      	{
      		move_uploaded_file($_FILES["file"]["tmp_name"],"backup/" . $saveimge);
      		echo "Stored in: " . "backup/" . $saveimge;
      	}
	}
	
	
	
	
	$sql->connect_database_default();
	$mydb = $sql->get_database();
	$sql->create_database($mydb);
	
	$mytable = "live_type_table";
	
	$sql->delete_table($mydb, $mytable);
	$sql->create_table($mydb, $mytable, "name longtext, id longtext, lang text");
	//$namess = $type_sql->fetch_datas($mydb, $mytable);
	
	$doc = new DOMDocument();
	$doc->load("backup/" . $saveimge);
	$livetypes = $doc->getElementsByTagName("livetype");
	foreach($livetypes as $livetype)
	{
		
		$name = $livetype->getElementsByTagName("name")->item(0)->nodeValue;
		$addr = $livetype->getElementsByTagName("id")->item(0)->nodeValue;

		$sql->insert_data($mydb, $mytable, "name, id, lang", urldecode($name).", ".$addr.", "."");
		//echo "$title - $author - $publisher\n";
	} 
	
	$sql->disconnect_database();
	
	echo "<script type='text/javascript'>";
	echo "window.opener.location.reload();";  
	echo "window.close();";
	echo "</script>";
	
?>