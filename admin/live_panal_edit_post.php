<?php
	include 'common.php';
	$sql = new DbSql();
	$sql->login();

	$mytable = "live_panal_table";
	$sql->connect_database_default();
	$mydb = $sql->get_database();
	$sql->create_database($mydb);
	$sql->create_table($mydb, $mytable, "tag longtext, value longtext");
	
	$tag = "watermark";
	$showscroll = "showscroll";
	$lrkey = "lrkey";
	$listad = "adliveimage";
	$watermarksite = "watermarksite";
	$showicon = "showicon";
	$dip1 = "watermarkdip1";
	$dip2 = "watermarkdip2";
	$showid = "showid";
	$showscrolltimes = "showscrolltimes";
	$adliveimagesite = "adliveimagesite";
	$showplaylist = "showplaylist";
	$playtimeout = "playtimeout";
	$text_playtimeout = "text_playtimeout";
	
	if(isset($_GET["recovery"]))
	{
		if(count($sql->fetch_datas_where($mydb, $mytable,"tag",$tag)) <= 0)
		{
			$sql->insert_data($mydb, $mytable, "tag, value", $tag.", "."");
		}
		else
		{	
			$sql->update_data_2($mydb, $mytable,"tag",$tag,"value","");
		}		
		$sql->disconnect_database();
		header("Location: live_panal_edit.php");
		exit;
	}

	if(strlen($_FILES["file"]["name"]) > 4)
	{
			if(count($sql->fetch_datas_where($mydb, $mytable,"tag",$tag)) <= 0)
	{
		$sql->insert_data($mydb, $mytable, "tag, value", $tag.", "."");
	}
	else
	{	
		$sql->update_data_2($mydb, $mytable,"tag",$tag,"value","");
	}
	
		$saveimge = $value = randomkeys(). "." . get_extension($_FILES["file"]["name"]);
		if(count($sql->fetch_datas_where($mydb, $mytable,"tag",$tag)) <= 0)
		{
			$sql->insert_data($mydb, $mytable, "tag, value", $tag.", ".$value);
		}
		else
		{	
			$sql->update_data_2($mydb, $mytable,"tag",$tag,"value",$value);
		}
		
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
	}
	
	$showscroll_value = $_POST["showscroll"];
	if(count($sql->fetch_datas_where($mydb, $mytable,"tag",$showscroll)) <= 0)
	{
		$sql->insert_data($mydb, $mytable, "tag, value", $showscroll.", ".$showscroll_value);
	}
	else
	{	
		$sql->update_data_2($mydb, $mytable,"tag",$showscroll,"value",$showscroll_value);
	}
	
	
	$lrkey_value = $_POST["lrkey"];
	if(count($sql->fetch_datas_where($mydb, $mytable,"tag",$lrkey)) <= 0)
	{
		$sql->insert_data($mydb, $mytable, "tag, value", $lrkey.", ".$lrkey_value);
	}
	else
	{	
		$sql->update_data_2($mydb, $mytable,"tag",$lrkey,"value",$lrkey_value);
	}	
	
	$listad_value = $_POST["listad"];
	if(count($sql->fetch_datas_where($mydb, $mytable,"tag",$listad)) <= 0)
	{
		$sql->insert_data($mydb, $mytable, "tag, value", $listad.", ".$listad_value);
	}
	else
	{	
		$sql->update_data_2($mydb, $mytable,"tag",$listad,"value",$listad_value);
	}	
	
	$watermark_site_value = $_POST["site"];
	if(count($sql->fetch_datas_where($mydb, $mytable,"tag",$watermarksite)) <= 0)
	{
		$sql->insert_data($mydb, $mytable, "tag, value", $watermarksite.", ".$watermark_site_value);
	}
	else
	{	
		$sql->update_data_2($mydb, $mytable,"tag",$watermarksite,"value",$watermark_site_value);
	}	
	
	$showid_value = $_POST["showid"];
	if(count($sql->fetch_datas_where($mydb, $mytable,"tag",$showid)) <= 0)
	{
		$sql->insert_data($mydb, $mytable, "tag, value", $showid.", ".$showid_value);
	}
	else
	{	
		$sql->update_data_2($mydb, $mytable,"tag",$showid,"value",$showid_value);
	}
	
	$showicon_value = $_POST["showicon"];
	if(count($sql->fetch_datas_where($mydb, $mytable,"tag",$showicon)) <= 0)
	{
		$sql->insert_data($mydb, $mytable, "tag, value", $showicon.", ".$showicon_value);
	}
	else
	{	
		$sql->update_data_2($mydb, $mytable,"tag",$showicon,"value",$showicon_value);
	}
	
	$dip1_value = $_POST["dip1"];
	echo $dip1_value;
	if(count($sql->fetch_datas_where($mydb, $mytable,"tag",$dip1)) <= 0)
	{
		$sql->insert_data($mydb, $mytable, "tag, value", $dip1.", ".$dip1_value);
	}
	else
	{	
		$sql->update_data_2($mydb, $mytable,"tag",$dip1,"value",$dip1_value);
	}
	
	$dip2_value = $_POST["dip2"];
	echo $dip2_value;
	if(count($sql->fetch_datas_where($mydb, $mytable,"tag",$dip2)) <= 0)
	{
		$sql->insert_data($mydb, $mytable, "tag, value", $dip2.", ".$dip2_value);
	}
	else
	{	
		$sql->update_data_2($mydb, $mytable,"tag",$dip2,"value",$dip2_value);
	}
	
	$showscrolltimes_value = $_POST["$showscrolltimes"];
	if(count($sql->fetch_datas_where($mydb, $mytable,"tag",$showscrolltimes)) <= 0)
	{
		$sql->insert_data($mydb, $mytable, "tag, value", $showscrolltimes.", ".$showscrolltimes_value);
	}
	else
	{	
		$sql->update_data_2($mydb, $mytable,"tag",$showscrolltimes,"value",$showscrolltimes_value);
	}
	
	$adliveimagesite_value = $_POST["$adliveimagesite"];
	if(count($sql->fetch_datas_where($mydb, $mytable,"tag",$adliveimagesite)) <= 0)
	{
		$sql->insert_data($mydb, $mytable, "tag, value", $adliveimagesite.", ".$adliveimagesite_value);
	}
	else
	{	
		$sql->update_data_2($mydb, $mytable,"tag",$adliveimagesite,"value",$adliveimagesite_value);
	}
	
	$showplaylist_value = $_POST["showplaylist"];
	if(count($sql->fetch_datas_where($mydb, $mytable,"tag",$showplaylist)) <= 0)
	{
		$sql->insert_data($mydb, $mytable, "tag, value", $showplaylist.", ".$showplaylist_value);
	}
	else
	{	
		$sql->update_data_2($mydb, $mytable,"tag",$showplaylist,"value",$showplaylist_value);
	}
	
	$playtimeout_value = $_POST["playtimeout"];
	$text_playtimeout_value = $_POST["text_playtimeout"];
	
	if(count($sql->fetch_datas_where($mydb, $mytable,"tag",$playtimeout)) <= 0)
	{
		if($playtimeout_value == 0)
		{
			$text_playtimeout_value = -1;
		}
		$sql->insert_data($mydb, $mytable, "tag, value", $playtimeout.", ".$text_playtimeout_value);
	}
	else
	{	
		if($playtimeout_value == 0)
		{
			$text_playtimeout_value = -1;	
		}
		$sql->update_data_2($mydb, $mytable,"tag",$playtimeout,"value",$text_playtimeout_value);
	}
	
	$sql->disconnect_database();
	
	header("Location: live_panal_edit.php");
?>