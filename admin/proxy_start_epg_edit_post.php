<?php

include 'common.php';
$sql = new DbSql();
$sql->login_proxy();
	
function save($name,$proxy)
{
	$sql = new DbSql();
	$sql->connect_database_default();
	$mydb = $sql->get_database();
	$sql->create_database($mydb);
	$mytable = "proxy_download_table";
	$sql->create_table($mydb, $mytable, "name text, sbackground text, sepg text, ebackground text, livepanel text, vodpanel text, download text, allow text, watermark text");	
	$rows = $sql->fetch_datas_where($mydb, $mytable, "name", $proxy);
	if(count($rows) <= 0)
	{
		$sql->insert_data($mydb, $mytable, "name, sbackground, sepg, ebackground, livepanel, vodpanel, download, allow",
			$proxy.", "."".", "."".", ".$name.", "."".", "."0".", "."".", "."");
	}
	else
	{
		$value = $sql->query_data($mydb, $mytable, "name", $proxy, "ebackground");
		if(strlen($value) > 4)
			$value = $value . "|" . $name;
		else
			$value = $name;
		$sql->update_data_2($mydb, $mytable, "name", $proxy, "ebackground", $value);
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
				save($_FILES["file"]["name"],$_COOKIE["user"]);
      		}
    		else
      		{
      			move_uploaded_file($_FILES["file"]["tmp_name"],
      			"../images/background/" . $_FILES["file"]["name"]);
				save($_FILES["file"]["name"],$_COOKIE["user"]);
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

header("Location: proxy_start_epg_background.php");
?>