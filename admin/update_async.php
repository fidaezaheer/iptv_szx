<?php
	include_once 'memcache.php';
	$mem = new GMemCache();
	$mem->step_connect_update();
	
	include_once 'common.php';
	$sql = new DbSql();
	$sql->login();
	
	$sql->connect_database_default();
	$mydb = $sql->get_database();
	$sql->create_database($mydb);
		
	for($ii=0; $ii<5; $ii++)
	{
		$cmd = $mem->step_out_update();	
		if($cmd == null || strlen($cmd) <= 0)
		{
			echo "END";
			$sql->disconnect_database();
			$mem->step_close_update();
			exit;
		}
		
		$cmds = explode("**",$cmd); 
		if(count($cmds) >= 3)
		{
			$cmd = base64_decode($cmds[0]); 
			//echo "cmd:" . $cmd . "<br/>";
			$sql->update_data_cmd($mydb,$cmd);
		}
		
		usleep(500000);
	}
	
	
	
	echo $cmd . "<br/>" . "left:" . $mem->step_count_update();
	
	$sql->disconnect_database();
	$mem->step_close_update();
	
/*
	$ip = $_SERVER['SERVER_NAME'];
	$ip2 = $_SERVER['HTTP_HOST'];
	
	//echo "OK IP:" . $ip;
	
	if(strstr($ip,"127.0.0.1") != false && strstr($ip2,"127.0.0.1") != false)
	{
		include_once 'admindir.php';
		$a = new Adminer();
		include_once $a->ad . 'common.php';
		include_once $a->ad . 'table_list.php';
	
		$sql = new DbSql();
		$sql->connect_database_default();
		$mydb = $sql->get_database();
		$sql->create_database($mydb);
		
		$cmd = base64_decode($_GET["cmd"]);
		$cmds = explode("#@#",$cmd); 

		if(count($cmds) >= 2)
		{
			$mytable = $cmds[0];
			
			$sql->create_table($mydb, $mytable, $table_array[$mytable]);
			
			echo "cmds: " . $cmds[1];
			
			$sql->update_data_cmd($mydb,$cmds[1]);
		}
		
		$sql->disconnect_database();
	}
*/
?>