<?php
	include 'common.php';
	$sql = new DbSql();
	$sql->login_proxy();
	
	$sql->connect_database_default();
	$mydb = $sql->get_database();
	$sql->create_database($mydb);
	
	$mytable = "proxy_table";
	$sql->create_table($mydb, $mytable, "name text, password text, ptip text, edit int, watermark text, validity date, allow int, remark text, ccount int");
	$ccount = $sql->query_data($mydb, $mytable,"name",$_COOKIE["user"], "ccount");
	if($ccount != 1)
	{
		$sql->disconnect_database();
		exit;
	}
		
	$playlist = $_GET["playlist"];
	$proxy = $_GET["proxy"];
	$days = $_GET["days"];
	$len = $_GET["len"];
	$show = $_GET["show"];
	$panel = $_GET["panel"];
	
	$cpuid = "";
	$mac = "";
	$use = 0;
	
	$mytable = "account_table";
	$sql->create_table($mydb, $mytable, "id int, playlist text, days text, proxy text, 
			export int, used int, mac text, cpuid text, cdkey text, showtime text, type text, member text, startime datetime");
	
	$namess = $sql->fetch_datas_order($mydb, $mytable, "id");
	$count = count($namess);
	$id = 0;
	if($count >= 1)
		$id = intval($namess[0][0]) + 1;
	$export = 0;

	for($ii=0; $ii<intval($len); $ii++)
	{
		$cdkey = "";	
		for($kk=0; $kk<16; $kk++)
		{
			srand((float)microtime()*1000000); 
			$cdkey = $cdkey . rand(0,9);
			usleep(100);
		}
		$ids = $id + $ii;
		$sql->insert_data($mydb, $mytable, "id, playlist, days, proxy, export, used, mac, cpuid, cdkey, showtime, type, member, startime",$ids.", ".$playlist.", ".$days.", ".$proxy.", ".$export.", ".$use.", ".$mac.", ".$cpuid.", ".$cdkey.", ".$show.", ".$panel.", ". "" . ", ". date("Y-m-d H:i:s"));
	}
	
	$sql->disconnect_database();
	header("Location: proxy_account_list.php");
?>