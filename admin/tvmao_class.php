<?php
include_once 'common.php';

class tvmaoclass {
function update_preview($preview_new,$id,$date,$previewid)
{
	$dates = explode("-",$date);
	$date_seconds = mktime(0,0,0,$dates[1],$dates[2],$dates[0]);

	$sql = new DbSql();

	$mytable = "live_preview_table";
	$sql->connect_database_default();
	$mydb = $sql->get_database();
	$sql->create_database($mydb);
	$sql->create_table($mydb, $mytable, "name longtext, image longtext, 
			url longtext, password longtext, type longtext, 
			preview longtext, id longtext, urlid smallint, hd longtext");
	
	$previewss = $sql->query_data($mydb, $mytable, "urlid", $id, "preview");
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
	
	$preview_add = str_replace(PHP_EOL, '', $preview_add);  
	$preview_add = str_replace('"',"",$preview_add);
	$preview_add = str_replace('"',"",$preview_add);
	$preview_add = str_replace(' ',"",$preview_add);
	$preview_add = str_replace('"',"",$preview_add);
	$preview_add = str_replace('\'',"",$preview_add);
	$preview_add = str_replace('nk','n\k',$preview_add);
	$sql->update_data_2($mydb, $mytable, "id", $previewid, "preview", trim($preview_add));
					
	$sql->disconnect_database();
}

function preview_form_server($id,$previewid,$date)
{
	set_time_limit(0);  
	$w = intval(date('w',strtotime($date)));
	if($w == 0)
		$w = 7;
		
	if(strstr($previewid,"http://") != false)
	{
		$tvmao_url = $previewid . "-" . $w;
	}
	else
	{
		$tvmao_url = $previewid . "-" . $w;
	}
	$preview = file_get_contents("http://enlen.vip/cjepg.php?url=".$tvmao_url);
	echo $preview;
	//$preview = iconv("GBK//IGNORE", "UTF-8" , $preview);
	
	$this->update_preview($preview,$id,$date,$previewid);
	
	return $preview;
}
}
?>