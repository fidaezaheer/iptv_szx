<?
	
	
	include 'common.php';
	$sql = new DbSql();
	$sql->login();
	
	$add_day = $_GET["addday"];
	
	$mytable = "custom_table";
	$sql->connect_database_default();
	$mydb = $sql->get_database();
	$sql->create_database($mydb);
	$sql->create_table($mydb, $mytable, "mac text,cpu text,ip text,space text, date text,time text,allow text, playlist text, online text, allocation text, proxy text, balance float,showtime text,contact text,member text,panal text,number text,ips text");

	$namess = $sql->fetch_datas_where($mydb, $mytable, "allow", "yes"); 
	foreach($namess as $names) 
	{
		if(strcmp($names[5],"-1") == 0)
			continue;
			
		$Date_List_a1=explode("-",$names[5]);
		if(count($Date_List_a1) < 3)
			continue;
			
		if(is_numeric($Date_List_a1[1]) && is_numeric($Date_List_a1[0]) && is_numeric($Date_List_a1[2]))
		{
			$d1=mktime(0,0,0,$Date_List_a1[1],$Date_List_a1[2],$Date_List_a1[0]);
			$d1=$d1+$add_day*3600*24;
			$sql->update_data_3($mydb, $mytable,"mac",$names[0],"cpu",$names[1],"time",date('Y-m-d',$d1));
			
		}
		else
		{
			continue;
		}
		
	}
	
	$sql->disconnect_database();
	header("Location: custom_batch_list.php");
?>