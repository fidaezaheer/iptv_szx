<?php
    header("Content-Type: text/html; charset=utf-8");
	include_once 'common.php';
	set_zone();
	$sql = new DbSql();

    if(isset($_GET['action'])){ 
        $fntv_action=$_GET['action'];
    }else{
        die ("操作被禁止>");
    }

	switch($fntv_action){
		case "list":
		    fntv_catogary_list();
		break;
    case "save":
	    fntv_save();
        break;
	default:
        echo '非法操作['.$fntv_action.']';
	}
	
	function char_to_char($v1){
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
	
	function fntv_catogary_list(){
		$sql = new DbSql();
	    $mytable = "vod_name_table";
	    $sql->connect_database_default();
	    $mydb = $sql->get_database();
	    $sql->create_database($mydb);
	    $sql->create_table($mydb, $mytable, "id smallint,name text, needps int, password text");
	    $list = $sql->fetch_datas($mydb, $mytable);
	    foreach($list as $row){
		    echo '<<<'.$row[0].'--'.$row[1].'>>>';
	    }
		$sql->disconnect_database();
    }
	
	function fntv_do_save_post($post_detail){
		include_once 'gemini.php';
	    $g = new Gemini();
	    extract($post_detail);
	    $sql = new DbSql();
		//$sql->login();
		
		$mytable = "vod_table_".$type;
		$sql->connect_database_default();
		$mydb = $sql->get_database();
		
		$sql->create_database($mydb);
		$sql->create_table($mydb, $mytable, "name text, image text, 
			url text, area text, year text, type text, intro1 longtext, intro2 longtext, 
			intro3 longtext, intro4 longtext, id int, clickrate int, recommend tinyint, chage float, updatetime int, firstletter text, status int");
		
		$url = 	str_replace(" ","%20",$url); 
		$url = 	str_replace("&amp;","&",$url);
        $url = 	str_replace("|||||","",$url);
        $url = substr($url,0,strlen($url)-1);		
		$url = trim($url);
		if(count($sql->fetch_datas_where($mydb,$mytable, "name", $name)) > 0)
	    {
		    $sql->update_data_2($mydb, $mytable, "name", $name, "url", $g->j3($url));
	    }else{
			$namess = $sql->fetch_datas_order($mydb, $mytable, "id");
			    $count = count($namess);
			    $id = 0;
			    $change = 0;
			    if($count >= 1)
				    $id = intval($namess[0][10]) + 1;

			    $sql->insert_data($mydb, $mytable, "name, image, url, area, year, type, intro1, intro2, intro3, intro4, id, clickrate, recommend, chage, updatetime, firstletter, status", 
				    $name.", ". $saveimge .", ".$g->j3($url). ", ". $area . ", ". $year . ", " . $livetype . ", " . char_to_char($introduction2).", ".char_to_char($introduction2).", ". char_to_char($introduction3).", ". char_to_char($introduction4) .", " . $id . ", ". 0 . ", ". $recommend. ", ". $change . ", ". $updatetime. ", ". strtolower($firstletter). ", ". 0);
		}
		$sql->disconnect_database();
		
    
    }
	function get_type_area_year($str,$str1,$str2){
		$sql = new DbSql();

	    $sql->connect_database_default();
	    $mydb = $sql->get_database();
	    $sql->create_database($mydb);

	    $mytable = "vod_type_table_". $str;
	    $sql->create_table($mydb, $mytable, "value longtext, id smallint");
			
	    $value0 = $sql->query_data($mydb, $mytable, "id", 0 , "value"); 
	    $value1 = $sql->query_data($mydb, $mytable, "id", 1 , "value"); 
	    $value2 = $sql->query_data($mydb, $mytable, "id", 2 , "value"); 
	
	    if(strlen($value0) < 2)
		    $value0 = "爱情|纪录|喜剧|科幻";
	    if(strlen($value1) < 2)
		    $value1 = "2013|2012|2011|2010";
	    if(strlen($value2) < 2)
		    $value2 = "中国|美国|香港";
			
	    $type_value0s = explode("|",$value0);
	    $type_value1s = explode("|",$value1);
	    $type_value2s = explode("|",$value2);
		
		if($str1=='type'){
			$str3 = explode("|",$str2);
			for($i=0;$i<count($str3);$i++){
				if($i==0){
			        $livetype = '00'.(array_search($str3[$i],$type_value0s) + 1);
		        }else{
			        $livetype .= "|00".(array_search($str[$i],$type_value0s) + 1);
		        }
				break;
			}
			return $livetype;
		}
		if($str1=='year'){
			return (array_search($str2,$type_value1s) + 1);
			break;
		}
		if($str1=='area'){
			return (array_search($str2,$type_value2s) + 1);
			break;
		}
		
	}
	function fntv_save(){
		extract($_POST);
		if($name=='[名称]'||$name=='') die('节目名称为空');
	    if($image=='[图标]'||$image=='') die('图标地址为空');
	    if($url=='[地址]'||$url=='') die('节目地址为空');
	    if($category=='[分类id]'||$category=='') die('分类id为空');
		if(!isset($year)) $year='未知';
		$type = str_replace(" ","%20",$type);
        $type = trim($type);		
		fntv_do_save_post(array(
		    'type'=>$category,
		    'name'=>$name,
            'saveimge'=>$image,
            'url'=>$url,
            'area'=>get_type_area_year($category,'area',$area),
            'year'=>get_type_area_year($category,'year',$year),
            'livetype'=>get_type_area_year($category,'type',$type),
            'introduction2'=>$intro2,
			'introduction3'=>$intro3,
			'introduction4'=>$intro4,
			'recommend'=>$recommend,
			'change'=>$change,
			'updatetime'=>strtotime($updatetime),
			'firstletter'=>$firstletter
		));
		echo '发布成功';
	}
?>