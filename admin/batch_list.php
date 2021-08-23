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
                                <div class="muted pull-left"><?php echo $lang_array["custom_batch_list_text1"] ?></div>
                            </div>
                            
                            <div class="block-content collapse in">
                                <div class="span12">
                                	<form class="form-horizontal" method="post" action="user_post.php" enctype='multipart/form-data'>
                                    	<fieldset><br/>
                                   			<button type="button" class="btn btn-primary" onClick="add_leftday()"><?php echo $lang_array["custom_batch_list_text2"] ?></button>
                                            <button type="button" class="btn btn-primary" onClick="delect_invalid_user()"><?php echo $lang_array["custom_batch_list_text3"] ?></button>
                                            <button type="button" class="btn btn-primary" onClick="delect_timeout_user()"><?php echo $lang_array["custom_batch_list_text4"] ?></button>
                                            <button type="button" class="btn btn-primary" onClick="batch_playlist()"><?php echo $lang_array["custom_batch_list_text5"] ?></button>
                                            <br/>
                                            <br/>
                                            <button type="button" class="btn btn-primary" onClick="batch_scrolltext()"><?php echo $lang_array["custom_batch_list_text16"] ?></button>
                                            <button type="button" class="btn btn-primary" onClick="delect_test_user()"><?php echo $lang_array["custom_batch_list_text17"] ?></button>
                                            
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
		
		function add_leftday()
		{
			var r=confirm("<?php echo $lang_array["custom_batch_list_text6"] ?>" + "？");
			if(r==true)
  			{	
				var day= window.prompt("<?php echo $lang_array["custom_batch_list_text7"] ?>");
				var rr=confirm("<?php echo $lang_array["custom_batch_list_text8"] ?>" + "：" + day + " 天 " + "," + "<?php echo $lang_array["custom_batch_list_text9"] ?>" +"?");
				if(rr == true)
				{
					var cmd = "custom_add_leftday.php?" + "addday=" + day;
					window.location.href = cmd;
				}
			}	
		}
		
		function delect_timeout_user()
		{
			if(confirm("<?php echo $lang_array["custom_batch_list_text10"] ?>" + "?") == true)
  			{
				if(confirm("<?php echo $lang_array["custom_batch_list_text11"] ?>" + "?") == true)
					window.location.href = "custom_timeout_del2.php";
			}
		}

		function batch_playlist()
		{
			if(confirm("<?php echo $lang_array["custom_batch_list_text12"] ?>" + "?") == true)
  			{
				window.location.href = "batch_playlist_list.php";
			}	
		}


		function delect_invalid_user()
		{
			if(confirm("<?php echo $lang_array["custom_batch_list_text13"] ?>" + "?") == true)
  			{
				if(confirm("<?php echo $lang_array["custom_batch_list_text14"] ?>" + "?") == true)
					window.location.href = "custom_invalid_del.php";
			}
		}
		
		function delect_test_user()
		{
			if(confirm("<?php echo $lang_array["custom_batch_list_text17"] ?>" + "?") == true)
  			{
				if(confirm("<?php echo $lang_array["custom_batch_list_text18"] ?>" + "?") == true)
					window.location.href = "custom_test_del.php";
			}			
		}
		
		function batch_authorization()
		{
			window.location.href = "batch_authorization.php";		
		}
		
		
        </script>
    </body>

</html>