<?php
header('Content-Type:text/html;charset=utf-8');

function tvolleh_preview($previewurl)
{
	$file=file_get_contents($previewurl);	

	$file = strstr($file,"<div class=\"scheduleList\">");
	$pos = strpos($file,"</table>");
	$file=substr($file,0,$pos);
	
	$preview = "";
	
	$value_pre = null;
	$time_pre = null;
	
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
		
		/*
		if(strlen($time) > 3 && strlen($value) > 1)
		{
			$preview = $preview . trim($time) . "#" . trim($value);
			$preview = $preview . "|";
		}
		*/
		if($value_pre != null && $time_pre != null)
		{
			$preview = $preview . trim($time_pre) . "-" . trim($time) . "#" . trim($value_pre);
			$preview = $preview . "|";
		}
		
		$time_pre = $time;
		$value_pre = $value;
		//echo trim($time) . ":" . trim($value) . "<br/>";
	}
	
	$preview = $preview . trim($time_pre) . "-00:00"  . "#" . trim($value_pre);
	$preview = $preview . "|";
			
	return $preview;
}
	
	$url_array = array("CJ오쇼핑"=>"http://tv.olleh.com/renewal_sub/liveTv/pop_schedule_week.asp?ch_no=4&ch_name=CJ%uC624%uC1FC%uD551",
		"MBC"=>"http://tv.olleh.com/renewal_sub/liveTv/pop_schedule_week.asp?chtype=1&ch_no=11&ch_name=MBC",
		"OCN"=>"http://tv.olleh.com/renewal_sub/liveTv/pop_schedule_week.asp?ch_no=21&ch_name=OCN",
		"SBS"=>"http://tv.olleh.com/renewal_sub/liveTv/pop_schedule_week.asp?chtype=1&ch_no=5&ch_name=SBS",
		"KBS2"=>"http://tv.olleh.com/renewal_sub/liveTv/pop_schedule_week.asp?ch_no=7&ch_name=KBS2",
		"KBS1"=>"http://tv.olleh.com/renewal_sub/liveTv/pop_schedule_week.asp?ch_no=9&ch_name=KBS1",
		"JTBC"=>"http://tv.olleh.com/renewal_sub/liveTv/pop_schedule_week.asp?chtype=1&ch_no=15&ch_name=JTBC",
		"tvN"=>"http://tv.olleh.com/renewal_sub/liveTv/pop_schedule_week.asp?chtype=1&ch_no=17&ch_name=tvN",
		"YTN"=>"http://tv.olleh.com/renewal_sub/liveTv/pop_schedule_week.asp?chtype=1&ch_no=24&ch_name=YTN",
		"TV조선"=>"http://tv.olleh.com/renewal_sub/liveTv/pop_schedule_week.asp?chtype=1&ch_no=19&ch_name=TV%uC870%uC120",
		"MBN"=>"http://tv.olleh.com/renewal_sub/liveTv/pop_schedule_week.asp?chtype=1&ch_no=16&ch_name=MBN",
		"채널A"=>"http://tv.olleh.com/renewal_sub/liveTv/pop_schedule_week.asp?chtype=1&ch_no=18&ch_name=%uCC44%uB110A",
		"연합뉴스TV"=>"http://tv.olleh.com/renewal_sub/liveTv/pop_schedule_week.asp?chtype=1&ch_no=23&ch_name=%uC5F0%uD569%uB274%uC2A4TV",
		"EBS1"=>"http://tv.olleh.com/renewal_sub/liveTv/pop_schedule_week.asp?chtype=1&ch_no=13&ch_name=EBS"
	);
	
	$previewid = "";
	if(isset($url_array[$_GET["id"]]))
	{
		$previewid = $url_array[$_GET["id"]];
	}
	else
	{
		exit;	
	}
	
	$nowdate = date('Ymd');
	if($_GET["time"])
		$nowdate = $_GET["time"];
	
	$previewurl = $previewid . "&nowdate=" . trim($nowdate) . "&time=00:00:00";
	
	$previews = tvolleh_preview($previewurl);
	//echo $previews;
	
	$doc = new DOMDocument('1.0', 'utf-8');  // 声明版本和编码 
	$doc->formatOutput = true; 
	$r = $doc->createElement("timeTable"); 
	$doc->appendChild($r); 
	
	$a = $doc->createElement("item");
	$attrname = $doc->createAttribute("name"); 
	$attrdate = $doc->createAttribute("date"); 
	$attrname->appendChild($doc->createTextNode(date("Y-m-d"))); 
	$attrdate->appendChild($doc->createTextNode(date("Ymd")));
	$a->appendChild($attrname);
	$a->appendChild($attrdate);
	
	$rowss = explode("|",$previews);
	//if($rows != null)
	{
		foreach($rowss as $rows) 
		{ 
			$b = $doc->createElement("prgItem");
			 
			$row = explode("#", $rows);
			if(count($row) == 2)
			{
				$date = $doc->createElement("time"); 
    			$date->appendChild($doc->createTextNode($row[0])); 
    			$b->appendChild($date);
			
				$preview = $doc->createElement("name"); 
    			$preview->appendChild($doc->createTextNode($row[1])); 
    			$b->appendChild($preview);
			
				$a->appendChild($b); 
			}
		} 
	}
	
	$r->appendChild($a); 
	echo $doc->saveXML(); 
?>