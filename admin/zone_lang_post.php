<?php


	include 'common.php';
	$sql = new DbSql();
	$sql->login();
	
	$zone = $_POST["zone"];
	$terlang = $_POST["terlang_id"];
	$content = "<?php session_start(); \$allowTime = 10; \$allowT = \"geminiallowtime_getprc\"; if(!isset(\$_SESSION[\$allowT])){\$_SESSION[\$allowT] = time();}else if(time() - \$_SESSION[\$allowT]>\$allowTime){\$_SESSION[\$allowT] = time();}else{return;}date_default_timezone_set(\"" . $zone . "\");echo \"adfasdfwefsdfwefasadfefdtime#\" . time() . \"#time\";?>";
	
	$mydb = $sql->get_database();
	$mytable = "system_table";
	$sql->connect_database_default();
	$sql->create_database($mydb);
	$sql->create_table($mydb, $mytable, "name text, value text");
	
	$row = $sql->get_row($mydb,$mytable,"name","zone");
	if(count($row) > 0)
	{
		$sql->update_data_2($mydb, $mytable, "name", "zone", "value", $zone);	
		
		$fpp0 = fopen('zone.dat', 'w');
		fwrite($fpp0, $zone) or die('error');
		fclose($fpp0);
		
		$fpp1 = fopen('../get_prc.php', 'w');
		fwrite($fpp1, $content) or die('error');
		fclose($fpp1);
	}
	else
	{
		$sql->insert_data($mydb, $mytable,"name,value","zone".", ".$zone);
	}
	
	$row = $sql->get_row($mydb,$mytable,"name","terlang");
	if(count($row) > 0)
	{
		$sql->update_data_2($mydb, $mytable, "name", "terlang", "value", $terlang);	
	}
	else
	{
		$sql->insert_data($mydb, $mytable,"name,value","terlang".", ".$terlang);
	}
	
	$sql->disconnect_database();
	
	header("Location: zone_lang_list.php");
?>