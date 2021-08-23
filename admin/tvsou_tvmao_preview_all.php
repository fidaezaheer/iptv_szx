<?php


function tvolleh_preview($previewid)
{
	$date = date('Ymd');
	$previewurl = $previewid . "&nowdate=" . $date . "&time=00:00:00";
	$previewurl = str_replace(" ","",$previewurl);
	
	$file=file_get_contents($previewurl);	

	$file = strstr($file,"<div class=\"scheduleList\">");
	$pos = strpos($file,"</table>");
	$file=substr($file,0,$pos);
	
	$preview = "";
	
	while(true)
	{
		$file = strstr($file,"<tr>");
		
		$file = strstr($file,"<td class=\"alignC\">");
		if($file == false)
			break;
			
		$pos = strpos($file,"</td>");
		$time = substr($file,strlen("<td class=\"alignC\">"),$pos-strlen("<td class=\"alignC\">"));
		
		$file = strstr($file,"<td>");
		if($file == false)
			break;
			
		$pos = strpos($file,"</td>");
		$value = substr($file,strlen("<td>"),$pos-strlen("<td>"));
		
		
		$value = str_replace("&nbsp;","",$value);
		$value = str_replace("&lt;","",$value);
		$value = str_replace("&gt;","",$value);
		$value = iconv("euc-kr", "UTF-8" , $value);
		
		$file = strstr($file,"<tr>");
		
		if(strlen($time) > 3 && strlen($value) > 1)
		{
			$preview = $preview . trim($time) . "#" . trim($value);
			$preview = $preview . "|";
		}
		
		//echo trim($time) . ":" . trim($value) . "<br/>";
		
	}
	
	return $preview;
}

function tvmao_preview($previewid)
{
	set_time_limit(0);  
	$w = intval(date("w"));
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
	return $preview;
}
?>

<?
function getPreviewValue1($tvmao_url)
{
	$preview = "";
	
	$file=file_get_contents($tvmao_url);

	$file = strstr($file,"<table class=\"timetable\">");
	if($file == false)
		return null;
		
	$pos = strpos($file,"</table>");
	$file=substr($file,0,$pos);

	$i = 0;
	while(true)
	{
	
	$time = "";
	$value = "";
		
	$file=strstr($file,"<tr");
	$pos = strpos($file,"</tr>");
	$tr=substr($file,0,$pos);
	
	$i++;
	if($i <= 1)
	{
		$file = strstr($file,"</tr>");
		if($file == false)
			break;
		else
			continue;
	}
	
	$tr=strstr($tr,"<td>");
	$pos = strpos($tr,"</td>");
	$time=substr($tr,4,$pos-4);
	//echo $time . "\n";

	$tr = substr($tr,$pos+4+1);
	$value="";
	if(strstr($tr,"<a") == false)
	{
		$tr=strstr($tr,"<td>");
		$pos = strpos($tr,"</td>");
		$value=substr($tr,4,$pos-4);
		//echo $value . "\n";
	}
	else
	{
		$tr=strstr($tr,"<td>");
		$pos = strpos($tr,"</td>");
		$value=substr($tr,4,$pos-4);
		//echo $value . "\n";
		
		$sa0 = strpos($value,"<a");
		$sa1 = strpos($value,">");
		
		$ea = strpos($value,"</a>");
		
		$sv0=substr($value,0,$sa0);
		$sv1=substr($value,$sa1+1,$ea-$sa1-1);
		
		$ev=substr($value,$ea+4);
		
		$value = $sv0 . $sv1 . $ev;
		//echo $sv0 . $sv1 . $ev . "\n";
	}
	
	if(strlen($time) > 0 && strlen($value) > 0)
	{
		//$sql->insert_data($mydb, $mytable, "time, value", $time.", ".$value);
		$time = str_replace('#',"~",$time);
		$time = str_replace('|',"~",$time);
			
		$value = str_replace('#',"~",$value);
		$value = str_replace('|',"~",$value);
			
		$preview = $preview . $time . "#" . $value;
	}
	
	$file = strstr($file,"</tr>");
	
	//$preview = $preview . $time . "#" . $value;
	
	if($file == false)
	{
		//$sql->disconnect_database();
		break;
	}
	 
	if(strlen($time) > 0 && strlen($value) > 0)
	{	
		$preview = $preview . "|";
	}
	
	}	
	
	return $preview;
}

function getPreviewValue2($tvmao_url)
{
	$preview = "";
	
	$file=file_get_contents($tvmao_url);

	$file = strstr($file,"<ul  id=\"pgrow\">");
	if($file == false)
		return null;
		
	$pos = strpos($file,"</ul>");
	$file=substr($file,0,$pos);
	
	while(true)
	{
	
		$time = "";
		$value = "";
		
		$file=strstr($file,"<li>");
		$pos = strpos($file,"</li>");
		$li=substr($file,0,$pos);

		$spanvalue = strstr($li,"<span class=\"");
		$pos = strpos($spanvalue,"</span>");
		
		$time=substr($spanvalue,strlen("<span class=\"")+4,$pos-(strlen("<span class=\"")+4));
		
		
		$value = strstr($li,"</span>");
		$value = substr($value,strlen("</span>"));
				
		if(strstr($value,"<div class=\"tvgd\"") != false)
		{
			$value=substr($value,0,strpos($value,"<div class=\"tvgd\""));	
			$value2 = strstr($value,"<a href=\"");
			if($value2 != false)
			{
				$value = substr($value,0,strpos($value,"<a href=\""));
				
				$pos = strpos($value2,">");
				$poe = strpos($value2,"</a>");
				$value = $value . substr($value2,$pos+1,$poe-($pos+1));
			}
		}
		else if(strstr($value,"<a href=\"") != false)
		{
			$value2 = strstr($value,"<a href=\"");
			$value3 = substr($value,0,strpos($value,"<a href=\""));
			
			$pos = strpos($value2,">");
			$poe = strpos($value2,"</a>");
			$value = $value3 . substr($value2,$pos+1,$poe-($pos+1));
			
			//echo "getPreviewValue4 = " . $value;
		}
		
		if(strlen($time) > 0 && strlen($value) > 0)
		{
			//$sql->insert_data($mydb, $mytable, "time, value", $time.", ".$value);
			$time = str_replace('#',"",$time);
			$time = str_replace('|',"",$time);
			
			$value = str_replace(PHP_EOL, '', $value);  
			$value = str_replace('#',"",$value);
			$value = str_replace('|',"",$value);
			
			$preview = $preview . $time . "#" . $value;
		}
	
		$file=strstr($file,"</li>");

		if($file == false)
		{
			//$sql->disconnect_database();
			break;
		}
	 
		if(strlen($time) > 0 && strlen($value) > 0)
		{	
		
			$preview = $preview . "|";
		}
	}
	
	//echo "aaa preview = " . $preview;
	return $preview;
}
?>

<?php
function tvsou_preview1($id)
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
	$w = date('w');
	//echo date('Y-m-d');
	if($w == 0)
		$w = 7;
		
	$previewurl = "http://epg.tvsou.com/" . $id . "W" . $w . ".htm";
	if(strstr($id,"http://") == false)
		$previewurl = "http://epg.tvsou.com/" . $id . "W" . $w . ".htm";
	else
		$previewurl = $id . "W" . $w . ".htm";
	//$previewurl = "http://tvnet898.xicp.net:18006/tvsou2_preview_translate.php?id=" . $id;
	
	$file=file_get_contents($previewurl);
	$file = strstr($file,$playlisttext);
	$pos = strpos($file,"<!--");
	$file=substr($file,0,$pos);

	//echo $file;
	
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

function tvsou_preview2($id)
{
	$preview = "";
	$playlisttext = "<div class=\"tvgenre clear\">";
	$w = date('w');
	//echo date('Y-m-d');
	if($w == 0)
		$w = 7;
		
	$previewurl = "";
	if(strstr($id,"http://") == false)
		$previewurl = "http://epg.tvsou.com/" . $id . "W" . $w . ".htm";
	else
		$previewurl = $id . "W" . $w . ".htm";
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

function tvsou_preview3($previewid)
{
	echo $previewid;
	$file=file_get_contents($previewid);
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

function suntv_preview($previewid)
{
	set_time_limit(0);
	$suntv_url = $previewid."&date=".date('Y-m-d');
	$preview = file_get_contents("http://enlen.vip/cjepg.php?url=".$suntv_url);
	return $preview;
	
}
function yahoo_japan_preview($url)
{
	$xmlstring = file_get_contents($url);
	$xmlstring = iconv("GBK//IGNORE", "UTF-8" , $xmlstring);
	
	$dom = new DOMDocument();
	$dom->loadXML($xmlstring);
	
	$valueElement = $dom->getElementsByTagName("value");
	$value = $valueElement->item(0)->nodeValue;
	
	$value = str_replace('"',"^",$value);
	$value = str_replace('"',"^",$value);
	$value = str_replace(' ',"^",$value);
	$value = str_replace('"',"^",$value);
	$value = str_replace('\'',"^",$value);
	$value = str_replace('nk','n\k',$value);
	$value = str_replace('KEY','\key',$value);	
	$value = str_replace('K','\K',$value);
	$value = str_replace('\k','\k',$value);	
	$value = str_replace('NON',"",$value);
	return $value;
}

function update_preview($sql2,$preview_new,$id,$date,$days)
{
	$dates = explode("-",$date);
	$date_seconds = mktime(0,0,0,$dates[1],$dates[2],$dates[0]);
	
	$mydb = $sql2->get_database();
	$mytable = "live_preview_table";
	$previewss = $sql2->query_data($mydb, $mytable, "urlid", $id, "preview");

	$offset = -1;
	$previews = array();
	if(strpos($previewss,"$#geminidate#$") != FALSE && strpos($previewss,"$#geminipreview#$") != FALSE)
	{
		$previews = explode("$#geminipreview#$",$previewss);

		for($ii=0; $ii<count($previews); $ii++)
		{
			$preview = explode("$#geminidate#$",$previews[$ii]);
			
			if(strcmp($preview[0],$date) == 0)
			{
				$offset = $ii;	
				break;
			}
		}
	}
	else if(strpos($previewss,"$#geminidate#$") != FALSE)
	{
		array_push($previews,$previewss);
		$preview = explode("$#geminidate#$",$previewss);
		if(strcmp($preview[0],$date) == 0)
		{
			$offset = 0;	
		}
	}
		
	$preview_add = "";
	if($offset == -1)
	{
		$preview_date_seconds_array = array();
		array_push($preview_date_seconds_array,$date);

		if(count($previews) > 0)
		{
			for($ii=0; $ii<count($previews); $ii++)
			{
				$preview = explode("$#geminidate#$",$previews[$ii]);
				$preview_dates = explode("-",$preview[0]);
				array_push($preview_date_seconds_array,$preview[0]);
			}
		}
		rsort($preview_date_seconds_array);
		
		for($ii=0; $ii<count($preview_date_seconds_array); $ii++)
		{
			if($ii > $days)
				break;
			
			if(count($previews) > 0)
			{
				for($kk=0; $kk<count($previews); $kk++)
				{
					
					$preview_date_seconds_array_all = $preview_date_seconds_array[$ii]."$#geminidate#$";					
					$strosvalue = strncmp($previews[$kk],$preview_date_seconds_array_all,strlen($preview_date_seconds_array_all));
					if($strosvalue == 0)
					{
						if($ii == 0)
							$preview_add = $previews[$kk];
						else
							$preview_add = $preview_add . "$#geminipreview#$" . $previews[$kk];
							
						break;
					}
				}

				if(strcmp($preview_date_seconds_array[$ii],$date) == 0)
				{
					if($ii == 0)
						$preview_add = $date .  "$#geminidate#$" . $preview_new;
					else
						$preview_add = $preview_add . "$#geminipreview#$" . $date .  "$#geminidate#$" . $preview_new;					
				}
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
			if($ii > $days)
				break;
				
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
	
	$preview_add = str_replace('“',"",$preview_add);
	$preview_add = str_replace('”',"",$preview_add);
	$preview_add = str_replace(' ',"",$preview_add);
	$preview_add = str_replace('"',"",$preview_add);
	$preview_add = str_replace('\'',"",$preview_add);
	$preview_add = str_replace('nk','n\k',$preview_add);
	$preview_add = str_replace('KEY','\key',$preview_add);	
	$preview_add = str_replace('K','\K',$preview_add);
	$preview_add = str_replace('\k','\k',$preview_add);
	$preview_add = str_replace('NON',"",$preview_add);
	$sql2->update_data_2($mydb, $mytable, "urlid", $id, "preview", $preview_add);	
	
}

function checkPreviewss($previewss)
{
	if(strlen($previewss) > 16)
	{
		$date = date('Y-m-d');
		if(strstr($previewss,$date) != false)
		{
			$previews = explode("$#geminipreview#$",$previewss);
			for($ii=0; $ii<count($previews); $ii++)
			{
				$preview = explode("$#geminidate#$",$previews[$ii]);
				if(count($preview) >= 2 && strstr($preview[0],$date) != false )
				{
					if(strlen($preview[1]) > 16)
						return true;
				}
			}
		}	
		else
		{
			return false;
		}
	}	
	else
	{
		return false;
	}
}
?>

<?php
	set_time_limit(1800);

	
		
	include 'common.php';
	$sql = new DbSql();
	
	set_zone();
	
	$date = date('Y-m-d');
	if(isset($_GET["date"]))
		$date = $_GET["date"];
		
	$mytable = "live_preview_table";
	$sql->connect_database_default();
	$mydb = $sql->get_database();
	$sql->create_database($mydb);
	$sql->create_table($mydb, $mytable, "name longtext, image longtext, 
			url longtext, password longtext, type longtext, 
			preview longtext, id longtext, urlid smallint, hd longtext");
			
	$namess = $sql->fetch_datas_order_asc($mydb, $mytable, "urlid");
	
	foreach($namess as $names) 
	{
		$key = $names[6];
		$urlid = $names[7];
		if(strcmp($key,"null") == 0)
		{
			continue;
		}	
		
		$previewss = $names[5];
		if(checkPreviewss($previewss) == true && $_GET["value"] == 0)
			continue;

		if(strstr($key,"https://www.tvsou.com"))
		{
			$preview = tvsou_preview3($key);
		}
		else if(strstr($key,"https://m.tvsou.com"))
		{
			$preview = tvsou_preview4($key);
		}
		else if(strstr($key,"yahoo") != false && strstr($key,".xml") != false)
		{
			$preview = yahoo_japan_preview($key);
		}
		else if(strstr($key,"olleh") != false)
		{
			$preview = tvolleh_preview($key);
		}
		else if(strstr($key,"tvmao") != false)
		{
			$preview = tvmao_preview($key);
		}
		else if(strstr($key,"suntv") != false)
		{
			$preview = suntv_preview($key);
		}
		else if(strstr($key,"TV_") == false && strstr($key,"Channel_") == false && strlen($key) > 5)
		{

			$preview = tvmao_preiview($key);
		}
		else if(strlen($key) > 5)
		{
			$preview = tvsou_preview1($key);
			if(strlen($preview) < 16)
				$preview = tvsou_preview2($key);
		
			$preview = iconv("GBK//IGNORE", "UTF-8" , $preview);
		}
		else 
		{
			continue;
		}
		echo $preview;
		
		if(strlen($preview) > 16)
			update_preview($sql,$preview,$urlid,$date,6);	
	}
	
	$sql->disconnect_database();
	
	if(isset($_GET["type"]) && intval($_GET["type"]) == 1)
	{
		
	}
	else
	{
		echo "<script language='javascript' type='text/javascript'>";
		echo "window.location.href='timer_preview.php?reload=true'";
		echo "</script>";
	}
?>