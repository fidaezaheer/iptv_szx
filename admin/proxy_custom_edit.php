<!DOCTYPE html>
<?php
	include_once "cn_lang.php";
	include_once "common.php";
	$sql = new DbSql();
	$sql->login_proxy();
	
	$sql->connect_database_default();
	$mydb = $sql->get_database();
	$sql->create_database($mydb);
	
	$mytable = "proxy_playlist_table";
	$sql->create_table($mydb, $mytable, "proxy text, playlist text");	
	$proxy_playlist = $sql->query_data($mydb, $mytable,"proxy",$_COOKIE["user"],"playlist");
	//echo "proxy_playlist:" . $proxy_playlist;
	
	$mytable = "proxy_table";
	$sql->create_table($mydb, $mytable, "name text, password text, ptip text, edit int");
	$edit = $sql->query_data($mydb, $mytable,"name",$_COOKIE["user"],"edit");
	
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
		evernumber longtext, prekey text");
	
	$allow = $sql->query_data_2($mydb, $mytable,"mac",$_GET["mac"],"cpu",$_GET["cpuid"],"allow");
	$sdate = $sql->query_data_2($mydb, $mytable,"mac",$_GET["mac"],"cpu",$_GET["cpuid"],"date");
	$edate = $sql->query_data_2($mydb, $mytable,"mac",$_GET["mac"],"cpu",$_GET["cpuid"],"time");
	
	$allocation = $sql->query_data_2($mydb, $mytable,"mac",$_GET["mac"],"cpu",$_GET["cpuid"],"allocation");
	$playlist = $sql->query_data_2($mydb, $mytable,"mac",$_GET["mac"],"cpu",$_GET["cpuid"],"playlist");
	
	$proxy = $sql->query_data_2($mydb, $mytable,"mac",$_GET["mac"],"cpu",$_GET["cpuid"],"proxy");
	$allow = $sql->query_data_2($mydb, $mytable,"mac",$_GET["mac"],"cpu",$_GET["cpuid"],"allow");
	
	$showtime = $sql->query_data_2($mydb, $mytable,"mac",$_GET["mac"],"cpu",$_GET["cpuid"],"showtime");
	$contact = $sql->query_data_2($mydb, $mytable,"mac",$_GET["mac"],"cpu",$_GET["cpuid"],"contact");
	
	$member = $sql->query_data_2($mydb, $mytable,"mac",$_GET["mac"],"cpu",$_GET["cpuid"],"member");
	$remarks = $sql->query_data_2($mydb, $mytable,"mac",$_GET["mac"],"cpu",$_GET["cpuid"],"remarks");
	$panel = $sql->query_data_2($mydb, $mytable,"mac",$_GET["mac"],"cpu",$_GET["cpuid"],"panal");
	if($panel == null)
		$panel = 1;
		
	$lefttime = getChaBetweenTwoDate($edate,date('Y-m-d',time()));
	if($lefttime < 0)
		$lefttime = 0;
		
	$cdkey = $sql->query_data_2($mydb, $mytable,"mac",$_GET["mac"],"cpu",$_GET["cpuid"],"number");
	$unbundling = $sql->query_data_2($mydb, $mytable,"mac",$_GET["mac"],"cpu",$_GET["cpuid"],"unbundling");
	$scrollcontent = $sql->query_data_2($mydb, $mytable,"mac",$_GET["mac"],"cpu",$_GET["cpuid"],"scrollcontent");
	$limitmodel = $sql->query_data_2($mydb, $mytable,"mac",$_GET["mac"],"cpu",$_GET["cpuid"],"limitmodel");
	$model = $sql->query_data_2($mydb, $mytable,"mac",$_GET["mac"],"cpu",$_GET["cpuid"],"model");
	$prekey = $sql->query_data_2($mydb, $mytable,"mac",$_GET["mac"],"cpu",$_GET["cpuid"],"prekey");
	
	//echo "aa:" . $allow; 
?>

<?php
function getChaBetweenTwoDate($date1,$date2)
{
	
	$Date_List_a1=explode("-",$date1);
	$Date_List_a2=explode("-",$date2);
	if(count($Date_List_a1) < 3 || count($Date_List_a2) < 3)
		return 0;
	if(is_numeric($Date_List_a1[1]) && is_numeric($Date_List_a1[0]) && is_numeric($Date_List_a1[2])
		&& is_numeric($Date_List_a2[1]) && is_numeric($Date_List_a2[0]) && is_numeric($Date_List_a2[2])) 	
	{
		$d1=mktime(0,0,0,intval($Date_List_a1[1]),intval($Date_List_a1[2]),intval($Date_List_a1[0]));
		$d2=mktime(0,0,0,intval($Date_List_a2[1]),intval($Date_List_a2[2]),intval($Date_List_a2[0]));
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
    
    <body onLoad="createSelect(1)">
        <div class="container-fluid">
            <div class="row-fluid">
              	<div class="span13" id="content">
                <div class="row-fluid">
                   <!-- block -->
						<div class="block">
                          <div class="navbar navbar-inner block-header">
                                <div class="muted pull-left"><?php echo $lang_array["proxy_custom_edit_text1"] ?></div>
                            </div>
                            
                            <div class="block-content collapse in">
                                <div class="span12">
                                	<form class="form-horizontal" method="post" action="custom_get.php" enctype='multipart/form-data'>
                                    <fieldset><br/>
                                    <div class="control-group">
                                       <label class="control-label" for="focusedInput" style="text-shadow:#0CF"><?php echo $lang_array["custom_edit_text4"] ?></label>
                                       <div class="controls">
                                       
                                       <?php
                                       	  //if($edit == 1)
                                       	  {
                                          	echo "<input name=\"radio\" type=\"radio\" id=\"radio0\" value=\"no\" style=\"width:15px;height:28px;\" disabled=\"disabled\"";
											/*
											if(strcmp($allow,"no")==0) 
												echo "checked";
											echo "onclick=\"changeradio0()\"";
											if($edit == 0 || $edit == 2) 
												echo "disabled=\"disabled\"";
											*/	
                                            echo ">";
											echo $lang_array["custom_edit_text1"];
											echo "&nbsp;&nbsp;&nbsp;&nbsp;";
										  }
										?>
                                        <input name="radio" type="radio" id="radio3" value="pause" style="width:15px;height:28px;" <?php if(strcmp($allow,"pause")==0) echo "checked"; ?> onclick="changeradio3()" ><?php echo $lang_array["custom_edit_text28"] ?>&nbsp;&nbsp;&nbsp;&nbsp;
                                        
                                        <?php
                                          //if($edit == 1)
                                       	  {
                                          	echo "<input name=\"radio\" type=\"radio\" id=\"radio1\" value=\"allway\" style=\"width:15px;height:28px;\"";
											if(strcmp($allow,"yes")==0 && $edate == -1) 
												echo "checked"; 
											echo "onclick=\"changeradio2()\"";
											if($edit == 0 || $edit == 2) 
												echo "disabled=\"disabled\"";
											echo ">";
											 echo $lang_array["custom_edit_text3"];
											 echo "&nbsp;&nbsp;&nbsp;&nbsp;";
										  }
                                        ?>
                                        
                                          <input name="radio" type="radio" id="radio2" value="yes" style="width:15px;height:28px;" <?php if(strcmp($allow,"yes")==0) echo "checked"; ?> onclick="changeradio1()" <?php if($edit == 0) echo "disabled=\"disabled\""?>><?php echo $lang_array["custom_edit_text2"] ?>&nbsp;&nbsp;&nbsp;&nbsp;
                                          
                                          <!--
                                          <select id="tYEAR" size="1" onChange="createSelect()" style="width:72px;" <?php //if($edit != 1) echo "disabled=\"disabled\""?>></select><?php //echo $lang_array["custom_edit_text22"] ?>
										  <select id="tMON" size="1" onChange="createSelect()" style="width:54px;" <?php //if($edit != 1) echo "disabled=\"disabled\""?>></select><?php //echo $lang_array["custom_edit_text23"] ?>
										  <select id="tDAY" size="1" style="width:54px;" <?php //if($edit != 1) echo "disabled=\"disabled\""?>></select><?php //echo $lang_array["custom_edit_text24"] ?>
                                          -->
                                          <?php
                                          //if($edit == 1)
										  {
                                          	echo "&nbsp;&nbsp;";
											echo $lang_array["custom_edit_text19"];
											echo ":<font id=\"admin_leftday_text\">";
											echo $lefttime; 
											echo "</font>&nbsp;&nbsp;&nbsp;&nbsp;";
											echo $lang_array["custom_edit_text20"];
                                            echo ":<input name=\"\" type=\"text\" value=\"0\" size=\"5\" maxlength=\"5\" id=\"admin_allowday_text\" oninput=\"endDate()\" style=\"width:36px;\"";
											if($edit != 1) 
												echo "disabled=\"disabled\"";
											echo "/>";
											echo $lang_array["custom_edit_text25"];
											echo "&nbsp;&nbsp;&nbsp;&nbsp;";
											echo $lang_array["custom_edit_text21"];
											echo ":<font id=\"endtime_label\">";
											echo $edate;
											echo "</font>";
                                            
                                           }
                                           ?>
                                       </div>
                                   	</div>

                                    <div class="control-group">
										<label class="control-label" ><?php echo $lang_array["custom_edit_text5"] ?></label>
                                        <div class="controls">
											 <input name="allocation" type="radio" id="allocation0" value="all" style="width:15px;height:28px;" <?php if(strcmp($allocation,"all")==0 || strcmp($allocation,"ERROR")==0 || strcmp($allocation,"me")==0 || strlen($allocation) <= 0) echo "checked"; ?> <?php if($edit != 1) echo "disabled=\"disabled\""?>><?php echo $lang_array["custom_edit_text6"] ?>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                             <input name="allocation" type="radio" id="allocation1" value="auto" style="width:15px;height:28px;"<?php if(strcmp($allocation,"auto")==0) echo "checked"; ?> <?php if($edit == 0 || $edit == 2) echo "disabled=\"disabled\""?>><?php echo $lang_array["custom_edit_text7"] ?>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                             <input name="allocation" type="radio" id="allocation2" value="manually" style="width:15px;height:28px;" <?php if(strcmp($allocation,"manually")==0) echo "checked" ?> <?php if($edit == 0 || $edit == 2) echo "disabled=\"disabled\""?>><?php echo $lang_array["custom_edit_text8"] ?>  
                                             <select id="playlist_select_id" name="" <?php if($edit != 1) echo "disabled=\"disabled\""?>>
   	 										 <?php
												$mytable = "playlist_type_table";
												$sql->create_table($mydb, $mytable, "name text, space longtext, id text, playlist longtext");	
												$namess = $sql->fetch_datas($mydb, $mytable);
												foreach($namess as $names) 
												{
													if($proxy_playlist == NULL || strlen($proxy_playlist)<= 0 || strstr($proxy_playlist,$names[2]) != false)
													{
														if(strcmp($playlist,$names[2]) == 0)
														{
															echo "<option value='" . $names[2] . "' selected='selected'>" . $names[0] . "</option>";
														}
														else
														{
															echo "<option value='" . $names[2] . "'>" . $names[0] . "</option>";
														}
													}
												}
											?>
  											</select>
                                        </div>    
                                    </div>
                                    
                                    <div class="control-group">
										<label class="control-label" ><?php echo $lang_array["custom_edit_text9"] ?></label>
                                        <div class="controls">
											<input name="proxy" type="radio" id="proxy0" value="proxy1" style="width:15px;height:28px;" <?php if(strcmp($proxy,$_COOKIE["user"])==0) echo "checked" ?> <?php if($edit == 0 || $edit == 2) echo "disabled=\"disabled\""?>><?php echo $lang_array["custom_edit_text10"] ?>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                            <input name="proxy" type="radio" id="proxy1" value="proxy2" style="width:15px;height:28px;" <?php if(strcmp($proxy,$_COOKIE["user"])!=0) echo "checked" ?> <?php if($edit == 0 || $edit == 2) echo "disabled=\"disabled\""?>><?php echo $lang_array["custom_edit_text11"] ?>  
											<select id="proxy_select_id" name="" <?php if($edit == 0 || $edit == 2) echo "disabled=\"disabled\""?>>
											<?php
												$mytable = "proxy_table";
												$sql->create_table($mydb, $mytable, "name text, password text, ptip text, edit int, watermark text, validity date, allow int, remark text, ccount int, panal int, pwmd5 int, logonum text, terlang int, proxylevel int, proxybelong text");	
	
												$namess = $sql->fetch_datas_where($mydb, $mytable, "proxybelong", $_COOKIE["user"]);
												foreach($namess as $names) {
													if(strcmp($proxy,$names[0]) == 0)
													{
														echo "<option value='" . $names[0] . "' selected='selected'>" . $names[0] . "</option>";
													}
													else
													{
														echo "<option value='" . $names[0] . "'>" . $names[0] . "</option>";
													}
												}
											?>
											</select>
                                        </div>    
                                    </div>                                   

                                    <div class="control-group">
										<label class="control-label" ><?php echo $lang_array["custom_edit_text12"] ?></label>
                                        <div class="controls">
											<input name="show" type="radio" id="show0" value="yes" style="width:15px;height:28px;" <?php if(strcmp($showtime,"no")!=0) echo "checked"; ?>><?php echo $lang_array["yes_text1"] ?>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                            <input name="show" type="radio" id="show1" value="no" style="width:15px;height:28px;" <?php if(strcmp($showtime,"no")==0) echo "checked"; ?>><?php echo $lang_array["no_text1"] ?>  
                                        </div>    
                                    </div>
                                    
									<div class="control-group">
										<label class="control-label" ><?php echo $lang_array["custom_edit_text13"] ?></label>
                                        <div class="controls">
											<input class="input-xxlarge focused" id="contact" name="" type="text" value="<?php echo $contact;?>">
                                        </div>    
                                    </div>
                                    
                                    <div class="control-group">
										<label class="control-label" ><?php echo $lang_array["custom_edit_text14"] ?></label>
                                        <div class="controls">
											<input class="input-medium focused" id="member" name="" type="text" value="<?php echo $member;?>">
                                        </div>    
                                    </div>   
                                    
                                    <div class="control-group">
										<label class="control-label" ><?php echo $lang_array["custom_edit_text26"] ?></label>
                                        <div class="controls">
											<input class="input-xxlarge focused" id="remarks" name="" type="text" value="<?php echo $remarks;?>">
                                        </div>    
                                    </div>     
                                         
                                    <div class="control-group">
										<label class="control-label" ><?php echo $lang_array["proxy_custom_edit_text2"] ?></label>
                                        <div class="controls">         
                                            <label class="control-label" for="focusedInput" style="text-align:left;width:220px;" ><?php if(strlen($cdkey) > 4) echo $cdkey; else echo $lang_array["proxy_custom_edit_text5"]; ?>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<?php if($unbundling != null && $unbundling == 0) echo $lang_array["proxy_custom_edit_text3"]; else echo $lang_array["proxy_custom_edit_text4"]; ?></label>
    										<input class="btn btn-primary"  name="" type="button" value='<?php if($unbundling != null && $unbundling == 0) echo $lang_array["proxy_custom_edit_text4"]; else echo $lang_array["proxy_custom_edit_text3"]; ?>' onclick="unbundling_number(<?php if($unbundling != null && $unbundling == 0) echo 1; else echo 0; ?>)"/>
    									</div>    
                                    </div> 
                                    
                                    <div class="control-group">
                                       	<label class="control-label" for="focusedInput" style="text-shadow:#0CF"><?php echo $lang_array["authenticate_list_text35"] ?></label>
                                       	<div class="controls" style="vertical-align:middle;">
                                        	<input style="width:15px;height:28px;" type="radio" name="limitmodel_radio" value="0" id="limitmodel0_radio" <?php if($limitmodel == null || strlen($limitmodel)==0) echo "checked"?>  onChange="limitmodel_change(0)"/><?php echo $lang_array["no_text1"] ?>&nbsp;
                                       		<input style="width:15px;height:28px;" type="radio" name="limitmodel_radio" value="1" id="limitmodel1_radio" <?php if($limitmodel != null || strlen($limitmodel)>0) echo "checked"?> onChange="limitmodel_change(1)"/><?php echo $lang_array["yes_text1"] ?>&nbsp;
                                            <input class="input-large focused" id="limitmodel_id" name="limitmodel_id" type="text" value="<?php if($limitmodel != null || strlen($limitmodel)>0) echo $limitmodel; else echo $model;?>">
                                            <button type='button' class='btn btn-primary' onclick='limitmodel()'> . <?php echo $lang_array['sure_text1'] ?>. </button>
                                            
                                       	</div>
                                   	</div>
                                    
    								<div class="control-group">
										<label class="control-label" ><?php echo $lang_array["custom_edit_text27"] ?></label>
                                        <div class="controls">
											<input class="input-xxlarge focused" id="scrollcontent_text" name="" type="text" value="<?php echo $scrollcontent;?>">
                                        </div>    
                                    </div>
                                    
                                    <div class="control-group">
                                       	<label class="control-label" for="focusedInput" style="text-shadow:#0CF"><?php echo $lang_array["authenticate_list_text45"] ?></label>
                                       	<div class="controls" style="vertical-align:middle;font-size:15px;">
                                        	<input class="input-large focused" id="prekey" name="prekey" type="text" value="<?php if(strlen($prekey) > 0)echo "**************"; ?>">
                                            &nbsp;&nbsp;&nbsp
                                            <input class="btn btn-primary"  name="" type="button" onclick="clear_prekey()" value='<?php  echo $lang_array["authenticate_list_text46"] ?>'/>
                                            <input class="btn btn-primary"  name="" type="button" onclick="save_prekey()" value='<?php  echo $lang_array["authenticate_list_text48"] ?>'/>
                                            <?php echo $lang_array["authenticate_list_text47"] ?>
                                       	</div>
                                   	</div>
                                    
                                    <div class="control-group">
                                       <label class="control-label" for="focusedInput"><?php echo $lang_array["custom_edit_text15"] ?></label>
                                       <div class="controls">
                                          <input name="panel" type="radio" id="panel0" value="0" style="width:15px;height:28px;" <?php if(strcmp($panel,"0")==0 || strcmp($panel,"null") == 0 || strlen($panel) == 0) echo "checked"; ?>><?php echo $lang_array["custom_edit_text16"] ?>&nbsp;&nbsp;&nbsp;&nbsp;
                                          <input name="panel" type="radio" id="panel2" value="2" style="width:15px;height:28px;" <?php if(strcmp($panel,"2")==0) echo "checked"; ?>><?php echo $lang_array["custom_edit_text17"] ?>&nbsp;&nbsp;&nbsp;&nbsp;
                                          <input name="panel" type="radio" id="panel3" value="3" style="width:15px;height:28px;" <?php if(strcmp($panel,"3")==0) echo "checked"; ?>><?php echo $lang_array["custom_edit_text30"] ?>&nbsp;&nbsp;&nbsp;&nbsp;
                                          <input name="panel" type="radio" id="panel1" value="1" style="width:15px;height:28px;" <?php if(strcmp($panel,"1")==0) echo "checked"; ?>><?php echo $lang_array["custom_edit_text18"] ?>&nbsp;&nbsp;&nbsp;&nbsp;
                                       </div>
                                   	</div>
                                                                                                                                     
                                    <div class="form-actions">
   										<button type="button" class="btn btn-primary" onclick="save()"><?php echo $lang_array["save_text1"] ?></button>
                                		<button type="reset" class="btn" onclick="back_page()"><?php echo $lang_array["back_text1"] ?></button>
                            		</div>
                                   	</fieldset>
									</form>
                                </div>
                            </div>
                         </div>
                    </div>
				</div>
            </div>
        </div>
        
        <FORM name="authform_edit" action="" method="post">  
    	<input name="scrollcontent" id="scrollcontent" type="hidden" value=""/>
    	</Form>
        
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
	
		Date.prototype.pattern=function(fmt) {         
    	var o = {         
    	"M+" : this.getMonth()+1, //月份         
    	"d+" : this.getDate(), //日         
    	"h+" : this.getHours()%12 == 0 ? 12 : this.getHours()%12, //小时         
    	"H+" : this.getHours(), //小时         
    	"m+" : this.getMinutes(), //分         
    	"s+" : this.getSeconds(), //秒         
    	"q+" : Math.floor((this.getMonth()+3)/3), //季度         
    	"S" : this.getMilliseconds() //毫秒         
    	};   
		      
    	var week = {         
    		"0" : "/u65e5",         
    		"1" : "/u4e00",         
    		"2" : "/u4e8c",         
    		"3" : "/u4e09",         
    		"4" : "/u56db",         
    		"5" : "/u4e94",         
    		"6" : "/u516d"        
    	};         
    	
		if(/(y+)/.test(fmt)){         
       	 	fmt=fmt.replace(RegExp.$1, (this.getFullYear()+"").substr(4 - RegExp.$1.length));         
    	}        
		 
    	if(/(E+)/.test(fmt)){         
        	fmt=fmt.replace(RegExp.$1, ((RegExp.$1.length>1) ? (RegExp.$1.length>2 ? "/u661f/u671f" : "/u5468") : "")+week[this.getDay()+""]);         
    	}     
		    
   	 	for(var k in o){         
        	if(new RegExp("("+ k +")").test(fmt)){         
            	fmt = fmt.replace(RegExp.$1, (RegExp.$1.length==1) ? (o[k]) : (("00"+ o[k]).substr((""+ o[k]).length)));         
        	}         
    	}         
    	return fmt;         
		} 
	
		var base64EncodeChars = "ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789+/";  
  
		function base64encode(str) {  
    		var out, i, len;  
    		var c1, c2, c3;  
  
    		len = str.length;  
    		i = 0;  
    		out = "";  
    		while(i < len) 
			{  
    		c1 = str.charCodeAt(i++) & 0xff;  
    		if(i == len)  
    		{  
        		out += base64EncodeChars.charAt(c1 >> 2);  
        		out += base64EncodeChars.charAt((c1 & 0x3) << 4);  
        		out += "==";  
        		break;  
    		}  
   		 	c2 = str.charCodeAt(i++);  
    		if(i == len)  
    		{  
        		out += base64EncodeChars.charAt(c1 >> 2);  
        		out += base64EncodeChars.charAt(((c1 & 0x3)<< 4) | ((c2 & 0xF0) >> 4));  
        		out += base64EncodeChars.charAt((c2 & 0xF) << 2);  
        		out += "=";  
        		break;  
    		}  
    		c3 = str.charCodeAt(i++);  
    		out += base64EncodeChars.charAt(c1 >> 2);  
    		out += base64EncodeChars.charAt(((c1 & 0x3)<< 4) | ((c2 & 0xF0) >> 4));  
    		out += base64EncodeChars.charAt(((c2 & 0xF) << 2) | ((c3 & 0xC0) >>6));  
    		out += base64EncodeChars.charAt(c3 & 0x3F);  
    		}  
   		 	return out;  
		}
		
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
		
		function create2Select(year, month, day) 
		{ 
			//var selYear = document.getElementById("tYEAR"); 
			//var selMonth = document.getElementById("tMON"); 
			//var selDay = document.getElementById("tDAY"); 
			
			var selYear = "<?php echo date("Y") ?>";
			var selMonth = "<?php echo date("m") ?>";
			var selDay = "<?php echo date("d") ?>";
			
			var dt = new Date(year,month-1,day); 

			//if(ActionFlag == 1) 
			{ 
				MinYear = dt.getFullYear(); 
				MaxYear = dt.getFullYear()+10; 

				for(var i = MinYear; i <= MaxYear; i++) 
				{ 
					var op = document.createElement("OPTION"); 
					op.value = i; 
					op.innerHTML = i; 
					selYear.appendChild(op); 
				} 
				selYear.selectedIndex = 0; 

				for(var i = 1; i < 13; i++) 
				{ 
					var op = document.createElement("OPTION"); 
					op.value = i; 
					op.innerHTML = i; 
					selMonth.appendChild(op); 
				} 
				selMonth.selectedIndex = dt.getMonth(); 
			} 

			var date = new Date(selYear.value, selMonth.value, 0); 
			var daysInMonth = date.getDate(); 
			selDay.options.length = 0; 

			for(var i = 1; i <= daysInMonth ; i++) 
			{ 
				var op = document.createElement("OPTION"); 
				op.value = i; 
				op.innerHTML = i; 
				selDay.appendChild(op); 
			} 
			selDay.selectedIndex = dt.getDate() - 1; 
		} 

		function endSelect(ActionFlag) 
		{ 
			var selYear = document.getElementById("eYEAR"); 
			var selMonth = document.getElementById("eMON"); 
			var selDay = document.getElementById("eDAY"); 
			var dt = new Date(); 

			if(ActionFlag == 1) { 
				MinYear = dt.getFullYear(); 
				MaxYear = dt.getFullYear()+10; 

				for(var i = MinYear; i <= MaxYear; i++) 
				{ 
					var op = document.createElement("OPTION"); 
					op.value = i; 
					op.innerHTML = i; 
					selYear.appendChild(op); 
				} 
				selYear.selectedIndex = 1; 

				for(var i = 1; i < 13; i++) 
				{ 
					var op = document.createElement("OPTION"); 
					op.value = i; 
					op.innerHTML = i; 
					selMonth.appendChild(op); 
				} 
				selMonth.selectedIndex = dt.getMonth(); 
			} 

			var date = new Date(selYear.value, selMonth.value, 0); 
			var daysInMonth = date.getDate(); 
			selDay.options.length = 0; 

			for(var i = 1; i <= daysInMonth ; i++) 
			{ 
				var op = document.createElement("OPTION"); 
				op.value = i; 
				op.innerHTML = i; 
				selDay.appendChild(op); 
			} 
			selDay.selectedIndex = dt.getDate(); 
		}
		
		function createSelect(ActionFlag) 
		{ 
			//var selYear = document.getElementById("tYEAR"); 
			//var selMonth = document.getElementById("tMON"); 
			//var selDay = document.getElementById("tDAY"); 
			
			var selYear = "<?php echo date("Y") ?>";
			var selMonth = "<?php echo date("m") ?>";
			var selDay = "<?php echo date("d") ?>";
			
			var dt = new Date(); 

			if(ActionFlag == 1) { 
				MinYear = dt.getFullYear(); 
				MaxYear = dt.getFullYear()+10; 

				for(var i = MinYear; i <= MaxYear; i++) 
				{ 
					var op = document.createElement("OPTION"); 
					op.value = i; 
					op.innerHTML = i; 
					selYear.appendChild(op); 
				} 
				selYear.selectedIndex = 0; 

				for(var i = 1; i < 13; i++) 
				{ 
					var op = document.createElement("OPTION"); 
					op.value = i; 
					op.innerHTML = i; 
					selMonth.appendChild(op); 
				} 
				selMonth.selectedIndex = dt.getMonth(); 
			} 

			var date = new Date(selYear.value, selMonth.value, 0); 
			var daysInMonth = date.getDate(); 
			selDay.options.length = 0; 


			for(var i = 1; i <= daysInMonth ; i++) 
			{ 
				var op = document.createElement("OPTION"); 
				op.value = i; 
				op.innerHTML = i; 
				selDay.appendChild(op); 
			} 
			selDay.selectedIndex = dt.getDate() - 1; 
		} 
		
		function endDate()
		{
			
			//var sy = document.getElementById("tYEAR").value;
			//var sm = document.getElementById("tMON").value;
			//var sd = document.getElementById("tDAY").value;
			var sy = "<?php echo date("Y") ?>";
			var sm = "<?php echo date("m") ?>";
			var sd = "<?php echo date("d") ?>";
			
			var ed = document.getElementById("admin_allowday_text").value;
			var _date = new Date(parseInt(sy), parseInt(sm)-1, parseInt(sd));
			var d = _date.setDate(_date.getDate() + (parseInt(<?php echo $lefttime ?>) + parseInt(ed)));
			var end_day = new Date(d).pattern("yyyy-MM-dd");	
			document.getElementById("endtime_label").innerHTML = end_day;
		}
		
		function changeradio3()
		{
			
		}
		
		function changeradio2()
		{
			document.getElementById("admin_leftday_text").innerHTML = -1;
			document.getElementById("admin_allowday_text").value = -1;
			document.getElementById("endtime_label").innerHTML = -1;
			document.getElementById("allowday_text").value = -1;
		}

		function changeradio0()
		{
			document.getElementById("admin_leftday_text").innerHTML = 0;
			document.getElementById("admin_allowday_text").value = 0;
			document.getElementById("endtime_label").innerHTML = 0;
		}

		function changeradio1()
		{
			document.getElementById("admin_leftday_text").innerHTML = <?php echo $lefttime ?>;
			document.getElementById("admin_allowday_text").value = 0;
			//document.getElementById("allowday_text").value = 366;
			endDate();
		}
		
		function limitmodel()
		{
			var value = document.getElementById("limitmodel_id").value;
			window.location.href = "proxy_limitmodel_post.php?mac=<?php echo $_GET["mac"]?>&cpuid=<?php echo $_GET["cpuid"]?>&value=" + base64encode(value);
		}
		
		function limitmodel_change(value)
		{
			if(value == 0)
				document.getElementById("limitmodel_id").value = "";
			else if(value == 1)
			{
				document.getElementById("limitmodel_id").value = "<?php if($limitmodel != null || strlen($limitmodel)>0) echo $limitmodel; else echo $model;?>";
			}
		}
		
		function save()
		{
			var radio_value = GetRadioValue("radio");
			var panel = GetRadioValue("panel");

			if(radio_value == "yes")
			{
				var ed = document.getElementById("admin_allowday_text").value;
				if(parseInt(ed) < 0)
				{
					alert("增加授权时长必需大于0！");
					return;
				}

				var start_day;
				var end_day;

				<?php
				$d = date('Y-m-d',time());
				$ds=explode("-",$d);
				echo "var sy = " . $ds[0] . ";";
				echo "var sm = " . $ds[1] . ";";
				echo "var sd = " . $ds[2] . ";";
				?>

				var ld = document.getElementById("admin_leftday_text").value;
				var ed = document.getElementById("admin_allowday_text").value;
				var _date = new Date(parseInt(sy), parseInt(sm)-1, parseInt(sd));
				var d = _date.setDate(_date.getDate() + (parseInt(<?php echo $lefttime ?>) + parseInt(ed)));
			
				start_day = sy + "-" + sm + "-" + sd;
				end_day = new Date(d).pattern("yyyy-MM-dd");

				var playlist;
				var allocation_value = GetRadioValue("allocation");

				if(allocation_value == "manually")
				{
					playlist = document.getElementById("playlist_select_id").value;
					if(playlist == null || playlist.length <= 0)
					{
						alert("无有效列表，请添加播放列表！");
						return;
					}
				}
				else if(allocation_value == "auto")
				{
					playlist = "auto";
				}
				else if(allocation_value == "all")
				{
					playlist = "all";
				}
				

				var proxy;
				proxy = document.getElementById("proxy_select_id").value;
		
				
				var show_value = GetRadioValue("show");

				var contact = document.getElementById("contact").value;
				var member = document.getElementById("member").value;
				var remarks = document.getElementById("remarks").value;
				
				var scrollcontent = document.getElementById("scrollcontent_text").value;
				
				var cmd = "proxy_custom_get.php?mac=<?php echo $_GET["mac"] ?>&cpuid=<?php echo $_GET["cpuid"] ?>&date=" + start_day + "&time=" + end_day + "&allow=" + radio_value + "&playlist=" + playlist + "&allocation=" + allocation_value + "&proxy=" + proxy + "&show=" + show_value + "&contact=" + contact + "&panel=" + panel + "&member=" + member + "&remarks=" + remarks;

				document.authform_edit.action = cmd;
				document.authform_edit.scrollcontent.value = scrollcontent;
				document.authform_edit.submit();
			}
			else if(radio_value == "no")
			{
				<?php
				$d = date('Y-m-d',time());
				$ds=explode("-",$d);
				echo "var sy = " . $ds[0] . ";";
				echo "var sm = " . $ds[1] . ";";
				echo "var sd = " . $ds[2] . ";";
				?>

				var ld = document.getElementById("admin_leftday_text").value;
				var ed = document.getElementById("admin_allowday_text").value;
				
				var _date = new Date(parseInt(sy), parseInt(sm)-1, parseInt(sd));
				var d = _date.setDate(_date.getDate() + (parseInt(<?php echo $lefttime ?>) + parseInt(ed)));
			
				start_day = sy + "-" + sm + "-" + sd;
				end_day = new Date(d).pattern("yyyy-MM-dd");
				
				var playlist;
				var allocation_value = GetRadioValue("allocation");
				if(allocation_value == "manually")
				{
					playlist = document.getElementById("playlist_select_id").value;
			
				}
				else if(allocation_value == "auto")
				{
					playlist = "auto";
				}
				else if(allocation_value == "all")
				{
					playlist = "all";
				}
		
				var proxy;
				proxy = "<?php echo $_COOKIE["user"]; ?>";
		
				var show;
				var show_value = GetRadioValue("show");

				var contact = document.getElementById("contact").value;
				var member = document.getElementById("member").value;
				var remarks = document.getElementById("remarks").value;
				var scrollcontent = document.getElementById("scrollcontent_text").value;
				
				//window.open("proxy_custom_get.php?mac=<?php echo $_GET["mac"] ?>&cpuid=<?php echo $_GET["cpuid"] ?>&date=" + start_day + "&time=" + end_day + "&allow=" + radio_value + "&playlist=" + playlist + "&allocation=" + allocation_value + "&proxy=" + proxy + "&show=" + show_value + "&contact=" + contact + "&panel=" + panel + "&member=" + member + "&remarks=" + remarks, "_self");		
				var cmd = "proxy_custom_get.php?mac=<?php echo $_GET["mac"] ?>&cpuid=<?php echo $_GET["cpuid"] ?>&date=" + start_day + "&time=" + end_day + "&allow=" + radio_value + "&playlist=" + playlist + "&allocation=" + allocation_value + "&proxy=" + proxy + "&show=" + show_value + "&contact=" + contact + "&panel=" + panel + "&member=" + member + "&remarks=" + remarks;
				
				document.authform_edit.action = cmd;
				document.authform_edit.scrollcontent.value = scrollcontent;
				document.authform_edit.submit();

			}
			else if(radio_value == "pause")
			{
				var ed = document.getElementById("admin_allowday_text").value;
				if(parseInt(ed) < 0)
				{
					alert("增加授权时长必需大于0！");
					return;
				}

				var start_day;
				var end_day;

				<?php
				$d = date('Y-m-d',time());
				$ds=explode("-",$d);
				echo "var sy = " . $ds[0] . ";";
				echo "var sm = " . $ds[1] . ";";
				echo "var sd = " . $ds[2] . ";";
				?>

				var ld = document.getElementById("admin_leftday_text").value;
				var ed = document.getElementById("admin_allowday_text").value;
				var _date = new Date(parseInt(sy), parseInt(sm)-1, parseInt(sd));
				var d = _date.setDate(_date.getDate() + (parseInt(<?php echo $lefttime ?>) + parseInt(ed)));
			
				start_day = sy + "-" + sm + "-" + sd;
				end_day = new Date(d).pattern("yyyy-MM-dd");

				var playlist;
				var allocation_value = GetRadioValue("allocation");

				if(allocation_value == "manually")
				{
					playlist = document.getElementById("playlist_select_id").value;
					if(playlist == null || playlist.length <= 0)
					{
						alert("无有效列表，请添加播放列表！");
						return;
					}
				}
				else if(allocation_value == "auto")
				{
					playlist = "auto";
				}
				else if(allocation_value == "all")
				{
					playlist = "all";
				}
				

				var proxy;
				proxy = "<?php echo $_COOKIE["user"]; ?>";
		
				
				var show_value = GetRadioValue("show");

				var contact = document.getElementById("contact").value;
				var member = document.getElementById("member").value;
				var remarks = document.getElementById("remarks").value;
				var scrollcontent = document.getElementById("scrollcontent_text").value;
				
				var cmd = "proxy_custom_get.php?mac=<?php echo $_GET["mac"] ?>&cpuid=<?php echo $_GET["cpuid"] ?>&date=" + start_day + "&time=" + end_day + "&allow=" + radio_value + "&playlist=" + playlist + "&allocation=" + allocation_value + "&proxy=" + proxy + "&show=" + show_value + "&contact=" + contact + "&panel=" + panel + "&member=" + member + "&remarks=" + remarks;
				//window.open(cmd, "_self");

				document.authform_edit.action = cmd;
				document.authform_edit.scrollcontent.value = scrollcontent;
				document.authform_edit.submit();
			}
			else
			{
				radio_value = "yes";
				<?php
				$d = date('Y-m-d',time());
				$ds=explode("-",$d);
				echo "var sy = " . $ds[0] . ";";
				echo "var sm = " . $ds[1] . ";";
				echo "var sd = " . $ds[2] . ";";
				?>

				var ld = document.getElementById("admin_leftday_text").value;
				var ed = document.getElementById("admin_allowday_text").value;
				var _date = new Date(parseInt(sy), parseInt(sm)-1, parseInt(sd));
				var d = _date.setDate(_date.getDate() + (parseInt(<?php echo $lefttime ?>) + parseInt(ed)));
			
				start_day = sy + "-" + sm + "-" + sd;
				end_day = -1;

				var playlist;
				var allocation_value = GetRadioValue("allocation");
				if(allocation_value == "manually")
				{
					playlist = document.getElementById("playlist_select_id").value;
				}
				else if(allocation_value == "auto")
				{
					playlist = "auto";
				}
				else if(allocation_value == "all")
				{
					playlist = "all";
				}
		
				var proxy;
				var proxy_value = GetRadioValue("proxy");
				proxy = "<?php echo $_COOKIE["user"]; ?>";

				//alert(proxy);
				var show;
				var show_value = GetRadioValue("show");

				var contact = document.getElementById("contact").value;
				var member = document.getElementById("member").value;
				var remarks = document.getElementById("remarks").value;
				var scrollcontent = document.getElementById("scrollcontent_text").value;
				
				var cmd = "proxy_custom_get.php?mac=<?php echo $_GET["mac"] ?>&cpuid=<?php echo $_GET["cpuid"] ?>&date=" + start_day + "&time=" + end_day + "&allow=" + radio_value + "&playlist=" + playlist + "&allocation=" + allocation_value + "&proxy=" + proxy + "&show=" + show_value + "&contact=" + contact + "&panel=" + panel + "&member=" + member + "&remarks=" + remarks;
				
				document.authform_edit.action = cmd;
				document.authform_edit.scrollcontent.value = scrollcontent;
				document.authform_edit.submit();
				
				//window.open("proxy_custom_get.php?mac=<?php echo $_GET["mac"] ?>&cpuid=<?php echo $_GET["cpuid"] ?>&date=" + start_day + "&time=" + end_day + "&allow=" + radio_value + "&playlist=" + playlist + "&allocation=" + allocation_value + "&proxy=" + proxy + "&show=" + show_value + "&contact=" + contact + "&panel=" + panel + "&member=" + member + "&remarks=" + remarks, "_self");	
		//window.open("proxy_custom_get.php?mac=<?php //echo $_GET["mac"] ?>&cpuid=<?php //echo $_GET["cpuid"]?>&contact=" + contact + "&panel=" + panel + "&allow=" + radio_value, "_self");
				
			}
		}

		
		function GetRadioValue(RadioName){
    		var obj;
   			obj=document.getElementsByName(RadioName);
    		if(obj!=null){
        		var i;
        		for(i=0;i<obj.length;i++){
            		if(obj[i].checked){
                		return obj[i].value;
            		}
        		}
    		}
    		return null;
		}
		
		
		function unbundling_number(value)
		{
			window.location.href = "proxy_unbundling_post.php?mac=<?php echo $_GET["mac"]?>&cpuid=<?php echo $_GET["cpuid"]?>&value="+value;
	
		}

		function clear_prekey()
		{
			window.location.href = "proxy_clear_prekey.php?mac=<?php echo $_GET["mac"]?>&cpuid=<?php echo $_GET["cpuid"]?>";
		}
		
		function save_prekey()
		{
			var value = document.getElementById("prekey").value;
			window.location.href = "proxy_save_prekey.php?mac=<?php echo $_GET["mac"]?>&cpuid=<?php echo $_GET["cpuid"]?>&prekey=" + value;
		}
		
		function back_page()
		{
			window.location.href = "proxy_custom_list.php";
				
		}
        </script>
    </body>
</html>

<?php
	$sql->disconnect_database();
?>