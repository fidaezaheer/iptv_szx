<?php

  	include 'common.php';
	$sql = new DbSql();
	$sql->login();	
		
	include_once 'gemini.php';
	$g = new Gemini();
	$g->check_version();
	
	$page = $_GET["page"];
	$isinsert = intval($_POST["isinsert"]);
	if($_FILES["file"]["name"] != null)
		$saveimge = randomkeys(). "." . get_extension($_FILES["file"]["name"]);
	else if(strlen($_POST["imageurl"]) > 12)
		$saveimge = $_POST["imageurl"];

	if($_POST["radio_watermark"] == "1" && strlen($_FILES["watermark"]["name"]) > 4)
		$watermark = randomkeys(). "." . get_extension($_FILES["watermark"]["name"]);
	else
		$watermark = "";
		
	$mytable = "live_preview_table";
	$sql->connect_database_default();
	$mydb = $sql->get_database();
		
	$sql->create_database($mydb);
	$sql->create_table($mydb, $mytable, "name longtext, image longtext, url longtext, password longtext, type longtext, preview longtext, id longtext, urlid smallint, hd longtext, watermark text, lang text");
			

	$selected_url = str_replace("&amp;","&",$_POST["liveurl"]);
	$selected_url = str_replace(" ","%20",$_POST["liveurl"]);
	$en_url = base64_encode($selected_url);
	$url = $g->j1($en_url);
	$pw = $g->j1($_POST["livepw"]);
	
	$row = $sql->get_row($mydb,$mytable,"urlid",$_POST["live_id"]);
	if(count($row) >= 1 && $isinsert == 0)
	{
		if(isset($_POST["liveold_id"]) && $_POST["liveold_id"] != $_POST["live_id"])
			$sql->delete_data($mydb, $mytable, "urlid", $_POST["liveold_id"]);
			
		$sql->update_data_2($mydb, $mytable, "urlid", $_POST["live_id"], "name", $_POST["name"]);
		$sql->update_data_2($mydb, $mytable, "urlid", $_POST["live_id"], "url", $url);
		
		if($_FILES["file"]["name"] != null)
			$sql->update_data_2($mydb, $mytable, "urlid", $_POST["live_id"], "image", $saveimge);
		else if(strlen($_POST["imageurl"]) > 12)
			$sql->update_data_2($mydb, $mytable, "urlid", $_POST["live_id"], "image", $_POST["imageurl"]);
		
		$sql->update_data_2($mydb, $mytable, "urlid", $_POST["live_id"], "password", $pw);
		$sql->update_data_2($mydb, $mytable, "urlid", $_POST["live_id"], "type", $_POST["livetype"]);
		$sql->update_data_2($mydb, $mytable, "urlid", $_POST["live_id"], "preview", $_POST["preview"]);
		$sql->update_data_2($mydb, $mytable, "urlid", $_POST["live_id"], "id", $_POST["previewid"]);
		$sql->update_data_2($mydb, $mytable, "urlid", $_POST["live_id"], "hd", $_POST["sourcetype"]);
		$sql->update_data_2($mydb, $mytable, "urlid", $_POST["live_id"], "watermark", $watermark);
		$sql->update_data_2($mydb, $mytable, "urlid", $_POST["live_id"], "urlid", $_POST["live_id"]);
	}
	else if(count($row) >= 1 && $isinsert == 1)
	{
		$sql->insert_where_big($mydb, $mytable, "urlid", $_POST["live_id"]);
		
		if(isset($_POST["liveold_id"]))
			$sql->delete_data($mydb, $mytable, "urlid", $_POST["liveold_id"]);
		$lang = $sql->query_data($mydb,$mytable, "urlid", 20000 ,"lang");
		$sql->insert_data($mydb, $mytable, "name, image, url, password, type, preview, id, urlid, hd, watermark, lang", $_POST["name"].", ".$saveimge.", ". $url .", ".$pw.", ".$_POST["livetype"].", ".$_POST["preview"].", ".$_POST["previewid"].", ".$_POST["live_id"].", ".$_POST["sourcetype"].", ".$watermark.", ".$lang);

	}
	else
	{
		if(isset($_POST["liveold_id"]))
			$sql->delete_data($mydb, $mytable, "urlid", $_POST["liveold_id"]);
			
		$lang = $sql->query_data($mydb,$mytable, "urlid", 20000 ,"lang");
		$sql->insert_data($mydb, $mytable, "name, image, url, password, type, preview, id, urlid, hd, watermark, lang", $_POST["name"].", ".$saveimge.", ". $url .", ".$pw.", ".$_POST["livetype"].", ".$_POST["preview"].", ".$_POST["previewid"].", ".$_POST["live_id"].", ".$_POST["sourcetype"].", ".$watermark.", ".$lang);
	}
	
	$sql->delete_data($mydb, $mytable, "urlid", 20000);
	
	$sql->disconnect_database();

	if ((($_FILES["file"]["type"] == "image/gif") || ($_FILES["file"]["type"] == "image/jpeg") || ($_FILES["file"]["type"] == "image/pjpeg") || ($_FILES["file"]["type"] == "image/png") || ($_FILES["file"]["type"] == "image/bmp")) && ($_FILES["file"]["size"] < 4*1024*1024))
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

    		if (file_exists("../images/livepic/" . $saveimge))
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

	if ((($_FILES["watermark"]["type"] == "image/gif") || ($_FILES["watermark"]["type"] == "image/jpeg") || ($_FILES["watermark"]["type"] == "image/pjpeg") || ($_FILES["watermark"]["type"] == "image/png") || ($_FILES["watermark"]["type"] == "image/bmp")) && ($_FILES["watermark"]["size"] < 4*1024*1024))
	{
  		if ($_FILES["watermark"]["error"] > 0)
    	{
    		echo "Return Code: " . $_FILES["watermark"]["error"] . "<br />";
    	}
  		else
    	{
    		echo "Upload: " . $_FILES["watermark"]["name"] . "<br />";
    		echo "Type: " . $_FILES["watermark"]["type"] . "<br />";
    		echo "Size: " . ($_FILES["watermark"]["size"] / 1024) . " Kb<br />";
    		echo "Temp file: " . $_FILES["watermark"]["tmp_name"] . "<br />";

    		if (file_exists("../images/livepic/" . $watermark))
      		{
      			echo $_FILES["watermark"]["name"] . " already exists. ";
      		}
    		else
      		{
      			move_uploaded_file($_FILES["watermark"]["tmp_name"],"../images/livepic/" . $watermark);
      			echo "Stored in: " . "../images/livepic/" . $watermark;
      		}
    	}
	}
	
	//echo "live_preview_list.php?page=" . $page;
    header("Location: live_preview_list.php?page=" . $page);
?>