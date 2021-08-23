<?php
  		include_once 'common.php';
		include_once 'gemini.php';
		$g = new Gemini();
		
		set_time_limit(1800);
		
		$del_ids = $_POST["del"];
		
		$sql = new DbSql();
		$sql->login();

			
		$mytable = "live_preview_table";
		$sql->connect_database_default();
		$mydb = $sql->get_database();
		
		$sql->create_database($mydb);
		$sql->create_table($mydb, $mytable, "name longtext, image longtext, url longtext, password longtext, type longtext, preview longtext, id longtext, urlid smallint, hd longtext");
			
		$del_id = explode("|",$del_ids);
		for($ii=0; $ii<count($del_id); $ii++)
		{
			$sql->delete_data($mydb, $mytable, "urlid", $del_id[$ii]);
		}
		
		$sql->disconnect_database();
		
		echo "<script language='javascript' type='text/javascript'>";
		echo "window.location.href='playback_list.php'";
		echo "</script>";
?>