<?php 
	Header( "Content-type:   application/octet-stream "); 
	Header( "Accept-Ranges:   bytes "); 
	header( "Content-Disposition:   attachment;   filename=type.xml "); 
	header( "Expires:   0 "); 
	header( "Cache-Control:   must-revalidate,   post-check=0,   pre-check=0 "); 
	header( "Pragma:   public "); 

	include 'common.php';
	$sql = new DbSql();
	$sql->login();
	
	include_once 'gemini.php';
	$g = new Gemini();
	
	
	$doc = new DOMDocument('1.0', 'utf-8');  // 声明版本和编码 
	$doc->formatOutput = true; 
	$r = $doc->createElement("root"); 
	$doc->appendChild($r); 
	
	$sql->connect_database_default();
	$mydb = $sql->get_database();
	$sql->create_database($mydb);
	
	$mytable = "live_type_table";
	$sql->create_table($mydb, $mytable, "name longtext, id longtext, lang text");
	$namess = $sql->fetch_datas($mydb, $mytable);
	foreach($namess as $names) {
		$b = $doc->createElement("livetype"); 
		
		$name = $doc->createElement("name"); 
    	$name->appendChild($doc->createTextNode(urlencode($names[0]))); 
    	$b->appendChild($name); 
		
		$id = $doc->createElement("id"); 
    	$id->appendChild($doc->createTextNode($names[1])); 
    	$b->appendChild($id); 
		
		$r->appendChild($b);
	}

	$xml = $doc->saveXML();
	$xml = strstr($xml,"<?xml");
	echo $xml; 
	
	include_once "cn_lang.php";
	$content =  $lang_array["live_type_list_text11"];
	$date = date("Y-m-d H:i:s");
	$mytable = "log_record_table";
	$sql->create_table($mydb, $mytable, "date datetime, user text, mac text, cpuid text, content text, other text, ip text");
	if($sql->find_column($mydb, $mytable, "ip") == 0)
		$sql->add_column($mydb, $mytable,"ip", "text");	
		
	$sql->insert_data($mydb, $mytable, "date, user, mac, cpuid, content, other, ip", $date.", ".$_COOKIE["user"].", "."".", "."".", ".$content.", "."null".", "."");

	$sql->disconnect_database();
?>