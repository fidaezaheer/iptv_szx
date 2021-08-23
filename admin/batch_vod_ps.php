<?php
	include 'common.php';
	$sql = new DbSql();
	$sql->login();
	
	$new_server = $_GET["ps0"];
	$old_server = $_GET["ps1"];
	$type = $_GET["type"];
	
	include_once 'gemini.php';
	$g = new Gemini();
	
	$mytable = "vod_table_".$_GET["type"];
	$sql->connect_database_default();
	$mydb = $sql->get_database();
	$sql->create_database($mydb);
	$sql->create_table($mydb, $mytable, "name text, image text, url text, area text, year text, 
										type text, intro1 longtext, intro2 longtext, intro3 longtext, intro4 longtext, 
										id int, clickrate int,recommend tinyint, chage float, updatetime int, 
										firstletter text");
										
	$namess = $sql->fetch_datas($mydb, $mytable);
	foreach($namess as $names)
	{
		$js_urlsss = $g->j4($names[2]);
		$js_urlss = explode("|",$js_urlsss);
		$new_js_urlsss = "";
		$ii = 0;
		foreach($js_urlss as $js_urls)
		{
			
			$js_url = explode("geminipwgemini",$js_urls);
			if(count($js_url) > 0)
			{
				$new_js_url = $js_url[0];
				$new_js_ps = "";
				if(count($js_url) > 1)
					$new_js_ps = str_replace($old_server,$new_server,$js_url[1]);
				$new_js_urlsss = $new_js_urlsss . $new_js_url . "geminipwgemini" . $new_js_ps;
				if($ii < count($js_urlss) - 1)
					$new_js_urlsss = $new_js_urlsss . "|";
			}	
			$ii++;
		}
		/*
		$js_urls = explode("geminipwgemini",$js_url);
		if(count($js_urls) > 1)
			$js_url = $js_urls[0];
		echo "js_url:" . $js_url;
		$js_url = str_replace($old_server,$new_server,$js_url);
		*/
		//echo "<br/><br/>js_urls:" . $new_js_urlsss;
		$sql->update_data_2($mydb, $mytable, "id", $names[10], "url", $g->j3($new_js_urlsss));
	}

	$sql->disconnect_database();
	
	header("Location: vod_list.php?type=" . $type);
?>