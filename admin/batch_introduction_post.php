<?php
/*
function get_type($alltype, $index, $playlisttypess)
{
	//$playlisttypess = explode("$",$_POST["playlisttype"]);
	for($ii=0; $ii<count($playlisttypess); $ii++)
	{
		$playlisttypes = explode("#",$playlisttypess[$ii]);
		if(strlen($playlisttypes[0]) <= 0)
			return $alltype;
			
		if(intval($index) == intval($playlisttypes[0]))
			return $playlisttypes[1];
	}
	
	return $alltype;
}
*/
?>

<?php
	include 'common.php';
	$sql = new DbSql();
	$sql->login();

	set_time_limit(1800);
	
	include_once 'gemini.php';
	$g = new Gemini();
		
	$playlists=array();

	$saveimge = "";
	
	if(isset($_POST["playlistfile"]))
	{
		$playlist_file = $_POST["playlistfile"];
		
		$handle = fopen('backup/' . $playlist_file , 'r');
    	while(!feof($handle)){
        	$l = fgets($handle);
			if(strlen($l) > 16)
				array_push($playlists,$l);
    	}
    	fclose($handle);
	}	
			
	$sql->connect_database_default();
	$mydb = $sql->get_database();
	$sql->create_database($mydb);
	
	$type = "";
	$type_array = array();
	$mytable = "live_type_table2";
	$sql->create_table($mydb, $mytable, "id text, need int, typepassword text, param0 text, param1 text, param2 text, param3 text");
	$type_namess = $sql->fetch_datas_where($mydb, $mytable, "need", 0);	
	//foreach($type_namess as $type_names) 
	for($ii=0; $ii<count($type_namess); $ii++)
	{
		if($type_namess[$ii][1] == 0)
		{
			$type = $type . $type_namess[$ii][0];
			if($ii<count($type_namess)-1)
				$type = $type . "|";
		}
	}
	
	$type_array = array();
	$mytable = "introduction_table_tmp";
	$sql->create_table($mydb, $mytable, "type text, id text");	
	
	if(isset($_POST["type"]) && strlen($_POST["type"]) > 2)
	{
		$playlisttypess = explode("$",$_POST["type"]);
		
		for($ii=0; $ii<count($playlisttypess); $ii++)
		{
			$playlisttypes = explode("#",$playlisttypess[$ii]);
			if(count($playlisttypes) >= 2)
			{
				$type_namess = $sql->fetch_datas_where($mydb, $mytable, "id", $playlisttypes[0]);
				if($type_namess == null)
				{
					$sql->insert_data($mydb, $mytable, "type, id", $playlisttypes[1].", ".$playlisttypes[0]);
				}
				else
				{
					$sql->update_data_2($mydb, $mytable, "id", $playlisttypes[0], "type", $playlisttypes[1]);
				}
			}
		}
	
	}
	
	$typess = $sql->fetch_datas($mydb, $mytable);
	foreach($typess as $types)
	{
		$type_array[strval($types[1])] =  $types[0];
	}
	
	$mytable = "live_preview_table";
	if(intval($_POST["playlistitype"]) == 0)
	{
		$sql->delete_data_less($mydb,$mytable,"urlid",10000);
	}
	$sql->create_table($mydb, $mytable, "name longtext, image longtext, url longtext, password longtext, type longtext, preview longtext, id longtext, urlid smallint, hd longtext");
	
	$namess = $sql->fetch_datas_order_where_islive($mydb, $mytable, "urlid", "urlid",10000);
	$count = count($namess);
	$id = 1;
	if($count >= 1)
		$id = intval($namess[0][7]) + 1;
	//echo "id = " . $namess[0][7];
		
    for($ii=0; $ii<count($playlists); $ii++)
	{ 
		$arr = explode(",",$playlists[$ii]);
		if(count($arr) >= 2)
		{
			$name = $arr[0];
			$selected_url = str_replace("&amp;","&",$arr[1]);
			$arrs = explode("#",$arr[1]);
			$allurl = "";
			for($kk = 0; $kk<count($arrs); $kk++)
			{
				$links = explode("&link=",$arrs[$kk]);
				if(count($links) >= 2)
				{
					$allurl = $allurl . $kk . "#" . trim($links[0]);
					if($kk < count($arrs) - 1)
						$allurl = $allurl . "|";
				}
				else
				{
					$allurl = $allurl . $kk . "#" . trim($arrs[$kk]);
					if($kk < count($arrs) - 1)
						$allurl = $allurl . "|";
				}
			}
			$en_url = base64_encode(trim($allurl) . "geminihighlowgemini");
			$url = trim($g->j1($en_url));
			
			$allpw = "";
			for($kk = 0; $kk<count($arrs); $kk++)
			{
				$links = explode("&link=",$arrs[$kk]);
				if(count($links) >= 2)
				{
					$allpw = $allpw . $kk . "#" . trim($links[1]);
					if($kk < count($arrs) - 1)
						$allpw = $allpw . "|";				
				}
				else
				{
					$allpw = $allpw . $kk . "#";
					if($kk < count($arrs) - 1)
						$allpw = $allpw . "|";
				}
			}
			$pw = $g->j1($allpw . "geminihighlowgemini");
			
			$sql->insert_data($mydb, $mytable, "name, image, url, password, type, preview, id, urlid, hd", 
				$name.", ".$saveimge.", ". $url .", ". $pw .", ". get_type($type_array,$type,$ii) . ", ". "" . ", " . "null" . ", " . ($ii+$id) . ", ". "hd");
		}
	}
	
	$sql->disconnect_database();

	echo "<script language='javascript' type='text/javascript'>";
	echo "window.location.href='live_preview_list.php'";
	echo "</script>";
	
function get_type($type_array,$alltype, $ii)
{
	if(isset($type_array[strval($ii)]))
		return $type_array[strval($ii)];
	else
		return $alltype;
}
?>