<!DOCTYPE html>
<?php
include_once "cn_lang.php";
include_once "common.php";
$sql = new DbSql();
$sql->login();

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
                                <div class="muted pull-left"><?php echo $lang_array["live_type_list_text1"] ?></div>
                            </div>
                            <div class="block-content collapse in">
                                <div class="span12">                                     
                                	<div class="table-toolbar">
                                      <div class="btn-group">
                                         <a href="live_type_add.php"><button class="btn btn-success"><?php echo $lang_array["live_type_list_text2"] ?><i class="icon-plus icon-white"></i></button></a>
                                      </div>
                                      <div class="btn-group">
                                         <a href="#" onClick="edit_password()"><button class="btn btn-success"><?php echo $lang_array["live_type_list_text3"] ?></button></a>
                                      </div>
                                      <div class="btn-group">
                                         <a href="#" onClick="batch_export_type()"><button class="btn btn-success"><?php echo $lang_array["live_type_list_text11"] ?></button></a>
                                      </div>
                                      <div class="btn-group">
                                         <a href="#" onClick="batch_introduction_type()"><button class="btn btn-success"><?php echo $lang_array["live_type_list_text12"] ?></button></a>
                                      </div>
                                   	</div>                                  
                                    <table cellpadding="0" cellspacing="0" border="0" class="table table-bordered">
                                        <thead>
                                            <tr>
          									<th width="14%" style="vertical-align:middle; text-align:center;"><?php echo $lang_array["live_type_list_text6"] ?></th>
            								<th width="23%" style="vertical-align:middle; text-align:center;"><?php echo $lang_array["live_type_list_text7"] ?></th>
            								<th width="23%" style="vertical-align:middle; text-align:center;"><?php echo $lang_array["live_type_list_text8"] ?></th>
          									<th width="14%" style="vertical-align:middle; text-align:center;"><?php echo $lang_array["live_type_list_text9"] ?></th>
                                            </tr>
                                        </thead>
                                        
		<?php
			//include 'common.php';
			$type_array = array();
			$type_sql = new DbSql();
			$type_sql->connect_database_default();
			$mydb = $type_sql->get_database();
			$type_sql->create_database($mydb);
			
			$mytable = "live_type_table";
			$type_sql->create_table($mydb, $mytable, "name longtext, id longtext, lang text");
			$type_namess = $type_sql->fetch_datas($mydb, $mytable);

			$type_sql->delete_data($mydb,$mytable,"id","000000");
			
			$mytable = "live_type_table2";
			$type_sql->create_table($mydb, $mytable, "id text, need int, typepassword text, param0 text, param1 text, param2 text, param3 text");
			
			echo "<tbody>";
			foreach($type_namess as $type_names) {
				echo "<tr>";
				$key = null;
				
				echo "<td style='vertical-align:middle; text-align:center;'>" . $type_names[1] . "</td>";
				echo "<td style='vertical-align:middle; text-align:center;'>" . $type_names[0] . "</td>";
				$need = $type_sql->query_data($mydb, $mytable, "id", $type_names[1], "need");
				$password = $type_sql->query_data($mydb, $mytable, "id", $type_names[1], "typepassword");
				if($need != null || $password != null)
				{
					if($need == 0)
					{
						echo "<td style='vertical-align:middle; text-align:center;'>" . $lang_array["no_text1"] . "</td>";
					}
					else
					{
						echo "<td style='vertical-align:middle; text-align:center;'>" . $password . "</td>";
					}
				}
				else
				{
					echo "<td style='vertical-align:middle; text-align:center;'>" . $lang_array["no_text1"] . "</td>";	
				}
				echo "<td style='vertical-align:middle; text-align:center;'>";
				echo "<a href='live_type_edit.php?id=" . $type_names[1] . "'>" . $lang_array["edit_text2"] . "</a>";
				echo "&nbsp;&nbsp;";
				echo "<a href='#' onclick='direct_live_type(\"" . $type_names[1] . "\")'>" . $lang_array["live_type_list_text10"]  . "</a>";
				echo "&nbsp;&nbsp;";
				echo "<a href='#' onclick='delete_live_type(\"" . $type_names[0] . "\",\"" . $type_names[1] . "\")'>" . $lang_array["del_text1"]  . "</a></td>";	
				echo "&nbsp;&nbsp;";	
				echo "</tr>";
				
				$type_array[$type_names[1]] = $type_names[0];
			}
			echo "</tbody>";
			
			$type_sql->disconnect_database();
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
		
		
		function batch_export_type()
		{
			var url = "batch_export_type_post.php";
			window.location.href = url;
		}
		
		function batch_introduction_type()
		{
			if(confirm("<?php echo $lang_array["live_type_list_text13"] ?>") == true)
  			{
				if(confirm("<?php echo $lang_array["live_type_list_text14"] ?>") == true)
				{
					window.open("batch_introduction_type_list.php","","height=180,width=600");
				}
  			}
		}
		
		function delete_live_type(name,id)
		{
			if(confirm("<?php echo $lang_array["live_type_list_text5"] ?>： " + name + " ?") == true)
  			{
				var url = "live_type_del.php?id=" + id;
				window.location.href = url;
  			}
		}
		
		function direct_live_type(id)
		{
			var url = "live_type_direct.php?id=" + id;
			window.location.href = url;
		}
		
		function edit_password()
		{
			var ps= window.prompt("<?php echo $lang_array["live_type_list_text4"] ?>:");
			if(ps.length > 0)
			{
				window.location.href = "live_type_password.php?ps=" + ps;
			}
		}

        </script>
    </body>

</html>