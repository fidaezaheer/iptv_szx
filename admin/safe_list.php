<!DOCTYPE html>
<?php
	include_once "cn_lang.php";
	include 'common.php';
	$sql = new DbSql();
	$sql->login();

	$sql->connect_database_default();
	$mydb = $sql->get_database();
	$sql->create_database($mydb);
	$mytable = "safe_table";
	$sql->create_table($mydb, $mytable, "id int,safe0 int,safe1 int,safe2 int,safe3 int,safe4 int,safe5 int,safe6 int,safe7 int,safe8 int,safe9 int");
	$safe0 = get_set_xml_file("setting.xml");	
	$safe1 = $sql->query_data($mydb, $mytable, "id", "0", "safe1");		
	$safe2 = $sql->query_data($mydb, $mytable, "id", "0", "safe2");	

		
	$mytable = "safe_table2";
	$sql->create_table($mydb, $mytable, "id text,safe10 text,safe11 text,safe12 text,safe13 text,safe14 text,safe15 text,safe16 text,safe17 text,safe18 text,safe19 text,model text,logintime int, unbundling int, disabel_model text, limitarea text, prekey text, server_request text, longtime text, limitsign text");
											
	$safe3 = $sql->query_data($mydb, $mytable, "id", "0", "safe10");	
	$safe4 = $sql->query_data($mydb, $mytable, "id", "0", "safe11");	
	$safe5 = $sql->query_data($mydb, $mytable, "id", "0", "safe12");		
	$safe6 = $sql->query_data($mydb, $mytable, "id", "0", "safe13");
	$safe7 = $sql->query_data($mydb, $mytable, "id", "0", "safe14");		
	
	
	/*
	if($sql->find_column($mydb, $mytable, "model") == 0)
		$sql->add_column($mydb, $mytable,"model", "text");
	
	if($sql->find_column($mydb, $mytable, "logintime") == 0)
		$sql->add_column($mydb, $mytable,"logintime", "int");
	*/
						
	$model = $sql->query_data($mydb, $mytable, "id", "0", "model");
	$logintime = $sql->query_data($mydb, $mytable, "id", "0", "logintime");
	$unbundling = $sql->query_data($mydb, $mytable, "id", "0", "unbundling");
	$disabel_model = $sql->query_data($mydb, $mytable, "id", "0", "disabel_model");
	$limitarea = $sql->query_data($mydb, $mytable, "id", "0", "limitarea");
	$prekey = $sql->query_data($mydb, $mytable, "id", "0", "prekey");
	$server_request = $sql->query_data($mydb, $mytable, "id", "0", "server_request");
	$save_login_num = get_set_xml_file("safe3.xml");
	$long_time = $sql->query_data($mydb, $mytable, "id", "0", "longtime");
	$limitsign = $sql->query_data($mydb, $mytable, "id", "0", "limitsign");
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
                                <div class="muted pull-left"><?php echo $lang_array["safe_list_text1"] ?></div>
                            </div>
                            
                            <div class="block-content collapse in">
                                <div class="span12">
                                	<form class="form-horizontal" name="authform" method="post" action="version_post.php" enctype='multipart/form-data'>
                                    <fieldset><br/>
                                 	<div class="control-group">
                                       <label class="control-label" for="focusedInput" style="text-shadow:#0CF"><?php echo $lang_array["safe_list_text2"] ?></label>
                                       <div class="controls">
                                       <input style="width:15px;height:28px;" type="radio" name="rsafe0" value="0" id="radio0" <?php if($safe0 == null || intval($safe0)==0) echo "checked"?> /><?php echo $lang_array["safe_list_text11"] ?>&nbsp;
                                       <input style="width:15px;height:28px;" type="radio" name="rsafe0" value="1" id="radio1" <?php if(intval($safe0)==1) echo "checked"?> /><?php echo $lang_array["safe_list_text12"] ?>&nbsp;
                                       <input style="width:15px;height:28px;" type="radio" name="rsafe0" value="2" id="radio1" <?php if(intval($safe0)==2) echo "checked"?> /><?php echo $lang_array["safe_list_text13"] ?>&nbsp;
                                       <input name="" class="btn btn-primary" type="button" value="<?php echo $lang_array["sure_text1"] ?>" onclick="save0()"/>
                                       </div>
                                   	</div>
                                    
                                    <div class="control-group">
                                       <label class="control-label" for="focusedInput" style="text-shadow:#0CF"><?php echo $lang_array["safe_list_text3"] ?></label>
                                       <div class="controls">
                                       <input style="width:15px;height:28px;" type="radio" name="rsafe1" value="0" id="radio0" <?php if($safe1 == null || intval($safe1)==0) echo "checked"?> /><?php echo $lang_array["safe_list_text11"] ?>&nbsp;
                                       <input style="width:15px;height:28px;" type="radio" name="rsafe1" value="1" id="radio1" <?php if(intval($safe1)==1) echo "checked"?> /><?php echo $lang_array["safe_list_text12"] ?>&nbsp;
                                       <input style="width:15px;height:28px;" type="radio" name="rsafe1" value="2" id="radio1" <?php if(intval($safe1)==2) echo "checked"?> /><?php echo $lang_array["safe_list_text13"] ?>&nbsp;
                                       <input name="" class="btn btn-primary" type="button" value="<?php echo $lang_array["sure_text1"] ?>" onclick="save1()"/>
                                       </div>
                                   	</div>
                                    
                                    <div class="control-group">
                                       <label class="control-label" for="focusedInput" style="text-shadow:#0CF"><?php echo $lang_array["safe_list_text4"] ?></label>
                                       <div class="controls">
                                       <input style="width:15px;height:28px;" type="radio" name="rsafe2" value="0" id="radio0" <?php if($safe2 == null || intval($safe2)==0) echo "checked"?> /><?php echo $lang_array["safe_list_text11"] ?>&nbsp;
                                       <input style="width:15px;height:28px;" type="radio" name="rsafe2" value="1" id="radio1" <?php if(intval($safe2)==1) echo "checked"?> /><?php echo $lang_array["safe_list_text12"] ?>&nbsp;
                                       <input name="" class="btn btn-primary" type="button" value="<?php echo $lang_array["sure_text1"] ?>" onclick="save2()"/>
                                       </div>
                                   	</div>

									<div class="control-group">
                                       <label class="control-label" for="focusedInput" style="text-shadow:#0CF"><?php echo $lang_array["safe_list_text28"] ?></label>
                                       <div class="controls">
                                       <input style="width:15px;height:28px;" type="radio" name="rloginnum" value="0" id="radio0" <?php if($save_login_num == null || intval($save_login_num)==0) echo "checked"?> /><?php echo $lang_array["safe_list_text11"] ?>&nbsp;
                                       <input style="width:15px;height:28px;" type="radio" name="rloginnum" value="1" id="radio1" <?php if(intval($save_login_num)==1) echo "checked"?> /><?php echo $lang_array["safe_list_text12"] ?>&nbsp;
                                       <input style="width:15px;height:28px;" type="radio" name="rloginnum" value="2" id="radio2" <?php if(intval($save_login_num)!=1 && intval($save_login_num)!=0) echo "checked"?> /><?php echo $lang_array["safe_list_text29"] ?>&nbsp;<input class="input-mini focused" id="text_login_num" name="text_login_num" type="text" value="<?php if(intval($save_login_num)!=1 && intval($save_login_num)!=0) echo $save_login_num ?>">
                                       <input name="" class="btn btn-primary" type="button" value="<?php echo $lang_array["sure_text1"] ?>" onclick="save_login_num()"/>
                                       </div>
                                   	</div>
                                    
                                    <div class="control-group">
                                       <label class="control-label" for="focusedInput" style="text-shadow:#0CF"><?php echo $lang_array["safe_list_text5"] ?></label>
                                       <div class="controls">
                                       <?php echo $lang_array["safe_list_text9"] ?>:&nbsp;<input class="input-medium focused" id="text_safe3" name="text_safe3" type="text" value="<?php echo $safe3 ?>">
                                       &nbsp;&nbsp;&nbsp;&nbsp;
                                       <?php echo $lang_array["safe_list_text10"] ?>:&nbsp;<input class="input-mini focused" id="text_safe4" name="text_safe4" type="text" value="<?php echo $safe4 ?>">
                                       &nbsp;
                                       <input name="" class="btn btn-primary" type="button" value="<?php echo $lang_array["sure_text1"] ?>" onclick="save3()"/>
                                       </div>
                                   	</div>
                                    
                                    <div class="control-group">
                                       <label class="control-label" for="focusedInput" style="text-shadow:#0CF"><?php echo $lang_array["safe_list_text6"] ?></label>
                                       <div class="controls">
                                       <input class="input-xxlarge focused" id="text_safe5" name="text_safe5" type="text" value="<?php echo $safe5 ?>">
                                       &nbsp;
                                       <input name="" class="btn btn-primary" type="button" value="<?php echo $lang_array["sure_text1"] ?>" onclick="save4()"/>
                                       </div>
                                   	</div>
                                    
                                    <div class="control-group">
                                       <label class="control-label" for="focusedInput" style="text-shadow:#0CF"><?php echo $lang_array["safe_list_text27"] ?></label>
                                       <div class="controls">
                                       <input class="input-xxlarge focused" id="text_server_request" name="text_server_request" type="text" value="<?php echo $server_request ?>">
                                       &nbsp;
                                       <input name="" class="btn btn-primary" type="button" value="<?php echo $lang_array["sure_text1"] ?>" onclick="save_server_request()"/>
                                       </div>
                                   	</div>
                                    
                                    <div class="control-group">
                                       <label class="control-label" for="focusedInput" style="text-shadow:#0CF"><?php echo $lang_array["safe_list_text7"] ?></label>
                                       <div class="controls">
                                       <input class="input-xxlarge focused" id="text_safe6" name="text_safe6" type="text" value="<?php echo $safe6 ?>">
                                       &nbsp;
                                       <input name="" class="btn btn-primary" type="button" value="<?php echo $lang_array["sure_text1"] ?>" onclick="save5()"/>
                                       </div>
                                   	</div>
                                    
                                    <div class="control-group">
                                       <label class="control-label" for="focusedInput" style="text-shadow:#0CF"><?php echo $lang_array["safe_list_text8"] ?></label>
                                       <div class="controls">
                                       <input class="input-xxlarge focused" id="text_safe7" name="text_safe7" type="text" value="<?php echo $safe7 ?>">
                                       &nbsp;
                                       <input name="" class="btn btn-primary" type="button" value="<?php echo $lang_array["sure_text1"] ?>" onclick="save6()"/>
                                       </div>
                                   	</div>
                                    
                                    <div class="control-group">
                                       <label class="control-label" for="focusedInput" style="text-shadow:#0CF"><?php echo $lang_array["safe_list_text14"] ?></label>
                                       <div class="controls">
                                       <textarea class="input-xxlarge focused" id="text_model" name="text_model" type="text" ><?php echo $model ?></textarea>
                                       &nbsp;<?php echo $lang_array["safe_list_text15"] ?>&nbsp;
                                       <input name="" class="btn btn-primary" type="button" value="<?php echo $lang_array["sure_text1"] ?>" onclick="save_model()"/>
                                       </div>
                                   	</div>
                                    
                                    <div class="control-group">
                                       <label class="control-label" for="focusedInput" style="text-shadow:#0CF"><?php echo $lang_array["safe_list_text20"] ?></label>
                                       <div class="controls">
                                       <textarea class="input-xxlarge focused" id="text_disabel_model" name="text_disabel_model" type="text" ><?php echo $disabel_model ?></textarea>
                                       &nbsp;<?php echo $lang_array["safe_list_text15"] ?>&nbsp;
                                       <input name="" class="btn btn-primary" type="button" value="<?php echo $lang_array["sure_text1"] ?>" onclick="save_disabel_model()"/>
                                       </div>
                                   	</div>
                                    
                                    <div class="control-group">
                                       <label class="control-label" for="focusedInput" style="text-shadow:#0CF"><?php echo $lang_array["safe_list_text16"] ?></label>
                                       <div class="controls">
                                       <input style="width:15px;height:28px;" type="radio" name="rlogin_time" value="0" id="rlogin_time0" <?php if($logintime == null || intval($logintime)==0) echo "checked"?> /><?php echo $lang_array["safe_list_text11"] ?>&nbsp;
                                       <input style="width:15px;height:28px;" type="radio" name="rlogin_time" value="1" id="rlogin_time1" <?php if($logintime > 1) echo "checked"?> /><?php echo $lang_array["safe_list_text12"] ?>&nbsp;
                                       <input class="input-mini focused" id="text_logintime" name="text_logintime" type="text" value="<?php echo $logintime ?>">&nbsp;<?php echo $lang_array["safe_list_text17"] ?>&nbsp;
                                       
                                       <input name="" class="btn btn-primary" type="button" value="<?php echo $lang_array["sure_text1"] ?>" onclick="save_logintime()"/>
                                       </div>
                                   	</div>
                                    
                                    <div class="control-group">
                                       <label class="control-label" for="focusedInput" style="text-shadow:#0CF"><?php echo $lang_array["safe_list_text19"] ?></label>
                                       <div class="controls">
                                       <input style="width:15px;height:28px;" type="radio" name="unbundling" value="0" id="unbundling0" <?php if($unbundling == null || intval($unbundling)==0) echo "checked"?> /><?php echo $lang_array["no_text1"] ?>&nbsp;
                                       <input style="width:15px;height:28px;" type="radio" name="unbundling" value="1" id="unbundling1" <?php if(intval($unbundling)==1) echo "checked"?> /><?php echo $lang_array["yes_text1"] ?>&nbsp;
                                       <input name="" class="btn btn-primary" type="button" value="<?php echo $lang_array["sure_text1"] ?>" onclick="save_unbundling()"/>
                                       </div>
                                   	</div>
                                    
                                    <div class="control-group">
                                       	<label class="control-label" for="focusedInput" style="text-shadow:#0CF"><?php echo $lang_array["safe_list_text21"] ?></label>
                                       	<div class="controls" style="vertical-align:middle;">
                                        	<input style="width:15px;height:28px;" type="radio" name="limitarea_radio" value="0" id="limitarea0_radio" <?php if($limitarea == null || strlen($limitarea)<=0) echo "checked"?>  onChange="limitarea_change(0)"/><?php echo $lang_array["no_text1"] ?>&nbsp;
                                       		<input style="width:15px;height:28px;" type="radio" name="limitarea_radio" value="1" id="limitarea1_radio" <?php if($limitarea != null && strlen($limitarea)>0) echo "checked"?> onChange="limitarea_change(1)"/><?php echo $lang_array["yes_text1"] ?>&nbsp;
                                            
                                            <?php echo $lang_array["safe_list_text22"] ?>:<input class="input-xxlarge focused" id="limitarea_id" name="limitarea_id" type="text" value="<?php echo $limitarea; ?>">
                                            <button type='button' class='btn btn-primary' onclick='limitarea()'> . <?php echo $lang_array['sure_text1'] ?>. </button>
                                            <?php echo $lang_array["safe_list_text23"] ?>
                                       	</div>
                                   	</div>
                                    
                                    <div class="control-group">
                                       <label class="control-label" for="focusedInput" style="text-shadow:#0CF"><?php echo $lang_array["safe_list_text25"] ?></label>
                                       <div class="controls">
                                       <input style="width:15px;height:28px;" type="radio" name="prekey" value="0" id="prekey0" <?php if($prekey == null || intval($prekey)==0) echo "checked"?> /><?php echo $lang_array["no_text1"] ?>&nbsp;
                                       <input style="width:15px;height:28px;" type="radio" name="prekey" value="1" id="prekey1" <?php if(intval($prekey)==1) echo "checked"?> /><?php echo $lang_array["yes_text1"] ?>&nbsp;
                                       <input name="" class="btn btn-primary" type="button" value="<?php echo $lang_array["sure_text1"] ?>" onclick="save_prekey()"/>
                                       &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                       <?php echo $lang_array["safe_list_text26"] ?>
                                       </div>
                                   	</div>
                                    
                                    <div class="control-group">
                                       <label class="control-label" for="focusedInput" style="text-shadow:#0CF"><?php echo $lang_array["safe_list_text31"] ?></label>
                                       <div class="controls">
                                       <input style="width:15px;height:28px;" type="radio" name="rlong_time" value="0" id="long_time0" <?php if($long_time == null || intval($long_time)==0) echo "checked"?> /><?php echo $lang_array["safe_list_text11"] ?>&nbsp;
                                       <input style="width:15px;height:28px;" type="radio" name="rlong_time" value="1" id="long_time1" <?php if($long_time > 1) echo "checked"?> /><?php echo $lang_array["safe_list_text12"] ?>&nbsp;
                                       <input class="input-mini focused" id="text_longtime" name="text_longtime" type="text" value="<?php echo $long_time ?>">&nbsp;<?php echo $lang_array["safe_list_text32"] ?>&nbsp;
                                       
                                       <input name="" class="btn btn-primary" type="button" value="<?php echo $lang_array["sure_text1"] ?>" onclick="save_longtime()"/>
                                       </div>
                                   	</div>
                                    
                                    <div class="control-group">
                                       	<label class="control-label" for="focusedInput" style="text-shadow:#0CF"><?php echo $lang_array["safe_list_text33"] ?></label>
                                       	<div class="controls" style="vertical-align:middle;">
                                        	<input style="width:15px;height:28px;" type="radio" name="limitsign_radio" value="0" id="limitsign0_radio" <?php if($limitsign == null || strlen($limitsign)<=0) echo "checked"?>  onChange="limitsign_change(0)"/><?php echo $lang_array["no_text1"] ?>&nbsp;
                                       		<input style="width:15px;height:28px;" type="radio" name="limitsign_radio" value="1" id="limitsign1_radio" <?php if($limitsign != null && strlen($limitsign)>0) echo "checked"?> onChange="limitsign_change(1)"/><?php echo $lang_array["yes_text1"] ?>&nbsp;
                                            
                                            <?php echo $lang_array["safe_list_text34"] ?>:<input class="input-xxlarge focused" id="limitsign_id" name="limitsign_id" type="text" value="<?php echo $limitsign; ?>">
                                            <button type='button' class='btn btn-primary' onclick='limitsign()'> . <?php echo $lang_array['sure_text1'] ?>. </button>
                                            <?php echo $lang_array["safe_list_text35"] ?>
                                       	</div>
                                   	</div>
                                    <!--
                                    <div class="form-actions">
                                    	<button type="submit" class="btn btn-primary" onclick="save()"><?php echo $lang_array["save_text1"] ?></button>
                                    </div>
                                    -->
                                   	</fieldset>
									</form>
                                </div>
                            </div>
                         </div>
                    </div>
				</div>
            </div>
        </div>

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
		
		
		var base64EncodeChars = "ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789+/";  
		var base64DecodeChars = new Array(-1, -1, -1, -1, -1, -1, -1, -1, -1, -1, -1, -1, -1, -1, -1, -1, -1, -1, -1, -1, -1, -1, -1, -1, -1, -1, -1, -1, -1, -1, -1, -1, -1, -1, -1, -1, -1, -1, -1, -1, -1, -1, -1, 62, -1, -1, -1, 63, 52, 53, 54, 55, 56, 57, 58, 59, 60, 61, -1, -1, -1, -1, -1, -1, -1, 0, 1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12, 13, 14, 15, 16, 17, 18, 19, 20, 21, 22, 23, 24, 25, -1, -1, -1, -1, -1, -1, 26, 27, 28, 29, 30, 31, 32, 33, 34, 35, 36, 37, 38, 39, 40, 41, 42, 43, 44, 45, 46, 47, 48, 49, 50, 51, -1, -1, -1, -1, -1);  

		function base64encode(str){  
   			var out, i, len;  
    		var c1, c2, c3;  
    		len = str.length;  
    		i = 0;  
    		out = "";  
    		while (i < len) {  
        	c1 = str.charCodeAt(i++) & 0xff;  
        		if (i == len) {  
            		out += base64EncodeChars.charAt(c1 >> 2);  
            		out += base64EncodeChars.charAt((c1 & 0x3) << 4);  
            		out += "==";  
            		break;  
        		}  
       		 	c2 = str.charCodeAt(i++);  
        		if (i == len) {  
            		out += base64EncodeChars.charAt(c1 >> 2);  
            		out += base64EncodeChars.charAt(((c1 & 0x3) << 4) | ((c2 & 0xF0) >> 4));  
            		out += base64EncodeChars.charAt((c2 & 0xF) << 2);  
            		out += "=";  
            		break;  
        		}  
        		c3 = str.charCodeAt(i++);  
        		out += base64EncodeChars.charAt(c1 >> 2);  
        		out += base64EncodeChars.charAt(((c1 & 0x3) << 4) | ((c2 & 0xF0) >> 4));  
        		out += base64EncodeChars.charAt(((c2 & 0xF) << 2) | ((c3 & 0xC0) >> 6));  
        		out += base64EncodeChars.charAt(c3 & 0x3F);  
    		}  
    		return out;  
		} 

        $(function() {
            
        });
		
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
		
		
		function save0()
		{
			var value = GetRadioValue("rsafe0");
			window.location.href = "safe_post.php?safe0="+value;
		}

		function save1()
		{
			var value = GetRadioValue("rsafe1");
			window.location.href = "safe_post.php?safe1="+value;
		}

		function save2()
		{
			var value = GetRadioValue("rsafe2");
			window.location.href = "safe_post.php?safe2="+value;
		}

		function save3()
		{
			var safe3 = document.getElementById("text_safe3").value;
			var safe4 = document.getElementById("text_safe4").value;
			window.location.href = "safe_post.php?safe10="+safe3+"&safe11="+safe4;
		}

		function save4()
		{
			var safe5 = document.getElementById("text_safe5").value;
			//alert(base64encode(safe5))
			window.location.href = "safe_post.php?safe12="+base64encode(safe5);
		}

		function save5()
		{
			var safe6 = document.getElementById("text_safe6").value;
			window.location.href = "safe_post.php?safe13="+safe6;	
		}

		function save6()
		{
			var safe7 = document.getElementById("text_safe7").value;
			window.location.href = "safe_post.php?safe14="+safe7;	
		}
		
		function save_server_request()
		{
			var server_request = document.getElementById("text_server_request").value;
			window.location.href = "safe_post.php?server_request="+server_request;	
		}
		
		function save_login_num()
		{
			var value = GetRadioValue("rloginnum");
			if(value == 2)
			{
				value = document.getElementById("text_login_num").value;	
			}
			window.location.href = "safe_post.php?rloginnum="+value;	
		}
		
		
		function save_model()
		{
			var model = document.getElementById("text_model").value;
			window.location.href = "safe_post.php?model="+model;	
		}
		
		function save_disabel_model()
		{
			var model = document.getElementById("text_disabel_model").value;
			window.location.href = "safe_post.php?disabel_model="+model;	
		}
		
		function save_logintime()
		{
			var logintime = 0;
			var value = 0;
				value = GetRadioValue("rlogin_time");
			if(value == 0)
			{
				logintime = 0;
			}
			else
			{
				logintime = document.getElementById("text_logintime").value;
				if(logintime <= 1)
				{
					alert("<?php echo $lang_array["safe_list_text18"] ?>");
					return;
				}
			}
			window.location.href = "safe_post.php?logintime="+logintime;
		}
		
		function save_unbundling()
		{
			var value = GetRadioValue("unbundling");
			window.location.href = "safe_post.php?unbundling="+value;	
		}
		
		function limitarea()
		{
			var value = document.getElementById("limitarea_id").value;
			if(GetRadioValue("limitarea_radio") == "1" && value.length <= 0)
			{
				alert("<?php echo $lang_array["safe_list_text24"]; ?>");
				return;
			}
			
			window.location.href = "safe_post.php?limitarea="+value;
		}
		
		function limitsign()
		{
			
			var value = document.getElementById("limitsign_id").value;
			if(GetRadioValue("limitsign_radio") == "1" && value.length <= 0)
			{
				alert("<?php echo $lang_array["safe_list_text36"]; ?>");
				return;
			}
			
			window.location.href = "safe_post.php?limitsign="+value;
		}
		
		function limitarea_change(value)
		{
			if(value == 0)
			{
				document.getElementById("limitarea_id").value = "";
			}
			else if(value == 1)
			{
				document.getElementById("limitarea_id").value = "<?php if($limitarea != null && strlen($limitarea) < 4) echo $limitarea;?>";
			}
		}
		
		function limitsign_change(value)
		{
			if(value == 0)
			{
				document.getElementById("limitsign_id").value = "";
			}
			else if(value == 1)
			{
				document.getElementById("limitsign_id").value = "<?php if($limitsign != null && strlen($limitsign) < 4) echo $limitsign;?>";
			}
		}
		
		function save_prekey()
		{
			var value = GetRadioValue("prekey");
			window.location.href = "safe_post.php?prekey="+value;	
		}
		
		function save_longtime()
		{
			var longtime = 0;
			var value = 0;
				value = GetRadioValue("rlongtime");
			if(value == 0)
			{
				longtime = 0;
			}
			else
			{
				longtime = document.getElementById("text_longtime").value;
				if(longtime <= 1)
				{
					alert("<?php echo $lang_array["safe_list_text18"] ?>");
					return;
				}
			}
			
			window.location.href = "safe_post.php?longtime="+longtime;			
			
		}
        </script>	

</body>
<?php
	$sql->disconnect_database();

?>
</html>
