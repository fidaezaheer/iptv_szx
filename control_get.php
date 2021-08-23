<?php	

	include_once 'admindir.php';
	$a = new Adminer();
	include_once $a->ad . 'common.php';
	include_once $a->ad . 'memcache.php';
	$mem = new GMemCache();
	if(get_set_xml_file($addir . "safe2.xml") == 1 && $mem->step1(__FILE__) == false)
	{
		echo "reload";
		exit;		
	}
	
	$sql = new DbSql();
	
	if(isset($_GET["version"]) && isset($_GET["key"]))
	{
		$version = intval($_GET["version"]);
		$key = $sql->str_safe($_GET["key"]);	
	}
	check_key_out($version,$key,$a->ad);	
	
	$mac = $_GET["mac"];
	$cpuid = $_GET["cpuid"];
	
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
		evernumber longtext, prekey text");
		
	$url = $sql->query_data_2($mydb, $mytable, "mac", $mac, "cpu", $cpuid, "controlurl"); 

 	$sql->disconnect_database();
	
	
	echo "aaaaaaaaaaaaaaaaaagemini:" . $url;

?>