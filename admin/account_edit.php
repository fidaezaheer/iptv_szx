<!DOCTYPE html>
<?php
	include_once "cn_lang.php";
	include_once 'common.php';
	set_time_limit(1800);
	$sql = new DbSql();
	$sql->login();

	$sql->connect_database_default();
	$mydb = $sql->get_database();
	$sql->create_database($mydb);
	
	$mytable = "account_table";
	$sql->create_table($mydb, $mytable, "id int, playlist text, days text, proxy text, export int, used int, mac text, cpuid text, cdkey text, showtime text, type text, member text");
	
	$id = $_GET["edit"];
	
	$member = $sql->query_data($mydb, $mytable, "id", $id,"member");
	
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
    </head>
    
    <body>
       <div class="container-fluid">
            <div class="row-fluid">
              	<div class="span13" id="content">
                <div class="row-fluid">
                   <!-- block -->
				  <div class="block">
                          <div class="navbar navbar-inner block-header">
                                <div class="muted pull-left"><?php echo $lang_array["account_edit_text1"] ?></div>
                            </div>
                            
                            <div class="block-content collapse in">
                                <div class="span12">
                                	<form class="form-horizontal" method="post" action="scroll_post.php" enctype='multipart/form-data'>
                                    <fieldset>                                
                                    <div class="control-group">
                                       <label class="control-label" for="focusedInput"><?php echo $lang_array["account_edit_text2"] ?></label>
                                       <div class="controls">
                                          <input class="input-xxlarge" id="member_id" name="member_id" type="text" style="width: 800px" value="<?php echo $member; ?>">
                                       </div>
                                   	</div>    
                                                                    
                                    <div class="form-actions">
                                    	<button type="button" class="btn btn-primary" onclick="save()"><?php echo $lang_array["save_text1"] ?></button>
                                    	<input name="" class="btn btn-primary" type="button" value="<?php echo $lang_array["back_text1"] ?>" onclick="back_page()"/>
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
		
		function save()
		{
			var value = document.getElementById("member_id").value;
			window.location.href = "account_edit_post.php?id=<?php echo $id?>&member="+value+"&page=<?php echo $_GET["page"]?>";
		}

		function back_page()
		{
			window.location.href = "account_list.php?page=<?php echo $_GET["page"] ?>";
	
		}
        </script>
    </body>

</html>