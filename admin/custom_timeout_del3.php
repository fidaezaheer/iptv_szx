<?php
function getChaBetweenTwoDate($date1,$date2){
	$Date_List_a1=explode("-",$date1);
	$Date_List_a2=explode("-",$date2);
	if(count($Date_List_a1) < 3 || count($Date_List_a2) < 3)
		return 0;
		
	if(is_numeric($Date_List_a1[1]) && is_numeric($Date_List_a1[0]) && is_numeric($Date_List_a1[2])
		&& is_numeric($Date_List_a2[1]) && is_numeric($Date_List_a2[0]) && is_numeric($Date_List_a2[2])) 	
	{
		$d1=mktime(0,0,0,$Date_List_a1[1],$Date_List_a1[2],$Date_List_a1[0]);
		$d2=mktime(0,0,0,$Date_List_a2[1],$Date_List_a2[2],$Date_List_a2[0]);
		$Days=round(($d1-$d2)/3600/24);
		return $Days;
	}
	else
	{
		return 0;
	}
}


function custom_del($sql, $mydb, $mac, $cpuid)
{
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
		evernumber longtext, prekey text, cpuinfo text");
	$sql->delete_data_2($mydb, $mytable, "mac", $mac, "cpu", $cpuid);
			
	/*
	$mytable = "custom_two_table";
	$sql->create_table($mydb, $mytable, "mac text,cpuid text,showtime text,contact text,param0 text,param1 text,param2 text,param3 text,param4 text");
	$sql->delete_data_2($mydb, $mytable, "mac", $mac, "cpuid", $cpuid);
	*/
			
	$mytable = "custom_download_table";
	$sql->create_table($mydb, $mytable, "mac text,cpu text,tip text,url text,date text,state text,version text");
	$sql->delete_data_2($mydb, $mytable, "mac", $mac, "cpu", $cpuid);
			
	$mytable = "custom_tree_table";
	$sql->create_table($mydb, $mytable, "mac text,cpuid text,date text,timers int");
	$sql->delete_data_2($mydb, $mytable, "mac", $mac, "cpuid", $cpuid);
			
	$mytable = "custom_scroll_table";	
	$sql->create_table($mydb, $mytable, "mac text, cpuid text, scroll longtext,times int");
	$sql->delete_data_2($mydb, $mytable, "mac", $mac, "cpuid", $cpuid);
	
	return $mac;	
}

?>

<?php

	set_time_limit(7200);
	
	include_once 'common.php';
	include_once "cn_lang.php";
	
	$sql = new DbSql();
	$sql->login();
	
	$offset = intval($_GET["offset"]);
	$size = intval($_GET["size"]);
	$offset = $offset*$size;
	$timeout_size = 0;
	$allmac = "";
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
		controltime int, unbundling int, accessdate timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP, mv text, isupdate int,
		remarks text, startime date, model text, remotelogin int, limitmodel text, 
		modelerror int, limittimes int, limitarea text, ghost int, password text, 
		evernumber longtext, prekey text, cpuinfo text");
	
	$namess = $sql->fetch_datas_limit($mydb, $mytable, $offset, $size); 
	foreach($namess as $names) 
	{
		//if(strcmp($names[6],"no") == 0)
		//{
		//	custom_del($sql, $mydb, $names[0], $names[1]);
		//	$timeout_size++;
		//}
		//else
		{
			$starttime = getChaBetweenTwoDate(date('Y-m-d',time()),$names[4]);
			$lefttime = getChaBetweenTwoDate($names[5],date('Y-m-d',time()));
		
			if($lefttime < 0 && $starttime >= 0)
			{
				custom_del($sql, $mydb, $names[0], $names[1]);
				$timeout_size++;
				
				$allmac = $allmac . $mac . "|";
			}
		}
		
		//$sql->update_data_3($mydb, $mytable, "mac", $names[0], "cpu", $names[1], "prekey", "");
	}

	if(strlen($allmac) >= 17)
	{
		$date = date("Y-m-d H:i:s");
		$mytable = "log_record_table";
		$content = $lang_array["del_text1"] . ":" . $allmac;
		$sql->create_table($mydb, $mytable, "date datetime, user text, mac text, cpuid text, content text, other text, ip text");	
		$sql->insert_data($mydb, $mytable, "date, user, mac, cpuid, content, other, ip", $date.", ".$_COOKIE["user"].", "."".", "."".", ".$content.", "."null".", "."");
	}
	$sql->disconnect_database();

	if($namess != null && $offset > 0)
		echo "continue " . $timeout_size;
	else
		echo "finish";
	//header("Location: custom_batch_list.php");
	
?>