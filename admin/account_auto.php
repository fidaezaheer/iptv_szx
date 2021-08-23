<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" 
"http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title></title>
<link href="styles/style.css" rel="stylesheet" type="text/css" />
<body>

<script src="/libs/cvi_busy_lib.js"></script>
<script type="text/javascript">
	var xval=getBusyOverlay('viewport',{color:'white', opacity:0.75, text:'viewport: loading...', style:'text-shadow: 0 0 3px black;font-weight:bold;font-size:16px;color:white'},{color:'#ff0', size:100, type:'o'});
	$.ajax({
		url : '${ctx}/login/tologin',
		type : 'POST',
		dataType : 'json',
		beforeSend:function(){
			if(xval) {
				xval.settext("正在登录，请稍后......");
			}
		},
		success:function(result) {
			xval.remove();
			if (result.success) {
				document.location = "${ctx}/";
			}
		}
	});
</script>

<?php
function getChaBetweenTwoDate($date1,$date2){
	$Date_List_a1=explode("-",$date1);
	$Date_List_a2=explode("-",$date2);
	$d1=mktime(0,0,0,$Date_List_a1[1],$Date_List_a1[2],$Date_List_a1[0]);
	$d2=mktime(0,0,0,$Date_List_a2[1],$Date_List_a2[2],$Date_List_a2[0]);
	$Days=round(($d1-$d2)/3600/24);
	return $Days;
}	


function generate_code($length = 4) {
    return rand(pow(10,($length-1)), pow(10,$length)-1);
}

function getIP()
{
	return $_SERVER["REMOTE_ADDR"];
}

?>

<?php
	/*
	include_once 'memcache.php';
	$mem = new GMemCache();
	$timeout = 1;
	if($mem->step1(__FILE__,1) == false)
	{
		echo "<script>setTimeout('window.location.reload()'," . (intval($timeout)+1)*1000 . ");</script>";
		exit;		
	}
	*/
	
	include_once 'common.php';
	include_once 'gemini.php';
	include_once "cn_lang.php";
	
	$sql = new DbSql();
	
	$g = new Gemini();
	
	set_zone();
	
	$mac = $sql->str_safe($_GET["mac"]);
	$cpuid = $sql->str_safe($_GET["cpuid"]);
	$number = $sql->str_safe($_GET["number"]);
	$date = date("Y-m-d");
	$proxy = "admin";
	$content = "";
	$terlang = "zh_cn";
	if(isset($_GET["terlang"]))
		$terlang = $_GET["terlang"];	
	//echo $_COOKIE['vcode'];
	//echo $_GET["inputcode"];
	
	/*
	if(!isset($_COOKIE['randomcode']) || ($_GET["randomcode"] != $_COOKIE['randomcode']))
	{
		//$randomcode = generate_code(6);
		//setcookie("randomcode",$randomcode,time()+3600);
		//header("Location: ../error.php?version=" . $version . "&key=" . $key . "&error=12&randomcode=" . $randomcode);
		echo "<script>alert('ERROR CODE 13')</script>";
		exit;
	}
	
	setcookie("randomcode","",time()-3600);
	*/
	
	$randomcode = "";
	
	$md5_logonum = md5(md5($_GET["inputcode"]));
	if(((isset($_COOKIE['vcode']) && strlen($_COOKIE['vcode']) > 1 && $_COOKIE['vcode'] == $md5_logonum) || 
		(isset($_GET["logonum"]) && strlen($_GET["logonum"]) > 1 && $_GET["logonum"] == $md5_logonum) ||
		(isset($_GET["clogonum"]) && strlen($_GET["clogonum"]) > 1 && $_GET["clogonum"] == $md5_logonum)) == false)		//clogonum 是独立每一个授权码的验证码	logonum 是每一个代理的验证码
	{
		$mac = $sql->str_safe($_GET["mac"]);
		$cpuid = $sql->str_safe($_GET["cpuid"]);
		$number = $sql->str_safe($_GET["number"]);
		$key = $sql->str_safe($_GET["key"]);
		$version = $sql->str_safe($_GET["version"]);

		$randomcode = generate_code(6);
		//addRandomcodeXML("../randomcode.xml",md5($_GET["mac"].$_GET["cpuid"]),md5(md5($randomcode)));	
	
		header("Location: ../code.php?error=1&mac=".$mac."&cpuid=".$cpuid."&number=".$number."&key=".$key."&version=".$version."&randomcode=".$randomcode."&terlang=".$terlang);
		//echo "<meta http-equiv=refresh content='0; url='" . "../code.php?error=1&mac=".$mac."&cpuid=".$cpuid."&number=".$number."&key=".$key."&version=".$version . "'>";
		exit;
	}
	
	/*
	$version = 88;
	$key = "";
	if(isset($_GET["version"]) && isset($_GET["key"]))
	{
		$version = $sql->str_safe($_GET["version"]);
		$key = $sql->str_safe($_GET["key"]);	
	}
	check_key($version,$key);
	*/
	
	/*
	$md5_randomcode = getRandomcodeXML("../randomcode.xml",md5($mac.$cpuid));
	if($md5_randomcode == "error" || md5(md5($_GET["randomcode"])) != $md5_randomcode)	
	{
		echo "<script>alert('ACCOUNT ERROR CODE 13')</script>";
		delRandomcodeXML("../randomcode.xml",md5($_GET["mac"].$_GET["cpuid"]));
		exit;
	}
	delRandomcodeXML("../randomcode.xml",md5($_GET["mac"].$_GET["cpuid"]));
	
	$randomcode = generate_code(6);
	addRandomcodeXML("../randomcode.xml",md5($_GET["mac"].$_GET["cpuid"]),md5(md5($randomcode)));	
	*/
	
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
		remarks text");
		
	$machine = $sql->fetch_datas_where($mydb, $mytable,"number",$number);
	
	if(count($machine) == 1)
	{
		$proxy = $machine[0][10];
		$numberdate = $machine[0][20];
		if(time() - $numberdate > 24*3600)
		{
			$allow = $machine[0][6];
			$unbundling = $machine[0][26];
			//echo "allow" . $allow;
			//echo "unbundling" . $unbundling;
			if($allow == "yes" && ($unbundling != null && $unbundling == 0))
			{
				$oldmac = $sql->query_data($mydb, $mytable, "number",$number,"mac");
				$oldcpu = $sql->query_data($mydb, $mytable, "number",$number,"cpu");
				if(strlen($oldmac) < 17)
				{
					echo "<script language='javascript' type='text/javascript'>";
					echo "window.Authentication.CTCLoadWebView();";
					echo "</script>";
					
					$content = "使用无效:" . $mac . "CPUID:" . $cpuid . " 授权码：" . $number;	
				}
				else if(($oldmac==$mac) && ($oldcpu==$cpuid))
				{
					echo "<script language='javascript' type='text/javascript'>";
					echo "window.Authentication.CTCLoadWebView();";
					echo "</script>";
					
					$content = "使用了相同的MAC:" . $mac . "CPUID:" . $cpuid . " 授权码：" . $number;	
				}
				else
				{
					//$member = $sql->query_data($mydb, $mytable, "number",$number,"member");
					
					$new_namess = $sql->fetch_datas_where_2($mydb, $mytable,"mac",$mac,"cpu",$cpuid);
					//if(count($new_namess) >= 1 && $new_namess[0][6] == "no" && $new_namess[0][4] == "null" && $new_namess[0][5] == "null")
					//if(count($new_namess) >= 1 && $new_namess[0][6] == "no")
					if(count($new_namess) >= 1)
					{
						$sql->delete_data_2($mydb, $mytable,"mac",$mac,"cpu",$cpuid);
						$sql->update_data_2($mydb, $mytable, "number",$number, "mac", $mac);
						$sql->update_data_2($mydb, $mytable, "number",$number, "cpu", $cpuid);
						$sql->update_data_2($mydb, $mytable, "number",$number, "numberdate", time());
						$sql->update_data_2($mydb, $mytable, "number",$number, "unbundling", 1);
				
				
						$mytable = "account_table";
						$sql->create_table($mydb, $mytable, "id int, playlist text, days text, proxy text, 
							export int, used int, mac text, cpuid text, cdkey text, showtime text, type text, member text, startime datetime");
						
						$sql->update_data_2($mydb, $mytable, "cdkey", $number, "mac", $mac);
						$sql->update_data_2($mydb, $mytable, "cdkey", $number, "cpuid", $cpuid);
						$sql->update_data_2($mydb, $mytable, "cdkey", $number, "startime",date("Y-m-d H:i:s"));
						
						echo "<script language='javascript' type='text/javascript'>";
						echo "window.Authentication.CTCLoadWebView();";
						echo "</script>";
			
						$content = "取消:" . $oldmac . " " . $mac . "绑定了已使用激活码:" . $number;
					
					}
					else
					{
						echo "<script language='javascript' type='text/javascript'>";
						echo "alert('" . $lang_array["account_auto_text1"] . "');";
						echo "window.location.href='../error.php?" . "mac=" . $mac . "&cpuid=" . $cpuid . "&randomcode=" . $randomcode . "'";
						echo "</script>";
					}
					/*
					$key = $g->jj_key("gemini#123time#8888#time123#gemini");
					$headerurl = "../index.php?mac=". $mac . "&cpuid=" . $cpuid . "&key=" . $key;
					echo "<script language='javascript' type='text/javascript'>";
					echo "window.location.href='" . $headerurl . "'";
					echo "</script>";
					
					echo "<script language='javascript' type='text/javascript'>";
					echo "window.Authentication.CTCLoadWebView();";
					echo "</script>";
			
					$content = "取消:" . $oldmac . " " . $mac . "绑定了已使用激活码:" . $number;
					*/
				}
				//header("Location: ../error.php?error=1");
				//exit;
			}
			else
			{
				$sql->disconnect_database();
				header("Location: ../error.php?error=1" . "&mac=" . $mac . "&cpuid=" . $cpuid . "&randomcode=" . $randomcode."&terlang=".$terlang);
				exit;	
			}
		}
		else
		{
			/*
			//$key = $g->jj_key("gemini#123time#8888#time123#gemini");
			$errorurl = "../error.php?mac=". $mac . "&cpuid=" . $cpuid . "&error=2";
			echo "<script language='javascript' type='text/javascript'>";
			//echo "alert('距离上次使用此授权码不得小于24小时');";
			echo "window.location.href='" . $errorurl . "';";
			echo "</script>";
			*/
			echo "<script language='javascript' type='text/javascript'>";
			echo "window.Authentication.CTCLoadWebView();";
			echo "</script>";
			exit;
		}
	}
	else if(count($machine) == 0)
	{
		$mytable = "account_table";
		$sql->create_table($mydb, $mytable, "id int, playlist text, days text, proxy text, 
			export int, used int, mac text, cpuid text, cdkey text, showtime text, type text, member text, startime datetime");
		$accounts = $sql->fetch_datas_where($mydb, $mytable,"cdkey",$number);
		if($accounts == null || count($accounts) <= 0)
		{
			$sql->disconnect_database();
			header("Location: ../error.php?error=1" . "&mac=" . $mac . "&cpuid=" . $cpuid . "&randomcode=" . $randomcode."&terlang=".$terlang);
			exit;
		}
	
		if(intval($accounts[0][5]) > 0)
		{
			$sql->disconnect_database();
			header("Location: ../error.php?error=1" . "&mac=" . $mac . "&cpuid=" . $cpuid . "&randomcode=" . $randomcode."&terlang=".$terlang);
			exit;
		}
	
		$days =intval($accounts[0][2]);
		$playlist = $accounts[0][1];
		$proxy = $accounts[0][3];
		$show = $accounts[0][9];
		$panel = $accounts[0][10];
		$member = $accounts[0][11];
		
		$allow = "yes";
		$allocation = "manually";
	
		if(strcmp($playlist,"all")==0)
			$allocation = "all";
		else if(strcmp($playlist,"auto")==0)
			$allocation = "auto";
		
		$mytable = "custom_table";
		/*
		$sql->create_table($mydb, $mytable, 
			"mac text,cpu text,ip text,space text, date text,
			time text,allow text, playlist text, online text, allocation text,
			proxy text, balance float,showtime text,contact text,member text,
			panal text,number text,ips text, onescrolltext text, onescrolltexttimes int, 
			numberdate int, scrollcontent text, scrolldate text, scrolltimes text, controlurl text, 		
			controltime int, unbundling int, accessdate timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP, mv text, isupdate int,
			remarks text, startime date");
		*/
		$sql->create_table($mydb, $mytable, 
			"mac text,cpu text,ip text,space text, date text,
			time text,allow text, playlist text, online text, allocation text,
			proxy text, balance float,showtime text,contact text,member text,
			panal text,number text,ips text, onescrolltext text, onescrolltexttimes int, 
			numberdate int, scrollcontent text, scrolldate text, scrolltimes text, controlurl text, 		
			controltime int, unbundling int, accessdate timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP, mv text, isupdate int,
			remarks text, startime date, model text, remotelogin int, limitmodel text, 
			modelerror int, limittimes int, limitarea text, ghost int, password text, evernumber longtext");
		
		$machine = $sql->fetch_datas_where_2($mydb, $mytable,"mac",$mac,"cpu",$cpuid);
		if($machine == null || count($machine) <= 0)
		{
			$sql->disconnect_database();
			echo "<script language='javascript' type='text/javascript'>";
			echo "window.Authentication.CTCLoadWebView();";
			echo "</script>";
			exit;
		}
		
		/*
		$starttime = 0;	
		if(strcmp($machine[0][6],"yes") == 0)
		{
			$starttime = getChaBetweenTwoDate(date('Y-m-d',time()),$machine[0][4]);
			if($starttime < 0)
				$starttime = 0;
		}
	
		$sql->update_data_3($mydb, $mytable, "cpu", $cpuid, "mac", $mac, "date", $date);
		*/
		$time = date("Y-m-d",time()+$days*3600*24);
		$sql->update_data_3($mydb, $mytable, "cpu", $cpuid, "mac", $mac, "time", $time);
		$sql->update_data_3($mydb, $mytable, "cpu", $cpuid, "mac", $mac, "date", $date);
		$sql->update_data_3($mydb, $mytable, "cpu", $cpuid, "mac", $mac, "allocation", $allocation);
		$sql->update_data_3($mydb, $mytable, "cpu", $cpuid, "mac", $mac, "playlist", $playlist);
		$sql->update_data_3($mydb, $mytable, "cpu", $cpuid, "mac", $mac, "proxy", $proxy);
		$sql->update_data_3($mydb, $mytable, "cpu", $cpuid, "mac", $mac, "allow", $allow);
		$sql->update_data_3($mydb, $mytable, "cpu", $cpuid, "mac", $mac, "startime", date("Y-m-d H:i:s"));
		/*
		$mytable = "custom_two_table";
		$sql->create_table($mydb, $mytable, "mac text,cpuid text,showtime text,contact text,param0 text,param1 text,param2 text,param3 text,param4 text");
		$namess = $sql->fetch_datas_where_2($mydb, $mytable, "mac", $mac, "cpuid", $cpuid);
		if(count($namess) <= 0)
		{
			$sql->insert_data($mydb, $mytable, "mac, cpuid, showtime, contact, param0, param1, param2, param3, param4", $mac.", ".$cpuid.", ".$show.", "."".", "."".", ".$panel.", "."null".", ".$number.", "."null");
		}
		else
		{
			$sql->update_data_3($mydb, $mytable, "cpuid", $cpuid, "mac", $mac, "param3", $number);
			$sql->update_data_3($mydb, $mytable, "cpuid", $cpuid, "mac", $mac, "param1", $panel);
			$sql->update_data_3($mydb, $mytable, "cpuid", $cpuid, "mac", $mac, "showtime", $show);
		}
		*/
		$sql->update_data_3($mydb, $mytable, "cpu", $cpuid, "mac", $mac, "number", $number);
		$sql->update_data_3($mydb, $mytable, "cpu", $cpuid, "mac", $mac, "panal", $panel);
		$sql->update_data_3($mydb, $mytable, "cpu", $cpuid, "mac", $mac, "showtime", $show);
		$sql->update_data_3($mydb, $mytable, "cpu", $cpuid, "mac", $mac, "member", $member);
		$sql->update_data_3($mydb, $mytable, "cpu", $cpuid, "mac", $mac, "unbundling", 1);
		
		$evernumber = $sql->query_data_2($mydb, $mytable, "cpu", $cpuid, "mac", $mac, "evernumber");
		$sql->update_data_3($mydb, $mytable, "cpu", $cpuid, "mac", $mac, "evernumber", $evernumber . "&" . $number);
		
		$mytable = "account_table";
		//$sql->create_table($mydb, $mytable, "id int, playlist text, days text, proxy text, export int, used int, mac text, cpuid text, cdkey text");
		$sql->create_table($mydb, $mytable, "id int, playlist text, days text, proxy text, 
			export int, used int, mac text, cpuid text, cdkey text, showtime text, type text, member text, startime datetime");
			
		$used = 1;
		$sql->update_data_2($mydb, $mytable, "cdkey", $number, "mac", $mac);
		$sql->update_data_2($mydb, $mytable, "cdkey", $number, "cpuid", $cpuid);
		$sql->update_data_2($mydb, $mytable, "cdkey", $number, "used", $used);
		$sql->update_data_2($mydb, $mytable, "cdkey", $number, "startime", date("Y-m-d H:i:s"));
		
		if(true)
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
				modelerror int, limittimes int, limitarea text");
		
			$model = $sql->query_data_2($mydb, $mytable, "mac", $mac, "cpu", $cpuid, "model");
			//echo "model:" . $model;
			$sql->update_data_3($mydb, $mytable, "cpu", $cpuid, "mac", $mac, "limitmodel", $model);
		}
		
		$content = "绑定了未使用激活码:" . $number . " 时长:" .  $days . "天";
			//http://iptv.188tv.net:18006/gemini-iptv/?mac=18:fe:34:ca:58:1a&cpuid=GEMINI5409116333139092&key=UJUYPlnsWovl0h6kKJxqnJ2rKJxqnJ2rO3Hl0h6DUuxIPlUDWVKIeVXOoVo2cv0n5hqY08okMx81mzglTLyMxx5wSm9qKVEUPvK7K8jNWJjpklsYcX==
		/*
		$key = $g->jj_key("gemini#123time#8888#time123#gemini");
		$headerurl = "../index.php?mac=". $mac . "&cpuid=" . $cpuid . "&key=" . $key;
		echo "<script language='javascript' type='text/javascript'>";
		echo "window.location.href='" . $headerurl . "'";
		echo "</script>";
		*/
		echo "<script language='javascript' type='text/javascript'>";
		echo "window.Authentication.CTCLoadWebView();";
		echo "</script>";
		//header("Location: ../index.php?mac=". $mac . "&cpuid=" . $cpuid . "&key=" . $key);
	}
	
	//$content =  $lang_array["account_export_post_text1"];
	$date = date("Y-m-d H:i:s");
	$mytable = "log_record_table";
	$sql->create_table($mydb, $mytable, "date datetime, user text, mac text, cpuid text, content text, other text, ip text");	
	$sql->insert_data($mydb, $mytable, "date, user, mac, cpuid, content, other, ip", $date.", ".$proxy.", ".$mac.", ".$cpuid.", ".$content.", "."null".", ".getIP());
	
	$sql->disconnect_database();
?>

</body>
</html>