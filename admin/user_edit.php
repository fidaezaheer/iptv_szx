<!DOCTYPE html>
<?php
	include_once "cn_lang.php";
	include_once "common.php";
	$sql = new DbSql();
	$sql->login();

	$name = $_GET["name"];
	
	$mytable = "user_table";
	$sql->connect_database_default();
	$mydb = $sql->get_database();
	$sql->create_database($mydb);
	$sql->create_table($mydb, $mytable, "name longtext, password longtext, namemd5 text, passwordmd5 text, needmd5 int");
	
	$password = $sql->query_data($mydb, $mytable,"name",$name,"password");
	$needmd5 = $sql->query_data($mydb, $mytable,"name",$name,"needmd5");
	
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
                                <div class="muted pull-left"><?php echo $lang_array["left1_text1"] ?></div>
                            </div>
                            
                            <div class="block-content collapse in">
                                <div class="span12">
                                	<form class="form-horizontal" method="post" action="user_post.php" enctype='multipart/form-data'>
                                    	<fieldset><br/>
                                   	<div class="control-group">
                                       <label class="control-label" for="focusedInput"><?php echo $lang_array["user_add_text1"] ?></label>
                                       <div class="controls">
                                          <input class="input-xlarge focused" id="name" name="name" type="text" value="<?php echo $name ?>" readonly>
                                       </div>
                                   	</div>
                                    <div class="control-group">
                                       <label class="control-label" for="focusedInput"><?php echo $lang_array["user_add_text2"] ?></label>
                                       <div class="controls">
                                          <input class="input-xlarge focused" id="password" name="password" type="text" value="<?php echo $password ?>">
                                       </div>
                                   	</div>
                                    
                                    <div class="control-group">
										<label class="control-label" ><?php echo $lang_array["user_add_text3"] ?></label>
                                        <div class="controls">
											<?php  echo $lang_array["yes_text1"] ?><input name="rmd5" type="radio" id="rmd50" value="2" style="width:15px;height:28px;" <?php if($needmd5 != 0) echo "checked"; ?>>&nbsp;&nbsp;
                                            <?php  echo $lang_array["no_text1"] ?><input name="rmd5" type="radio" id="rmd51" value="0" style="width:15px;height:28px;" <?php if($needmd5 == 0) echo "checked"; ?>>
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
			window.location.href = "user_list.php";
				
		}
        </script>
    </body>

</html>