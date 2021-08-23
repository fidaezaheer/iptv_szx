<?php

	//date_default_timezone_set('PRC');
	include_once 'admin/common.php';
	$sql = new DbSql();
	
	$version = 88;
	$key = "";
	if(isset($_GET["version"]) && isset($_GET["key"]))
	{
		$version = intval($_GET["version"]);
		$key = $sql->str_safe($_GET["key"]);	
	}
	check_key_out($version,$key);
	
	set_zone();
	
	$type = 0;
	
	$page = 0;
	if(isset($_GET["page"]))
		$page = intval($_GET["page"]);
		
	$size = 10;
	$offset = $page*$size;
	
	if(isset($_GET["type"]))
		$type = intval($_GET["type"]);
		
	
	$mytable = "vod_type_table_".$type;
		
	$sql->connect_database_default();
	$mydb = $sql->get_database();
	$sql->create_database($mydb);
	$sql->create_table($mydb, $mytable, "value longtext, id smallint");
	
	$rows = $sql->fetch_datas($mydb, $mytable);
			
	$doc = new DOMDocument('1.0', 'utf-8');  // ÉùÃ÷°æ±¾ºÍ±àÂë 
	$doc->formatOutput = true; 
	$r = $doc->createElement("root"); 
	$doc->appendChild($r); 
 
 
 	$b = $doc->createElement("item"); 
	
	foreach($rows as $row) 
	{ 
 		if($row[1] == 0)
		{
    		$name = $doc->createElement("type"); 
 		}
		else if($row[1] == 1)
		{
			$name = $doc->createElement("year");
		}
		else if($row[1] == 2)
		{
			$name = $doc->createElement("area");
		}
		$name->appendChild($doc->createTextNode($row[0])); 
    	$b->appendChild($name); 
										
    	
	} 
 	$r->appendChild($b); 
	echo $doc->saveXML(); 

?>
