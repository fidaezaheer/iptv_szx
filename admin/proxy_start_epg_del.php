<?php
function update_ebackground()
{
	if ((($_FILES["ebackground"]["type"] == "image/gif")
	|| ($_FILES["ebackground"]["type"] == "image/jpeg")
	|| ($_FILES["ebackground"]["type"] == "image/pjpeg")
	|| ($_FILES["ebackground"]["type"] == "image/png")
	|| ($_FILES["ebackground"]["type"] == "image/bmp"))
	&& ($_FILES["ebackground"]["size"] < 4*1024*1024))
	{
  		if ($_FILES["ebackground"]["error"] > 0)
    	{
    		echo "Return Code: " . $_FILES["ebackground"]["error"] . "<br />";
    	}
  		else
    	{
    		echo "Upload: " . $_FILES["ebackground"]["name"] . "<br />";
    		echo "Type: " . $_FILES["ebackground"]["type"] . "<br />";
    		echo "Size: " . ($_FILES["ebackground"]["size"] / 1024) . " Kb<br />";
    		echo "Temp file: " . $_FILES["ebackground"]["tmp_name"] . "<br />";

    		if (file_exists("../images/background/" . $_FILES["ebackground"]["name"]))
      		{
      			echo $_FILES["ebackground"]["name"] . " already exists. ";
      		}
    		else
      		{
      			move_uploaded_file($_FILES["ebackground"]["tmp_name"],
      			"../images/background/" . $_FILES["ebackground"]["name"]);
      			echo "Stored in: " . "../images/background/" . $_FILES["ebackground"]["name"];
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
	$sbackground = "0";
	$slive = "-1";
	$ebackground = "0";
	
	$mydb = $sql->get_database();
	$sql->connect_database_default();
	$sql->create_database($mydb);
	
	$mytable = "proxy_download_table";
	$sql->create_table($mydb, $mytable, "name text, sbackground text, sepg text, ebackground text, livepanel text, vodpanel text, download text, allow text");	
	$sql->update_data_2($mydb, $mytable,"name",$proxy,"ebackground",$ebackground);
	
	$sql->disconnect_database();
	
	header("Location: proxy_start_epg_background.php");

?>