<?php
	function writeLog($StrConents)
	{
		$TxtRes;
		if(($TxtRes=fopen("sms.txt","a")) === FALSE)
		{
			return;
		}
		
		fwrite ($TxtRes,$StrConents);
		
		fclose ($TxtRes);
	}
	
	include_once 'admindir.php';
	
	$a = new Adminer();
	$addir = $a->ad;
	include_once $addir . 'common.php';
	include_once 'gemini.php';
	include_once 'cn_lang.php';


	if(!isset($_GET["mac"]) || !isset($_GET["cpuid"]) || !isset($_GET["phone"]))
		return;
		
	$mac = $_GET["mac"];
	$cpuid = $_GET["cpuid"];
	$phone = $_GET["phone"];

	$opts = array(   
  		'http'=>array(   
    	'method'=>"GET",   
    	'timeout'=>30,//单位秒  
   		)   
	);   
	
	$user = "0931581740";
	$password = "Q56128890@";
	//$phone = "8613580584233";
	$contactkey = rand(100000,999999);
	$body = urlencode("验证码:" . $contactkey);
	$url = "http://smexpress.mitake.com.tw:7003/SpSendUtf?username=" . $user . "&password=" . $password . "&dstaddr=" . urlencode($phone) . "&smbody=" . $body . "&CharsetURL=utf-8";
	writeLog($url);
	$ret = file_get_contents($url, false, stream_context_create($opts));
	//e10adc3949ba59abbe56e057f20f883e
	
	$sql = new DbSql();
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
		evernumber longtext, prekey text, cpuinfo text, contactkey text");
		
	$sql->update_data_3($mydb, $mytable, "mac",$mac,"cpu",$cpuid,"contact",$phone);	
	$sql->update_data_3($mydb, $mytable, "mac",$mac,"cpu",$cpuid,"contactkey",md5($contactkey));
	
	$sql->disconnect_database();	

?>