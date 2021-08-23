<?php
	//$g = new Gemini();
	//$url = $g->j1("p2p://live.eatuo.com:2015/51b814d2000cf60a4d021462440b7ee8.ts","52683555258451");
	//echo $url;
	//$url2= $g->j2($url,"52683555258451");
	//echo $url2;
	
include "geminip.php";

/*
class Password{
	public $password = "5268312453";
}
*/

class Gemini {
	
	//private $textArray = array("6","f","8","U","0","J","2","3","4","7",
	//		"G","g","F","i","9","I","d","W","H","K","l","R","T","1","n","D","Y","y","V","r","s","t","u","v","w","O",
	//		"E","A","b","c","N","C","o","a","j","k","p","L","B","M","z","P","m","Q","S","q","e","X","x","Z","5","h");
			
	private $textArray = array("8","U","2","4","3","f","6","7","0","J","G","b","I","d","W","5","g","h","i","j","k","l","n","m","D","p","r","q","s","t","u","v","w","Y","y","O","A","N","C","o","E","F","a","H","c","9","K","L","B","M","z","P","Q","S","R","T","1","V","e","X","x","Z");
	
	private function char_int($val)
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
	
	private function scroll_char($val,$vals)
	{
		$length = count($this->textArray);
		$ii=0;
		for($ii=0; $ii<$length; $ii++)
		{
			if(strcmp($this->textArray[$ii],$val) == 0)
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
				return $this->textArray[$offset];
			}
		}
	
		return $val;		
	}
	
	private function scroll_int($val,$vals)
	{
		$length = count($this->textArray);
		$ii=0;
		for($ii=0; $ii<$length; $ii++)
		{
			if(strcmp($this->textArray[$ii],$val) == 0)
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
	
				return $this->textArray[$offset];			
			}
		}

		return $val;
	}
	
	public function j1($val)
	{
		$passworder = new Password();
		$vals = $passworder->password;
		
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
			$n = $this->char_int(substr($password_tmp,$ii,1));
			$url_tmp = $url_tmp . $this->scroll_char($c,$n);
			//echo $url_tmp . "\n";
		}
		
		return $url_tmp;
	}
	
	public function j2($val)
	{
		$passworder = new Password();
		$vals = $passworder->password;
		
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
			$n = $this->char_int(substr($password_tmp,$ii,1));
			$url_decoder = $url_decoder . $this->scroll_int($c,$n);
		}	
		
		return $url_decoder;	
	}
	
	public function j3($val)
	{
		$aurls = "";
		$urls = explode("|",$val);
		//foreach($urls as $url)
		for($ii=0; $ii<count($urls); $ii++)
		{
			$url = explode("#",$urls[$ii]);
			$turl = $url[0] . "#" . $this->j1(base64_encode($url[1]));
			
			$aurls = $aurls . $turl;
			if($ii < count($urls) - 1)
				$aurls = $aurls . "|";
		}
		
		return $aurls;
	}
	
	public function j4($val)
	{
		$aurls = "";
		$urls = explode("|",$val);
		//foreach($urls as $url)
		for($ii=0; $ii<count($urls); $ii++)
		{
			$url = explode("#",$urls[$ii]);
			$turl = $url[0] . "#" . base64_decode($this->j2($url[1]));
			
			$aurls = $aurls . $turl;
			if($ii < count($urls) - 1)
				$aurls = $aurls . "|";
		}
		
		return $aurls;
	}
	
	public function j_key($val)
	{
		return base64_decode($this->j2($val));
	}
	
	public function jj_key($val)
	{
		return $this->j1(base64_encode($val));
	}
}
?>