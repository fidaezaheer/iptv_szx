<?php

function save_xml($save0)
{
	$dom = new DOMDocument('1.0', 'UTF-8');  
	$dom->formatOutput = true;  
	$rootelement = $dom->createElement("root");  
	$option = $dom->createElement("option");  
    $key = $dom->createElement("key", $save0);  
    $option->appendChild($key);  
    $rootelement->appendChild($option);  
	$dom->appendChild($rootelement);  
	$filename = "setting.xml"; 
	$dom->save($filename); 
}


function save_xml_file($save0,$file)
{
	$dom = new DOMDocument('1.0', 'UTF-8');  
	$dom->formatOutput = true;  
	$rootelement = $dom->createElement("root");  
	$option = $dom->createElement("option");  
    $key = $dom->createElement("key", $save0);  
    $option->appendChild($key);  
    $rootelement->appendChild($option);  
	$dom->appendChild($rootelement);  
	$filename = $file; 
	$dom->save($filename); 
}
?>

<?php		
	include 'common.php';
	$sql = new DbSql();
	$sql->login();
	$sql->connect_database_default();
	$mydb = $sql->get_database();
	$sql->create_database($mydb);
	$mytable = "safe_table";

	$safe0 = 0;
	$safe1 = 0;
	$safe2 = 0;
	$safe3 = 0;
	$safe4 = 0;
	$safe5 = 0;
	$safe6 = 0;
	$safe7 = 0;
	$safe8 = 0;
	$safe9 = 0;
	$model = "";
	
	$id = 0;
	
	if(isset($_GET["safe0"]))
	{
		$safe0 = intval($_GET["safe0"]);
		save_xml($safe0);
	}
	if(isset($_GET["safe1"]))
		$safe1 = intval($_GET["safe1"]);
	if(isset($_GET["safe2"]))
		$safe2 = intval($_GET["safe2"]);
	if(isset($_GET["safe3"]))
		$safe3 = intval($_GET["safe3"]);
	if(isset($_GET["safe4"]))
		$safe4 = intval($_GET["safe4"]);
	if(isset($_GET["safe5"]))
		$safe5 = intval($_GET["safe5"]);
	if(isset($_GET["safe6"]))
		$safe6 = intval($_GET["safe6"]);
	if(isset($_GET["safe7"]))
		$safe7 = intval($_GET["safe7"]);
	if(isset($_GET["safe8"]))
		$safe8 = intval($_GET["safe8"]);
	if(isset($_GET["safe9"]))
		$safe9 = intval($_GET["safe9"]);

	
	$sql->create_table($mydb, $mytable, "id int,safe0 int,safe1 int,safe2 int,safe3 int,safe4 int,
										safe5 int,safe6 int,safe7 int,safe8 int,safe9 int");
																		
	$names = $sql->fetch_datas($mydb, $mytable);
	if(count($names) <= 0)
	{
		save_xml_file($safe2,"safe2.xml");
		$sql->insert_data($mydb, $mytable, "id, safe0, safe1, safe2, safe3, safe4, safe5, safe6, safe7, safe8, safe9",
								 $id . ", " . $safe0 . ", ". $safe1 . ", ". $safe2 . ", ". $safe3 . ", ". $safe4 . ", "
								 . $safe5 . ", ". $safe6 . ", ". $safe7 . ", ". $safe8 . ", ". $safe9);
	}
	else
	{
		if(isset($_GET["safe0"]))
			$sql->update_data_2($mydb, $mytable, "id", $id, "safe0", $_GET["safe0"]);
		if(isset($_GET["safe1"]))
			$sql->update_data_2($mydb, $mytable, "id", $id, "safe1", $_GET["safe1"]);
		if(isset($_GET["safe2"]))
		{
			save_xml_file($safe2,"safe2.xml");
			$sql->update_data_2($mydb, $mytable, "id", $id, "safe2", $_GET["safe2"]);
		}
		if(isset($_GET["safe3"]))
			$sql->update_data_2($mydb, $mytable, "id", $id, "safe3", $_GET["safe3"]);
		if(isset($_GET["safe4"]))
			$sql->update_data_2($mydb, $mytable, "id", $id, "safe4", $_GET["safe4"]);
		if(isset($_GET["safe5"]))
			$sql->update_data_2($mydb, $mytable, "id", $id, "safe5", $_GET["safe5"]);
		if(isset($_GET["safe6"]))
			$sql->update_data_2($mydb, $mytable, "id", $id, "safe6", $_GET["safe6"]);
		if(isset($_GET["safe7"]))
			$sql->update_data_2($mydb, $mytable, "id", $id, "safe7", $_GET["safe7"]);
		if(isset($_GET["safe8"]))
			$sql->update_data_2($mydb, $mytable, "id", $id, "safe8", $_GET["safe8"]);
		if(isset($_GET["safe9"]))
			$sql->update_data_2($mydb, $mytable, "id", $id, "safe9", $_GET["safe9"]);
	}
	
	$safe10 = "0";
	$safe11 = "0";
	$safe12 = "0";
	$safe13 = "0";	//允许的MAC段
	$safe14 = "0";	//禁止的MAC段
	$safe15 = "0";
	$safe16 = "0";
	$safe17 = "0";
	$safe18 = "0";
	$safe19 = "0";
	$limitarea = "";
	$prekey = "";
	$server_request = "";
	$long_time = 0;
	$limitsign = "";
	
	if(isset($_GET["safe10"]))
		$safe10 = $_GET["safe10"];
	if(isset($_GET["safe11"]))
		$safe11 = $_GET["safe11"];
	if(isset($_GET["safe12"]))
		$safe12 = base64_decode($_GET["safe12"]);
	if(isset($_GET["safe13"]))
		$safe13 = $_GET["safe13"];
	if(isset($_GET["safe14"]))
		$safe14 = $_GET["safe14"];
	if(isset($_GET["safe15"]))
		$safe15 = $_GET["safe15"];
	if(isset($_GET["safe16"]))
		$safe16 = $_GET["safe16"];
	if(isset($_GET["safe17"]))
		$safe17 = $_GET["safe17"];
	if(isset($_GET["safe18"]))
		$safe18 = $_GET["safe18"];
	if(isset($_GET["safe19"]))
		$safe19 = $_GET["safe19"];
	if(isset($_GET["model"]))
		$model = $_GET["model"];
	if(isset($_GET["logintime"]))
		$logintime = $_GET["logintime"];
	if(isset($_GET["unbundling"]))
		$unbundling = $_GET["unbundling"];
	if(isset($_GET["disabel_model"]))
		$disabel_mode = $_GET["disabel_model"];	
	if(isset($_GET["limitarea"]))
		$limitarea = $_GET["limitarea"];	
	if(isset($_GET["prekey"]))
		$prekey = $_GET["prekey"];	
	if(isset($_GET["server_request"]))
		$server_request = $_GET["server_request"];
	if(isset($_GET["rloginnum"]))
	{
		save_xml_file($_GET["rloginnum"],"safe3.xml");
	}
	 
	if(isset($_GET["longtime"]))
		$long_time = $_GET["longtime"];
	
	if(isset($_GET["limitsign"]))
		$limitsign = $_GET["limitsign"];
			
	$mytable = "safe_table2";
	//$sql->create_table($mydb, $mytable, "id text,safe10 text,safe11 text,safe12 text,safe13 text,safe14 text,safe15 text,safe16 text,safe17 text,safe18 text,safe19 text,model text,logintime int, unbundling int, disabel_mode text");
	$sql->create_table($mydb, $mytable, "id text,safe10 text,safe11 text,safe12 text,safe13 text,safe14 text,safe15 text,safe16 text,safe17 text,safe18 text,safe19 text,model text,logintime int, unbundling int, disabel_model text, limitarea text, prekey text, server_request text, longtime text, limitsign text");
	
	$names = $sql->fetch_datas($mydb, $mytable);
	if(count($names) <= 0)
	{
		$sql->insert_data($mydb, $mytable, "id, safe10, safe11, safe12, safe13, safe14, safe15, safe16, safe17, safe18, safe19, model, logintime, unbundling, disabel_model, limitarea, prekey, server_request, longtime, limitsign",
								 "0" . ", " . $safe10 . ", ". $safe11 . ", ". $safe12 . ", ". $safe13 . ", ". $safe14 . ", "
								 . $safe15 . ", ". $safe16 . ", ". $safe17 . ", ". $safe18 . ", ". $safe19. ", ". $model . ", ". $logintime . ", ". $unbundling . ", " . $disabel_mode . ", " . $limitarea . ", " . $prekey
								 . ", " . $server_request . ", " . $long_time . ", " . $limitsign);
	}
	else
	{
		if(isset($_GET["safe10"]))
			$sql->update_data_2($mydb, $mytable, "id", "0", "safe10", $_GET["safe10"]);
		if(isset($_GET["safe11"]))
			$sql->update_data_2($mydb, $mytable, "id", "0", "safe11", $_GET["safe11"]);
		if(isset($_GET["safe12"]))
			$sql->update_data_2($mydb, $mytable, "id", "0", "safe12", $safe12);
		if(isset($_GET["safe13"]))
			$sql->update_data_2($mydb, $mytable, "id", "0", "safe13", $_GET["safe13"]);
		if(isset($_GET["safe14"]))
			$sql->update_data_2($mydb, $mytable, "id", "0", "safe14", $_GET["safe14"]);
		if(isset($_GET["safe15"]))
			$sql->update_data_2($mydb, $mytable, "id", "0", "safe15", $_GET["safe15"]);
		if(isset($_GET["safe16"]))
			$sql->update_data_2($mydb, $mytable, "id", "0", "safe16", $_GET["safe16"]);
		if(isset($_GET["safe17"]))
			$sql->update_data_2($mydb, $mytable, "id", "0", "safe17", $_GET["safe17"]);
		if(isset($_GET["safe18"]))
			$sql->update_data_2($mydb, $mytable, "id", "0", "safe18", $_GET["safe18"]);
		if(isset($_GET["safe19"]))
			$sql->update_data_2($mydb, $mytable, "id", "0", "safe19", $_GET["safe19"]);
		if(isset($_GET["model"]))
			$sql->update_data_2($mydb, $mytable, "id", "0", "model", $_GET["model"]);
		if(isset($_GET["logintime"]))
			$sql->update_data_2($mydb, $mytable, "id", "0", "logintime", $_GET["logintime"]);
		if(isset($_GET["unbundling"]))
			$sql->update_data_2($mydb, $mytable, "id", "0", "unbundling", $_GET["unbundling"]);
		if(isset($_GET["disabel_model"]))
			$sql->update_data_2($mydb, $mytable, "id", "0", "disabel_model", $_GET["disabel_model"]);
		if(isset($_GET["limitarea"]))
			$sql->update_data_2($mydb, $mytable, "id", "0", "limitarea", $_GET["limitarea"]);
		if(isset($_GET["prekey"]))
			$sql->update_data_2($mydb, $mytable, "id", "0", "prekey", $_GET["prekey"]);
		if(isset($_GET["server_request"]))
			$sql->update_data_2($mydb, $mytable, "id", "0", "server_request", $_GET["server_request"]);
		if(isset($_GET["longtime"]))
			$sql->update_data_2($mydb, $mytable, "id", "0", "longtime", $_GET["longtime"]);
		if(isset($_GET["limitsign"]))
			$sql->update_data_2($mydb, $mytable, "id", "0", "limitsign", $_GET["limitsign"]);
	}									
	
	$sql->disconnect_database();
	
	header("Location:safe_list.php");
?>