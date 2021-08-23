<!DOCTYPE html>
<?php
	include_once "cn_lang.php";
	include_once "common.php";
	$sql = new DbSql();
	$sql->login();

	
	$name = $_GET["name"];
	$serverip = $_GET["serverip"];

	$sql->connect_database_default();
	$mydb = $sql->get_database();
	$sql->create_database($mydb);

	$mytable = "stream_server_list_table";
	$sql->create_table($mydb, $mytable, "serverid int, serverip text, tip text, os text, version int");
	$serverip_namess = $sql->fetch_datas($mydb, $mytable);


	$cmd = "getplayxml";
	$ret = send($serverip,$cmd);
	//echo "ret:" . $ret . " serverip:" . $serverip;
	
	$urlid = "";
	if(isset($_GET["id"]))
	{
		$urlid = $_GET["id"];
	}
	$cname = "";
	$url = "";
	$dir = "";
	$path = "";
	$cache = "";
	$disable = "";
	$tabel = "";
	$equilibrium_array = array();
	$exist = 0;
	if(strstr($ret,"?xml") != false && strstr($ret,"version=") != false)
	{
		$doc = new DOMDocument();
		$doc->loadXML($ret);
		$channels = $doc->getElementsByTagName("channel");
		foreach($channels as $channel){
			$cname = $channel->getElementsByTagName("name")->item(0)->nodeValue;
			if($cname == $name)
			{
				$exist = 1;
				
				$urlid = $channel->getElementsByTagName("id")->item(0)->nodeValue;
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
	
	if($exist == 0)
	{
		/*
		$cmd = "getlivedirinfo#" . $name;
		$ret = send($serverip,$cmd);
		$dirpath = "";
		if(strstr($ret,"?xml") != false && strstr($ret,"version=") != false)
		{
			$doc = new DOMDocument();
			$doc->loadXML($ret);
			$channels = $doc->getElementsByTagName("status");
			$index = 0;
			foreach($channels as $channel){
				$dirname = $channel->getElementsByTagName("name")->item(0)->nodeValue;
				$dirreceive = $channel->getElementsByTagName("receive")->item(0)->nodeValue;
				$dironline = $channel->getElementsByTagName("online")->item(0)->nodeValue;
				$dirpath = $channel->getElementsByTagName("dir")->item(0)->nodeValue;
				$dircurrent = $channel->getElementsByTagName("current")->item(0)->nodeValue;
			}
		}
		*/
		
		$urlid = time()%1000000;
		$url = "push";
		$dirpath = "/tmp/gemini/gserver/";
		$rtime = 250;
		$tip = "";
		$equilibriumss = "";
		$acc = "";
		
		$cmd = "playedit#" . $urlid . "|" . $name . "|" . $url . "|" . $pcache . "|" . $rtime . "|" . "0" . "|" . urlencode($tip) . "|" . base64_encode($equilibriumss) . "|" . $acc;
		$ret = send($serverip,$cmd);
		if($ret != "ReceiveCmdSuccessful")
		{
			header("Location: stream_channel_live_list.php?control=1&serverip=" . $serverip);
			//echo "receive fail";	
		}
		else
		{
			//echo "receive ok";
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
                                <div class="muted pull-left"><?php echo $lang_array["stream_channel_add_text1"] ?></div>
                            </div>
                            
                            <div class="block-content collapse in">
                                <div class="span12">
                                	<form class="form-horizontal"  name="authform"  method="post" action="stream_channel_push_post.php" enctype='multipart/form-data'>
									<fieldset><br/>
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
													echo "<input class='uniform_on' name='auxiliary_checkbox' id='id_auxiliary_checkbox_" . $namess[$ii][1] . "' type='checkbox' value='" . $namess[$ii][1] . "' onclick=\"auxiliary_checkbox_click('" . $namess[$ii][1] . "')\" checked=\"checked\"/>&nbsp;&nbsp;" . $namess[$ii][1] . "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
												}
												else
												{
													echo "<input class='uniform_on' name='auxiliary_checkbox' id='id_auxiliary_checkbox_" . $namess[$ii][1] . "' type='checkbox' value='" . $namess[$ii][1] . "' onclick=\"auxiliary_checkbox_click('" . $namess[$ii][1] . "')\"/>&nbsp;&nbsp;" . $namess[$ii][1] . "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
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
                                        
                                        <input name="urlid" id="urlid" type="hidden" value=""/>
                                        <input name="nname" id="nname" type="hidden" value=""/>
                                        <input name="pcache" id="pcache" type="hidden" value=""/>
                                        <input name="time" id="time" type="hidden" value=""/>
                                        <input name="equilibrium" id="equilibrium" type="hidden" value=""/>
                                        <input name="acc" id="acc" type="hidden" value=""/>
                                        <input name="serverip" id="serverip" type="hidden" value=""/>
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
        <script src="scripts/base-loading.js"></script>
                
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
		
		//equ_sync_acc();
		
		function loadXMLDoc(serverip,auxiliary,urlid,name,cmd)
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
			var name = "<?php echo $name ?>";
			if(name.length <= 0)
			{
				alert("<?php echo $lang_array["stream_channel_list_text36"] ?>");
				return;
			}
			
			var auxiliary_checkbox = document.getElementById("id_auxiliary_checkbox_" +  serverip);
			if(auxiliary_checkbox.checked == true)
				loadXMLDoc("<?php echo $serverip ?>",serverip,"<?php echo $urlid ?>",name,1);
			else
				loadXMLDoc("<?php echo $serverip ?>",serverip,"<?php echo $urlid ?>",name,0);
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
			document.authform.urlid.value = "<?php echo $urlid ?>";
			//document.authform.tip.value = "";
			document.authform.nname.value = "<?php echo $nname ?>";
			document.authform.pcache.value = "5";
			document.authform.time.value = "250";
			document.authform.equilibrium.value = get_equilibrium_checkbox_value();
			document.authform.acc.value = GetRadioValue("acc_radio");
			document.authform.nname.value = "<?php echo $name ?>";
			document.authform.serverip.value = "<?php echo $serverip ?>";
			document.authform.submit();
		}
		
		function rtime_change(value)
		{
			document.getElementById("time").value = value;
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