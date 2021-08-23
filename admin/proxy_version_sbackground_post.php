<?php
function update_sbackground()
{
	if ((($_FILES["sbackground"]["type"] == "image/gif")
	|| ($_FILES["sbackground"]["type"] == "image/jpeg")
	|| ($_FILES["sbackground"]["type"] == "image/pjpeg")
	|| ($_FILES["sbackground"]["type"] == "image/png")
	|| ($_FILES["sbackground"]["type"] == "image/bmp"))
	&& ($_FILES["sbackground"]["size"] < 4*1024*1024))
	{
  		if ($_FILES["sbackground"]["error"] > 0)
    	{
    		echo "Return Code: " . $_FILES["sbackground"]["error"] . "<br />";
    	}
  		else
    	{
    		echo "Upload: " . $_FILES["sbackground"]["name"] . "<br />";
    		echo "Type: " . $_FILES["sbackground"]["type"] . "<br />";
    		echo "Size: " . ($_FILES["sbackground"]["size"] / 1024) . " Kb<br />";
    		echo "Temp file: " . $_FILES["sbackground"]["tmp_name"] . "<br />";

    		if (file_exists("../images/background/" . $_FILES["sbackground"]["name"]))
      		{
      			echo $_FILES["sbackground"]["name"] . " already exists. ";
      		}
    		else
      		{
      			move_uploaded_file($_FILES["sbackground"]["tmp_name"],
      			"../images/background/" . $_FILES["sbackground"]["name"]);
      			echo "Stored in: " . "../images/background/" . $_FILES["sbackground"]["name"];
      		}
    	}
	}
	else
	{
  		//echo "Invalid file";
		//return;
	}	
}
	include 'common.php';
	$sql = new DbSql();
	$sql->login_proxy();
	
	$proxy = $_COOKIE["user"];
	$allow = "1";
	$sepg = "0";
	$url = "0";
	$slive = "-1";
	$sbackground = "0";
	
	$mydb = $sql->get_database();
	$sql->connect_database_default();
	$sql->create_database($mydb);
	
	$mytable = "proxy_download_table";
	$sql->create_table($mydb, $mytable, "name text, sbackground text, sepg text, ebackground text, livepanel text, vodpanel text, download text, allow text");	
	$sql->update_data_2($mydb, $mytable,"name",$proxy,"sbackground",$sbackground);
	
	$sql->disconnect_database();
	
	header("Location: proxy_version.php");

?>