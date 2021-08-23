<!DOCTYPE html>
<?php
	include_once "cn_lang.php";
	include_once "common.php";
	$sql = new DbSql();
	$sql->login();

	$mytable = "vod_type_table_" . $_GET["type"];
	$sql->connect_database_default();
	$mydb = $sql->get_database();
	$sql->create_database($mydb);
	$sql->create_table($mydb, $mytable, "value longtext, id smallint");
	$value0 = $sql->query_data($mydb, $mytable, "id", 0 , "value"); 
	$value1 = $sql->query_data($mydb, $mytable, "id", 1 , "value");
	$value2 = $sql->query_data($mydb, $mytable, "id", 2 , "value");
	
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
    
    <body>
        <div class="container-fluid">
            <div class="row-fluid">
              <div class="span13" id="content">
                <div class="row-fluid">
                   <!-- block -->
						<div class="block">
                            <div class="navbar navbar-inner block-header">
                                <div class="muted pull-left"><?php echo $lang_array["vod_type_edit_text1"] ?></div>
                            </div>
                            
                            <div class="block-content collapse in">
                                <div class="span12">
                                	<form class="form-horizontal" method="post" action="vod_type_post.php?type=<?php echo $_GET["type"] ?>" enctype='multipart/form-data'>
                                    	<fieldset><br/>
                                   	<div class="control-group">
                                       <label class="control-label" for="focusedInput"><?php echo $lang_array["vod_type_edit_text2"] ?></label>
                                       <div class="controls">
                                          <input class="input-xlarge focused" id="vtype" name="vtype" type="text" value="<?php echo $value0 ?>">&nbsp;&nbsp;&nbsp;<?php echo $lang_array["vod_type_edit_text5"] ?>
                                       </div>
                                   	</div>
                                   	<div class="control-group">
                                       <label class="control-label" for="focusedInput"><?php echo $lang_array["vod_type_edit_text3"] ?></label>
                                       <div class="controls">
                                          <input class="input-xlarge focused" id="year" name="year" type="text" value="<?php echo $value1 ?>">&nbsp;&nbsp;&nbsp;<?php echo $lang_array["vod_type_edit_text6"] ?>
                                       </div>
                                   	</div>
                                   	<div class="control-group">
                                       <label class="control-label" for="focusedInput"><?php echo $lang_array["vod_type_edit_text4"] ?></label>
                                       <div class="controls">
                                          <input class="input-xlarge focused" id="area" name="area" type="text" value="<?php echo $value2 ?>">&nbsp;&nbsp;&nbsp;<?php echo $lang_array["vod_type_edit_text7"] ?>
                                       </div>
                                   	</div>
                                    <div class="form-actions">
   										<button type="submit" class="btn btn-primary"><?php echo $lang_array["save_text1"] ?></button>
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

        <script src="vendors/jquery-1.9.1.js"></script>
        <script src="bootstrap/js/bootstrap.min.js"></script>
        <script src="vendors/datatables/js/jquery.dataTables.min.js"></script>


        <script src="assets/scripts.js"></script>
        <script src="assets/DT_bootstrap.js"></script>
        <script>
        $(function() {
            
        });
		
		function back_page()
		{
			window.location.href = "vod_item_list.php";
				
		}
        </script>
    </body>

</html>