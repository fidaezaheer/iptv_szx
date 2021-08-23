<?php
	include_once 'memcache.php';
	$mem = new GMemCache();
	if($mem->step1(__FILE__,1) == false)
	{
		echo "1";
		exit;		
	}
	
	include_once 'admindir.php';
	include_once 'gemini.php';
	$a = new Adminer();
	include_once $a->ad . 'common.php';
	$sql = new DbSql();
	$g = new Gemini();
	
	$ip = $sql->str_safe($_GET["ip"]);
	
	$sql->connect_database_default();
	$mydb = $sql->get_database();
	$sql->create_database($mydb);
	
	$mytable = "custom_table";
	$sql->create_table($mydb, $mytable, 
			"mac text,cpu text,ip text,space text, date text,
			time text,allow text, playlist text, online text, allocation text,
			proxy text, balance float,showtime text,contact text,member text,
			panal text,number text,ips text, onescrolltext text, onescrolltexttimes int, 
			numberdate int, scrollcontent text, scrolldate text, scrolltimes text, controlurl text, 		
			controltime int, unbundling int, accessdate timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP, mv text, isupdate int");	

	$rows = array();
	if(isset($_GET["mac"]) && isset($_GET["key"]) && isset($_GET["ip"]) && isset($_GET["time"]))
	{
		$mac = $sql->str_safe($_GET["mac"]);
		$key = $sql->str_safe($_GET["key"]);
		$ip = $sql->str_safe($_GET["ip"]);
		$time = $sql->str_safe($_GET["time"]);
		$smac = $mac;
		
		$check_ok = 0;
		
		
		$time2 = $g->j_key($time);
		date_default_timezone_set('UTC');
		//echo time() . "#" . $time2/1000; 
		if(abs(time() - $time2/1000) > 180)
		{
			echo "1";
			exit;
		}
		
		if(strstr($mac,"00:15:18:01") != false)
			$smac = "00:15:18:01:81:31";
		else if(strstr($mac,"02:00:00:00") != false)
			$smac = "02:00:00:00:00:00";
			
		$rows = $sql->fetch_datas_where_2($mydb, $mytable,"mac",$smac,"ip",$ip);
		$sql->disconnect_database();
		
		foreach($rows as $row)
		{
			if(md5($mac.$row[1]) == $key)
			{
				if($row[6] == "yes")
				{
					$check_ok = 1;
				}
			}
		}
		
		if($check_ok == 1)
			echo "0";
		else
			echo "1";
		exit;
	}
	
	echo "1";
	exit;
?>