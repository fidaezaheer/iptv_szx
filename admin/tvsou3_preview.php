<?php
set_time_limit(1800);


function tvsou_preview3($previewid,$date)
{
	$date = str_replace('-',"",$date);
	
	$url = "";
	if($previewid{strlen($previewid)-1} == "/")
		$url = $previewid . $date;
	else
		$url = $previewid . "/" . $date;
	echo $url;
	$file=file_get_contents($url);
	$file=strstr($file,"<ol class=\"font-14 color-3\">");
	$pos = strpos($file,"</ol>");
	$file=substr($file,0,$pos);
	
	$preview = "";
	while(true)
	{
		$file=strstr($file,"<li class");
		if($file == false)
			break;
		
		$pos = strpos($file,"</li>");
		
		$file1=substr($file,strlen("<li class"),$pos);
		$file1=strstr($file1,"<span >");
		$t2=strpos($file1,"</span>");
		$time=substr($file1,strlen("<span >"),$t2-strlen("<span >"));
		
		
		$file1=strstr($file1,"<a");
		$file1=strstr($file1,">");
		$pos = strpos($file1,"</a>");
		$value=substr($file1,strlen(">"),$pos-strlen(">"));
		//$value = iconv("UTF-8//IGNORE", "GB2312" , $value);
		//echo $time . "#" . $value . "<br/>";
		$value = str_replace('#',"",$value);
		$value = str_replace('|',"",$value);
		$value = str_replace(' ',"",$value);
		
		$file = strstr($file,"</li>");
		if($file == false)
			break;
			
		//echo $file1;
		if(strlen($time) >= 4 && strlen($value) >= 2)
			$preview = $preview . $time . "#" . $value . "|";
	}
	
	return $preview;
	//echo $file;
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

	echo "id:" . $id;
	echo "preview_add:" . $preview_add;
	
	
	$preview_add = str_replace('"',"^",$preview_add);
	$preview_add = str_replace('"',"^",$preview_add);
	$preview_add = str_replace(' ',"^",$preview_add);
	$preview_add = str_replace('"',"^",$preview_add);
	$preview_add = str_replace('\'',"^",$preview_add);
	$preview_add = str_replace('nk','n\k',$preview_add);
	$sql2->update_data_2($mydb, $mytable, "urlid", $id, "preview", $preview_add);	
				
	$sql2->disconnect_database();
}





	//TVid=39&Channelid=72&pro=guandong&tid=1
	/*
	if(!isset($_GET["TVid"]))
		return;
	if(!isset($_GET["Channelid"]))
		return;
	if(!isset($_GET["pro"]))
		return;
		
	//if(!isset($_GET["tid"]))
	//	return;
		
	$tvid=$_GET["TVid"];
	$id = $_GET["Channelid"];
	$pro = $_GET["pro"];
	$tid = null;
	
	if(isset($_GET["tid"]))
		$tid = $_GET["tid"];
	
	if($tid != null)	
		$id_all = "TVid=" . $tvid . "&Channelid=" . $id . "&pro=" . $pro . "&tid=" . $tid;
	else
		$id_all = "TVid=" . $tvid . "&Channelid=" . $id . "&pro=" . $pro;
		
	echo "id_all:" . $id_all . "\n";
	*/
	
	/*
	$urlid = 0;
	if(isset($_GET["urlid"]))
		$urlid = intval($_GET["urlid"]);
	*/
	

	include_once 'common.php';
	$sql = new DbSql();
	$sql->login();
	
	$date = date('Y-m-d');
	if(isset($_GET["date"]))
		$date = $_GET["date"];

	$urlid = $_GET["urlid"];
	//$previewid = $_GET["previewid"];	
	
	$sql->connect_database_default();
	$mydb = $sql->get_database();
	$sql->create_database($mydb);
		
	$mytable = "live_preview_table";
	$sql->create_table($mydb, $mytable, "name longtext, image longtext, url longtext, password longtext, type longtext, preview longtext, id longtext, urlid smallint, hd longtext");
	$previewid = $sql->query_data($mydb, $mytable, "urlid", $urlid, "id");
	$sql->disconnect_database();	

	$previews = tvsou_preview3($previewid,$date);
	//echo $previews;
	
	//$previews = iconv("GB2312//IGNORE" , "UTF-8", $previews);
	//$previews = iconv("GBK//IGNORE", "UTF-8" , $previews);
	
	//$previews = mb_convert_encoding($previews,"UTF-8","GBK");
	//$previews = "高清翡翠臺";
	
	
	echo $previews;
	update_preview($previews,$urlid,$date);
	
	if(isset($_GET["date"]))
	{
		echo "=2=";
		//header("Location: timer_preview_all.php?id=" . $previewid . "&urlid=" . $id);
		//header("Location: timer_preview.php");
		echo "<script language='javascript' type='text/javascript'>";
		echo "window.location.href='timer_preview_all.php?id=". $previewid . "&urlid=" . $urlid . "'";
		echo "</script>";
	}
	else
	{
		echo "=3=";
		//header("Location: timer_preview.php");
		echo "<script language='javascript' type='text/javascript'>";
		echo "window.location.href='timer_preview.php'";
		echo "</script>";
	}
?>