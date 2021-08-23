<!DOCTYPE html>
<?php
	include_once "cn_lang.php";
	include_once "common.php";
	$sql = new DbSql();
	$sql->login();

	$mytable = "ad_live_list_table";
	$sql->connect_database_default();
	$mydb = $sql->get_database();
	$sql->create_database($mydb);
	$sql->create_table($mydb, $mytable, "id int, image text");
	$namess = $sql->fetch_datas($mydb, $mytable);
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
                                <div class="muted pull-left"><?php echo $lang_array["update_ad_live_list_text1"] ?></div>
                            </div>
                            
                            <div class="block-content collapse in">
                                <div class="span12">
                                	<form class="form-horizontal" method="post" action="update_ad_live_post.php" enctype='multipart/form-data'>
                                    <fieldset><br/>
                                    <div class="control-group">
                                       <label class="control-label" for="focusedInput"><?php echo $lang_array["update_ad_live_list_text1"] ?></label>
                                       <div class="controls">
                                          <input class="input-file uniform_on" id="file" name="file" type="file">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                          <button type="submit" class="btn btn-primary"><?php echo $lang_array["update_ad_live_list_text5"] ?></button>
                                       </div>
                                   	</div>
                                    
                                    <table cellpadding="0" cellspacing="0" border="0" class="table table-striped table-bordered">
                                        <thead>
                                            <tr>
            									<th width="43%" style="vertical-align:middle; text-align:center;"><?php echo $lang_array["update_ad_live_list_text2"] ?></th>
            									<th width="43%" style="vertical-align:middle; text-align:center;"><?php echo $lang_array["update_ad_live_list_text3"] ?></th>
                                            </tr>
                                        </thead>
<?php
			echo "<tbody>";
			foreach($namess as $names) {
				echo "<tr class='odd gradeA'>";
				echo "<td style='vertical-align:middle; text-align:center;'><img src='" . "../images/background/" . $names[1] . "' width='36' height='36' /></td>";
				echo "<td style='vertical-align:middle; text-align:center;'><a href='#' onclick='delete_image(\"".$names[0]."\")'>".$lang_array["del_text1"]."</a></td>";
				echo "</tr>";
			}
			echo "</tbody>";
?>                                       
                                    </table>
                                    
                                    <div class="form-actions">
                                		<button type="reset" class="btn" onclick="back_page()"><?php echo $lang_array["back_text1"] ?></button>
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
		
		function delete_image(id)
		{
			var r=confirm("<?php echo $lang_array["update_ad_live_list_text4"] ?>" +　"?");
			if(r==true)
  			{
				window.location.href = "update_ad_live_del.php?id=" + id;
			}
		}
		
		function back_page()
		{
			window.location.href = "live_panal_edit.php";
				
		}
		
        </script>
    </body>

</html>
<?php
	$sql->disconnect_database();

?>
