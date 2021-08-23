<?php
	include 'common.php';
	set_time_limit(1800);
	$sql = new DbSql();
	$sql->login_proxy();
	
	$member = $_GET["member"];
	$id = $_GET["id"];
	
	$sql->connect_database_default();
	$mydb = $sql->get_database();
	$sql->create_database($mydb);
	
	$mytable = "account_table";
	$sql->create_table($mydb, $mytable, "id int, playlist text, days text, proxy text, export int, used int, mac text, cpuid text, cdkey text, showtime text, type text, member text");
	
	$sql->update_data_2($mydb, $mytable, "id", $id, "member", $member);
	$used = $sql->query_data($mydb, $mytable, "id", $id, "used");
	if($used == 1)
	{
		$mac = $sql->query_data($mydb, $mytable, "id", $id, "mac");
		$cpuid = $sql->query_data($mydb, $mytable, "id", $id, "cpuid");
		if(strlen($mac) > 8 && strlen($cpuid) > 5)
		{
			$mytable = "custom_table";
			$sql->create_table($mydb, $mytable, 
				"mac text,cpu text,ip text,space text, date text,
				time text,allow text, playlist text, online text, allocation text,
				proxy text, balance float,showtime text,contact text,member text,
				panal text,number text,ips text, onescrolltext text, onescrolltexttimes int, 
				numberdate int, scrollcontent text, scrolldate text, scrolltimes text, controlurl text, 		
				controltime int");
				
			$sql->update_data_3($mydb, $mytable, "cpu", $cpuid, "mac", $mac, "member", $member);
			
		}
	}
	$sql->disconnect_database();
	
	header("Location: proxy_account_list.php?page=". $_GET["page"]);
?>