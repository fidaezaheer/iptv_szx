<?PHP if(Extension_Loaded('zlib')) Ob_Start('ob_gzhandler');Header('Content-type: text/html'); ?>

<?php
	$type = 0;
	
	$page = 0;
	if(isset($_GET["page"]))
		$page = intval($_GET["page"]);
		
	$size = 10;
	$offset = $page*$size;
	
	if(isset($_GET["type"]))
		$type = intval($_GET["type"]);
		
    include_once 'admin/common.php';
	set_zone();
	
	$sql = new DbSql();
	$mytable = "vod_type_table_".$type;
		
	$sql->connect_database_default();
	$mydb = $sql->get_database();
	$sql->create_database($mydb);
	$sql->create_table($mydb, $mytable, "value longtext, id smallint");
	
	$rows = $sql->fetch_datas($mydb, $mytable);
			
	$doc = new DOMDocument('1.0', 'utf-8');  // 声明版本和编码 
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

<?PHP if(Extension_Loaded('zlib')) Ob_End_Flush(); ?>