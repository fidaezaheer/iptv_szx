<?php
	include 'common.php';
	$sql = new DbSql();
	$sql->login();
	
	set_time_limit(1800);
	
	if ($_FILES["file"]["type"] != "text/plain")
	{
		header("Location: custom_introduction_edit.php");
		exit;
	}
	
	if(strlen($_FILES["file"]["name"]) < 2)
	{
		header("Location: custom_introduction_edit.php");
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
	
	header("Location: custom_introduction_edit.php?saveimage=" . $saveimge);
?>