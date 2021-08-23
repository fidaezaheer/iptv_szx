<?php
	header("Content-Type: text/html; charset=utf-8");
	include_once 'admindir.php';
	$a = new Adminer();
	include_once $a->ad . 'common.php';
	
	include_once $a->ad . 'memcache.php';
	$mem = new GMemCache();
	$timeout = 1;
	if(get_set_xml_file($addir . "safe2.xml") == 1 && $mem->step1(__FILE__,$timeout) == false)
	{
		exit;		
	}

	$sql = new DbSql();
	 
	$version = 88;
	$key = "";
	if(isset($_GET["version"]) && isset($_GET["key"]))
	{
		$version = intval($_GET["version"]);
		$key = $sql->str_safe($_GET["key"]);	
	}
	check_key_out($version,$key,$a->ad);
	
	set_zone();

	$type = $_GET["type"];
	$vodid = $_GET["id"];
	$sql->connect_database_default();
	$mydb = $sql->get_database();
	$sql->create_database($mydb);
	
	
	$mytable = "vod_table_" . $type;
	$sql->create_table($mydb, $mytable, "name text, image text, 
		url text, area text, year text, type text, intro1 longtext, intro2 longtext, 
		intro3 longtext, intro4 longtext, id int, clickrate int, recommend tinyint, chage float, updatetime int, firstletter text, status int");
	$rows = $sql->fetch_datas_where_no_2($mydb, $mytable, "status", 0, "id", $vodid);
	
	$sql->disconnect_database();
	
	$doc = new DOMDocument('1.0', 'utf-8');  // 声明版本和编码 
	$doc->formatOutput = true; 
	$r = $doc->createElement("root"); 
	$doc->appendChild($r); 

	foreach($rows as $row) 
	{ 
	
   	 	$b = $doc->createElement("item"); 
 
    	$name = $doc->createElement("name"); 
    	$name->appendChild($doc->createTextNode($row[0])); 
    	$b->appendChild($name); 
 
    	$image = $doc->createElement("image"); 
		$image->appendChild($doc->createTextNode($row[1])); 
    	$b->appendChild($image); 
 
    	$url = $doc->createElement("url"); 
    	$url->appendChild($doc->createTextNode($row[2])); 
    	$b->appendChild($url); 
 
    	$area = $doc->createElement("area"); 
    	$area->appendChild($doc->createTextNode($row[3])); 
    	$b->appendChild($area);
		 
		if(intval($row[4]) < 1000)
		{
			$year = $doc->createElement("year"); 
    		$year->appendChild($doc->createTextNode($row[4])); 
    		$b->appendChild($year);

		}
		else
		{
		 	$item_index = 1;
			if(count($item_year) > 0)
			{
				//foreach($item_year as $year) 	
				for($ll=0; $ll<count($item_year); $ll++)
				{
					if($item_year[$ll] == $row[4])
					{
						$item_index = $ll+1;
						break;
					}
				}
			}
			
			$year = $doc->createElement("year"); 
    		$year->appendChild($doc->createTextNode($item_index)); 
    		$b->appendChild($year);
		}
		
		$type = $doc->createElement("type"); 
    	$type->appendChild($doc->createTextNode($row[5])); 
    	$b->appendChild($type);

		$intro1 = $doc->createElement("intro1"); 
    	$intro1->appendChild($doc->createTextNode($row[6])); 
    	$b->appendChild($intro1);

		$intro2 = $doc->createElement("intro2"); 
    	$intro2->appendChild($doc->createTextNode($row[7])); 
    	$b->appendChild($intro2);

		$intro3 = $doc->createElement("intro3"); 
    	$intro3->appendChild($doc->createTextNode($row[8])); 
    	$b->appendChild($intro3);
		
		$intro4 = $doc->createElement("intro4"); 
    	$intro4->appendChild($doc->createTextNode($row[9])); 
    	$b->appendChild($intro4);
		
		$id = $doc->createElement("id"); 
    	$id->appendChild($doc->createTextNode($row[10])); 
    	$b->appendChild($id);

		$clickrate = $doc->createElement("clickrate"); 
    	$clickrate->appendChild($doc->createTextNode($row[11])); 
    	$b->appendChild($clickrate);
		
		$recommend = $doc->createElement("recommend"); 
    	$recommend->appendChild($doc->createTextNode($row[12])); 
    	$b->appendChild($recommend);
		
		$chage = $doc->createElement("chage"); 
    	$chage->appendChild($doc->createTextNode($row[13])); 
    	$b->appendChild($chage);
					
		$updatetime = $doc->createElement("updatetime"); 
    	$updatetime->appendChild($doc->createTextNode($row[14])); 
    	$b->appendChild($updatetime);
													
    	$r->appendChild($b); 

	}
	echo $doc->saveXML(); 
?>