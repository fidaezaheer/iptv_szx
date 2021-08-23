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
    
    <body>
        <div class="container-fluid">
            <div class="row-fluid">
              <div class="span13" id="content">
                <div class="row-fluid">
                   <!-- block -->
						<div class="block">
                            <div class="navbar navbar-inner block-header">
                                <div class="muted pull-left"><?php echo $lang_array["left1_text1"] ?></div>
                            </div>
                            
                            <div class="block-content collapse in">
                                <div class="span12">
                                	<form class="form-horizontal" method="post" action="number_post.php" enctype='multipart/form-data'>
                                    	<fieldset><br/>
                                   	<div class="control-group">
                                       <label class="control-label" for="focusedInput"><?php echo $lang_array["number_edit_text1"] ?></label>
                                       <div class="controls">
                                          <input class="input-xlarge focused" id="oldps" name="oldps" type="text" value="">
                                       </div>
                                   	</div>
                                    <div class="control-group">
                                       <label class="control-label" for="focusedInput"><?php echo $lang_array["number_edit_text2"] ?></label>
                                       <div class="controls">
                                          <input class="input-xlarge focused" id="newps" name="newps" type="text" value="">
                                       </div>
                                   	</div>
                                    <div class="form-actions">
   										<button type="submit" class="btn btn-primary"><?php echo $lang_array["save_text1"] ?></button>
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
		<?php
			if(isset($_GET["error"]) && intval($_GET["error"]) == 1)
			{
				echo "alert('" .$lang_array["number_edit_text3"] . "');";
			}
		?>
        </script>
    </body>

</html>