<?php

function update_watermark()
{
	if ((($_FILES["watermark"]["type"] == "image/gif")
	|| ($_FILES["watermark"]["type"] == "image/jpeg")
	|| ($_FILES["watermark"]["type"] == "image/pjpeg")
	|| ($_FILES["watermark"]["type"] == "image/png")
	|| ($_FILES["watermark"]["type"] == "image/bmp"))
	&& ($_FILES["watermark"]["size"] < 4*1024*1024))
	{
  		if ($_FILES["watermark"]["error"] > 0)
    	{
    		echo "Return Code: " . $_FILES["watermark"]["error"] . "<br />";
    	}
  		else
    	{
    		echo "Upload: " . $_FILES["watermark"]["name"] . "<br />";
    		echo "Type: " . $_FILES["watermark"]["type"] . "<br />";
    		echo "Size: " . ($_FILES["watermark"]["size"] / 1024) . " Kb<br />";
    		echo "Temp file: " . $_FILES["watermark"]["tmp_name"] . "<br />";

    		if (file_exists("../images/livepic/" . $_FILES["watermark"]["name"]))
      		{
      			echo $_FILES["watermark"]["name"] . " already exists. ";
      		}
    		else
      		{
      			move_uploaded_file($_FILES["watermark"]["tmp_name"],
      			"../images/livepic/" . $_FILES["watermark"]["name"]);
      			echo "Stored in: " . "../images/livepic/" . $_FILES["watermark"]["name"];
      		}
    	}
	}
	else
	{
  		//echo "Invalid file";
		//return;
	}		
	
	return $_FILES["watermark"]["name"];
}

	include 'common.php';
	$sql = new DbSql();
	$sql->login_proxy();
	
	if(isset($_POST["password"]))
		$ppassword = trim($_POST["password"]);
	else
		$ppassword = trim($_POST["password"]);
			
	if(isset($_GET["proxy"]))
		$proxy = trim($_GET["proxy"]);
	else
		$proxy = trim($_POST["name"]);
		
	$rupdate = "1";
	if(isset($_POST["rupdate"]))
		$rupdate = $_POST["rupdate"];
	echo "<br/> rupdate = " .  $_POST["rupdate"];
	
	$edit = 0;
	if(isset($_POST["redit"]))
		$edit = $_POST["redit"];

	$sepg = "";
	$slive = "";
	
	if(isset($_POST["tupdate"]))
		$url = $_POST["tupdate"];

	$sbackground = "";
	$ebackground = "";
	
	if(isset($_POST["ptip"]))
		$ptip = $_POST["ptip"];

	$remark = "";
	if(isset($_POST["remark"]))
		$remark = $_POST["remark"];
	
	$ccount = 0;
	if(isset($_POST["raccount"]))
		$ccount = $_POST["raccount"];
		
	$year = "";
	$month = "";
	$day = "";
	$validity = "1970-1-1";
	if(isset($_POST["year"]))
		$year = $_POST["year"];
	if(isset($_POST["month"]))
		$month = $_POST["month"];
	if(isset($_POST["day"]))
		$day = $_POST["day"];
	if(strlen($year) == 4 && strlen($month)>0 && strlen($day)>0)
		$validity = $year."-".$month."-".$day;
		
	$level = 2;
	
	$proxybelong = $_COOKIE["user"];
	
	$allow = $_POST["allow"];
	$version = $_POST["tversion"];
	$scrolltext = $_POST["scrolltext"];
	//echo "<br/><br/>" . $validity;
	
	$panal = 0;
	$pwmd5 = 2;
	$watermark = update_watermark();

	$mydb = $sql->get_database();
	$sql->connect_database_default();
	$sql->create_database($mydb);
	
	$mytable = "proxy_table";
	//$sql->create_table($mydb, $mytable, "name text, password text, ptip text, edit int, watermark text, validity date, allow int, remark text, ccount int");
	$sql->create_table($mydb, $mytable, "name text, password text, ptip text, edit int, watermark text, validity date, allow int, remark text, ccount int, panal int, pwmd5 int, logonum text, terlang int, proxylevel int, proxybelong text");		
	$names = $sql->fetch_datas_where($mydb, $mytable, "name", $proxy);

	if(count($names) <= 0)
	{
		$sql->insert_data($mydb, $mytable, "name, password, ptip, edit, watermark, validity, allow, remark, ccount, panal, pwmd5, logonum, terlang, proxylevel, proxybelong", $proxy.", ".md5(md5($ppassword)).", ". "" .", ".$edit.", ".$watermark.", ".$validity.", ". intval($allow) .", ".$remark.", ".$ccount.", ".$panal.", ".$pwmd5.", ".$pwmd5.", "."".", "."".$level.", ".$proxybelong);
	
	}
	else
	{
		$pwmd5_pre = $sql->query_data($mydb, $mytable, "name",$proxy, "pwmd5");
		if(strlen($ppassword) > 0)
		{
			$sql->update_data_2($mydb, $mytable,"name",$proxy,"password",md5(md5($ppassword)));
		}
		else if($pwmd5_pre == 0 || $pwmd5_pre == 1)
		{
			$password_pre = $sql->query_data($mydb, $mytable, "name",$proxy, "password");
			if($pwmd5_pre == 0)
				$sql->update_data_2($mydb, $mytable,"name",$proxy,"password",md5(md5($password_pre)));
			else if($pwmd5_pre == 1)
				$sql->update_data_2($mydb, $mytable,"name",$proxy,"password",md5($password_pre));
		}
		$sql->update_data_2($mydb, $mytable,"name",$proxy,"ptip",$ptip);
		$sql->update_data_2($mydb, $mytable,"name",$proxy,"edit",$edit);
		$sql->update_data_2($mydb, $mytable,"name",$proxy,"watermark",$watermark);
		$sql->update_data_2($mydb, $mytable,"name",$proxy,"validity",$validity);
		$sql->update_data_2($mydb, $mytable,"name",$proxy,"allow",intval($allow));
		$sql->update_data_2($mydb, $mytable,"name",$proxy,"remark",$remark);
		$sql->update_data_2($mydb, $mytable,"name",$proxy,"ccount",$ccount);
		$sql->update_data_2($mydb, $mytable,"name",$proxy,"pwmd5",$pwmd5);
		$sql->update_data_2($mydb, $mytable,"name",$proxy,"level",$level);
		$sql->update_data_2($mydb, $mytable,"name",$proxy,"proxybelong",$proxybelong);
	}
		
	$mytable = "proxy_download_table";
	$sql->create_table($mydb, $mytable, "name text, sbackground text, sepg text, ebackground text, livepanel text, vodpanel text, download text, allow text, watermark text, scrolltext text, version int");
	$names = $sql->fetch_datas_where($mydb, $mytable, "name", $proxy);
	if(count($names) <= 0)
	{
		$sql->insert_data($mydb, $mytable, "name, sbackground, sepg, ebackground, livepanel, vodpanel, download, allow, watermark, scrolltext, version",
			$proxy.", ".$sbackground.", ".$sepg.", ".$ebackground.", ".$slive.", "."0".", ".$url.", ".intval($allow).", ".$watermark.", ".$scrolltext.", ".$version);
	}
	else
	{
		$sql->update_data_2($mydb, $mytable,"name",$proxy,"sepg",$sepg);
		$sql->update_data_2($mydb, $mytable,"name",$proxy,"livepanel",$slive);
		$sql->update_data_2($mydb, $mytable,"name",$proxy,"download",$url);
		$sql->update_data_2($mydb, $mytable,"name",$proxy,"allow",intval($rupdate));
		$sql->update_data_2($mydb, $mytable,"name",$proxy,"watermark",$watermark);
		$sql->update_data_2($mydb, $mytable,"name",$proxy,"version",$version);
		$sql->update_data_2($mydb, $mytable,"name",$proxy,"scrolltext",$scrolltext);
	}
	
	$sql->disconnect_database();
	
	header("Location: proxy_children_list.php?level=".$level);
	
?>