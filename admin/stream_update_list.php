<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<!DOCTYPE html>
<?php
	include_once "cn_lang.php";
	include_once "common.php";
	$sql = new DbSql();
	$sql->login();

	$mytable = "stream_server_list_table";
	$sql->connect_database_default();
	$mydb = $sql->get_database();
	$sql->create_database($mydb);
	//$sql->create_table($mydb, $mytable, "urlid int, name text, url text, online int, cache int, path text, tabel text, serverip text, url1 text, url2 text, url3 text");
	$sql->create_table($mydb, $mytable, "serverid int, serverip text, tip text, os text, version int");
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
    </head>
    
    <body>
        <div class="container-fluid">
            <div class="row-fluid">
              <div class="span13" id="content">
                <div class="row-fluid">
                   <!-- block -->
						<div class="block">
                            <div class="navbar navbar-inner block-header">
                                <div class="muted pull-left"><?php echo $lang_array["stream_update_list_text1"] ?></div>
                            </div>
                            <div class="block-content collapse in">
                                <div class="span12">                                  
                                    <table cellpadding="0" cellspacing="0" border="0" class="table table-bordered">
                                        <thead>
                                            <tr>
            								<th width="8%" style="vertical-align:middle; text-align:center;"><?php echo $lang_array["stream_server_list_text5"] ?></th>
            								<th width="12%" style="vertical-align:middle; text-align:center;"><?php echo $lang_array["stream_server_list_text2"] ?></th>
          									<th width="30%" style="vertical-align:middle; text-align:center;"><?php echo $lang_array["stream_server_list_text3"] ?></th>
                                            <th width="30%" style="vertical-align:middle; text-align:center;"><?php echo $lang_array["stream_update_list_text3"] ?></th>
            								<th width="20%" style="vertical-align:middle; text-align:center;"><?php echo $lang_array["stream_server_list_text4"] ?></th>
                                            </tr>
                                        </thead>
<?php
                                        echo "<tbody>";
										foreach($namess as $names) 
										{
        									echo "<tr>";

  											echo "<td style='align:center; vertical-align:middle; text-align:center;'>" . $names[0] . "</td>";
                                        	echo "<td style='align:center; vertical-align:middle; text-align:center;'>" . $names[1] . "</td>";
											echo "<td style='align:center; vertical-align:middle; text-align:center;'>" . $names[2] . "</td>";
											echo "<td style='align:center; vertical-align:middle; text-align:center;'>" . date("Y-m-d h:i:s",$names[4]) . "</td>";
											echo "<td style='vertical-align:middle; text-align:center;'><a href='#' onclick='get_version(\"".$names[0]."\")'>".$lang_array["stream_update_list_text4"]."</a>&nbsp;&nbsp;<a href='#' onclick='update_server(\"".$names[0]."\")'>".$lang_array["stream_update_list_text2"]."</a></td>";
											
        									echo "</tr>";   
										}
                                        echo "<tbody>";        
?>                       
                                    </table>
                                    
                                    
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

		function get_version(serverid)
		{
			window.location.href = "stream_update_version.php?serverid=" + serverid;
		}
		
		function update_server(serverid)
		{
			window.location.href = "stream_update_post.php?serverid=" + serverid;
		}
        </script>
    </body>

</html>

<?php
	$sql->disconnect_database();

?>