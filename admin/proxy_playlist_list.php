<!DOCTYPE html>
<?php
include_once "cn_lang.php";
include_once "common.php";
$sql = new DbSql();
$sql->login();

$sql->connect_database_default();
$mydb = $sql->get_database();
$sql->create_database($mydb);

$mytable = "playlist_type_table";
$sql->create_table($mydb, $mytable, "name text, space longtext, id text, playlist longtext");
$playlistss = $sql->fetch_datas($mydb, $mytable);
				
$mytable = "proxy_table";
$sql->create_table($mydb, $mytable, "name text, password text");
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
                                <div class="muted pull-left"><?php echo $lang_array["proxy_playlist_list_text1"] ?></div>
                            </div>
                            <div class="block-content collapse in">
                                <div class="span12">                                    
                                    <table cellpadding="0" cellspacing="0" border="0" class="table table-bordered">
                                        <thead>
                                            <tr>
            								<th width="8%" style="vertical-align:middle; text-align:center;"><?php echo $lang_array["proxy_playlist_list_text2"] ?></th>
          									<th width="84%" style="vertical-align:middle; text-align:center;"><?php echo $lang_array["proxy_playlist_list_text3"] ?></th>
            								<th width="8%" style="vertical-align:middle; text-align:center;"><?php echo $lang_array["proxy_playlist_list_text4"] ?></th>
                                            </tr>
                                        </thead>
<?php                                        
										foreach($namess as $names)
										{
											echo "<tr>";
											echo "<td style='vertical-align:middle; text-align:center;'>".$names[0]."</td>";
											echo "<td style='vertical-align:middle; text-align:center;'>";
											$playlist_text = "";
											$ii = 0;
											foreach($playlistss as $playlists) 
											{
												$mytable = "proxy_playlist_table";
												$sql->create_table($mydb, $mytable, "proxy text, playlist text");
												$playlists_id = $sql->query_data($mydb, $mytable, "proxy", $names[0], "playlist");
												if(strstr($playlists_id,$playlists[2]) != FALSE || $playlists_id == null)
													$checked = "' checked> ";
												else
													$checked = "'> ";
												$playlist_text = $playlist_text . "<input name='type_checkbox_".$names[0]."' type='checkbox' value='" . $playlists[2] . $checked . "" . $playlists[0] . "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;" . "" . "</input>";
												$ii++;
												if($ii%10 == 0)
													$playlist_text = $playlist_text ."<br/>";
											}
											echo $playlist_text;
											echo "</td>";
											echo "<td style='vertical-align:middle; text-align:center;'><a href='#' onclick='proxy_playlist(\"".$names[0]."\")'>" . $lang_array["save_text1"] . "</a></img></td>";
											echo "</tr>";
                                        }
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
		
		function proxy_playlist(name)
		{
			var url = "proxy_playlist_post.php?name=" + name + "&playlist=" + get_type_checkbox_value(name);
			window.location.href = url;
		}
		
		function get_type_checkbox_value(name)
		{
			var value = "";
			var value_array = new Array();
			var type_checkboxs = document.getElementsByName("type_checkbox_" + name);
			for(var i = 0; i < type_checkboxs.length; i++)
			{
				if(type_checkboxs[i].type == "checkbox" && type_checkboxs[i].checked)
				{
					value_array.push(type_checkboxs[i].value);

				}
			}
	
			for(var ii = 0; ii < value_array.length; ii++)
			{
				value = value + value_array[ii];
				if(ii < value_array.length - 1)
					value = value + "|";
			}
	
			//alert(value);
			return value;
		}

        </script>
    </body>

</html>