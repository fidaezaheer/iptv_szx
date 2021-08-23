<?php

header("Content-type: text/html; charset=utf-8"); 

function char_to_char($v1)
{
	$v2 = $v1;
	
	$v2 = str_replace('\\',"/",$v2);
	$v2 = str_replace('"',"",$v2);
	$v2 = str_replace('"',"",$v2);
	$v2 = str_replace(' ',"\ ",$v2);
	$v2 = str_replace('"',"\"",$v2);
	$v2 = str_replace('\'',"",$v2);
	$v2 = str_replace('nk','n\k',$v2);
	
	return $v2;
}

function get_type($str)
{
	$father = 0;
	$child = "001";
	
	//echo "1 encode:" . urlencode("欧美AV") . "<br/>";
	//echo "2 encode:" . urlencode($str) . "<br/>";
	
	if(urlencode($str) == urlencode("欧美电影"))
	{
		$father = 0;
		$child = "001";
	}
	else if(urlencode($str) == urlencode("粤语电影"))
	{
		$father = 0;
		$child = "002";
	}
	else if(urlencode($str) == urlencode("华语电影"))
	{
		$father = 0;
		$child = "003";
	}
	else if(urlencode($str) == urlencode("韩国电影"))
	{
		$father = 0;
		$child = "004";
	}
	else if(urlencode($str) == urlencode("日本电影"))
	{
		$father = 0;
		$child = "005";
	}
	else if(urlencode($str) == urlencode("周星驰电影"))
	{
		$father = 0;
		$child = "006";
	}
	else if(urlencode($str) == urlencode("经典香港电影"))
	{
		$father = 0;
		$child = "007";
	}
	else if(urlencode($str) == urlencode("香港剧"))
	{
		$father = 1;
		$child = "001";
	}
	else if(urlencode($str) == urlencode("大陆剧"))
	{
		$father = 1;
		$child = "002";
	}
	else if(urlencode($str) == urlencode("日韩剧"))
	{
		$father = 1;
		$child = "003";
	}
	else if(urlencode($str) == urlencode("欧美剧"))
	{
		$father = 1;
		$child = "004";
	}
	else if(urlencode($str) == urlencode("台湾剧"))
	{
		$father = 1;
		$child = "005";
	}
	else if(urlencode($str) == urlencode("泰国剧"))
	{
		$father = 1;
		$child = "006";
	}
	
	/*
	else if(urlencode($str) == urlencode("热门综艺"))
	{
		$father = 2;
		$child = "001";
	}
	else if(urlencode($str) == urlencode("纪录片"))
	{
		$father = 2;
		$child = "002";
	}
	else if(urlencode($str) == urlencode("音乐"))
	{
		$father = 2;
		$child = "003";
	}
	*/
	
	else if(urlencode($str) == urlencode("最新合集"))
	{
		$father = 2;
		$child = "001";
	}
	else if(urlencode($str) == urlencode("中文字幕"))
	{
		$father = 2;
		$child = "002";
	}
	else if(urlencode($str) == urlencode("亚洲有码"))
	{
		$father = 2;
		$child = "003";
	}
	else if(urlencode($str) == urlencode("亚洲无码"))
	{
		$father = 2;
		$child = "004";
	}
	else if(urlencode($str) == urlencode("欧美AV"))
	{
		$father = 2;
		$child = "005";
	}
	
	else if(urlencode($str) == urlencode("热门动漫"))
	{
		$father = 3;
		$child = "001";
	}
	else if(urlencode($str) == urlencode("热门综艺"))
	{
		$father = 3;
		$child = "002";
	}
	else if(urlencode($str) == urlencode("少儿英语"))
	{
		$father = 3;
		$child = "003";
	}
	else if(urlencode($str) == urlencode("少儿华语"))
	{
		$father = 3;
		$child = "004";
	}
	
	else if(urlencode($str) == urlencode("中文字幕"))
	{
		$father = 2;
		$child = "005";
	}
	

	return $father . "#" . $child;
}


	$json_string = file_get_contents("vod.json");
	$jsons = json_decode($json_string,true);


	$index = intval($_GET["index"]);
	if($index >= count($jsons))
	{
		echo "finish";
		return;
	}
	
	
	echo count($jsons) . "/" . $index;
	
	include_once 'common.php';
	include_once 'gemini.php';
		
	$g = new Gemini();
	$sql = new DbSql();
	
	$sql->connect_database_default();
	$mydb = $sql->get_database();
	$sql->create_database($mydb);
	
	//foreach($jsons as $json) 
	//echo "sources:" . count($jsons[$index]["sources"]) . "<br/>";
	for($ii=0; $ii<count($jsons[$index]["sources"]); $ii++)
	{
		$id = $jsons[$index]["chid"];
		$url = $jsons[$index]["sources"][$ii]["address"];
		$image = $jsons[$index]["logo"]["image"]["big"];
		$type = get_type($jsons[$index]["category"]);
		$types = explode("#",$type);
		$btype = $types[0];
		$mtype = $types[1];
		$name = $jsons[$index]["name"]["init"];
		$description = $jsons[$index]["description"];
		$subTitle = $jsons[$index]["sources"][$ii]["subTitle"];
		$area = "1";
		$year = "2017";
		$type = "1";
		$intro1 = "";
		$clickrate = 0;
		$recommend = 4;
		$chage = 0;
		$updatetime = time();
		$$firstletter = "";
		$intro1= "";
		$intro2= "";
		$intro3= "";
		$intro4= "";

		//echo "id:" . $id . "<br/>";
		//echo "url:" . $url . "<br/>";
		//echo "image:" . $image . "<br/>";
		//echo "btype:" . $btype . "name:" . $jsons[$index]["category"]. "<br/>";
		//echo "subTitle" . $subTitle . "<br/>";
		
		$mytable = "vod_table_" . $btype;
	
		$sql->create_table($mydb, $mytable, "name text, image text, 
			url text, area text, year text, type text, intro1 longtext, intro2 longtext, 
			intro3 longtext, intro4 longtext, id int, clickrate int, recommend tinyint, chage float, updatetime int, firstletter text");
		
		$namess = $sql->fetch_datas_where($mydb, $mytable,"id",$id);
		$all_url = "";
		$insert = 0;
	
		if(count($namess) > 0)
		{
			$vods = explode("|",$g->j4($namess[0][2]));
			//print_r($vods);
	
			$num = count($vods);
	
			for($kk=0; $kk<$num; $kk++)
			{
				$tmps = explode("#", $vods[$kk]);
				if(intval($subTitle) < intval($tmps[0]) && $insert == 0)
				{
					$all_url = $all_url . intval($subTitle) . "#" . $url;	
					$insert = 1;
					$all_url = $all_url . "|";
				}
				else if(intval($subTitle) == intval($tmps[0]) && $insert == 0)
				{
					$all_url = $all_url . intval($subTitle) . "#" . $url;	
					$insert = 1;
				
					if($kk < $num-1)
						$all_url = $all_url . "|";
				
					continue;
				}

				$all_url = $all_url . $vods[$kk];	
				if($kk < $num-1)
					$all_url = $all_url . "|";
			}
		
			if($insert == 0)
			{
				$all_url = $all_url .  "|" . intval($subTitle) . "#" . $url;	
				$insert = 1;
			}
		}
		else
		{
			$all_url = intval($subTitle) . "#" . $url;
		}
	
		$all_url = str_replace("http://static.tvgood.live:8080/","https://static.tvgood.live/",$all_url);
		
		echo "<br/>insert:" . $all_url;
			
		if(count($namess) > 0)
		{
		
			$sql->update_data_2($mydb, $mytable, "id", $id, "name", $name);
			$sql->update_data_2($mydb, $mytable, "id", $id, "url", $g->j3($all_url));
			$sql->update_data_2($mydb, $mytable, "id", $id, "image", $image);
			$sql->update_data_2($mydb, $mytable, "id", $id, "type", $mtype);		
			$sql->update_data_2($mydb, $mytable, "id", $id, "intro4",char_to_char($description));	
			$sql->update_data_2($mydb, $mytable, "id", $id, "recommend",$recommend);	
		}
		else
		{
			$sql->insert_data($mydb, $mytable, "name, image, url, area, year,
										 type, intro1, intro2, intro3, intro4,
										 id, clickrate, recommend, chage, updatetime, 
										 firstletter", 
										$name.", ". $image .", ".$g->j3($all_url). ", ". $area . ", ". $year . ", " . 
										$mtype . ", " . char_to_char($intro1).", ".char_to_char($intro2).", ". char_to_char($intro3).", ". char_to_char($description) .", " . 
										$id . ", ". $clickrate . ", ". $recommend. ", ". $chage . ", ". $updatetime. ", ". $firstletter);

		}
		
	}
	
	$sql->disconnect_database();
?>