<?php
	
	if(md5($_GET["key"]) != "27982b1047522b3b2f4c57cc5725c09d")
	{
		echo "key error";
		exit;
	}
	

	
  	include 'common.php';
	$sql = new DbSql();
	//$sql->login();	
		
	include_once 'gemini.php';
	$g = new Gemini();
	
	
	$mytable = "live_preview_table";
	$sql->connect_database_default();
	$mydb = $sql->get_database();
		
	$sql->create_database($mydb);
	$sql->create_table($mydb, $mytable, "name longtext, image longtext, url longtext, password longtext, type longtext, preview longtext, id longtext, urlid smallint, hd longtext, watermark text, lang text");
	
	
	if(isset($_GET["del"]) && isset($_GET["name"]))
	{
		$sql->delete_data($mydb, $mytable,"name",$_GET["name"]);
	}
	else
	{
		if(!isset($_GET["json"]))
		{
			echo "json error";
			exit;	
		}
	
		$json = $_GET["json"];
		$json = base64_decode($json);
		echo $json;
	
		$data = json_decode($json);
		
		$id = $data->id;
		$name = $data->name;
		$url = $data->url;
		$password = $data->password;
		$type = $data->type;
		$hd = $data->hd;
		$previewid = $data->previewid;
	
		$saveimage = $data->image;;
		$preview = null;
		$watermark = null;
		$lang = null;
	
		if((strlen($id) <= 0) || (strlen($name) <= 0) || (strlen($url) <= 0) || (strlen($type) <= 0))
		{
			echo "param error";
			exit;	
		}
	
		$url = "0#" . $url . "geminihighlowgemini";
		$url = base64_encode($url);
		$url = $g->j1($url);
		$password = $g->j1($password);
	
		$row = $sql->get_row($mydb,$mytable,"name",$name);
		if(count($row) >= 1)
		{
			$sql->update_data_2($mydb, $mytable, "name", $name, "urlid", $id);
			$sql->update_data_2($mydb, $mytable, "name", $name, "url", $url);
			$sql->update_data_2($mydb, $mytable, "name", $name, "password", $password);
			$sql->update_data_2($mydb, $mytable, "name", $name, "type", $type);
			$sql->update_data_2($mydb, $mytable, "name", $name, "hd", $hd);
			$sql->update_data_2($mydb, $mytable, "name", $name, "id", $previewid);
			$sql->update_data_2($mydb, $mytable, "name", $name, "image", $saveimage);
		}
		else 
		{
			$sql->insert_data($mydb, $mytable, "name, image, url, password, type, preview, id, urlid, hd, watermark, lang", $name.", ".$saveimage.", ". $url .", ".$password.", ".$type.", ".$preview.", ".$previewid.", ".$id.", ".$hd.", ".$watermark.", ".$lang);
		}
	}
	$sql->disconnect_database();
?>