<!DOCTYPE html>
<?php
	include_once "cn_lang.php";
	include_once 'common.php';
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
    
    <body onLoad="timeout_flash()">
        <div class="container-fluid">
            <div class="row-fluid">
              <div class="span13" id="content">
                <div class="row-fluid">
                   <!-- block -->
						<div class="block">
                            <div class="navbar navbar-inner block-header">
                                <div class="muted pull-left"><?php echo $lang_array["options_asyncupdate_flash_text3"] ?></div>
                            </div>
                            
                            <div class="block-content collapse in">
                                <div class="span12">
                                	<label id="tip" style="color:#F00"><?php echo $lang_array["options_asyncupdate_flash_text1"] ?></label>
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
		
		var loadtime = 0;
		function loadXMLDoc()
		{
			var xmlhttp;
			if (window.XMLHttpRequest)
			{
				//  IE7+, Firefox, Chrome, Opera, Safari 浏览器执行代码
				xmlhttp=new XMLHttpRequest();
			}
			else
			{
				// IE6, IE5 浏览器执行代码
				xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
			}
			xmlhttp.onreadystatechange=function()
			{
				//alert(xmlhttp.status);
				if(xmlhttp.readyState==4 && xmlhttp.status==200)
				{
					if(xmlhttp.responseText == "END")
					{
						document.getElementById("tip").innerHTML = "<?php echo $lang_array["options_asyncupdate_flash_text2"] ?>";
					}
					else
					{
						document.getElementById("tip").innerHTML = "<?php echo $lang_array["options_asyncupdate_flash_text4"] ?>" + xmlhttp.responseText;
					}
				}
			}
			xmlhttp.open("GET","update_async.php",true);
			xmlhttp.send();
		}
		
		function timeout_flash()
		{
			var timer = setInterval(function() {    
    			loadXMLDoc();
			}, 1000);
		}
        </script>
    </body>

</html>