<!DOCTYPE html>
<?php
	include_once "cn_lang.php";
	include 'common.php';
	$sql = new DbSql();
	$sql->login();
	
	$mydb = $sql->get_database();
	$sql->connect_database_default();
	$sql->create_database($mydb);
	
	$mytable = "version_table";
	$sql->create_table($mydb, $mytable, "name longtext, value longtext");
	$net_version = $sql->query_data($mydb, $mytable, "name", "version", "value");
	$net_addr = $sql->query_data($mydb, $mytable, "name", "addr", "value");
	$proxy = $sql->query_data($mydb, $mytable, "name", "proxy", "value");
	$timeout = $sql->query_data($mydb, $mytable, "name", "timeout", "value");
	$expire = $sql->query_data($mydb, $mytable, "name", "expire", "value");
	$expire_text = $sql->query_data($mydb, $mytable, "name", "expiretext", "value");
	$update_model = $sql->query_data($mydb, $mytable, "name", "update_model", "value");
	$account_tip = $sql->query_data($mydb, $mytable, "name", "account_tip", "value");
	$expire_times = $sql->query_data($mydb, $mytable, "name", "expiretimes", "value");
	$libforcetv_version = $sql->query_data($mydb, $mytable, "name", "libforcetv_version", "value");
	$libforcetv_open = $sql->query_data($mydb, $mytable, "name", "libforcetv_open", "value");
	$libforcetv_md5 = $sql->query_data($mydb, $mytable, "name", "libforcetv_md5", "value");
	
	if($expire_text == null)
		$expire_text = $lang_array["version_text12"];
	
	if($expire_times == null)
		$expire_times = "10";
			
	$mytable = "proxy_table";
	$sql->create_table($mydb, $mytable, "name text, password text");
	$namess = $sql->fetch_datas($mydb, $mytable);
	
	$mytable = "start_epg_table";
	$sql->create_table($mydb, $mytable, "tag text, value text");
	$epglist = $sql->query_data($mydb, $mytable, "tag", "epglist","value");
	
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
                                <div class="muted pull-left"><?php echo $lang_array["version_text1"] ?> : <a href="version.php">双子星升级</a> | <a href="version_1.php">世纪壳升级</a> | <a href="version_2.php">MTV壳升级</a></div>
                            </div>
                            
                            <div class="block-content collapse in">
                                <div class="span12">
                                	<form class="form-horizontal" name="authform" method="post" action="version_post.php" enctype='multipart/form-data'>
                                    <fieldset><br/>
                                 	<div class="control-group">
                                       <label class="control-label" for="focusedInput" style="text-shadow:#0CF"><?php echo $lang_array["version_text2"] ?></label>
                                       <div class="controls">
                                       <label class="control-label" for="focusedInput" style="text-align:left;"><?php echo $net_version; ?></label>
                                       </div>
                                   	</div>
                                    
                                    <div class="control-group">
                                       <label class="control-label" for="focusedInput" style="text-shadow:#0CF"><?php echo $lang_array["version_text3"] ?></label>
                                       <div class="controls">
                                       <input class="input-mini focused" id="versionfield" name="versionfield" type="text" value="<?php echo $net_version ?>">
                                       </div>
                                   	</div>
                                    
                                    
                                    <div class="control-group">
                                       <label class="control-label" for="focusedInput" style="text-shadow:#0CF"><?php echo $lang_array["version_text13"] ?></label>
                                       <div class="controls">
										<input style="width:15px;height:28px;" type="radio" name="radio_update_model" id="radio0_update_model" value="0" <?php if($update_model == null || strcmp($update_model,"0") == 0) echo "checked"?>/><?php echo $lang_array["version_text14"] ?>
                                       &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
        							   <input style="width:15px;height:28px;" type="radio" name="radio_update_model" id="radio1_update_model" value="1" <?php if(strcmp($update_model,"1") == 0) echo "checked"?>/><?php echo $lang_array["version_text15"] ?>
                                       &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                       <input style="width:15px;height:28px;" type="radio" name="radio_update_model" id="radio1_update_model" value="2" <?php if(strcmp($update_model,"2") == 0) echo "checked"?>/><?php echo $lang_array["version_text19"] ?>
                                       </div>
                                   	</div>
                                    
                                    <div class="control-group">
                                       <label class="control-label" for="focusedInput" style="text-shadow:#0CF"><?php echo $lang_array["version_text4"] ?></label>
                                       <div class="controls">
                                       <input class="input-xxlarge focused" id="updatefield" name="updatefield" type="text" value="<?php echo $net_addr ?>">
                                       </div>
                                   	</div>
                                    
                                    <div class="control-group">
                                       <label class="control-label" for="focusedInput" style="text-shadow:#0CF"><?php echo $lang_array["version_text6"] ?></label>
                                       <div class="controls">
                                       <input class="input-xxlarge focused" id="epglist" name="epglist" type="text" value="<?php echo $epglist ?>">
                                       </div>
                                   	</div>
                                    
                                    <div class="control-group">
                                       <label class="control-label" for="focusedInput" style="text-shadow:#0CF"><?php echo $lang_array["version_text7"] ?></label>
                                       <div class="controls">
                                       <input style="width:15px;height:28px;" type="radio" name="radio_timeout" id="radio0_timeoutid" value="-1" <?php if($timeout == null || strcmp($timeout,"-1") == 0) echo "checked"?>/><?php echo $lang_array["no_text1"] ?>
                                       &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
        							   <input style="width:15px;height:28px;" type="radio" name="radio_timeout" id="radio1_timeoutid" value="1" <?php if(intval($timeout) > 10) echo "checked"?>/><?php echo $lang_array["yes_text1"] ?>
                                       &nbsp;&nbsp;&nbsp;
                                       <?php echo $lang_array["version_text8"] ?>:
                                       &nbsp;
                                       <input class="input-mini focused" name="text_timeout" type="text" id="text_timeoutid" size="6" value="<?php if(intval($timeout) > 10) echo $timeout ?>"/>&nbsp;<?php echo $lang_array["version_text9"] ?>                                     
                                       </div>
                                   	</div>
                                    
                                    <div class="control-group">
                                       <label class="control-label" for="focusedInput" style="text-shadow:#0CF"><?php echo $lang_array["version_text10"] ?></label>
                                       <div class="controls">
                                       <input style="width:15px;height:28px;" type="radio" name="radio_expire" id="radio0_expire" value="0" <?php if($expire == null || intval($expire) == 0) echo "checked"?>/><?php echo $lang_array["no_text1"] ?>
                                       &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
        							   <input style="width:15px;height:28px;" type="radio" name="radio_expire" id="radio1_expire" value="1" <?php if(intval($expire) == 1) echo "checked"?>/><?php echo $lang_array["yes_text1"] ?>
                                       &nbsp;&nbsp;&nbsp;
                                       <?php echo $lang_array["version_text18"] ?>:
                                       <input class="input-mini focused" name="expire_times" type="text" id="expire_times" value="<?php echo $expire_times ?>"/>
                                       &nbsp;&nbsp;&nbsp;
                                       <?php echo $lang_array["version_text11"] ?>:
                                       &nbsp;
                                       <input class="input-xxlarge focused" name="expire_text" type="text" id="expire_text" value="<?php echo $expire_text ?>"/>                                    
                                       </div>
                                   	</div>
                                    
                                    <div class="control-group">
                                       <label class="control-label" for="focusedInput" style="text-shadow:#0CF"><?php echo $lang_array["version_text16"] ?></label>
                                       <div class="controls">
                                       <input class="input-xxlarge focused" id="account_tip" name="account_tip" type="text" value="<?php echo $account_tip ?>">
                                       </div>
                                   	</div>
                                    
                                    <div class="control-group">
                                       <label class="control-label" for="focusedInput" style="text-shadow:#0CF"><?php echo $lang_array["version_text20"] ?></label>
                                       <div class="controls">
                                       		<input style="width:15px;height:28px;" type="radio" name="radio_libforcetv_open" id="radio0_libforcetv_open" value="0" <?php if($libforcetv_open == null || strcmp($libforcetv_open,"0") == 0) echo "checked"?>/><?php echo $lang_array["no_text1"] ?>
                                       		&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
        							   		<input style="width:15px;height:28px;" type="radio" name="radio_libforcetv_open" id="radio1_libforcetv_open" value="1" <?php if(intval($libforcetv_open) > 0) echo "checked"?>/><?php echo $lang_array["yes_text1"] ?>
                                            <input class="input-file uniform_on" id="libforcetv_file" name="libforcetv_file" type="file">
                                            &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                            <?php if(intval($libforcetv_open) > 0) echo $lang_array["version_text22"] . ":" . date("Y-m-d H:m:s",$libforcetv_version) ?>
                                            &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                            <?php if(intval($libforcetv_open) > 0) echo $lang_array["version_text23"] . ":" . $libforcetv_md5 ?>
                                       </div>
                                   	</div>
                                    
                                    <div class="form-actions">
                                    	<button type="submit" class="btn btn-primary" onclick="save()"><?php echo $lang_array["save_text1"] ?></button>
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
		
		function update_lib()
		{	
			alert("update lib");
			document.libform.submit();
		}
        </script>	

</body>
<?php
	$sql->disconnect_database();

?>
</html>
