<!DOCTYPE html>
<?php
	include_once "cn_lang.php";
	include_once "common.php";
	$sql = new DbSql();
	$sql->login_proxy();

	$sql->connect_database_default();
	$mydb = $sql->get_database();
	$sql->create_database($mydb);
	
	
	$type_array = array();
	$mytable = "playlist_type_table";
	$sql->create_table($mydb, $mytable, "name text, space longtext, id text, playlist longtext");
	$type_namess = $sql->fetch_datas($mydb, $mytable);
	foreach($type_namess as $type_names)
	{
		$type_array[$type_names[2]] = $type_names[0];
	}
	
	$size = 20;
	$offset = 0;
	$page = 0;
	if(isset($_GET["page"]))
	{
		$offset = $size*intval($_GET["page"]);
		$page = $_GET["page"];
	}
	
	$mytable = "proxy_table";
	$sql->create_table($mydb, $mytable, "name text, password text, ptip text, edit int, watermark text, validity date, allow int, remark text, ccount int, panal int, pwmd5 int, proxylevel int, proxybelong text");					
	$proxynamess = $sql->fetch_datas_where_2($mydb, $mytable, "proxylevel", "2","proxybelong",$_COOKIE["user"]);
	
	$mytable = "custom_table";
	$sql->create_table($mydb, $mytable, 
		"mac text,cpu text,ip text,space text, date text,
		time text,allow text, playlist text, online text, allocation text,
		proxy text, balance float,showtime text,contact text,member text,
		panal text,number text,ips text, onescrolltext text, onescrolltexttimes int, 
		numberdate int, scrollcontent text, scrolldate text, scrolltimes text, controlurl text, 		
		controltime int, unbundling int, accessdate timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP");
	if(isset($_GET["find"]) )
	{
		if(strlen($_GET["find"]) > 0)
		{
			$namess = $sql->fetch_datas_where_like_3_or($mydb, $mytable, "mac", $_GET["find"], "allow", "no", "proxy", $_COOKIE["user"], "allow", "pre");
			
			foreach($proxynamess as $proxynames)
			{
				$namess_tmp = $sql->fetch_datas_where_like_3_or($mydb, $mytable, "mac", $_GET["find"], "allow", "no", "proxy", $proxynames[0], "allow", "pre");
				
				$namess = array_merge($namess,$namess_tmp);
			}
			
			$numrows = count($namess);
		}
	}
	else if(isset($_GET["cpuid"]))
	{
		//$namess = $sql->fetch_datas_where_like_2($mydb, $mytable, "cpu", $_GET["cpuid"], "proxy", $_COOKIE["user"]);
		$namess = $sql->fetch_datas_where_like_3_or($mydb, $mytable, "cpu", $_GET["cpuid"], "allow", "no", "proxy", $_COOKIE["user"], "allow", "pre");
		foreach($proxynamess as $proxynames)
		{
			$namess_tmp = $sql->fetch_datas_where_like_3_or($mydb, $mytable, "cpu", $_GET["cpuid"], "allow", "no", "proxy", $proxynames[0], "allow", "pre");
				
			$namess = array_merge($namess,$namess_tmp);
		}
			
		$numrows = count($namess);		
	}
	else if(isset($_GET["number"]))
	{
		//$namess = $sql->fetch_datas_where_like_2($mydb, $mytable, "number", $_GET["number"], "proxy", $_COOKIE["user"]);
		$namess = $sql->fetch_datas_where_like_3_or($mydb, $mytable, "number", $_GET["number"], "allow", "no", "proxy", $_COOKIE["user"], "allow", "pre");
		foreach($proxynamess as $proxynames)
		{
			$namess_tmp = $sql->fetch_datas_where_like_3_or($mydb, $mytable, "number", $_GET["number"], "allow", "no", "proxy", $proxynames[0], "allow", "pre");
				
			$namess = array_merge($namess,$namess_tmp);
		}
		
		$numrows = count($namess);		
	}
	else if(isset($_GET["member"]))
	{
		//$namess = $sql->fetch_datas_where_like_2($mydb, $mytable, "member", $_GET["member"], "proxy", $_COOKIE["user"]);
		$namess = $sql->fetch_datas_where_like_3_or($mydb, $mytable, "member", $_GET["member"], "allow", "no", "proxy", $_COOKIE["user"], "allow", "pre");
		foreach($proxynamess as $proxynames)
		{
			$namess_tmp = $sql->fetch_datas_where_like_3_or($mydb, $mytable, "member", $_GET["member"], "allow", "no", "proxy", $proxynames[0], "allow", "pre");
				
			$namess = array_merge($namess,$namess_tmp);
		}
		
		$numrows = count($namess);		
	}
	else
	{
		//$namess = $sql->fetch_datas_limit_where_desc($mydb, $mytable, $offset, $size, "proxy", $_COOKIE["user"], "online"); 

		//$numrows = count($sql->fetch_datas_where($mydb, $mytable, "proxy", $_COOKIE["user"]));
		//$namess = $sql->fetch_datas_where_limit_desc_2($mydb, $mytable, "proxy", $_COOKIE["user"], $offset, $size, "startime", "date"); 
		
		$pcmd = "";
		//if(count($namess) < $size)
		{
			
			foreach($proxynamess as $proxynames)
			{
				//$namess_tmp = $sql->fetch_datas_where_limit_desc_2($mydb, $mytable, "proxy", $proxynames[0], $offset, $size, "startime", "date"); 
				//$namess = array_merge($namess,$namess_tmp);
			
				//$numrows_tmp = count($sql->fetch_datas_where($mydb, $mytable, "proxy", $proxynames[0]));
				//$numrows = $numrows + $numrows_tmp;
				$pcmd = $pcmd . " OR proxy = '" .  $proxynames[0] . "'";
			}
			
			
		}
		
		$cmd = "SELECT * FROM " . $mytable . " WHERE " . "(proxy" . " = '" . $_COOKIE["user"] . "'" . $pcmd . ") ORDER BY " . "startime" . " DESC, " . "date" . " DESC" . " LIMIT " . intval($offset) . "," . intval($size);
		
		
		$namess = $sql->fetch_datas_limit_desc_cmd($mydb, $mytable, $cmd);
		
		$cmd = "SELECT COUNT(*) AS count FROM " . $mytable . " WHERE " . "(proxy" . " = '" . $_COOKIE["user"] . "'" . $pcmd . ")";
		//echo "</br>cmd:" . $cmd;
		$numrows = $sql->count_fetch_datas_cmd($mydb, $mytable, $cmd);
		
		//echo "numrows:" . $numrows;
		//$namess = $sql->fetch_datas_limit_where_or($mydb, $mytable, $offset, $size, "proxy", $_COOKIE["user"], "allow", "pre");
		
	}
	


	$pages = 0;
	$pages = intval($numrows/$size);
	if($numrows%$size)
	{
		$pages++;
	}

	/*
	foreach($namess as $names) 
	{
		if(update_date(date("Y-m-d"),$names[5]))
		{
			$sql->update_data_2($mydb, $mytable, "mac", $names[0], "allow", "no");
		}
		else
		{
			$sql->update_data_2($mydb, $mytable, "mac", $names[0], "allow", "yes");
		}
	}
	*/

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
                                <div class="muted pull-left"><?php echo $lang_array["proxy_custom_list_text1"] ?></div>
                            </div>
                            <div class="block-content collapse in">
                                <div class="span12">
									<form class="form-horizontal" name="authform" method="post" action="" enctype='multipart/form-data'>
									<div class="control-group" style="background-color:#CCC"> 
                                        <br/>
                                        <label class="control-label"><?php echo $lang_array["custom_list_text22"] ?></label>  
                                       	<div class="controls" style="text-align:left;">
                                        	<!--
                                            <input class="input-medium focused" id="find_id" name="find_id" type="text" value="">
                                            <button type="button" class="btn btn-primary" onClick="find_text()"><?php //echo $lang_array["proxy_custom_list_text2"] ?></button>
                                            <button type="button" class="btn btn-primary" onClick="cpuid_text()"><?php //echo $lang_array["proxy_custom_list_text3"] ?></button>
                                            <button type="button" class="btn btn-primary" onClick="find_number()"><?php //echo $lang_array["proxy_custom_list_text4"] ?></button>
                                            <button type="button" class="btn btn-primary" onClick="find_member()"><?php //echo $lang_array["proxy_custom_list_text5"] ?></button>
                                            -->
                                            <select id="find_select_id" name=""  style='width:120px;' onchange="">
											<?php
											echo "<option value='0' selected='selected' style='width:120px;'>" . $lang_array["proxy_custom_list_text2"] . "</option>";
											echo "<option value='1' style='width:120px;'>" . $lang_array["proxy_custom_list_text3"] . "</option>";
											echo "<option value='2' style='width:120px;'>" . $lang_array["proxy_custom_list_text4"] . "</option>";
											echo "<option value='3' style='width:120px;'>" . $lang_array["proxy_custom_list_text5"] . "</option>";
											?>
    										</select>
                                            <input class="input-medium focused" id="find_id" name="find_id" type="text" value="">
                                            <button type="button" class="btn btn-primary" onClick="find_content()"><?php echo $lang_array["custom_list_text22"] ?></button>
                                            <button type="button" class="btn btn-primary" onClick="reduction()"><?php echo $lang_array["proxy_custom_list_text6"] ?></button>
                                        </div>
                                        <br/>
                                   	</div> 
                                     
                                    <table cellpadding="0" cellspacing="0" border="0" class="table table-striped table-bordered" style="table-layout:fixed;">
                                        <thead>
                                            <tr>
            									<th width="12%" style="vertical-align:middle; text-align:center;"><?php echo $lang_array["custom_list_text2"] ?></th>
          										<th width="15%" style="vertical-align:middle; text-align:center;"><?php echo $lang_array["custom_list_text3"] ?></th>
            									<th width="8%" style="vertical-align:middle; text-align:center;"><?php echo $lang_array["custom_list_text4"] ?></th>
            									<th width="7%" style="vertical-align:middle; text-align:center;"><?php echo $lang_array["custom_list_text5"] ?></th>
            									<th width="8%" style="vertical-align:middle; text-align:center;"><?php echo $lang_array["custom_list_text6"] ?></th>
            									<th width="4%" style="vertical-align:middle; text-align:center;"><?php echo $lang_array["custom_list_text7"] ?></th>
            									<th width="3%" style="vertical-align:middle; text-align:center;"><?php echo $lang_array["custom_list_text8"] ?></th>
            									<th width="7%" style="vertical-align:middle; text-align:center;"><?php echo $lang_array["custom_list_text9"] ?></th>
            									<th width="7%" style="vertical-align:middle; text-align:center;"><?php echo $lang_array["custom_list_text10"] ?></th>
            									<th width="8%" style="vertical-align:middle; text-align:center;"><?php echo $lang_array["custom_list_text11"] ?></th>
            									<th width="5%" style="vertical-align:middle; text-align:center;"><?php echo $lang_array["custom_list_text12"] ?></th>
            									<th width="5%" style="vertical-align:middle; text-align:center;"><?php echo $lang_array["custom_list_text13"] ?></th>
            									<th width="5%" style="vertical-align:middle; text-align:center;"><?php echo $lang_array["custom_list_text14"] ?></th>
                                            </tr>
                                        </thead>
                                        
<?php
			echo "<tbody>";
			foreach($namess as $names) {
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
					
					//echo "<td style='vertical-align:middle; text-align:center;word-wrap:break-word;'>" . $names[8]. "</td>";
					if(getChaBetweenTwoDate2(date('Y-m-d',time()),$names[8]) > 7)
						echo "<td style='background-color:#FF7F27; vertical-align:middle; text-align:center;word-wrap:break-word;'>" . $names[8]. "</td>";
					else
						echo "<td style='vertical-align:middle; text-align:center;word-wrap:break-word;'>" . $names[8]. "</td>";
						
					//$member = $sql->query_data_2($mydb, $mytable,"mac",$names[0],"cpuid",$names[1],"param0");
					$member = $names[14];
					if(strcmp($member,"null") == 0 || strlen($member) <= 0 || $member == null)
						echo "<td style='vertical-align:middle; text-align:center;word-wrap:break-word;'>" . $lang_array["custom_list_text61"] . "</td>";		//会员号
					else
						echo "<td style='vertical-align:middle; text-align:center;word-wrap:break-word;'>" . $member . "</td>";		//会员号
					
					echo "<td style='vertical-align:middle; text-align:center;word-wrap:break-word;'>" . $names[10]. "</td>";
					
					echo "<td style='vertical-align:middle; text-align:center;word-wrap:break-word;'><a href='proxy_custom_edit.php?mac=" . $names[0] . "&cpuid=" . $names[1] . "'>" . $lang_array["proxy_custom_list_text7"] . "</a></img>";	
					/*
					echo "&nbsp";
					echo "<a href='authenticate_list.php?mac=" . $names[0] . "&cpuid=" . $names[1] . "'>". $lang_array["custom_list_text63"] ."</a></img>";
					echo "&nbsp";
					echo "<a href='#' onclick='delete_user(\"" . $names[0] . "\",\"". $names[1] . "\")'>". $lang_array["del_text1"] ."</a></img></td>";
					*/	
				echo "</tr>";
			}
			echo "</tbody>";
			
			$sql->disconnect_database();
?>                                       
          	</table>
                                    
			<div class="form-actions">
				<button type="button" class="btn btn-primary" onclick="go_pre()"><?php echo $lang_array["custom_list_text17"] ?></button>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                <button type="button" class="btn btn-primary" onclick="go_page()"><?php echo $lang_array["custom_list_text19"] ?></button>&nbsp;<input class="input-mini focused" id="pageid" name="pageid" type="text" value="<?php echo ($page+1) ?>" ><?php echo $lang_array["custom_list_text20"] ?>/<?php echo $pages ?>&nbsp;<?php echo $lang_array["custom_list_text20"] ?>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                <button type="button" class="btn btn-primary" onclick="go_back()"><?php echo $lang_array["custom_list_text18"] ?></button>
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
		
		function delete_user(mac,cpuid)
		{
			if(confirm("<?php echo $lang_array["custom_list_text64"] ?>" + "：CPUID= " + cpuid + " MAC=" + mac + " ?") == true)
  			{
				var url = "custom_del.php?name=" + mac + "&cpuid=" + cpuid;
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
			var pageid = document.getElementById("pageid").value;
			var url = "proxy_custom_list.php?page=" + (pageid-1);
			window.location.href = url;
		}
		
		function go_pre()
		{
<?php
			if($page-1 >= 0)
			{
				echo "var url = 'proxy_custom_list.php?page=".($page-1)."';";
				echo "window.location.href = url;";
			}
?>
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
				find_number();
			}
			else if(value == "3")
			{
				find_member();
			}
		}
		
		function find_text()
		{
			var value = document.getElementById("find_id").value;
			window.location.href = "proxy_custom_list.php?find=" + value + "&page=0";
		}

		function find_proxy()
		{
			var value = document.getElementById("find_id").value;
			window.location.href = "proxy_custom_list.php?proxy=" + value + "&page=0";
		}

		function find_member()
		{
			var value = document.getElementById("find_id").value;
			window.location.href = "proxy_custom_list.php?member=" + value + "&page=0";	
		}

		function find_number()
		{
			var value = document.getElementById("find_id").value;
			window.location.href = "proxy_custom_list.php?number=" + value + "&page=0";	
		}

		function cpuid_text()
		{
			var value = document.getElementById("find_id").value;
			window.location.href = "proxy_custom_list.php?cpuid=" + value + "&page=0";	
		}

		function ip_text()
		{
			var value = document.getElementById("find_id").value;
			window.location.href = "proxy_custom_list.php?ip=" + value + "&page=0";	
		}

		function reduction()
		{
			window.location.href = "proxy_custom_list.php?page=0";
		}

		function custom_close()
		{
			window.location.href = "proxy_custom_list.php";
		}

		function go_back()
		{
<?php
			if($page+1 < $pages)
			{
				echo "var url = 'proxy_custom_list.php?page=".($page+1)."';";
				echo "window.location.href = url;";
			}
?>			
		}
        </script>
    </body>
</html>