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
	$allow = $_POST["rupdate"];
	$sepg = $_POST["rpanel"];
	$slive = $_POST["livetype"];
	$url = $_POST["tupdate"];
	$sbackground = $_FILES["sbackground"]["name"];
	$ebackground = $_FILES["ebackground"]["name"];
	$scrolltext = $_POST["scrolltext"];
	$version = $_POST["tversion"];
	
	update_sbackground();
	update_ebackground();
	
	$mydb = $sql->get_database();
	$sql->connect_database_default();
	$sql->create_database($mydb);
	
	$mytable = "proxy_download_table";
	$sql->create_table($mydb, $mytable, "name text, sbackground text, sepg text, ebackground text, livepanel text, vodpanel text, download text, allow text, watermark text, scrolltext text, version int");
	$names = $sql->fetch_datas_where($mydb, $mytable, "name", $proxy);
	if(count($names) <= 0)
	{
		$sql->insert_data($mydb, $mytable, "name, sbackground, sepg, ebackground, livepanel, vodpanel, download, allow, scrolltext, version",
			$proxy.", ".$sbackground.", ".$sepg.", ".$ebackground.", ".$slive.", "."0".", ".$url.", ".$allow.", ".$scrolltext.", ".$version);
	}
	else
	{
		$sql->update_data_2($mydb, $mytable,"name",$proxy,"sbackground",$sbackground);
		$sql->update_data_2($mydb, $mytable,"name",$proxy,"sepg",$sepg);
		$sql->update_data_2($mydb, $mytable,"name",$proxy,"livepanel",$slive);
		$sql->update_data_2($mydb, $mytable,"name",$proxy,"ebackground",$ebackground);
		$sql->update_data_2($mydb, $mytable,"name",$proxy,"download",$url);
		$sql->update_data_2($mydb, $mytable,"name",$proxy,"allow",$allow);
		$sql->update_data_2($mydb, $mytable,"name",$proxy,"scrolltext",$scrolltext);
		$sql->update_data_2($mydb, $mytable,"name",$proxy,"version",$version);
	}

	$sql->disconnect_database();
	
	header("Location: proxy_version.php");
	
?>