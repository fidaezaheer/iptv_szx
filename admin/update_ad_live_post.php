<?php
	include 'common.php';
	$sql = new DbSql();
	$sql->login();

	$mytable = "ad_live_list_table";
	$sql->connect_database_default();
	$mydb = $sql->get_database();
	$sql->create_database($mydb);
	$sql->create_table($mydb, $mytable, "id int, image text");


	if ((($_FILES["file"]["type"] == "image/gif") || ($_FILES["file"]["type"] == "image/jpeg") || ($_FILES["file"]["type"] == "image/pjpeg") || ($_FILES["file"]["type"] == "image/png") || ($_FILES["file"]["type"] == "image/bmp")) && ($_FILES["file"]["size"] < 4*1024*1024))
	{
		
		$saveimge = $value = randomkeys(). "." . get_extension($_FILES["file"]["name"]);
		
  		if ($_FILES["file"]["error"] > 0)
    	{
    		echo "Return Code: " . $_FILES["file"]["error"] . "<br />";
    	}
  		else
    	{
    		echo "Upload: " . $_FILES["file"]["name"] . "<br />";
    		echo "Type: " . $_FILES["file"]["type"] . "<br />";
    		echo "Size: " . ($_FILES["file"]["size"] / 1024) . " Kb<br />";
    		echo "Temp file: " . $_FILES["file"]["tmp_name"] . "<br />";

    		if (file_exists("../images/background/" . $_FILES["file"]["name"]))
      		{
      			echo $_FILES["file"]["name"] . " already exists. ";
				$saveimge = $_FILES["file"]["name"];
      		}
    		else
      		{
      			move_uploaded_file($_FILES["file"]["tmp_name"],"../images/background/" . $saveimge);
      			echo "Stored in: " . "../images/background/" . $saveimge;
      		}
    	}
		
		$addr = rand(1,1024*1024);
		$sql->insert_data($mydb, $mytable, "id, image", $addr.", ".$saveimge);
	}

	
	$sql->disconnect_database();
	
	header("Location: update_ad_live_list.php");



?>