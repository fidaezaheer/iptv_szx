<!DOCTYPE html>
<?php
	include_once "cn_lang.php";
	include 'common.php';
	$sql = new DbSql();
	$sql->login();
	
	$asynupdate = 0;
	
	include_once 'memcache.php';
	$mem = new GMemCache();
	if($mem->used())
	{
		$mem->connect();
		$mem_asynupdate = intval($mem->get("asynupdate"));
		$mem->close();
		if($mem_asynupdate > 0)
			$asynupdate = 1;
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
                                <div class="muted pull-left"><?php echo $lang_array["left2_text6"] ?></div>
                            </div>
                            
                            <div class="block-content collapse in">
                                <div class="span12">
                                	<form class="form-horizontal" name="authform" method="post" action="option_post.php" enctype='multipart/form-data'>
                                    <fieldset><br/>

                                    <div class="control-group">
                                       <label class="control-label" for="focusedInput" style="text-shadow:#0CF"><?php echo $lang_array["option_list_text1"] ?></label>
                                       <div class="controls">
                                       <input style="width:15px;height:28px;" type="radio" name="asynupdate0" value="0" id="asynupdate0" <?php if($asynupdate == null || intval($asynupdate)==0) echo "checked"?> /><?php echo $lang_array["no_text1"] ?>&nbsp;
                                       <input style="width:15px;height:28px;" type="radio" name="asynupdate0" value="1" id="asynupdate1" <?php if(intval($asynupdate)==1) echo "checked"?> /><?php echo $lang_array["yes_text1"] ?>&nbsp;
                                       <input name="" class="btn btn-primary" type="button" value="<?php echo $lang_array["sure_text1"] ?>" onclick="save_asynupdate()"/>&nbsp;&nbsp;
                                       <input name="" class="btn btn-primary" type="button" value="<?php echo $lang_array["option_list_text4"] ?>" onclick="flash_asynupdate()"/>&nbsp;&nbsp;
                                       <?php echo $lang_array["option_list_text2"] ?>&nbsp;&nbsp;
                                       <input name="" class="btn btn-primary" type="button" value="<?php echo $lang_array["option_list_text5"] ?>" onclick="memcache_test()"/>&nbsp;&nbsp;
                                       </div>
                                   	</div>
                                    
                                    
                                    <input name="zone" id="zone" type="hidden" value=""/>

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
		
		
		function save_asynupdate()
		{
			var asynupdate = GetRadioValue("asynupdate0");
			window.location.href = "options_post.php?asynupdate=" + asynupdate;
		}
		
		function flash_asynupdate()
		{
			window.open("options_asyncupdate_flash.php", 'newwindow', 'height=280, width=550, top=300, left=300, toolbar=no, menubar=no, scrollbars=no, resizable=no,location=n o, status=no');
		}
		
		function memcache_test()
		{
			window.open("options_memcache_test.php", 'newwindow', 'height=150, width=550, top=300, left=300, toolbar=no, menubar=no, scrollbars=no, resizable=no,location=n o, status=no');
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
<?php
	$sql->disconnect_database();

?>
</html>