<!DOCTYPE html>
<?php
	include_once "cn_lang.php";
	include_once "common.php";
	$sql = new DbSql();
	$sql->login();
	
	$serverip = $_GET["serverip"];
	
	$sql->connect_database_default();
	$mydb = $sql->get_database();
	$sql->create_database($mydb);
	
	$mytable = "stream_server_list_table";
	$sql->create_table($mydb, $mytable, "serverid int, serverip text, tip text, os text, version int");
	$serverip_namess = $sql->fetch_datas($mydb, $mytable);
	
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
                                <div class="muted pull-left"><?php echo $lang_array["stream_channel_batch_list_text1"] ?></div>
                            </div>
                            
                            <div class="block-content collapse in">
                                <div class="span12">
                                	<form class="form-horizontal" method="post" action="stream_channel_batch_post.php" enctype='multipart/form-data'>
                                    	<fieldset>
                                        	<div class="control-group">
                                            	<label class="control-label" for="focusedInput"><?php echo $lang_array["stream_channel_batch_list_text5"] ?></label>
                                      			<div class="controls">
                                            		<select id="serverip" name="serverip"  style='width:180px;' onchange="">
													<?php
													foreach($serverip_namess as $names) 
													{
														$selected = "";
														if($serverip == $names[1])
															$selected = "selected";
													
														if(strlen($names[2]) > 0)
															echo "<option value='" . $names[1] . "' style='width:240px;' " . $selected . ">" . $names[1] . "(" . $names[2] . ")" . "</option>";
														else
															echo "<option value='" . $names[1] . "' style='width:240px;' " . $selected . ">" . $names[1] . "</option>";
												
													}
													?>
    												</select>
                                      		 	</div>
                                       			</br>
                                                <label class="control-label" for="focusedInput"><?php echo $lang_array["stream_channel_batch_list_text2"] ?></label>
                                                <div class="controls">
                                                	 <input class="input-large focused" id="url0" name="url0" type="text" />	-->
                                                     <input class="input-large focused" id="url1" name="url1" type="text" />
                                                </div>
                                                </br>
                                                <label class="control-label" for="focusedInput"><?php echo $lang_array["stream_channel_batch_list_text3"] ?></label>
                                       			<div class="controls">
                                          			<input type='radio' name='pcache' value='0' <?php echo "checked='checked'"; ?> style='width:15px;height:28px;'/>
                                          			<?php echo $lang_array["stream_channel_add_text5"] ?>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                         			 <input type='radio' name='pcache' value='1' style='width:15px;height:28px;'/>
                                          			<?php echo $lang_array["stream_channel_add_text6"] ?>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                          			<input type='radio' name='pcache' value='2' style='width:15px;height:28px;'/>
                                          			<?php echo $lang_array["stream_channel_add_text7"] ?>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                          			<input type='radio' name='pcache' value='3' style='width:15px;height:28px;'/>
                                          			<?php echo $lang_array["stream_channel_add_text8"] ?>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                          			<input type='radio' name='pcache' value='4' style='width:15px;height:28px;'/>
                                          			<?php echo $lang_array["stream_channel_add_text9"] ?>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                          			<input type='radio' name='pcache' value='5' style='width:15px;height:28px;'/>
                                          			<?php echo $lang_array["stream_channel_add_text10"] ?>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                       			</div>
                                                </br>
                                                <label class="control-label" for="focusedInput"><?php echo $lang_array["stream_channel_batch_list_text4"] ?></label>
                                       			<div class="controls">
                                          			<input class="input-mini focused" id="time" name="time" type="text" value="200" />&nbsp;&nbsp;&nbsp;<?php echo $lang_array["stream_channel_add_text18"] ?>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                          			<input type='radio' name='rtime' value='604800' checked='checked' onclick="rtime_change(300)" style='width:15px;height:28px;'/>
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
												</br>
                                                <div class="form-actions">
   													<button type="submit" class="btn btn-primary"><?php echo $lang_array["save_text1"] ?></button>
                                					<button type="reset" class="btn" onclick="back_page()"><?php echo $lang_array["back_text1"] ?></button>
                            					</div>
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
        <!--/.fluid-container-->

        <script src="vendors/jquery-1.9.1.js"></script>
        <script src="bootstrap/js/bootstrap.min.js"></script>
        <script src="vendors/datatables/js/jquery.dataTables.min.js"></script>


        <script src="assets/scripts.js"></script>
        <script src="assets/DT_bootstrap.js"></script>
        <script>
        $(function() {
            
        });
		
		function rtime_change(value)
		{
			document.getElementById("time").value = value;
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
		
        </script>
    </body>

</html>