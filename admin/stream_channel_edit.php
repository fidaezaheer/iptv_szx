<!DOCTYPE html>
<?php
	include_once "cn_lang.php";
	include_once "common.php";
	$sql = new DbSql();
	$sql->login();

	$urlid = $_GET["urlid"];
	$serverip = trim($_GET["serverip"]);

	$sql->connect_database_default();
	$mydb = $sql->get_database();
	$sql->create_database($mydb);

	$mytable = "stream_server_list_table";
	$sql->create_table($mydb, $mytable, "serverid int, serverip text, tip text, os text, version int");
	$serverip_namess = $sql->fetch_datas($mydb, $mytable);


	$cmd = "getplayxml";
	$ret = send($serverip,$cmd);
	//echo "ret:" . $ret . " serverip:" . $serverip;
	
	$curlid = "";
	$name = "";
	$url = "";
	$dir = "";
	$path = "";
	$cache = "";
	$disable = "";
	$tabel = "";
	$equilibrium_array = array();
	
	if(strstr($ret,"?xml") != false && strstr($ret,"version=") != false)
	{
		$doc = new DOMDocument();
		$doc->loadXML($ret);
		$channels = $doc->getElementsByTagName("channel");
		foreach($channels as $channel){
			$curlid = $channel->getElementsByTagName("id")->item(0)->nodeValue;
			if($curlid == $urlid)
			{
				$name = $channel->getElementsByTagName("name")->item(0)->nodeValue;
				$url = $channel->getElementsByTagName("url")->item(0)->nodeValue;
				$dir = $channel->getElementsByTagName("dir")->item(0)->nodeValue;
				$cache = $channel->getElementsByTagName("time")->item(0)->nodeValue;
				$disable = $channel->getElementsByTagName("disable")->item(0)->nodeValue;
				$tabel = $channel->getElementsByTagName("tabel")->item(0)->nodeValue;
				$equilibrium = base64_decode($channel->getElementsByTagName("equilibrium")->item(0)->nodeValue);
				$acc = $channel->getElementsByTagName("acc")->item(0)->nodeValue;
				
				if(strlen($equilibrium) >= 7)
					$equilibrium_array = explode("|",$equilibrium);
				
				break;
			}
		}
	}
	
	if(strstr($dir,"/home/gemini/gserver0/") != false)
	{
		$path = "0";
	}
	else if(strstr($dir,"/home/gemini/gserver1/") != false)
	{
		$path = "1";
	}
	else if(strstr($dir,"/home/gemini/gserver2/") != false)
	{
		$path = "2";
	}
	else if(strstr($dir,"/home/gemini/gserver3/") != false)
	{
		$path = "3";
	}
	else if(strstr($dir,"/home/gemini/gserver4/") != false)
	{
		$path = "4";
	}
	else if(strstr($dir,"/tmp/gemini/gserver/") != false)
	{
		$path = "5";
	}	
	
	/*
	$mytable = "stream_channel_list_table";
	$sql->create_table($mydb, $mytable, "urlid int, name text, url text, online int, cache int, 
										path text, tabel text, serverip text, url1 text, url2 text, 
										url3 text, tip text");
	$channel_list_namess = $sql->fetch_datas_order_asc($mydb, $mytable, "urlid");
	$name = $sql->query_data_2($mydb, $mytable, "urlid", $urlid, "serverip", $serverip, "name");
	$url = $sql->query_data_2($mydb, $mytable, "urlid", $urlid, "serverip", $serverip, "url");
	$cache = $sql->query_data_2($mydb, $mytable, "urlid", $urlid, "serverip", $serverip, "cache");
	$path = $sql->query_data_2($mydb, $mytable, "urlid", $urlid, "serverip", $serverip, "path");
	//$serverip = $sql->query_data($mydb, $mytable, "urlid", $urlid, "serverip");
	$tip = $sql->query_data_2($mydb, $mytable, "urlid", $urlid, "serverip", $serverip, "tip");
	*/
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
                                <div class="muted pull-left"><?php echo $lang_array["stream_channel_add_text1"] ?></div>
                            </div>
                            
                            <div class="block-content collapse in">
                                <div class="span12">
                                	<form class="form-horizontal"  name="authform"  method="post" action="stream_channel_post.php" enctype='multipart/form-data'>
                                    	<fieldset><br/>
                                   	<div class="control-group">
                                       <label class="control-label" for="focusedInput"><?php echo $lang_array["stream_channel_list_text2"] ?></label>
                                       <div class="controls">
                                          <input class="input-mini focused" id="urlid" name="urlid" type="text" value="<?php echo $curlid; ?>" readonly>
                                       </div>
                                   	</div>
                                    
                                    <?php
										$tables = urldecode($tabel);
										$tabless = explode("@#@",$tables);
									?>
                                    <div class="control-group">
                                       <label class="control-label" for="focusedInput"><?php echo $lang_array["stream_channel_list_text3"] ?></label>
                                       <div class="controls">
                                          <input class="input-xlarge focused" id="tip" name="tip" type="text" value="<?php echo $tabless[0]; ?>">
                                       </div>
                                   	</div>
                                    
                                    <div class="control-group">
                                       <label class="control-label" for="focusedInput"><?php echo $lang_array["stream_channel_list_text4"] ?></label>
                                       <div class="controls">
                                          <input class="input-xlarge focused" id="name" name="name" type="text" value="<?php echo $name; ?>"><label id="tipName" style="color:#F00"></label>
                                       </div>
                                   	</div>
                                    
                                    <div class="control-group">
                                       <label class="control-label" for="focusedInput"><?php echo $lang_array["stream_channel_list_text7"] ?></label>
                                       <div class="controls">
                                            <select id="serverip" name="serverip"  style='width:180px;' onchange="">
											<?php
											foreach($serverip_namess as $names) 
											{
												$selected = "";
												if($serverip == trim($names[1]))
													$selected = "selected";
													
												if(strlen($names[2]) > 0)
													echo "<option value='" . $names[1] . "' style='width:240px;' " . $selected . ">" . $names[1] . "(" . $names[2] . ")" . "</option>";
												else
													echo "<option value='" . $names[1] . "' style='width:240px;' " . $selected . ">" . $names[1] . "</option>";
												
											}
											?>
    										</select>
                                       </div>
                                   	</div>
                                    
                                    <div class="control-group">
                                       <label class="control-label" for="focusedInput"><?php echo $lang_array["stream_channel_list_text13"] ?></label>
                                       <div class="controls">
                                          <input class="input-xxlarge focused" id="url" name="url" type="text" value="<?php echo $url; ?>">
                                          &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                          <input class="btn btn-success" name="" type="button" value="<?php echo $lang_array["stream_channel_add_text21"] ?>" onclick="url_test()"/>
                                          <label id="tipUrl" style="color:#F00"></label>
                                       </div>
                                   	</div>                                   
                                    
                                    <div class="control-group">
                                       <label class="control-label" for="focusedInput"><?php echo $lang_array["stream_channel_list_text5"] ?></label>
                                       <div class="controls">
                                          <input type='radio' name='pcache' id="pcache_id_0" value='0' <?php if($path == "0") echo "checked='checked'"; ?> style='width:15px;height:28px;'/>
                                          <?php echo $lang_array["stream_channel_add_text5"] ?>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                          <input type='radio' name='pcache' id="pcache_id_1" value='1' <?php if($path == "1") echo "checked='checked'"; ?> style='width:15px;height:28px;'/>
                                          <?php echo $lang_array["stream_channel_add_text6"] ?>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                          <input type='radio' name='pcache' id="pcache_id_2" value='2' <?php if($path == "2") echo "checked='checked'"; ?> style='width:15px;height:28px;'/>
                                          <?php echo $lang_array["stream_channel_add_text7"] ?>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                          <input type='radio' name='pcache' id="pcache_id_3" value='3' <?php if($path == "3") echo "checked='checked'"; ?> style='width:15px;height:28px;'/>
                                          <?php echo $lang_array["stream_channel_add_text8"] ?>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                          <input type='radio' name='pcache' id="pcache_id_4" value='4' <?php if($path == "4") echo "checked='checked'"; ?> style='width:15px;height:28px;'/>
                                          <?php echo $lang_array["stream_channel_add_text9"] ?>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                          <input type='radio' name='pcache' id="pcache_id_5" value='5' <?php if($path == "5") echo "checked='checked'"; ?> style='width:15px;height:28px;'/>
                                          <?php echo $lang_array["stream_channel_add_text10"] ?>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                          
                                       </div>
                                   	</div>
                                    
                                    
                                    <div class="control-group">
                                       <label class="control-label" for="focusedInput"><?php echo $lang_array["stream_channel_list_text6"] ?></label>
                                       <div class="controls">
                                          <input class="input-mini focused" id="time" name="time" type="text" value="<?php echo $cache; ?>">&nbsp;&nbsp;&nbsp;<?php echo $lang_array["stream_channel_add_text18"] ?>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                          <input type='radio' name='rtime' value='250' checked='checked' onclick="rtime_change(250)" style='width:15px;height:28px;'/>
                                          <?php echo $lang_array["stream_channel_add_text19"] ?>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                          <input type='radio' name='rtime' value='86400' onclick="rtime_change(86400)" style='width:15px;height:28px;'/>
                                          <?php echo $lang_array["stream_channel_add_text11"] ?>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                          <input type='radio' name='rtime' value='172800' onclick="rtime_change(172800)" style='width:15px;height:28px;'/>
                                          <?php echo $lang_array["stream_channel_add_text12"] ?>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                          <input type='radio' name='rtime' value='259200' onclick="rtime_change(259200)" style='width:15px;height:28px;'/>
                                          <?php echo $lang_array["stream_channel_add_text13"] ?>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                          <input type='radio' name='rtime' value='345600' onclick="rtime_change(345600)" style='width:15px;height:28px;'/>
                                          <?php echo $lang_array["stream_channel_add_text14"] ?>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                          <input type='radio' name='rtime' value='432000' onclick="rtime_change(432000)" style='width:15px;height:28px;'/>
                                          <?php echo $lang_array["stream_channel_add_text15"] ?>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                          <input type='radio' name='rtime' value='518400' onclick="rtime_change(518400)" style='width:15px;height:28px;'/>
                                          <?php echo $lang_array["stream_channel_add_text16"] ?>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                          <input type='radio' name='rtime' value='604800' onclick="rtime_change(604800)" style='width:15px;height:28px;'/>
                                          <?php echo $lang_array["stream_channel_add_text17"] ?>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                       </div>
                                   	</div>
                                    
                                    <div class="control-group">
                                    	<label class="control-label" for="focusedInput"><?php echo $lang_array["stream_channel_add_text28"] ?></label>
                                        <div class="controls">
                                          <?php

											$mytable = "stream_equilibrium_server_list_table";
											$sql->create_table($mydb, $mytable, "serverid int, serverip text, tip text, os text, version int, info text, online int, channelcount int");	
										  	$namess = $sql->fetch_datas_order_asc($mydb, $mytable,"serverid");
										  	//foreach($namess as $names) 
											for($ii=0; $ii<count($namess); $ii++)
											{
												if(in_array($namess[$ii][1], $equilibrium_array) || ($namess[$ii][1] == $acc))
												{
													echo "<input class='uniform_on' name='auxiliary_checkbox' id='id_auxiliary_checkbox_" . $namess[$ii][1] . "' type='checkbox' value='" . $namess[$ii][1] . "' onclick=\"auxiliary_checkbox_click('" . $namess[$ii][1] . "')\" checked=\"checked\"/>" . $namess[$ii][1] . "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
												}
												else
												{
													echo "<input class='uniform_on' name='auxiliary_checkbox' id='id_auxiliary_checkbox_" . $namess[$ii][1] . "' type='checkbox' value='" . $namess[$ii][1] . "' onclick=\"auxiliary_checkbox_click('" . $namess[$ii][1] . "')\"/>" . $namess[$ii][1] . "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
												}
												if($ii+1%5==0)
													echo "<br/>";
											}
										  ?>
                                        </div>
                                    </div>
                                    
									<div class="control-group">
                                    	<label class="control-label" for="focusedInput"><?php echo $lang_array["stream_channel_add_text24"] ?></label>
                                        <div class="controls">
                                          <?php
											for($ii=0; $ii<count($namess); $ii++)
											{
												if(in_array($namess[$ii][1], $equilibrium_array))
												{
													echo "<input class='' name='equilibrium_checkbox' id='id_equilibrium_checkbox_" . $namess[$ii][1] . "' type='checkbox' value='" . $namess[$ii][1] . "' onclick=\"\" checked=\"checked\"/>&nbsp;&nbsp;" . $namess[$ii][1] . "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
												}
												else
												{
													echo "<input class='' name='equilibrium_checkbox' id='id_equilibrium_checkbox_" . $namess[$ii][1] . "' type='checkbox' value='" . $namess[$ii][1] . "' onclick=\"\"/>&nbsp;&nbsp;" . $namess[$ii][1] . "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
												}
												
												if($ii+1%5==0)
													echo "<br/>";
											}
										  ?>
                                        </div>
                                    </div>
                                          
                                    <div class="control-group">
                                    	<label class="control-label" for="focusedInput"><?php echo $lang_array["stream_channel_add_text25"] ?></label>
                                        <div class="controls">
                                          <input style='width:15px;height:28px;' name='acc_radio' id='id_acc_radio_no' type='radio' value='no' onclick="acc_click('')" /><?php echo $lang_array["stream_channel_add_text27"] ?>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                          <input style='width:15px;height:28px;' name='acc_radio' id='id_acc_radio_me' type='radio' value='me' onclick="acc_click('')" checked /><?php echo $lang_array["stream_channel_add_text26"] ?>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                          <?php
											for($ii=0; $ii<count($namess); $ii++)
											{
												if($namess[$ii][1] == $acc)
												{
													echo "<input style='width:15px;height:28px;' name='acc_radio' id='id_acc_radio_" . $namess[$ii][1] . "' type='radio' value='" . $namess[$ii][1] . "' onclick=\"\" checked />" . $namess[$ii][1] . "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
												}
												else
												{
													echo "<input style='width:15px;height:28px;' name='acc_radio' id='id_acc_radio_" . $namess[$ii][1] . "' type='radio' value='" . $namess[$ii][1] . "' onclick=\"\"/>" . $namess[$ii][1] . "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
												}
												
												if($ii+1%5==0)
													echo "<br/>";
											}
										  ?>
                                        </div>
                                    </div>
                                                                        
                                    <div class="form-actions">
   										<button type="button" class="btn btn-primary" onClick="save()"><?php echo $lang_array["save_text1"] ?></button>
                                		<button type="reset" class="btn" onclick="back_page()"><?php echo $lang_array["back_text1"] ?></button>
                            		</div>
                                   		</fieldset>
                                        
                                        <input name="nname" id="nname" type="hidden" value=""/>
                                        <input name="equilibrium" id="equilibrium" type="hidden" value=""/>
                                        <input name="acc" id="acc" type="hidden" value=""/>
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
		
		select_live_path();
		
		function loadXMLDoc(serverip,auxiliary,name,urlid,cmd)
		{
			var xmlhttp;
			if (window.XMLHttpRequest)
			{
				//  IE7+, Firefox, Chrome, Opera, Safari 浏览器执行代码
				xmlhttp=new XMLHttpRequest();
			}
			else
			{
				// IE6, IE5 浏览器执行代码
				xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
			}
			xmlhttp.onreadystatechange=function()
			{
				if(xmlhttp.readyState==4 && xmlhttp.status==200)
				{
					if(xmlhttp.responseText.indexOf("ReceiveCmdSuccessful") >= 0)
					{
						alert("<?php echo $lang_array["stream_server_list_text23"] ?>");	
						
					}
					else
					{
						
						/*
						var equilibrium_checkbox = document.getElementById("id_equilibrium_checkbox_" +  serverip);
						//alert(cmd);
						if(cmd == 0)
							equilibrium_checkbox.checked = false;
						else
							equilibrium_checkbox.checked = true;
						*/
							
							
						alert("<?php echo $lang_array["stream_server_list_text24"] ?>");
					}
					
					equ_sync_acc();
				}
			}
			var cmd = "stream_channel_auxiliary_post.php?serverip="+ serverip + "&cmd=" + cmd + "&name=" + name + "&urlid=" + urlid + "&auxiliary=" + auxiliary;
			xmlhttp.open("GET",cmd,true);
			xmlhttp.send();
		}
		
		function auxiliary_checkbox_click(serverip)
		{
			var name = document.getElementById("name").value;
			var urlid = document.getElementById("urlid").value;
			if(name.length <= 0)
			{
				alert("<?php echo $lang_array["stream_channel_list_text36"] ?>");
				
				return;
			}
			
			var auxiliary_checkbox = document.getElementById("id_auxiliary_checkbox_" +  serverip);
			if(auxiliary_checkbox.checked == true)
				loadXMLDoc("<?php echo $serverip ?>",serverip,name,urlid,1);
			else
				loadXMLDoc("<?php echo $serverip ?>",serverip,name,urlid,0);
		}
		
		function get_equilibrium_checkbox_value()
		{
			var value = "";
			var value_array = new Array();
			var equilibrium_checkbox = document.getElementsByName("equilibrium_checkbox");
			for(var i = 0; i < equilibrium_checkbox.length; i++)
			{
				if(equilibrium_checkbox[i].type == "checkbox" && equilibrium_checkbox[i].checked)
				{
					value_array.push(equilibrium_checkbox[i].value);
				}
			}
			
			for(var ii = 0; ii < value_array.length; ii++)
			{
				value = value + value_array[ii];
				if(ii < value_array.length - 1)
					value = value + "|";
			}
			return value;
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
		
		function select_live_path()
		{
			var time = document.getElementById("time").value;
			if(time < 600)
			{
				var pcache_0 = document.getElementById("pcache_id_0");
				var pcache_1 = document.getElementById("pcache_id_1");
				var pcache_2 = document.getElementById("pcache_id_2");
				var pcache_3 = document.getElementById("pcache_id_3");
				var pcache_4 = document.getElementById("pcache_id_4");
				var pcache_5 = document.getElementById("pcache_id_5");
				
				pcache_0.disabled = true;
				pcache_1.disabled = true;
				pcache_2.disabled = true;
				pcache_3.disabled = true;
				pcache_4.disabled = true;
				pcache_5.checked = true;
			}
			else
			{
				var pcache_0 = document.getElementById("pcache_id_0");
				var pcache_1 = document.getElementById("pcache_id_1");
				var pcache_2 = document.getElementById("pcache_id_2");
				var pcache_3 = document.getElementById("pcache_id_3");
				var pcache_4 = document.getElementById("pcache_id_4");
				//var pcache_5 = document.getElementById("pcache_id_5");
				
				pcache_0.disabled = false;
				pcache_1.disabled = false;
				pcache_2.disabled = false;
				pcache_3.disabled = false;
				pcache_4.disabled = false;
			}
		}
		
		function equ_sync_acc()
		{
			
			var auxiliary_obj = document.getElementsByName("auxiliary_checkbox");
			for(var i = 0; i < auxiliary_obj.length; i++)
			{
				
				var equilibrium_obj = document.getElementById("id_equilibrium_checkbox_" + auxiliary_obj[i].value);
				if(auxiliary_obj[i].type == "checkbox" && auxiliary_obj[i].checked)
				{
					equilibrium_obj.disabled = false;
					equilibrium_obj.checked = true;
				}
				else
				{
					equilibrium_obj.checked = false;
					equilibrium_obj.disabled = true;
					
				}
				
				var acc_obj = document.getElementById("id_acc_radio_" + auxiliary_obj[i].value);
				if(auxiliary_obj[i].type == "checkbox" && auxiliary_obj[i].checked)
				{
					//acc_obj.setAttribute("disabled", true);
					acc_obj.disabled = false;
				}
				else
				{
					acc_obj.disabled = true;
					if(acc_obj.checked)
					{
						var acc_me_obj = document.getElementById("id_acc_radio_me");
						acc_me_obj.checked = true;
					}
				}
			}
			
		}
		
		function save()
		{
			var arrayId = new Array();
		<?php
			foreach($channel_list_namess as $channel_list_names)
			{
				if(strcmp($name,$channel_list_names[1]) != 0)
					echo "arrayId.push('" . $channel_list_names[1] . "');";
			}
		?>	
			document.getElementById("tipName").innerHTML = "";
			document.getElementById("tipUrl").innerHTML = "";
			
			var id = document.getElementById("name").value;
			if(id == "")
			{
				document.getElementById("tipName").innerHTML = "<?php echo $lang_array["stream_channel_add_text23"] ?>";
			}
			
			id = document.getElementById("url").value;
			if(id == "")
			{
				document.getElementById("tipUrl").innerHTML = "<?php echo $lang_array["live_preview_add_text41"] ?>";
				return;
			}
	
			var name = document.getElementById("name").value;
			if(name.length <= 0)
			{
				name = RndNum(9);
			}
			else if(checknum(name) == false || name.length > 9 || encodeURI(name).length > 9)
			{
				alert("<?php echo $lang_array["stream_channel_add_text22"] ?>");	
				name = RndNum(9);
			}
			
			var time = document.getElementById("time").value;
			var rtime = GetRadioValue("rtime");
			if(rtime == 250 && time > 500)
			{
				alert("<?php echo $lang_array["stream_channel_add_text29"] ?>");
				return;
			}
			
			for(ii=0; ii<arrayId.length; ii++)
			{
				if(arrayId[ii].localeCompare(name) == 0)
				{
					alert("<?php echo $lang_array["stream_channel_add_text20"] ?>");
					return;
				}
			}

			document.authform.equilibrium.value = get_equilibrium_checkbox_value();
			document.authform.acc.value = GetRadioValue("acc_radio");
			document.authform.nname.value = name;
			document.authform.submit();
		}
		
		function rtime_change(value)
		{
			document.getElementById("time").value = value;
			select_live_path();
		}
		
		function back_page()
		{
			window.location.href = "stream_channel_live_list.php?serverip=<?php echo $serverip ?>";
				
		}
		
		function url_test()
		{
			var serverip = document.getElementById("serverip").value;
			var url = document.getElementById("url").value;
			
			window.open("stream_channel_test.php?serverip=" + serverip + "&url=" + encodeURI(url), "newwindow", "height=450, width=800, toolbar =no, menubar=no, scrollbars=no, resizable=no, location=no, status=no");
		}
		
		function RndNum(n){
   		 	var rnd="";
    		for(var i=0;i<n;i++)
        		rnd+=Math.floor(Math.random()*10);
    		return rnd;
		}
		
		function checknum(value) {
            var Regx = /^[A-Za-z0-9]*$/;
            if (Regx.test(value)) {
                return true;
            }
            else {
                return false;
            }
        }

        </script>
    </body>
<?php
	$sql->disconnect_database();

?>
</html>