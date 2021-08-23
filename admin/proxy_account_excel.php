<?php
	include 'common.php';
	$sql = new DbSql();
	$sql->login_proxy();
	
	$sql->connect_database_default();
	$mydb = $sql->get_database();
	$sql->create_database($mydb);
	
	$export_ids = $_GET["export"];
	
	$mytable = "playlist_type_table";
	$sql->create_table($mydb, $mytable, "name text, space longtext, id text, playlist longtext");	
	$playlists = $sql->fetch_datas($mydb, $mytable);
	$playlists_array = array();	
	
	foreach($playlists as $playlist) 
	{		
		$playlists_array[$playlist[2]] = $playlist[0];
	}
	
	$mytable = "account_table";
	$sql->create_table($mydb, $mytable, "id int, playlist text, days text, proxy text, export int, used int, mac text, cpuid text, cdkey text, showtime text, type text");
	
	
	header("Content-type:application/vnd.ms-excel");
	header("Content-Disposition:attachment;filename=account.xls" );

	//echo "授权码"."\t";
	//echo "分配列表"."\t";
	//echo "授权天数"."\t";
	//echo "代理商"."\t";
	echo iconv("UTF-8", "GBK", "授权码")."\t";
	echo iconv("UTF-8", "GBK", "分配列表")."\t";
	echo iconv("UTF-8", "GBK", "授权天数")."\t";
	echo iconv("UTF-8", "GBK", "代理商")."\t";
	echo "\n";

	$export_id = explode("|",$export_ids);
	for($ii=0; $ii<count($export_id); $ii++)
	{
		$mytable = "account_table";
		$sql->create_table($mydb, $mytable, "id int, playlist text, days text, proxy text, export int, used int, mac text, cpuid text, cdkey text, showtime text, type text");
		$names = $sql->fetch_datas_where($mydb, $mytable,"id",$export_id[$ii]);
		$sql->update_data_2($mydb, $mytable, "id", $export_id[$ii], "export", 1);
		
		echo chunk_split($names[0][8],4," ") . "\t";
		if(strcmp($names[0][1],"all") == 0)
			echo iconv("UTF-8", "GBK//ignore", "全部"). "\t";	
		else if(strcmp($names[0][1],"auto") == 0)
			echo iconv("UTF-8", "GBK//ignore", "按地址分配"). "\t";	
		else
		{		
			$mytable = "playlist_type_table";
			$sql->create_table($mydb, $mytable, "name text, space longtext, id text, playlist longtext");	
			$playlist_name = $sql->query_data($mydb, $mytable,"id",$names[0][1],"name");
			echo iconv("UTF-8", "GBK", $playlist_name). "\t";	
		}
		echo $names[0][2] . "\t";
		echo $names[0][3] . "\t";
		echo "\n";
	}
	
	$sql->disconnect_database();
	
	//header("Location: account_list.php?page=". $_GET["page"]);
?>