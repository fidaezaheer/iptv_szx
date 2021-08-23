<?php
	include 'common.php';
	$sql = new DbSql();
	$sql->login_proxy();
	
	$val = urldecode(base64_decode($_GET["name"]));
	
	$level = 2;

	//echo "abc:" . $val;
	
	$sql->connect_database_default();
	$mydb = $sql->get_database();
	$sql->create_database($mydb);
	
	$mytable = "proxy_table";
	$sql->create_table($mydb, $mytable, "name text, password text");
	$sql->delete_data($mydb, $mytable, "name", $val);
	
	$mytable = "proxy_download_table";
	$sql->create_table($mydb, $mytable, "name text, sbackground text, sepg text, ebackground text, livepanel text, vodpanel text, download text, allow text");	
	$sql->delete_data($mydb, $mytable, "name", $val);
	
	
	$mytable = "custom_table";
	$sql->create_table($mydb, $mytable, 
		"mac text,cpu text,ip text,space text, date text,
		time text,allow text, playlist text, online text, allocation text,
		proxy text, balance float,showtime text,contact text,member text,
		panal text,number text,ips text, onescrolltext text, onescrolltexttimes int, 
		numberdate int, scrollcontent text, scrolldate text, scrolltimes text, controlurl text, 		
		controltime int, unbundling int, accessdate timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP, mv text, isupdate int,
		remarks text, startime date, model text");
	
	$sql->update_data($mydb, $mytable, "proxy", $val, "admin");
			
	$sql->disconnect_database();

	header("Location: proxy_children_list.php?level=" . $level);
	
?>