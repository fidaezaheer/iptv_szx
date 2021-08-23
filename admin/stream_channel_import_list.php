<!DOCTYPE html>
<?php
	include_once "cn_lang.php";
	include_once "common.php";
	include_once "gemini.php";
	$sql = new DbSql();
	$sql->login();

	$serverip = $_GET["serverip"];
	
	$liveurls = array();
	
	$sql->connect_database_default();
	$mydb = $sql->get_database();
	$sql->create_database($mydb);
	
	$mytable = "stream_channel_batch_import_tmp_table";
	$sql->create_table($mydb, $mytable, "urlid int, name text, url text, online int, cache int, 
										path text, tabel text, serverip text, url1 text, url2 text, 
										url3 text, tip text, status text, receive text");
	$namess = $sql->fetch_datas($mydb, $mytable);

	$mytable = "stream_server_list_table";
	$sql->create_table($mydb, $mytable, "serverid int, serverip text, tip text, os text, version int");
	$serverip_namess = $sql->fetch_datas($mydb, $mytable);
	
	
	
	$sql->disconnect_database();
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
                                <div class="muted pull-left"><?php echo $lang_array["batch_introduction_list_text7"] ?></div>
                            </div>
                            <div class="block-content collapse in">
                                <div class="span12">
									<form class="form-horizontal" method="post" action="stream_channel_import_upload.php?serverip=<?php echo $serverip ?>" enctype='multipart/form-data'>
                                    
                                    <div class="control-group" style="background-color:#CCC"> 
                                        <br/>
										<label class="control-label"><?php echo $lang_array["batch_introduction_list_text8"] ?>：</label>
                                        <div class="controls">
                                        	<input class="input-file uniform_on" type="file" name="file" id="file" /><input class="btn btn-primary" type="submit" name="submit" value="<?php echo $lang_array["batch_introduction_list_text8"] ?>" /> 
                                            
                                            <input name="" class="btn btn-primary" type="button" value="<?php echo $lang_array["stream_channel_import_list_text15"] ?>" onclick="flash_import()"/>
                                      	</div>    
                                      	<br/>                    
                                    </div>
                                    
                                     
                                    <table cellpadding="0" cellspacing="0" border="0" class="table table-striped table-bordered" style="table-layout:fixed;">
                                        <thead>
                                            <tr>
                                            	<th style="vertical-align:middle; text-align:center;" width="5%"><?php echo $lang_array["stream_channel_import_list_text14"] ?></th>
            									<th style="vertical-align:middle; text-align:center;" width="5%"><?php echo $lang_array["stream_channel_import_list_text6"] ?></th>
            									<th style="vertical-align:middle; text-align:center;" width="5%"><?php echo $lang_array["stream_channel_import_list_text7"] ?></th>
            									<th style="vertical-align:middle; text-align:center;" width="10%"><?php echo $lang_array["stream_channel_import_list_text8"] ?></th>
            									<th style="vertical-align:middle; text-align:center;" width="45%"><?php echo $lang_array["stream_channel_import_list_text9"] ?></th>
												<th style="vertical-align:middle; text-align:center;" width="15%"><?php echo $lang_array["stream_channel_import_list_text10"] ?></th>
            									<th style="vertical-align:middle; text-align:center;" width="10%"><?php echo $lang_array["stream_channel_import_list_text11"] ?></th>
                                                <th style="vertical-align:middle; text-align:center;" width="10%"><?php echo $lang_array["stream_channel_import_list_text12"] ?></th>
                                            </tr>
                                        </thead>
                                        
<?php
		echo "<tbody>";
		{
			$ii = 0;
			foreach($namess as $names)
			{ 
				
				echo "<td style='vertical-align:middle; text-align:center;'>"."<input name='introduction_checkbox' type='checkbox' value='" . $ii . "' />"."</td>";
				echo "<td style='vertical-align:middle; text-align:center;'>" . $names[0] . "</td>";
				echo "<td style='vertical-align:middle; text-align:center;'>" . $names[1] . "</td>";
				echo "<td style='vertical-align:middle; text-align:center;'>" . $names[11] . "</td>";
				echo "<td style='vertical-align:middle; text-align:center;'>" . $names[2] . "</td>";
				echo "<td style='vertical-align:middle; text-align:center;'>" . $names[4] . "</td>";
				$pdir = "";
				switch(intval($names[5]))
				{
					case 0:
						$pdir = $lang_array["stream_channel_list_text17"];
						break;	
					case 1:
						$pdir = $lang_array["stream_channel_list_text18"];
						break;	
					case 2:
						$pdir = $lang_array["stream_channel_list_text19"];
						break;	
					case 3:
						$pdir = $lang_array["stream_channel_list_text20"];
						break;	
					case 4:
						$pdir = $lang_array["stream_channel_list_text21"];
						break;	
					case 5:
						$pdir = $lang_array["stream_channel_list_text22"];
						break;																										
				}					
				echo "<td style='align:center; vertical-align:middle; text-align:center;'>" . $pdir . "</td>";
				echo "<td style='align:center; vertical-align:middle; text-align:center;'><a href='#' onclick='delete_stream(" . $names[0] . ")'>" . $lang_array["del_text1"] . "</a></td>";
				echo "</tr>";
			}
		}
		echo "</tbody>";	
		$sql->disconnect_database();
?>                                       
          	</table>
            <input name="" type="button" class="btn btn-primary" value="<?php echo $lang_array["batch_introduction_list_text9"] ?>" onclick="selectAll()"/><input name="" type="button" class="btn btn-primary" value="<?php echo $lang_array["batch_introduction_list_text10"] ?>" onclick="noAll()"/>         
            
            <br/><br/>                  
			<div class="control-group" style="background-color:#CCC">
            	<br/>               
                <label class="control-label"><?php echo $lang_array["batch_introduction_list_text14"] ?></label>
                <div class="controls">
                	<input type="radio" id="itype" name="itype" style="width:15px;height:28px;"/><?php echo $lang_array["batch_introduction_list_text15"] ?>&nbsp;&nbsp;&nbsp;&nbsp;<input type="radio" id="itype" name="itype" style="width:15px;height:28px;" checked/><?php echo $lang_array["batch_introduction_list_text16"] ?>
                </div>
                <br/>
                <label class="control-label" for="focusedInput"><?php echo $lang_array["stream_channel_sync_list_text2"] ?></label>
                <div class="controls">
                	<select id="serverip" name="serverip"  style='width:360px;' onchange="">
					<?php
					
						foreach($serverip_namess as $names) 
						{
							if($serverip == $names[1])
							{
								$selected = "";
								if(strlen($names[2]) > 0)
									echo "<option value='" . $names[1] . "' style='width:240px;' " . $selected . ">" . $names[1] . "(" . $names[2] . ")" . "</option>";
								else
									echo "<option value='" . $names[1] . "' style='width:240px;' " . $selected . ">" . $names[1] . "</option>";
							}
												
						}
					?>
    				</select>
                    </div>
                <br/>
  			</div>
            
            <div class="form-actions">
            	<input name='' class='btn btn-primary' type='button' value="<?php echo $lang_array['stream_channel_import_list_text13'] ?>" onclick="save()"/>
                <input name="" class="btn btn-primary" type="button" value="<?php echo $lang_array["back_text1"] ?>" onclick="live_back()"/>
            </div> 
                                    
									</form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

         </div>    
         
		<FORM name="authform_type" action="batch_introduction_list.php" method="post">  
    		<input name="playlist" id="playlist" type="hidden" value=""/>
    		<input name="page" id="page" type="hidden" value=""/>
    		<input name="type" id="type" type="hidden" value=""/>
		</Form>
        
        <form name='authform' method='post' action='batch_introduction_post.php' enctype='multipart/form-data' >
        	<input name="playlistfile" id="playlistfile" type="hidden" value=""/>
        	<input name="playlisttype" id="playlisttype" type="hidden" value=""/>
        	<input name="playlistitype" id="playlistitype" type="hidden" value=""/>
        	<input name="type" id="type" type="hidden" value=""/>
     	</form>
     
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

		function GetRadioValue(RadioName){
    		var obj;
    		obj=document.getElementsByName(RadioName);
    		if(obj!=null){
       			var i;
        		for(i=0;i<obj.length;i++)
				{
           	 		if(obj[i].checked){
                		return i;
            		}
        		}
    		}
    		return null;
		}
		
		function selectAll() //全选
		{
			//alert(1);
			var objs = document.getElementsByName('introduction_checkbox');
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
			//alert(0);
			var objs = document.getElementsByName('introduction_checkbox');
			var i;
			for(i = 0; i < objs.length; i++)
			{
				if(objs[i].type == "checkbox")
				{
					objs[i].checked = false;
				}
			}
		} 
		
		function delete_stream(urlid)
		{
			window.location.href = "stream_channel_import_del.php?urlid=" +urlid + "&serverip=<?php echo $serverip ?>";
			
		}
		
		function flash_import()
		{
			window.location.href = "stream_channel_import_list.php?serverip=<?php echo $serverip ?>";
		}
		
		

		function delXMLDoc(serverip)
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
				//alert(xmlhttp.status);
				if(xmlhttp.readyState==4 && xmlhttp.status==200)
				{
					if(xmlhttp.responseText != "ReceiveCmdSuccessful")
					{
						//alert("DEL PLAYXML DATA ERROR");	
					}
				}
			}
			xmlhttp.open("GET","stream_channel_playxml_del.php?serverip="+serverip,true);
			xmlhttp.send();
		}
		
		function save()
		{
			var serverip = document.getElementById("serverip").value;
			var itype = GetRadioValue("itype");
			if(itype == 0)
			{
				delXMLDoc(serverip);	
			}
			
			//
			var cmd = "stream_channel_import_run.php?serverip=" + serverip + "&itype=" + itype;
			window.open(cmd, "newwindow", "height=300, width=650, toolbar =no, menubar=no, scrollbars=no, resizable=no, location=no, status=no");
		}
		
		function live_back()
		{
			window.location.href = "stream_channel_list.php?serverip=<?php echo $serverip ?>";
		}

        </script>
    </body>
    
</html>
