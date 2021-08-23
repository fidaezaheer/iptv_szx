<?php
	include_once "cn_lang.php";
	include_once "common.php";
	$sql = new DbSql();
	$sql->login();
	
	$sql->connect_database_default();
	$mydb = $sql->get_database();
	$sql->create_database($mydb);
	
	$serverip = $_GET["serverip"];
	
	$mytable = "stream_server_list_table";
	$sql->create_table($mydb, $mytable, "serverid int, serverip text, tip text, os text, version int");
	$serverip_namess = $sql->fetch_datas($mydb, $mytable);
	
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
                                <div class="muted pull-left"><?php echo $lang_array["stream_channel_batch_del_list_text1"] ?></div>
                            </div>
                            
                            <div class="block-content collapse in">
                                <div class="span12">
                                	<form class="form-horizontal" method="post" action="stream_channel_batch_del.php" enctype='multipart/form-data'>
<fieldset>
                                        	<div class="control-group">
                                            	<label class="control-label" for="focusedInput"><?php echo $lang_array["stream_channel_batch_del_list_text2"] ?></label>
                                      			<div class="controls">
                                            		<select id="serverip" name="serverip"  style='width:180px;' onchange="">
													<?php
													foreach($serverip_namess as $names) 
													{
														$selected = "";
														if($names[1] == $serverip)
															$selected = "selected";
														
														if(strlen($names[2]) > 0)
															echo "<option value='" . $names[1] . "' style='width:480px;' " . $selected . ">" . $names[1] . "(" . $names[2] . ")" . "</option>";
														else
															echo "<option value='" . $names[1] . "' style='width:480px;' " . $selected . ">" . $names[1] . "</option>";
												
													}
													?>
    												</select>
                                      		 	</div>
                                                <div class="form-actions">
   													<button type="button" class="btn btn-primary" onClick="save()"><?php echo $lang_array["del_text1"] ?></button>
                                                    <button type="reset" class="btn" onclick="back_page()"><?php echo $lang_array["back_text1"] ?></button>
                            					</div>
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
		
		function save()
		{
			var value = document.getElementById("serverip").value;
			var r=confirm("<?php echo $lang_array["stream_channel_list_text32"] ?>:" + value);
			if(r==true)
			{
				
				window.location.href = "stream_channel_batch_del.php?serverip=" +value;
			}
		}
		
		function back_page()
		{
			window.location.href = "stream_channel_list.php";
		}
        </script>
    </body>

</html>