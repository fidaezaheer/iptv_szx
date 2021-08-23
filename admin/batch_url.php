<?php
	include 'common.php';
	$sql = new DbSql();
	$sql->login();
	
	$checked = "";
	$checkeds = array();
	$new_server = $_GET["url0"];
	$old_server = $_GET["url1"];
	
	if(isset($_GET["checked"]))
	{
		$checked = base64_decode($_GET["checked"]);	
		$checkeds = explode("|",$checked);
	}
	
	include_once 'gemini.php';
	$g = new Gemini();
	
	$mytable = "live_preview_table";
	$sql->connect_database_default();
	$mydb = $sql->get_database();
	$sql->create_database($mydb);
	$sql->create_table($mydb, $mytable, "name longtext, image longtext, url longtext, password longtext, type longtext, preview longtext, id longtext, urlid smallint, hd longtext");
	$namess = $sql->fetch_datas($mydb, $mytable);
	foreach($namess as $names)
	{
		if(intval($names[7]) < 10000)
		{
			if(count($checkeds) > 0)
			{
				for($ii=0;$ii<count($checkeds);$ii++)
				{
					if($checkeds[$ii] == $names[7])
					{
						$js_url = base64_decode($g->j2($names[2]));
						$js_url = str_replace($old_server,$new_server,$js_url);
						$js_url = str_replace(" ","%20",$js_url);
						$sql->update_data_2($mydb, $mytable, "urlid", $names[7], "url", $g->j1(base64_encode($js_url)));
					}
				}
			}
			else
			{
				$js_url = base64_decode($g->j2($names[2]));
				$js_url = str_replace($old_server,$new_server,$js_url);
				$js_url = str_replace(" ","%20",$js_url);
				$sql->update_data_2($mydb, $mytable, "urlid", $names[7], "url", $g->j1(base64_encode($js_url)));				
			}
		}
	}

	$sql->disconnect_database();
	
	header("Location: live_preview_list.php");
?>