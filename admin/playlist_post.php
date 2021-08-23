<?php	
	include 'common.php';
	$sql = new DbSql();
	$sql->login();	
	
	$space = $_POST["spacearea"];
	$playlist = $_POST["url"];
	$addr = $_GET["id"];
	
	$mytable = "playlist_type_table";
	$sql->connect_database_default();
	$mydb = $sql->get_database();
	$sql->create_database($mydb);
	$sql->create_table($mydb, $mytable, "name text, space longtext, id text, playlist longtext");
	//if($addr != "")
	//	$sql->insert_data($mydb, $mytable, "name, space, id", $name.", ".$space.", ".$addr);
		
	$sql->update_data_2($mydb, $mytable,"id",$addr,"space",$space);
	$sql->update_data_2($mydb, $mytable,"id",$addr,"playlist",$playlist);
	
	$sql->disconnect_database();
	
	header("Location: playlist_list.php");
?>