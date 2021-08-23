<!DOCTYPE html>
<?php
	include_once "cn_lang.php";
	include_once "common.php";
	$sql = new DbSql();
	$sql->login();
	
	include_once "gemini.php";
	$g = new Gemini();
	
	$sql->connect_database_default();
	$mydb = $sql->get_database();
	$sql->create_database($mydb);
	$mytable = "vod_type_table_0";
	$sql->create_table($mydb, $mytable, "value longtext, id smallint");
	$value00 = $sql->query_data($mydb, $mytable, "id", 0 , "value"); 
	$value01 = $sql->query_data($mydb, $mytable, "id", 1 , "value"); 
	$value02 = $sql->query_data($mydb, $mytable, "id", 2 , "value"); 
	
	$mytable = "vod_type_table_1";
	$sql->create_table($mydb, $mytable, "value longtext, id smallint");
	$value10 = $sql->query_data($mydb, $mytable, "id", 0 , "value"); 
	$value11 = $sql->query_data($mydb, $mytable, "id", 1 , "value"); 
	$value12 = $sql->query_data($mydb, $mytable, "id", 2 , "value"); 
	
	$mytable = "vod_type_table_2";
	$sql->create_table($mydb, $mytable, "value longtext, id smallint");
	$value20 = $sql->query_data($mydb, $mytable, "id", 0 , "value"); 
	$value21 = $sql->query_data($mydb, $mytable, "id", 1 , "value"); 
	$value22 = $sql->query_data($mydb, $mytable, "id", 2 , "value"); 
	
	$mytable = "vod_type_table_3";
	$sql->create_table($mydb, $mytable, "value longtext, id smallint");
	$value30 = $sql->query_data($mydb, $mytable, "id", 0 , "value"); 
	$value31 = $sql->query_data($mydb, $mytable, "id", 1 , "value"); 
	$value32 = $sql->query_data($mydb, $mytable, "id", 2 , "value"); 
	
	$mytable = "vod_name_table";
	$sql->create_table($mydb, $mytable, "id smallint,name text, needps int, password text, num int, total int");
	$name0 = $sql->query_data($mydb, $mytable, "id", 0 , "name"); 
	if($name0 == null)
		$name0 = $lang_array["vod_item_list_text9"];
	$needps0 = $sql->query_data($mydb, $mytable, "id", 0 , "needps"); 
	$password0 = $sql->query_data($mydb, $mytable, "id", 0 , "password"); 
	$num0 = $sql->query_data($mydb, $mytable, "id", 0 , "num"); 
	$total0 = $sql->query_data($mydb, $mytable, "id", 0 , "total"); 
	
	$name1 = $sql->query_data($mydb, $mytable, "id", 1 , "name"); 
	if($name1 == null)
		$name1 = $lang_array["vod_item_list_text10"];
	$needps1 = $sql->query_data($mydb, $mytable, "id", 1 , "needps"); 
	$password1 = $sql->query_data($mydb, $mytable, "id", 1 , "password"); 
	$num1 = $sql->query_data($mydb, $mytable, "id", 1 , "num"); 
	$total1 = $sql->query_data($mydb, $mytable, "id", 1 , "total"); 
	
	$name2 = $sql->query_data($mydb, $mytable, "id", 2 , "name"); 
	if($name2 == null)
		$name2 = $lang_array["vod_item_list_text11"];
	$needps2 = $sql->query_data($mydb, $mytable, "id", 2 , "needps"); 
	$password2 = $sql->query_data($mydb, $mytable, "id", 2 , "password"); 
	$num2 = $sql->query_data($mydb, $mytable, "id", 2 , "num"); 
	$total2 = $sql->query_data($mydb, $mytable, "id", 2 , "total"); 
	
	$name3 = $sql->query_data($mydb, $mytable, "id", 3 , "name"); 
	if($name3 == null)
		$name3 = $lang_array["vod_item_list_text12"];
	$needps3 = $sql->query_data($mydb, $mytable, "id", 3 , "needps"); 
	$password3 = $sql->query_data($mydb, $mytable, "id", 3 , "password"); 
	$num3 = $sql->query_data($mydb, $mytable, "id", 3 , "num"); 
	$total3 = $sql->query_data($mydb, $mytable, "id", 3 , "total"); 
	
	/*
	$number_0 = 0;
	$number_1 = 1;
	$number_2 = 2;
	$number_3 = 3;
	
	$number_total_0 = 0;
	$number_total_1 = 0;
	$number_total_2 = 0;
	$number_total_3 = 0;
	
	$mytable = "vod_table_0";
	$sql->create_table($mydb, $mytable, "name text, image text, url text, area text, year text, 
		type text, intro1 longtext, intro2 longtext, intro3 longtext, intro4 longtext,
		id int, clickrate int, recommend tinyint, chage float, updatetime int, 
		firstletter text");
	
	$number_0 = $sql->count_fetch_datas($mydb, $mytable);
	$number_0_namess = $sql->fetch_datas($mydb, $mytable);
	foreach($number_0_namess as $number_0_names) 
	{
		$vods = explode("|", $g->j4($number_0_names[2]));
		$num = count($vods);
		$number_total_0 = $number_total_0 + $num;
	}
	
	$mytable = "vod_table_1";
	$sql->create_table($mydb, $mytable, "name text, image text, url text, area text, year text, 
		type text, intro1 longtext, intro2 longtext, intro3 longtext, intro4 longtext,
		id int, clickrate int, recommend tinyint, chage float, updatetime int, 
		firstletter text");
	
	$number_1 = $sql->count_fetch_datas($mydb, $mytable);
	$number_1_namess = $sql->fetch_datas($mydb, $mytable);
	foreach($number_1_namess as $number_1_names) 
	{
		$vods = explode("|", $g->j4($number_1_names[2]));
		$num = count($vods);
		$number_total_1 = $number_total_1 + $num;
	}
	
	$mytable = "vod_table_2";
	$sql->create_table($mydb, $mytable, "name text, image text, url text, area text, year text, 
		type text, intro1 longtext, intro2 longtext, intro3 longtext, intro4 longtext,
		id int, clickrate int, recommend tinyint, chage float, updatetime int, 
		firstletter text");
	
	$number_2 = $sql->count_fetch_datas($mydb, $mytable);
	$number_2_namess = $sql->fetch_datas($mydb, $mytable);
	foreach($number_2_namess as $number_2_names) 
	{
		$vods = explode("|", $g->j4($number_2_names[2]));
		$num = count($vods);
		$number_total_2 = $number_total_2 + $num;
	}
	
	$mytable = "vod_table_3";
	$sql->create_table($mydb, $mytable, "name text, image text, url text, area text, year text, 
		type text, intro1 longtext, intro2 longtext, intro3 longtext, intro4 longtext,
		id int, clickrate int, recommend tinyint, chage float, updatetime int, 
		firstletter text");
	
	$number_3 = $sql->count_fetch_datas($mydb, $mytable);
	$number_3_namess = $sql->fetch_datas($mydb, $mytable);
	foreach($number_3_namess as $number_3_names) 
	{
		$vods = explode("|", $g->j4($number_3_names[2]));
		$num = count($vods);
		$number_total_3 = $number_total_3 + $num;
	}
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
                                <div class="muted pull-left"><?php echo $lang_array["vod_item_list_text1"] ?></div>
                            </div>
                            <div class="block-content collapse in">
                                <div class="span12">                                    
                                    <table cellpadding="0" cellspacing="0" border="0" class="table table-bordered">
                                        <thead>
                                            <tr>
            								<th width="15%" style="vertical-align:middle; text-align:center;"><?php echo $lang_array["vod_item_list_text2"] ?></th>
            								<th width="45%" style="vertical-align:middle; text-align:center;"><?php echo $lang_array["vod_item_list_text3"] ?></th>
          									<th width="10%" style="vertical-align:middle; text-align:center;"><?php echo $lang_array["vod_item_list_text4"] ?></th>
                                            <th width="5%" style="vertical-align:middle; text-align:center;"><?php echo $lang_array["vod_item_list_text18"] ?></th>
                                            <th width="5%" style="vertical-align:middle; text-align:center;"><?php echo $lang_array["vod_item_list_text19"] ?></th>
            								<th width="20%" style="vertical-align:middle; text-align:center;"><?php echo $lang_array["vod_item_list_text5"] ?></th>
                                            </tr>
                                        </thead>
                                        
        								<tr>
										<td style='align:center; vertical-align:middle; text-align:center;'><?php echo $name0 ?></td>
        								<td style='align:center; vertical-align:middle; text-align:center;'>
                                        <?php 
											if(strlen($value00)<2 || strlen($value01)<2 || strlen($value02)<2) 
												echo $lang_array["vod_item_list_text13"];
											else 
											{
												echo $lang_array["vod_item_list_text14"] . " : " . $value00 . "<br/>";
												echo $lang_array["vod_item_list_text15"] . " : " . $value01 . "<br/>";
												echo $lang_array["vod_item_list_text16"] . " : " . $value02 . "<br/>";
											}
										?></td>
  										<td style='align:center; vertical-align:middle; text-align:center;'><?php if($needps0 == 1) echo $password0; else echo $lang_array["vod_item_list_text17"]; ?></td>
                                        
                                        <td style='align:center; vertical-align:middle; text-align:center;'><?php echo $num0 ?></td>
                                        <td style='align:center; vertical-align:middle; text-align:center;'><?php echo $total0 ?></td>
                                        
        								<td style='align:center; vertical-align:middle; text-align:center;'>
                                        <a href="#" onclick="edit(0)"><?php echo $lang_array["vod_item_list_text6"] ?></a>&nbsp;&nbsp;
                                        <a href="#" onclick="ontype(0)"><?php echo $lang_array["vod_item_list_text7"] ?></a>&nbsp;&nbsp;
                                        <?php
                						if(strlen($value00)>2 && strlen($value01)>2 && strlen($value02)>2)
										{
                                        	echo "<a href='#' onclick='save(0)'>" . $lang_array["vod_item_list_text8"] . "</a>";
                                        }
                                        ?>
                                        </td>
        								</tr>  
        								<tr>
                                        
										<td style='align:center; vertical-align:middle; text-align:center;'><?php echo $name1 ?></td>
        								<td style='align:center; vertical-align:middle; text-align:center;'>
                                        <?php 
											if(strlen($value10)<2 || strlen($value11)<2 || strlen($value12)<2) 
												echo $lang_array["vod_item_list_text13"];
											else 
											{
												echo $lang_array["vod_item_list_text14"] . " : " . $value10 . "<br/>";
												echo $lang_array["vod_item_list_text15"] . " : " . $value11 . "<br/>";
												echo $lang_array["vod_item_list_text16"] . " : " . $value12 . "<br/>";
											}
										?></td>
  										<td style='align:center; vertical-align:middle; text-align:center;'><?php if($needps1 == 1) echo $password1; else echo $lang_array["vod_item_list_text17"]; ?></td>
                                        
                                        <td style='align:center; vertical-align:middle; text-align:center;'><?php echo $num1 ?></td>
                                        <td style='align:center; vertical-align:middle; text-align:center;'><?php echo $total1 ?></td>

        								<td style='align:center; vertical-align:middle; text-align:center;'>
                                        <a href="#" onclick="edit(1)"><?php echo $lang_array["vod_item_list_text6"] ?></a>&nbsp;&nbsp;
                                        <a href="#" onclick="ontype(1)"><?php echo $lang_array["vod_item_list_text7"] ?></a>&nbsp;&nbsp;                                       
                                        <?php
                						if(strlen($value10)>2 && strlen($value11)>2 && strlen($value12)>2)
										{
                                        	echo "<a href='#' onclick='save(1)'>" . $lang_array["vod_item_list_text8"] . "</a>";
                                        }
                                        ?>
                                        </td>
        								</tr>  
                                        
                                        
         								<tr>
										<td style='align:center; vertical-align:middle; text-align:center;'><?php echo $name2 ?></td>
        								<td style='align:center; vertical-align:middle; text-align:center;'>
                                        <?php 
											if(strlen($value20)<2 || strlen($value21)<2 || strlen($value22)<2) 
												echo $lang_array["vod_item_list_text13"];
											else 
											{
												echo $lang_array["vod_item_list_text14"] . " : " . $value20 . "<br/>";
												echo $lang_array["vod_item_list_text15"] . " : " . $value21 . "<br/>";
												echo $lang_array["vod_item_list_text16"] . " : " . $value22 . "<br/>";
											}
										?></td>
  										<td style='align:center; vertical-align:middle; text-align:center;'><?php if($needps2 == 1) echo $password2; else echo $lang_array["vod_item_list_text17"]; ?></td>
                                        
                                        <td style='align:center; vertical-align:middle; text-align:center;'><?php echo $num2 ?></td>
                                        <td style='align:center; vertical-align:middle; text-align:center;'><?php echo $total2 ?></td>
                                        
        								<td style='align:center; vertical-align:middle; text-align:center;'>
                                        <a href="#" onclick="edit(2)"><?php echo $lang_array["vod_item_list_text6"] ?></a>&nbsp;&nbsp;
                                        <a href="#" onclick="ontype(2)"><?php echo $lang_array["vod_item_list_text7"] ?></a>&nbsp;&nbsp;
                                        <?php
                						if(strlen($value20)>2 && strlen($value21)>2 && strlen($value22)>2)
										{
                                        	echo "<a href='#' onclick='save(2)'>" . $lang_array["vod_item_list_text8"] . "</a>";
                                        }
                                        ?>
                                        </td>
        								</tr>  
                                        
        								<tr>
										<td style='align:center; vertical-align:middle; text-align:center;'><?php echo $name3 ?></td>
        								<td style='align:center; vertical-align:middle; text-align:center;'>
										<?php 
											if(strlen($value30)<2 || strlen($value31)<2 || strlen($value32)<2) 
												echo $lang_array["vod_item_list_text13"];
											else 
											{
												echo $lang_array["vod_item_list_text14"] . ":" . $value30 . "<br/>";
												echo $lang_array["vod_item_list_text15"] . ":" . $value31 . "<br/>";
												echo $lang_array["vod_item_list_text16"] . ":" . $value32 . "<br/>";
											}
										?></td>
  										<td style='align:center; vertical-align:middle; text-align:center;'><?php if($needps3 == 1) echo $password3; else echo $lang_array["vod_item_list_text17"]; ?></td>
                                        
                                        <td style='align:center; vertical-align:middle; text-align:center;'><?php echo $num3 ?></td>
                                        <td style='align:center; vertical-align:middle; text-align:center;'><?php echo $total3 ?></td>
        								
                                        <td style='align:center; vertical-align:middle; text-align:center;'>
                                        <a href="#" onclick="edit(3)"><?php echo $lang_array["vod_item_list_text6"] ?></a>&nbsp;&nbsp;
                                        <a href="#" onclick="ontype(3)"><?php echo $lang_array["vod_item_list_text7"] ?></a>&nbsp;&nbsp;
                                        <?php
                						if(strlen($value30)>2 && strlen($value31)>2 && strlen($value32)>2)
										{
                                        	echo "<a href='#' onclick='save(3)'>" . $lang_array["vod_item_list_text8"] . "</a>";
                                        }
                                        ?>
                                        </td>
        								</tr>   
                                        
                                        
                                        <tr>
                                        
										<td style='align:center; vertical-align:middle; text-align:center;'><?php echo $lang_array["vod_item_list_text21"] ?></td>
        								<td style='align:center; vertical-align:middle; text-align:center;'></td>
  										<td style='align:center; vertical-align:middle; text-align:center;'></td>
                                        <td style='align:center; vertical-align:middle; text-align:center;'><?php echo $num0+$num1+$num2+$num3 ?></td>
                                        <td style='align:center; vertical-align:middle; text-align:center;'><?php echo $total0+$total1+$total2+$total3 ?></td>
        								<td style='align:center; vertical-align:middle; text-align:center;'></td>
        								</tr>                                                                                                                 
                                    </table>
                                    
                                    <div class="form-actions">
   										<button type="button" class="btn btn-primary" onClick="fnum()"><?php echo $lang_array["vod_item_list_text20"] ?></button>
                                        <button type="button" class="btn btn-primary" onClick="vod_edit()"><?php echo $lang_array["vod_item_list_text22"] ?></button>
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
		
		function fnum()
		{
			window.location.href = "vod_item_num_post.php";
		}

		function edit_user(name)
		{
			//if(confirm("是否删除用户： " + name + " ?") == true)
  			{
				var url = "proxy_edit.php?name=" + name;
				window.location.href = url;
  			}
		}

		function edit(index)
		{
			//window.location.href = "version_proxy_edit.php?proxy=" + name;
			var url = "vod_name_edit.php?type=" + index;
			window.location.href = url;
		}
		
		function ontype(index)
		{
			window.location.href = "vod_type_edit.php?type=" + index;
		}
		
		function save(index)
		{
			window.location.href = "vod_list.php?type=" + index;
		}
		
		function vod_edit()
		{
			window.location.href = "vod_panal_edit.php";
		}
        </script>
    </body>

</html>