<?php
	header('Content-Type: application/json; charset=utf-8');
	include_once 'admindir.php';
	$a = new Adminer();
	include_once $a->ad . 'common.php';
	include_once $a->ad . 'memcache.php';
	include_once 'gemini.php';
	include_once 'fnconfig.php';
	$mem = new GMemCache();
	$mem_ret = $mem->connect();
	$mem_timeout = 30*60;
	$timeout = 1;
	$g = new Gemini();
	$fn = new fntv();
	$fnkey = new Password();
	set_zone();
	$sql = new DbSql();
	
	
	if(empty($_GET["token"]) && empty($_GET["id"]))
	{
		$msg = json_encode(array(
			'Auth' => False
		));
		$msg = (($fn->mtvkey == '')? $msg : $fn->encrypt($msg,$fn->mtvkey));
		exit($msg);	
	}
	
	$token = $fn ->authcode(base64_decode($sql->str_safe($_GET["token"])),'DECODE',$fnkey->key);
	list($cpuid,$mac,$stime) = explode("\t",$token);
	/*if(time()>$stime){
		$msg = json_encode(array(
			'Auth' => False
		));
		$msg = (($fn->mtvkey == '')? $msg : $fn->encrypt($msg,$fn->mtvkey));
		exit($msg);
    }*/
	$sql->connect_database_default();
	$mydb = $sql->get_database();
	$sql->create_database($mydb);
	
	$id = $sql->str_safe($_GET["id"]);
	$type = substr($id,0,1)-1;

	$mytable = "vod_table_".$type;
	$cmd = "SELECT name,url FROM " . $mytable . " WHERE id=".substr($id,1);
	$memkey = md5($mytable . "fetch_datas_limit_desc_cmd" .  $cmd);
	$rows = array();
	if($mem_ret == true){
		$mem_value = $mem->get($memkey);
		if($mem_value != false){
			$rows = unserialize($mem_value);
		}else{
			$sql->create_table($mydb, $mytable, "name text, image text, 
		        url text, area text, year text, type text, intro1 longtext, intro2 longtext, 
		        intro3 longtext, intro4 longtext, id int, clickrate int, recommend tinyint, chage float, updatetime int, firstletter text");
			$rows = $sql->fetch_datas_limit_desc_cmd($mydb, $mytable, $cmd);
			$mem->set_timeout($memkey,serialize($rows),$mem_timeout);
		}
	}else{
		$sql->create_table($mydb, $mytable, "name text, image text, 
		    url text, area text, year text, type text, intro1 longtext, intro2 longtext, 
		    intro3 longtext, intro4 longtext, id int, clickrate int, recommend tinyint, chage float, updatetime int, firstletter text");
		$rows = $sql->fetch_datas_limit_desc_cmd($mydb, $mytable, $cmd);
	}
	$url = $rows[0][1];
	$name = $rows[0][0];
	$url = explode("|",$g->j4($url));
	foreach($url as $m => $row){
		$url = explode("#",$row);
		$outdata["Items"][]= array("Id"=> $m+1,"Name"=>$url[0],"Url"=> $url[1]);
	}
	$msg = json_encode(array(
		"Auth"=>True,
		"Data"=>$outdata,
		"Status"=>200
	));
	$msg = (($fn->mtvkey == '')? $msg : $fn->encrypt($msg,$fn->mtvkey));
	exit($msg);
?>
