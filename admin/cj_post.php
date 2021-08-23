<?php
	include_once 'common.php';
	$sql = new DbSql();
	$sql->login();
	
	$id = intval($_POST["id"]);
	$name = trim($_POST["name"]);
	$url = trim($_POST["url"]);
    $appkey = trim($_POST["appkey"]);
	
	$sql->connect_database_default();
	$mydb = $sql->get_database();
	$sql->create_database($mydb);
	$mytable = "cj_list_table";
	$sql->create_table($mydb, $mytable, "id int, name text, url text, appkey text");
	if(isset($_GET["id"])){
		$sql->update_data_2($mydb, $mytable,"id",$id,"name",$name);	
		$sql->update_data_2($mydb, $mytable,"id",$id,"url",$url);
		$sql->update_data_2($mydb, $mytable,"id",$id,"appkey",$appkey);
	}else{
		$namess = $sql->fetch_datas_order($mydb, $mytable, "id");
		$count = count($namess);
		$id = 1;
		if($count >= 1)
			$id = intval($namess[0][0]) + 1;
		$sql->insert_data($mydb, $mytable, "id, name, url, appkey", $id . ", " . $name . ", ". $url . ", " . $appkey);
	}
	$sql->disconnect_database();
	header("Location: cj_list.php");
?>