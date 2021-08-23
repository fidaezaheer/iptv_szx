<?php

function save($name)
{
	include 'common.php';
	$sql = new DbSql();
	$sql->login();
	
	$mytable = "start_load_table";
	$sql->connect_database_default();
	$mydb = $sql->get_database();
	$sql->create_database($mydb);
	$sql->create_table($mydb, $mytable, "tag text, value text");
	$background = $sql->get_row_data($mydb, $mytable, "tag", "background");
	if($background == null)
	{
		$sql->insert_data($mydb, $mytable, "tag, value", "background".", ".$name);
	}
	else
	{
		$sql->update_data_2($mydb, $mytable, "tag", "background", "value", $name);
	}

	$sql->disconnect_database();	
}


function account_save($name)
{
	include 'common.php';
	$sql = new DbSql();
	$sql->login();
	
	$mytable = "start_load_table";
	$sql->connect_database_default();
	$mydb = $sql->get_database();
	$sql->create_database($mydb);
	$sql->create_table($mydb, $mytable, "tag text, value text");
	$background = $sql->get_row_data($mydb, $mytable, "tag", "aacount");
	if($background == null)
	{
		$sql->insert_data($mydb, $mytable, "tag, value", "aacount".", ".$name);
	}
	else
	{
		$sql->update_data_2($mydb, $mytable, "tag", "aacount", "value", $name);
	}

	$sql->disconnect_database();	
}

if($_FILES["file"]["name"] != null)
{
	if ((($_FILES["file"]["type"] == "image/gif")
		|| ($_FILES["file"]["type"] == "image/jpeg")
		|| ($_FILES["file"]["type"] == "image/pjpeg")
		|| ($_FILES["file"]["type"] == "image/png")
		|| ($_FILES["file"]["type"] == "image/bmp"))
		&& ($_FILES["file"]["size"] < 4*1024*1024))
	{
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
				save($_FILES["file"]["name"]);
      		}
    		else
      		{
      			move_uploaded_file($_FILES["file"]["tmp_name"],
      			"../images/background/" . $_FILES["file"]["name"]);
				save($_FILES["file"]["name"]);
      			echo "Stored in: " . "../images/background/" . $_FILES["file"]["name"];
      		}
    	}
	}
	else
	{
  		//echo "Invalid file";
		//return;
	}
}

if($_FILES["aacountfile"]["name"] != null)
{
	if ((($_FILES["aacountfile"]["type"] == "image/gif")
		|| ($_FILES["aacountfile"]["type"] == "image/jpeg")
		|| ($_FILES["aacountfile"]["type"] == "image/pjpeg")
		|| ($_FILES["aacountfile"]["type"] == "image/png")
		|| ($_FILES["aacountfile"]["type"] == "image/bmp"))
		&& ($_FILES["aacountfile"]["size"] < 4*1024*1024))
	{
  		if ($_FILES["aacountfile"]["error"] > 0)
    	{
    		echo "Return Code: " . $_FILES["aacountfile"]["error"] . "<br />";
    	}
  		else
    	{
    		echo "Upload: " . $_FILES["aacountfile"]["name"] . "<br />";
    		echo "Type: " . $_FILES["aacountfile"]["type"] . "<br />";
    		echo "Size: " . ($_FILES["aacountfile"]["size"] / 1024) . " Kb<br />";
    		echo "Temp file: " . $_FILES["aacountfile"]["tmp_name"] . "<br />";

    		if (file_exists("../images/background/" . $_FILES["aacountfile"]["name"]))
      		{
      			echo $_FILES["aacountfile"]["name"] . " already exists. ";
				account_save($_FILES["aacountfile"]["name"]);
      		}
    		else
      		{
      			move_uploaded_file($_FILES["aacountfile"]["tmp_name"],"../images/background/" . $_FILES["aacountfile"]["name"]);
				account_save($_FILES["aacountfile"]["name"]);
      			echo "Stored in: " . "../images/background/" . $_FILES["aacountfile"]["name"];
      		}
    	}
	}
	else
	{
  		//echo "Invalid file";
		//return;
	}
}

header("Location: background_list.php");
?>