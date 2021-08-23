<?PHP
if(Extension_Loaded('zlib')) Ob_Start('ob_gzhandler');
Header("Content-type: text/html");
?> 

<!DOCTYPE html>
<?php
include_once "cn_lang.php";
include_once "common.php";

set_time_limit(0);  

$sql = new DbSql();
$sql->login();

$sql->connect_database_default();
$mydb = $sql->get_database();
$sql->create_database($mydb);

$proxy_array = array();	
$mytable = "proxy_table";
$sql->create_table($mydb, $mytable, "name text, password text, ptip text, edit int, watermark text, validity date, allow int, remark text, ccount int, panal int");
$proxy_namess = $sql->fetch_datas($mydb, $mytable);
foreach($proxy_namess as $proxy_names)
{
	$proxy_array[$proxy_names[0]] = $proxy_names[7];
}						
						
$type_array = array();
$mytable = "playlist_type_table";
$sql->create_table($mydb, $mytable, "name text, space longtext, id text, playlist longtext");
$type_namess = $sql->fetch_datas($mydb, $mytable);
foreach($type_namess as $type_names)
{
	$type_array[$type_names[2]] = $type_names[0];
}


$allow="allow";	
$mytable = "custom_close_table";
$sql->create_table($mydb, $mytable, "allow text, value text");
$close = $sql->query_data($mydb, $mytable, "allow", $allow, "value");

$pre_days;
$pre_allocation;
$pre_playlist;
if($close == "pre")
{
	$pre_days = $sql->query_data($mydb, $mytable, "allow", "days", "value");
	$pre_allocation = $sql->query_data($mydb, $mytable, "allow", "allocation", "value");
	$pre_playlist = $sql->query_data($mydb, $mytable, "allow", "playlist", "value");		
}
	
	
$size = 100;
$offset = 0;
$page = 0;
$online_numrows = 0;
if(isset($_GET["page"]))
{
	$offset = $size*intval($_GET["page"]);
	$page = $_GET["page"];
}

	//evernumber 过去使用过的授权码
	//prekey 上一次登录记录的KEY
	$mytable = "custom_table";
	$sql->create_table($mydb, $mytable, 
		"mac text,cpu text,ip text,space text, date text,
		time text,allow text, playlist text, online text, allocation text,
		proxy text, balance float,showtime text,contact text,member text,
		panal text,number text,ips text, onescrolltext text, onescrolltexttimes int, 
		numberdate int, scrollcontent text, scrolldate text, scrolltimes text, controlurl text, 		
		controltime int, unbundling int, accessdate timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP, mv text, isupdate int,
		remarks text, startime date, model text, remotelogin int, limitmodel text, 
		modelerror int, limittimes int, limitarea text, ghost int, password text, 
		evernumber longtext, prekey text, cpuinfo text, contactkey text");
	
	if($sql->find_column($mydb, $mytable, "showtime") == 0)
		$sql->add_column($mydb, $mytable,"showtime", "text");
		
	if($sql->find_column($mydb, $mytable, "contact") == 0)
		$sql->add_column($mydb, $mytable,"contact", "text");
		
	if($sql->find_column($mydb, $mytable, "member") == 0)
		$sql->add_column($mydb, $mytable,"member", "text");
		
	if($sql->find_column($mydb, $mytable, "panal") == 0)
		$sql->add_column($mydb, $mytable,"panal", "text");
	
	if($sql->find_column($mydb, $mytable, "number") == 0)
		$sql->add_column($mydb, $mytable,"number", "text");

	if($sql->find_column($mydb, $mytable, "ips") == 0)
		$sql->add_column($mydb, $mytable,"ips", "text");
	
	if($sql->find_column($mydb, $mytable, "onescrolltext") == 0)
		$sql->add_column($mydb, $mytable,"onescrolltext", "text");

	if($sql->find_column($mydb, $mytable, "onescrolltexttimes") == 0)
		$sql->add_column($mydb, $mytable,"onescrolltexttimes", "int");
		
	if($sql->find_column($mydb, $mytable, "numberdate") == 0)
		$sql->add_column($mydb, $mytable,"numberdate", "int");
	
	if($sql->find_column($mydb, $mytable, "scrollcontent") == 0)
		$sql->add_column($mydb, $mytable,"scrollcontent", "text");
	
	if($sql->find_column($mydb, $mytable, "scrolldate") == 0)
		$sql->add_column($mydb, $mytable,"scrolldate", "text");
	
	if($sql->find_column($mydb, $mytable, "scrolltimes") == 0)
		$sql->add_column($mydb, $mytable,"scrolltimes", "text");	
			
	if($sql->find_column($mydb, $mytable, "controlurl") == 0)
		$sql->add_column($mydb, $mytable,"controlurl", "text");						
		
	if($sql->find_column($mydb, $mytable, "controltime") == 0)
		$sql->add_column($mydb, $mytable,"controltime", "int");
		
	if($sql->find_column($mydb, $mytable, "unbundling") == 0)
		$sql->add_column($mydb, $mytable,"unbundling", "int");
		
	if($sql->find_column($mydb, $mytable, "accessdate") == 0)
		$sql->add_column($mydb, $mytable,"accessdate", "timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP");
		
	if($sql->find_column($mydb, $mytable, "mv") == 0)
		$sql->add_column($mydb, $mytable,"mv", "text");
		
	if($sql->find_column($mydb, $mytable, "isupdate") == 0)
		$sql->add_column($mydb, $mytable,"isupdate", "int");
		
	if($sql->find_column($mydb, $mytable, "remarks") == 0)
		$sql->add_column($mydb, $mytable,"remarks", "text");

	if($sql->find_column($mydb, $mytable, "startime") == 0)
	{
		$sql->add_column($mydb, $mytable,"startime", "datetime");
	}
	
	if($sql->find_column($mydb, $mytable, "model") == 0)
	{
		$sql->add_column($mydb, $mytable,"model", "text");
	}
	
	if($sql->find_column($mydb, $mytable, "remotelogin") == 0)
	{
		$sql->add_column($mydb, $mytable,"remotelogin","int");
	}
	
	if($sql->find_column($mydb, $mytable, "limitmodel") == 0)
	{
		$sql->add_column($mydb, $mytable,"limitmodel","text");
	}
	
	if($sql->find_column($mydb, $mytable, "modelerror") == 0)
	{
		$sql->add_column($mydb, $mytable,"modelerror","int");
	}
	
	if($sql->find_column($mydb, $mytable, "limittimes") == 0)
	{
		$sql->add_column($mydb, $mytable,"limittimes","int");
	}
	
	if($sql->find_column($mydb, $mytable, "limitarea") == 0)
	{
		$sql->add_column($mydb, $mytable,"limitarea","text");
	}
	
	if($sql->find_column($mydb, $mytable, "limitarea") == 0)
	{
		$sql->add_column($mydb, $mytable,"limitarea","text");
	}
	
	if($sql->find_column($mydb, $mytable, "ghost") == 0)
	{
		$sql->add_column($mydb, $mytable,"ghost","int");
	}
	
	if($sql->find_column($mydb, $mytable, "password") == 0)
	{
		$sql->add_column($mydb, $mytable,"password","text");
	}
	
	if($sql->find_column($mydb, $mytable, "evernumber") == 0)
	{
		$sql->add_column($mydb, $mytable,"evernumber","longtext");
	}
	
	if($sql->find_column($mydb, $mytable, "prekey") == 0)
	{
		$sql->add_column($mydb, $mytable,"prekey","text");
	}
	
	if($sql->find_column($mydb, $mytable, "cpuinfo") == 0)
	{
		$sql->add_column($mydb, $mytable,"cpuinfo","text");
	}
	
	if($sql->find_column($mydb, $mytable, "contactkey") == 0)
	{
		$sql->add_column($mydb, $mytable,"contactkey","text");
	}
	/*
	$rowss = $sql->fetch_datas($mydb, $mytable);
	foreach($rowss as $rows)
	{
		if($rows[4] != "null" && strlen($rows[4]) > 5 && strlen($rows[31]) < 5)
		{
			$sql->update_data_3($mydb, $mytable, "cpu", $rows[1], "mac", $rows[0], "startime", $rows[4]);
		}
	}
	*/
	
	$allow_yes_numrows;
	$allow_no_numrows;
	$allow_test_numrows;
	$online_numrows;
	if(isset($_GET["find"]))
	{
		$namess = $sql->fetch_datas_where_like($mydb, $mytable, "mac", trim($_GET["find"])); 
		$numrows = count($sql->fetch_datas_where_like($mydb, $mytable, "mac", trim($_GET["find"])));
		$allow_yes_numrows = count($sql->fetch_datas_where_like_2($mydb, $mytable, "mac", $_GET["find"] ,"allow", "yes"));
		$allow_no_numrows = count($sql->fetch_datas_where_like_2($mydb, $mytable, "mac", $_GET["find"] ,"allow", "no"));
		$allow_test_numrows = count($sql->fetch_datas_where_like_2($mydb, $mytable, "mac", $_GET["find"] ,"allow", "pre"));
		$allow_pause_numrows = count($sql->fetch_datas_where_like_2($mydb, $mytable, "mac", $_GET["find"] ,"allow", "pause"));
	}
	else if(isset($_GET["proxy"]))
	{
		$namess = $sql->fetch_datas_where_like($mydb, $mytable, "proxy", trim($_GET["proxy"])); 
		$numrows = count($sql->fetch_datas_where_like($mydb, $mytable, "proxy", trim($_GET["proxy"])));
		$allow_yes_numrows = count($sql->fetch_datas_where_like_2($mydb, $mytable, "proxy", $_GET["proxy"], "allow", "yes"));
		$allow_no_numrows = count($sql->fetch_datas_where_like_2($mydb, $mytable, "proxy", $_GET["proxy"], "allow", "no"));
		$allow_test_numrows = count($sql->fetch_datas_where_like_2($mydb, $mytable, "proxy", $_GET["proxy"], "allow", "pre"));
		$allow_pause_numrows = count($sql->fetch_datas_where_like_2($mydb, $mytable, "proxy", $_GET["proxy"], "allow", "pause"));
	}
	else if(isset($_GET["cpuid"]))
	{
		$namess = $sql->fetch_datas_where_like($mydb, $mytable, "cpu", trim($_GET["cpuid"])); 
		$numrows = count($sql->fetch_datas_where_like($mydb, $mytable, "cpu", trim($_GET["cpuid"])));		
		$allow_yes_numrows = count($sql->fetch_datas_where_like_2($mydb, $mytable,"cpu", $_GET["cpuid"], "allow", "yes"));
		$allow_no_numrows = count($sql->fetch_datas_where_like_2($mydb, $mytable, "cpu", $_GET["cpuid"], "allow", "no"));
		$allow_test_numrows = count($sql->fetch_datas_where_like_2($mydb, $mytable, "cpu", $_GET["cpuid"], "allow", "pre"));
		$allow_pause_numrows = count($sql->fetch_datas_where_like_2($mydb, $mytable, "cpu", $_GET["cpuid"], "allow", "pause"));
	}
	else if(isset($_GET["ip"]))
	{
		$namess = $sql->fetch_datas_where_like($mydb, $mytable, "ip", trim($_GET["ip"])); 
		$numrows = count($sql->fetch_datas_where_like($mydb, $mytable, "ip", trim($_GET["ip"])));	
		$allow_yes_numrows = count($sql->fetch_datas_where_like_2($mydb, $mytable, "ip", $_GET["ip"], "allow", "yes"));
		$allow_no_numrows = count($sql->fetch_datas_where_like_2($mydb, $mytable, "ip", $_GET["ip"], "allow", "no"));
		$allow_test_numrows = count($sql->fetch_datas_where_like_2($mydb, $mytable, "ip", $_GET["ip"], "allow", "pre"));		
		$allow_pause_numrows = count($sql->fetch_datas_where_like_2($mydb, $mytable, "ip", $_GET["ip"], "allow", "pause"));			
	}
	else if(isset($_GET["member"]))
	{
		$namess = $sql->fetch_datas_where_like($mydb, $mytable, "member", trim($_GET["member"])); 
		$numrows = count($sql->fetch_datas_where_like($mydb, $mytable, "member", trim($_GET["member"])));		
		$allow_yes_numrows = count($sql->fetch_datas_where_like_2($mydb, $mytable, "member", $_GET["member"], "allow", "yes"));
		$allow_no_numrows = count($sql->fetch_datas_where_like_2($mydb, $mytable, "member", $_GET["member"], "allow", "no"));
		$allow_test_numrows = count($sql->fetch_datas_where_like_2($mydb, $mytable, "member", $_GET["member"], "allow", "pre"));
		$allow_pause_numrows = count($sql->fetch_datas_where_like_2($mydb, $mytable, "member", $_GET["member"], "allow", "pause"));
	}	
	else if(isset($_GET["number"]))
	{
		$namess = $sql->fetch_datas_where_like_like_2($mydb, $mytable, "number", trim($_GET["number"]), "evernumber", trim($_GET["number"])); 
		$numrows = count($namess);		
		$allow_yes_numrows = count($sql->fetch_datas_where_like_like_and_2($mydb, $mytable, "number", $_GET["number"], "evernumber", trim($_GET["number"]),"allow", "yes"));
		$allow_no_numrows = count($sql->fetch_datas_where_like_like_and_2($mydb, $mytable, "number", $_GET["number"], "evernumber", trim($_GET["number"]),"allow", "no"));
		$allow_test_numrows = count($sql->fetch_datas_where_like_like_and_2($mydb, $mytable, "number", $_GET["number"], "evernumber", trim($_GET["number"]),"allow", "pre"));			
		$allow_pause_numrows = count($sql->fetch_datas_where_like_like_and_2($mydb, $mytable, "number", $_GET["number"], "evernumber", trim($_GET["number"]),"allow", "pause"));	
	}
	else if(isset($_GET["loginremote"]) && intval($_GET["loginremote"]) == 1)
	{
		$namess = $sql->fetch_datas_big($mydb, $mytable, "remotelogin", 5); 
		$numrows = count($namess);
	}
	else if(isset($_GET["pause"]) && intval($_GET["pause"]) == 1)
	{
		$namess = $sql->fetch_datas_where($mydb, $mytable, "allow", "pause");
		$numrows = count($namess);	
	}
	else if(isset($_GET["ghost"]) && intval($_GET["ghost"]) == 1)
	{
		$namess = $sql->fetch_datas_big($mydb, $mytable, "ghost", 1);
		$numrows = count($namess);
	}
	else if(isset($_GET["modelerror"]) && intval($_GET["modelerror"]) == 1)
	{
		$namess = $sql->fetch_datas_where($mydb, $mytable, "modelerror", 1);
		$numrows = count($namess);
	}
	else
	{
		$namess = $sql->fetch_datas_limit_desc_2($mydb, $mytable, $offset, $size, "startime", "date"); 
		$numrows = $sql->count_fetch_datas($mydb, $mytable);
		$allow_yes_numrows = $sql->count_fetch_datas_where($mydb, $mytable, "allow", "yes");
		$allow_no_numrows = $sql->count_fetch_datas_where($mydb, $mytable, "allow", "no");
		$allow_test_numrows = $sql->count_fetch_datas_where($mydb, $mytable, "allow", "pre");
		$allow_pause_numrows = $sql->count_fetch_datas_where($mydb, $mytable, "allow", "pause");
		$online_numrows = $sql->count_fetch_datas_where_like_3_or($mydb, $mytable, "online", date("Y-m-d"), "allow", "yes", "allow", "pre");
	}

	$pages = 0;
	$pages = intval($numrows/$size);
	if($numrows%$size)
	{
		$pages++;
	}

	foreach($namess as $names) 
	{
		if(update_date(date("Y-m-d"),$names[5]))
		{
			$sql->update_data_3($mydb, $mytable, "mac", $names[0], "cpu", $names[1], "allow", "no");
		}
		else
		{
			//$sql->update_data_3($mydb, $mytable, "mac", $names[0], "cpu", $names[1], "allow", "yes");
		}
	}


?>


<?php

function update_date($day1, $day2)
{
	if(intval($day2) == -1)
		return false;
		
	$days_array = explode("-",$day1);
	$daye_array = explode("-",$day2);
	
	if(intval($days_array[0]) > intval($daye_array[0]))
		return true;
	else if(intval($days_array[0]) < intval($daye_array[0]))
		return false;
		
	if(intval($days_array[1]) > intval($daye_array[1]))
		return true;
	else if(intval($days_array[1]) < intval($daye_array[1]))
		return false;
		
	if(intval($days_array[2]) > intval($daye_array[2]))
		return true;		
	else if(intval($days_array[2]) < intval($daye_array[2]))
		return false;
		
	return false;
}

function getChaBetweenTwoDate($date1,$date2){
	$Date_List_a1=explode("-",$date1);
	$Date_List_a2=explode("-",$date2);
	if(count($Date_List_a1) < 3 || count($Date_List_a2) < 3)
		return 0;
		
	if(is_numeric($Date_List_a1[1]) && is_numeric($Date_List_a1[0]) && is_numeric($Date_List_a1[2])
		&& is_numeric($Date_List_a2[1]) && is_numeric($Date_List_a2[0]) && is_numeric($Date_List_a2[2])) 	
	{
		$d1=mktime(0,0,0,$Date_List_a1[1],$Date_List_a1[2],$Date_List_a1[0]);
		$d2=mktime(0,0,0,$Date_List_a2[1],$Date_List_a2[2],$Date_List_a2[0]);
		$Days=round(($d1-$d2)/3600/24);
		return $Days;
	}
	else
	{
		return 0;
	}
}

function getChaBetweenTwoDate2($date1,$date2){
	$Date_List_a1=explode("-",$date1);
	$Date_List_a2_1=explode(" ",$date2);
	$Date_List_a2 = explode("-",$Date_List_a2_1[0]);
	if(count($Date_List_a1) < 3 || count($Date_List_a2) < 3)
		return 0;
		
	if(is_numeric($Date_List_a1[1]) && is_numeric($Date_List_a1[0]) && is_numeric($Date_List_a1[2])
		&& is_numeric($Date_List_a2[1]) && is_numeric($Date_List_a2[0]) && is_numeric($Date_List_a2[2])) 	
	{
		$d1=mktime(0,0,0,$Date_List_a1[1],$Date_List_a1[2],$Date_List_a1[0]);
		$d2=mktime(0,0,0,$Date_List_a2[1],$Date_List_a2[2],$Date_List_a2[0]);
		$Days=round(($d1-$d2)/3600/24);
		return $Days;
	}
	else
	{
		return 0;
	}
}

?>

<html>
    <META http-equiv=Content-Type content="text/html; charset=utf-8">
    <head>
        <title><?php echo $lang_array["left_title"] ?></title>
        <!-- Bootstrap -->
        <link href="bootstrap/css/bootstrap.min.css" rel="stylesheet" media="screen">
        <link href="bootstrap/css/bootstrap-responsive.min.css" rel="stylesheet" media="screen">
        <link href="assets/styles.css" rel="stylesheet" media="screen">
        <link href="assets/DT_bootstrap.css" rel="stylesheet" media="screen">
        <script src="vendors/modernizr-2.6.2-respond-1.1.0.min.js"></script>
    </head>
    
    <body>
        <div class="container-fluid">
            <div class="row-fluid">
              <div class="span13" id="content">
                <div class="row-fluid">
                   <!-- block -->
						<div class="block">
                            <div class="navbar navbar-inner block-header">
                                <div class="muted pull-left"><?php echo $lang_array["custom_list_text1"] ?></div>
                            </div>
                            <div class="block-content collapse in">
                                <div class="span12">
									<form class="form-horizontal" name="authform" method="post" action="" enctype='multipart/form-data'>
                                    
                                    <div class="control-group" style="background-color:#CCC"> 
                                        <br/>
                                        <label class="control-label"><?php echo $lang_array["custom_list_text30"] ?></label>  
                                        <div class="controls">
                                       	  <label class="control-label" style="width:300px;text-align:left;">
                                          <?php
											if($close == "" || $close == "yes")
												echo $lang_array["custom_list_text65"];
											else if($close == "pre")
											{
												echo $lang_array["custom_list_text66"] . "&nbsp;&nbsp;";
												echo $lang_array["custom_list_text67"] . $pre_days . $lang_array["custom_list_text55"] . "&nbsp;&nbsp;";
												echo $lang_array["custom_list_text77"] . ":";
												if(strcmp($pre_allocation,"auto") == 0)
													echo $lang_array["custom_list_text78"];
												else if(strcmp($pre_allocation,"all") == 0)
												{
													echo $lang_array["custom_list_text79"];
												}
												else if(strcmp($pre_allocation,"manually") == 0)
												{
													if(isset($type_array[$pre_playlist]))
														echo $type_array[$pre_playlist];
												}
												echo "&nbsp;&nbsp;";
											}
											else if($close == "no")
												echo $lang_array["custom_list_text80"];
											else if($close == "boxphone")
												echo $lang_array["custom_all_edit_text27"];
											else if($close == "cnusa")
												echo $lang_array["custom_all_edit_text28"];	
										  ?>
                                          </label>

                                          <button type="button" class="btn btn-primary" onClick="custom_close()"><?php echo $lang_array["custom_list_text31"] ?></button>
                                          <button type="button" class="btn btn-primary" onClick="custom_batch()"><?php echo $lang_array["custom_list_text32"] ?></button>
                                          <button type="button" class="btn btn-primary" onClick="user_login_timers()"><?php echo $lang_array["custom_list_text72"] ?></button>
                                          <button type="button" class="btn btn-primary" onClick="custom_introduction()"><?php echo $lang_array["custom_list_text81"] ?></button>
                                          <!--<button type="button" class="btn btn-primary" onClick="custom_flash()"><?php echo $lang_array["custom_list_text97"] ?></button>-->
                                      </div>    
                                      <br/> 
                                        
                                        <!--
                                        <label class="control-label" ><?php //echo $lang_array["custom_list_text32"] ?></label> 
                                        <div class="controls">
                                            <button type="button" class="btn btn-primary" onClick="add_low()"><?php echo $lang_array["custom_list_text33"] ?></button>
                                            <button type="button" class="btn btn-primary" onClick="add_low()"><?php echo $lang_array["custom_list_text34"] ?></button>
                                            <button type="button" class="btn btn-primary" onClick="add_low()"><?php echo $lang_array["custom_list_text35"] ?></button>
                                            <button type="button" class="btn btn-primary" onClick="add_low()"><?php echo $lang_array["custom_list_text36"] ?></button>
                                            <button type="button" class="btn btn-primary" onClick="add_low()"><?php echo $lang_array["custom_list_text37"] ?></button>
                                        </div>
                                        <br/>       
										-->
                                        
                                        
                                        
								    	<label class="control-label"><?php echo $lang_array["custom_list_text22"] ?></label>
                                       	<div class="controls" style="text-align:left;">
                                        	<select id="find_select_id" name=""  style='width:180px;' onchange="">
											<?php
											echo "<option value='0' selected='selected' style='width:120px;'>" . $lang_array["custom_list_text23"] . "</option>";
											echo "<option value='1' style='width:120px;'>" . $lang_array["custom_list_text24"] . "</option>";
											echo "<option value='2' style='width:120px;'>" . $lang_array["custom_list_text25"] . "</option>";
											echo "<option value='3' style='width:120px;'>" . $lang_array["custom_list_text26"] . "</option>";
											echo "<option value='4' style='width:120px;'>" . $lang_array["custom_list_text27"] . "</option>";
											echo "<option value='5' style='width:120px;'>" . $lang_array["custom_list_text28"] . "</option>";
											echo "<option value='6' style='width:120px;'>" . $lang_array["custom_list_text89"] . "</option>";
											echo "<option value='7' style='width:120px;'>" . $lang_array["custom_list_text90"] . "</option>";
											echo "<option value='8' style='width:120px;'>" . $lang_array["custom_list_text94"] . "</option>";
											echo "<option value='9' style='width:120px;'>" . $lang_array["custom_list_text95"] . "</option>";
											?>
    										</select>
                                        
                                            <input class="input-medium focused" id="find_id" name="find_id" type="text" value="">
                                            <button type="button" class="btn btn-primary" onClick="find_content()"><?php echo $lang_array["custom_list_text22"] ?></button>
                                            <button type="button" class="btn btn-primary" onClick="reduction()"><?php echo $lang_array["custom_list_text29"] ?></button>
                                            &nbsp;&nbsp;
                                            <?php echo $lang_array["custom_list_text73"] ?>:<?php echo $numrows; ?>&nbsp;
											<?php echo $lang_array["custom_list_text74"] ?>：<?php echo $allow_yes_numrows; ?>&nbsp;
    										<?php echo $lang_array["custom_list_text75"] ?>：<?php echo $allow_no_numrows; ?>&nbsp;
    										<?php echo $lang_array["custom_list_text76"] ?>：<?php echo $allow_test_numrows; ?>&nbsp;
                                            <?php echo $lang_array["custom_list_text91"] ?>：<?php echo $allow_pause_numrows; ?>&nbsp;
    										<?php echo $lang_array["custom_list_text87"] ?>：<?php echo $online_numrows; ?>
    
                                        </div>
                                        <br/>                         
                                    </div>
                                    
                                     
                                    <table cellpadding="0" cellspacing="0" border="0" class="table table-striped table-bordered" style="table-layout:fixed;">
                                        <thead>
                                            <tr>
                                            	<th width="3%" style="vertical-align:middle; text-align:center;"><?php echo $lang_array["custom_list_text83"] ?></th>
            									<th width="12%" style="vertical-align:middle; text-align:center;"><?php echo $lang_array["custom_list_text2"] ?></th>
          										<th width="10%" style="vertical-align:middle; text-align:center;"><?php echo $lang_array["custom_list_text3"] ?></th>
            									<th width="8%" style="vertical-align:middle; text-align:center;"><?php echo $lang_array["custom_list_text4"] ?></th>
            									<th width="7%" style="vertical-align:middle; text-align:center;"><?php echo $lang_array["custom_list_text5"] ?></th>
            									<th width="7%" style="vertical-align:middle; text-align:center;"><?php echo $lang_array["custom_list_text6"] ?></th>
            									<th width="4%" style="vertical-align:middle; text-align:center;"><?php echo $lang_array["custom_list_text7"] ?></th>
            									<th width="3%" style="vertical-align:middle; text-align:center;"><?php echo $lang_array["custom_list_text8"] ?></th>
            									<th width="7%" style="vertical-align:middle; text-align:center;"><?php echo $lang_array["custom_list_text9"] ?></th>
            									<th width="7%" style="vertical-align:middle; text-align:center;"><?php echo $lang_array["custom_list_text10"] ?></th>
            									<th width="8%" style="vertical-align:middle; text-align:center;"><?php echo $lang_array["custom_list_text11"] ?></th>
                                                <th width="4%" style="vertical-align:middle; text-align:center;"><?php echo $lang_array["custom_list_text84"] ?></th>
            									<th width="5%" style="vertical-align:middle; text-align:center;"><?php echo $lang_array["custom_list_text12"] ?></th>
            									<th width="5%" style="vertical-align:middle; text-align:center;"><?php echo $lang_array["custom_list_text13"] ?></th>
            									<th width="10%" style="vertical-align:middle; text-align:center;"><?php echo $lang_array["custom_list_text14"] ?></th>
                                            </tr>
                                        </thead>
                                        
<?php
			$null_array = array();
			echo "<tbody>";
			foreach($namess as $names) 
			{
				if($names[4] != "null" && strlen($names[4]) > 5 && strlen($names[31]) < 5)
				{
					$sql->update_data_3($mydb, $mytable, "cpu", $names[1], "mac", $names[0], "startime", $names[4]);
				}

				if($names[4] == "null" && $names[5] == "null" && abs(time() - strtotime($names[8])) > 24*3600*3)
				{
					array_push($null_array,$names);
					//continue;
				}
				
				$space_bgcolor = "";
				
				echo "<tr class='odd gradeA'>";
					echo "<td style='vertical-align:middle; text-align:center;word-wrap:break-word;'>" . "<input name='custom_checkbox' type='checkbox' style='width:15px;height:25px;' value='" . $names[0] . "' />". "</td>";
					if(strlen($names[38]) == 1 && intval($names[38]) == 3)
						$space_bgcolor = "background-color:#FF0000;";
					else if(strlen($names[38]) == 1 && intval($names[38]) == 2)
						$space_bgcolor = "background-color:#7F7F7F;";
						
					//echo "<td style='vertical-align:middle; text-align:center;word-wrap:break-word;'>"."<input name='custom_checkbox' style='width:15px;height:28px;' type='checkbox' value='" . $names[0] . "#" . $names[1] . "' />"."</td>";
					echo "<td style='" .$space_bgcolor . "vertical-align:middle; text-align:center;word-wrap:break-word;' ondblclick='deleteghost(\"" . $names[0] . "\",\"" . $names[1] . "\")', onmouseover='getghost(this,\"" . $names[38] . "\")'>" . $names[0]. "</td>";
					if($names[1] != "88888888")
						echo "<td style='vertical-align:middle; text-align:center;word-wrap:break-word;'>" . $names[1]. "</td>";
					else
						echo "<td style='vertical-align:middle; text-align:center;word-wrap:break-word;'>" . $lang_array["custom_list_text82"] . "</td>";
						
					$space_bgcolor = "";
					if(intval($names[33]) > 10)
						$space_bgcolor = "background-color:#FF0000;";
					else if(intval($names[33]) > 5)
						$space_bgcolor = "background-color:#7F7F7F;";
						
					echo "<td style='" .$space_bgcolor . "vertical-align:middle; text-align:center;word-wrap:break-word;' ondblclick='findIp(\"" . $names[2] . "\")', onmouseover='getValue(this,\"" . $names[2] . "\")'>" . $names[3] . "</td>";
					
					if(strcmp($names[4],"proxy") == 0)
					{
						echo "<td style='vertical-align:middle; text-align:center;word-wrap:break-word;'>" . $lang_array["custom_list_text15"] . "</td>";
					}
					else
					{
						echo "<td style='vertical-align:middle; text-align:center;word-wrap:break-word;'>" . $names[4]. "</td>";
					}
					
					if(strcmp($names[5],"-1") == 0)
					{
						echo "<td style='vertical-align:middle; text-align:center;word-wrap:break-word;'>" . $lang_array["custom_list_text16"] . "</td>";
					}
					else
					{
						echo "<td style='vertical-align:middle; text-align:center;word-wrap:break-word;'>" . $names[5]. "</td>";
					}
					
					if(strcmp($names[5],"null") == 0)
					{
					 	echo "<td style='vertical-align:middle; text-align:center;word-wrap:break-word;'>" . $lang_array["custom_list_text51"] . "</td>";
					}
					else
					{
						if(strcmp($names[4],"proxy") == 0)
						{
							if(strcmp($names[5],"-1") == 0)
							{
								echo "<td style='vertical-align:middle; text-align:center;word-wrap:break-word;'>" . $lang_array["custom_list_text52"] . "</td>";
							}
							else
							{
								echo "<td style='vertical-align:middle; text-align:center;word-wrap:break-word;'>" . $names[5] . $lang_array["custom_list_text55"] . "</td>";
							}
						}
						else if(strcmp($names[5],"-1") == 0)
						{
							echo "<td style='vertical-align:middle; text-align:center;word-wrap:break-word;'>" . $lang_array["custom_list_text52"] . "</td>";
						}
						else
						{
							$starttime = getChaBetweenTwoDate(date('Y-m-d',time()),$names[4]);
							$lefttime = getChaBetweenTwoDate($names[5],date('Y-m-d',time()));
							if($starttime < 0)
							{
								echo "<td style='vertical-align:middle; text-align:center;word-wrap:break-word;'>" . $lang_array["custom_list_text53"] . "</td>";
							}
							else if($lefttime < 0)
							{
								echo "<td style='vertical-align:middle; text-align:center;word-wrap:break-word;'>" . $lang_array["custom_list_text54"] . "</td>";
							}
							else
							{
								echo "<td style='vertical-align:middle; text-align:center;word-wrap:break-word;'>" . $lefttime . $lang_array["custom_list_text55"] ."</td>";
							}
						}
					}
										
					if($names[6] == "pre")
						echo "<td style='background-color:#00FF00; vertical-align:middle; text-align:center;word-wrap:break-word;'>" . $lang_array["custom_list_text38"] . "</td>";
					else if($names[6] == "yes")
						echo "<td style='background-color:#00FFFF; vertical-align:middle; text-align:center;word-wrap:break-word;'>" . $lang_array["yes_text1"] . "</td>";
					else if($names[6] == "pause")
						echo "<td style='background-color:#7F7F7F; vertical-align:middle; text-align:center;word-wrap:break-word;'>" . $lang_array["custom_list_text86"] . "</td>";	
					else
						echo "<td style='background-color:#FF0000; vertical-align:middle; text-align:center;word-wrap:break-word;'>" . $lang_array["no_text1"] . "</td>";
											
					if(strcmp($names[9],"ERROR")==0)
						echo "<td style='vertical-align:middle; text-align:center;word-wrap:break-word;'>".$lang_array["custom_list_text39"]."</td>";
					else if(strcmp($names[9],"auto")==0)
						echo "<td style='vertical-align:middle; text-align:center;word-wrap:break-word;'>".$lang_array["custom_list_text40"]."</td>";
					else if(strcmp($names[9],"all")==0)
						echo "<td style='vertical-align:middle; text-align:center;word-wrap:break-word;'>".$lang_array["custom_list_text41"]."</td>";
					else 
						echo "<td style='vertical-align:middle; text-align:center;word-wrap:break-word;'>".$lang_array["custom_list_text42"]."</td>";
						
					//echo "<td>" . $names[9]. "</td>";
					if(strcmp($names[7],"ERROR")==0)
						echo "<td style='vertical-align:middle; text-align:center;word-wrap:break-word;'>" . $lang_array["custom_list_text56"] . "</td>";
					else if(strcmp($names[7],"me")==0)
						echo "<td style='vertical-align:middle; text-align:center;word-wrap:break-word;'>" . $lang_array["custom_list_text57"] . "</td>";
					else if(strcmp($names[7],"auto")==0)
						echo "<td style='vertical-align:middle; text-align:center;word-wrap:break-word;'>" . $lang_array["custom_list_text58"] . "</td>";
					else if(strcmp($names[7],"all")==0)
						echo "<td style='vertical-align:middle; text-align:center;word-wrap:break-word;'>" . $lang_array["custom_list_text59"] . "</td>";
					else
					{
						if(isset( $type_array[$names[7]]))
							echo "<td style='vertical-align:middle; text-align:center;word-wrap:break-word;'>" . $type_array[$names[7]] . "</td>";
						else
							echo "<td style='vertical-align:middle; text-align:center;word-wrap:break-word;'>" . $lang_array["custom_list_text60"] . "</td>";
					}
					
					
					if(getChaBetweenTwoDate2(date('Y-m-d',time()),$names[8]) > 7)
						echo "<td style='background-color:#FF7F27; vertical-align:middle; text-align:center;word-wrap:break-word;'>" . $names[8]. "</td>";
					else
						echo "<td style='vertical-align:middle; text-align:center;word-wrap:break-word;'>" . $names[8]. "</td>";
					
					$model = $names[32];
					if(strcmp($model,"null") == 0 || strlen($model) <= 0 || $model == null)
						echo "<td style='vertical-align:middle; text-align:center;word-wrap:break-word;'>" . $lang_array["custom_list_text85"] . "</td>";
					else
					{
						//$ips = $names[22];
						$preloginmodel = "";
						$ips = explode("#",$names[17]);
						if(count($ips) > 0)
						{
							$login = explode("&",$ips[0]);	
							if(count($login) >= 3)
								$preloginmodel = $login[2];
						}
						$td = "<td style='vertical-align:middle; text-align:center;word-wrap:break-word;'>";
						if(strlen($preloginmodel) > 1 && strcmp(trim($model),trim($preloginmodel)) != 0) 
						{
							$td = "<td style='background-color:#7F7F7F; vertical-align:middle; text-align:center;word-wrap:break-word;'>";
						}
						if(intval($names[35]) == 1)
							$td = "<td style='background-color:#FF0000; vertical-align:middle; text-align:center;word-wrap:break-word;'>";
						//if(strlen($model) <= 5)
						{
							echo $td . $model . "</td>";
						}
						//else
						{
						//	echo $td . substr($model,0,4) . "~" . "</td>";
						}
					}
					//$member = $sql->query_data_2($mydb, $mytable,"mac",$names[0],"cpuid",$names[1],"param0");
					$member = $names[14];
					if(strcmp($member,"null") == 0 || strlen($member) <= 0 || $member == null)
						echo "<td style='vertical-align:middle; text-align:center;word-wrap:break-word;'>" . $lang_array["custom_list_text61"] . "</td>";		//会员号
					else
						echo "<td style='vertical-align:middle; text-align:center;word-wrap:break-word;'>" . $member . "</td>";		//会员号
					
					if($names[10] == "admin")
						echo "<td style='vertical-align:middle; text-align:center;word-wrap:break-word;'>" . $lang_array["custom_list_text96"] . "</td>";
					else if(strlen($proxy_array[$names[10]]) > 0)
						echo "<td style='vertical-align:middle; text-align:center;word-wrap:break-word;'>" . $proxy_array[$names[10]]. "</td>";
					else
						echo "<td style='vertical-align:middle; text-align:center;word-wrap:break-word;'>" . $names[10]. "</td>";
					echo "<td style='vertical-align:middle; text-align:center;word-wrap:break-word;'><a href='custom_edit.php?mac=" . $names[0] . "&cpuid=" . $names[1] . "'>" . $lang_array["custom_list_text62"] . "</a>";	
					echo "&nbsp";
					echo "<a href='authenticate_list.php?mac=" . $names[0] . "&cpuid=" . $names[1] . "'>". $lang_array["custom_list_text63"] ."</a>";
					echo "&nbsp";
					echo "<a href='#' onclick='delete_user(\"" . $names[0] . "\",\"". $names[1] . "\")'>". $lang_array["del_text1"] ."</a></td>";	
				echo "</tr>";
			}
			
			/*
			foreach($null_array as $names) {
				echo "<tr class='odd gradeA'>";
					echo "<td style='vertical-align:middle; text-align:center;word-wrap:break-word;'>" . $names[0]. "</td>";
					echo "<td style='vertical-align:middle; text-align:center;word-wrap:break-word;'>" . $names[1]. "</td>";
					echo "<td style='vertical-align:middle; text-align:center;word-wrap:break-word;'>" . $names[3]. "</td>";
					
					if(strcmp($names[4],"proxy") == 0)
					{
						echo "<td style='vertical-align:middle; text-align:center;word-wrap:break-word;'>" . $lang_array["custom_list_text15"] . "</td>";
					}
					else
					{
						echo "<td style='vertical-align:middle; text-align:center;word-wrap:break-word;'>" . $names[4]. "</td>";
					}
					
					if(strcmp($names[5],"-1") == 0)
					{
						echo "<td style='vertical-align:middle; text-align:center;word-wrap:break-word;'>" . $lang_array["custom_list_text16"] . "</td>";
					}
					else
					{
						echo "<td style='vertical-align:middle; text-align:center;word-wrap:break-word;'>" . $names[5]. "</td>";
					}
					
					if(strcmp($names[5],"null") == 0)
					{
					 	echo "<td style='vertical-align:middle; text-align:center;word-wrap:break-word;'>" . $lang_array["custom_list_text51"] . "</td>";
					}
					else
					{
						if(strcmp($names[4],"proxy") == 0)
						{
							if(strcmp($names[5],"-1") == 0)
							{
								echo "<td style='vertical-align:middle; text-align:center;word-wrap:break-word;'>" . $lang_array["custom_list_text52"] . "</td>";
							}
							else
							{
								echo "<td style='vertical-align:middle; text-align:center;word-wrap:break-word;'>" . $names[5] . $lang_array["custom_list_text55"] . "</td>";
							}
						}
						else if(strcmp($names[5],"-1") == 0)
						{
							echo "<td style='vertical-align:middle; text-align:center;word-wrap:break-word;'>" . $lang_array["custom_list_text52"] . "</td>";
						}
						else
						{
							$starttime = getChaBetweenTwoDate(date('Y-m-d',time()),$names[4]);
							$lefttime = getChaBetweenTwoDate($names[5],date('Y-m-d',time()));
							if($starttime < 0)
							{
								echo "<td style='vertical-align:middle; text-align:center;word-wrap:break-word;'>" . $lang_array["custom_list_text53"] . "</td>";
							}
							else if($lefttime < 0)
							{
								echo "<td style='vertical-align:middle; text-align:center;word-wrap:break-word;'>" . $lang_array["custom_list_text54"] . "</td>";
							}
							else
							{
								echo "<td style='vertical-align:middle; text-align:center;word-wrap:break-word;'>" . $lefttime . $lang_array["custom_list_text55"] ."</td>";
							}
						}
					}
										
					if($names[6] == "pre")
						echo "<td style='background-color:#00FF00; vertical-align:middle; text-align:center;word-wrap:break-word;'>" . $lang_array["custom_list_text38"] . "</td>";
					else if($names[6] == "yes")
						echo "<td style='background-color:#00FFFF; vertical-align:middle; text-align:center;word-wrap:break-word;'>" . $lang_array["yes_text1"] . "</td>";
					else
						echo "<td style='background-color:#FF0000; vertical-align:middle; text-align:center;word-wrap:break-word;'>" . $lang_array["no_text1"] . "</td>";
											
					if(strcmp($names[9],"ERROR")==0)
						echo "<td style='vertical-align:middle; text-align:center;word-wrap:break-word;'>".$lang_array["custom_list_text39"]."</td>";
					else if(strcmp($names[9],"auto")==0)
						echo "<td style='vertical-align:middle; text-align:center;word-wrap:break-word;'>".$lang_array["custom_list_text40"]."</td>";
					else if(strcmp($names[9],"all")==0)
						echo "<td style='vertical-align:middle; text-align:center;word-wrap:break-word;'>".$lang_array["custom_list_text41"]."</td>";
					else 
						echo "<td style='vertical-align:middle; text-align:center;word-wrap:break-word;'>".$lang_array["custom_list_text42"]."</td>";
						
					//echo "<td>" . $names[9]. "</td>";
					if(strcmp($names[7],"ERROR")==0)
						echo "<td style='vertical-align:middle; text-align:center;word-wrap:break-word;'>" . $lang_array["custom_list_text56"] . "</td>";
					else if(strcmp($names[7],"me")==0)
						echo "<td style='vertical-align:middle; text-align:center;word-wrap:break-word;'>" . $lang_array["custom_list_text57"] . "</td>";
					else if(strcmp($names[7],"auto")==0)
						echo "<td style='vertical-align:middle; text-align:center;word-wrap:break-word;'>" . $lang_array["custom_list_text58"] . "</td>";
					else if(strcmp($names[7],"all")==0)
						echo "<td style='vertical-align:middle; text-align:center;word-wrap:break-word;'>" . $lang_array["custom_list_text59"] . "</td>";
					else
					{
						if(isset( $type_array[$names[7]]))
							echo "<td style='vertical-align:middle; text-align:center;word-wrap:break-word;'>" . $type_array[$names[7]] . "</td>";
						else
							echo "<td style='vertical-align:middle; text-align:center;word-wrap:break-word;'>" . $lang_array["custom_list_text60"] . "</td>";
					}
					
					echo "<td style='vertical-align:middle; text-align:center;word-wrap:break-word;'>" . $names[8]. "</td>";
					
					//$member = $sql->query_data_2($mydb, $mytable,"mac",$names[0],"cpuid",$names[1],"param0");
					$member = $names[14];
					if(strcmp($member,"null") == 0 || strlen($member) <= 0 || $member == null)
						echo "<td style='vertical-align:middle; text-align:center;word-wrap:break-word;'>" . $lang_array["custom_list_text61"] . "</td>";		//会员号
					else
						echo "<td style='vertical-align:middle; text-align:center;word-wrap:break-word;'>" . $member . "</td>";		//会员号
					
					echo "<td style='vertical-align:middle; text-align:center;word-wrap:break-word;'>" . $names[10]. "</td>";
					
					echo "<td style='vertical-align:middle; text-align:center;word-wrap:break-word;'><a href='custom_edit.php?mac=" . $names[0] . "&cpuid=" . $names[1] . "'>" . $lang_array["custom_list_text62"] . "</a></img>";	
					echo "&nbsp";
					echo "<a href='authenticate_list.php?mac=" . $names[0] . "&cpuid=" . $names[1] . "'>". $lang_array["custom_list_text63"] ."</a></img>";
					echo "&nbsp";
					echo "<a href='#' onclick='delete_user(\"" . $names[0] . "\",\"". $names[1] . "\")'>". $lang_array["del_text1"] ."</a></img></td>";	
				echo "</tr>";	
			}
			*/
			
			echo "</tbody>";
			
			$sql->disconnect_database();
?>                                       
          	</table>
            
            <input name="" class="btn btn-primary" type="button" value="<?php echo $lang_array["account_add_text16"] ?>" onclick="selectAll()"/>
            <input name="" class="btn btn-primary" type="button" value="<?php echo $lang_array["account_add_text17"] ?>" onclick="noAll()"/>
                                    
			<div class="form-actions">
            <?php
				if(!isset($_GET["loginremote"]) && !isset($_GET["pause"]) && !isset($_GET["ghost"]) && !isset($_GET["modelerror"]))
				{
					echo "<button type='button' class='btn btn-primary' onclick='go_pre()'>" . $lang_array["custom_list_text17"] . "</button>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
                	echo "<button type='button' class='btn btn-primary' onclick='go_page()'>" . $lang_array["custom_list_text19"] . "</button>&nbsp;<input class='input-mini focused' id='pageid' name='pageid' type='text' value='" . ($page+1) . "' >" .  $lang_array["custom_list_text20"] . "/" . $pages . "&nbsp;" . $lang_array["custom_list_text20"] . "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
                	echo "<button type='button' class='btn btn-primary' onclick='go_back()'>" . $lang_array["custom_list_text18"] . "</button>";
                }
            ?>
            </div>                                    
<?php			
		/*
		for($ii=0; $ii<$pages; $ii++)
		{
			$kk = $ii + 1;
			echo "<a href='custom_list.php?page=".$ii."'>[". $kk ."]</a>";
		}
		echo "<br/>";
		$page_show = $page + 1;
		echo "共" . $pages . "页" . "/" . "第" . $page_show . "页";
		*/
?>
									</form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

         </div>    
        <!--/.fluid-container-->

        <link href="vendors/datepicker.css" rel="stylesheet" media="screen">
        <link href="vendors/uniform.default.css" rel="stylesheet" media="screen">
        <link href="vendors/chosen.min.css" rel="stylesheet" media="screen">

        <link href="vendors/wysiwyg/bootstrap-wysihtml5.css" rel="stylesheet" media="screen">
        
        <script src="vendors/jquery-1.9.1.js"></script>
        <script src="bootstrap/js/bootstrap.min.js"></script>
        <script src="vendors/jquery.uniform.min.js"></script>
        <script src="vendors/chosen.jquery.min.js"></script>
        <script src="vendors/bootstrap-datepicker.js"></script>

        <script src="vendors/wysiwyg/wysihtml5-0.3.0.js"></script>
        <script src="vendors/wysiwyg/bootstrap-wysihtml5.js"></script>

        <script src="vendors/wizard/jquery.bootstrap.wizard.min.js"></script>

		<script type="text/javascript" src="vendors/jquery-validation/dist/jquery.validate.min.js"></script>
		<script src="assets/form-validation.js"></script>
        
		<script src="assets/scripts.js"></script>
        <script>
		
		jQuery(document).ready(function() {   
	   		FormValidation.init();
		});

        $(function() {
            $(".datepicker").datepicker();
            $(".uniform_on").uniform();
            $(".chzn-select").chosen();
            $('.textarea').wysihtml5();

            $('#rootwizard').bootstrapWizard({onTabShow: function(tab, navigation, index) {
                var $total = navigation.find('li').length;
                var $current = index+1;
                var $percent = ($current/$total) * 100;
                $('#rootwizard').find('.bar').css({width:$percent+'%'});
                // If it's the last tab then hide the last button and show the finish instead
                if($current >= $total) {
                    $('#rootwizard').find('.pager .next').hide();
                    $('#rootwizard').find('.pager .finish').show();
                    $('#rootwizard').find('.pager .finish').removeClass('disabled');
                } else {
                    $('#rootwizard').find('.pager .next').show();
                    $('#rootwizard').find('.pager .finish').hide();
                }
            }});
            $('#rootwizard .finish').click(function() {
                alert('Finished!, Starting over!');
                $('#rootwizard').find("a[href*='tab1']").trigger('click');
            });
        });
		
        $(function() {
            
        });
		
		function getValue(_obj,ip){
   			//var tValue = _obj.innerText;
   			_obj.setAttribute("title","<?php echo $lang_array["custom_list_text88"] ?>:" + ip);
		}

		function delete_user(mac,cpuid)
		{
			if(confirm("<?php echo $lang_array["custom_list_text64"] ?>" + "：CPUID= " + cpuid + " MAC=" + mac + " ?") == true)
  			{
				var url = "custom_del.php?name=" + mac + "&cpuid=" + cpuid + "&page=" + "<?php echo $page ?>";
				window.location.href = url;
  			}
		}

		function edit_user(name)
		{
  			{
				var url = "user_edit.php?name=" + name;
				window.location.href = url;
  			}
		}
		
		function go_page()
		{
			
<?php
			$param = "";
			if(isset($_GET["loginremote"]))
				$param = "&loginremote=" . $_GET["loginremote"];
					
			if(isset($_GET["pause"]))
				$param = "&pause=" . $_GET["pause"];
					
			if(isset($_GET["ghost"]))
				$param = "&ghost=" . $_GET["ghost"];
					
			if(isset($_GET["modelerror"]))
				$param = "&modelerror=" . $_GET["modelerror"];

?>			
			
			var pageid = document.getElementById("pageid").value;
			var url = "custom_list.php?page=" + (pageid-1);
			window.location.href = url + "<?php echo $param ?>";
		}
		
		function go_pre()
		{
<?php
			if($page-1 >= 0)
			{
				echo "var url = 'custom_list.php?page=".($page-1)."';";
				$param = "";
				if(isset($_GET["loginremote"]))
					$param = "&loginremote=" . $_GET["loginremote"];
					
				if(isset($_GET["pause"]))
					$param = "&pause=" . $_GET["pause"];
					
				if(isset($_GET["ghost"]))
					$param = "&ghost=" . $_GET["ghost"];
					
				if(isset($_GET["modelerror"]))
					$param = "&modelerror=" . $_GET["modelerror"];
						
				echo "window.location.href = url" . $param . ";";
			}
?>
		}
		
		function findIp(ip)
		{
			document.getElementById("find_id").value = ip;
			var all_options = document.getElementById("find_select_id").options;
			for (i=0; i<all_options.length; i++){
				if(all_options[i].value == "2") 
					all_options[i].selected = true;
			}
		}
		
		function find_content()
		{
			var value = document.getElementById("find_select_id").value;
			if(value == "0")
			{
				find_text();	
			}
			else if(value == "1")
			{
				cpuid_text();
			}
			else if(value == "2")
			{
				ip_text();
			}
			else if(value == "3")
			{
				find_member();
			}
			else if(value == "4")
			{
				find_number();
			}
			else if(value == "5")
			{
				find_proxy();
			}
			else if(value == "6")
			{
				loginremote_text();
			}
			else if(value == "7")
			{
				pause_text();
			}
			else if(value == "8")
			{
				ghost_text();
			}
			else if(value == "9")
			{
				modelerror_text();
			}
		}
		
		function find_text()
		{
			var value = document.getElementById("find_id").value;
			window.location.href = "custom_list.php?find=" + value + "&page=0";
		}

		function find_proxy()
		{
			var value = document.getElementById("find_id").value;
			window.location.href = "custom_list.php?proxy=" + value + "&page=0";
		}

		function find_member()
		{
			var value = document.getElementById("find_id").value;
			window.location.href = "custom_list.php?member=" + value + "&page=0";	
		}

		function find_number()
		{
			var value = document.getElementById("find_id").value;
			window.location.href = "custom_list.php?number=" + value + "&page=0";	
		}

		function cpuid_text()
		{
			var value = document.getElementById("find_id").value;
			window.location.href = "custom_list.php?cpuid=" + value + "&page=0";	
		}

		function ip_text()
		{
			var value = document.getElementById("find_id").value;
			window.location.href = "custom_list.php?ip=" + value + "&page=0";	
		}

		function loginremote_text()
		{
			window.location.href = "custom_list.php?loginremote=1";	
		}
		
		function pause_text()
		{
			window.location.href = "custom_list.php?pause=1";
		}
		
		function ghost_text()
		{
			window.location.href = "custom_list.php?ghost=1";
		}
		
		function modelerror_text()
		{
			window.location.href = "custom_list.php?modelerror=1";
		}
		
		function reduction()
		{
			window.location.href = "custom_list.php?page=0";
		}

		function custom_close()
		{
			window.location.href = "custom_all_edit.php";
		}

		/*
		function custom_batch()  
 	    {  
			alert(1);
			var tempForm = document.createElement("form");  
			tempForm.id="tempForm1";  
			tempForm.method="post";  
			tempForm.action="custom_batch_list.php";  
			tempForm.target="newwindow"; 
			 
			alert(2);
			var hideInput = document.createElement("input");  
			hideInput.type="hidden";  
			hideInput.name= "content"
			hideInput.value= "12345678";
			
			alert(3);
			tempForm.appendChild(hideInput);  
			 alert(31);
			tempForm.attachEvent("onsubmit",function(){ openWindow("newwindow"); });
			alert(32);
			document.body.appendChild(tempForm);  
			
			alert(4);
			tempForm.fireEvent("onsubmit");
			tempForm.submit();
			document.body.removeChild(tempForm);
			
			alert(5);
		}
		
		function openWindow(name)
		{
			
			//window.open("about:blank", 'newwindow', 'height=280, width=550, top=300, left=300, toolbar=no, menubar=no, scrollbars=no, resizable=no,location=n o, status=no');
			
		}
		*/
		function custom_batch()
		{
			var customlist = "";
			var chkObjs = document.getElementsByName("custom_checkbox");
			for(var i=0;i<chkObjs.length;i++){
				if(chkObjs[i].checked){
					customlist = customlist + chkObjs[i].value;
					customlist = customlist + "|";
				}
			}
			
			openWindowWithPost("custom_batch_list.php","newwindow","selbox",customlist);
		}
		
		function openWindowWithPost(url,name,keys,values)  
    	{  
        	var newWindow = window.open(url, name , 'height=250, width=850, top=300, left=300, toolbar=no, menubar=no, scrollbars=no, resizable=no,location=n o, status=no');  
        	if (!newWindow)  
            	return false;  
              
        	var html = "";  
        	html += "<html><head></head><body><form id='formid' method='post' action='" + url + "'>";  
        	if (keys && values)  
        	{  
           		html += "<input type='hidden' name='" + keys + "' value='" + values + "'/>";  
        	}  
          
        	html += "</form><script type='text/javascript'>document.getElementById('formid').submit();";  
        	html += "<\/script></body></html>".toString().replace(/^.+?\*|\\(?=\/)|\*.+?$/gi, "");   
        	newWindow.document.write(html);  
          
        	return newWindow;  
    	}  
	
		function custom_flash()
		{
			window.open("custom_flash.php", 'newwindow', 'height=280, width=550, top=300, left=300, toolbar=no, menubar=no, scrollbars=no, resizable=no,location=n o, status=no');
		}
		
		function user_login_timers()
		{
			window.location.href = "user_login_times.php";
		}

		function custom_introduction()
		{
			window.location.href = "custom_introduction_edit.php";
		}
		
		function deleteghost(mac,cpuid)
		{
			if(confirm("<?php echo $lang_array["custom_list_text93"] ?>" + "：CPUID= " + cpuid + " MAC=" + mac + " ?") == true)
			{
				window.location.href = "custom_ghost_del.php?cpuid=" + cpuid + "&mac=" + mac + "&page=<?php echo $page ?>";
			}
		}
		
		function getghost(_obj,ghost)
		{
			_obj.setAttribute("title","<?php echo $lang_array["custom_list_text92"] ?>");
		}
		
		function selectAll() //全选
		{
			
			var objs = document.getElementsByName('custom_checkbox');
			var i;
			for(i = 0; i < objs.length; i++)
			{
				if(objs[i].type == "checkbox")
				{
					objs[i].checked = true;
				}
			}
		} 

		function noAll() //全选
		{
			var objs = document.getElementsByName('custom_checkbox');
			var i;
			for(i = 0; i < objs.length; i++)
			{
				if(objs[i].type == "checkbox")
				{
					objs[i].checked = false;
				}
			}
		} 
		
		function get_type_checkbox_value()
		{
			var value = "";
			var value_array = new Array();
			var type_checkboxs = document.getElementsByName("custom_checkbox");
			for(var i = 0; i < type_checkboxs.length; i++)
			{
				if(type_checkboxs[i].type == "checkbox" && type_checkboxs[i].checked)
				{
					value_array.push(type_checkboxs[i].value);
				}
			}
	
			for(var ii = 0; ii < value_array.length; ii++)
			{
				value = value + value_array[ii];
				if(ii < value_array.length - 1)
					value = value + "|";
			}
	
			//alert(value);
			return value;
		}
		
		function go_back()
		{
<?php
			if($page+1 < $pages)
			{
				$param = "";
				if(isset($_GET["loginremote"]))
					$param = "&loginremote=" . $_GET["loginremote"];
					
				if(isset($_GET["pause"]))
					$param = "&pause=" . $_GET["pause"];
					
				if(isset($_GET["ghost"]))
					$param = "&ghost=" . $_GET["ghost"];
					
				if(isset($_GET["modelerror"]))
					$param = "&modelerror=" . $_GET["modelerror"];
					
				echo "var url = 'custom_list.php?page=".($page+1)."';";
				echo "window.location.href = url;";
			}
?>			
		}
        </script>
    </body>
    
</html>

<?PHP
if(Extension_Loaded('zlib')) Ob_End_Flush();
?>