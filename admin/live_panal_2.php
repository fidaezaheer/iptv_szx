<!DOCTYPE html>
<?php
include_once "cn_lang.php";
include_once "common.php";
$sql = new DbSql();
$sql->login();

$mytable = "live_panal_table";
$sql->connect_database_default();
$mydb = $sql->get_database();
$sql->create_database($mydb);
$sql->create_table($mydb, $mytable, "tag longtext, value longtext");
	
$liveuitype = intval($sql->query_data($mydb, $mytable,"tag","liveuitype","value"));
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
                                <div class="muted pull-left"><?php echo $lang_array["live_panal_2_text1"] ?></div>
                            </div>
                            <div class="block-content collapse in">
                                <div class="span12">                                    
                                    <table cellpadding="0" cellspacing="0" border="0" class="table table-bordered">
                                        <thead>
                                            <tr>
            								<th width="8%" style="vertical-align:middle; text-align:center;"><?php echo $lang_array["live_panal_2_text2"] ?></th>
            								<th width="12%" style="vertical-align:middle; text-align:center;"><?php echo $lang_array["live_panal_2_text3"] ?></th>
          									<th width="60%" style="vertical-align:middle; text-align:center;"><?php echo $lang_array["live_panal_2_text4"] ?></th>
            								<th width="20%" style="vertical-align:middle; text-align:center;"><?php echo $lang_array["live_panal_2_text5"] ?></th>
                                            </tr>
                                        </thead>
                                        
        								<tr>
										<td style='align:center; vertical-align:middle; text-align:center;'>
                                        	<div class='controls'>
											<input style='width:15px;height:28px;' name='livetype' type='radio' value='1' <?php if($liveuitype == 1 || $liveuitype == null) echo "checked" ?>/></div>
										</td>
        								<td style='align:center; vertical-align:middle; text-align:center;'><img src="images/p6.jpg" width="72" height="50" /></td>
  										<td style='align:center; vertical-align:middle; text-align:center;'></td>
        								<td style='align:center; vertical-align:middle; text-align:center;'><a href="#" onclick="edit(1)"><?php echo $lang_array["live_panal_2_text6"] ?></a>&nbsp;&nbsp;<a href="#" onclick="ontype(1)"><?php echo $lang_array["live_panal_2_text7"] ?></a>&nbsp;&nbsp;<a href="#" onclick="save(1)"><?php echo $lang_array["live_panal_2_text8"] ?></a></td>
        								</tr>   
                                         
                                        <tr>
										<td style='align:center; vertical-align:middle; text-align:center;'>
                                        	<div class='controls'>
											<input style='width:15px;height:28px;' name='livetype' type='radio' value='2' <?php if($liveuitype == 2) echo "checked" ?>/></div>
										</td>
        								<td style='align:center; vertical-align:middle; text-align:center;'><img src="images/p5.jpg" width="72" height="50" /></td>
  										<td style='align:center; vertical-align:middle; text-align:center;'></td>
        								<td style='align:center; vertical-align:middle; text-align:center;'><a href="#" onclick="edit(2)"><?php echo $lang_array["live_panal_2_text6"] ?></a>&nbsp;&nbsp;<a href="#" onclick="ontype(2)"><?php echo $lang_array["live_panal_2_text7"] ?></a>&nbsp;&nbsp;<a href="#" onclick="save(2)"><?php echo $lang_array["live_panal_2_text8"] ?></a></td>
        								</tr>                                 
                                    </table>
                                    
                                    <div class="form-actions">
   										<button type="button" class="btn btn-primary" onclick="save_type()"><?php echo $lang_array["save_text1"] ?></button>
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

		function save_type()
		{
			var id = GetRadioValue("livetype");
			window.location.href = "live_panal_post.php?id=" + id;
		}
		
		
		function ontype(index)
		{
			window.location.href = "live_type_list.php";
		}
		
		function save(index)
		{
			window.location.href = "live_preview_list.php";
		}
		
		function edit(index)
		{
			window.location.href = "live_panal_edit.php";
		}
        </script>
    </body>

</html>

<?php
	$sql->disconnect_database();

?>