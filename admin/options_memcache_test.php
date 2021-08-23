<!DOCTYPE html>
<?php
	include_once "cn_lang.php";
	include_once 'common.php';
	$sql = new DbSql();
	$sql->login();
	
	include_once 'memcache.php';
	$mem = new GMemCache();
	$isok = false;
	if($mem->used())
	{
		$mem->connect();
		$mem->set("memcachetest","123456");
		$mem_test = $mem->get("memcachetest");
		if($mem_test == "123456")
			$isok = true;
		$mem->close();
	}	
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
    
    <body onLoad="memcache_test()">
        <div class="container-fluid">
            <div class="row-fluid">
              <div class="span13" id="content">
                <div class="row-fluid">
                   <!-- block -->
						<div class="block">
                            <div class="navbar navbar-inner block-header">
                                <div class="muted pull-left"><?php echo $lang_array["options_memcache_test_text3"] ?></div>
                            </div>
                            
                            <div class="block-content collapse in">
                                <div class="span12">
                                	<label id="tip" style="color:#F00"></label>
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
		
		function memcache_test()
		{
<?php
			if($isok == true)
				echo "document.getElementById(\"tip\").innerHTML =\"" . $lang_array["options_memcache_test_text1"] . "\"";
			else
				echo "document.getElementById(\"tip\").innerHTML =\"" . $lang_array["options_memcache_test_text2"] . "\"";
?>
		}
        </script>
    </body>

</html>