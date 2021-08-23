<?php
	include 'common.php';
	$sql = new DbSql();
	$sql->login();

	set_time_limit(1800);
	
	include_once 'gemini.php';
	$g = new Gemini();

	$sql->connect_database_default();
	$mydb = $sql->get_database();
	$sql->create_database($mydb);
	
	$mytable = "custom_introduction_table";
	$sql->delete_table($mydb,$mytable);
	$sql->create_table($mydb, $mytable, "allow longtext, value longtext");


	$allow = "yes";
	
	$days = null;
	if(isset($_POST["days"]))
		$days = $_POST["days"];
		
	$allocation = null;
	if(isset($_POST["allocationv"]))	
		$allocation = $_POST["allocationv"];
		
	$playlist = null;
	if(isset($_POST["playlist"]))		
		$playlist = $_POST["playlist"];
	
	$show = "no";
	if(isset($_POST["showv"]))		
		$show = $_POST["showv"];
		
	$proxy = "admin";
	if(isset($_POST["proxyv"]))		
		$proxy = $_POST["proxyv"];
	
	$loginnum = "no";
	if(isset($_POST["loginnumv"]))		
		$loginnum = $_POST["loginnumv"];
			
	$sql->insert_data($mydb, $mytable, "allow, value", "allow".", ".$allow);

	$sql->insert_data($mydb, $mytable, "allow, value", "days".", ".$days);
	
	$sql->insert_data($mydb, $mytable, "allow, value", "allocation".", ".$allocation);

	$sql->insert_data($mydb, $mytable, "allow, value", "playlist".", ".$playlist);

	$sql->insert_data($mydb, $mytable, "allow, value", "show".", ".$show);
	
	$sql->insert_data($mydb, $mytable, "allow, value", "proxy".", ".$proxy);
	
	$sql->insert_data($mydb, $mytable, "allow, value", "loginnum".", ".$loginnum);
	//echo "show:" . $show;
	//echo "allocation:" . $allocation;
	
	if ($_FILES["file"]["type"] != "text/plain")
	{
		header("Location: custom_introduction_edit.php?");
		exit;
	}
	
	if(strlen($_FILES["file"]["name"]) < 1)
	{
		header("Location: custom_introduction_edit.php?");
		exit;
	}
	
	$saveimge = randomkeys(). "." . get_extension($_FILES["file"]["name"]);
	
	if ($_FILES["file"]["error"] > 0)
    {
    	//echo "Return Code: " . $_FILES["file"]["error"] . "<br />";
    }
  	else
    {
    	if (file_exists("backup/" . $_FILES["file"]["name"]))
      	{
      		//echo $_FILES["file"]["name"] . " already exists. ";
      	}
    	else
      	{
      		move_uploaded_file($_FILES["file"]["tmp_name"],"backup/" . $saveimge);
      		//echo "Stored in: " . "backup/" . $saveimge;
      	}
	}

	if(file_exists('backup/' . $saveimge))
	{
	$macs=array();
	$handle = fopen('backup/' . $saveimge , 'r');
    while(!feof($handle))
	{
		$l = fgets($handle);
		if(strlen($l) > 8)
			array_push($macs,$l);
    }
    fclose($handle);
			
	$cpuid = "88888888";
	$space="";
	$ip="";
	$date="";
	$time="";
	$allow="yes";
	$online="";
	$contact="";
	$member="";
	$panal="1";
	$number="";
	$ips="";
	$onescrolltext="";
	$onescrolltexttimes=0;
	
	$numberdate=0;
	$scrollcontent="";
	$scrolldate="";
	$scrolltimes="";
	$controlurl="";
	
	$controltime=0;
	$unbundling=0;
	$accessdate=date("Y-m-d H:i:s");
	$mv="";
	$isupdate=0;
	
	$remarks="";
	$startime="";
	$model="";
	$remotelogin=0;
	$limitmodel="";
	
	$modelerror=0;
	$limittimes=0;
	$limitarea="";
	$ghost=0;
	$password="";
	
	$evernumber="";
	$prekey="";
	//if($loginnum == "yes")
	//	$prekey=strval(rand(1000,9999));
		
	$cpuinfo="";
	$contactkey="";
	
	$mytable = "custom_table";
	$sql->create_table($mydb, $mytable, 
		"mac text,cpu text,ip text,space text, date text,
		time text,allow text, playlist text, online text, allocation text,
		proxy text, balance float,showtime text,contact text,member text,
		panal text,number text,ips text, onescrolltext text, onescrolltexttimes int, 
		numberdate int, scrollcontent text, scrolldate text, scrolltimes text, controlurl text, 		
		controltime int, unbundling int, accessdate timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP, mv text, isupdate int,
		remarks text, startime date, model text, remotelogin int, limitmodel text, 
		modelerror int, limittimes int, limitarea text, ghost int, password text, 
		evernumber longtext, prekey text, cpuinfo text, contactkey text");
	
	$export = "";
	
	foreach($macs as $mac)
	{
		$rows = $sql->fetch_datas_where($mydb, $mytable, "mac", trim(strtolower($mac)));
		if(count($rows) > 0)
		{
			$exist = 0;
			for($ii=0; $ii<count($rows); $ii++)
			{
				if($rows[$ii][1] == "88888888")
				{
					$sql->delete_data_2($mydb, $mytable, "mac", trim(strtolower($rows[$ii][0])), "cpu", "88888888");
				}
				else
				{
					$exist++;
				}
			}
			
			if($exist > 0)
				continue;
		}
		
		$md5_prekey = "";
		if($loginnum == "yes")
		{
			$prekey=strval(rand(1000,9999));
			$md5_prekey = md5(md5($prekey));
		}
		
		$sql->insert_data($mydb, $mytable, 
				"mac, cpu, ip, space, date, 
				time, allow, playlist, online, allocation,
				proxy, balance, showtime ,contact, member, 
				panal, number, ips, onescrolltext, onescrolltexttimes,
				numberdate, scrollcontent, scrolldate, scrolltimes, controlurl,
				controltime, unbundling, accessdate, mv, isupdate,
				remarks, startime, model, remotelogin, limitmodel,
				modelerror, limittimes, limitarea, ghost, password,
				evernumber, prekey, cpuinfo, contactkey",				
				trim(strtolower($mac)).", ".$cpuid.", ".$ip.", ".$space.", ".$date.", ".
				$time.", ".$allow.", ".$playlist.", ".$online.", ".$allocation.", " . 
				$proxy . ", ". 0 . ", ". $show .", ".$contact.", ".$member.", ".
				$panal.", " .$number.", ".$ips.", ".$onescrolltext.", ".$onescrolltexttimes.", ".
				$numberdate.", ".$scrollcontent.", ".$scrolldate.", ".$scrolltimes.", ".$controlurl.", ".
				$controltime.", ".$unbundling.", ".$accessdate.", ".$mv.", ".$isupdate.", ".
				$remarks.", ".$startime.", ".$model.", ".$remotelogin.", ".$limitmodel.", ".
				$modelerror.", ".$limittimes.", ".$model.", ".$remotelogin.", ".$limitmodel.", ".
				$evernumber.", ".$md5_prekey.", ".$cpuinfo.", ".$contactkey);
				
		$export = $export . trim(strtolower($mac)) . "       " . $prekey . "\r\n";	
	}
	}
	$sql->disconnect_database();

	if($loginnum == "yes")
	{
		header( "Content-type:   application/octet-stream "); 
		header( "Accept-Ranges:   bytes "); 
		header( "Content-Disposition:   attachment;   filename=mac.txt "); 
		header( "Expires:   0 "); 
		header( "Cache-Control:   must-revalidate,   post-check=0,   pre-check=0 "); 
		header( "Pragma:   public ");
	
		echo $export;
	}
	else
		header("Location: custom_list.php");
?>