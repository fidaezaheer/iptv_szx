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
	$net_version = $sql->query_data($mydb, $mytable, "name", "versionCode", "value");
	$net_versionName = $sql->query_data($mydb, $mytable, "name", "versionName", "value");
	$net_addr = $sql->query_data($mydb, $mytable, "name", "apkUrl", "value");
	$net_message = $sql->query_data($mydb, $mytable, "name", "message", "value");
	$net_filemd5 = $sql->query_data($mydb, $mytable, "name", "filemd5", "value");
	$update_model = $sql->query_data($mydb, $mytable, "name", "forceupdate", "value");
	$net_fileSize = $sql->query_data($mydb, $mytable, "name", "fileSize", "value");	
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
                                	<form class="form-horizontal" name="authform" method="post" action="version_1_post.php" enctype='multipart/form-data'>
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
                                       <input class="input-mini focused" id="versionCode" name="versionCode" type="text" value="<?php echo $net_version ?>">
                                       </div>
                                   	</div>
                                    
                                    <div class="control-group">
                                       <label class="control-label" for="focusedInput" style="text-shadow:#0CF">版本名称</label>
                                       <div class="controls">
                                       <input class="input-xxlarge focused" id="versionName" name="versionName" type="text" value="<?php echo $net_versionName ?>">
                                       </div>
                                   	</div>
									
                                    <div class="control-group">
                                       <label class="control-label" for="focusedInput" style="text-shadow:#0CF"><?php echo $lang_array["version_text13"] ?></label>
                                       <div class="controls">
										<input style="width:15px;height:28px;" type="radio" name="radio_update_model" id="radio0_update_model" value="0" <?php if($update_model == null || strcmp($update_model,"0") == 0) echo "checked"?>/>提示
                                       &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
        							   <input style="width:15px;height:28px;" type="radio" name="radio_update_model" id="radio1_update_model" value="1" <?php if(strcmp($update_model,"1") == 0) echo "checked"?>/>强制
                   
                                       </div>
                                   	</div>
                                    
									<div class="control-group">
                                       <label class="control-label" for="focusedInput" style="text-shadow:#0CF">更新内容</label>
                                       <div class="controls">
                                       <input class="input-xxlarge focused" id="message" name="message" type="text" value="<?php echo $net_message ?>">
                                       </div>
                                   	</div>
									
									<div class="control-group">
                                       <label class="control-label" for="focusedInput" style="text-shadow:#0CF">文件大小</label>
                                       <div class="controls">
                                       <input class="input-xxlarge focused" id="fileSize" name="fileSize" type="text" value="<?php echo $net_fileSize ?>">
                                       </div>
                                   	</div>
									
									<div class="control-group">
                                       <label class="control-label" for="focusedInput" style="text-shadow:#0CF">软件MD5值</label>
                                       <div class="controls">
                                       <input class="input-xxlarge focused" id="filemd5" name="filemd5" type="text" value="<?php echo $net_filemd5 ?>">
                                       </div>
                                   	</div>
									
                                    <div class="control-group">
                                       <label class="control-label" for="focusedInput" style="text-shadow:#0CF"><?php echo $lang_array["version_text4"] ?></label>
                                       <div class="controls">
                                       <input class="input-xxlarge focused" id="updatefield" name="updatefield" type="text" value="<?php echo $net_addr ?>">
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
