<?php

header('Content-Type:text/html;charset=utf-8');

function ontvtonight_preview($id,$previewurl)
{
	//echo "previewurl:" . $previewurl;
	
	$file=file_get_contents($previewurl);	
	$file = strstr($file,"<div class=\"span6\">");
	$file = strstr($file,"<tbody>");
	$pos = strpos($file,"</tbody>");
	$file = substr($file,0,$pos-strlen("<tbody>"));
	//echo $file;
	
	$previews = "";
	//echo $file;
	for($ii=0; $ii<512; $ii++)
	{
		$time = "";
		$value = "";
		
		$file = strstr($file,"<tr>");
		if($file == false)
			break;
		$end_pos_tr = strpos($file,"</tr>");
		if($end_pos_tr < 0)
			break;
			
		$content_tr = substr($file,0,$end_pos_tr);
		//echo $content_tr;
		
		$time_start_h5 = strstr($content_tr,"<h5 class=");
		$time_start_pos = strpos($time_start_h5,">");
		$time_end_pos = strpos($time_start_h5,"</h5>");
		$time_tmp = substr($time_start_h5,$time_start_pos+strlen(">"),$time_end_pos-$time_start_pos-strlen(">"));
		//echo "time:" . $time . " ## ";
		$time = str_replace("pm","",$time_tmp);
		$time = str_replace("am","",$time);
		$time = str_replace(" ","",$time);
		
		if(strstr($time_tmp,"pm") != false)
		{
			$times = explode(":",$time);
			if(count($times) == 2)
			{
				if(intval($times[0]) < 12)
					$time = intval($times[0])+12 . ":" . $times[1];	
			}
		}
		
		
		$content_tr = strstr($content_tr,"</h5>");
		
		$value_start_h5 = strstr($content_tr,"<h5 class=");
		$value_start_pos = strpos($value_start_h5,">");
		$value_end_pos = strpos($value_start_h5,"</h5>");
		$content_tr = substr($value_start_h5,$value_start_pos+strlen(">"),$value_end_pos-$value_start_pos-strlen(">"));
		//echo "content_tr:" . $content_tr . "\r\n";
		
		
		$value_start_h5 = strstr($content_tr,"<a href=");
		$value_start_pos = strpos($value_start_h5,">");
		$value_end_pos = strpos($value_start_h5,"</a>");
		$content_tr = substr($value_start_h5,$value_start_pos+strlen(">"),$value_end_pos-$value_start_pos-strlen(">"));
		//echo "content_tr:" . $content_tr . "\r\n";
		
		for($kk=0; $kk<128; $kk++)
		{
			if(strstr($content_tr,"<img src=") != false)
			{
				$value_start_h5 = strstr($content_tr,"<img src=");
				$value_start_pos = strpos($value_start_h5,">");
				$content_tr = trim(substr($value_start_h5,$value_start_pos+strlen(">")));
			}
			else
			{
				$value = trim($content_tr);
				break;
			}
		}
		
		$value = str_replace("&amp;","",$value);
		
		for($kk=0; $kk<128; $kk++)
		{
			if(strstr($value,"&") != false && strstr($value,";") != false)
			{
			
				$tt1 = strpos($value,"&");
				$tt2 = strpos($value,";");
				//echo "AAA:" . substr($value,0,$tt1) . " s:" . $tt1;
				//echo "BBB:" . substr($value,$tt2+1) . " s:" . $tt2;
				$value = substr($value,0,$tt1) . substr($value,$tt2+1);
			}
			else
			{
				break;	
			}
		}
		
		$previews = $previews . $time . "#" . $value . "|";
		
		$file = strstr($file,"</tr>");
		if($file == null)
			break;
	}

	//echo $previews;
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
	//echo "previewss=" . $previewss;
	$offset = -1;
	$previews = array();
	if(strpos($previewss,"$#geminidate#$") != FALSE && strpos($previewss,"$#geminipreview#$") != FALSE)
	{
		$previews = explode("$#geminipreview#$",$previewss);

		for($ii=0; $ii<count($previews); $ii++)
		{
			//echo "<br/> ==previews[ii]==:" . $previews[$ii] . "<br/>";
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
		//echo "==preview==:" . $previews[$ii];
	}
	
	//echo "<br/> ==offset==:" . $offset . "<br/>";
	
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
			//echo "<br/> ==preview_date_seconds_array==:" . $preview_date_seconds_array[$ii] . "<br/>";
		}
		
		for($ii=0; $ii<count($preview_date_seconds_array); $ii++)
		{
			//echo "<br/> array date " . $preview_date_seconds_array[$ii] . "<br/>";
			
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
					//echo "<br/> 1 previews[kk]:" . $previews[$kk] . "===" . $preview_date_seconds_array_all . "<br/>";
					$strosvalue = strncmp($previews[$kk],$preview_date_seconds_array_all,strlen($preview_date_seconds_array_all));
					//echo "<br/> 1 previews[kk] strosvalue :" . $strosvalue;
					if($strosvalue == 0)
					{
						//echo "<br/> 1 previews[kk] OK <br/>";
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
				
				//echo "<br/> 1 preview_add:" . $preview_add . "<br/>";
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
	$date = date('Y-m-d');
	if(isset($_GET["date"]))
		$date = $_GET["date"];
		
	$urlid = $_GET["urlid"];
	$previewid = $_GET["previewid"];
	
	/*
	$mytable = "live_preview_table";
	$sql->connect_database_default();
	$mydb = $sql->get_database();
	$sql->create_database($mydb);
	$sql->create_table($mydb, $mytable, "name longtext, image longtext, 
			url longtext, password longtext, type longtext, 
			preview longtext, id longtext, urlid smallint, hd longtext");
	$previewid = $sql->query_data($mydb, $mytable, "urlid", $urlid, "id");
	$sql->disconnect_database();
	*/
	$previewurl = $previewid;
	
	$previews = ontvtonight_preview($urlid,$previewurl);
	
	//echo $date;
	//echo $previews;
	
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