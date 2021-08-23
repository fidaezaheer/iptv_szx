<!DOCTYPE html>
<?php
	include_once "cn_lang.php";
	include_once 'common.php';

	$sql = new DbSql();
	$sql->login();

	$tag = "watermark";
	
	$mytable = "live_panal_table";
	$sql->connect_database_default();
	$mydb = $sql->get_database();
	$sql->create_database($mydb);
	$sql->create_table($mydb, $mytable, "tag longtext, value longtext");

	$watermark = $sql->query_data($mydb, $mytable,"tag","watermark","value");
	$showscroll = $sql->query_data($mydb, $mytable,"tag","showscroll","value");
	$lrkey = $sql->query_data($mydb, $mytable,"tag","lrkey","value");
	$listad = $sql->query_data($mydb, $mytable,"tag","adliveimage","value");
	$watermarksite = $sql->query_data($mydb, $mytable,"tag","watermarksite","value");
	$showicon = $sql->query_data($mydb, $mytable,"tag","showicon","value");
	$watermarkdip1 = $sql->query_data($mydb, $mytable,"tag","watermarkdip1","value");
	$watermarkdip2 = $sql->query_data($mydb, $mytable,"tag","watermarkdip2","value");
	$showid = $sql->query_data($mydb, $mytable,"tag","showid","value");
	$showscrolltimes = $sql->query_data($mydb, $mytable,"tag","showscrolltimes","value");
	$adliveimagesite = $sql->query_data($mydb, $mytable,"tag","adliveimagesite","value");
	$showplaylist = $sql->query_data($mydb, $mytable,"tag","showplaylist","value");
	$playtimeout = $sql->query_data($mydb, $mytable,"tag","playtimeout","value");
	if($playtimeout == null)
		$playtimeout = 30;
		
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
    
    <body>
        <div class="container-fluid">
            <div class="row-fluid">
              <div class="span13" id="content">
                <div class="row-fluid">
                   <!-- block -->
						<div class="block">
                            <div class="navbar navbar-inner block-header">
                                <div class="muted pull-left"><?php echo $lang_array["live_panal_edit_text1"] ?></div>
                            </div>
                            
                            <div class="block-content collapse in">
                                <div class="span12">
                                	<form class="form-horizontal" method="post" action="live_panal_edit_post.php" enctype='multipart/form-data'>
                                    	<fieldset><br/>
                                   	<div class="control-group">
                                       <label class="control-label" for="focusedInput"><?php echo $lang_array["live_panal_edit_text2"] ?></label>
                                       <div class="controls">
                                       		<label class="control-label" for="focusedInput" style="text-align:left;">
    										<?php
												if($watermark == null)
												{
													echo $lang_array["live_panal_edit_text4"];
												}
												else
												{
													echo $watermark;
												}
											?>
                                            </label>
                                            &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                            &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                            <input class="btn btn-primary" name="" type="button" value="<?php echo $lang_array["live_panal_edit_text6"] ?>" onclick="recovery()"/>
                                       </div>
                                   	</div>
                                    <div class="control-group">
                                       <label class="control-label" for="focusedInput"><?php echo $lang_array["live_panal_edit_text5"] ?></label>
                                       <div class="controls">
                                          <input class="input-file uniform_on" id="file" name="file" type="file">
                                          
                                       </div>
                                   	</div>
                                    
                                    <div class="control-group">
                                       <label class="control-label" for="focusedInput"><?php echo $lang_array["live_panal_edit_text11"] ?></label>
                                       <div class="controls">
                                       		<input style="width:15px;height:28px;" type="radio" name="site" id="site0" value="0" <?php if($watermarksite == 0) echo "checked"?>/><?php echo $lang_array["live_panal_edit_text12"] ?>
                                       		&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
        							   		<input style="width:15px;height:28px;" type="radio" name="site" id="site1" value="1" <?php if($watermarksite == 1) echo "checked"?>/><?php echo $lang_array["live_panal_edit_text13"] ?>
                                       		&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                          	<input style="width:15px;height:28px;" type="radio" name="site" id="site2" value="2" <?php if($watermarksite == null || $watermarksite == 2) echo "checked"?>/><?php echo $lang_array["live_panal_edit_text14"] ?>
                                       		&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                            <input style="width:15px;height:28px;" type="radio" name="site" id="site3" value="3" <?php if($watermarksite == 3) echo "checked"?>/><?php echo $lang_array["live_panal_edit_text15"] ?>
                                            &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                            <input style="width:15px;height:28px;" type="radio" name="site" id="site3" value="-1" <?php if($watermarksite == -1) echo "checked"?>/><?php echo $lang_array["live_panal_edit_text17"] ?>
                                       		&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                            
                                            <?php echo $lang_array["live_panal_edit_text18"] ?>&nbsp;&nbsp;<input class="input-mini focused" id="dip1" name="dip1" type="text" value="<?php echo $watermarkdip1; ?>">
                                            &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                            
                                            <?php echo $lang_array["live_panal_edit_text19"] ?>&nbsp;&nbsp;<input class="input-mini focused" id="dip2" name="dip2" type="text" value="<?php echo $watermarkdip2; ?>">
                                       </div>
                                   	</div>
                                    
                                    <div class="control-group">
                                       <label class="control-label" for="focusedInput"><?php echo $lang_array["live_panal_edit_text8"] ?></label>
                                       <div class="controls">
                                       	<input style="width:15px;height:28px;" type="radio" name="showscroll" id="showscroll0" value="0" <?php if($showscroll == null || $showscroll == 0) echo "checked"?>/><?php echo $lang_array["no_text1"] ?>
                                       &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
        							   	<input style="width:15px;height:28px;" type="radio" name="showscroll" id="showscroll1" value="1" <?php if($showscroll == 1) echo "checked"?>/><?php echo $lang_array["yes_text1"] ?>
                                        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;    
                                        <?php echo $lang_array["live_panal_edit_text23"] ?>&nbsp;&nbsp;<input class="input-mini focused" id="showscrolltimes" name="showscrolltimes" type="text" value="<?php echo $showscrolltimes; ?>">&nbsp;&nbsp;<?php echo $lang_array["live_panal_edit_text24"] ?>
                                          
                                       </div>
                                   	</div>
                                    
                                    
                                    <div class="control-group">
                                       <label class="control-label" for="focusedInput"><?php echo $lang_array["live_panal_edit_text7"] ?></label>
                                       <div class="controls">
                                       	<input style="width:15px;height:28px;" type="radio" name="lrkey" id="lrkey0" value="0" <?php if($lrkey == null || $lrkey == 0) echo "checked"?>/><?php echo $lang_array["no_text1"] ?>
                                       &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
        							   	<input style="width:15px;height:28px;" type="radio" name="lrkey" id="lrkey1" value="1" <?php if($lrkey == 1) echo "checked"?>/><?php echo $lang_array["yes_text1"] ?>
                                          
                                       </div>
                                   	</div>
                                    
                                    <div class="control-group">
                                       <label class="control-label" for="focusedInput"><?php echo $lang_array["live_panal_edit_text9"] ?></label>
                                       <div class="controls">
                                       		<input style="width:15px;height:28px;" type="radio" name="listad" id="listad0" value="0" <?php if($listad == null || $listad == 0) echo "checked"?>/><?php echo $lang_array["no_text1"] ?>
                                       		&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
        							   		<input style="width:15px;height:28px;" type="radio" name="listad" id="listad1" value="1" <?php if($listad == 1) echo "checked"?>/><?php echo $lang_array["yes_text1"] ?>
                                            &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                            <?php echo $lang_array["live_panal_edit_text9"] ?>
                                            &nbsp;&nbsp;&nbsp;
                                            <input style="width:15px;height:28px;" type="radio" name="adliveimagesite" id="listadsite0" value="0" <?php if($adliveimagesite == 0) echo "checked"?>/><?php echo $lang_array["live_panal_edit_text25"] ?>
                                            &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                            <input style="width:15px;height:28px;" type="radio" name="adliveimagesite" id="listadsite1" value="1" <?php if($adliveimagesite == null || $adliveimagesite == 1) echo "checked"?>/><?php echo $lang_array["live_panal_edit_text26"] ?>
                                       		&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                            <input style="width:15px;height:28px;" type="radio" name="adliveimagesite" id="listadsite2" value="2" <?php if($adliveimagesite == 2) echo "checked"?>/><?php echo $lang_array["live_panal_edit_text27"] ?>
                                            &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                          	<input class="btn btn-primary" name="" type="button" value="<?php echo $lang_array["live_panal_edit_text10"] ?>" onclick="update_ad_image()"/>
        			
                                       </div>
                                   	</div>
                                    
                                    <div class="control-group">
                                       <label class="control-label" for="focusedInput"><?php echo $lang_array["live_panal_edit_text16"] ?></label>
                                       <div class="controls">
                                       	<input style="width:15px;height:28px;" type="radio" name="showicon" id="showicon0" value="0" <?php if($showicon == 0) echo "checked"?>/><?php echo $lang_array["no_text1"] ?>
                                       &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
        							   	<input style="width:15px;height:28px;" type="radio" name="showicon" id="showicon1" value="1" <?php if($showicon == null || $showicon == 1) echo "checked"?>/><?php echo $lang_array["yes_text1"] ?>
                                          
                                       </div>
                                   	</div>
                                    
                                    
                                    <div class="control-group">
                                       <label class="control-label" for="focusedInput"><?php echo $lang_array["live_panal_edit_text20"] ?></label>
                                       <div class="controls">
                                       	<input style="width:15px;height:28px;" type="radio" name="showid" id="showid0" value="0" <?php if($showid  == 0) echo "checked"?>/><?php echo $lang_array["live_panal_edit_text21"] ?>
                                       &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
        							   	<input style="width:15px;height:28px;" type="radio" name="showid" id="showid1" value="1" <?php if($showid  == null || $showid  == 1) echo "checked"?>/><?php echo $lang_array["live_panal_edit_text22"] ?>
                                          
                                       </div>
                                   	</div>
                                    
                                    <div class="control-group">
                                       <label class="control-label" for="focusedInput"><?php echo $lang_array["live_panal_edit_text28"] ?></label>
                                       <div class="controls">
                                       	<input style="width:15px;height:28px;" type="radio" name="showplaylist" id="showplaylist0" value="0" <?php if($showplaylist  == 0) echo "checked"?>/><?php echo $lang_array["no_text1"] ?>
                                       &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
        							   	<input style="width:15px;height:28px;" type="radio" name="showplaylist" id="showplaylist1" value="1" <?php if($showplaylist  == null || $showplaylist  == 1) echo "checked"?>/><?php echo $lang_array["yes_text1"] ?>
                                          
                                       </div>
                                   	</div>
                                    
                                    <div class="control-group">
                                       <label class="control-label" for="focusedInput"><?php echo $lang_array["live_panal_edit_text29"] ?></label>
                                       <div class="controls">
                                       	<input style="width:15px;height:28px;" type="radio" name="playtimeout" id="playtimeout0" value="0" <?php if($playtimeout == null || intval($playtimeout)  <= 0) echo "checked"?>/><?php echo $lang_array["no_text1"] ?>
                                       &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
        							   	<input style="width:15px;height:28px;" type="radio" name="playtimeout" id="playtimeout1" value="1" <?php if(intval($playtimeout)  >= 1) echo "checked"?>/><?php echo $lang_array["yes_text1"] ?>
                                       &nbsp;&nbsp;&nbsp;
                                        <?php echo $lang_array["live_panal_edit_text30"] ?>:
                                       &nbsp;
                                        <input class="input-mini focused" name="text_playtimeout" type="text" id="text_playtimeoutid" size="6" value="<?php if(intval($playtimeout) >= 1) echo $playtimeout ?>"/>&nbsp;<?php echo $lang_array["live_panal_edit_text31"] ?>
                                       </div>
                                   	</div>
                                    
                                    <div class="form-actions">
   										<button type="submit" class="btn btn-primary" onClick="save()"><?php echo $lang_array["save_text1"] ?></button>
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

        <link href="vendors/datepicker.css" rel="stylesheet" media="screen">
        <link href="vendors/uniform.default.css" rel="stylesheet" media="screen">
        <link href="vendors/chosen.min.css" rel="stylesheet" media="screen">

        <link href="vendors/wysiwyg/bootstrap-wysihtml5.css" rel="stylesheet" media="screen">
        
        <script src="vendors/jquery-1.9.1.js"></script>
        <script src="bootstrap/js/bootstrap.min.js"></script>
        <script src="vendors/jquery.uniform.min.js"></script>
        <script src="vendors/chosen.jquery.min.js"></script>
        <script src="vendors/bootstrap-datepicker.js"></script>

        <script src="vendors/wysiwyg/wysihtml5-0.3.0.js"></script>
        <script src="vendors/wysiwyg/bootstrap-wysihtml5.js"></script>

        <script src="vendors/wizard/jquery.bootstrap.wizard.min.js"></script>

		<script type="text/javascript" src="vendors/jquery-validation/dist/jquery.validate.min.js"></script>
		<script src="assets/form-validation.js"></script>
        
		<script src="assets/scripts.js"></script>
        <script>
		
		jQuery(document).ready(function() {   
	   		FormValidation.init();
		});

        $(function() {
            $(".datepicker").datepicker();
            $(".uniform_on").uniform();
            $(".chzn-select").chosen();
            $('.textarea').wysihtml5();

            $('#rootwizard').bootstrapWizard({onTabShow: function(tab, navigation, index) {
                var $total = navigation.find('li').length;
                var $current = index+1;
                var $percent = ($current/$total) * 100;
                $('#rootwizard').find('.bar').css({width:$percent+'%'});
                // If it's the last tab then hide the last button and show the finish instead
                if($current >= $total) {
                    $('#rootwizard').find('.pager .next').hide();
                    $('#rootwizard').find('.pager .finish').show();
                    $('#rootwizard').find('.pager .finish').removeClass('disabled');
                } else {
                    $('#rootwizard').find('.pager .next').show();
                    $('#rootwizard').find('.pager .finish').hide();
                }
            }});
            $('#rootwizard .finish').click(function() {
                alert('Finished!, Starting over!');
                $('#rootwizard').find("a[href*='tab1']").trigger('click');
            });
        });
		
		function update_ad_image()
		{
			window.location.href = "update_ad_live_list.php";
		}
		
		function recovery()
		{
			window.location.href = "live_panal_edit_post.php?recovery=0";	
		}

		function back_page()
		{
			window.location.href = "live_panal_2.php";
				
		}
        </script>
    </body>

</html>