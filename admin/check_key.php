<?php

//include_once "gemini.php";
	
function check_key()	
{
	$keyfile = fopen($_COOKIE["keyfile"], "r");
	$contents = fread($keyfile, 10);
	if(strlen($contents) == 4)
	{
		include_once 'gemini.php';
		$g = new Gemini();
		$key = $g->j2($contents);

		//echo "key:" . $key . "<br/>";
		//echo "testid:" . $_COOKIE[$_COOKIE["keyfile"]] . "<br/>";
		if(strcmp($key,$_COOKIE[$_COOKIE["keyfile"]]) == 0)
		{
			//echo "==";
			return true;	
		}
		else
		{
			//echo "ee";
			return false;	
		}
	}
	else
		return false;
	
}


function write_key()
{
	include_once 'gemini.php';
	$g = new Gemini();
	$filename = $g->j1($_POST["name"]);
	$keyfile = fopen($filename, "w");
	echo "key write:" . $g->j1($_POST["testid"]) . "<br/>";
	fwrite($keyfile,$g->j1($_POST["testid"]));
	fclose($keyfile);
	setcookie("keyfile", $filename);
	setcookie($filename, $_POST["testid"]);
}
?>