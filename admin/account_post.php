
<?php
	include 'common.php';
	$sql = new DbSql();
	$sql->login();
	
	$playlist = $_GET["playlist"];
	$proxy = $_GET["proxy"];
	$days = $_GET["days"];
	$len = $_GET["len"];
	$show = $_GET["show"];
	$panel = $_GET["panel"];
	$member = $_GET["member"];
	$logonum = $_GET["logonum"];
	
	$cpuid = "";
	$mac = "";
	$use = 0;
	
	
	$sql->connect_database_default();
	$mydb = $sql->get_database();
	$sql->create_database($mydb);

	$mytable = "account_table";
	$sql->create_table($mydb, $mytable, "id int, playlist text, days text, proxy text, 
			export int, used int, mac text, cpuid text, cdkey text, showtime text, type text, member text, startime datetime, logonum text");
	
	$namess = $sql->fetch_datas_limit_desc($mydb, $mytable, 0, 1, "id");
	$count = $sql->count_fetch_datas_order($mydb, $mytable, "id");
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
		
		$md5_logonum = "";
		if(strlen($logonum) > 1)
			$md5_logonum = md5(md5($logonum));
		$sql->insert_data($mydb, $mytable, "id, playlist, days, proxy, export, used, mac, cpuid, cdkey, showtime, type, member, startime, logonum",$ids.", ".$playlist.", ".$days.", ".$proxy.", ".$export.", ".$use.", ".$mac.", ".$cpuid.", ".$cdkey.", ".$show.", ".$panel.", ". $member . ", ". date("Y-m-d H:i:s") . ", " . $md5_logonum);
	}
	
	$sql->disconnect_database();
	header("Location: account_list.php");
?>