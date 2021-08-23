<!DOCTYPE html>
<?php
	include_once "cn_lang.php";
	include_once "common.php";
	$sql = new DbSql();
	$sql->login();
	
	$level = 1;
	if(isset($_GET["level"]))
	{
		$level = intval($_GET["level"]);
	}
	
	$sql->connect_database_default();
	$mydb = $sql->get_database();
	$sql->create_database($mydb);
	$mytable = "proxy_table";
	$sql->create_table($mydb, $mytable, "name text, password text, ptip text, edit int, watermark text, validity date, allow int, remark text, ccount int, panal int, pwmd5 int");
	$allnamess = $sql->fetch_datas($mydb, $mytable);	
	$namess = $sql->fetch_datas_where_or_null($mydb, $mytable, "proxylevel", "1");
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
                            	<?php
									if($level == 1)
                                		echo "<div class=\"muted pull-left\">" . $lang_array["left1_text2"] . "</div>";
									else
										echo "<div class=\"muted pull-left\">" . $lang_array["proxy_list_text22"] . "</div>";
                                
                                ?>
                            </div>
                            
                            <div class="block-content collapse in">
                                <div class="span12">
                                	<form class="form-horizontal" name="authform" method="post" action="proxy_post.php?level=<?php echo $level ?>" enctype='multipart/form-data'>
                                    	<fieldset><br/>
                                   	<div class="control-group">
                                       <label class="control-label" for="focusedInput"><?php echo $lang_array["proxy_add_text1"] ?></label>
                                       <div class="controls">
                                          <input class="input-xlarge focused" id="name" name="name" type="text" value="">
                                       </div>
                                   	</div>
                                    <div class="control-group">
                                       <label class="control-label" for="focusedInput"><?php echo $lang_array["proxy_add_text2"] ?></label>
                                       <div class="controls">
                                          <input class="input-xlarge focused" id="password" name="password" type="text" value="">
                                       </div>
                                   	</div>

                                    <?php
									if($level == 2)
									{
                                    	echo "<div class='control-group'>";
                                    	echo "<label class='control-label' for='focusedInput'>" . $lang_array["proxy_add_text7"] . "</label>";
                                    	echo "<div class='controls'>";
                                    	echo "<select class='input-medium' name='proxybelong' id='proxybelong'>";
                                        	foreach($namess as $names) {
												if(strlen($names[7]) > 0)
													echo "<option value=\"" . $names[0] . "\">" . $names[0] . "(" . $names[7] . ")" . "</option>";
												else
													echo "<option value=\"" . $names[0] . "\">" . $names[0] . "</option>";
											}
                                    	echo "</select>";
                                    	echo "</div>";
                                   		echo "</div>";
									}
                                    ?>
                                    
                                    <div class="control-group">
                                       <label class="control-label" for="focusedInput"><?php echo $lang_array["proxy_list_text11"] ?></label>
                                       <div class="controls">
                                          <input class="input-xxlarge" id="remark" name="remark" type="text" value="">
                                       </div>
                                   	</div>
                                    <div class="form-actions">
   										<button type="button" class="btn btn-primary" onClick="save()"><?php echo $lang_array["save_text1"] ?></button>
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
		
		var arrayId = new Array();
		<?php
			foreach($allnamess as $allnames)
			{
				echo "arrayId.push('" . $allnames[0] . "');";
			}
		?>
		
		function save()
		{
			
			var name = document.getElementById("name").value;
			for(ii=0; ii<arrayId.length; ii++)
			{
				if(arrayId[ii].localeCompare(name) == 0)
				{
					alert("<?php echo $lang_array["proxy_add_text8"] ?>");
					return;
				}
			}
			
			if(name == "admin")
			{
				alert("<?php echo $lang_array["proxy_add_text3"] ?>");
			}
			else
			{
				document.authform.submit();	
			}
		}
		
		function back_page()
		{
			window.location.href = "proxy_list.php?level=<?php echo $level ?>";
				
		}
        </script>
    </body>

</html>