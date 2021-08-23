<?php
	include_once 'common.php';
	include_once 'cn_lang.php';
	
	$sql = new DbSql();
	$sql->login();
	
	$url = urldecode($_GET["url"]);
	$serverip = $_GET["serverip"];
	
	$cmd = "urltest#" . $url;
	//$cmd = "runcmd#df";
	$ret = send($serverip,$cmd);
	$ret2 = "";
	if(strstr($ret,"Video:") != false && strstr($ret,"Audio:") != false)
		$ret2 = $lang_array["stream_channel_test_text3"];
	else
		$ret2 = $lang_array["stream_channel_test_text4"];
		

?>

<!DOCTYPE html>
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
                                <div class="muted pull-left"><?php echo $lang_array["stream_channel_test_text1"] ?></div>
                            </div>
                            
                            <div class="block-content collapse in">
                                <div class="span12">
                                	<form class="form-horizontal" method="post" action="user_post.php" enctype='multipart/form-data'>
                                    	<fieldset>
                                        	<div class="control-group">
                                       			<?php echo $ret ?>
                                   			</div>
                                   		</fieldset>
                                        
                                        	<hr/>
                                        	<br/>
                                        	<div class="control-group">
                                       			<div class="controls">
                                       				<?php echo $lang_array["stream_channel_test_text2"] . ":" . $ret2 ?>
                                       			</div>
                                   			</div>
                                        
                                        
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
		
		function GetRadioValue(RadioName){
    		var obj;
    		obj=document.getElementsByName(RadioName);
    		if(obj!=null){
       		 	var i;
        		for(i=0;i<obj.length;i++){
            		if(obj[i].checked){
                		return obj[i].value;
            		}
        		}
    		}
    		return null;
		}
		
		function batch_export()
		{
			var value = GetRadioValue("export");
			window.location.href = "batch_export_post.php?type=" + value;
		}
        </script>
    </body>

</html>