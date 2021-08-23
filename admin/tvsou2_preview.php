<?php
set_time_limit(1800);

function tvsou_preview1($id,$date)
{
	$preview = "";
	
	//$id_all = "TVid=" . $tvid . "&Channelid=" . $id . "&pro=" . $pro . "&tid=" . $tid;
	/*
	if($tid != null)	
		$id_all = "TVid=" . $tvid . "&Channelid=" . $id . "&pro=" . $pro . "&tid=" . $tid;
	else
		$id_all = "TVid=" . $tvid . "&Channelid=" . $id . "&pro=" . $pro;
	*/
	
	//$file=file_get_contents("http://m.tvsou.com/epg.asp?" . $id_all);
	
	$playlisttext = "<div  id='PMT1'";
	
	/*
	$w = intval(date("w"));
	if($w == 0)
		$w = 7;
	*/
	
	$w = intval(date('w',strtotime($date)));
	if($w == 0)
		$w = 7;
		
	$previewurl = "http://epg.tvsou.com/" . $id . "W" . $w . ".htm";
	//$previewurl = "http://tvnet898.xicp.net:18006/tvsou2_preview_translate.php?id=" . $id;
	//echo $previewurl;
	$file=file_get_contents($previewurl);
	$file = strstr($file,$playlisttext);
	$pos = strpos($file,"<!--");
	$file=substr($file,0,$pos);
	
	$ii = 0;
	while(true)
	{
	
		$time = "";
		$value = "";
		
		$file=strstr($file,"<font color=");
		$pos = strpos($file,"</font>");
		$time = substr($file,strlen("<font color='#6699CC'>"),$pos-strlen("<font color='#6699CC'>"));
		//echo $time . "|";
		
		$file=strstr($file,"<div id='e2' >");
		$pos = strpos($file,"</div>");
		
		$value = substr($file,strlen("<div id='e2' >"),$pos-strlen("<div id='e2' >"));
		
		if(strstr($value,"<a") != false)
		{
			$sa0 = strpos($value,"<a");
			$sa1 = strpos($value,">");
		
			$ea = strpos($value,"</a>");
		
			$sv0=substr($value,0,$sa0);
			$sv1=substr($value,$sa1+1,$ea-$sa1-1);
		
			//$ev=substr($value,$ea+4);
			$value = $sv0 . $sv1; //. $ev;
		}
		
		if(strstr($value,"(") != false)
		{
			$sa0 = strpos($value,"(");
			$sa1 = strpos($value,")");
			$value=substr($value,0,$sa0);
		}
		
		if(strstr($value,"<") != false)
		{
			$sa0 = strpos($value,"<");
			$value=substr($value,0,$sa0);
		}
		//$value = substr($value,strlen("<a href="),$pos-strlen("<div id='e2' >"));
		
		if(strlen($time) > 0 && strlen($value) > 0)
		{
			$time = str_replace('#',"",$time);
			$time = str_replace('|',"",$time);
			
			$value = str_replace('#',"",$value);
			$value = str_replace('|',"",$value);
			
			$preview = $preview . $time . "#" . $value;
		}
		//echo $file . "\n";
		//echo $playlisttext;
		
		$file_end = strstr($file,$playlisttext);
		if($file_end == false )//|| $ii == 18)
		{
			if(strcmp($playlisttext,"<div  id='PMT1'") == 0)
			{
				$playlisttext = "<div  id='PMT2'";
				if(strlen($time) > 0 && strlen($value) > 0)
				{	
					$preview = $preview . "|";
				}
				continue;
			}
			break;
		}
		else
		{
			$file = $file_end;	
		}
		
		if(strlen($time) > 0 && strlen($value) > 0)
		{	
			$preview = $preview . "|";
		}
		
		$ii++;
	}
	
	return $preview;
}


function tvsou_preview2($id,$date)
{
	$preview = "";
	$playlisttext = "<div class=\"tvgenre clear\">";
	$w = intval(date('w',strtotime($date)));
	if($w == 0)
		$w = 7;
	$previewurl = "http://epg.tvsou.com/" . $id . "W" . $w . ".htm";
	//$previewurl = "http://tvnet898.xicp.net:18006/tvsou2_preview_translate.php?id=" . $id;
	//echo $previewurl;
	$file=file_get_contents($previewurl);
	$file = strstr($file,$playlisttext);
	$pos = strpos($file,"		</div>");
	$file=substr($file,0,$pos);
	
	//echo $file;
	$ii = 0;
	while(true)
	{
	
		$time = "";
		$value = "";
		
		$file=strstr($file,"<li");
		$pos = strpos($file,"</li>");
		
		$li = substr($file,0,$pos);
		
		$li_time1 = strstr($li,"<span style='color:#");
		
		//echo "li:" . iconv("GBK//IGNORE", "UTF-8" , $li_time1) . "<br/>";
		$pos = strpos($li_time1,"</span>");
		$time = substr($li_time1,strlen("<span style='color:#669966'>"),$pos-strlen("<span style='color:#669966'>"));
		//echo "time:" . $time . "<br/>";
		
		$context = "";
		$span_end = strstr($li_time1,"</span>");
		//echo "span_end:" . iconv("GBK//IGNORE", "UTF-8" , $span_end) . "<br/>";
		$pos = strpos($span_end,"<div");
		$context = str_replace("</span>","",$span_end);;
		if($pos != false)
		{
			//echo "div exist";
			$context = substr($span_end,strlen("</span>"),$pos-strlen("</span>"));
		}
		
		$a1 = strstr($context,"target='_blank'>");
		if($a1 != false)
		{
			$pos = strpos($a1,"</a>");
			$context = substr($a1,strlen("target='_blank'>"),$pos-strlen("target='_blank'>"));
		}
		$context = str_replace('#',"",$context);
		$context = str_replace('|',"",$context);
		$context = str_replace(' ',"",$context);
		
		//echo "context:" . iconv("GBK//IGNORE", "UTF-8" , $context) . "<br/>";
		
		if(strlen($time) > 3 && strlen($context) > 1)
		{
			$preview = $preview . $time . "#" . $context;
			$preview = $preview . "|";
		}
		
		$file = strstr($file,"</li>");
		if($file == false)
			break;
	}
	
	return $preview;
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
?>

<?php	

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
	$previewid = $_GET["previewid"];	
	
	$sql->connect_database_default();
	$mydb = $sql->get_database();
	$sql->create_database($mydb);
		
	$mytable = "live_preview_table";
	$sql->create_table($mydb, $mytable, "name longtext, image longtext, url longtext, password longtext, type longtext, preview longtext, id longtext, urlid smallint, hd longtext");
	$previewid = $sql->query_data($mydb, $mytable, "urlid", $urlid, "id");
	$sql->disconnect_database();	
	
	
	$previews = tvsou_preview1($previewid,$date);
	if(strlen($previews) < 16)
		$previews = tvsou_preview2($previewid,$date);
	//$previews = iconv("GB2312//IGNORE" , "UTF-8", $previews);
	$previews = iconv("GBK//IGNORE", "UTF-8" , $previews);
	
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
