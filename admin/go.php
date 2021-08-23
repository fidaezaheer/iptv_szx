<?php
	include_once "common.php";
	include_once "number.php";
	include_once "cn_lang.php";
	
	if(!isset($_POST["user"]) || !isset($_POST["password"]))
	{
		header("Location: index.php?error=1");
		exit;
	}
	
	if(strcmp($_POST["testid"],$_COOKIE["vcode"]) != 0)
	{
		header("Location: index.php?error=2");
		exit;
	}
	
	setcookie("testid", $_POST["testid"]);
	
	$number = "";
	$name = trim($_POST["user"]);
	$password = trim($_POST["password"]);
	if(isset($_POST["number"]))
		$number = md5(md5(trim($_POST["number"])));
	
	//$lang = trim($_POST["lang"]);
	//setcookie("lang", $lang);
	
	deldir("backup/");
	init();
		
	$sql = new DbSql();
	set_zone();
	
	$sql->connect_database_default();
	$mydb = $sql->get_database();
	$sql->create_database($mydb);
	
	$mytable = "user_table";
	$sql->create_table($mydb, $mytable, "name longtext, password longtext, namemd5 text, passwordmd5 text, needmd5 int, terlang int");
	if($sql->find_column($mydb, $mytable, "namemd5") == 0)
		$sql->add_column($mydb, $mytable,"namemd5", "text");
				
	if($sql->find_column($mydb, $mytable, "passwordmd5") == 0)
		$sql->add_column($mydb, $mytable,"passwordmd5", "text");
				
	if($sql->find_column($mydb, $mytable, "needmd5") == 0)
		$sql->add_column($mydb, $mytable,"needmd5", "int");
		
	if($sql->find_column($mydb, $mytable, "terlang") == 0)
		$sql->add_column($mydb, $mytable,"terlang", "int");
		
	$admin_password = $sql->query_data($mydb, $mytable, "name", $name, "password");
	//echo "ps:" . $admin_password;
	$admin_passwordmd5 = trim($sql->query_data($mydb, $mytable, "name", $name, "passwordmd5"));
	$admin_needmd5 = $sql->query_data($mydb, $mytable, "name", $name, "needmd5");
				
	//$mytable = "proxy_table";
	//$sql->create_table($mydb, $mytable, "name text, password text, ptip text, edit int, watermark text, validity date, allow int, remark text");
	$mytable = "proxy_table";
	$sql->create_table($mydb, $mytable, "name text, password text, ptip text, edit int, watermark text, validity date, allow int, remark text, ccount int, panal int, pwmd5 int, logonum text, terlang int, proxylevel int, proxybelong text");	
		
	if($sql->find_column($mydb, $mytable, "ptip") == 0)
		$sql->add_column($mydb, $mytable,"ptip", "text");
			
	if($sql->find_column($mydb, $mytable, "edit") == 0)
		$sql->add_column($mydb, $mytable,"edit", "int");

	if($sql->find_column($mydb, $mytable, "watermark") == 0)
		$sql->add_column($mydb, $mytable,"watermark", "text");
			
	if($sql->find_column($mydb, $mytable, "validity") == 0)
		$sql->add_column($mydb, $mytable,"validity", "date");
			
	if($sql->find_column($mydb, $mytable, "allow") == 0)
		$sql->add_column($mydb, $mytable,"allow", "int");

	if($sql->find_column($mydb, $mytable, "remark") == 0)
		$sql->add_column($mydb, $mytable,"remark", "text");	
					
	if($sql->find_column($mydb, $mytable, "ccount") == 0)
		$sql->add_column($mydb, $mytable,"ccount", "int");	
				
	if($sql->find_column($mydb, $mytable, "panal") == 0)
		$sql->add_column($mydb, $mytable,"panal", "int");	
						
	if($sql->find_column($mydb, $mytable, "pwmd5") == 0)
		$sql->add_column($mydb, $mytable,"pwmd5", "int");

	if($sql->find_column($mydb, $mytable, "pwmd5") == 0)
		$sql->add_column($mydb, $mytable,"pwmd5", "int");
			
	if($sql->find_column($mydb, $mytable, "logonum") == 0)
		$sql->add_column($mydb, $mytable,"logonum", "text");	
			
	if($sql->find_column($mydb, $mytable, "terlang") == 0)
		$sql->add_column($mydb, $mytable,"terlang", "int");	
		
	if($sql->find_column($mydb, $mytable, "proxylevel") == 0)
	{
		$sql->add_column($mydb, $mytable,"proxylevel", "int");
	}
		
	if($sql->find_column($mydb, $mytable, "proxybelong") == 0)
	{	
		$sql->add_column($mydb, $mytable,"proxybelong", "text");
	}
					
	$proxy_password = trim($sql->query_data($mydb, $mytable, "name", $name, "password"));
	$proxy_validity = $sql->query_data($mydb, $mytable, "name", $name, "validity");
	$proxy_allow = $sql->query_data($mydb, $mytable, "name", $name, "allow");
	$proxy_pwmd5 = $sql->query_data($mydb, $mytable, "name", $name, "pwmd5");
	if($proxy_pwmd5 == null)
		$proxy_pwmd5 = 0;
		
	//echo $proxy_password . "#";
	//echo $password. "#";
	//echo $admin_passwordmd5. "#";
	//echo $admin_needmd5. "#";
	//echo $proxy_validity. "#";
	//echo $proxy_allow. "#";
	//echo $proxy_pwmd5 . "#";
	
	if($admin_needmd5 == 0 && strcmp($admin_password,$password) == 0)
	{
		$num = new Number();
		if(strcmp($num->number,$number) != 0)
		{
			header("Location: index.php?error=1");
			$sql->disconnect_database();
			exit;		
		}
		$isproxy = 0;
		setcookie("user", trim($name));
		setcookie("password", trim($password));
		setcookie("number", trim($number));
		
		$date = date("Y-m-d H:i:s");
		$mytable = "log_record_table";
		$content = $lang_array["go_text1"];
		$sql->create_table($mydb, $mytable, "date datetime, user text, mac text, cpuid text, content text, other text");
		$sql->insert_data($mydb, $mytable, "date, user, mac, cpuid, content, other", $date.", ".$name.", "."".", "."".", ".$content.", "."null");
		header("Location: left_4.php");
		$sql->disconnect_database();
	}
	else if($admin_needmd5 == 1 && strcmp($admin_passwordmd5,md5($password)) == 0)
	{
		$num = new Number();
		if(strcmp($num->number,$number) != 0)
		{
			header("Location: index.php?error=1");
			$sql->disconnect_database();
			exit;		
		}
		$isproxy = 0;
		setcookie("user", trim($name));
		setcookie("password", trim($password));
		setcookie("number", trim($number));
		
		$date = date("Y-m-d H:i:s");
		$mytable = "log_record_table";
		$content = $lang_array["go_text2"];
		$sql->create_table($mydb, $mytable, "date datetime, user text, mac text, cpuid text, content text, other text");
		$sql->insert_data($mydb, $mytable, "date, user, mac, cpuid, content, other", $date.", ".$name.", "."".", "."".", ".$content.", "."null");
		header("Location: left_4.php");
		$sql->disconnect_database();
	}
	else if($admin_needmd5 == 2 && strcmp($admin_passwordmd5,md5(md5($password))) == 0)
	{
		$num = new Number();
		if(strcmp($num->number,$number) != 0)
		{
			header("Location: index.php?error=3");
			$sql->disconnect_database();
			exit;		
		}
		$isproxy = 0;
		setcookie("user", trim($name));
		setcookie("password", trim($password));
		setcookie("number", trim($number));
		
		$date = date("Y-m-d H:i:s");
		$mytable = "log_record_table";
		$content = $lang_array["go_text2"];
		$sql->create_table($mydb, $mytable, "date datetime, user text, mac text, cpuid text, content text, other text");
		$sql->insert_data($mydb, $mytable, "date, user, mac, cpuid, content, other", $date.", ".$name.", "."".", "."".", ".$content.", "."null");
		header("Location: left_4.php");
		$sql->disconnect_database();
	}
	
	else if(($proxy_pwmd5 == 0 && strcmp($proxy_password,$password) == 0 && ((intoDay(date("Y-m-d"),$proxy_validity) == 1 && $proxy_allow == 1) || $proxy_allow == -1)) 
		|| ($proxy_pwmd5 == 1 && strcmp($proxy_password,md5($password)) == 0 && ((intoDay(date("Y-m-d"),$proxy_validity) == 1 && $proxy_allow == 1) || $proxy_allow == -1))
		|| ($proxy_pwmd5 == 2 && strcmp($proxy_password,md5(md5($password))) == 0 && ((intoDay(date("Y-m-d"),$proxy_validity) == 1 && $proxy_allow == 1) || $proxy_allow == -1)))
	{
		echo "ok";
		$isproxy = 1;
		setcookie("user", trim($name));
		setcookie("password", trim($password));

		$date = date("Y-m-d H:i:s");
		$content = $lang_array["go_text2"] . ":" . $name . " " . $lang_array["go_text3"];
		$mytable = "log_record_table";
		$sql->create_table($mydb, $mytable, "date datetime, user text, mac text, cpuid text, content text, other text");
		$sql->insert_data($mydb, $mytable, "date, user, mac, cpuid, content, other", $date.", ".$name.", "."".", "."".", ".$content.", "."null");
		header("Location: proxy_left.php");
		$sql->disconnect_database();
	}	
	else
	{
		echo "no";
		header("Location: index.php?error=1");
		$sql->disconnect_database();
		exit;
	}
	
?>


<?php

	function init()
	{
		$sql = new DbSql();
		$sql->connect_database_default();
		$mydb = $sql->get_database();
		$sql->create_database($mydb);		
		
		$mytable = "live_type_table";
		$sql->create_table($mydb, $mytable, "name longtext, id longtext, lang text");
		if($sql->find_column($mydb, $mytable, "lang") == 0)
			$sql->add_column($mydb, $mytable,"lang", "text");	
			
		$mytable = "playback_type_table";
		$sql->create_table($mydb, $mytable, "name text, id text ,need int, typepassword text, param0 text, param1 text, param2 text, param3 text, lang text");
		if($sql->find_column($mydb, $mytable, "lang") == 0)
			$sql->add_column($mydb, $mytable,"lang", "text");
			
		$mytable = "live_preview_table";
		$sql->create_table($mydb, $mytable, "name longtext, image longtext, url longtext, password longtext, type longtext, preview longtext, id longtext, urlid smallint, hd longtext, watermark text, lang text");
		
		if($sql->find_column($mydb, $mytable, "watermark") == 0)
		{
			$sql->add_column($mydb, $mytable,"watermark", "text");
		}
			
		if($sql->find_column($mydb, $mytable, "lang") == 0)
			$sql->add_column($mydb, $mytable,"lang", "text");
			
			
		$mytable = "safe_table2";
		$sql->create_table($mydb, $mytable, "id text,safe10 text,safe11 text,safe12 text,safe13 text,safe14 text,safe15 text,safe16 text,safe17 text,safe18 text,safe19 text,model text,logintime int, unbundling int");
		if($sql->find_column($mydb, $mytable, "model") == 0)
			$sql->add_column($mydb, $mytable,"model", "text");
	
		if($sql->find_column($mydb, $mytable, "logintime") == 0)
			$sql->add_column($mydb, $mytable,"logintime", "int");
	
		if($sql->find_column($mydb, $mytable, "unbundling") == 0)
			$sql->add_column($mydb, $mytable,"unbundling", "int");
		
		
		$mytable = "proxy_download_table";
		$sql->create_table($mydb, $mytable, "name text, sbackground text, sepg text, ebackground text, livepanel text, vodpanel text, download text, allow text, watermark text, scrolltext text, version int");	
		
		if($sql->find_column($mydb, $mytable, "watermark") == 0)
			$sql->add_column($mydb, $mytable,"watermark", "text");
			
		if($sql->find_column($mydb, $mytable, "scrolltext") == 0)
			$sql->add_column($mydb, $mytable,"scrolltext", "text");
			
		if($sql->find_column($mydb, $mytable, "version") == 0)
			$sql->add_column($mydb, $mytable,"version", "int");
						
		$mytable = "safe_table2";
		$sql->create_table($mydb, $mytable, "id text,safe10 text,safe11 text,safe12 text,safe13 text,safe14 text,safe15 text,safe16 text,safe17 text,safe18 text,safe19 text,model text,logintime int, unbundling int, disabel_model text, limitarea text, prekey text, server_request text, longtime text, limitsign text");
		if($sql->find_column($mydb, $mytable, "model") == 0)
			$sql->add_column($mydb, $mytable,"model", "text");
	
		if($sql->find_column($mydb, $mytable, "logintime") == 0)
			$sql->add_column($mydb, $mytable,"logintime", "int");
		
		if($sql->find_column($mydb, $mytable, "unbundling") == 0)
			$sql->add_column($mydb, $mytable,"unbundling", "int");
			
		if($sql->find_column($mydb, $mytable, "disabel_model") == 0)
			$sql->add_column($mydb, $mytable,"disabel_model", "text");
			
		if($sql->find_column($mydb, $mytable, "limitarea") == 0)
			$sql->add_column($mydb, $mytable,"limitarea", "text");
			
		if($sql->find_column($mydb, $mytable, "prekey") == 0)
			$sql->add_column($mydb, $mytable, "prekey", "text");
			
		if($sql->find_column($mydb, $mytable, "prekey") == 0)
			$sql->add_column($mydb, $mytable, "prekey", "text");
			
		if($sql->find_column($mydb, $mytable, "server_request") == 0)
			$sql->add_column($mydb, $mytable, "server_request", "text");
			
		if($sql->find_column($mydb, $mytable, "longtime") == 0)
			$sql->add_column($mydb, $mytable, "longtime", "text");
			
		if($sql->find_column($mydb, $mytable, "limitsign") == 0)
			$sql->add_column($mydb, $mytable, "limitsign", "text");
				
		$mytable = "account_table";
		$sql->create_table($mydb, $mytable, "id int, playlist text, days text, proxy text, export int, 
						used int, mac text, cpuid text, cdkey text, showtime text, 
						type text, member text, startime datetime, usedtime datetime, logonum text");
		if($sql->find_column($mydb, $mytable, "showtime") == 0)
			$sql->add_column($mydb, $mytable,"showtime", "text");
	
		if($sql->find_column($mydb, $mytable, "type") == 0)
			$sql->add_column($mydb, $mytable,"type", "text");
	
		if($sql->find_column($mydb, $mytable, "member") == 0)
			$sql->add_column($mydb, $mytable,"member", "text");
			
		if($sql->find_column($mydb, $mytable, "startime") == 0)
			$sql->add_column($mydb, $mytable,"startime", "datetime");
		
		if($sql->find_column($mydb, $mytable, "logonum") == 0)
			$sql->add_column($mydb, $mytable,"logonum", "text");
									
		$mytable = "stream_server_list_table";							
		$sql->create_table($mydb, $mytable, "serverid int, serverip text, tip text, os text, version int, info text");							
		if($sql->find_column($mydb, $mytable, "info") == 0)
			$sql->add_column($mydb, $mytable,"info", "text");
		
		$mytable = "vod_name_table";
		$sql->create_table($mydb, $mytable, "id smallint,name text, needps int, password text, num int, total int");
		if($sql->find_column($mydb, $mytable, "num") == 0)
			$sql->add_column($mydb, $mytable,"num", "int");
			
		if($sql->find_column($mydb, $mytable, "total") == 0)
			$sql->add_column($mydb, $mytable,"total", "int");
			
		$sql->disconnect_database();	
		
		/*
		$origin_str = file_get_contents('gemini.php');
		$pos = strrpos($origin_str,"<!--");
		if($pos > 0)
		{
			$update_str = substr($origin_str,0,$pos);
			file_put_contents('gemini.php', $update_str);
		}
		
		$origin_str = file_get_contents('geminip.php');
		$pos = strrpos($origin_str,"<!--");
		if($pos > 0)
		{
			$update_str = substr($origin_str,0,$pos);
			file_put_contents('geminip.php', $update_str);
		}
		*/
	}
	
	function intoDay($start,$end)
	{
		$startdate=strtotime($start);
		$enddate=strtotime($end);
		$days=$enddate-$startdate;
		if($days < 0)
		{
			return 0;
		}
		else
		{
			return 1;		
		}
	}

	function record($date,$user,$mac,$cpuid,$content)
	{	
		//include_once 'common.php';
		$sql = new DbSql();
		//$sql->login();
		$sql->connect_database_default();
		$mydb = $sql->get_database();
		$mytable = "log_record_table";
		$sql->create_database($mydb);
		//$sql->delete_table($mydb, $mytable);
		$sql->create_table($mydb, $mytable, "date datetime, user text, mac text, cpuid text, content text, other text");
		$sql->insert_data($mydb, $mytable, "date, user, mac, cpuid, content, other", $date.", ".$user.", ".$mac.", ".$cpuid.", ".$content.", "."null");
		$sql->disconnect_database();
	}
?>
