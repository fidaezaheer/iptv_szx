<?php

	//$textArray_j3 = array("G","U","Z","p","3","f","R","6","L","J","8","b","d","I","W","P","g","h","E","n","k","l","j","m","D","5","r","q","s","t","u","v","Y","w","y","A","O","N","C","o","i","F","a","S","c","9","K","0","e","M","z","4","Q","H","7","T","1","V","B","X","x","2");
							
	function char_int_2($val)
	{
		if($val == "0") return 0;
		else if($val == "1") return 1;
		else if($val == "2") return 2;
		else if($val == "3") return 3;
		else if($val == "4") return 4;
		else if($val == "5") return 5;
		else if($val == "6") return 6;
		else if($val == "7") return 7;
		else if($val == "8") return 8;
		else if($val == "9") return 9;
	}
	
	function scroll_int_2($val,$vals)
	{
		$textArray_j3 = array("G","U","Z","p","3","f","R","6","L","J","8","b","d","I","W","P","g","h","E","n","k","l","j","m","D","5","r","q","s","t","u","v","Y","w","y","A","O","N","C","o","i","F","a","S","c","9","K","0","e","M","z","4","Q","H","7","T","1","V","B","X","x","2");
		
		$length = count($textArray_j3);
		$ii=0;
		for($ii=0; $ii<$length; $ii++)
		{
			if(strcmp($textArray_j3[$ii],$val) == 0)
			{
				$offset = 0;
				if($ii-$vals < 0)
				{
					$offset = $length + ($ii-intval($vals));
				}
				else
				{
					$offset = $ii-intval($vals);
				}
				//alert(array[offset]);
	
				return $textArray_j3[$offset];			
			}
		}

		return $val;
	}
	
	function j3_2($val)			//����
	{
		$vals = "872356";
		
		$url_tmp = "";
		$url_decoder = "";
	
		$password_tmp = $vals;
	
		$url_len = strlen($val);
		$password_len = strlen($vals);
		$password_len_count = floor($url_len/$password_len);
		if($url_len%$password_len>0)
			$password_len_count++;
			
		//$ii=0;
		for($ii=0;$ii<$password_len_count;$ii++)
		{
			$password_tmp = $password_tmp . $vals;
		}
		
		for($ii=0;$ii<$url_len;$ii++)
		{
			$c = substr($val,$ii,1);
			$n = char_int_2(substr($password_tmp,$ii,1));
			$url_decoder = $url_decoder . scroll_int_2($c,$n);
		}	
		
		return $url_decoder;	
	}
	
	
	///////////////////////////////////////////////////////////
	
	function scroll_char($val,$vals)
	{
		$textArray = array("8","U","2","4","3","f","6","7","0","J","G","b","I","d","W","5","g","h","i","j","k","l","n","m","D","p","r","q","s","t","u","v","w","Y","y","O","A","N","C","o","E","F","a","H","c","9","K","L","B","M","z","P","Q","S","R","T","1","V","e","X","x","Z");
	
		$length = 62;
		$ii=0;
		for($ii=0; $ii<$length; $ii++)
		{
			if(strcmp($textArray[$ii],$val) == 0)
			{	
				//echo $this->textArray[$ii];
				//echo $vals . "#";
				//echo $ii . "\n";
				$offset = 0;
				if(intval($ii)+intval($vals) >= intval($length))
				{
					$offset = intval($ii)+intval($vals)-intval($length);
				}
				else
					$offset = intval($ii)+intval($vals);
				return $textArray[$offset];
			}
		}
		return $val;		
	}
	
	function scroll_char_j3($val,$vals)
	{
		$textArray_j3 = array("G","U","Z","p","3","f","R","6","L","J","8","b","d","I","W","P","g","h","E","n","k","l","j","m","D","5","r","q","s","t","u","v","Y","w","y","A","O","N","C","o","i","F","a","S","c","9","K","0","e","M","z","4","Q","H","7","T","1","V","B","X","x","2");
	
		$length = 62;
		$ii=0;
		for($ii=0; $ii<$length; $ii++)
		{
			if(strcmp($textArray_j3[$ii],$val) == 0)
			{	
				//echo $this->textArray[$ii];
				//echo $vals . "#";
				//echo $ii . "\n";
				$offset = 0;
				if(intval($ii)+intval($vals) >= intval($length))
				{
					$offset = intval($ii)+intval($vals)-intval($length);
				}
				else
					$offset = intval($ii)+intval($vals);

				//echo $offset . "\n\n";
				//echo "=====";
				return $textArray_j3[$offset];
			}
		}
	
		return $val;		
	}
		
	function aj1($val,$cursom,$version)			//����
	{
		if(strcmp($cursom,"ruiyuan") == 0 || strcmp($cursom,"anpai") == 0 || $version < 2)
		{
			return aj2($val);
		}
		else
		{
			$aj_2 = aj2($val);
			$aj_e = base64_encode($aj_2);
			$aj_3 = aj3($aj_e);
			return $aj_3;
		}
	}
	
	function aj2($val)//����
	{
		$vals = "847502";
		
		$url_tmp = "";
		$url_decoder = "";
	
		$password_tmp = $vals;
	
		$url_len = strlen($val);
		$password_len = strlen($vals);
		$password_len_count = floor($url_len/$password_len);
		if($url_len%$password_len>0)
			$password_len_count++;
			
		//$ii=0;
		for($ii=0;$ii<$password_len_count;$ii++)
		{
			$password_tmp = $password_tmp . $vals;
		}
		
		for($ii=0;$ii<$url_len;$ii++)
		{
			$c = substr($val,$ii,1);
			$n = char_int_2(substr($password_tmp,$ii,1));
			$url_tmp = $url_tmp . scroll_char($c,$n);
			//echo $url_tmp . "\n";
		}
		
		return $url_tmp;
	}
	
	function aj3($val)//����
	{
		$vals = "872356";
		
		$url_tmp = "";
		$url_decoder = "";
	
		$password_tmp = $vals;
	
		$url_len = strlen($val);
		$password_len = strlen($vals);
		$password_len_count = floor($url_len/$password_len);
		if($url_len%$password_len>0)
			$password_len_count++;
			
		//$ii=0;
		for($ii=0;$ii<$password_len_count;$ii++)
		{
			$password_tmp = $password_tmp . $vals;
		}
		
		for($ii=0;$ii<$url_len;$ii++)
		{
			$c = substr($val,$ii,1);
			$n = char_int_2(substr($password_tmp,$ii,1));
			$url_tmp = $url_tmp . scroll_char_j3($c,$n);
			//echo $url_tmp . "\n";
		}
		
		return $url_tmp;
	}
	
	function getContent($url, $method = 'GET', $postData = array()) {
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_USERAGENT, 'Mozilla/5.0 (X11; U; Linux i686; zh-CN; rv:1.9.1.2) Gecko/20120829 Firefox/3.5.2 GTB5');
        curl_setopt($curl, CURLOPT_HEADER, 0);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($curl, CURLOPT_REFERER, $url);
        $content = curl_exec($curl);

        $httpCode = curl_getinfo($curl, CURLINFO_HTTP_CODE);
        curl_close($curl);


        if ($httpCode == 200) {
                $content = mb_convert_encoding($content, "UTF-8", "GBK");
        }
		
        return $content;
        
    }
	
	include_once 'gemini.php';
	$g = new Gemini();
		
	//echo $_GET["ps"] . "#" . $g->get_ps() . "#" .  $g->j_key($_GET["ps"]);
	if(!isset($_GET["ps"]) || $g->get_ps() != $g->j_key($_GET["ps"]))
	{
		exit;	
	}
	
	
	$mac = $_GET["mac"];
	$cpuid = $_GET["cpuid"];
	$cursom = $_GET["cursom"];
	$epg = $_GET["epg"];
	$version = $_GET["version"];
	$key = $_GET["key"];
	
	include_once 'common.php';
	$sql = new DbSql();
	//$sql->login();
	
	//include_once 'gemini.php';
	//$g = new Gemini();

	$mytable = "authenticate_table";
	$sql->connect_database_default();
	$mydb = $sql->get_database();
	$sql->create_database($mydb);
	$sql->create_table($mydb, $mytable, "mac text, cpuid text, akey text, state text, time text");
	
	$key = $sql->query_data_2($mydb, $mytable, "mac", $mac, "cpuid", $cpuid, "akey");
	
	$sql->disconnect_database();
	
	if($key == null || strlen($key) < 5)
	{
		if($g->check_dog() == 200 && $g->is_check_dog() == 1)
		{
			echo "qq147538240qq147538240qq147538240gemini#".aj1($mac,$cursom,$version)."#gemini#phone13580584233";
			return;
		}
		else
		{	
			$file = getContent("http://www.gemini-iptv.com/approve2/index.php?version=".$version."&mac=".$mac."&cpuid=".$cpuid."&cursom=".$cursom."&epg=".$epg);
			echo $file;
		}
		
	}
	else
	{
		$key = j3_2($key);
		$key = base64_decode($key);
		
		$key1 = explode("#geminicpuid#",$key);
		$key2 = explode("#geminitime#",$key1[1]);
		$key3 = explode("#geminiout#",$key2[1]);
		//echo $key2[0];
		//echo $cpuid;
		//echo $key3[0];
		//echo "<br/>";
		//echo $key3[1];
		//echo "<br/>";
		date_default_timezone_set('UTC');
		
		if(abs(intval($key3[0])-time()) <= $key3[1] && strcmp($key2[0],$cpuid) == 0)
		{
			echo $key1[0];
		}
		else
		{
			echo "qq147538240qq147538240qq147538240gemini#00:00:00:00:00:00#gemini";
		}
	}
	
?>