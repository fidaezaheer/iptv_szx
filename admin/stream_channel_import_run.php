<!DOCTYPE html>
<?php
	include_once "cn_lang.php";
	include_once 'common.php';
	$sql = new DbSql();
	
	$serverip = $_GET["serverip"];	
	
	$sql->connect_database_default();
	$mydb = $sql->get_database();
	$sql->create_database($mydb);
	
	$mytable = "stream_channel_batch_import_tmp_table";
	$sql->create_table($mydb, $mytable, "urlid int, name text, url text, online int, cache int, 
										path text, tabel text, serverip text, url1 text, url2 text, 
										url3 text, tip text, status text, receive text");		
	$count = $sql->count_fetch_datas($mydb, $mytable);
	
	$serverip = $_GET["serverip"];
	
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
		var count = <?php echo $count ?>;
		var serverip = "<?php echo $serverip ?>";
		function loadXMLDoc(serverip,index)
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
					document.getElementById("tip").innerHTML = xmlhttp.responseText;
				}
			}
			xmlhttp.open("GET","stream_channel_import_run_post.php?serverip="+serverip+"&index="+index,true);
			xmlhttp.send();
		}
		
		function timeout_flash()
		{
			var index = 0;
			var timer = setInterval(function() {    
    			loadXMLDoc(serverip,index);
				index++;
				if(index > count)
					return;
			}, 3000);
		}
        </script>
    </body>

</html>