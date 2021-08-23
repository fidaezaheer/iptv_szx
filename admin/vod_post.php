<?php

  /*$dom = new DOMDocument();
  if(isset($_GET["vod"]))
  	$dom->load("vod" . $_GET["vod"] . ".xml");
  else
  	$dom->load("vod0.xml");
	
  $channel = $dom->getElementsByTagName("channel"); 
  $channel = $channel->item(0);
  
  $contents = $channel->getElementsByTagName("content"); 
  
  foreach( $contents as $content )
  {
		
		$name_t = $content->getElementsByTagName("name")->item(0)->nodeValue;
		
		if($name_t == $_POST["name"])
		{
			echo "上传的频道名字已经存在，请重新输入!";
			echo "<a href='vod_list.php?vod=" . $_GET["vod"] . "'>返回</a>";
			return;
		}
		
  }*/
  		//date_default_timezone_set('PRC');
		
function resizeImage($im,$maxwidth,$maxheight,$name,$filetype)
{
  	$pic_width = imagesx($im);
  	$pic_height = imagesy($im);
 
 	$resizewidth_tag = false;
	
  	if(($maxwidth && $pic_width > $maxwidth) || ($maxheight && $pic_height > $maxheight))
  	{
   		if($maxwidth && $pic_width>$maxwidth)
   		{
    		$widthratio = $maxwidth/$pic_width;
    		$resizewidth_tag = true;
   		}
 
   		if($maxheight && $pic_height>$maxheight)
   		{
    		$heightratio = $maxheight/$pic_height;
    		$resizeheight_tag = true;
   		}
 
   		if($resizewidth_tag && $resizeheight_tag)
   		{
    		if($widthratio<$heightratio)
     			$ratio = $widthratio;
    		else
     			$ratio = $heightratio;
   		}
 
   		if($resizewidth_tag && !$resizeheight_tag)
    		$ratio = $widthratio;
   		if($resizeheight_tag && !$resizewidth_tag)
    		$ratio = $heightratio;
 
   		$newwidth = $pic_width * $ratio;
   		$newheight = $pic_height * $ratio;
 
   		if(function_exists("imagecopyresampled"))
   		{
    		$newim = imagecreatetruecolor($newwidth,$newheight);//PHP系统函数
      		imagecopyresampled($newim,$im,0,0,0,0,$newwidth,$newheight,$pic_width,$pic_height);//PHP系统函数
   		}
   		else
   		{
    		$newim = imagecreate($newwidth,$newheight);
      		imagecopyresized($newim,$im,0,0,0,0,$newwidth,$newheight,$pic_width,$pic_height);
   		}
 
   		$name = $name.$filetype;
   		imagejpeg($newim,$name);
   		imagedestroy($newim);
  	}
  	else
  	{
   		$name = $name.$filetype;
   		imagejpeg($im,$name);
  	}
}
 
function char_to_char($v1)
{
	$v2 = $v1;
	
	$v2 = str_replace('\\',"/",$v2);
	$v2 = str_replace('“',"",$v2);
	$v2 = str_replace('”',"",$v2);
	$v2 = str_replace(' ',"\ ",$v2);
	$v2 = str_replace('"',"\"",$v2);
	$v2 = str_replace('\'',"",$v2);
	$v2 = str_replace('nk','n\k',$v2);
	
	return $v2;
}

		header("Content-Type: text/html; charset=utf-8");
		
  		include_once 'common.php';
		include_once 'gemini.php';
		include_once "get_pingying.php";
		
		$g = new Gemini();
		$g->check_version();
		
		set_zone();
		
		$sql = new DbSql();
		$sql->login();
		
		$mytable = "vod_table_".$_GET["type"];
		if($_FILES["file"]["name"] != null)
			$saveimge = randomkeys(). "." . get_extension($_FILES["file"]["name"]);
		else if(strlen($_POST["iconurl"]) > 12)
			$saveimge = $_POST["iconurl"];
			
		$updatetime=time();
		
		$sql->connect_database_default();
		$mydb = $sql->get_database();
		
		$sql->create_database($mydb);
		//$sql->delete_table($mydb,$mytable);
		$sql->create_table($mydb, $mytable, "name text, image text, url text, area text, year text, 
			type text, intro1 longtext, intro2 longtext, intro3 longtext, intro4 longtext,
			id int, clickrate int, recommend tinyint, chage float, updatetime int, 
			firstletter text, status int");
		
		$url = 	str_replace(" ","%20",$_POST["url"]); 
		$url = 	str_replace("&amp;","&",$_POST["url"]); 
		$url = trim($url);
		
		if(isset($_GET["id"]))
		{
			$sql->update_data_2($mydb, $mytable, "id", $_GET["id"], "name", trim($_POST["name"]));
			$sql->update_data_2($mydb, $mytable, "id", $_GET["id"], "url", $g->j3($url));
	
			if($_FILES["file"]["name"] != null)
				$sql->update_data_2($mydb, $mytable, "id", $_GET["id"], "image", $saveimge);
			else if(strlen($_POST["iconurl"]) > 12)
				$sql->update_data_2($mydb, $mytable, "id", $_GET["id"], "image", $_POST["iconurl"]);
				
			$sql->update_data_2($mydb, $mytable, "id", $_GET["id"], "year", $_POST["year"]);
			$sql->update_data_2($mydb, $mytable, "id", $_GET["id"], "area", $_POST["area"]);
			$sql->update_data_2($mydb, $mytable, "id", $_GET["id"], "type", $_POST["livetype"]);

			$sql->update_data_2($mydb, $mytable, "id", $_GET["id"], "recommend", $_POST["recommend"]);

			$sql->update_data_2($mydb, $mytable, "id", $_GET["id"], "intro2", char_to_char($_POST["introduction2"]));
			$sql->update_data_2($mydb, $mytable, "id", $_GET["id"], "intro3", char_to_char($_POST["introduction3"]));
			$sql->update_data_2($mydb, $mytable, "id", $_GET["id"], "intro4", char_to_char($_POST["introduction4"]));
	
			$sql->update_data_2($mydb, $mytable, "id", $_GET["id"], "updatetime", $updatetime);
			
			if(strlen($_POST["firstletter"]) > 0)
			{
				$sql->update_data_2($mydb, $mytable, "id", $_GET["id"], "firstletter", strtolower($_POST["firstletter"]));		
			}
			else
			{
				$firstletter = strtolower(pinyin1(trim($_POST["name"])));
				$sql->update_data_2($mydb, $mytable, "id", $_GET["id"], "firstletter", $firstletter);	
			}
			
			$sql->update_data_2($mydb, $mytable, "id", $_GET["id"], "status", intval($_POST["vodstatus"]));	
			
		}
		else
		{
			$namess = $sql->fetch_datas_order($mydb, $mytable, "id");
			$count = count($namess);
			$id = 0;
			$change = 0;
			if($count >= 1)
				$id = intval($namess[0][10]) + 1;

			$firstletter = "";
			if(strlen(trim($_POST["firstletter"])) > 0)
				$firstletter = trim($_POST["firstletter"]);
			else
				$firstletter = pinyin1(trim($_POST["name"]));
			
			$sql->insert_data($mydb, $mytable, "name, image, url, area, year, type, intro1, intro2, intro3, intro4, id, clickrate, recommend, chage, updatetime, firstletter, status", 
				$_POST["name"].", ". $saveimge .", ".$g->j3($url). ", ". $_POST["area"] . ", ". $_POST["year"] . ", " . $_POST["livetype"] . ", " . char_to_char($_POST["introduction2"]).", ".char_to_char($_POST["introduction2"]).", ". char_to_char($_POST["introduction3"]).", ". char_to_char($_POST["introduction4"]) .", " . $id . ", ". 0 . ", ". $_POST["recommend"]. ", ". $change . ", ". $updatetime. ", ". strtolower($firstletter).", ".intval($_POST["vodstatus"]));
		}
		
		$sql->disconnect_database();
  

if ((($_FILES["file"]["type"] == "image/gif")
|| ($_FILES["file"]["type"] == "image/jpeg")
|| ($_FILES["file"]["type"] == "image/pjpeg")
|| ($_FILES["file"]["type"] == "image/png")
|| ($_FILES["file"]["type"] == "image/bmp"))
&& ($_FILES["file"]["size"] < 4*1024*1024))
{
  	if($_FILES["file"]["error"] > 0)
    {
    	echo "Return Code: " . $_FILES["file"]["error"] . "<br />";
    }
  	else
    {
    	echo "Upload: " . $_FILES["file"]["name"] . "<br />";
    	echo "Type: " . $_FILES["file"]["type"] . "<br />";
    	echo "Size: " . ($_FILES["file"]["size"] / 1024) . " Kb<br />";
    	echo "Temp file: " . $_FILES["file"]["tmp_name"] . "<br />";

    	if (file_exists("../images/vodpic/" . $_FILES["file"]["name"]))
      	{
      		echo $_FILES["file"]["name"] . " already exists. ";
      	}
    	else
      	{
      		move_uploaded_file($_FILES["file"]["tmp_name"],"../images/vodpic/" . $saveimge);
      		echo "Stored in: " . "../images/vodpic/" . $_FILES["file"]["name"];
			
			$im=imagecreatefromjpeg("../images/vodpic/" . $saveimge);//参数是图片的存方路径
			$maxwidth="800";//设置图片的最大宽度
			$maxheight="1600";//设置图片的最大高度
			$name="../images/vodpic/" . $saveimge;//图片的名称，随便取吧
			$filetype="";//图片类型
			resizeImage($im,$maxwidth,$maxheight,$name,$filetype);//调用上面的函数

      	}
    }
}
else
{
  	//echo "Invalid file";
	//return;
}

header("Location: vod_list.php?type=" . $_GET["type"]);
?>