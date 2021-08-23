<?php
	include_once 'common.php';
	$sql = new DbSql();
	$sql->login();

	include_once 'gemini.php';
	$g = new Gemini();
	
	if (($_FILES["file"]["type"] != "text/xml") && ($_FILES["file"]["type"] != "text/plain"))
	{
		header("Location: stream_channel_import_list.php?error=1");
		exit;
	}
	
	$playlistfile = randomkeys(). "." . get_extension($_FILES["file"]["name"]);
	
	if ($_FILES["file"]["error"] > 0)
    {
    	echo "Return Code: " . $_FILES["file"]["error"] . "<br />";
    }
  	else
    {
    	if (file_exists("backup/" . $_FILES["file"]["name"]))
      	{
      		echo $_FILES["file"]["name"] . " already exists. ";
      	}
    	else
      	{
      		move_uploaded_file($_FILES["file"]["tmp_name"],"backup/" . $playlistfile);
      		echo "Stored in: " . "backup/" . $playlistfile;
      	}
	}
	

	$handle = fopen('backup/' . $playlist_file , 'r');
    while(!feof($handle)){
        $l = fgets($handle);
		if(strlen($l) > 16)
			array_push($playlists,$l);
    }
    fclose($handle);

	
	$sql->disconnect_database();
?>