<?php
	
	include 'common.php';
	$sql = new DbSql();
	$sql->login();
	
	$psclear = 0;
	$useridclear = 0;
	
	$new_ps = "";
	$old_ps = "";
	$new_userid = "";
	$old_userid = "";
	$urltext_id = "";
	$pstext_id = "";
	
	if(isset($_GET["psclear"]))
		$psclear = intval($_GET["psclear"]);
		
	if(isset($_GET["useridclear"]))
		$useridclear = intval($_GET["useridclear"]);
		
	if(isset($_GET["ps0"]))
		$new_ps = trim(urldecode(base64_decode($_GET["ps0"])));
		
	if(isset($_GET["ps1"]))
		$old_ps = trim(urldecode(base64_decode($_GET["ps1"])));
	
	if(isset($_GET["userid0"]))
		$new_userid = trim(urldecode(base64_decode($_GET["userid0"])));
		
	if(isset($_GET["userid1"]))	
		$old_userid = trim(urldecode(base64_decode($_GET["userid1"])));
	
	if(isset($_GET["urltext"]))	
		$urltext = trim(urldecode(base64_decode($_GET["urltext"])));

	if(isset($_GET["pstext"]))	
		$pstext = trim(urldecode(base64_decode($_GET["pstext"])));	
		
	if(isset($_GET["useridtext"]))	
		$useridtext = trim(urldecode(base64_decode($_GET["useridtext"])));	
		
	echo "+++++++" . $new_ps . "<br/>";
	echo "+++++++" . $old_ps . "<br/>";
	echo "+++++++" . $new_userid . "<br/>";
	echo "+++++++" . $old_userid . "<br/>";
	echo "+++++++" . $urltext . "<br/>";
	echo "+++++++" . $pstext . "<br/>";
	echo "+++++++" . $url2text . "<br/>";
	echo "+++++++" . $useridtext . "<br/>";
		
	include_once 'gemini.php';
	$g = new Gemini();
	
	$mytable = "live_preview_table";
	$sql->connect_database_default();
	$mydb = $sql->get_database();
	$sql->create_database($mydb);
	$sql->create_table($mydb, $mytable, "name longtext, image longtext, url longtext, password longtext, type longtext, preview longtext, id longtext, urlid smallint, hd longtext");
	$namess = $sql->fetch_datas($mydb, $mytable);
	foreach($namess as $names) 
	{
		
		$js_pwss = $g->j2($names[3]);
		//echo "old_js_pwss = " . $js_pwss . "<br/>";
		if(strlen($urltext)>1 && (strlen($pstext) >=1 || strlen($useridtext) >=1))
		{
			$high_urls = array();
			$low_urls = array();
			$urlss = base64_decode($g->j2($names[2]));
			$urls = explode("geminihighlowgemini",$urlss);
			if(count($urls) >= 1)
				$high_urls = explode("|",$urls[0]);
			if(count($urls) >= 2)
				$low_urls = explode("|",$urls[1]);	
		
			$high_pws = array();
			$low_pws = array();
			$pwss = $g->j2($names[3]);
			$pws = explode("geminihighlowgemini",$pwss);
			if(count($pws) >= 1)
				$high_pws = explode("|",$pws[0]);
			if(count($pws) >= 2)
				$low_pws = explode("|",$pws[1]);
		
		
			//foreach($high_urls as $high_url)
			$new_high_pws = "";
			for($ii=0; $ii<count($high_urls); $ii++)
			{
				$highs = explode("#",$high_urls[$ii]);
				if(count($highs) >= 2)
				{
					$num = $highs[0];
					$url = $highs[1];
					$pw = "";
					if(strstr($url,$urltext) != false)
					{
						if(strlen($useridtext) > 0)
							$pw	= $num . "#" . $pstext . "@PWUSERID@" . $useridtext;
						else
							$pw	= $num . "#" . $pstext;
					}
					else
					{
						$pws = explode("#",$high_pws[$ii]);
						if(count($pws) >= 2)
						{
							$pw = $num . "#" . $pws[1];
						}
						else
							$pw = $num . "#";
					}
					$new_high_pws = $new_high_pws . $pw . "|";
				}
			}
			
			$new_low_pws = "";
			for($ii=0; $ii<count($low_urls); $ii++)
			{
				$lows =  explode("#",$low_urls[$ii]);
				if(count($lows) >= 2)
				{
					$num = $lows[0];
					$url = $lows[1];
					$pw = "";
					if(strstr($url,$urltext) != false)
					{
						if(strlen($useridtext) > 0)
							$pw	= $num . "#" . $pstext . "@PWUSERID@" . $useridtext;
						else
							$pw	= $num . "#" . $pstext;
					}
					else
					{
						$pws = explode("#",$low_pws[$ii]);
						if(count($pws) >= 2)
						{
							$pw = $num . "#" . $pws[1];
						}
						else
							$pw = $num . "#";
					}
					$new_low_pws = $new_low_pws . $pw . "|";
				}
			}
			
			$t = "";
			if(strlen($new_high_pws) > 0 || strlen($new_low_pws) > 0)
			{
				$t = $new_high_pws . "geminihighlowgemini" . $new_low_pws;

				$sql->update_data_2($mydb, $mytable, "urlid", $names[7], "password", $g->j1($t));
			}
			else
			{
				$sql->update_data_2($mydb, $mytable, "urlid", $names[7], "password", "");
			}
		}	
		else
		{
			$js_pw_high_low = explode("geminihighlowgemini",$js_pwss);
			if(count($js_pw_high_low) >= 2)
			{
				$js_pw_highss = $js_pw_high_low[0];
				$js_pw_lowss = $js_pw_high_low[1];
			
				$js_pw_highs = explode("|",$js_pw_highss);
				$js_pw_lows  = explode("|",$js_pw_lowss);
			
				$js_pw_highss = "";
				$js_pw_highss_count = count($js_pw_highs);
				$js_pw_highss_ii = 0;
			
				foreach($js_pw_highs as $js_pw_high)
				{
				
					$js_pw_high_temp = explode("#",$js_pw_high);
					$num = "";
					$js_pw_high_pw = "";
					$js_pw_high_userid = "";
				
					if(count($js_pw_high_temp) == 2)
					{
						$num = 	$js_pw_high_temp[0];
						$js_pw_high_pw_userid = explode("@PWUSERID@",$js_pw_high_temp[1]);
						if(count($js_pw_high_pw_userid) == 2)
						{
							$js_pw_high_pw = $js_pw_high_pw_userid[0];
							$js_pw_high_userid = $js_pw_high_pw_userid[1];
							if($old_ps != "geminipassword" && $old_ps != "geminipassword2" && $old_ps != "geminipassword3")
							{
								if($psclear == 1)
								{
									$js_pw_high_pw = "";
								}
								else if(strlen($old_ps) <=0 && strlen($new_ps) > 0)
								{
									$js_pw_high_pw = $new_ps;
								}
								else if(strstr($js_pw_high_pw,$old_ps)!=false)
								{
									$js_pw_high_pw = str_replace($old_ps,$new_ps,$js_pw_high_pw);	
								}
							
								if($useridclear == 1)
								{
									$js_pw_high_userid = "";
								}
								else if(strlen($old_userid) <=0 && strlen($new_userid) > 0)
								{
									$js_pw_high_userid = $new_userid;
								}
								else if(strstr($js_pw_high_userid,$old_userid)!=false)
								{
									$js_pw_high_userid = str_replace($old_userid,$new_userid,$js_pw_high_userid);
								}
							}
						}
						else if(count($js_pw_high_pw_userid) == 1)
						{
							if($old_ps != "geminipassword" && $old_ps != "geminipassword2" && $old_ps != "geminipassword3")
							{
							
								if($psclear == 1)
								{
									$js_pw_high_pw = "";
								}
								else if(strlen($old_ps) <=0 && strlen($new_ps) > 0)
								{
									$js_pw_high_pw = $new_ps;
								}
								else
								{
									$js_pw_high_pw = str_replace($old_ps,$new_ps,$js_pw_high_temp[1]);	
								}
						
								if($useridclear == 1)
								{
									$js_pw_high_userid = "";
								}
								else if(strlen($old_userid) <=0 && strlen($new_userid) > 0)
								{
									$js_pw_high_userid = $new_userid;
								}
							}
						}
					}
					else
					{
						if($old_ps != "geminipassword" && $old_ps != "geminipassword2" && $old_ps != "geminipassword3")
						{
					
							if($psclear == 1)
							{
								$js_pw_high_pw = "";
							}
							else if(strlen($old_ps) <=0 && strlen($new_ps) > 0)
							{	
								$js_pw_high_pw = $new_ps;
							}
							else
							{
								$js_pw_high_pw = str_replace($old_ps,$new_ps,$js_pw_high);	
							}
					
							if($useridclear == 1)
							{
								$js_pw_high_userid = "";
							}
							else if(strlen($old_userid) <=0 && strlen($new_userid) > 0)	
							{
								$js_pw_high_userid = $new_userid;
							}
						}
					}
				
					if($num == "")
						$num = $js_pw_highss_ii;
				
					if(strlen($js_pw_high_userid) >= 1)
					{
						if(strlen($js_pw_high_pw) >= 1)
						{	
							$js_pw_highss = $js_pw_highss . $num . "#" . $js_pw_high_pw . "@PWUSERID@" . $js_pw_high_userid;
							if($js_pw_highss_ii < $js_pw_highss_count-1)
								$js_pw_highss = $js_pw_highss . "|";
						}
						else
						{
							$js_pw_highss = $js_pw_highss . $num . "#";
							if($js_pw_highss_ii < $js_pw_highss_count-1)
								$js_pw_highss = $js_pw_highss . "|";
						}
					}
					else
					{
						if(strlen($js_pw_high_pw) >= 1)
						{
							$js_pw_highss = $js_pw_highss . $num . "#" . $js_pw_high_pw;
							if($js_pw_highss_ii < $js_pw_highss_count-1)
								$js_pw_highss = $js_pw_highss . "|";	
						}
						else
						{
							$js_pw_highss = $js_pw_highss . $num . "#";
							if($js_pw_highss_ii < $js_pw_highss_count-1)
								$js_pw_highss = $js_pw_highss . "|";	
						}
					}

					$js_pw_highss_ii++;
				}
			

				$js_pw_lowss = "";
				$js_pw_lows_count = count($js_pw_lows);
				$js_pw_lowss_ii = 0;
				foreach($js_pw_lows as $js_pw_low)
				{
					//echo "js_pw_low : " . $js_pw_low;
					$js_pw_low_temp = explode("#",$js_pw_low);
					$num = "";
					$js_pw_low_pw = "";
					$js_pw_low_userid = "";
				
					if(count($js_pw_low_temp) == 2)
					{
					
						$num = 	$js_pw_low_temp[0];
						$js_pw_low_pw_userid = explode("@PWUSERID@",$js_pw_low_temp[1]);
						if(count($js_pw_low_pw_userid) == 2)
						{
							$js_pw_low_pw = $js_pw_low_pw_userid[0];
							$js_pw_low_userid = $js_pw_low_pw_userid[1];
						
							if($old_ps != "geminipassword" && $old_ps != "geminipassword2" && $old_ps != "geminipassword3")
							{
								if($psclear == 1)
								{
									$js_pw_low_pw = "";
								}
								else if(strlen($old_ps) <=0  && strlen($new_ps) > 0)
								{
									$js_pw_low_pw = $new_ps;
								}
								else if(strstr($js_pw_low_pw,$new_ps)!=false)
								{
									$js_pw_low_pw = str_replace($old_ps,$new_ps,$js_pw_low_pw);	
								}
						
								if($useridclear == 1)
								{
									$js_pw_low_userid = "";
								}
								else if(strlen($old_userid) <=0 && strlen($new_userid) > 0)
								{
									$js_pw_low_userid = $new_userid;
								}
								else if(strstr($js_pw_low_userid,$old_userid)!=false)
								{
									$js_pw_low_userid = str_replace($old_userid,$new_userid,$js_pw_low_userid);
								} 
							}
						}
						else if(count($js_pw_low_pw_userid) == 1)
						{
							if($old_ps != "geminipassword" && $old_ps != "geminipassword2" && $old_ps != "geminipassword3")
							{
								if($psclear == 1)
								{
									$js_pw_low_pw = "";
								}
								else if(strlen($old_ps) <=0  && strlen($new_ps) > 0)
								{
									$js_pw_low_pw = $new_ps;
								}
								else
								{
									$js_pw_low_pw = str_replace($old_ps,$new_ps,$js_pw_low_temp[1]);	
								}
						
								if($useridclear == 1)
								{
									$js_pw_low_userid = "";
								}
								else if(strlen($old_userid) <=0 && strlen($new_userid) > 0)	
								{
									$js_pw_low_userid = $new_userid;
								}
							}
						}
					}
					else
					{
						if($old_ps != "geminipassword" && $old_ps != "geminipassword2" && $old_ps != "geminipassword3")
						{
						
							if($psclear == 1)
							{
								$js_pw_low_pw = "";
							}
							else if(strlen($old_ps) <=0  && strlen($new_ps) > 0)
							{	
								$js_pw_low_pw = $new_ps;
							}
					
							if($useridclear == 1)
							{
								$js_pw_low_userid = "";
							}
							else if(strlen($old_userid) <=0 && strlen($new_userid) > 0)
							{
								$js_pw_low_userid = $new_userid;
							}
						}
					}
				
					if($num == "")
						$num = $js_pw_lowss_ii;
					
					if(strlen($js_pw_low_userid) >= 1)	
					{
						if(strlen($js_pw_low_pw) >= 1)
						{
							$js_pw_lowss = $js_pw_lowss . $num . "#" . $js_pw_low_pw . "@PWUSERID@" . $js_pw_low_userid;
							if($js_pw_lowss_ii < $js_pw_lows_count)
								$js_pw_lowss = $js_pw_lowss . "|";
						}
						else
						{
							$js_pw_lowss = $js_pw_lowss . $num . "#";
							if($js_pw_lowss_ii < $js_pw_lows_count-1)
								$js_pw_lowss = $js_pw_lowss . "|";
						}
					}
					else
					{
						if(strlen($js_pw_low_pw) >= 1)
						{
							$js_pw_lowss = $js_pw_lowss . $num . "#" . $js_pw_low_pw;
							if($js_pw_lowss_ii < $js_pw_lows_count)
								$js_pw_lowss = $js_pw_lowss . "|";
						}
						else 
						{
							$js_pw_lowss = $js_pw_lowss . $num . "#";
							if($js_pw_lowss_ii < $js_pw_lows_count-1)
								$js_pw_lowss = $js_pw_lowss . "|";
						}
					}

					
					$js_pw_lowss_ii++;
				}

				$js_pwss = $js_pw_highss . "geminihighlowgemini" . $js_pw_lowss;
			
				$t = $js_pwss;
			
				echo "t:" . $names[7] . ":" . $t . "<br/>";
			}
			else
			{
				$t = str_replace($old_ps,$new_ps,$js_pwss);	
			}
		
			//echo "new_js_pwss = " . $t . "<br/><br/>";
		
			$sql->update_data_2($mydb, $mytable, "urlid", $names[7], "password", $g->j1($t));
		}
		
		/*
		if(((strlen($new_ps) > 0 && strlen($old_ps) > 0) || (strlen($new_userid) > 0 && strlen($old_userid) > 0)) && strlen($js_pw) > 0)
		{
			if(strlen($new_ps) > 0 && strlen($old_ps) > 0)
				$js_pw = str_replace($old_ps,$new_ps,$js_pw);
				
			if(strlen($new_userid) > 0 && strlen($old_userid) > 0)
				$js_pw = str_replace($old_userid,$new_userid,$js_pw);
			
			$sql->update_data_2($mydb, $mytable, "urlid", $names[7], "password", $g->j1($js_pw));
		}
		
		
		if()
		{
			
			$t = "0#" . $new_ps . "@PWUSERID@" . $new_userid . "geminihighlowgemini";
			$sql->update_data_2($mydb, $mytable, "urlid", $names[7], "password", $g->j1($t));
		}
		*/
	}

	$sql->disconnect_database();
	
	/*
	//header("Location: live_preview_list.php");
	echo "<script type='text/javascript'>";
	echo "window.opener.location.reload();";  
	echo "window.close();";
	echo "</script>";
	*/
?>