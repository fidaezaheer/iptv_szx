<!DOCTYPE html>
<?php
include_once "cn_lang.php";
include_once "common.php";
$sql = new DbSql();
$sql->login();

	$mytable = "scroll_table";
	$sql->connect_database_default();
	$mydb = $sql->get_database();
	$sql->create_database($mydb);
	$sql->create_table($mydb, $mytable, "name longtext, value longtext");
	$txt = $sql->query_data($mydb, $mytable, "name", "scroll_text", "value");
	$txt_invalid = $sql->query_data($mydb, $mytable, "name", "txt_invalid", "value");
	$add_mac = $sql->query_data($mydb, $mytable, "name", "add_mac", "value");
	$add_cpuid = $sql->query_data($mydb, $mytable, "name", "add_cpuid", "value");
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
                                <div class="muted pull-left"><?php echo $lang_array["left3_text1"] ?></div>
                            </div>
                            
                            <div class="block-content collapse in">
                                <div class="span12">
                                	<form class="form-horizontal" method="post" action="scroll_post.php" name="authform" enctype='multipart/form-data'>
                                    <fieldset>                                
                                    <div class="control-group">
                                       <label class="control-label" for="focusedInput"><?php echo $lang_array["scroll_edit_text1"] ?></label>
                                       <div class="controls">
                                          <input class="input-xxlarge" id="txt_subtitle" name="txt_subtitle" type="text" style="width: 800px" value="<?php echo $txt ?>">
                                          &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                          <input class='uniform_on' name='scheckbox' type='checkbox' style='width:15px;height:25px;' value='0' <?php if($add_mac == 1) echo "checked"; ?>/><?php echo $lang_array["scroll_edit_text3"] ?>
                                          &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                          <input class='uniform_on' name='scheckbox' type='checkbox' style='width:15px;height:25px;' value='1' <?php if($add_cpuid == 1) echo "checked"; ?>/><?php echo $lang_array["scroll_edit_text4"] ?>
                                       </div>
                                   	</div>      
                                    
                                    <div class="control-group">
                                       <label class="control-label" for="focusedInput"><?php echo $lang_array["scroll_edit_text2"] ?></label>
                                       <div class="controls">
                                          <input class="input-xxlarge" id="txt_invalid" name="txt_invalid" type="text" style="width: 800px" value="<?php echo $txt_invalid ?>">
                                       </div>
                                   	</div> 
                                                                  
                                    <div class="form-actions">
   										<button type="button" class="btn btn-primary" onClick="save()"><?php echo $lang_array["save_text1"] ?></button>
                            		</div>
                                   	</fieldset>
                                    <input name="mac_checkbox" id="mac_checkbox" type="hidden" value=""/>
                                    <input name="cpuid_checkbox" id="cpuid_checkbox" type="hidden" value=""/>
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
		
		function delete_user(name)
		{
			if(confirm("是否删除代理商： " + name + " ?") == true)
  			{
				var url = "proxy_del.php?name=" + name;
				window.location.href = url;
  			}
		}

		function edit_user(name)
		{
			//if(confirm("是否删除用户： " + name + " ?") == true)
  			{
				var url = "proxy_edit.php?name=" + name;
				window.location.href = url;
  			}
		}

		function edit_proxy(name)
		{
			//window.location.href = "version_proxy_edit.php?proxy=" + name;
			var url = "proxy_edit.php?proxy=" + name;
			window.location.href = url;
		}
		
		function save()
		{
			document.authform.mac_checkbox.value = 0;
			document.authform.cpuid_checkbox.value = 0;
			
			var scheckboxs = document.getElementsByName("scheckbox");
			for(var i = 0; i < scheckboxs.length; i++)
			{
				if(scheckboxs[i].type == "checkbox" && scheckboxs[i].checked)
				{
					if(i == 0)
						document.authform.mac_checkbox.value = 1;
					else if(i == 1)
						document.authform.cpuid_checkbox.value = 1;
				}
			}
			document.authform.submit();
		}
        </script>
    </body>

</html>