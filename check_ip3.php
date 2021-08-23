<?php
	function writeLog($StrConents)
	{
		$TxtRes;
		if(($TxtRes=fopen("log.txt","a")) === FALSE)
		{
			return;
		}
		
		fwrite ($TxtRes,$StrConents);
		
		fclose ($TxtRes);
	}
	
	include_once 'admindir.php';
	$a = new Adminer();
	include_once $a->ad . 'common.php';
	$sql = new DbSql();
	
	header('Content-Length: 1');
	
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
	if(isset($_GET["mac"]) && isset($_GET["key"]) && isset($_GET["ip"]))
	{
		$mac = $sql->str_safe(urldecode($_GET["mac"]));
		$key = $sql->str_safe(strtolower($_GET["key"]));
		$ip = $sql->str_safe($_GET["ip"]);
		$check_ok = 0;
		
		$rows = $sql->fetch_datas_where_2($mydb, $mytable,"mac",$mac,"ip",$ip);
		$sql->disconnect_database();
		
		foreach($rows as $row)
		{
			if(strtolower(md5($row[0].$row[1])) == $key)
			{
				if($row[6] == "yes" || $row[6] == "pre")
				{
					$check_ok = 1;
				}
			}
		}
		
		if($check_ok == 1)
			echo "0";
		else
		{
			$ip = $sql->query_data($mydb, $mytable,"mac",$mac,"ip");
			if(strlen($ip)>15)
			{
				echo "0";
				exit;
			}
			
			if(count($rows) <= 0)
				writeLog(date("Y.m.d H:i:s") . " 1 " . $mac . "  " . $key . "  " . $ip . "   " . $_GET["mac"] . " NO MAC OR IP \r\n");
			else
				writeLog(date("Y.m.d H:i:s") . " 1 " . $mac . "  " . $key . "  " . $ip . "   " . $_GET["mac"] . " KEY ERROR \r\n");
			
			
			$opts = array(   
  				'http'=>array(   
    				'method'=>"GET",   
    				'timeout'=>5,//单位秒  
   				)   
			);   
	
			$url = "http://weixingdianshitw.com:18006/gemini-iptv/check_ip3.php?mac=" . $_GET["mac"] . "&key=" . $_GET["key"] . "&ip=" . $_GET["ip"];
			$ret = file_get_contents($url, false, stream_context_create($opts));

			if(strlen($ret) > 0)
				return $ret;
			else
				echo "1";
		}
		exit;
	}
	
	$sql->disconnect_database();
	
	writeLog(date("Y.m.d H:i:s") . " 2 " . $mac . "  " . $key . "  " . $ip . "\r\n");
	echo "1";
?>