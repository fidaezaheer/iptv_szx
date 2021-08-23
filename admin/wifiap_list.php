<!DOCTYPE html>
<?php
	include_once "cn_lang.php";
	include_once "common.php";
	$sql = new DbSql();
	$sql->login();

	$mytable = "version_table";
	$sql->connect_database_default();
	$mydb = $sql->get_database();
	$sql->create_database($mydb);
	$sql->create_table($mydb, $mytable, "name longtext, value longtext");
	
	$ap_enable = $sql->query_data($mydb, $mytable, "name", "ap_enable", "value");
	$ap_ssid = $sql->query_data($mydb, $mytable, "name", "ap_ssid", "value");
	$ap_password = $sql->query_data($mydb, $mytable, "name", "ap_password", "value");
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
    </head>
    
    <body>
        <div class="container-fluid">
            <div class="row-fluid">
              <div class="span13" id="content">
                <div class="row-fluid">
                   <!-- block -->
						<div class="block">
                            <div class="navbar navbar-inner block-header">
                                <div class="muted pull-left"><?php echo $lang_array["left5_text5"] ?></div>
                            </div>
                            <div class="block-content collapse in">
                                <div class="span12">               
                                
                                	<form class="form-horizontal" name="authform" method="post" action="version_post.php" enctype='multipart/form-data'>
                                    <fieldset><br/>
                                    
                                    <div class="control-group">
                                       <label class="control-label" for="focusedInput" style="text-shadow:#0CF"><?php echo $lang_array["wifiap_text5"] ?></label>
                                       <div class="controls">
										<input style="width:15px;height:28px;" type="radio" name="radio_ap_enable" id="radio0_ap_enable" value="1" <?php if($ap_enable == null || strcmp($ap_enable,"1") == 0) echo "checked"?>/><?php echo $lang_array["wifiap_text1"] ?>
                                       &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
        							   <input style="width:15px;height:28px;" type="radio" name="radio_ap_enable" id="radio1_ap_enable" value="0" <?php if(strcmp($ap_enable,"0") == 0) echo "checked"?>/><?php echo $lang_array["wifiap_text2"] ?>
                                       
                                       &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                       <?php echo $lang_array["wifiap_text6"] ?>
                                       </div>
                                   	</div>
                                    
                                    <div class="control-group">
                                       <label class="control-label" for="focusedInput" style="text-shadow:#0CF"><?php echo $lang_array["wifiap_text3"] ?></label>
                                       <div class="controls">
                                       <input class="input-large focused" id="ssid" name="ssid" type="text" value="<?php echo $ap_ssid ?>">
                                       </div>
                                   	</div>
                                    
                                    <div class="control-group">
                                       <label class="control-label" for="focusedInput" style="text-shadow:#0CF"><?php echo $lang_array["wifiap_text4"] ?></label>
                                       <div class="controls">
                                       <input class="input-large focused" id="ps" name="ps" type="text" value="<?php echo $ap_password ?>">
                                       </div>
                                   	</div>
                                    
                                    
                                    <div class="form-actions">
                                    	<button type="button" class="btn btn-primary" onclick="save()"><?php echo $lang_array["save_text1"] ?></button>
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
        
        <script src="bootstrap/js/bootstrap.min.js"></script>
        <script src="vendors/datatables/js/jquery.dataTables.min.js"></script>


        <script src="assets/scripts.js"></script>
        <script src="assets/DT_bootstrap.js"></script>
        
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
        		for(i=0;i<obj.length;i++)
				{
           	 		if(obj[i].checked){
                		return obj[i].value;
            		}
        		}
    		}
    		return null;
		}

		function save()
		{
			var enable = GetRadioValue("radio_ap_enable");
			var ssid = document.getElementById("ssid").value;
			var password = document.getElementById("ps").value;
			
			var cmd = "wifiap_post.php?enable=" + enable + "&ssid=" + ssid + "&password=" +password;
			window.location.href = cmd;			
		}
        </script>
    </body>

</html>

<?php
	$sql->disconnect_database();

?>