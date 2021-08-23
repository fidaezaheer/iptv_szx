<?php
  	include_once 'common.php';
	include_once 'gemini.php';
	$sql = new DbSql();
	$sql->login();

	set_time_limit(0);

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
		remarks text, startime date, model text");
	$sql->update_data_0($mydb, $mytable, "scrollcontent", "");
	
	$sql->disconnect_database();
	
	echo "<script language='javascript' type='text/javascript'>";
	echo "window.location.href='custom_batch_list.php'";
	echo "</script>";
?>