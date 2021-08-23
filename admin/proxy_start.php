<!DOCTYPE html>
<?php
	include_once "cn_lang.php";
	include_once 'common.php';
	$sql = new DbSql();
	$sql->login();
	
	$sql->connect_database_default();
	$mydb = $sql->get_database();
	$sql->create_database($mydb);
	
	$mytable = "proxy_download_table";
	$sql->create_table($mydb, $mytable, "name text, sbackground text, sepg text, ebackground text, livepanel text, vodpanel text, download text, allow text, watermark text, scrolltext text, version int");
			
	$panal = $sql->query_data($mydb, $mytable, "name", $_GET["proxy"], "sepg");
	if($panal == null)
		$pannal = -1;
		
	/*
	$mytable = "start_load_table";
	$sql->create_table($mydb, $mytable, "tag text, value text");
	$loadbackground = $sql->query_data($mydb, $mytable, "tag", "background","value");
	*/
	
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
                                <div class="muted pull-left"><?php echo $lang_array["start_set_text1"] ?></div>
                            </div>
                            <div class="block-content collapse in">
                                <div class="span12">                                    
                                    <table cellpadding="0" cellspacing="0" border="0" class="table table-bordered">
                                        <thead>
                                            <tr>
            								<th width="8%" style="vertical-align:middle; text-align:center;"><?php echo $lang_array["live_panal_2_text2"] ?></th>
          									<th width="72%" style="vertical-align:middle; text-align:center;"><?php echo $lang_array["live_panal_2_text4"] ?></th>
            								<th width="20%" style="vertical-align:middle; text-align:center;"><?php echo $lang_array["live_panal_2_text5"] ?></th>
                                            </tr>
                                        </thead>
                                        
                                        <tr>
        								<td style='align:center; vertical-align:middle; text-align:center;'><input style="width:15px;height:28px;" type="radio" name="radio" id="radio0" value="-1" <?php if($panal == "-1") echo "checked";  ?>/></td>
  										<td style='align:center; vertical-align:middle; text-align:center;'><?php echo $lang_array["proxy_start_set_text1"] ?></td>							
        								<td>
                                        
        								<tr>
        								<td style='align:center; vertical-align:middle; text-align:center;'><input style="width:15px;height:28px;" type="radio" name="radio" id="radio1" value="0" <?php if($panal == "0") echo "checked";  ?>/></td>
  										<td style='align:center; vertical-align:middle; text-align:center;'><?php echo $lang_array["start_set_text2"] ?></td>							
        								<!--<td style='align:center; vertical-align:middle; text-align:center;'><a href='#' onclick='proxy_edit_background()'><?php echo $lang_array["start_set_text9"] ?></a>
                                        </td>-->
                                        <td style='align:center; vertical-align:middle; text-align:center;'></td>
       									</tr>
                                        
                                        <tr>
        								<td style='align:center; vertical-align:middle; text-align:center;'><input style="width:15px;height:28px;" type="radio" name="radio" id="radio2" value="1" <?php if($panal == "1") echo "checked";  ?>/></td>
  										<td style='align:center; vertical-align:middle; text-align:center;'><?php echo $lang_array["start_set_text3"] ?></td>
        								<td style='align:center; vertical-align:middle; text-align:center;'></td>
        								</tr>
        
        								<tr>
        								<td style='align:center; vertical-align:middle; text-align:center;'><input style="width:15px;height:28px;" type="radio" name="radio" id="radio3" value="2" <?php if($panal == "2") echo "checked";  ?>/></td>
  										<td style='align:center; vertical-align:middle; text-align:center;'><?php echo $lang_array["start_set_text4"] ?></td>
        								<td style='align:center; vertical-align:middle; text-align:center;'></td>
        								</tr>
        
                						<tr>
        								<td style='align:center; vertical-align:middle; text-align:center;'><input style="width:15px;height:28px;" type="radio" name="radio" id="radio3" value="6" <?php if($panal == "6") echo "checked";  ?>/></td>
  										<td style='align:center; vertical-align:middle; text-align:center;'><?php echo $lang_array["start_set_text10"] ?></td>
        								<td style='align:center; vertical-align:middle; text-align:center;'></td>
        								</tr>
                                        
                                    </table>
                                    
                                    <div class="form-actions">
   										<button type="button" class="btn btn-primary" onclick="save()"><?php echo $lang_array["save_text1"] ?></button>
                            		</div>
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
			//alert(document.getElementById("preview_radio").value);
			var check_id;
			var chkObjs = document.getElementsByName("radio");
			for(var i=0;i<chkObjs.length;i++){
				if(chkObjs[i].checked){
					check_id = chkObjs[i].value;
					break;
				}
			}

			window.location.href = "proxy_start_post.php?id=" + check_id + "&proxy=<?php echo $_GET["proxy"] ?>";
		}
		
		
		function proxy_edit_background()
		{
			window.location.href = "proxy_start_epg_background.php";
		}

		/*
		function edit_background()
		{
			window.location.href = "start_epg_background.php";
		}

		function edit_broadcast()
		{
			window.location.href = "start_broadcast_edit.php";
		}

		function recovery()
		{
			window.location.href = "start_load_del.php";	
		}
		*/
		
        </script>
    </body>

</html>