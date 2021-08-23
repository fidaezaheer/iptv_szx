<?php
function update_preview($preview_new,$id,$date)
{
	$dates = explode("-",$date);
	$date_seconds = mktime(0,0,0,$dates[1],$dates[2],$dates[0]);

	$sql2 = new DbSql();
	$sql2->login();
	
	$mytable = "live_preview_table";
	$sql2->connect_database_default();
	$mydb = $sql2->get_database();
	$sql2->create_database($mydb);
	$sql2->create_table($mydb, $mytable, "name longtext, image longtext, 
			url longtext, password longtext, type longtext, 
			preview longtext, id longtext, urlid smallint, hd longtext");
	
	$previewss = $sql2->query_data($mydb, $mytable, "urlid", $id, "preview");
	//echo "previewss=" . $previewss;
	$offset = -1;
	$previews = array();
	if(strpos($previewss,"$#geminidate#$") != FALSE && strpos($previewss,"$#geminipreview#$") != FALSE)
	{
		$previews = explode("$#geminipreview#$",$previewss);

		for($ii=0; $ii<count($previews); $ii++)
		{
			echo "<br/> ==previews[ii]==:" . $previews[$ii] . "<br/>";
			$preview = explode("$#geminidate#$",$previews[$ii]);
			
			if(strcmp($preview[0],$date) == 0)
			{
				$offset = $ii;	
				break;
			}
			/*
			$preview_dates = explode("-",$preview[0]);
			$preview_date_seconds = mktime(0,0,0,$preview_dates[1],$preview_dates[2],$preview_dates[0]);
			if($preview_date_seconds == $date_seconds)
			{
				//$preview[1] = $preview;
				$offset = $ii;
				break;
			}
			*/
		}
	}
	else if(strpos($previewss,"$#geminidate#$") != FALSE)
	{
		array_push($previews,$previewss);
		$preview = explode("$#geminidate#$",$previewss);
		/*
		$preview_dates = explode("-",$preview[0]);
		$preview_date_seconds = mktime(0,0,0,$preview_dates[1],$preview_dates[2],$preview_dates[0]);
		if($preview_date_seconds == $date_seconds)
		{
			$offset = 0;
		}
		*/
		if(strcmp($preview[0],$date) == 0)
		{
			$offset = 0;	
		}
	}
	
	for($ii=0; $ii<count($previews); $ii++)
	{
		echo "==preview==:" . $previews[$ii];
	}
	
	echo "<br/> ==offset==:" . $offset . "<br/>";
	
	$preview_add = "";
	if($offset == -1)
	{
		//echo $date;
		$preview_date_seconds_array = array();
		array_push($preview_date_seconds_array,$date);

		if(count($previews) > 0)
		{
			for($ii=0; $ii<count($previews); $ii++)
			{
				$preview = explode("$#geminidate#$",$previews[$ii]);
				$preview_dates = explode("-",$preview[0]);
				//$preview_date_seconds = mktime(0,0,0,$preview_dates[1],$preview_dates[2],$preview_dates[0]);
				array_push($preview_date_seconds_array,$preview[0]);
			}
		}
		rsort($preview_date_seconds_array);
		
		for($ii=0; $ii<count($preview_date_seconds_array); $ii++)
		{
			echo "<br/> ==preview_date_seconds_array==:" . $preview_date_seconds_array[$ii] . "<br/>";
		}
		
		for($ii=0; $ii<count($preview_date_seconds_array); $ii++)
		{
			echo "<br/> array date " . $preview_date_seconds_array[$ii] . "<br/>";
			
			//$preview_dates = explode("-",$preview_date_seconds_array[$ii]);
			//$preview_date_seconds = mktime(0,0,0,$preview_dates[1],$preview_dates[2],$preview_dates[0]);
					
			if($ii > 6)
				break;
			
			if(count($previews) > 0)
			{
				for($kk=0; $kk<count($previews); $kk++)
				{
					
					//$preview_dates = explode("-",$preview_date_seconds_array[$ii]);
					//$preview_date_seconds = mktime(0,0,0,$preview_dates[1],$preview_dates[2],$preview_dates[0]);
					$preview_date_seconds_array_all = $preview_date_seconds_array[$ii]."$#geminidate#$";
					echo "<br/> 1 previews[kk]:" . $previews[$kk] . "===" . $preview_date_seconds_array_all . "<br/>";
					$strosvalue = strncmp($previews[$kk],$preview_date_seconds_array_all,strlen($preview_date_seconds_array_all));
					echo "<br/> 1 previews[kk] strosvalue :" . $strosvalue;
					if($strosvalue == 0)
					{
						echo "<br/> 1 previews[kk] OK <br/>";
						if($ii == 0)
							$preview_add = $previews[$kk];
						else
							$preview_add = $preview_add . "$#geminipreview#$" . $previews[$kk];
							
						break;
					}
				}

				//if($preview_date_seconds == $date_seconds)
				if(strcmp($preview_date_seconds_array[$ii],$date) == 0)
				{
					if($ii == 0)
						$preview_add = $date .  "$#geminidate#$" . $preview_new;
					else
						$preview_add = $preview_add . "$#geminipreview#$" . $date .  "$#geminidate#$" . $preview_new;					
				}
				
				echo "<br/> 1 preview_add:" . $preview_add . "<br/>";
			}
			else
			{
				$preview_add = $date .  "$#geminidate#$" . $preview_new;
			}
		}
	}
	else
	{
		if(count($previews) > 0)
		{
		for($ii=0; $ii<count($previews); $ii++)
		{
			//echo "preview:" . $preview_new;
			if($ii == $offset)
			{
				if($ii == 0)
					$preview_add = $date .  "$#geminidate#$" . $preview_new;
				else
					$preview_add = $preview_add . "$#geminipreview#$" . $date .  "$#geminidate#$" . $preview_new;
			}
			else
			{
				if($ii == 0)
					$preview_add = $previews[$ii];
				else
					$preview_add = $preview_add . "$#geminipreview#$" . $previews[$ii];
			}
		}
		}
		else
			$preview_add = $date .  "$#geminidate#$" . $preview_new;
	}

	//echo "id:" . $id;
	//echo "preview_add:" . $preview_add;
	
	
	$preview_add = str_replace('"',"^",$preview_add);
	$preview_add = str_replace('"',"^",$preview_add);
	$preview_add = str_replace(' ',"\ ",$preview_add);
	$preview_add = str_replace('"',"^",$preview_add);
	$preview_add = str_replace('\'',"^",$preview_add);
	$preview_add = str_replace('nk','n\k',$preview_add);
	$preview_add = str_replace('KEY','\key',$preview_add);	
	$preview_add = str_replace('K','\K',$preview_add);
	$preview_add = str_replace('\k','\k',$preview_add);	
	$sql2->update_data_2($mydb, $mytable, "urlid", $id, "preview", $preview_add);	
				
	$sql2->disconnect_database();
}


	include_once "previews_table.php";
	include_once 'common.php';
	$sql = new DbSql();
	$sql->login();
	
	$id = $_GET["previewid"];
	$w = date("w"); 
	$date = date('Ymd');
	$pw = intval($w)+1;
	if($pw >= 7)
		$pw = 0;
	
	$previews_array = $preview_array[$id][$w][$pw];

	$v = "";
	for($ii=0; $ii<count($previews_array); $ii++)
	{
		//print_r($previews[$ii]);
		$v = $v . $previews_array[$ii][0] . "#" . $previews_array[$ii][1] . "|";
	}
	
	/*
	$v = "<?xml version=\"1.0\" encoding=\"utf-8\"?>";
    $v = $v . "<previews>";
	$v = $v . "<preview>";
	$v = $v . "<date>" . $date . "</date>";
	$v = $v . "<channel></channel>";
	$v = $v . "<value>" . $v . "</value>";
	$v = $v . "</preview>";
	$v = $v . "</previews>";
	*/
	
	//echo "v=" . $v;
	
	$date = date('Y-m-d');
	if(isset($_GET["date"]))
		$date = $_GET["date"];
		
	$urlid = $_GET["urlid"];
	$previewid = $_GET["previewid"];	
	
	$sql->connect_database_default();
	$mydb = $sql->get_database();
	$sql->create_database($mydb);
		
	$mytable = "live_preview_table";
	$sql->create_table($mydb, $mytable, "name longtext, image longtext, url longtext, password longtext, type longtext, preview longtext, id longtext, urlid smallint, hd longtext");
	$previewid = $sql->query_data($mydb, $mytable, "urlid", $urlid, "id");
	$sql->disconnect_database();	
	
	$previews = $v;

	//$previews = iconv("GBK//IGNORE", "UTF-8" , $previews);
	
	echo "v:" . $previews;
	update_preview($previews,$urlid,$date);

	if(isset($_GET["date"]))
	{
		echo "=2=";
		echo "<script language='javascript' type='text/javascript'>";
		echo "window.location.href='timer_preview_all.php?id=". $previewid . "&urlid=" . $urlid . "'";
		echo "</script>";
	}
	else
	{
		echo "=3=";
		echo "<script language='javascript' type='text/javascript'>";
		echo "window.location.href='timer_preview.php'";
		echo "</script>";
	}
?>