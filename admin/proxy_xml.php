<?php
	include_once "cn_lang.php";
	include_once "common.php";
	$sql = new DbSql();

	$doc = new DOMDocument('1.0', 'utf-8');  // 声明版本和编码 
	$doc->formatOutput = true; 
	$r = $doc->createElement("root"); 
	$doc->appendChild($r);

	$sql->connect_database_default();
	$mydb = $sql->get_database();
	$sql->create_database($mydb);
	$mytable = "proxy_table";
	$sql->create_table($mydb, $mytable, "name text, password text, ptip text, edit int, watermark text, validity date, allow int, remark text, ccount int, panal int, pwmd5 int");	
	$namess = $sql->fetch_datas($mydb, $mytable);
	foreach($namess as $names) {
		$b = $doc->createElement("proxy"); 
		
		$name = $doc->createElement("name"); 
    	$name->appendChild($doc->createTextNode(urlencode($names[0]))); 
    	$b->appendChild($name); 
		
		$id = $doc->createElement("ptip"); 
    	$id->appendChild($doc->createTextNode(urlencode($names[7]))); 
    	$b->appendChild($id); 
		
		$r->appendChild($b);
	}

	$xml = $doc->saveXML();
	echo $xml; 
	$sql->disconnect_database();
?>