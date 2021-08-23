<?php
header('Content-Type:text/html;charset=utf-8');

function tvolleh_preview($previewurl)
{
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
	
	$url_array = array("CJ오쇼핑"=>"http://tv.olleh.com/renewal_sub/liveTv/pop_schedule_week.asp?ch_no=4&ch_name=CJ%uC624%uC1FC%uD551",
		"MBC Every1"=>"http://tv.olleh.com/renewal_sub/liveTv/pop_schedule_week.asp?ch_no=1&ch_name=MBC Every1",
		"OCN"=>"http://tv.olleh.com/renewal_sub/liveTv/pop_schedule_week.asp?ch_no=21&ch_name=OCN",
		
		"KBS2"=>"http://tv.olleh.com/renewal_sub/liveTv/pop_schedule_week.asp?ch_no=7&ch_name=KBS2",
		"KBS1"=>"http://tv.olleh.com/renewal_sub/liveTv/pop_schedule_week.asp?ch_no=9&ch_name=KBS1"
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
	
	$previewurl = $previewid . "&nowdate=" . trim(date('Ymd')) . "&time=00:00:00";
	
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