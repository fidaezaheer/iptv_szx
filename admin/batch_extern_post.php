<?php
	include 'common.php';
	$sql = new DbSql();
	$sql->login();
	
	$find_url = $_POST["url"];
	$set_ps = $_POST["ps"];
	$set_userid = $_POST["userid"];
	$set_ip = $_POST["ip"];
	
	echo $find_url . "<br/>";
	echo $set_ps . "<br/>";
	echo $set_userid . "<br/><br/>";
	
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
		$urlss = base64_decode($g->j2($names[2]));
		echo "urlss:". $urlss . "<br/>";
		$urls = explode("geminihighlowgemini",$urlss);
		if(count($urls) >= 1)
			$high_urls = explode("|",$urls[0]);
		if(count($urls) >= 2)
			$low_urls = explode("|",$urls[1]);

		
		$pwss = $g->j2($names[3]);
		echo "pwss:". $pwss . "<br/>";
		$pws = explode("geminihighlowgemini",$pwss);
		if(count($pws) >= 1)
			$high_pws = explode("|",$pws[0]);
		if(count($pws) >= 2)
			$low_pws = explode("|",$pws[1]);
		
		$all_selected_high_pw = "";
		$all_selected_low_pw = "";
		
		$all_selected_high_url = "";
		$all_selected_low_url = "";
		
		for($ii=0; $ii<count($high_urls); $ii++)
		{
			$selected_url="";
			$selected_pw="";
		
			$high_url = explode("#",$high_urls[$ii]);
			if(count($high_url) >= 2)
				$selected_url =  $high_url[1];
			else if(count($high_url) >= 1)
				$selected_url =  $high_url[0];
			else
				$selected_url =  $high_urls[$ii];
			
			echo "selected_url:" . $selected_url . "<br/>";
			
			$high_pw = explode("#",$high_pws[$ii]);
			if(count($high_pw) >= 2)
				$selected_pw =  $high_pw[1];
			else if(count($high_pw) >= 1)
				$selected_pw =  $high_pw[0];
			else
				$selected_pw =  $high_pws[$ii];	
				
			echo "selected_pw:" . $selected_pw . "<br/>";
			
			$all_selected_pw = "";
			$all_selected_url = "";
			if(strstr($selected_url,$find_url) != false)
			{
				$selected_url = str_replace($find_url,$set_ip,$selected_url);
				if(count($high_url) >= 2)
					$all_selected_url =  $high_url[0] . "#" . $selected_url;
				else 
					$all_selected_url =  $selected_url;
				
				$selected_pw = "";
				if(strlen($set_userid) > 0)	
					$selected_pw = $set_ps . "@PWUSERID@" . $set_userid;
				else
					$selected_pw = $set_ps;
				if(count($high_pw) >= 2)
					$all_selected_pw =  $high_pw[0] . "#" . $selected_pw;
				else 
					$all_selected_pw =  $selected_pw;

			}
			else
			{
				$all_selected_url =  $high_urls[$ii];
				$all_selected_pw = $high_pws[$ii];
			}
			
			$all_selected_high_url = $all_selected_high_url . $all_selected_url;
			if($ii<count($high_urls)-1)
				$all_selected_high_url = $all_selected_high_url . "|";
							
			$all_selected_high_pw = $all_selected_high_pw . $all_selected_pw;
			if($ii<count($high_urls)-1)
				$all_selected_high_pw = $all_selected_high_pw . "|";
		}
		
		for($ii=0; $ii<count($low_urls); $ii++)
		{
			$selected_url="";
			$selected_pw="";
		
			$low_url = explode("#",$low_urls[$ii]);
			if(count($low_url) >= 2)
				$selected_url =  $low_url[1];
			else if(count($low_url) >= 1)
				$selected_url =  $low_url[0];
			else
				$selected_url =  $low_urls[$ii];
			
			$low_pw = explode("#",$low_pws[$ii]);
			if(count($low_pw) >= 2)
				$selected_pw =  $low_pw[1];
			else if(count($low_pw) >= 1)
				$selected_pw =  $low_pw[0];
			else
				$selected_pw =  $low_pws[$ii];	
				
			$all_selected_pw = "";
			$all_selected_url = "";
			if(strstr($selected_url,$find_url) != false)
			{
				$selected_url = str_replace($find_url,$set_ip,$selected_url);
				if(count($low_url) >= 2)
					$all_selected_url =  $low_url[0] . "#" . $selected_url;
				else 
					$all_selected_url =  $selected_url;
					
				$selected_pw = "";
				if(strlen($set_userid) > 0)	
					$selected_pw = $set_ps . "@PWUSERID@" . $set_userid;
				else
					$selected_pw = $set_ps;
				//$selected_pw = $set_ps . "@PWUSERID@" . $set_userid;
				if(count($low_pw) >= 2)
					$all_selected_pw =  $low_pw[0] . "#" . $selected_pw;
				else 
					$all_selected_pw =  $selected_pw;

			}
			else
			{
				$all_selected_url =  $low_urls[$ii];
				$all_selected_pw = $low_pws[$ii];
			}
			

			$all_selected_low_pw = $all_selected_low_pw . $all_selected_pw;
			if($ii<count($low_urls)-1)
				$all_selected_low_pw = $all_selected_low_pw . "|";
		}
		
		$js_url = $all_selected_high_url . "geminihighlowgemini" . $all_selected_low_url;
		$js_pw = $all_selected_high_pw . "geminihighlowgemini" . $all_selected_low_pw;
		echo $js_pw . "<br/><br/>";
		
		$sql->update_data_2($mydb, $mytable, "urlid", $names[7], "url", $g->j1(base64_encode($js_url)));
		$sql->update_data_2($mydb, $mytable, "urlid", $names[7], "password", $g->j1($js_pw));
	}

	$sql->disconnect_database();
	
	//header("Location: batch_extern.php");
?>