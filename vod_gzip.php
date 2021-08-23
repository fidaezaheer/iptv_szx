<?PHP if(Extension_Loaded('zlib')) Ob_Start('ob_gzhandler');Header('Content-type: text/html'); ?>

<?php

	//date_default_timezone_set('PRC');
	header("Content-Type: text/html; charset=utf-8");
	
	$type = 0;
	
	$page = 0;
	
	$iarea = 0;
	$iyear = 0;
	$itype = "";
	$sort = 0;
	$find = "";
	
	if(isset($_GET["page"]))
		$page = intval($_GET["page"]);
	
	if(isset($_GET["iarea"]))
		$iarea = intval($_GET["iarea"]);
	
	if(isset($_GET["iyear"]))
		$iyear = intval($_GET["iyear"]);
		
	if(isset($_GET["itype"]))
		$itype = $_GET["itype"];
			
	if(isset($_GET["sort"]))
		$sort = intval($_GET["sort"]);
	
	if(isset($_GET["find"]))
		$find = intval($_GET["find"]);
			
	$size = 10;
	$offset = $page*$size;
	
	if(isset($_GET["type"]))
		$type = intval($_GET["type"]);
		
    include 'admin/common.php';
	$sql = new DbSql();
	$mytable = "vod_table_".$type;
		
	$sql->connect_database_default();
	$mydb = $sql->get_database();
	$sql->create_database($mydb);
	$sql->create_table($mydb, $mytable, "name text, image text, 
		url text, area text, year text, type text, intro1 longtext, intro2 longtext, 
		intro3 longtext, intro4 longtext, id int, clickrate int, recommend tinyint, chage float, updatetime int, firstletter text");
		
	//$rows = $sql->fetch_datas_limit_desc($mydb, $mytable, $offset, $size, "id");
	$rows = array();
	
	if(isset($_GET["find"]))
	{
		$value =strtolower($find);
		$rows = $sql->fetch_datas_where_like($mydb, $mytable, "firstletter", $value);
	}
	else if(isset($_GET["iarea"]) || isset($_GET["iyear"]) || isset($_GET["itype"]))
	{
		$cmd = "SELECT * FROM " . $mytable;
		$cmd_v = 0;
	
		if((isset($_GET["iarea"]) && $iarea > 0) || (isset($_GET["iyear"]) && $iyear > 0) || (isset($_GET["itype"]) && strcmp($itype,"0") != 0)) 
			$cmd = $cmd . " WHERE";
		
		if(isset($_GET["iarea"]) && $iarea > 0)
		{
			$cmd_v++;
			$cmd = $cmd . " area='" . $iarea . "'";
		}
	
		if(isset($_GET["iyear"]) && $iyear > 0)
		{
			if($cmd_v > 0)
				$cmd = $cmd . " AND";
			$cmd_v++;
			$cmd = $cmd . " year='" . $iyear . "'";
		}
	
		if(isset($_GET["itype"]) && strcmp($itype,"0") != 0)
		{
			if($cmd_v > 0)
				$cmd = $cmd . " AND";
			$cmd_v++;
			$cmd = $cmd . " type like '%" . sprintf("%03d", intval($itype)) . "%'";
		}

		$cmd = $cmd . " ORDER BY " . "id" . " DESC" . " LIMIT " . $offset . "," . $size;
		//echo $cmd;
		$rows = $sql->fetch_datas_limit_desc_cmd($mydb, $mytable, $cmd);
	}
	else
	{
		if($sort == 1)
		{
			$rows = $sql->fetch_datas_limit_desc($mydb, $mytable, $offset, $size, "clickrate");
		}
		else
		{
			$rows = $sql->fetch_datas_limit_desc($mydb, $mytable, $offset, $size, "updatetime");
		}
	}
	 
	$sql->disconnect_database();
	 
	$doc = new DOMDocument('1.0', 'utf-8');  // �����汾�ͱ��� 
	$doc->formatOutput = true; 
	$r = $doc->createElement("root"); 
	$doc->appendChild($r); 
 
	foreach($rows as $row) 
	{ 
	
		/*
		if(isset($_GET["iarea"]) && check_area($row,$iarea) == false)
			continue;
			
		if(isset($_GET["iyear"]) && check_year($row,$iyear) == false)
			continue;			
		*/
		
		
		//if(isset($_GET["itype"]) && check_type($row,$itype) == false)
		//	continue;
		
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
		 
		$year = $doc->createElement("year"); 
    	$year->appendChild($doc->createTextNode($row[4])); 
    	$b->appendChild($year);

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

<?php
function check_area($row,$area)
{
	if($area == 0)
		return true;
		
	if($row[3] == $area)
		return true;
	else
		return false;
}

function check_year($row,$year)
{
	if($year == 0)
		return true;
		
	if($row[4] == $year)
		return true;
	else
		return false;
}

function check_type($row,$type)
{
	if(strlen($type) <= 0)
		return true;
		
	if(strcmp($type,"0") == 0)
		return true;
	
	$rows = explode("|",$row[5]);	
	
	for($ii=0; $ii<count($rows); $ii++)
	{
		if(strcmp($type,$rows[$ii]) == 0)
			return true;
	}
	
	return false;
}
?>
<?PHP if(Extension_Loaded('zlib')) Ob_End_Flush(); ?>