<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="content-type" content="text/html; charset=utf-8" />
<title>404</title>
<style type="text/css">
* { margin:0; padding:0; list-style:none; font-size:14px;}/*---该css reset仅用于demo，请自行换成适合您自己的css reset---*/
html { height:100%;}
body { height:100%; text-align:center;}
.centerDiv { display:inline-block; zoom:1; *display:inline; vertical-align:middle; text-align:left; width:576px; padding:0px; font-size: 36px; color: #FF0;}
.hiddenDiv { height:100%; overflow:hidden; display:inline-block; width:1px; overflow:hidden; margin-left:-1px; zoom:1; *display:inline; *margin-top:-1px; _margin-top:0; vertical-align:middle;}
</style>
</head>

<?php
	include_once 'admindir.php';
	
	$a = new Adminer();
	$addir = $a->ad;
	include_once $addir . 'common.php';
	include_once 'gemini.php';
	include_once 'cn_lang.php';
	include_once $addir . 'memcache.php';
	
	$sql = new DbSql();
	$sql->connect_database_default();
	$mydb = $sql->get_database();
	$sql->create_database($mydb);
	
	$net_version = "";
	$update_model = "";
	$libforcetv_version = "0";
	$libforcetv_open = "0";
	$libforcetv_md5 = "";
	$mytable = "version_table";
	$sql->create_table($mydb, $mytable, "name longtext, value longtext");
	$version_namess = $sql->fetch_datas($mydb, $mytable);
	foreach($version_namess as $version_names) {
		if($version_names[0] == "libforcetv_md5")
		{
			$libforcetv_md5 = $version_names[1];
		}
	}


?>

<script>

function on_keyback()
{
	window.Authentication.exitApp();
}

function show_id()
{
	var soUrl = window.Authentication.CTCgetEpg() + "so/libforcetv.so";
	var sofile = "libforcetv";
	var md5= "<?php echo $libforcetv_md5 ?>";
	var version = <?php echo $_GET["version"] ?>;
		
	if(window.Authentication.CTCIsExistsInterface('CTCLoadLIbs') == true)
		window.Authentication.CTCLoadLIbs(soUrl,md5,sofile,version);
}

</script>

<body onload="show_id()">

</body>
</html>