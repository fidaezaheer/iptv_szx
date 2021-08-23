<?php
	include_once 'admindir.php';
	$a = new Adminer();
	include_once $a->ad . 'common.php';
	include_once 'gemini.php';
	
	$sql = new DbSql();
	
	$version = 88;
	$key = "";
	if(isset($_GET["version"]) && isset($_GET["key"]))
	{
		$version = intval($_GET["version"]);
		$key = $sql->str_safe($_GET["key"]);	
	}
	check_key($version,$key);
	
	set_zone();
	
	$sql->connect_database_default();
	$mydb = $sql->get_database();
	$sql->create_database($mydb);
	
	$mytable = "safe_table2";
	$sql->create_table($mydb, $mytable, "id text,safe10 text,safe11 text,safe12 text,safe13 text,safe14 text,safe15 text,safe16 text,safe17 text,safe18 text,safe19 text,model text,logintime int, unbundling int");
	$unbundling = $sql->query_data($mydb, $mytable, "id", "0", "unbundling");
	if($unbundling == null || intval($unbundling)==0)
	{
		$sql->disconnect_database();
		echo "cannot";
		return;
	}
	
	$g = new Gemini();
	
	$mac = "";
	$cpuid = "";
	$value = 1;
	$time = 0;
	
	$cmd = $g->j_key($_GET["cmd"]);
	$cmds = explode("#",$cmd);
	if(count($cmds) >= 4)
	{
		$mac = $cmds[0];
		$cpuid = $cmds[1];
		$value = intval($cmds[2]);
		$time = floatval($cmds[3]);
	}
	else
	{
		echo "bundling";
		exit;	
	}
	
	$mytable = "custom_table";
	$sql->create_table($mydb, $mytable, 
		"mac text,cpu text,ip text,space text, date text,
		time text,allow text, playlist text, online text, allocation text,
		proxy text, balance float,showtime text,contact text,member text,
		panal text,number text,ips text, onescrolltext text, onescrolltexttimes int, 
		numberdate int, scrollcontent text, scrolldate text, scrolltimes text, controlurl text, 
		controltime int, unbundling int");

	if($value == 2)
	{
		$unbundling = $sql->query_data_2($mydb,$mytable,"mac",$mac,"cpu",$cpuid,"unbundling");
		$sql->disconnect_database();
		if($unbundling == 0)
			echo "unbundling";
		else
			echo "bundling";
			
		return;
	}
	
	if(abs(time() - $time/1000) > 70)
	{
		$sql->disconnect_database();
		echo "bundling";
		exit;	
	}
	
	if($value == 1)
	{
		$sql->update_data_3($mydb, $mytable,"mac",$mac,"cpu",$cpuid,"unbundling",1);
		$sql->disconnect_database();
		echo "bundling";
	}
	else if($value == 0)
	{
		$sql->update_data_3($mydb, $mytable,"mac",$mac,"cpu",$cpuid,"unbundling",0);
		$sql->disconnect_database();
		echo "unbundling";
	}
?>