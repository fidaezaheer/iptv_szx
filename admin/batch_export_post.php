<?php 
	include 'common.php';
	$sql = new DbSql();
	$sql->login();
	
	include_once 'gemini.php';
	$g = new Gemini();
	
	$export_type = "xml";
	if(isset($_GET["type"]))
		$export_type = $_GET["type"];
		
	Header( "Content-type:   application/octet-stream "); 
	Header( "Accept-Ranges:   bytes "); 
	if($export_type == "xml")
		header( "Content-Disposition:   attachment;   filename=live.xml "); 
	else
		header( "Content-Disposition:   attachment;   filename=live.txt "); 
	header( "Expires:   0 "); 
	header( "Cache-Control:   must-revalidate,   post-check=0,   pre-check=0 "); 
	header( "Pragma:   public ");
	
	$doc = new DOMDocument('1.0', 'utf-8');  // 声明版本和编码 
	$doc->formatOutput = true; 
	$r = $doc->createElement("root"); 
	$doc->appendChild($r); 
	
	$sql->connect_database_default();
	$mydb = $sql->get_database();
	$sql->create_database($mydb);
	
	/*
	$mytable = "live_type_table";
	$sql->create_table($mydb, $mytable, "name longtext, id longtext, lang text");
	$namess = $sql->fetch_datas($mydb, $mytable);
	foreach($namess as $names) {
		$b = $doc->createElement("livetype"); 
		
		$name = $doc->createElement("name"); 
    	$name->appendChild($doc->createTextNode($names[0])); 
    	$b->appendChild($name); 
		
		$id = $doc->createElement("id"); 
    	$id->appendChild($doc->createTextNode($names[1])); 
    	$b->appendChild($id); 
		
		$r->appendChild($b);
	}
	*/
			
	$mytable = "live_preview_table";
	$sql->create_table($mydb, $mytable, "name longtext, image longtext, url longtext, password longtext, type longtext, preview longtext, id longtext, urlid smallint, hd longtext");
	$namess = $sql->fetch_datas_order_asc($mydb, $mytable,"urlid");
	foreach($namess as $names) 
	{
		if($names[7] >= 10000)
			continue;
			
		$urlss = str_replace("&amp;","&",base64_decode($g->j2($names[2])));
		$pwss = str_replace("&amp;","&",$g->j2($names[3]));
		$typess = $names[4];
		
		$urls = explode("geminihighlowgemini",$urlss);
		
		if(count($urls) >= 1)
			$high_urls = explode("|",$urls[0]);
					
		if(count($urls) >= 2)
			$low_urls = explode("|",$urls[1]);
			
		$value = "";
		if(count($urls) >= 1 && strlen($urls[0]) > 1) 
		{
			for($ii=0; $ii<count($high_urls); $ii++)
			{
				$high_url = explode("#",$high_urls[$ii]);
				if(count($high_url) >= 2)
				{
					$value = $value . $high_url[1];
					if($ii <count($high_urls) - 1)
						$value = $value . "#";
				}
			}
		}
			
		if(count($urls) >= 2 && strlen($urls[1]) > 1) 
		{
			for($ii=0; $ii<count($low_urls); $ii++)
			{
				$low_url = explode("#",$low_urls[$ii]);
				if(count($low_url) >= 2)
				{
					$value = $value . $low_url[1];
					if($ii <count($low_urls) - 1)
						$value = $value . "#";
				}
			}
		}		
			
		
		if($export_type == "xml")
		{
		$b = $doc->createElement("liveurl"); 
 
    	$name = $doc->createElement("name"); 
    	$name->appendChild($doc->createTextNode(urlencode($names[0])));  
    	$b->appendChild($name); 
		
		$url = $doc->createElement("url"); 
    	$url->appendChild($doc->createTextNode(urlencode($urlss))); 
    	$b->appendChild($url); 
		
		
		$pw = $doc->createElement("pw"); 
    	$pw->appendChild($doc->createTextNode(urlencode($pwss))); 
    	$b->appendChild($pw);
		
		$type = $doc->createElement("type"); 
    	$type->appendChild($doc->createTextNode($typess)); 
    	$b->appendChild($type);
		
		$r->appendChild($b); 		
		}
		else
		{
		
			echo $names[0] . ","  . $value . "\r\n"; 
		}
	}
	
	
	if($export_type == "xml")
	{
		$xml = $doc->saveXML();
		$xml = strstr($xml,"<?xml");
		echo $xml; 
	}
	
	include_once "cn_lang.php";
	$content =  $lang_array["account_export_post_text1"];
	$date = date("Y-m-d H:i:s");
	$mytable = "log_record_table";
	$sql->create_table($mydb, $mytable, "date datetime, user text, mac text, cpuid text, content text, other text, ip text");
	if($sql->find_column($mydb, $mytable, "ip") == 0)
		$sql->add_column($mydb, $mytable,"ip", "text");	
		
	$sql->insert_data($mydb, $mytable, "date, user, mac, cpuid, content, other, ip", $date.", ".$_COOKIE["user"].", "."".", "."".", ".$content.", "."null".", "."");

	$sql->disconnect_database();
?>