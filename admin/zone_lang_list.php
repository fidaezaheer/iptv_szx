<!DOCTYPE html>
<?php
	include_once "cn_lang.php";
	include 'common.php';
	$sql = new DbSql();
	$sql->login();
	
	$mydb = $sql->get_database();
	$sql->connect_database_default();
	$sql->create_database($mydb);
	
	$mytable = "system_table";
	$sql->create_table($mydb, $mytable, "name text, value text");
	$zone = $sql->query_data($mydb, $mytable, "name", "zone", "value");
	$terlang = $sql->query_data($mydb, $mytable, "name", "terlang", "value");
?>	


<?php
function zone_select($zone1,$zone2)
{
	if(strcmp($zone1,$zone2) == 0)
	{
		return "selected='selected'";
	}
	else
	{
		return "";	
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
                                <div class="muted pull-left"><?php echo $lang_array["left2_text5"] ?></div>
                            </div>
                            
                            <div class="block-content collapse in">
                                <div class="span12">
                                	<form class="form-horizontal" name="authform" method="post" action="zone_lang_post.php" enctype='multipart/form-data'>
                                    <fieldset><br/>

                                    <div class="control-group">
                                       <label class="control-label" for="focusedInput" style="text-shadow:#0CF"><?php echo $lang_array["zone_lang_list_text1"] ?></label>
                                       <div class="controls">
                                       <select id="zone_id" name=""  style='width:600px;' onchange="">
											<?php
											echo "<option value='Etc/GMT-0' style='width:600px;'" . zone_select('Etc/GMT-0',$zone) . ">" . $lang_array["zone_text0"] . "</option>";
											echo "<option value='Etc/GMT-1' style='width:600px;'" . zone_select('Etc/GMT-1',$zone) . ">" . $lang_array["zone_text1"] . "</option>";
											echo "<option value='Etc/GMT-2' style='width:600px;'" . zone_select('Etc/GMT-2',$zone) . ">" . $lang_array["zone_text2"] . "</option>";
											echo "<option value='Etc/GMT-3' style='width:600px;'" . zone_select('Etc/GMT-3',$zone) . ">" . $lang_array["zone_text3"] . "</option>";
											echo "<option value='Etc/GMT-4' style='width:600px;'" . zone_select('Etc/GMT-4',$zone) . ">" . $lang_array["zone_text4"] . "</option>";
											echo "<option value='Etc/GMT-5' style='width:600px;'" . zone_select('Etc/GMT-5',$zone) . ">" . $lang_array["zone_text5"] . "</option>";
											echo "<option value='Etc/GMT-6' style='width:600px;'" . zone_select('Etc/GMT-6',$zone) . ">" . $lang_array["zone_text6"] . "</option>";
											echo "<option value='Etc/GMT-7' style='width:600px;'" . zone_select('Etc/GMT-7',$zone) . ">" . $lang_array["zone_text7"] . "</option>";
											echo "<option value='Etc/GMT-8' style='width:600px;'" . zone_select('Etc/GMT-8',$zone) . ">" . $lang_array["zone_text8"] . "</option>";
											echo "<option value='Etc/GMT-9' style='width:600px;'" . zone_select('Etc/GMT-9',$zone) . ">" . $lang_array["zone_text9"] . "</option>";
											echo "<option value='Etc/GMT-10' style='width:600px;'" . zone_select('Etc/GMT-10',$zone) . ">" . $lang_array["zone_text10"] . "</option>";
											echo "<option value='Etc/GMT-11' style='width:600px;'" . zone_select('Etc/GMT-11',$zone) . ">" . $lang_array["zone_text11"] . "</option>";
											echo "<option value='Etc/GMT-12' style='width:600px;'" . zone_select('Etc/GMT-12',$zone) . ">" . $lang_array["zone_text12"] . "</option>";
											echo "<option value='Etc/GMT+1' style='width:600px;'" . zone_select('Etc/GMT+1',$zone) . ">" . $lang_array["zone_text13"] . "</option>";
											echo "<option value='Etc/GMT+2' style='width:600px;'" . zone_select('Etc/GMT+2',$zone) . ">" . $lang_array["zone_text14"] . "</option>";
											echo "<option value='Etc/GMT+3' style='width:600px;'" . zone_select('Etc/GMT+3',$zone) . ">" . $lang_array["zone_text15"] . "</option>";
											echo "<option value='Etc/GMT+4' style='width:600px;'" . zone_select('Etc/GMT+4',$zone) . ">" . $lang_array["zone_text16"] . "</option>";
											echo "<option value='Etc/GMT+5' style='width:600px;'" . zone_select('Etc/GMT+5',$zone) . ">" . $lang_array["zone_text17"] . "</option>";
											echo "<option value='Etc/GMT+6' style='width:600px;'" . zone_select('Etc/GMT+6',$zone) . ">" . $lang_array["zone_text18"] . "</option>";
											echo "<option value='Etc/GMT+7' style='width:600px;'" . zone_select('Etc/GMT+7',$zone) . ">" . $lang_array["zone_text19"] . "</option>";
											echo "<option value='Etc/GMT+8' style='width:600px;'" . zone_select('Etc/GMT+8',$zone) . ">" . $lang_array["zone_text20"] . "</option>";
											echo "<option value='Etc/GMT+9' style='width:600px;'" . zone_select('Etc/GMT+9',$zone) . ">" . $lang_array["zone_text21"] . "</option>";
											echo "<option value='Etc/GMT+10' style='width:600px;'" . zone_select('Etc/GMT+10',$zone) . ">" . $lang_array["zone_text22"] . "</option>";
											echo "<option value='Etc/GMT+11' style='width:600px;'" . zone_select('Etc/GMT+11',$zone) . ">" . $lang_array["zone_text23"] . "</option>";
											echo "<option value='Etc/GMT+12' style='width:600px;'" . zone_select('Etc/GMT+12',$zone) . ">" . $lang_array["zone_text24"] . "</option>";
											?>
    									</select>
                                       </div>
                                   	</div>
                                    
                                    <div class="control-group">
                                       <label class="control-label" for="focusedInput" style="text-shadow:#0CF"><?php echo $lang_array["zone_lang_list_text2"] ?></label>
                                       <div class="controls">
                                       <select id="terlang_id" name="terlang_id"  style='width:360px;' onchange="">
											<?php
												if($terlang == "zh_cn" || $terlang == null)
													echo "<option value='zh_cn' style='width:360px;' selected='selected'>" . $lang_array["zone_lang_list_text3"] . "</option>";
												else
													echo "<option value='zh_cn' style='width:360px;'>" . $lang_array["zone_lang_list_text3"] . "</option>";
												
												if($terlang == "english")	
													echo "<option value='english' style='width:360px;' selected='selected'>" . $lang_array["zone_lang_list_text4"] . "</option>";
												else
													echo "<option value='english' style='width:360px;'>" . $lang_array["zone_lang_list_text4"] . "</option>";
													
												if($terlang == "tw_cn")		
													echo "<option value='tw_cn' style='width:360px;' selected='selected'>" . $lang_array["zone_lang_list_text5"] . "</option>";
												else
													echo "<option value='tw_cn' style='width:360px;'>" . $lang_array["zone_lang_list_text5"] . "</option>";
													
												if($terlang == "hk_cn")		
													echo "<option value='hk_cn' style='width:360px;' selected='selected'>" . $lang_array["zone_lang_list_text6"] . "</option>";
												else
													echo "<option value='hk_cn' style='width:360px;'>" . $lang_array["zone_lang_list_text6"] . "</option>";
													
												if($terlang == "kr")
													echo "<option value='kr' style='width:360px;' selected='selected'>" . $lang_array["zone_lang_list_text7"] . "</option>";
												else
													echo "<option value='kr' style='width:360px;'>" . $lang_array["zone_lang_list_text7"] . "</option>";
													
												if($terlang == "ja")	
													echo "<option value='ja' style='width:360px;' selected='selected'>" . $lang_array["zone_lang_list_text8"] . "</option>";
												else
													echo "<option value='ja' style='width:360px;'>" . $lang_array["zone_lang_list_text8"] . "</option>";
													
												if($terlang == "spain")	
													echo "<option value='spain' style='width:360px;' selected='selected'>" . $lang_array["zone_lang_list_text9"] . "</option>";
												else
													echo "<option value='spain' style='width:360px;'>" . $lang_array["zone_lang_list_text9"] . "</option>";
													
												if($terlang == "portugal")	
													echo "<option value='portugal' style='width:360px;' selected='selected'>" . $lang_array["zone_lang_list_text10"] . "</option>";
												else
													echo "<option value='portugal' style='width:360px;'>" . $lang_array["zone_lang_list_text10"] . "</option>";
													
												if($terlang == "auto")	
													echo "<option value='auto' style='width:360px;' selected='selected'>" . $lang_array["zone_lang_list_text11"] . "</option>";
												else
													echo "<option value='auto' style='width:360px;'>" . $lang_array["zone_lang_list_text11"] . "</option>";
											?>
    									</select>
                                       </div>
                                   	</div>
                                    
                                    <input name="zone" id="zone" type="hidden" value=""/>
                                    
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
		
		
		function save()
		{
			var zone = document.getElementById("zone_id").value;
			document.authform.zone.value = zone;
			document.authform.submit();	
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
