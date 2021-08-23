<!DOCTYPE html>
<?php
	include_once "cn_lang.php";
	include_once "common.php";
	$sql = new DbSql();
	$sql->login();
	
	$tip = file_get_contents("http://www.gemini-iptv.com/update_text.php");
	$tip = iconv("GBK//IGNORE", "UTF-8" , $tip);
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
                                <div class="muted pull-left"><?php echo $lang_array["left2_text3"] ?></div>
                            </div>
                            
                            <div class="block-content collapse in">
                                <div class="span12">
    							<p><?php echo $lang_array["about_text2"] ?></p>
								<p><?php echo $lang_array["about_text3"] ?></p> 
								<p><?php echo $lang_array["about_text4"] ?></p>
								<p><?php echo $lang_array["about_text5"] ?></p>
								<p><?php echo $lang_array["about_text6"] ?></p>
								<p><?php echo $lang_array["about_text7"] ?></p>
                                </div>
                            </div>
                            
                           	<div class="well" style="margin-top:30px;">
								<button type="button" class="btn btn-primary btn-large" onclick="save()"><?php echo $lang_array["about_text1"] ?></button>
							</div> 
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <font size="+1"><marquee width="1280" border="0" align="bottom" scrolldelay="120"  height="395"><?php echo $tip ?></marquee></font>
        <!--/.fluid-container-->

        <script src="vendors/jquery-1.9.1.js"></script>
        <script src="bootstrap/js/bootstrap.min.js"></script>
        <script src="vendors/datatables/js/jquery.dataTables.min.js"></script>


        <script src="assets/scripts.js"></script>
        <script src="assets/DT_bootstrap.js"></script>
        <script>
        $(function() {
            
        });
		
		function save()
		{
			window.location.href = "update_list.php";	
		}
		
		function back_page()
		{
			window.location.href = "proxy_list.php";
				
		}
        </script>
    </body>

</html>