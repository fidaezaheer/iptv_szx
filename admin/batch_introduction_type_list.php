<!DOCTYPE html>
<?php
	include 'common.php';
	include_once "cn_lang.php";
	include_once "gemini.php";
	$sql = new DbSql();
	$sql->login();
	
	
	
	$mytable = "live_type_table";
	$sql->connect_database_default();
	$mydb = $sql->get_database();
	$sql->create_database($mydb);
	$sql->create_table($mydb, $mytable, "name longtext, id longtext");
	$type_namess = $sql->fetch_datas($mydb, $mytable);
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
                                <div class="muted pull-left"><?php echo $lang_array["batch_introduction_list_text2"] ?></div>
                            </div>
                            
                            <div class="block-content collapse in">
                                <div class="span12">
                                	<form class="form-horizontal" method="post" action="batch_introduction_type_post.php" enctype='multipart/form-data'>
                                    	<div class="control-group" style="background-color:#CCC"> 
                                        <br/>
										<label class="control-label"><?php echo $lang_array["batch_introduction_list_text2"] ?>：</label>
                                        <div class="controls">
                                        	<input class="input-file uniform_on" type="file" name="file" id="file" /><input class="btn btn-primary" type="submit" name="submit" value="<?php echo $lang_array["batch_introduction_list_text1"] ?>" /> 
                                      	</div>    
                                      	<br/>                    
                                    	</div>
									</form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <FORM name="authform_post" action="batch_type_post.php" method="post" target="newWin">  
    	<input name="type" id="type" type="hidden" value=""/>
    	<input name="num" id="num" type="hidden" value=""/>
    	</Form>
    
        <!--/.fluid-container-->

        <script src="vendors/jquery-1.9.1.js"></script>
        <script src="bootstrap/js/bootstrap.min.js"></script>
        <script src="vendors/datatables/js/jquery.dataTables.min.js"></script>


        <script src="assets/scripts.js"></script>
        <script src="assets/DT_bootstrap.js"></script>
        <script>
        $(function() {
            
        });
		
		function get_type_checkbox_value()
		{
			var value = "";
			var checkboxs = document.getElementsByName("type_checkbox");
			
			for(var i = 0; i < checkboxs.length; i++)
			{
				if(checkboxs[i].type == "checkbox" && checkboxs[i].checked && checkboxs[i].value.length > 0)
				{
					if(value.length == 0)
					{
						value = checkboxs[i].value;
					}
					else
					{
						value = value + "|" + checkboxs[i].value;
					}
				}
			}
	
			return value;
		}

		function save()
		{
			var num = "<?php echo $_POST["type"] ?>";
			var type = get_type_checkbox_value();
			//window.location.href = "batch_type_post.php?num=" + num + "&type=" + type ;
			window.open('','newWin','height=180,width=600');  
			document.authform_post.type.value = type;
			document.authform_post.num.value = num;
			document.authform_post.submit()
	
		}

		function go_back()
		{
			window.opener.location.reload();
			window.close();
		}
        </script>
    </body>

</html>