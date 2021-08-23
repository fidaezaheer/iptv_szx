<?php
	include_once 'common.php';
	$sql = new DbSql();
	$sql->login();

	include_once 'gemini.php';
	$g = new Gemini();
	
	if(!isset($_POST["playlistfile"]))
	{
		exit;
	}

	$sql->connect_database_default();
	$mydb = $sql->get_database();
	$sql->create_database($mydb);
	
	$mytable = "live_preview_table";
	if(isset($_POST["playlistitype"]) && intval($_POST["playlistitype"]) == 0)
	{
		$sql->delete_data_less($mydb,$mytable,"urlid",10000);
	}
	$sql->create_table($mydb, $mytable, "name longtext, image longtext, url longtext, password longtext, type longtext, preview longtext, id longtext, urlid smallint, hd longtext, watermark text, lang text");
	
	$namess = $sql->fetch_datas_order_where_islive($mydb, $mytable, "urlid", "urlid",10000);
	$count = count($namess);
	$id = 1;
	$ii = 0;
	if($count >= 1)
		$id = intval($namess[0][7]) + 1;
		
	$doc = new DOMDocument();
	$doc->load("backup/" . $_POST["playlistfile"]);
	$liveurls = $doc->getElementsByTagName("liveurl");
	foreach($liveurls as $liveurl)
	{
		
		$name = $liveurl->getElementsByTagName("name")->item(0)->nodeValue;
		$url = $liveurl->getElementsByTagName("url")->item(0)->nodeValue;
		$pw = $liveurl->getElementsByTagName("pw")->item(0)->nodeValue;
		$type = $liveurl->getElementsByTagName("type")->item(0)->nodeValue;
		
		$name = urldecode($name);
		$url = $g->j1(base64_encode(urldecode($url)));
		$pw = $g->j1(urldecode($pw));
		
		
		$sql->insert_data($mydb, $mytable, "name, image, url, password, type, preview, id, urlid, hd", 
				$name.", "."".", ". $url .", ". $pw .", ". $type . ", ". "" . ", " . "null" . ", " . ($ii+$id) . ", ". "hd");

		$ii++;
		//echo "$title - $author - $publisher\n";
	} 
	
	$sql->disconnect_database();
	
	
	echo "<script language='javascript' type='text/javascript'>";
	echo "window.location.href='live_preview_list.php'";
	echo "</script>";
	
?>