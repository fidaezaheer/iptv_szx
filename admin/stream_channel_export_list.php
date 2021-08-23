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
	$sql->disconnect_database();
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
                                <div class="muted pull-left"><?php echo $lang_array["stream_channel_export_list_text1"] ?></div>
                            </div>
                            
                            <div class="block-content collapse in">
                                <div class="span12">
                                	<form class="form-horizontal" method="post" action="stream_channel_export_post.php" enctype='multipart/form-data'>
<fieldset>
                                        	<div class="control-group">
                                            	<label class="control-label" for="focusedInput"><?php echo $lang_array["stream_channel_sync_list_text2"] ?></label>
                                      			<div class="controls">
                                            		<select id="serverip" name="serverip"  style='width:180px;' onchange="">
													<?php
													foreach($serverip_namess as $names) 
													{
														$selected = "";
														if($names[1] == $serverip)
															$selected = "selected";
															
														if(strlen($names[2]) > 0)
															echo "<option value='" . $names[1] . "' style='width:240px;' " . $selected . ">" . $names[1] . "(" . $names[2] . ")" . "</option>";
														else
															echo "<option value='" . $names[1] . "' style='width:240px;' " . $selected . ">" . $names[1] . "</option>";
												
													}
													?>
    												</select>
                                      		 	</div>
                                                <br/>
                                                
                                                <div class="control-group">
                                       				<label class="control-label" for="focusedInput" style="text-shadow:#0CF"><?php echo $lang_array["stream_channel_export_list_text5"] ?></label>
                                       				<div class="controls">
                                       					<input style="width:15px;height:28px;" type="radio" name="export" value="txt" id="export0" checked/>TXT&nbsp;
                                       					<input style="width:15px;height:28px;" type="radio" name="export" value="xml" id="export1" />XML&nbsp;
                                                        <input style="width:15px;height:28px;" type="radio" name="export" value="source" id="export2" />SOURCE&nbsp;
                                       				</div>
                                   				</div>
                                                <div class="form-actions">
   													<button type="submit" class="btn btn-primary"><?php echo $lang_array["stream_channel_export_list_text2"] ?></button>
                                                    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                                    <?php 
														if(isset($_GET["success"]) && intval($_GET["success"]) == 1)
														{
															echo $lang_array["stream_channel_export_list_text4"];	
														}
														else if(isset($_GET["success"]) && intval($_GET["success"]) == 0)
														{
															echo $lang_array["stream_channel_export_list_text3"];	
														}
													?>
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
        </script>
    </body>

</html>