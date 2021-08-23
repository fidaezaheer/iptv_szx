<?php
  	include_once 'common.php';
	include_once 'gemini.php';
	$sql = new DbSql();
	$sql->login();
	$g = new Gemini();	

	$mytable = "live_preview_table";
	$sql->connect_database_default();
	$mydb = $sql->get_database();
		
	$sql->create_database($mydb);
	$sql->create_table($mydb, $mytable, "name longtext, image longtext, url longtext, password longtext, type longtext, preview longtext, id longtext, urlid smallint, hd longtext, lang text");

	$saveimge = randomkeys(). "." . get_extension($_FILES["file"]["name"]);
	
	$selected_url = str_replace("&amp;","&",$_POST["liveurl"]);
	$en_url = base64_encode($selected_url);
	$url = $g->j1($en_url);

	$pw = "null";
	
	if(isset($_GET["id"]))
	{
		$live_id = intval($_GET["id"]);
		echo $live_id;
		$sql->update_data_2($mydb, $mytable, "urlid", $live_id, "name", $_POST["name"]);
		$sql->update_data_2($mydb, $mytable, "urlid", $live_id, "url", $url);
	
		if($_FILES["file"]["name"] != null)
			$sql->update_data_2($mydb, $mytable, "urlid", $live_id, "image", $saveimge);

		$sql->update_data_2($mydb, $mytable, "urlid", $live_id, "type", $_POST["livetype"]);
		$sql->update_data_2($mydb, $mytable, "urlid", $live_id, "preview", $_POST["preview"]);
		$sql->update_data_2($mydb, $mytable, "urlid", $live_id, "id", $_POST["previewid"]);
		$sql->update_data_2($mydb, $mytable, "urlid", $live_id, "hd", $_POST["sourcetype"]);
	}
	else
	{
		$lang = $sql->query_data($mydb,$mytable, "urlid", 20001 ,"lang");
		
		$live_id = intval($_POST["live_id"])+10000;
		$sql->insert_data($mydb, $mytable, "name, image, url, password, type, preview, id, urlid, hd, lang", 
				$_POST["name"].", ".$saveimge.", ". $url .", ".$pw.", ".
				$_POST["livetype"].", ".$_POST["preview"].", ".$_POST["previewid"].", ".$live_id.", ".$_POST["sourcetype"].", ".$lang);
	}
	
	$sql->delete_data($mydb, $mytable, "urlid", 20001);
	
	$sql->disconnect_database();

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

    		if (file_exists("../images/livepic/" . $_FILES["file"]["name"]))
      		{
      			echo $_FILES["file"]["name"] . " already exists. ";
      		}
    		else
      		{
      			move_uploaded_file($_FILES["file"]["tmp_name"],"../images/livepic/" . $saveimge);
      			echo "Stored in: " . "../images/livepic/" . $saveimge;
      		}
    	}
	}
	else
	{
  		//echo "Invalid file";
		//return;
	}

    header("Location: playback_list.php");
?>