<?php
header('Content-Type:text/html;charset=utf-8');



				
function transfar_html($v)
{
	
	$t_array = array("&#192;"=>"À","&#193;"=>"Á","&#194;"=>"Â","&#195;"=>"Ã","&#196;"=>"Ä","&#197;"=>"Å","&#198;"=>"Æ",
					"&#199;"=>"Ç","&#200;"=>"È","&#201;"=>"É","&#202;"=>"Ê","&#203;"=>"Ë","&#204;"=>"Ì","&#205;"=>"Í",
					"&#206;"=>"Î","&#207;"=>"Ï","&#208;"=>"Ð","&#209;"=>"Ñ","&#210;"=>"Ò","&#211;"=>"Ó","&#212;"=>"Ô",
					"&#213;"=>"Õ","&#214;"=>"Ö","&#216;"=>"Ø","&#217;"=>"Ù","&#218;"=>"Ú","&#219;"=>"Û","&#220;"=>"Ü",
					"&#221;"=>"Ý","&#222;"=>"Ö","&#223;"=>"ß","&#224;"=>"à","&#225;"=>"á","&#226;"=>"â","&#227;"=>"ã",
					"&#228;"=>"ä","&#229;"=>"å","&#230;"=>"æ","&#231;"=>"ç","&#232;"=>"è","&#233;"=>"é","&#234;"=>"ê",
					"&#235;"=>"ë","&#236;"=>"ì","&#237;"=>"í","&#238;"=>"î","&#239;"=>"ï","&#240;"=>"ð","&#241;"=>"ñ",
					"&#242;"=>"ò","&#243;"=>"ó","&#244;"=>"ô","&#245;"=>"õ","&#246;"=>"ö","&#248;"=>"ø","&#249;"=>"ù",
					"&#250;"=>"ú","&#251;"=>"û","&#252;"=>"ü","&#253;"=>"ý","&#254;"=>"þ","&#255;"=>"ÿ","&#39;"=>"'");
								
	$str = $v;
	for($ii=0; $ii<512; $ii++)
	{
		if(strstr($str,"&#") != false && strstr($str,";") != false)	
		{
			$start_pos = strpos($str,"&#");
			$t = substr($str,$start_pos,6);
			if(isset($t_array[$t]))
			{
				$str = str_replace($t,$t_array[$t],$str);
			}
			else
			{
				$str = str_replace($t,"",$str);
			}
		}
		else
			break;
	}
	
	return $str;
}

function tvmap_preview($id,$previewurl)
{
	//echo "previewurl:" . $previewurl;
	
	$opts = array(   
  		'http'=>array(   
    	'method'=>"GET",   
    	'timeout'=>300,//单位秒  
		'header'=>"Accept-language: zh-cn\r\n" .
              "User-Agent: Mozilla/4.0 (compatible; MSIE 8.0; Windows NT 5.1; Trident/4.0; 4399Box.560; .NET4.0C; .NET4.0E)" .
              "Accept: *//*"
   		)   
	); 
		
	$file=file_get_contents($previewurl, false, stream_context_create($opts));	
	$file = strstr($file,"<ul id=\"timelineul\" class=\"\" style=\"\">");
	$pos = strpos($file,"</ul>");
	$file = substr($file,0,$pos);
	//echo $file;
	
	$previews = "";
	//echo $file;
	for($ii=0; $ii<512; $ii++)
	{
		$time = "";
		$value = "";
		
		$file = strstr($file,"<li");
		if($file == false)
			break;
		$end_pos_tr = strpos($file,"</li>");
		if($end_pos_tr < 0)
			break;
			
		$content_tr = substr($file,0,$end_pos_tr);
		//echo $content_tr;
		
		
		
		$data_start_h5 = strstr($content_tr,"<div class=\"timelineheader\">");
		$data_end_pos = strpos($data_start_h5,"</div>");
		$data_tmp = substr($data_start_h5,strlen("<div class=\"timelineheader\">"),$data_end_pos-strlen("<div class=\"timelineheader\">"));
		
		for($kk=0; $kk<512; $kk++)
		{
			if(strstr($data_tmp,"<") != false)
			{
				$start_pos = strpos($data_tmp,"<");
				$end_pos = strpos($data_tmp,">");	
				$data_tmp = substr($data_tmp,0,$start_pos) . substr($data_tmp,$end_pos+1);
			}
			else
			{
				break;	
			}
		}
		
		
		$data_tmp = trim(transfar_html($data_tmp));
		$data_tmp = str_replace("#","＃",$data_tmp);
		//echo "data_tmp:" . $data_tmp . "\r\n";
		
		
		$time_start_h5 = strstr($content_tr,"<div class=\"hourbox\">");
		$time_end_pos = strpos($time_start_h5,"</div>");
		$time_tmp = substr($time_start_h5,strlen("<div class=\"hourbox\">"),$time_end_pos-strlen("<div class=\"hourbox\">"));
		
		for($kk=0; $kk<512; $kk++)
		{
			if(strstr($time_tmp,"<") != false)
			{
				$start_pos = strpos($time_tmp,"<");
				$end_pos = strpos($time_tmp,">");	
				$time_tmp = substr($time_tmp,0,$start_pos) . substr($time_tmp,$end_pos+1);
			}
			else
			{
				break;	
			}
		}
		
		$time_tmp = trim(str_replace('h',"",$time_tmp));
		$time_tmps = explode(":",$time_tmp);
		if(count($time_tmps) == 2)
		{
			if(intval($time_tmps[0]) <= 4)
			{
				$time_tmp = intval($time_tmps[0])+24 . ":" . $time_tmps[1];
			}
		}
		//echo "time_tmp:" . $time_tmp . "\r\n";
		
		//echo "\r\n";
		$previews = $previews . $time_tmp . "#" . $data_tmp . "|";
		
		$file = strstr($file,"</li>");
		if($file == null)
			break;
	}
	
	
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
	
	$previews = tvmap_preview($urlid,$previewurl);
	
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