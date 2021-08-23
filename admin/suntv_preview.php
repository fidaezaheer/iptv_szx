<?php
header('Content-Type:text/html;charset=utf-8');

function suntv_preview($id,$previewurl)
{
	$xml = simplexml_load_file($previewurl);
    $xml = json_encode($xml);
    $xml = json_decode($xml,true);
		
	$previews = "";
	foreach(array_reverse($xml['epg']['program']) as $row){
		$previews = $previews . date('H:i:s',$row['start_time']) . "#" . $row['title'] . "|";
	}
	$previews = substr($previews,0,strlen($previews)-1);
	echo $previews;
	return $previews;
}

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
	$offset = -1;
	$previews = array();
	if(strpos($previewss,"$#geminidate#$") != FALSE && strpos($previewss,"$#geminipreview#$") != FALSE){
		$previews = explode("$#geminipreview#$",$previewss);
		for($ii=0; $ii<count($previews); $ii++){
			$preview = explode("$#geminidate#$",$previews[$ii]);
			if(strcmp($preview[0],$date) == 0){
				$offset = $ii;	
				break;
			}
		}
	}else if(strpos($previewss,"$#geminidate#$") != FALSE){
		array_push($previews,$previewss);
		$preview = explode("$#geminidate#$",$previewss);
		if(strcmp($preview[0],$date) == 0){
			$offset = 0;	
		}
	}
	
	$preview_add = "";
	if($offset == -1){
		$preview_date_seconds_array = array();
		array_push($preview_date_seconds_array,$date);
		if(count($previews) > 0){
			for($ii=0; $ii<count($previews); $ii++){
				$preview = explode("$#geminidate#$",$previews[$ii]);
				$preview_dates = explode("-",$preview[0]);
				array_push($preview_date_seconds_array,$preview[0]);
			}
		}
		rsort($preview_date_seconds_array);
		for($ii=0; $ii<count($preview_date_seconds_array); $ii++){
			if($ii > 6)
				break;
			
			if(count($previews) > 0){
				for($kk=0; $kk<count($previews); $kk++){
					$preview_date_seconds_array_all = $preview_date_seconds_array[$ii]."$#geminidate#$";
					$strosvalue = strncmp($previews[$kk],$preview_date_seconds_array_all,strlen($preview_date_seconds_array_all));
					if($strosvalue == 0){
						if($ii == 0)
							$preview_add = $previews[$kk];
						else
							$preview_add = $preview_add . "$#geminipreview#$" . $previews[$kk];
							
						break;
					}
				}

				if(strcmp($preview_date_seconds_array[$ii],$date) == 0){
					if($ii == 0)
						$preview_add = $date .  "$#geminidate#$" . $preview_new;
					else
						$preview_add = $preview_add . "$#geminipreview#$" . $date .  "$#geminidate#$" . $preview_new;					
				}
			}else{
				$preview_add = $date .  "$#geminidate#$" . $preview_new;
			}
		}
	}else{
		if(count($previews) > 0){
		    for($ii=0; $ii<count($previews); $ii++){
			    if($ii == $offset){
				    if($ii == 0)
					    $preview_add = $date .  "$#geminidate#$" . $preview_new;
				    else
					    $preview_add = $preview_add . "$#geminipreview#$" . $date .  "$#geminidate#$" . $preview_new;
			    }else{
				    if($ii == 0)
					    $preview_add = $previews[$ii];
				    else
					    $preview_add = $preview_add . "$#geminipreview#$" . $previews[$ii];
			    }
		    }
		}else{
			$preview_add = $date .  "$#geminidate#$" . $preview_new;
		}
	}

	$preview_add = str_replace('"',"",$preview_add);
	$preview_add = str_replace('"',"",$preview_add);
	$preview_add = str_replace(' ',"\ ",$preview_add);
	$preview_add = str_replace('"',"",$preview_add);
	$preview_add = str_replace('\'',"",$preview_add);
	$preview_add = str_replace('nk','n\k',$preview_add);
	$sql2->update_data_2($mydb, $mytable, "urlid", $id, "preview", $preview_add);	
				
	$sql2->disconnect_database();
}

	include 'common.php';
	$sql = new DbSql();
	$sql->login();

	set_time_limit(1800);
	set_time_limit(1800);
	$date = date('Y-m-d');
	if(isset($_GET["date"]))
		$date = $_GET["date"];
		
	$urlid = $_GET["urlid"];
	$previewid =  $_GET["previewid"];
	$previewurl = "http://stream.suntv.tvmining.com/approve/epginfo?channel=".substr($previewid,strripos($previewid,"-")+1)."&date=".$date;

	$previews = suntv_preview($urlid,$previewurl);
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