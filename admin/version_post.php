<?php
	include 'common.php';
	$sql = new DbSql();
	$sql->login();
	
	$value = 0;
	$addr="";
	$radio_update_model="0";
	$proxy="";
	$libforcetv_open = "0";
	
	if(isset($_POST["versionfield"]))
		$value = $_POST["versionfield"];
		
	if(isset($_POST["updatefield"]))
		$addr = $_POST["updatefield"];
		
	if(isset($_POST["proxy"]))
		$proxy = $_POST["proxy"];	
		
	if(isset($_POST["epglist"]))
		$epglist = $_POST["epglist"];
			
	if(isset($_POST["radio_timeout"]))
		$rtimeout = $_POST["radio_timeout"];
		
	if(isset($_POST["text_timeout"]))
		$ttimeout = $_POST["text_timeout"];
		
	if(isset($_POST["radio_expire"]))
		$expire = $_POST["radio_expire"];
		
	if(isset($_POST["expire_text"]))
		$expire_text = $_POST["expire_text"];
		
	if(isset($_POST["radio_update_model"]))
		$update_model = $_POST["radio_update_model"];
			
	if(isset($_POST["account_tip"]))
		$account_tip = $_POST["account_tip"];
		
	if(isset($_POST["expire_times"]))
		$expire_times = $_POST["expire_times"];
			
	if(isset($_POST["radio_libforcetv_open"]))
		$libforcetv_open = $_POST["radio_libforcetv_open"];
		
	echo $radio_update_model;
	$mydb = $sql->get_database();
	$mytable = "version_table";
	$sql->connect_database_default();
	$sql->create_database($mydb);
	//$sql->delete_table($mydb, $mytable);
	$sql->create_table($mydb, $mytable, "name longtext, value longtext");
	
	$row = $sql->get_row($mydb,$mytable,"name","version");
	if(count($row) >= 1)
		$sql->update_data_2($mydb, $mytable, "name", "version", "value", $value);
	else
		$sql->insert_data($mydb, $mytable,"name,value","version".", ".$value);

	$row = $sql->get_row($mydb,$mytable,"name","addr");
	if(count($row) >= 1)
		$sql->update_data_2($mydb, $mytable, "name", "addr", "value", $addr);
	else
		$sql->insert_data($mydb, $mytable,"name,value","addr".", ".$addr);
		

	$row = $sql->get_row($mydb,$mytable,"name","proxy");
	if(count($row) >= 1)
		$sql->update_data_2($mydb, $mytable, "name", "proxy", "value", $proxy);
	else
		$sql->insert_data($mydb, $mytable,"name,value","proxy".", ".$proxy);
		
	$row = $sql->get_row($mydb,$mytable,"name","expire");
	if(count($row) >= 1)
		$sql->update_data_2($mydb, $mytable, "name", "expire", "value", $expire);
	else
		$sql->insert_data($mydb, $mytable,"name,value","expire".", ".$expire);
		
	$row = $sql->get_row($mydb,$mytable,"name","expiretext");
	if(count($row) >= 1)
		$sql->update_data_2($mydb, $mytable, "name", "expiretext", "value", $expire_text);
	else
		$sql->insert_data($mydb, $mytable,"name,value","expiretext".", ".$expire_text);
		
	$row = $sql->get_row($mydb,$mytable,"name","update_model");
	if(count($row) >= 1)
		$sql->update_data_2($mydb, $mytable, "name", "update_model", "value", $update_model);
	else
		$sql->insert_data($mydb, $mytable,"name,value","update_model".", ".$update_model);
		
	$row = $sql->get_row($mydb,$mytable,"name","account_tip");
	if(count($row) >= 1)
		$sql->update_data_2($mydb, $mytable, "name", "account_tip", "value", $account_tip);
	else
		$sql->insert_data($mydb, $mytable,"name,value","account_tip".", ".$account_tip);
		
	$row = $sql->get_row($mydb,$mytable,"name","expiretimes");
	if(count($row) >= 1)
		$sql->update_data_2($mydb, $mytable, "name", "expiretimes", "value", $expire_times);
	else
		$sql->insert_data($mydb, $mytable,"name,value","expiretimes".", ".$expire_times);
	
	if(strcmp($rtimeout,"-1") == 0)
	{
		$row = $sql->get_row($mydb,$mytable,"name","timeout");
		if(count($row) >= 1)
			$sql->update_data_2($mydb, $mytable, "name", "timeout", "value", $rtimeout);
		else
			$sql->insert_data($mydb, $mytable,"name,value","timeout".", ".$rtimeout);
	}
	else
	{
		$row = $sql->get_row($mydb,$mytable,"name","timeout");
		if(count($row) >= 1)
			$sql->update_data_2($mydb, $mytable, "name", "timeout", "value", $ttimeout);
		else
			$sql->insert_data($mydb, $mytable,"name,value","timeout".", ".$ttimeout);
	}
	
	$mytable = "start_epg_table";
	$sql->create_table($mydb, $mytable, "tag text, value text");
	$epglists = $sql->fetch_datas_where($mydb, $mytable, "tag", "epglist");
	if(count($epglists) > 0)
	{
		$sql->update_data_2($mydb, $mytable, "tag" , "epglist", "value", $epglist);
	}
	else
	{
		$sql->insert_data($mydb, $mytable, "tag, value", "epglist".", ".$epglist);
	}
	

	$mytable = "version_table";
	$sql->create_table($mydb, $mytable, "name longtext, value longtext");
	
	$saveimage = "libforcetv.so";
	
	$libforcetv_opens = $sql->get_row($mydb,$mytable,"name","libforcetv_open");
	if(count($libforcetv_opens) >= 1)
		$sql->update_data_2($mydb, $mytable, "name", "libforcetv_open", "value", $libforcetv_open);
	else
		$sql->insert_data($mydb, $mytable,"name,value","libforcetv_open".", ".$libforcetv_open);
			
	if ($libforcetv_open == "1" && $_FILES["libforcetv_file"]["size"] < 4*1024*1024 && $_FILES["libforcetv_file"]["type"] == "application/octet-stream")
	{
  		if ($_FILES["libforcetv_file"]["error"] > 0)
    	{
    		echo "Return Code: " . $_FILES["libforcetv_file"]["error"] . "<br />";
    	}
  		else
    	{
    		echo "Upload: " . $_FILES["libforcetv_file"]["name"] . "<br />";
    		echo "Type: " . $_FILES["libforcetv_file"]["type"] . "<br />";
    		echo "Size: " . ($_FILES["libforcetv_file"]["size"] / 1024) . " Kb<br />";
    		echo "Temp file: " . $_FILES["libforcetv_file"]["tmp_name"] . "<br />";

    		move_uploaded_file($_FILES["libforcetv_file"]["tmp_name"],"../so/" . $saveimage);
      		echo "Stored in: " . "../so/" . $saveimge;
    	}
		
		
		$libforcetv_versions = $sql->get_row($mydb,$mytable,"name","libforcetv_version");
		if(count($libforcetv_versions) >= 1)
			$sql->update_data_2($mydb, $mytable, "name", "libforcetv_version", "value", time());
		else
			$sql->insert_data($mydb, $mytable,"name,value","libforcetv_version".", ".time());
		
		$libforcetv_md5s = $sql->get_row($mydb,$mytable,"name","libforcetv_md5");
		if(count($libforcetv_md5s) >= 1)
			$sql->update_data_2($mydb, $mytable, "name", "libforcetv_md5", "value", md5_file("../so/" . $saveimage));
		else
			$sql->insert_data($mydb, $mytable,"name,value","libforcetv_md5".", ".md5_file("../so/" . $saveimage));
	}

	$sql->disconnect_database();
	
	/*
	if($_FILES["file"]["size"] > 5*1024*1024 && $_FILES["file"]["size"] < 25*1024*1024)
	{
  		if ($_FILES["file"]["error"] > 0)
    	{
    		echo "Return Code: " . $_FILES["file"]["error"] . "<br />";
    	}
  		else	
		{
			move_uploaded_file($_FILES["file"]["tmp_name"],"../" . "gemini-iptv.apk");
		}
	}
	*/
	
	header("Location: version.php");
?>