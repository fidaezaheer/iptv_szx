<?PHP
if(extension_loaded('zlib') && strstr($_SERVER['HTTP_ACCEPT_ENCODING'],'gzip'))
{
	ob_end_clean();
    ob_start('ob_gzhandler');
}
?> 


<?php
	include_once 'admindir.php';
	$a = new Adminer();
	include_once $a->ad . 'common.php';
	include_once 'gemini.php';
	include_once "libs/iplocation.class.php";
	if(file_exists("extern.php"))
		include_once "extern.php";
	include_once "cn_lang.php";
	$g = new Gemini();
	
	$version = 88;
	$key = "";
	if(isset($_GET["version"]) && isset($_GET["key"]))
	{
		$version = intval($_GET["version"]);
		$key = $_GET["key"];	
	}
	check_key($version,$key);
?>



<?php 
	set_zone();
	$sql = new DbSql();
	$sql->connect_database_default();
	$mydb = $sql->get_database();
	$sql->create_database($mydb);
?>

    
<?php
function endecho()
{
	echo "<script>";
	echo "if(window.Authentication.CTCIsExistsInterface('CTCLoadWebView') == true)";
	echo "window.Authentication.CTCLoadWebView();";
	echo "</script>";
}

function check_mv($g,$mv,$mac,$t)
{
	$aid = $g->j_key($t);
	$custom_mvs = explode("&",$mv);
	$aids = explode("&",$aid);
	$ghost = 0;
	
	if(count($custom_mvs) < count($aids))
	{
		return 0;	
	}
	else if(count($custom_mvs) == 2 && count($aids) == 2)
	{
		for($ii=0; $ii<count($custom_mvs); $ii++)
		{
			if(strcmp($custom_mvs[$ii],$mac) == 0)
			{
				return 1;
			}
		}
		return 2;
	}
	else if(count($custom_mvs) == 3 && count($aids) == 3)
	{
		for($ii=0; $ii<2; $ii++)
		{
			if(strcmp($custom_mvs[$ii],$mac) == 0)
			{
				return 1;
			}
		}

		if(strcmp($custom_mvs[2],$aids[2]) != 0)
			return 3;
		else
			return 2;
		
	}
	else if(count($custom_mvs) == 4 && count($aids) == 4)
	{
		for($ii=0; $ii<2; $ii++)
		{
			if(strcmp($custom_mvs[$ii],$mac) == 0)
			{
				return 1;
			}
		}

		if(strcmp($custom_mvs[2],$aids[2]) != 0 || strcmp($custom_mvs[3],$aids[3]) != 0)
			return 3;
		else
			return 2;
	}
}
?>


<?php

function char_to_char($v1)
{
	$v2 = $v1;
	$v2 = str_replace("'","\'",$v2);
	
	return $v2;
}

function update_date($day1, $day2)
{
	if(intval($day2) == -1)
		return false;
		
	$days_array = explode("-",$day1);
	$daye_array = explode("-",$day2);
	
	if(intval($days_array[0]) > intval($daye_array[0]))
		return true;
	else if(intval($days_array[0]) < intval($daye_array[0]))
		return false;
		
	if(intval($days_array[1]) > intval($daye_array[1]))
		return true;
	else if(intval($days_array[1]) < intval($daye_array[1]))
		return false;
		
	if(intval($days_array[2]) > intval($daye_array[2]))
		return true;		
	else if(intval($days_array[2]) < intval($daye_array[2]))
		return false;
		
	return false;
}

function getChaBetweenTwoDate($date1,$date2){
	$Date_List_a1=explode("-",$date1);
	$Date_List_a2=explode("-",$date2);
	$d1=mktime(0,0,0,$Date_List_a1[1],$Date_List_a1[2],$Date_List_a1[0]);
	$d2=mktime(0,0,0,$Date_List_a2[1],$Date_List_a2[2],$Date_List_a2[0]);
	$Days=round(($d1-$d2)/3600/24);
	return $Days;
}

function getChaBetweenTwoDate2($date1,$date2){
	//$Date_List_a1=explode("-",$date1);
	$Date_List_a2=explode("-",$date2);
	$d1=$date1*3600*24;
	$d2=mktime(0,0,0,$Date_List_a2[1],$Date_List_a2[2],$Date_List_a2[0]);
	$Days=round(($d1-$d2)/3600/24);
	return $Days;
}

function getIP()
{
	return $_SERVER["REMOTE_ADDR"];
}

function getAreaCode($ip)
{
	$ip_l=new ipLocation();
	$code=$ip_l->getcode($ip);
	return $code;
}

function getSpace($ip)
{

	#需要查詢的IP
	//$ip = "175.191.142.54";
	//返回格式
	$format = "text";//默認text,json,xml,js
	//返回編碼
	$charset = "utf8"; //默認utf-8,gbk或gb2312
	#實例化(必須)
	$ip_l=new ipLocation();
	$address=$ip_l->getaddress($ip);

	$address["area1"] = iconv('GB2312','utf-8//ignore',$address["area1"]);
	$address["area2"] = iconv('GB2312','utf-8//ignore',$address["area2"]);

	$add=$address["area1"]." ".$address["area2"];

	//echo $add;	
	
	return $add;
}

function getSpace2($ip)
{

	#需要查詢的IP
	//$ip = "175.191.142.54";
	//返回格式
	$format = "text";//默認text,json,xml,js
	//返回編碼
	$charset = "utf8"; //默認utf-8,gbk或gb2312
	#實例化(必須)
	$ip_l=new ipLocation();
	$address=$ip_l->getaddress($ip);

	$address["area1"] = iconv('UTF-8','utf-8',$address["area1"]);
	//$address["area2"] = iconv('GB2312','utf-8',$address["area2"]);

	$add=$address["area1"];//." ".$address["area2"];

	return $add;
}

function getKey()
{
	$cdkey = "";	
	for($kk=0; $kk<16; $kk++)
	{
		srand((float)microtime()*1000000); 
		$cdkey = $cdkey . rand(0,9);
		usleep(100);
	}
	return $cdkey;
}

function getLangName($lang, $name, $lang_names)
{
	if($lang_names == null || strlen($lang_names) < 8)
		return char_to_char($name);
		
	$lang_name = explode("|",$lang_names);
	if(count($lang_name) < 7)
	{
		return char_to_char($name);
	}
	
	if($lang == "zh-cn")	
	{
		$lang_names = explode("@",$lang_name[4]);
		if(count($lang_names) >= 2 && strlen($lang_names[1]) > 1)
			return $lang_names[1];
		else
			return char_to_char($name);		
	}
	else if($lang == "zh-tw")
	{
		$lang_names = explode("@",$lang_name[6]);
		if(count($lang_names) >= 2 && strlen($lang_names[1]) > 1)
		{
			return char_to_char($lang_names[1]);
		}
		else
			return char_to_char($name);
	}
	else if($lang == "zh-hk")
	{
		$lang_names = explode("@",$lang_name[5]);
		if(count($lang_names) >= 2 && strlen($lang_names[1]) > 1)
			return char_to_char($lang_names[1]);
		else
			return char_to_char($name);		
	}
	else if(strstr($lang,"en") != false)
	{
		$lang_names = explode("@",$lang_name[0]);
		if(count($lang_names) >= 2 && strlen($lang_names[1]) > 1)
			return char_to_char($lang_names[1]);
		else
			return char_to_char($name);
	}
	else if($lang == "ja-jp")
	{
		$lang_names = explode("@",$lang_name[2]);
		if(count($lang_names) >= 2 && strlen($lang_names[1]) > 1)
			return char_to_char($lang_names[1]);
		else
			return char_to_char($name);		
	}
	else if($lang == "ko-kr")
	{
		$lang_names = explode("@",$lang_name[3]);
		if(count($lang_names) >= 2 && strlen($lang_names[1]) > 1)
			return char_to_char($lang_names[1]);
		else
			return char_to_char($name);		
	}
	else if($lang == "es-es")
	{
		$lang_names = explode("@",$lang_name[1]);
		if(count($lang_names) >= 2 && strlen($lang_names[1]) > 1)
			return char_to_char($lang_names[1]);
		else
			return char_to_char($name);		
	}
	else
	{
		return char_to_char($name);
	}
}
?>

<?php
	$mac="00:00:00:00:00:00";
	$cpuid="xxxxxxxx";
	$version = -1;
	$space="me";
	$playlist="me";
	$date="null";
	$time="null";
	$online=date("Y-m-d H:i:s");
	$allocation="me";
	$mv="none";
	$allow="allow";	
	$lefttime = 0;
	$lang = "";
	$startup = "3";
	$providersname = null;
	
	if(isset($_GET["mac"]))
		$mac=$sql->str_safe($_GET["mac"]);
	else
		exit;
		
	if(isset($_GET["cpuid"]))
		$cpuid=$sql->str_safe($_GET["cpuid"]);
	else
		exit;
	
	if(isset($_GET["version"]))
		$version=$sql->str_safe($_GET["version"]);
			
	if(isset($_GET["lang"]))
		$lang = $sql->str_safe($_GET["lang"]);
		
	if(isset($_GET["providersname"]))
		$providersname = isset($_GET["providersname"]);
		
	$ip = getIP();	
	if(isset($_GET["ip"]) && $_GET["ip"] != "0.0.0.0")
		$ip = $_GET["ip"];
	
	if(isset($_GET["mv"]))
	{
		$mv = $_GET["mv"];
	}
	
	$space = getSpace($ip);
	$ipcode = strtolower(getAreaCode($ip));

	if(strlen($ipcode) < 2)
	{
		$space2 = getSpace2($ip);
		$ip_add_urlcode=urlencode($space2);
		if(strcmp($ip_add_urlcode,urlencode("香港")) == 0)
		{
			$ipcode = "hk";
		}
		else if(strcmp($ip_add_urlcode,urlencode("澳門")) == 0)
		{
			$ipcode = "mo";
		}
		else if(strcmp($ip_add_urlcode,urlencode("台灣省")) == 0)
		{
			$ipcode = "tw";
		}
		else if(strcmp($ip_add_urlcode,urlencode("北美地區")) == 0)
		{
			$ipcode = "us";
		}
		else
		{
			$ipcode = "us";
		}
	}
	
	//echo "ipcode 2: " . $ipcode;
	
	$mytable = "system_table";
	$sql->create_table($mydb, $mytable, "name text, value text");
	$zone = $sql->query_data($mydb, $mytable, "name", "zone", "value");
	date_default_timezone_set($zone);
	
	
	$mytable = "custom_table";
	$sql->create_table($mydb, $mytable, 
		"mac text,cpu text,ip text,space text, date text,
		time text,allow text, playlist text, online text, allocation text,
		proxy text, balance float,showtime text,contact text,member text,
		panal text,number text,ips text, onescrolltext text, onescrolltexttimes int, 
		numberdate int, scrollcontent text, scrolldate text, scrolltimes text, controlurl text, 		
		controltime int, unbundling int, accessdate timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP, mv text, isupdate int,
		remarks text, startime date, model text, remotelogin int, limitmodel text, 
		modelerror int, limittimes int, limitarea text, ghost int");
		
	$namess = $sql->fetch_datas_where_2($mydb, $mytable,"mac",$mac,"cpu",$cpuid);
	$login_ips = "";
	$scrollcontent = "";
	$oldip = "";
	$oldspace = "";
	$limitmodel = "";
	$loginmodel = "";
	$currentmode = "";
	$limittimes = 0;
	$custom_limitarea = "";
	$custom_mv = "";
	$custom_ghost = 0;
	
	if(isset($_GET["model"]))
		$currentmode = $_GET["model"];
		

	
	if(count($namess) > 0)
	{
		$login_ips = $namess[0][17];
		$scrollcontent = $namess[0][21];
		$oldip = $namess[0][2];
		$oldspace = $namess[0][3];
		$limitmodel = $namess[0][34];
		$loginmodel = $namess[0][32];
		$limittimes = intval($namess[0][36]);
		$custom_limitarea = $namess[0][37];
		$custom_mv = $namess[0][28];
		$custom_ghost = $namess[0][38];
	}
	
	
	
	if(strlen($custom_mv) <= 4)
	{
		$sql->update_data_3($mydb, $mytable, "cpu", $cpuid, "mac", $mac, "mv", $g->j_key($mv));
	}
	else
	{
		$ghost = check_mv($g,$custom_mv,$mac,$mv);
		//echo "ghost:" . $ghost;
		if($ghost == 0)
		{
			$sql->update_data_3($mydb, $mytable, "cpu", $cpuid, "mac", $mac, "mv", $g->j_key($mv));
		}
		else if($custom_ghost < $ghost)
		{
			//$ghost = check_mv($g,$custom_mv,$mac,$mv);
			$sql->update_data_3($mydb, $mytable, "cpu", $cpuid, "mac", $mac, "ghost", $ghost);
		}
	}
	/*
	$login_ips = $sql->query_data_2($mydb, $mytable,"mac",$mac,"cpu",$cpuid,"ips");
	$scrollcontent = $sql->query_data_2($mydb, $mytable,"mac",$mac,"cpu",$cpuid,"scrollcontent");
	$oldip = $sql->query_data_2($mydb, $mytable,"mac",$mac,"cpu",$cpuid,"ip");
	$oldspace = $sql->query_data_2($mydb, $mytable,"mac",$mac,"cpu",$cpuid,"space");
	*/
	$login_ip = explode("#",$login_ips);
	$login_time = count($login_ip);
	$login_text = "";
	$login_series = 0;
	if($login_time > 30) 
		$login_time = 30;
	for($ii=0; $ii<$login_time; $ii++)
	{
		$login_text = $login_text . "#";
		$login_text = $login_text . $login_ip[$ii];
		
		if(strstr($login_ip[$ii],date("Y-m-d")) != false)
		{
			
			if(strstr($login_ip[$ii],$ip) == false)
				$login_series++;
		}
	}
	
	if(strlen($currentmode) > 1)
		$login_text = $ip . "&" . $online. "&" . $currentmode . $login_text;
	else
		$login_text = $ip . "&" . $online. $login_text;
	$sql->update_data_3($mydb, $mytable, "mac",$mac,"cpu",$cpuid,"ips",$login_text);
	$sql->update_data_3($mydb, $mytable, "mac",$mac,"cpu",$cpuid,"remotelogin",$login_series);

	$allow_macss = "";
	$stop_macss = "";
	$logintime = 0;
	$limitarea = "";
	$mytable = "safe_table2";
	//$sql->create_table($mydb, $mytable, "id text,safe10 text,safe11 text,safe12 text,safe13 text,safe14 text,safe15 text,safe16 text,safe17 text,safe18 text,safe19 text");
	//$sql->create_table($mydb, $mytable, "id text,safe10 text,safe11 text,safe12 text,safe13 text,safe14 text,safe15 text,safe16 text,safe17 text,safe18 text,safe19 text,model text,logintime int");
	$sql->create_table($mydb, $mytable, "id text,safe10 text,safe11 text,safe12 text,safe13 text,safe14 text,safe15 text,safe16 text,safe17 text,safe18 text,safe19 text,model text,logintime int, unbundling int, disabel_model text, limitarea text");
	$safe_namess = $sql->fetch_datas_where($mydb, $mytable, "id", "0");
	if(count($safe_namess) > 0)
	{
		$allow_macss = $safe_namess[0][4];
		$stop_macss = $safe_namess[0][5];
		$logintime = $safe_namess[0][12];
		$limitarea = $safe_namess[0][15];
	}
	
	if(strlen($ipcode) == 2 && (strlen($limitarea) >= 2 || strlen($custom_limitarea) >= 2) && (strstr(strtolower($limitarea),$ipcode) != false || strstr(strtolower($custom_limitarea),$ipcode) != false))
	{
		header("Location: error.php?version=" . $version . "&key=" . $key . "&error=9");
		exit;
	}
	
	//$allow_macss = $sql->query_data($mydb, $mytable,"id","0","safe13");		
	//$stop_macss = $sql->query_data($mydb, $mytable,"id","0","safe14");
				
	$mytable = "custom_close_table";
	$sql->create_table($mydb, $mytable, "allow text, value text");
	$close = $sql->query_data($mydb, $mytable, "allow", $allow, "value");
	//$startup = $sql->query_data($mydb, $mytable, "allow", "startup", "value");
	if($close == "boxphone")
	{
		if($providersname != null)
			$close = "pre";
		else
			$close = "no";
	}
	
	if($close == "no" || $close == "pre")
	{
		$exist = -1;
		$lefttime = 0;
		foreach($namess as $names) 
		{
			if(strcmp($names[4],"proxy") != 0)
			{
				$starttime = getChaBetweenTwoDate(date('Y-m-d',time()),$names[4]);
				if(strcmp($names[5],"-1") == 0)
				{
					$allow = $names[6];
				 	$lefttime = -1;
				}
				else
				{
					$lefttime = getChaBetweenTwoDate($names[5],date('Y-m-d',time()));
					$allow = $names[6];
					if($lefttime < 0 || $starttime < 0)
						$allow = "no";
				}
			}
			else
			{
				$allow = "no";			
			}
			
			$exist = 1;
			break;

		}
		
		//echo "allow:" . $allow;
		if($exist == -1)
		{

			if(strlen($stop_macss) > 3)
			{
				$stop_macs = explode("|",$stop_macss);
				foreach($stop_macs as $stop_mac)
				{
					$ret = strpos("#".$mac,$stop_mac);
					//echo "2:" . $stop_mac . " mac:" . $mac . " ret:" . $ret;
					if($ret != false)
					{
						$close = "yes";
					}
				}
			}
		
			if(strlen($allow_macss) > 3)
			{
				$allow = FALSE;
				$allow_macs	= explode("|",$allow_macss);
				foreach($allow_macs as $allow_mac)
				{
					$ret = strpos("#".$mac,$allow_mac);
					if($ret != false)	
					{
						$allow = TRUE;
						break;
					}
				}

				if($allow == FALSE)
				{
					$close = "yes";
				}
			}
							
			if(strcmp($close,"pre") == 0)
			{
				$allow = "pre";
				if(strlen($mac) == 17 && stristr(strtolower($cpuid),"and") == false && stristr(strtolower($cpuid),"order") == false && stristr(strtolower($cpuid),"by") == false && strlen($cpuid) > 0)
				{
					$mytable = "custom_close_table";
					$sql->create_table($mydb, $mytable, "allow text, value text");
					$days = $sql->query_data($mydb, $mytable, "allow", "days", "value");
					$allocation = $sql->query_data($mydb, $mytable, "allow", "allocation", "value");
					$playlist = $sql->query_data($mydb, $mytable, "allow", "playlist", "value");
					$contact = $sql->query_data($mydb, $mytable, "allow", "contact", "value");
					$limit = $sql->query_data($mydb, $mytable, "allow", "limit", "value");
					$show = $sql->query_data($mydb, $mytable, "allow", "show", "value");
					$samemac = $sql->query_data($mydb, $mytable, "allow", "samemac", "value");
					$startup = $sql->query_data($mydb, $mytable, "allow", "startup", "value");
					
					if($show == null || $show != "no")
						$show = "yes";
						
					$date = date('Y-m-d');
					$dates = explode("-",$date);
					$from=mktime(0,0,0,$dates[1],$dates[2],$dates[0]);	
					$time_second = intval($days)*24*3600;
					$to = $from + $time_second;
					$time = date("Y-m-d",$to);
					$lefttime = intval($days);

					$limit_allow = 1;
					if($limit != null && strcmp($limit,"1") == 0)
					{
						$limit_allow = 0;
						$mytable = "limit_mac_table";
						$sql->create_table($mydb, $mytable, "id int, mac text");
						$limitmacs = $sql->get_row($mydb, $mytable, "mac", trim(str_replace(":","",$mac)));
						if($limitmacs == null)	
						{
							$limit_allow = 1;	
							header("Location: repair.php?version=".$version."&key=".$key);
							$sql->disconnect_database();
							exit;	
						}
						else
						{
							$sql->delete_data($mydb, $mytable, "mac",trim(str_replace(":","",$mac)));
						}
					}
				
					$mytable = "custom_table";
					$sql->create_table($mydb, $mytable, 
								"mac text,cpu text,ip text,space text, date text,
								time text,allow text, playlist text, online text, allocation text, 
								proxy text, balance float,showtime text,contact text,member text,
								panal text,number text,ips text");
								
								
					if($samemac == "yes")
					{
						$macss = $sql->fetch_datas_where($mydb, $mytable,"mac",$mac);
						if(count($macss) > 0)
						{
							$allow = "no";
							$date = "null";
							$time = "null";
							$allocation = "me";
							$mytable = "custom_table";
							$sql->create_table($mydb, $mytable, "mac text,cpu text,ip text,space text, date text,time text,allow text, playlist text, online text, allocation text, proxy text, balance float,showtime text,contact text,member text,panal text,number text,ips text");
					
							$sql->insert_data($mydb, $mytable, 
								"mac, cpu, ip, space, date, time, allow, playlist, 
								online, allocation, proxy, balance, showtime ,contact ,member, panal,
								number,ips",
								$mac.", ".$cpuid.", ".$ip.", ".$space.", ".$date.", ".$time.", ".$allow.", "."ERROR".", ".
								$online.", ".$allocation.", " . "admin" . ", ". 0 . ", "."".", ".$contact.", "."".", "."1".", " .
								"".", "."");
					
							$sql->create_table($mydb, $mytable, 
								"mac text,cpu text,ip text,space text, date text,
								time text,allow text, playlist text, online text, allocation text,
								proxy text, balance float,showtime text,contact text,member text,
								panal text,number text,ips text, onescrolltext text, onescrolltexttimes int, 
								numberdate int, scrollcontent text, scrolldate text, scrolltimes text, controlurl text, 		
								controltime int, unbundling int, accessdate timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP, mv text, isupdate int,
								remarks text, startime date, model text, remotelogin int, limitmodel text, 
								modelerror int");
		
							$sql->update_data_3($mydb, $mytable, "cpu", $cpuid, "mac", $mac, "startime", date("Y-m-d H:i:s"));
							if(isset($_GET["model"]))
							{
								$sql->update_data_3($mydb, $mytable, "cpu", $cpuid, "mac", $mac, "model", $_GET["model"]);
								if(strlen($loginmodel)>0 && strcmp($loginmodel,$_GET["model"]) != 0)
								{
									$sql->update_data_3($mydb, $mytable, "cpu", $cpuid, "mac", $mac, "modelerror", 1);
								}
								else
								{
									$sql->update_data_3($mydb, $mytable, "cpu", $cpuid, "mac", $mac, "modelerror", 0);
								}
							}
							
							header("Location: error.php?version=" . $version . "&key=" . $key);
							$sql->disconnect_database();
							exit;
						}
					}
					
					$sql->insert_data($mydb, $mytable, 
						"mac, cpu, ip, space, date, time, allow, playlist, 
						online, allocation, proxy, balance, showtime ,contact ,member, panal,
						number,ips",
						$mac.", ".$cpuid.", ".$ip.", ".$space.", ".$date.", ".$time.", ".$allow.", ".$playlist.", ".
						$online.", ".$allocation.", " . "admin" . ", ". 0 . ", ". $show .", ".$contact.", "."".", "."1".", " .
						"".", "."");
						
					$mytable = "custom_table";
					$sql->create_table($mydb, $mytable, 
						"mac text,cpu text,ip text,space text, date text,
						time text,allow text, playlist text, online text, allocation text,
						proxy text, balance float,showtime text,contact text,member text,
						panal text,number text,ips text, onescrolltext text, onescrolltexttimes int, 
						numberdate int, scrollcontent text, scrolldate text, scrolltimes text, controlurl text, 		
						controltime int, unbundling int, accessdate timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP, mv text, isupdate int,
						remarks text, startime date, model text, remotelogin int, limitmodel text, 
						modelerror int");
		
					$sql->update_data_3($mydb, $mytable, "cpu", $cpuid, "mac", $mac, "startime", date("Y-m-d H:i:s"));
					//if(isset($_GET["model"]))
					//	$sql->update_data_3($mydb, $mytable, "cpu", $cpuid, "mac", $mac, "model", $_GET["model"]);
					if(isset($_GET["model"]))
					{
						$sql->update_data_3($mydb, $mytable, "cpu", $cpuid, "mac", $mac, "model", $_GET["model"]);
						if(strlen($loginmodel)>0 && strcmp($loginmodel,$_GET["model"]) != 0)
						{
							$sql->update_data_3($mydb, $mytable, "cpu", $cpuid, "mac", $mac, "modelerror", 1);
						}
						else
						{
							$sql->update_data_3($mydb, $mytable, "cpu", $cpuid, "mac", $mac, "modelerror", 0);
						}
					}
				}
			}
			else
			{
				$allow = "no";
				if(strlen($mac) == 17 && stristr(strtolower($cpuid),"and") == false && stristr(strtolower($cpuid),"order") == false && stristr(strtolower($cpuid),"by") == false && strlen($cpuid) > 0)
				{
					$mytable = "custom_table";
					$sql->create_table($mydb, $mytable, "mac text,cpu text,ip text,space text, date text,time text,allow text, playlist text, online text, allocation text, proxy text, balance float,showtime text,contact text,member text,panal text,number text,ips text");
					
					$sql->insert_data($mydb, $mytable, 
					"mac, cpu, ip, space, date, time, allow, playlist, 
					online, allocation, proxy, balance, showtime ,contact ,member, panal,
					number,ips",
					$mac.", ".$cpuid.", ".$ip.", ".$space.", ".$date.", ".$time.", ".$allow.", "."ERROR".", ".
					$online.", ".$allocation.", " . "admin" . ", ". 0 . ", "."".", ".$contact.", "."".", "."1".", " .
					"".", "."");
					
					$sql->create_table($mydb, $mytable, 
						"mac text,cpu text,ip text,space text, date text,
						time text,allow text, playlist text, online text, allocation text,
						proxy text, balance float,showtime text,contact text,member text,
						panal text,number text,ips text, onescrolltext text, onescrolltexttimes int, 
						numberdate int, scrollcontent text, scrolldate text, scrolltimes text, controlurl text, 		
						controltime int, unbundling int, accessdate timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP, mv text, isupdate int,
						remarks text, startime date, model text, remotelogin int, limitmodel text, 
						modelerror int");
		
					$sql->update_data_3($mydb, $mytable, "cpu", $cpuid, "mac", $mac, "startime", date("Y-m-d H:i:s"));
					//if(isset($_GET["model"]))
					//	$sql->update_data_3($mydb, $mytable, "cpu", $cpuid, "mac", $mac, "model", $_GET["model"]);
					if(isset($_GET["model"]))
					{
						$sql->update_data_3($mydb, $mytable, "cpu", $cpuid, "mac", $mac, "model", $_GET["model"]);
						if(strlen($loginmodel)>0 && strcmp($loginmodel,$_GET["model"]) != 0)
						{
							$sql->update_data_3($mydb, $mytable, "cpu", $cpuid, "mac", $mac, "modelerror", 1);
						}
						else
						{
							$sql->update_data_3($mydb, $mytable, "cpu", $cpuid, "mac", $mac, "modelerror", 0);
						}
					}
					
				}
			}
		}
		else if($exist == 1)
		{
			if(strlen($mac) == 17 && stristr(strtolower($cpuid),"and") == false && stristr(strtolower($cpuid),"order") == false && stristr(strtolower($cpuid),"by") == false && strlen($cpuid) > 0)
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
						modelerror int");
			
				$sql->update_data_3($mydb, $mytable, "mac", $mac, "cpu", $cpuid, "ip", $ip);
				$sql->update_data_3($mydb, $mytable, "mac", $mac, "cpu", $cpuid, "space", $space);
				$sql->update_data_3($mydb, $mytable,"mac", $mac, "cpu", $cpuid, "online", $online);
					
				if(isset($_GET["model"]))
				{
					$sql->update_data_3($mydb, $mytable, "cpu", $cpuid, "mac", $mac, "model", $_GET["model"]);
					if(strlen($loginmodel)>0 && strcmp($loginmodel,$_GET["model"]) != 0)
					{
						$sql->update_data_3($mydb, $mytable, "cpu", $cpuid, "mac", $mac, "modelerror", 1);
					}
					else
					{
						$sql->update_data_3($mydb, $mytable, "cpu", $cpuid, "mac", $mac, "modelerror", 0);
					}
				}
				
				$allow = $sql->query_data_2($mydb, $mytable, "mac", $mac, "cpu", $cpuid, "allow");
				$number = "";
				$number_row = "";

				$number = $sql->query_data_2($mydb, $mytable, "mac", $mac, "cpu", $cpuid, "number");
				$number_row = $sql->count_fetch_datas_where($mydb, $mytable,"number",$number);
				//if(strlen($number) < 8 || count($number_row) > 1)
				if(strlen($number) < 8 || $number_row > 1)
				{
					$number = getKey();
					$sql->update_data_3($mydb, $mytable, "mac", $mac, "cpu", $cpuid, "number", $number);
				}
				
				if($allow == "pre")
				{
					$mytable = "custom_close_table";
					$sql->create_table($mydb, $mytable, "allow text, value text");
					$startup = $sql->query_data($mydb, $mytable, "allow", "startup", "value");
				}
				
			}
		}
		/*
		else 
		{
			if(strlen($mac) == 17 && stristr(strtolower($cpuid),"and") == false && stristr(strtolower($cpuid),"order") == false && stristr(strtolower($cpuid),"by") == false && strlen($cpuid) > 0)
			{
				$mytable = "custom_table";
				$sql->create_table($mydb, $mytable, 
					"mac text,cpu text,ip text,space text, date text,
					time text,allow text, playlist text, online text, allocation text,
					proxy text, balance float,showtime text,contact text,member text,
					panal text,number text,ips text");	
					
				$sql->insert_data($mydb, $mytable, 
					"mac, cpu, ip, space, date, time, allow, playlist, 
					online, allocation, proxy, balance, showtime ,contact ,member, panal,
					number,ips",
					$mac.", ".$cpuid.", ".$ip.", ".$space.", ".$date.", ".$time.", ".$allow.", ".$playlist.", ".
					$online.", ".$allocation.", " . "admin" . ", ". 0 . ", "."".", ".$contact.", "."".", "."1".", " .
					"".", "."");
					
				$mytable = "custom_table";
				$sql->create_table($mydb, $mytable, 
						"mac text,cpu text,ip text,space text, date text,
						time text,allow text, playlist text, online text, allocation text,
						proxy text, balance float,showtime text,contact text,member text,
						panal text,number text,ips text, onescrolltext text, onescrolltexttimes int, 
						numberdate int, scrollcontent text, scrolldate text, scrolltimes text, controlurl text, 		
						controltime int, unbundling int, accessdate timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP, mv text, isupdate int,
						remarks text, startime date, model text, remotelogin int, limitmodel text, 
						modelerror int");
				//if(isset($_GET["model"]))
				//	$sql->update_data_3($mydb, $mytable, "cpu", $cpuid, "mac", $mac, "model", $_GET["model"]);
				if(isset($_GET["model"]))
				{
					$sql->update_data_3($mydb, $mytable, "cpu", $cpuid, "mac", $mac, "model", $_GET["model"]);
					if(strlen($loginmodel)>0 && strcmp($loginmodel,$_GET["model"]) != 0)
					{
						$sql->update_data_3($mydb, $mytable, "cpu", $cpuid, "mac", $mac, "modelerror", 1);
					}
					else
					{
						$sql->update_data_3($mydb, $mytable, "cpu", $cpuid, "mac", $mac, "modelerror", 0);
					}
				}
			}	
		}
		*/
	}

	$mytable = "custom_table";
	$sql->create_table($mydb, $mytable, 
		"mac text,cpu text,ip text,space text, date text,
		time text,allow text, playlist text, online text, allocation text,
		proxy text, balance float,showtime text,contact text,member text,
		panal text,number text,ips text, onescrolltext text, onescrolltexttimes int, 
		numberdate int, scrollcontent text, scrolldate text, scrolltimes text, controlurl text, 		
		controltime int, unbundling int, accessdate timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP, mv text, isupdate int,
		remarks text, startime date, model text");	
		
	$time = $sql->query_data_2($mydb, $mytable,"mac",$mac,"cpu",$cpuid,"time");
	$allow = $sql->query_data_2($mydb, $mytable,"mac",$mac,"cpu",$cpuid,"allow");
	if(($close == "no" || $close == "pre") && (update_date(date("Y-m-d"),$time) || $allow == "no"))
	{
		$sql->update_data_3($mydb, $mytable, "mac",$mac,"cpu",$cpuid, "allow", "no");
		$sql->disconnect_database();
		header("Location: error.php?version=" . $version . "&key=" . $key);
		$sql->disconnect_database();
		exit;
	}

	if(($close == "no" || $close == "pre") && $allow != "yes" && $allow != "pre")
	{
		if(strlen($member) > 0) 
			header("Location: error.php?version=" . $version . "&key=" . $key . "&member=" . $member);
		else
			header("Location: error.php?version=" . $version . "&key=" . $key);
		
		if(file_exists("extern.php"))
		{
			$cmd = "coreapi.cmd " . $cpuid . " " . $mac . " " . "false" . " " . $oldip . " " . $oldspace;
			extern_exec($cmd);
		}
		
		$sql->disconnect_database();	
		exit;
		
	}
	else
	{
		if(file_exists("extern.php"))
		{
			$cmd = "coreapi.cmd " . $cpuid . " " . $mac . " " . $ip . " " . $oldip;
			extern_exec($cmd);
			extern_http($ip,$mac,$cpuid,$_GET["model"]);
		}
	}

?>

<?php
	if(!isset($_GET["version"]))
	{
		echo "Abnormal Access!";
		return;
	}
	
	$version = intval($_GET["version"]);

	$mytable = "safe_table";
	$sql->create_table($mydb, $mytable,"id int,safe0 int,safe1 int,safe2 int,safe3 int,safe4 int,safe5 int,safe6 int,safe7 int,safe8 int,safe9 int");
	$safe_value = $sql->fetch_datas_where($mydb, $mytable, "id", "0");
	$safe0 = 0;
	$safe1 = 0;
	$safe2 = 0;
	if(count($safe_value) > 0)
	{
		$safe0 = $safe_value[0][1];
		$safe1 = $safe_value[0][2];
		$safe2 = $safe_value[0][3];
	}
	
	if($safe2 == 1)
	{
		session_start();
		$allowTime = 10;
		$allowT = "geminiallowtime2";
		if(!isset($_SESSION[$allowT]))
		{
			$_SESSION[$allowT] = time();
		}
		else if(time() - $_SESSION[$allowT]>$allowTime)
		{
			$_SESSION[$allowT] = time();
		}else{
			exit;
		}
	}
	
	
	if($safe1 == 1 || $safe1 == 2)
	{
		if(strcmp($g->j_key($_SERVER['HTTP_USER_AGENT']),"http://www.gemini-iptv.tk/") != 0)
		{
			$sql->disconnect_database();
			echo "Abnormal Access!";
			exit;
		}
	}
				
	/*
	if($safe0 == 1 || $safe0 == 2)
	{
		$key = "0";
		if(isset($_GET["key"]))
			$key = $_GET["key"];
		$key2 = $g->j_key($key);
		$key_s=strstr($key2,"gemini#");
		$key_e=strpos($key_s,"#gemini");
		$key3=substr($key_s,strlen("gemini#"),$key_e-strlen("gemini#"));
		$key4 = explode("&",$key3);
		if(count($key4) >= 2)
		{
			set_zone();
		
			$key_time_s=strstr($key4[0],"time#");
			$key_time_e=strpos($key_time_s,"#time");
			$key_time_3=substr($key_time_s,strlen("time#"),$key_time_e-strlen("#time"));
			if(abs(intval($key_time_3)-intval(time())) > 180 && strcmp($key_time_3,"8888") != 0)
			{
				$sql->disconnect_database();
				endecho();
				exit;
			}
		}
		else
		{
			$sql->disconnect_database();
			endecho();
			exit;
		}
	}
	*/
	
	//set_zone()
	$timers = 0;
	$mytable = "custom_tree_table";				//用於記錄登錄次數
	$sql->create_table($mydb, $mytable, "mac text,cpuid text,date text,timers int");
	$timers = $sql->query_data_2($mydb, $mytable,"mac",$mac,"cpuid",$cpuid,"timers");
	if($timers == null)
	{
		$sql->insert_data($mydb, $mytable, "mac, cpuid, date, timers", $mac.", ".$cpuid.", ".date("Y-m-d").", "."1");
	}
	else
	{
		$date = $sql->query_data_2($mydb, $mytable,"mac",$mac,"cpuid",$cpuid,"date");
		if(strcmp($date,date("Y-m-d")) == 0)
			$timers = $timers + 1;
		else
			$timers = 1;
		
		$sql->update_data_3($mydb, $mytable, "mac", $mac, "cpuid", $cpuid, "date", date("Y-m-d"));
		$sql->update_data_3($mydb, $mytable, "mac", $mac, "cpuid", $cpuid, "timers", $timers);
		
		if($logintime > 1 && $timers > $logintime)
		{
			$sql->disconnect_database();
			header("Location: error.php?error=5&version=" . $version . "&key=" . $key);
			exit;	
			//echo "times error". $logintime . "#" . $timers;
		}
	}
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>VinsenTV</title>
<style type="text/css">
* { margin:0; padding:0; list-style:none; font-size:14px;}
html { height:100%;}
body { height:100%; text-align:center;}
.centerDiv { display:inline-block; zoom:1; *display:inline; vertical-align:middle; text-align:left; width:250px; padding:0px; font-size: 36px; color: #FFF;}
.hiddenDiv { height:100%; overflow:hidden; display:inline-block; width:1px; overflow:hidden; margin-left:-1px; zoom:1; *display:inline; *margin-top:-1px; _margin-top:0; vertical-align:middle;}
</style>
</head>

<script>
var xmlHttp = null;

function init()
{
	
	if(startset() == 4)
	{
		window.open("repair.php?version=<?php echo $version ?>&key=<?php echo $key ?>");
	}
	
	if(CheckMacIsOk() == 0)
		return;
	 
	var error1 = 0;
	var error2 = 0;
	var error = 0;
	
	if(window.Authentication)
	{		
		SetModel();
		
		setTimeNow();
		
		submitPanal();
		
		leftday();
		
		submitScrollText();
		
		submitUpdate();
		
		submitProxyName();
		
		submitScrollEveryScrollText();
		submitScrollEveryVideo();
		
		setShowLefttime();
		
		setContact();
		
		setEpgBackground();
		
		setLoadBackground();
		
		setMember();
		
		setWifiAP();
		
		setDownload();
		
		setOneScroll();
		
		EnableHlsPlugIn();
		
		SetUdp();
			
		SetKey();
		 
		SetNumber();
		
		setEpgStyle();
		
		setEpgList();
		
		setCheckTimeout();
		
		SetVodColumn();
		
		SetLiveWatermark();
		
		SetLiveLeftRight();
		
		SetLiveScrollShow();
		
		SetAdLiveImage();
		
		SetLivePlaylistIcon();
		
		SetVodCount();
		
		SetShowLivePlaylist();
		
		SetIP();
		
		var panal_value = returnPanal();
		
		if(panal_value == 0 && (startset() == 0 || startset() == 1))
		{
			submitPost0();
		}
		else if(startset() == 0 || startset() == 1 || startset() == 5 || startset() == 6)
		{
			submitType();
			submitPlaybackType();
			if(submitPost1() == -1)
				error1 = 1;
			/*
			if(submitPost2() == -1)
				error2 = 1;
			*/
			if(error1 == -1)	
				error = 1;
		}
	}
	
	if(error == 1)
	{
		window.open("error.php?version=<?php echo $version ?>&key=<?php echo $key ?>",'_self');
		//alert(error,'_self');
	}
	else
	{
	if(startset() == 0)
	{
		if(window.Authentication)
		{
			window.Authentication.CTClanucherActivity();
			submitScrollAdPic();
		}
	}
	else if(startset() == 2)
	{
		if(window.Authentication)
		{
			window.Authentication.CTCStartVod();
			window.Authentication.CTCSetCloseWebPage();
		}
	}
	else if(startset() == 3)
	{
		if(window.Authentication)
		{
			setBroadcast();
			window.Authentication.CTCStartBroadcast();
			window.Authentication.CTCSetCloseWebPage();
		}
	}
	else if(startset() == 5)
	{
		if(window.Authentication)
		{
			if(window.Authentication.CTCIsExistsInterface('CTClanucherActivity2') == true)
			{
				window.Authentication.CTClanucherActivity2();
			}
			else
			{
				window.Authentication.CTClanucherActivity();
			}
			submitScrollAdPic();
		}		
	}
	else if(startset() == 6)
	{
		if(window.Authentication)
		{
			if(window.Authentication.CTCIsExistsInterface('CTCStartPlayback') == true)
			{
				window.Authentication.CTCStartPlayback();
				window.Authentication.CTCSetCloseWebPage();
			}
			submitScrollAdPic();
			
		}		
	}
	else 
	{
		if(window.Authentication)
		{
			window.Authentication.CTCStartApp();
			window.Authentication.CTCSetCloseWebPage();
		}
	}
	}
}

function on_keyback()
{
	window.Authentication.exitApp();
}


function returnPanal()
{
<?php
	$live_panal = null;
	$watermark = null;
	$showscroll = null;
	$watermarksite = null;
	$watermarkdip1 = null;
	$watermarkdip2 = null;
	$showicon = null;
	$showid = null;
	$showscrolltimes = null;
	$liveuitype = null;
	$showplaylist = null;
	$showplaylistname = null;
	
	$mytable = "live_panal_table";
	$sql->create_table($mydb, $mytable, "tag longtext, value longtext");

	$live_panal_table_row = $sql->fetch_datas($mydb, $mytable);
	for($ii=0; $ii<count($live_panal_table_row); $ii++)
	{
		if($live_panal_table_row[$ii][0] == "panal")
			$live_panal = $live_panal_table_row[$ii][1];
		else if($live_panal_table_row[$ii][0] == "watermark")
			$watermark = $live_panal_table_row[$ii][1];
		else if($live_panal_table_row[$ii][0] == "showscroll")
			$showscroll = $live_panal_table_row[$ii][1];	
		else if($live_panal_table_row[$ii][0] == "watermarksite")
			$watermarksite = $live_panal_table_row[$ii][1];		
		else if($live_panal_table_row[$ii][0] == "watermarkdip1")
			$watermarkdip1 = $live_panal_table_row[$ii][1];					
		else if($live_panal_table_row[$ii][0] == "watermarkdip2")
			$watermarkdip2 = $live_panal_table_row[$ii][1];			
		else if($live_panal_table_row[$ii][0] == "showicon")
			$showicon = $live_panal_table_row[$ii][1];		
		else if($live_panal_table_row[$ii][0] == "showid")
		{
			$showid = intval($live_panal_table_row[$ii][1]);			
		}
		else if($live_panal_table_row[$ii][0] == "showscrolltimes")
		{
			$showscrolltimes = intval($live_panal_table_row[$ii][1]);	
		}
		else if($live_panal_table_row[$ii][0] == "liveuitype")
		{
			$liveuitype = intval($live_panal_table_row[$ii][1]);			
		}
		else if($live_panal_table_row[$ii][0] == "showplaylist")
		{
			$showplaylist = intval($live_panal_table_row[$ii][1]);		
		}
		else if($live_panal_table_row[$ii][0] == "lrkey")
		{
			$lrkey = $live_panal_table_row[$ii][1];		
		}
		else if($live_panal_table_row[$ii][0] == "adliveimage")
		{
			$adliveimage = $live_panal_table_row[$ii][1];		
		}
		else if($live_panal_table_row[$ii][0] == "adliveimagesite")
		{
			$adliveimagesite_text = $live_panal_table_row[$ii][1];		
		}
	}
	
	if($showid == null)
		$showid = 0;
	if($showscrolltimes == null)
		$showscrolltimes = 0;
	if($liveuitype == null)
		$liveuitype = 0;				
	if($showplaylist == null)
		$showplaylist = 0;
	/*
	$live_panal = $sql->query_data($mydb, $mytable, "tag", "panal","value");
	$watermark = $sql->query_data($mydb, $mytable, "tag", "watermark","value");
	$showscroll = $sql->query_data($mydb, $mytable,"tag","showscroll","value");
	$watermarksite = $sql->query_data($mydb, $mytable,"tag","watermarksite","value");
	$watermarkdip1 = $sql->query_data($mydb, $mytable,"tag","watermarkdip1","value");
	$watermarkdip2 = $sql->query_data($mydb, $mytable,"tag","watermarkdip2","value");
	$showicon = $sql->query_data($mydb, $mytable,"tag","showicon","value");
	$showid = intval($sql->query_data($mydb, $mytable,"tag","showid","value"));
	$showscrolltimes = intval($sql->query_data($mydb, $mytable,"tag","showscrolltimes","value"));
	$liveuitype = intval($sql->query_data($mydb, $mytable,"tag","liveuitype","value"));
	*/
	
	if($showicon == null)
		$showicon = 1;
	if($watermarkdip1 == null)
		$watermarkdip1 = 25;
	if($watermarkdip2 == null)
		$watermarkdip2 = 25;
			
	if($showscroll == null) 
		$showscroll = 0;
		
	/*	
	$lrkey = $sql->query_data($mydb, $mytable,"tag","lrkey","value");
	$adliveimage = $sql->query_data($mydb, $mytable,"tag","adliveimage","value");
	$adliveimagesite_text = $sql->query_data($mydb, $mytable,"tag","adliveimagesite","value");
	*/
	
	if($lrkey == null) 
		$lrkey = 0;
	if($adliveimage == null) 
		$adliveimage = 0;
	if($watermarksite == null) 
		$watermarksite = 2;
		
	if($adliveimagesite_text == null || strlen($adliveimagesite_text) <= 0) 
		$adliveimagesite = 1;
	else
		$adliveimagesite = intval($adliveimagesite_text);
		
?>
	return <?php if($live_panal != null) echo $live_panal; else echo "1";?>;
}



function submitPanal()
{
<?php 
	/*
	0---mac
	1---cpu
	2---ip
	3---space
	4---date
	5---time
	6---allow
	7---playlist
	8---online
	9---allocation
	10--proxy
	11--banance
	*/
	$mytable = "custom_table";
	//$sql->create_table($mydb, $mytable, "mac text,cpu text,ip text,space text, date text,time text,allow text, playlist text, online text, allocation text, proxy text, balance float");
	/*
	$sql->create_table($mydb, $mytable, 
		"mac text,cpu text,ip text,space text, date text,		
		time text,allow text, playlist text, online text, allocation text, 
		proxy text, balance float,showtime text,contact text,member text,
		panal text,number text,ips text, onescrolltext text, onescrolltexttimes int");
	*/
	$sql->create_table($mydb, $mytable, 
			"mac text,cpu text,ip text,space text, date text,
			time text,allow text, playlist text, online text, allocation text,
			proxy text, balance float,showtime text,contact text,member text,
			panal text,number text,ips text, onescrolltext text, onescrolltexttimes int, 
			numberdate int, scrollcontent text, scrolldate text, scrolltimes text, controlurl text, 		
			controltime int, unbundling int, accessdate timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP, mv text, isupdate int");		
	$custom_tables = $sql->fetch_datas_where_2($mydb, $mytable,"mac",$mac,"cpu",$cpuid);
	$proxy_name = "admin";
	$custom_panal = "0";

	//if(count($custom_tables) > 0)
	{
		if(count($custom_tables) > 0)
		{
			$proxy_name = $custom_tables[0][10];
			$custom_panal = $custom_tables[0][15];
		}
	}	
	
	
	
	/*
	0---name
	1---sbackground
	2---sepg
	3---ebackground
	4---livepanel
	5---vodpanel
	6---download
	7---allow
	*/
	$mytable = "proxy_download_table";
	$sql->create_table($mydb, $mytable, "name text, sbackground text, sepg text, ebackground text, livepanel text, vodpanel text, download text, allow text, watermark text, scrolltext text");	
	$proxy_downloads = $sql->fetch_datas_where($mydb, $mytable,"name",$proxy_name);
	$proxylivepanel = null;
	$proxywatermark = null;
	$proxyscrolltext = null;
	$proxydownload = null;
	if(count($proxy_downloads) > 0)
	{
		$proxylivepanel = $proxy_downloads[0][4];
		$proxyscrolltext = $proxy_downloads[0][9];
		$proxydownload = $proxy_downloads[0][6];
		if(isset($proxy_downloads[0][8]))
			$proxywatermark = $proxy_downloads[0][8];
	}
	/*
	0---mac
	1---cpuid
	2---showtime
	3---contact
	4---param0
	5---param1
	6---param2
	7---param3
	8---param4
	*/
	
	/*
	$mytable = "custom_two_table";
	$sql->create_table($mydb, $mytable, "mac text,cpuid text,showtime text,contact text,param0 text,param1 text,param2 text,param3 text,param4 text");
	$custom_twos = $sql->fetch_datas_where_2($mydb, $mytable,"mac",$_GET["mac"],"cpuid",$_GET["cpuid"]);
	//$value = $sql->query_data_2($mydb, $mytable,"mac",$_GET["mac"],"cpuid",$_GET["cpuid"],"param1");
	//$panal = 1;
	*/
	
	if($proxylivepanel == null || strcmp($proxylivepanel,"-1") == 0 || strcmp($proxylivepanel,"0") == 0)
	{	
		$value = $custom_panal;
		
		if(intval($value) == 0)
		{
			if($live_panal != null)
				echo "window.Authentication.CTCSetLivePanal(" . intval($live_panal) . ");";
			else
				echo "window.Authentication.CTCSetLivePanal(" . 1 . ");";
		}
		else if(intval($value) == 1)
		{
			$live_panal = 5;
			echo "window.Authentication.CTCSetLivePanal(" . $live_panal . ");";
		}
		else if(intval($value) == 2)
		{
			$live_panal = 6;
			echo "window.Authentication.CTCSetLivePanal(" . $live_panal . ");";
		}
		
		echo "if(window.Authentication.CTCIsExistsInterface('CTCSetLiveUiType') == true)";
		echo "window.Authentication.CTCSetLiveUiType(" . $liveuitype . ");";
	}
	else
	{
		$value = $custom_panal;
		
		if(intval($value) == 2)
		{
			$live_panal = 6;
			echo "window.Authentication.CTCSetLivePanal(" . $live_panal . ")";
		}
		else
		{
			$live_panal = intval($proxylivepanel);
			echo "window.Authentication.CTCSetLivePanal(" . $live_panal . ");";
		}
		
		echo "if(window.Authentication.CTCIsExistsInterface('CTCSetLiveUiType') == true)";
		echo "window.Authentication.CTCSetLiveUiType(" . $proxylivepanel . ");";
	}

	//$live_panal = intval($proxylivepanel);
	//echo "window.Authentication.CTCSetLivePanal(" . $live_panal . ");";
?>
}



function leftday()
{
	var day = <?php echo $lefttime ?>;
	window.Authentication.CTCSetLastTime(day);
}

function startset()
{
<?php
	$mac="00:00:00:00:00:00";
	if(isset($_GET["mac"]))
		$mac=$_GET["mac"];	
	$cpuid="ERROR";
	if(isset($_GET["cpuid"]))
		$cpuid=$_GET["cpuid"];

	if($startup == "0")
		echo "return 1;";
	else if($startup == "1")
		echo "return 2;";
	else if($startup == "2")
		echo "return 6;";
	else	
	{
		$sepg = null;
		if(count($proxy_downloads) > 0)
			$sepg = $proxy_downloads[0][2];
		
		if($sepg == null || strcmp($sepg,"-1") == 0)
		{
			$mytable = "start_panal_table";
			$sql->create_table($mydb, $mytable,"tag longtext, value longtex");
			$startset = $sql->query_data($mydb, $mytable ,"tag","panal", "value");
			if($live_panal != null)
				echo "return " . $startset . ";";
			else
				echo "return 0;";
		}
		else
		{
			echo "return " . $sepg . ";";
		}
	}
?>
}

function submitPost0()
{
<?php	
	if($live_panal == 0)
	{
		$mytable = "live_table";
		$sql->create_table($mydb, $mytable, "name longtext, value longtext, password longtext, id smallint");
		$namess = $sql->fetch_datas_order_asc($mydb, $mytable, "id");
		$i = 0;
		foreach($namess as $names) 
		{
			echo "window.Authentication.CTCAddUrl(" . $i . ",'" . $names[0] . "','" . $names[1] . "','" . $names[2] . "')" . ";";
			$i++;
		}	
	}
?>
}

<?php
	$live_type_namess = array();
	$live_type_url_count = array();
	$mytable = "live_type_table";
	$sql->create_table($mydb, $mytable, "name longtext, id longtext");	
	$live_type_namess_tmps = $sql->fetch_datas($mydb, $mytable);
	
?>

function submitPost1() 
{
<?php
	/*
	0---name
	1---image
	2---url
	3---password
	4---type
	5---preview
	6---id
	7---urlid
	8---hd
	*/
	$mytable = "live_preview_table";
	$sql->create_table($mydb, $mytable, "name longtext, image longtext, url longtext, password longtext, type longtext, preview longtext, id longtext, urlid smallint, hd longtext, watermark text, lang text");
	$live_preview_namess = $sql->fetch_datas_order_asc($mydb, $mytable,"urlid");
	foreach($live_type_namess_tmps as $live_type_namess_tmp)
	{
		$live_type_namess_count = $sql->count_fetch_datas_where_like($mydb, $mytable,"type",$live_type_namess_tmp[1]);
		//$live_type_namess_count = $sql->fetch_datas_where_like($mydb, $mytable,"type",$live_type_namess_tmp[1]);
		if($live_type_namess_count > 0)
			array_push($live_type_namess,$live_type_namess_tmp);
	}
	
	if($live_panal != 0)
	{
		$allow="allow";	
		$mytable = "custom_close_table";
		$sql->create_table($mydb, $mytable, "allow text, value text");
		$close = $sql->query_data($mydb, $mytable, "allow", $allow, "value");
		if($close == "boxphone")
		{
			if($providersname != null)
				$close = "pre";
			else
				$close = "no";
		}
	
		if($close == "" || $close == "yes")
		{
			$urlid_live = 0;	
			$urlid_playback = 10000;
			foreach($live_preview_namess as $names) 
			{
				$preview = null;
				$date = date('Y-m-d');
				$previews = explode("$#geminipreview#$",$names[5]);
				for($ii=0; $ii<count($previews); $ii++)
				{
					if(strncmp($previews[$ii],$date,strlen($date)) == 0)
					{
						$tmp = explode("$#geminidate#$",$previews[$ii]);
						if(count($tmp) >= 2)
							$preview = $tmp[1];
						break;
					}
				}
				
				$preview = str_replace('"',"~",$preview);
				$image = $names[1];
				if(!file_exists("images/livepic/" . $image))
				{
					$image = null;
				}
			
				if(intval($names[7]) <= 10000)
				{
					if($showid == 1)
						$urlid_live++;
					else
						$urlid_live = $names[7];
					echo "if(window.Authentication.CTCIsExistsInterface('CTCAddUrl2') == true)";
					echo "{";
					echo "window.Authentication.CTCAddUrl2(" . $urlid_live . ",'" . getLangName($lang,$names[0],$names[10]) . "','" . $image . "','" . $names[2] . "','" . $names[3] . "','" . $names[4] . "',\"" . trim($preview) . "\",'" . $names[8] . "','" . $names[6] . "','" . $names[9] . "')" . ";";
					echo "}";
					echo "else{";
					echo "window.Authentication.CTCAddUrl(" . $urlid_live . ",'" . getLangName($lang,$names[0],$names[10]) . "','" . $image . "','" . $names[2] . "','" . $names[3] . "','" . $names[4] . "',\"" . trim($preview) . "\",'" . $names[8] . "','" . $names[6] . "')" . ";";
					echo "}";
				}
				else
				{
					if($showid == 1)
						$urlid_playback++;
					else
						$urlid_playback = intval($names[7]);
					echo "if(window.Authentication.CTCIsExistsInterface('CTCAddPlayback2') == true)";
					echo "window.Authentication.CTCAddPlayback2(" . ($urlid_playback-10000) . ",'" . getLangName($lang,$names[0],$names[10]) . "','" . $image . "','" . $names[2] . "','" . $names[3] . "','" . $names[4] . "',\"" . trim($names[5]) . "\",'" . $names[8] . "','" . $names[6] . "')" . ";";	
					echo "else if(window.Authentication.CTCIsExistsInterface('CTCAddPlayback') == true)";
					echo "window.Authentication.CTCAddPlayback(" . ($urlid_playback-10000) . ",'" . getLangName($lang,$names[0],$names[10]) . "','" . $image . "','" . $names[2] . "','" . $names[3] . "','" . $names[4] . "',\"" . trim($preview) . "\",'" . $names[8] . "','" . $names[6] . "')" . ";";						
				}
			}
		}
		else
		{
			$mac="00:00:00:00:00:00";
			if(isset($_GET["mac"]))
				$mac=$_GET["mac"];	
			$cpuid="ERROR";
			if(isset($_GET["cpuid"]))
				$cpuid=$_GET["cpuid"];	
				
			/*		
			$ipcode="cn";
			if(isset($_GET["ipcode"]))
				$ipcode=strtolower($_GET["ipcode"]);
			*/
				
			$custom_allow = null;
			if(count($custom_tables) > 0)	
				$custom_allow = $custom_tables[0][6];
				
			if($custom_allow == "no" || $custom_allow == null)
			{
				echo "return -1;";
			}
			else
			{
				$playlist_allocation = null;
				if(count($custom_tables) > 0)	
					$playlist_allocation = $custom_tables[0][9];
				
				if(strcmp($playlist_allocation,"all") == 0)
				{
					$ii = 0;	
					$urlid_live = 0;	
					$urlid_playback = 10000;
					foreach($live_preview_namess as $names) 
					{	
						$preview = null;
						$date = date('Y-m-d');
						$previews = explode("$#geminipreview#$",$names[5]);
						for($ii=0; $ii<count($previews); $ii++)
						{
							if(strncmp($previews[$ii],$date,strlen($date)) == 0)
							{
								$tmp = explode("$#geminidate#$",$previews[$ii]);
								if(count($tmp) >= 2)
									$preview = $tmp[1];
								break;
							}
						}
				
						$preview = str_replace('"',"~",$preview);
						$image = $names[1];
						if(!file_exists("images/livepic/" . $image))
						{
							$image = null;
						}
			
						
						if(intval($names[7]) <= 10000)
						{
							if($showid == 1)
								$urlid_live++;
							else
								$urlid_live = $names[7];
						
							//echo "window.Authentication.CTCAddUrl(" . $names[7] . ",'" . $names[0] . "','" . $image . "','" . $names[2] . "','" . $names[3] . "','" . $names[4] . "',\"" . trim($preview) . "\",'" . $names[8] . "','" . $names[6] . "')" . ";";
							echo "if(window.Authentication.CTCIsExistsInterface('CTCAddUrl2') == true)";
							echo "{";
							echo "window.Authentication.CTCAddUrl2(" . $urlid_live . ",'" . getLangName($lang,$names[0],$names[10]) . "','" . $image . "','" . $names[2] . "','" . $names[3] . "','" . $names[4] . "',\"" . trim($preview) . "\",'" . $names[8] . "','" . $names[6] . "','" . $names[9] . "')" . ";";
							echo "}";
							echo "else{";
							echo "window.Authentication.CTCAddUrl(" . $urlid_live . ",'" . getLangName($lang,$names[0],$names[10]) . "','" . $image . "','" . $names[2] . "','" . $names[3] . "','" . $names[4] . "',\"" . trim($preview) . "\",'" . $names[8] . "','" . $names[6] . "')" . ";";
							echo "}";
						}
						else
						{
							if($showid == 1)
								$urlid_playback++;
							else
								$urlid_playback = intval($names[7]);
						
							echo "if(window.Authentication.CTCIsExistsInterface('CTCAddPlayback2') == true)";
							echo "window.Authentication.CTCAddPlayback2(" . ($urlid_playback-10000) . ",'" . getLangName($lang,$names[0],$names[10]) . "','" . $image . "','" . $names[2] . "','" . $names[3] . "','" . $names[4] . "',\"" . trim($names[5]) . "\",'" . $names[8] . "','" . $names[6] . "')" . ";";	
							echo "else if(window.Authentication.CTCIsExistsInterface('CTCAddPlayback') == true)";
							echo "window.Authentication.CTCAddPlayback(" . ($urlid_playback-10000) . ",'" . getLangName($lang,$names[0],$names[10]) . "','" . $image . "','" . $names[2] . "','" . $names[3] . "','" . $names[4] . "',\"" . trim($preview) . "\",'" . $names[8] . "','" . $names[6] . "')" . ";";	
						}
					}
				}
				else
				{
					$playlist_id = "";
					if(strcmp($playlist_allocation,"auto") == 0)
					{
						$mytable = "allocation_table";
						$sql->create_table($mydb, $mytable, "name text, value text");
						if(strcmp($ipcode,"cn") == 0)
						{
							$playlist_id = $sql->query_data($mydb, $mytable,"name", "cn", "value");
						}
						else if(strcmp($ipcode,"hk") == 0)
						{
							$playlist_id = $sql->query_data($mydb, $mytable,"name", "hk", "value");
						}
						else if(strcmp($ipcode,"mo") == 0)
						{
							$playlist_id = $sql->query_data($mydb, $mytable,"name", "mo", "value");
						}
						else if(strcmp($ipcode,"tw") == 0)
						{
							$playlist_id = $sql->query_data($mydb, $mytable,"name", "tw", "value");
						}
						else if(strcmp($ipcode,"us") == 0)
						{
							$playlist_id = $sql->query_data($mydb, $mytable,"name", "us", "value");
						}
						else
						{
							$playlist_id = $sql->query_data($mydb, $mytable,"name", "us", "value");
						}
						
						$mytable = "custom_table";
						$sql->create_table($mydb, $mytable, "mac text,cpu text,ip text,space text, date text,time text,allow text, playlist text, online text, allocation text, proxy text, balance float,showtime text,contact text,member text,panal text,number text,ips text");
						$sql->update_data_3($mydb, $mytable,"mac", $mac, "cpu", $cpuid, "playlist",$playlist_id);
					}
					else
					{
						if(count($custom_tables) > 0)
							$playlist_id = $custom_tables[0][7];
					}
					
					$mytable = "playlist_type_table";
					$sql->create_table($mydb, $mytable, "name text, space longtext, id text, playlist longtext");
					$playlistss = $sql->query_data($mydb, $mytable, "id", $playlist_id, "playlist");
					if($showplaylist == 1)
						$showplaylistname = $sql->query_data($mydb, $mytable, "id", $playlist_id, "name");
						
					if($playlistss != null)
					{	
						$urlid_live = 0;	
						$urlid_playback = 10000;
					
						$playlists=explode('|',$playlistss);
						foreach($playlists as $playlist) 
						{
							for($kk=0; $kk<count($live_preview_namess); $kk++)
							{
								if(strcmp($live_preview_namess[$kk][7],$playlist) == 0)
								{
									//$names = $sql->get_row($mydb, $mytable, "urlid", $playlist);
									//if($names == null)
									//	break;
			
									$preview = null;
									$date = date('Y-m-d');
									$previews = explode("$#geminipreview#$",$live_preview_namess[$kk][5]);
									for($ii=0; $ii<count($previews); $ii++)
									{
										if(strncmp($previews[$ii],$date,strlen($date)) == 0)
										{
											$tmp = explode("$#geminidate#$",$previews[$ii]);
											if(count($tmp) >= 2)
												$preview = $tmp[1];
											break;
										}
									}
			
									$preview = str_replace('"',"~",$preview);
									$image = $live_preview_namess[$kk][1];
									if(!file_exists("images/livepic/" . $image))
									{
										$image = null;
									}		
		
									if(intval($live_preview_namess[$kk][7]) <= 10000)
									{
										if($showid == 1)
											$urlid_live++;
										else
											$urlid_live = intval($live_preview_namess[$kk][7]);
								
										echo "if(window.Authentication.CTCIsExistsInterface('CTCAddUrl2') == true)";
										echo "{";
										echo "window.Authentication.CTCAddUrl2(" . $urlid_live . ",'" . getLangName($lang,$live_preview_namess[$kk][0],$live_preview_namess[$kk][10]) . "','" . $image . "','" . $live_preview_namess[$kk][2] . "','" . $live_preview_namess[$kk][3] . "','" . $live_preview_namess[$kk][4] . "',\"" . trim($preview) . "\",'" . $live_preview_namess[$kk][8] . "','" . $live_preview_namess[$kk][6] . "','" . $live_preview_namess[$kk][9] . "')" . ";";
										echo "}";
										echo "else{";
										echo "window.Authentication.CTCAddUrl(" . $urlid_live . ",'" . getLangName($lang,$live_preview_namess[$kk][0],$live_preview_namess[$kk][10]) . "','" . $image . "','" . $live_preview_namess[$kk][2] . "','" . $live_preview_namess[$kk][3] . "','" . $live_preview_namess[$kk][4] . "',\"" . trim($preview) . "\",'" . $live_preview_namess[$kk][8] . "','" . $live_preview_namess[$kk][6] . "')" . ";";		
										echo "}";
									}
									else
									{
										if($showid == 1)
											$urlid_playback++;
										else
											$urlid_playback = intval($live_preview_namess[$kk][7]);
								
										echo "if(window.Authentication.CTCIsExistsInterface('CTCAddPlayback2') == true)";
										echo "window.Authentication.CTCAddPlayback2(" . ($urlid_playback-10000) . ",'" . getLangName($lang,$live_preview_namess[$kk][0],$live_preview_namess[$kk][10]) . "','" . $image . "','" . $live_preview_namess[$kk][2] . "','" . $live_preview_namess[$kk][3] . "','" . $live_preview_namess[$kk][4] . "',\"" . trim($live_preview_namess[$kk][5]) . "\",'" . $live_preview_namess[$kk][8] . "','" . $live_preview_namess[$kk][6] . "')" . ";";
										echo "else if(window.Authentication.CTCIsExistsInterface('CTCAddPlayback') == true)";
										echo "window.Authentication.CTCAddPlayback(" . ($urlid_playback-10000) . ",'" . getLangName($lang,$live_preview_namess[$kk][0],$live_preview_namess[$kk][10]) . "','" . $image . "','" . $live_preview_namess[$kk][2] . "','" . $live_preview_namess[$kk][3] . "','" . $live_preview_namess[$kk][4] . "',\"" . trim($preview) . "\",'" . $live_preview_namess[$kk][8] . "','" . $live_preview_namess[$kk][6] . "')" . ";";	
									}
									break;
								}
							}
						}
					}
					else
					{		
						/*
						foreach($live_preview_namess as $names) 
						{	
							$preview = null;
							$date = date('Y-m-d');
							$previews = explode("$#geminipreview#$",$names[5]);
							for($ii=0; $ii<count($previews); $ii++)
							{
								if(strncmp($previews[$ii],$date,strlen($date)) == 0)
								{
									$tmp = explode("$#geminidate#$",$previews[$ii]);
									if(count($tmp) >= 2)
										$preview = $tmp[1];
									break;
								}
							}
				
							$preview = str_replace('"',"~",$preview);
							$image = $names[1];
							if(!file_exists("images/livepic/" . $image))
							{
								$image = null;
							}
			
							if(intval($names[7]) <= 10000)
							{
								echo "window.Authentication.CTCAddUrl(" . $names[7] . ",'" . $names[0] . "','" . $image . "','" . $names[2] . "','" . $names[3] . "','" . $names[4] . "',\"" . trim($preview) . "\",'" . $names[8] . "','" . $names[6] . "')" . ";";
							}
							else
							{
								echo "if(window.Authentication.CTCIsExistsInterface('CTCAddPlayback2') == true)";
								echo "window.Authentication.CTCAddPlayback2(" . (intval($names[7])-10000) . ",'" . $names[0] . "','" . $image . "','" . $names[2] . "','" . $names[3] . "','" . $names[4] . "',\"" . trim($names[5]) . "\",'" . $names[8] . "','" . $names[6] . "')" . ";";	
								echo "else if(window.Authentication.CTCIsExistsInterface('CTCAddPlayback') == true)";
								echo "window.Authentication.CTCAddPlayback(" . (intval($names[7])-10000) . ",'" . $names[0] . "','" . $image . "','" . $names[2] . "','" . $names[3] . "','" . $names[4] . "',\"" . trim($preview) . "\",'" . $names[8] . "','" . $names[6] . "')" . ";";	
							}
						}
						*/		
					}
				}
				echo "return 0;";
			}
		}
	}
?>
}

function submitPost2()
{
<?php
	foreach($live_preview_namess as $names) 
	{
		if(intval($names[7]) <= 10000)
		{
			continue;
		}
			
			
		$preview = null;
		$date = date('Y-m-d');
		$previews = explode("$#geminipreview#$",$names[5]);
		for($ii=0; $ii<count($previews); $ii++)
		{
			if(strncmp($previews[$ii],$date,strlen($date)) == 0)
			{
				$tmp = explode("$#geminidate#$",$previews[$ii]);
				if(count($tmp) >= 2)
					$preview = $tmp[1];
				break;
			}
		}
				
		$preview = str_replace('"',"~",$preview);
		$image = $names[1];
		if(!file_exists("images/livepic/" . $image))
		{
			$image = null;
		}
		echo "if(window.Authentication.CTCIsExistsInterface('CTCAddPlayback2') == true)";
		echo "window.Authentication.CTCAddPlayback2(" . (intval($names[7])-10000) . ",'" . getLangName($lang,$names[0],$names[10]) . "','" . $image . "','" . $names[2] . "','" . $names[3] . "','" . $names[4] . "',\"" . trim($names[5]) . "\",'" . $names[8] . "','" . $names[6] . "')" . ";";
		echo "else if(window.Authentication.CTCIsExistsInterface('CTCAddPlayback') == true)";
		echo "window.Authentication.CTCAddPlayback(" . (intval($names[7])-10000) . ",'" . getLangName($lang,$names[0],$names[10]) . "','" . $image . "','" . $names[2] . "','" . $names[3] . "','" . $names[4] . "',\"" . trim($preview) . "\",'" . $names[8] . "','" . $names[6] . "')" . ";";
	}
?>	
	return 0;
}

function submitType()
{
<?php
	if($live_panal != 0)
	{
		//$type_array = array();
		/*
		$mytable = "live_type_table";
		$sql->create_table($mydb, $mytable, "name longtext, id longtext, lang text");	
		$live_type_namess = $sql->fetch_datas($mydb, $mytable);
		*/
		
		$mytable = "live_type_table2";
		$sql->create_table($mydb, $mytable, "id text, need int, typepassword text, param0 text, param1 text, param2 text, param3 text");
		$live_type2_namess = $sql->fetch_datas($mydb, $mytable);
		$typepassword = null;
		
		foreach($live_type_namess as $names) 
		{
			echo "window.Authentication.CTCAddType(\"" .  $names[1] . "\",\"" . getLangName($lang,$names[0],$names[2]) . "\");";
		
			for($ii=0; $ii<count($live_type2_namess); $ii++)
			{
				
				if(strcmp($live_type2_namess[$ii][0],$names[1]) == 0)
				{
					$need = $live_type2_namess[$ii][1];
					$password = $live_type2_namess[$ii][2];
					if($need == 1)
						$typepassword = $password;
					
					echo "if(window.Authentication.CTCIsExistsInterface('CTCAddType2') == true)";
					echo "window.Authentication.CTCAddType2('".$names[1]."','".$need."','".$password."');";			
					break;
				}
			}
		}

		echo "if(window.Authentication.CTCIsExistsInterface('CTCSetType2Pass') == true)";
		echo "window.Authentication.CTCSetType2Pass('".$typepassword."');";
	}
?>
}

function submitPlaybackType()
{
<?php
	if($live_panal != 0)
	{
		$playback_type_namess = array();
		//$playback_type_url_count = array();
	
		$mytable = "playback_type_table";
		$sql->create_table($mydb, $mytable, "name text, id text ,need int, typepassword text, param0 text, param1 text, param2 text, param3 text, lang text");	
		$playback_type_namess_tmps = $sql->fetch_datas($mydb, $mytable);
		
		$mytable = "live_preview_table";
		$sql->create_table($mydb, $mytable, "name longtext, image longtext, url longtext, password longtext, type longtext, preview longtext, id longtext, urlid smallint, hd longtext, watermark text, lang text");
		//$live_preview_namess = $sql->fetch_datas_order_asc($mydb, $mytable,"urlid");
		
		foreach($playback_type_namess_tmps as $playback_type_namess_tmp)
		{
			$playback_type_namess_count = $sql->count_fetch_datas_where_like($mydb, $mytable,"type",$playback_type_namess_tmp[1]);
			if($playback_type_namess_count > 0)
				array_push($playback_type_namess,$playback_type_namess_tmp);
		}
	
		foreach($playback_type_namess as $names) 
		{
			echo "if(window.Authentication.CTCIsExistsInterface('CTCAddPlaybackType') == true)";
			echo "window.Authentication.CTCAddPlaybackType(\"" .  $names[1] . "\",\"" . $names[2] . "\",\"" . $names[3] . "\",\"" . "null" . "\",\"" . getLangName($lang,$names[0],$names[8]) . "\");";
		}
	}
?>
}


function submitProxyName()
{
<?php
	$mytable = "version_table";
	$sql->create_table($mydb, $mytable, "name longtext, value longtext");
	$versions = $sql->fetch_datas($mydb, $mytable);
	
	$expire = 0;
	$expire_text = "";
	$zone_time = "";
	$expire_times = 10;
	$proxy = null;
	if(count($versions) > 0)
	{
		for($ii=0; $ii<count($versions); $ii++)
		{
			if(strcmp($versions[$ii][0],"proxy") == 0)
				$proxy = $versions[$ii][1];
				
			if(strcmp($versions[$ii][0],"expire") == 0)
				$expire = $versions[$ii][1];
				
			if(strcmp($versions[$ii][0],"expiretext") == 0)
				$expire_text = $versions[$ii][1];	
				
			/*
			if(strcmp($versions[$ii][0],"expiretext") == 0)
				$expire_text = $versions[$ii][1];	
			*/
				
			if(strcmp($versions[$ii][0],"expiretimes") == 0)
				$expire_times = intval($versions[$ii][1]);
		}
	}
	echo "window.Authentication.CTCProxyName('" . $proxy . "');";
?>
}

function submitUpdate()
{
<?php
	$mac="00:00:00:00:00:00";
	if(isset($_GET["mac"]))
		$mac=$_GET["mac"];	
	$cpuid="ERROR";
	if(isset($_GET["cpuid"]))
		$cpuid=$_GET["cpuid"];

	$download = null;
	if(count($proxy_downloads) > 0)
		$download = $proxy_downloads[0][6];
	
	if($download == null || strlen($download) <= 7)	
	{
		$update_addr = null;
		if(count($versions) > 0)
		{
			for($ii=0; $ii<count($versions); $ii++)
			{
				if(strcmp($versions[$ii][0],"addr") == 0)
					$update_addr = $versions[$ii][1];
			}
		}
		if($update_addr != null && strlen($update_addr) > 7)
		{ 
			echo "if(window.Authentication)";
			echo "window.Authentication.CTCSetConfig('update','" . $update_addr . "');";
		}
	}
	else
	{
		echo "if(window.Authentication)";
		echo "window.Authentication.CTCSetConfig('update','" . $download . "');";
	}
?>
}

function submitScrollText()
{
<?php
	if(strlen($scrollcontent) > 8)
	{
		$scrollcontent = trim(str_replace(array("/r/n", "/r", "/n"), "", $scrollcontent));
		echo "window.Authentication.CTCSetScrollText('" . $scrollcontent . "');";
	}
	else if(strlen($proxyscrolltext) > 8)
	{
		$proxyscrolltext = trim(str_replace(array("/r/n", "/r", "/n"), "", $proxyscrolltext));
		echo "window.Authentication.CTCSetScrollText('" . $proxyscrolltext . "');";
	}
	else
	{
		$mytable = "scroll_table";
		$sql->create_table($mydb, $mytable, "name longtext, value longtext");
		$scroll_text = $sql->query_data($mydb, $mytable, "name", "scroll_text", "value");
		if($scroll_text == null) { 
			$scroll_text = ""; 
		}
		$scroll_text = trim(str_replace(array("/r/n", "/r", "/n"), "", $scroll_text));
		echo "window.Authentication.CTCSetScrollText('" . $scroll_text . "');";
	}

	if($custom_tables[0][5] != null && $custom_tables[0][4] != "-1" && $custom_tables[0][5] != "-1" && (getChaBetweenTwoDate($custom_tables[0][5],date('Y-m-d',time())) <= 3))
	{
		if($expire == null) $expire = 0;
		if($expire_text == null) $expire_text = "";
	
		if(intval($expire) == 1 && strlen($expire_text) > 2)
		{
			echo "window.Authentication.CTCSetOneScroll('" . $expire_text . "'," . $expire_times .");";
		}
	}
?>	
}


function submitScrollAdPic()
{
<?php
	$mytable = "adpic_table";
	$sql->create_table($mydb, $mytable, "name text, value text");
	$name = $sql->query_data($mydb, $mytable, "name", "adpic" , "value");
	if($name == null) { 
		$$name = ""; 
	}
	echo "window.Authentication.CTCSetScrollAdPic('" . $name . "');";
	
?>		
}

function submitScrollEveryScrollText()
{
<?php
	/*
	$mytable = "scroll_every_close_table";
    $sql->create_table($mydb, $mytable, "close text,value text");
	$scroll_every_close = $sql->query_data($mydb, $mytable,"close","close","value");
	if($scroll_every_close == "no")
	{
		echo "window.Authentication.CTCSetEveryScrollText(1);";
	}
	*/
?>
}

function submitScrollEveryVideo()
{
<?php
	/*
	$video_every_close = $sql->query_data($mydb, $mytable,"close","close_video","value");
	if($video_every_close == "no")
	{
		echo "if(window.Authentication.CTCIsExistsInterface('CTCSetEveryVideo') == true)";
		echo "window.Authentication.CTCSetEveryVideo(1);";
	}
	else
	{
		echo "if(window.Authentication.CTCIsExistsInterface('CTCSetEveryVideo') == true)";
		echo "window.Authentication.CTCSetEveryVideo(0);";
	}
	*/
?>
}

function setShowLefttime()
{
<?php
	if(count($custom_tables) > 0)
	{
		$showtime = $custom_tables[0][12];
		if(strcmp($showtime,"null") != 0)
		{
			if(strcmp($showtime,"yes") == 0)
				echo "window.Authentication.CTCSetShowLefttime(1);";
			else if(strcmp($showtime,"no") == 0)
				echo "window.Authentication.CTCSetShowLefttime(0);";
			else
				echo "window.Authentication.CTCSetShowLefttime(1);";
		}
		else 
			echo "window.Authentication.CTCSetShowLefttime(1);";
	}
?>
}

function setContact()
{
<?php
	if(count($custom_tables) > 0)
	{
	$contact = $custom_tables[0][13];
	if(strcmp($contact,"null") != 0 && strlen($contact) > 1)
	{
		echo "window.Authentication.CTCSetContact('" . $contact . "');";
	}
	}
?>
}

function setEpgBackground()
{
<?php
	$ebackground = "";
	if(count($proxy_downloads) > 0)
		$ebackground = $proxy_downloads[0][3];
	if($ebackground == null || strcmp($ebackground,"0") == 0)
	{
		$mytable = "start_epg_table";
		$sql->create_table($mydb, $mytable, "tag text, value text");
		$background = $sql->query_data($mydb, $mytable, "tag", "background","value");
		if($background != null && strlen($background) > 4)
		{
			$backgroundss = explode("|",$background);
			if(count($backgroundss) > 0)
			{
				$background_index = time()%count($backgroundss);
				if(strlen($backgroundss[$background_index]) > 4)
				{
					echo "if(window.Authentication.CTCIsExistsInterface('CTCSetEpgBackground2') == true)";
					echo "window.Authentication.CTCSetEpgBackground2('" . $backgroundss[$background_index] . "','" . md5_file("images/background/". $backgroundss[$background_index]) . "');";
					echo "else ";
					echo "window.Authentication.CTCSetEpgBackground('" . $backgroundss[$background_index] . "');";
				}
				else
				{
					echo "if(window.Authentication.CTCIsExistsInterface('CTCSetEpgBackground2') == true)";
					echo "window.Authentication.CTCSetEpgBackground2('" . $background . "','" . md5_file("images/background/". $background) . "');";
					echo "else ";
					echo "window.Authentication.CTCSetEpgBackground('" . $background . "');";
				}
			}
			else
			{
				echo "if(window.Authentication.CTCIsExistsInterface('CTCSetEpgBackground2') == true)";
				echo "window.Authentication.CTCSetEpgBackground2('" . $background . "','" . md5_file("images/background/". $background) . "');";
				echo "else ";
				echo "window.Authentication.CTCSetEpgBackground('" . $background . "');";
			}
		}
	}
	else
	{
		if(strlen($ebackground) > 4)
		{
			$ebackgroundss = explode("|",$ebackground);
			if(count($ebackgroundss) > 0)
			{
				$ebackground_index = time()%count($ebackgroundss);
				if(strlen($ebackgroundss[$ebackground_index]) > 4)
				{
					echo "if(window.Authentication.CTCIsExistsInterface('CTCSetEpgBackground2') == true)";
					echo "window.Authentication.CTCSetEpgBackground2('" . $ebackgroundss[$ebackground_index] . "','" . md5_file("images/background/". $ebackgroundss[$ebackground_index]) . "');";
					echo "else ";
					echo "window.Authentication.CTCSetEpgBackground('" . $ebackgroundss[$ebackground_index] . "');";
				}
				else
				{
					echo "if(window.Authentication.CTCIsExistsInterface('CTCSetEpgBackground2') == true)";
					echo "window.Authentication.CTCSetEpgBackground2('" . $ebackground . "','" . md5_file("images/background/". $ebackground) . "');";
					echo "else ";
					echo "window.Authentication.CTCSetEpgBackground('" . $ebackground . "');";
				}
			}
			else
			{
				echo "if(window.Authentication.CTCIsExistsInterface('CTCSetEpgBackground2') == true)";
				echo "window.Authentication.CTCSetEpgBackground2('" . $ebackground . "','" . md5_file("images/background/". $ebackground) . "');";
				echo "else ";
				echo "window.Authentication.CTCSetEpgBackground('" . $ebackground . "');";
			}
		}
		
		//echo "window.Authentication.CTCSetEpgBackground('" . $ebackground . "');";
	}
?>
}
<?php
	$mytable = "start_epg_table";
	$sql->create_table($mydb, $mytable, "tag text, value text");
	$style = $sql->query_data($mydb, $mytable, "tag", "style","value");
	$epglist = $sql->query_data($mydb, $mytable, "tag", "epglist","value");
?>

function setEpgStyle()
{
<?php

	if($style == null)
		$style = "live|vod|back|setting|exit";
	echo "if(window.Authentication.CTCIsExistsInterface('CTCSetEpgStyle') == true)";
	echo "window.Authentication.CTCSetEpgStyle('" . $style . "');";
?>	
}

function setEpgList()
{
<?php
	echo "if(window.Authentication.CTCIsExistsInterface('CTCSetEpgList') == true)";
	echo "window.Authentication.CTCSetEpgList('" . $epglist . "');";
?>	
}

function setBroadcast()
{
<?php
	$mytable = "start_broadcast_table";
	$sql->create_table($mydb, $mytable, "tag text, value text");
	$broadcast = $sql->query_data($mydb, $mytable, "tag", "broadcast","value");
	if($broadcast != null && strlen($broadcast) > 4)
	{
		echo "window.Authentication.CTCSetBroadcast('" . $broadcast . "');";
	}
?>
}



function setLoadBackground()
{
<?php
	$sbackground = "";
	if(count($proxy_downloads) > 0)
		$sbackground = $proxy_downloads[0][1];
	
	$mytable = "start_load_table";
	$sql->create_table($mydb, $mytable, "tag text, value text");
	$aacountbackground = $sql->query_data($mydb, $mytable, "tag", "aacount","value");
	
	if($sbackground == null || strcmp($sbackground,"0") == 0)
	{
		$background = $sql->query_data($mydb, $mytable, "tag", "background","value");
		if($background != null && strlen($background) > 4)
		{
			echo "if(window.Authentication.CTCIsExistsInterface('CTCSetLoadingImage2') == true)";
			echo "window.Authentication.CTCSetLoadingImage2('" . $background . "','" . md5_file("images/background/". $background) . "');";
			echo "else if(window.Authentication.CTCIsExistsInterface('CTCSetLoadingImage') == true)";
			echo "window.Authentication.CTCSetLoadingImage('" . $background . "');";
		}
		else
		{
			echo "if(window.Authentication.CTCIsExistsInterface('CTCSetLoadingImage') == true)";
			echo "window.Authentication.CTCSetLoadingImage('null');";
		}
	}
	else
	{
		echo "if(window.Authentication.CTCIsExistsInterface('CTCSetLoadingImage2') == true)";
		echo "window.Authentication.CTCSetLoadingImage2('" . $background . "','" . md5_file("images/background/". $background) . "');";
		echo "else if(window.Authentication.CTCIsExistsInterface('CTCSetLoadingImage') == true)";
		echo "window.Authentication.CTCSetLoadingImage('" . $sbackground . "');";
	}
	
	{
		if($aacountbackground != null && strlen($aacountbackground) > 4)
		{
			echo "if(window.Authentication.CTCIsExistsInterface('CTCSetLoadingAccountImage2') == true)";
			echo "window.Authentication.CTCSetLoadingAccountImage2('" . $aacountbackground . "','" . md5_file("images/background/". $aacountbackground) . "');";
			echo "if(window.Authentication.CTCIsExistsInterface('CTCSetLoadingAccountImage') == true)";
			echo "window.Authentication.CTCSetLoadingAccountImage('" . $aacountbackground . "');";
		}
		else
		{
			echo "if(window.Authentication.CTCIsExistsInterface('CTCSetLoadingAccountImage') == true)";
			echo "window.Authentication.CTCSetLoadingAccountImage('null');";
		}
	}
?>	
}

function setTimeNow()
{
<?php
	date_default_timezone_set('UTC');
	echo "if(window.Authentication.CTCIsExistsInterface('CTCSetTimeNow') == true)";
	echo "window.Authentication.CTCSetTimeNow('" . date("Y-m-d H:i:s") . "');";	

	echo "if(window.Authentication.CTCIsExistsInterface('CTCSetTimeZonePRC') == true)";
	echo "window.Authentication.CTCSetTimeZonePRC('" . $zone . "');";	
	
	date_default_timezone_set($zone);
	echo "if(window.Authentication.CTCIsExistsInterface('CTCSetTimeNowPRC') == true)";
	echo "window.Authentication.CTCSetTimeNowPRC('" . date("Y-m-d H:i:s") . "');";	
?>
}

function setMember()
{
<?php
	if(count($custom_tables) > 0)
	{
		$member = $custom_tables[0][14];
		echo "if(window.Authentication.CTCIsExistsInterface('CTCSetMember') == true)";
		echo "window.Authentication.CTCSetMember('" . $member . "');";	
	}
?>	
}

function setWifiAP()
{
<?php
	/*
	$mytable = "wifi_ap_table";
	$sql->create_table($mydb, $mytable, "apid smallint,state text,ssid text,ps text,isshow text");
	$wifi_aps = $sql->fetch_datas_where($mydb, $mytable,"apid",0);
	$state = null;
	$ssid = null;
	$ps = null;
	$isshow = null;
	if(count($wifi_aps) > 0)
	{
		$state = $wifi_aps[0][1];
		$ssid = $wifi_aps[0][2];
		$ps = $wifi_aps[0][3];
		$isshow = $wifi_aps[0][4];
	}
	
	if($state != null && $ssid != null && $ps != null && $isshow != null)
	{
		echo "if(window.Authentication.CTCIsExistsInterface('CTCSetAP') == true)";
		echo "window.Authentication.CTCSetAP('" . $ssid . "','" . $ps . "'," . $state . "," . $isshow . ");";	
	}
	*/
?>	
}

function setDownload()
{
<?php $mac="00:00:00:00:00:00";
	if(isset($_GET["mac"]))
		$mac=$_GET["mac"];	
	$cpuid="ERROR";
	if(isset($_GET["cpuid"]))
		$cpuid=$_GET["cpuid"];
		
	$mytable = "custom_download_table";
	$sql->create_table($mydb, $mytable, "mac text,cpu text,tip text,url text,date text,state text,param text");	
	$custom_downloads = $sql->fetch_datas_where_2($mydb, $mytable, "mac", $mac, "cpu", $cpuid);
	
	$download_date = null;
	if(count($custom_downloads) > 0)
		$download_date = $custom_downloads[0][5];
		
	if($download_date != null)
	{
		if(count($custom_downloads) > 0)
		{
			$download_tip = $custom_downloads[0][2];
			$download_url = $custom_downloads[0][3];
		}
		echo "if(window.Authentication.CTCIsExistsInterface('CTCSetDownload') == true)";
		echo "window.Authentication.CTCSetDownload('" . $download_date. "','" . $download_tip . "','" . $download_url . "');";	
	}
?>
}

function setOneScroll()
{
<?php

	$scroll_text = $custom_tables[0][19];
	if($scroll_text != null && strlen($scroll_text) > 5)
	{
		$scroll_time = $custom_tables[0][20];
		echo "if(window.Authentication.CTCIsExistsInterface('CTCSetOneScroll') == true)";
		echo "window.Authentication.CTCSetOneScroll('" . $scroll_text. "'," . $scroll_time . ");";	
	}
?>	
}

function EnableHlsPlugIn()
{
<?php
	echo "if(window.Authentication.CTCIsExistsInterface('CTCEnableHlsPlugIn') == true)";
	echo "window.Authentication.CTCEnableHlsPlugIn(1);";		
?>
}

function CheckMacIsOk()
{
<?php
	$mac="00:00:00:00:00:00";
	if(isset($_GET["mac"]))
		$mac=$_GET["mac"];
	echo "var webmac = '" . $mac . "';";
?>		
	var machinamac = window.Authentication.CTCGetMac();
	if(webmac != machinamac)
		return 0;
	else
		return 1;
}

function SetUdp()
{
<?php
	$mytable = "safe_table2";
	$sql->create_table($mydb, $mytable, "id text,safe10 text,safe11 text,safe12 text,safe13 text,safe14 text,safe15 text,safe16 text,safe17 text,safe18 text,safe19 text,model text,logintime int, unbundling int, disabel_mode text");
	$safe_table2s = $sql->fetch_datas_where($mydb, $mytable, "id", "0");
	$safe3 = "";
	$safe4 = "";
	$safe5 = "";
	$model = "";
	$disabel_model = "";
	if(count($safe_table2s) > 0)
	{
		$safe3 = $safe_table2s[0][1];
		$safe4 = $safe_table2s[0][2];
		$safe5 = $safe_table2s[0][3];
		$model = $safe_table2s[0][11];
		$disabel_model = $safe_table2s[0][14];
	}
	
	if(strlen($safe3) > 0 && strlen($safe4) > 0)
	{
		echo "if(window.Authentication.CTCIsExistsInterface('CTCSetUdp') == true)";
		echo "window.Authentication.CTCSetUdp('".$safe3."','" . $safe4 . "');";	
	}
	
	if(strlen($safe5) > 7)
	{
		$safe5s = explode("|",$safe5);
		if(count($safe5s) > 1)
		{
			for($ii=0; $ii<count($safe5s); $ii++)
			{
				echo "window.Authentication.CTChttpRequest('" . $safe5s[$ii] . "');";
			}
		}
		else
		{
			echo "window.Authentication.CTChttpRequest('" . $safe5 . "');";
		}
	}
?>	
}

	
function SetModel()
{
<?php 
	if(strlen($limitmodel) > 1)
	{
		if(strcmp(strtolower($limitmodel),strtolower($_GET["model"])) != 0)
		{
			$sql->disconnect_database();
			echo "alert('Model Error')";
			header("Location: error.php?version=" . $version . "&key=" . $key . "&error=6");
			exit;
		}
	}
	
	if(isset($_GET["model"]) && strlen($disabel_model) > 2)
	{
		if(strstr(strtolower($disabel_model),strtolower($_GET["model"])) != FALSE)
		{
			$sql->disconnect_database();
			echo "alert('Model Error')";
			header("Location: error.php?version=" . $version . "&key=" . $key . "&error=7");
			exit;
		}
	}
	else if(isset($_GET["model"]) && strlen($model) > 2)
	{
		if(strstr(strtolower($model),strtolower($_GET["model"])) == FALSE)
		{
			$sql->disconnect_database();
			echo "alert('Model Error')";
			header("Location: error.php?version=" . $version . "&key=" . $key . "&error=8");
			exit;
		}
	}
?>
}

function SetNumber()
{
<?php
	echo "if(window.Authentication.CTCIsExistsInterface('CTCSetNumber') == true)";
	echo "window.Authentication.CTCSetNumber('".$custom_tables[0][16]."');";	
?>	
}

function setCheckTimeout()
{
<?php
	echo "if(window.Authentication.CTCIsExistsInterface('CTCCheckTimeout') == true)";
	echo "window.Authentication.CTCCheckTimeout(24*3600);";
?>	
}

function SetKey()
{
<?php
	$mac="00:00:00:00:00:00";
	if(isset($_GET["mac"]))
		$mac=$_GET["mac"];
	$key1 = base64_encode($mac);
	//echo $key1;	
	$playlistkey = $g->j1($key1);
	echo "if(window.Authentication.CTCIsExistsInterface('CTCKey') == true)";
	echo "{";
	echo "if(window.Authentication.CTCKey('".$playlistkey."') == 0)";
	if(isset($lang_array["error_text13"]))
		echo "alert('" . $lang_array["error_text13"] . "')";	
	else
		echo "alert('KEY ERROR')";
	echo "}";
?>	
}


function SetVodColumn()
{
<?php
	$mytable = "vod_name_table";
	$sql->create_table($mydb, $mytable, "id smallint,name text, needps int, password text");
	$names = $sql->fetch_datas($mydb, $mytable);
	$column0 = "0|電影|0|123456";
	$column1 = "1|電視劇|0|123456";
	$column2 = "2|綜藝|0|123456";
	$column3 = "3|動漫|0|123456";
	foreach($names as $name)
	{
		if($name[0] == 0)
			$column0 = $name[0]."|".$name[1]."|".$name[2]."|".$name[3];
		else if($name[0] == 1)
			$column1 = $name[0]."|".$name[1]."|".$name[2]."|".$name[3];
		else if($name[0] == 2)
			$column2 = $name[0]."|".$name[1]."|".$name[2]."|".$name[3];
		else if($name[0] == 3)
			$column3 = $name[0]."|".$name[1]."|".$name[2]."|".$name[3];	
	}
	
	$mytable = "vod_type_table_0";
	$sql->create_table($mydb, $mytable, "value longtext, id smallint");	
	$names = $sql->fetch_datas($mydb, $mytable);
	$column_type0 = "";
	foreach($names as $name)
	{
		if($name[1] == 0)
		{
			$column_type0 = $column_type0 . "@type#" . $name[0];	
		}
		else if($name[1] == 1)
		{
			$column_type0 = $column_type0 . "@year#" . $name[0];		
		}
		else if($name[1] == 2)
		{
			$column_type0 = $column_type0 . "@area#" . $name[0];		
		}
	}
	
	$mytable = "vod_type_table_1";
	$sql->create_table($mydb, $mytable, "value longtext, id smallint");	
	$names = $sql->fetch_datas($mydb, $mytable);
	$column_type1 = "";
	foreach($names as $name)
	{
		if($name[1] == 0)
		{
			$column_type1 = $column_type1 . "@type#" . $name[0];	
		}
		else if($name[1] == 1)
		{
			$column_type1 = $column_type1 . "@year#" . $name[0];		
		}
		else if($name[1] == 2)
		{
			$column_type1 = $column_type1 . "@area#" . $name[0];		
		}
	}
	
	$mytable = "vod_type_table_2";
	$sql->create_table($mydb, $mytable, "value longtext, id smallint");	
	$names = $sql->fetch_datas($mydb, $mytable);
	$column_type2 = "";
	foreach($names as $name)
	{
		if($name[1] == 0)
		{
			$column_type2 = $column_type2 . "@type#" . $name[0];	
		}
		else if($name[1] == 1)
		{
			$column_type2 = $column_type2 . "@year#" . $name[0];		
		}
		else if($name[1] == 2)
		{
			$column_type2 = $column_type2 . "@area#" . $name[0];		
		}
	}
	
	$mytable = "vod_type_table_3";
	$sql->create_table($mydb, $mytable, "value longtext, id smallint");	
	$names = $sql->fetch_datas($mydb, $mytable);
	$column_type3 = "";
	foreach($names as $name)
	{
		if($name[1] == 0)
		{
			$column_type3 = $column_type3 . "@type#" . $name[0];	
		}
		else if($name[1] == 1)
		{
			$column_type3 = $column_type3 . "@year#" . $name[0];		
		}
		else if($name[1] == 2)
		{
			$column_type3 = $column_type3 . "@area#" . $name[0];		
		}
	}
	
	$value = $column0 . $column_type0 . "&"  . $column1 . $column_type1 . "&" . $column2 . $column_type2 . "&"
				. $column3 . $column_type3;
				
	if(strlen($value) > 8)
	{
		echo "if(window.Authentication.CTCIsExistsInterface('CTCVodSetColumn') == true)";
		echo "window.Authentication.CTCVodSetColumn('" . $value . "');";
	}
?>
}

function SetLiveWatermark()
{
<?php

	if($proxywatermark != null && strlen($proxywatermark) > 8)
	{
		echo "if(window.Authentication.CTCIsExistsInterface('CTCLiveWatermark') == true)";
		echo "window.Authentication.CTCLiveWatermark('" . $proxywatermark . "');";
	}
	else if(strlen($watermark) > 8)
	{
		echo "if(window.Authentication.CTCIsExistsInterface('CTCLiveWatermark') == true)";
		echo "window.Authentication.CTCLiveWatermark('" . $watermark . "');";
	}
	else
	{
		echo "if(window.Authentication.CTCIsExistsInterface('CTCLiveWatermark') == true)";
		echo "window.Authentication.CTCLiveWatermark('');";		
	}
	
	echo "if(window.Authentication.CTCIsExistsInterface('CTCLiveWatermarkSite2') == true)";
	echo "window.Authentication.CTCLiveWatermarkSite2(" . $watermarksite . "," . $watermarkdip1 . "," . $watermarkdip2 . ");";
	echo "else if(window.Authentication.CTCIsExistsInterface('CTCLiveWatermarkSite') == true)";
	echo "window.Authentication.CTCLiveWatermarkSite(" . $watermarksite . ");";

?>
}


function SetLiveLeftRight()
{
<?php
	echo "if(window.Authentication.CTCIsExistsInterface('CTCLivePlayLeftRight') == true)";
	echo "window.Authentication.CTCLivePlayLeftRight(" . $lrkey . ");";		
?>	
}

function SetLiveScrollShow()
{
<?php
	echo "if(window.Authentication.CTCIsExistsInterface('CTCLivePlayShowScroll2') == true)";
	echo "window.Authentication.CTCLivePlayShowScroll2(" . $showscroll . "," . $showscrolltimes . ");";	 
	echo "else if(window.Authentication.CTCIsExistsInterface('CTCLivePlayShowScroll') == true)";
	echo "window.Authentication.CTCLivePlayShowScroll(" . $showscroll . ");";		
?>	
}

function SetLivePlaylistIcon()
{
<?php
	echo "if(window.Authentication.CTCIsExistsInterface('CTCLivePlaylistIcon') == true)";
	echo "window.Authentication.CTCLivePlaylistIcon(" . $showicon . ");";		
?>	
}

function SetAdLiveImage()
{
<?php
	if($adliveimage == 1)
	{
		
		echo "if(window.Authentication.CTCIsExistsInterface('CTCSetAdLiveImageSite') == true)";
		echo "window.Authentication.CTCSetAdLiveImageSite(" . $adliveimagesite . ");";

		$mytable = "ad_live_list_table";
		$sql->create_table($mydb, $mytable, "id int, image text");
		$namess = $sql->fetch_datas($mydb, $mytable);
		echo "if(window.Authentication.CTCIsExistsInterface('CTCSetAdLiveImage') == true)";
		echo "{";
		foreach($namess as $names) {
			echo "window.Authentication.CTCSetAdLiveImage('" . $names[1] . "');";
		}
		echo "}";
	}
?>	
}

function SetVodCount()
{
<?php $vod_count_0 = 0;
	$vod_count_1 = 0;
	$vod_count_2 = 0;
	$vod_count_3 = 0;
	for($ii=0; $ii<4; $ii++)
	{
		$mytable = "vod_table_".$ii;
		$sql->create_table($mydb, $mytable, "name text, image text, url text, area text, year text, 
			type text, intro1 longtext, intro2 longtext, intro3 longtext, intro4 longtext,
			id int, clickrate int, recommend tinyint, chage float, updatetime int, 
			firstletter text");	
		//$namess = $sql->fetch_datas($mydb, $mytable);
		$namess_count = $sql->count_fetch_datas($mydb, $mytable);
		if($ii == 0) $vod_count_0 = $namess_count;	
		else if($ii == 1) $vod_count_1 = $namess_count;	
		else if($ii == 2) $vod_count_2 = $namess_count;	
		else if($ii == 3) $vod_count_3 = $namess_count;	
	}
	echo "if(window.Authentication.CTCIsExistsInterface('CTCVodCount') == true)";
	echo "window.Authentication.CTCVodCount(" . $vod_count_0 . "," . $vod_count_1 . "," . $vod_count_2 . "," . $vod_count_3 .");";
?>
}

function SetShowLivePlaylist()
{
<?php
	if($showplaylist == 1 && $showplaylistname != null)
	{
		echo "if(window.Authentication.CTCIsExistsInterface('CTCSetShowLivePlaylist') == true)";
		echo "window.Authentication.CTCSetShowLivePlaylist('" . $showplaylistname . "');";		
	}
?>		
}

function SetIP()
{
<?php	
	echo "if(window.Authentication.CTCIsExistsInterface('CTCSetIP') == true)";
	echo "window.Authentication.CTCSetIP('" . $ip . "');";			
?>	
}
<?php
	date_default_timezone_set($zone);
	$timers = 0;
	$mytable = "custom_tree_table";
	$sql->create_table($mydb, $mytable, "mac text,cpuid text,date text,timers int");
	$timers = $sql->query_data_2($mydb, $mytable,"mac",$mac,"cpuid",$cpuid,"timers");
	if($timers == null)
	{
		$sql->insert_data($mydb, $mytable, "mac, cpuid, date, timers", $mac.", ".$cpuid.", ".date("Y-m-d").", "."1");
	}
	else
	{
		$date = $sql->query_data_2($mydb, $mytable,"mac",$mac,"cpuid",$cpuid,"date");
		if(strcmp($date,date("Y-m-d")) == 0)
			$timers = $timers + 1;
		else
			$timers = 1;
		
		$sql->update_data_3($mydb, $mytable, "mac", $mac, "cpuid", $cpuid, "date", date("Y-m-d"));
		$sql->update_data_3($mydb, $mytable, "mac", $mac, "cpuid", $cpuid, "timers", $timers);
		
		if(($logintime > 1 && $timers > $logintime) || ($limittimes > 0 && $timers > $limittimes))
		{
			$sql->disconnect_database();
			header("Location: error.php?error=5");
			
			exit;	
			echo "times error". $logintime . "#" . $timers;
		}
	}
?>

<?php
	$sql->disconnect_database();
?>
</script>

<body onLoad="init()">
	<div class="centerDiv">
    </div><div class="hiddenDiv"></div>
</body>
</html>

<?PHP
if(extension_loaded('zlib')){ob_end_flush();}
?>