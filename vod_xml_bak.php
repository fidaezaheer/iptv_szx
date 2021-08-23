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
		//echo "reload";
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
	//check_key_out($version,$key,$a->ad);
	
	set_zone();
	
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
		$find = $_GET["find"];
			
	$size = 10;
	$offset = $page*$size;
	
	if(isset($_GET["type"]))
		$type = intval($_GET["type"]);
		
	$sql = new DbSql();
	$sql->connect_database_default();
	$mydb = $sql->get_database();
	$sql->create_database($mydb);
	
	
	$mytable = "vod_name_table";
	$sql->create_table($mydb, $mytable, "id smallint,name text, needps int, password text, num int, total int");
	$types_rows = $sql->fetch_datas($mydb, $mytable);
	
	$mytable = "vod_type_table_".$type;
	$sql->create_table($mydb, $mytable, "value longtext, id smallint");
	$item_years = $sql->query_data($mydb, $mytable, "id", 1 ,"value"); 
	$item_year = explode("|", $item_years);
	
	
	$mytable = "vod_table_".$type;
	$sql->create_table($mydb, $mytable, "name text, image text, 
		url text, area text, year text, type text, intro1 longtext, intro2 longtext, 
		intro3 longtext, intro4 longtext, id int, clickrate int, recommend tinyint, chage float, updatetime int, firstletter text");
		
	//$rows = $sql->fetch_datas_limit_desc($mydb, $mytable, $offset, $size, "id");
	$rows = array();
	
	if(isset($_GET["hot"]))
	{
		$rows = array();
		for($ii=0; $ii<3; $ii++)
		{
			$needps = 0;
			for($kk=0; $kk<count($types_rows); $kk++)
			{
				if(intval($types_rows[$kk][0]) == $ii)
				{
					$needps = intval($types_rows[$kk][2]);
					break;
				}
			}
			
			if($needps == 0)
			{
				$mytable = "vod_table_".$ii;
				$sql->create_table($mydb, $mytable, "name text, image text, 
				url text, area text, year text, type text, intro1 longtext, intro2 longtext, 
				intro3 longtext, intro4 longtext, id int, clickrate int, recommend tinyint, chage float, updatetime int, firstletter text");
		
				$value =strtolower($find);
				$rows_tmp = $sql->fetch_datas_limit_desc($mydb, $mytable,0,10,"clickrate");
				$rows = array_merge_recursive($rows,$rows_tmp);
			}
		}
	}
	else if(isset($_GET["find"]))
	{
		$rows = array();
		for($ii=0; $ii<3; $ii++)
		{
			$needps = 0;
			for($kk=0; $kk<count($types_rows); $kk++)
			{
				if(intval($types_rows[$kk][0]) == $ii)
				{
					$needps = intval($types_rows[$kk][2]);
					break;
				}
			}
			
			if($needps == 0)
			{
				$mytable = "vod_table_".$ii;
				$sql->create_table($mydb, $mytable, "name text, image text, 
				url text, area text, year text, type text, intro1 longtext, intro2 longtext, 
				intro3 longtext, intro4 longtext, id int, clickrate int, recommend tinyint, chage float, updatetime int, firstletter text");
		
				$value =strtolower($find);
				$rows_tmp = $sql->fetch_datas_where_like_like_2($mydb, $mytable, "firstletter", $value, "name", $value);
				$rows = array_merge_recursive($rows,$rows_tmp);
			}
		}
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
			$rows = $sql->fetch_datas_limit_desc_2($mydb, $mytable, $offset, $size, "year","id");
		}
	}
	$sql->disconnect_database();
	 
	$doc = new DOMDocument('1.0', 'utf-8');  //声明版本和编码
	$doc->formatOutput = true; 
	$r = $doc->createElement("root"); 
	$doc->appendChild($r); 
 
 	if($rows != null)
	{
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
