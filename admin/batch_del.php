<?php
  		include_once 'common.php';
		include_once 'gemini.php';
		$sql = new DbSql();
		$sql->login();
		
		$g = new Gemini();
		
		set_time_limit(1800);
		
		$del_ids = $_POST["del"];
		$page = 0;
		if(isset($_POST["page"]))
			$page = $_POST["page"];
					
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
		echo "window.location.href='live_preview_list.php?page=" . $page . "'";
		echo "</script>";
?>