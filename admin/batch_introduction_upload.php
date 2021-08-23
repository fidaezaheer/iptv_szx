<?php
	include 'common.php';
	$sql = new DbSql();
	$sql->login();
	
	$sql->connect_database_default();
	$mydb = $sql->get_database();
	$sql->create_database($mydb);
	$mytable = "introduction_table_tmp";
	$sql->delete_table($mydb, $mytable);
	$sql->disconnect_database();
	
	set_time_limit(1800);
	if (($_FILES["file"]["type"] != "text/xml") && ($_FILES["file"]["type"] != "text/plain"))
	{
		header("Location: batch_introduction_list.php");
		exit;
	}
	
	if(strlen($_FILES["file"]["name"]) < 4)
	{
		header("Location: batch_introduction_list.php?");
		return;
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
	
	header("Location: batch_introduction_list.php?playlist=" . $saveimge);
?>