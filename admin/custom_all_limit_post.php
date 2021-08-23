<?php
	include 'common.php';
	$sql = new DbSql();
	$sql->login();
	
	$sql->connect_database_default();
	$mydb = $sql->get_database();
	$sql->create_database($mydb);
	$mytable = "limit_mac_table";
	$sql->create_table($mydb, $mytable, "id int, mac text");
	
	if(isset($_GET["limitfile"]))
	{
		$limitfile = $_GET["limitfile"];
		
		//$mytable = "limit_mac_table";
		//$sql->create_table($mydb, $mytable, "id int, mac text");
		
		if(strlen($limitfile) >= 4)
		{
			$ii = 0;
			$handle = fopen('backup/' . $limitfile , 'r');
    		while(!feof($handle))
			{
				$l = fgets($handle);
				$l =  trim(str_replace(":","",$l));
				
				if(strlen($l) > 8)
				{
					$row = $sql->get_row($mydb, $mytable,"mac",strtolower($l));
					if($row == null)
						$sql->insert_data($mydb, $mytable, "id, mac", $ii.", ".strtolower($l));
				}
				
				$ii++;
    		}
    		fclose($handle);
		}	
	}
	
	
	$sql->disconnect_database();


	header("Location: custom_all_edit.php");
?>
