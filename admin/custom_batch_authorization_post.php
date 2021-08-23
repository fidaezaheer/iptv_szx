<?php
	include 'common.php';
	$sql = new DbSql();
	$sql->login();

	$sql->connect_database_default();
	$mydb = $sql->get_database();
	$sql->create_database($mydb);

	$macs = $_POST["macs"];
	$days = $_POST["days"];
	$playlist = $_POST["playlist"];
	$proxy = $_POST["proxyv"];
	
	echo "proxy:" . $proxy;
	
	$macss = explode("|",$macs);
	
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

	foreach($macss as $mac)
	{
		
		$sql->update_data_2($mydb, $mytable, "mac", $mac, "date", date("Y-m-d"));
		if(intval($days) == -1)
			$sql->update_data_2($mydb, $mytable, "mac", $mac, "time", "-1");
		else
			$sql->update_data_2($mydb, $mytable, "mac", $mac, "time", date("Y-m-d",strtotime("+" . $days . ' days',strtotime(date("Y-m-d")))));
		$sql->update_data_2($mydb, $mytable, "mac", $mac, "startime", date("Y-m-d H:i:s"));
		$sql->update_data_2($mydb, $mytable, "mac", $mac, "proxy", $proxy);
		$sql->update_data_2($mydb, $mytable, "mac", $mac, "playlist", $playlist);
		$sql->update_data_2($mydb, $mytable, "mac", $mac, "allow", "yes");
	}
	
	$sql->disconnect_database();


	echo "<script>";
	echo "window.opener=null;";
  	echo "window.open('','_self');";
  	echo "window.close();";
	echo "</script>"

	//header("Location: custom_list.php");
?>