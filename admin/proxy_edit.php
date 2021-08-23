<!DOCTYPE html>
<?php
	include_once "cn_lang.php";
	include_once "common.php";
	$sql = new DbSql();
	$sql->login();

	$level = 1;
	if(isset($_GET["level"]))
	{
		$level = intval($_GET["level"]);
	}
	
	$name = trim($_GET["proxy"]);
	$year = "";
	$month = "";
	$day = "";
	
	$sql->connect_database_default();
	$mydb = $sql->get_database();
	$sql->create_database($mydb);
	
	$mytable = "proxy_table";
	$sql->create_table($mydb, $mytable, 
					"name text, password text, ptip text, edit int, watermark text, 
					validity date, allow int, remark text, ccount int, panal int, 
					pwmd5 int, logonum text, terlang int, proxylevel int, proxybelong text");	
			
	$validity = $sql->query_data($mydb, $mytable,"name",$name,"validity");
	$vallow = $sql->query_data($mydb, $mytable,"name",$name,"allow");
	$proxybelong = $sql->query_data($mydb, $mytable,"name",$name,"proxybelong");

	if($validity == null || strlen($validity) < 4)
	{
		$now = date("Y-m-d");
		$nows = explode("-",$now);
		$year = $nows[0];
		$month = $nows[1];
		$day = $nows[2];
	}
	else
	{
		$nowss = date("Y-m-d");
		$nowsss = explode("-",$nowss);
		
		$nows = explode("-",$validity);
		$year = $nows[0];
		if(intval($year) < 2016)
			$year = $nowsss[0];

		$month = $nows[1];
		if(intval($month) < 1)
			$month = $nowsss[1];
			
		$day = $nows[2];
		if(intval($day) < 1)
			$day = $nowsss[2];		
	}
	
	$namess = $sql->fetch_datas($mydb, $mytable);
	
	$password = $sql->query_data($mydb, $mytable,"name",$name,"password");
	

	
	$ptip = $sql->query_data($mydb, $mytable,"name",$name,"ptip");
	$edit = $sql->query_data($mydb, $mytable,"name",$name,"edit");
	$watermark = $sql->query_data($mydb, $mytable,"name",$name,"watermark");
	$remark = $sql->query_data($mydb, $mytable,"name",$name,"remark");
	$ccount = $sql->query_data($mydb, $mytable,"name",$name,"ccount");
	
	$mytable = "proxy_download_table";
	$sql->create_table($mydb, $mytable, "name text, sbackground text, sepg text, ebackground text, livepanel text, vodpanel text, download text, allow text, watermark text, scrolltext text, version int");
	$download = $sql->query_data($mydb, $mytable,"name",$name,"download");
	$allow = $sql->query_data($mydb, $mytable,"name",$name,"allow");
	$version = $sql->query_data($mydb, $mytable,"name",$name,"version");
	$scrolltext = $sql->query_data($mydb, $mytable,"name",$name,"scrolltext");

	$sql->disconnect_database();
?>


<?php
function dayToday($time)
{
	$startdate=strtotime(date("Y-m-d"));
	$enddate=strtotime($time);
	$days=round(($enddate-$startdate)/86400)+1;
	if($days < 0)
		$days = 0;
		
	return $days;		
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
    
    <body>
        <div class="container-fluid">
            <div class="row-fluid">
              	<div class="span13" id="content">
                <div class="row-fluid">
                   <!-- block -->
						<div class="block">
                          <div class="navbar navbar-inner block-header">
                                <div class="muted pull-left"><?php echo $lang_array["proxy_edit_text1"] ?>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<?php echo $name ?></div>
                            </div>
                            
                            <div class="block-content collapse in">
                                <div class="span12">
                                	<form class="form-horizontal" name="authform" method="post" action="proxy_post.php?proxy=<?php echo $name; ?>&level=<?php echo $level ?>" enctype='multipart/form-data'>
                                    <fieldset><br/>
                                    
									<div class="control-group">
										<label class="control-label" ><?php echo $lang_array["proxy_edit_text14"] ?></label>
                                        <div class="controls">
											<input name="allow" type="radio" id="allow0" value="1" style="width:15px;height:28px;" <?php if($vallow == 1 || $vallow == -1) echo "checked"; ?>><?php  echo $lang_array["proxy_edit_text12"] ?>&nbsp;&nbsp;
                                            <input name="allow" type="radio" id="allow1" value="0" style="width:15px;height:28px;" <?php if($vallow == 0) echo "checked"; ?>><?php  echo $lang_array["proxy_edit_text13"] ?>
                                        </div>    
                                    </div>
                                                                        
                                    <div class="control-group">
                                        <label class="control-label" for="fileInput"><?php echo $lang_array["proxy_edit_text2"] ?></label>
                                        <div class="controls">
                                          <input class="input-file uniform_on" id="watermark" name="watermark" type="file">&nbsp;&nbsp;&nbsp;&nbsp;<img src="../images/livepic/<?php echo $watermark ?>" width="20" height="20">
                                        </div>
                                    </div>
                                    
                                    <div class="control-group">
                                       <label class="control-label" for="focusedInput"><?php echo $lang_array["proxy_edit_text4"] ?></label>
                                       <div class="controls">
                                          <input class="input-xlarge focused" id="password" name="password" type="text" value="">
                                       </div>
                                   	</div>
                                    
                                    <?php
									if($level == 2)
									{
                                    	echo "<div class='control-group'>";
                                    	echo "<label class='control-label' for='focusedInput'>" . $lang_array["proxy_add_text7"] . "</label>";
                                    	echo "<div class='controls'>";
                                    	echo "<select class='input-medium' name='proxybelong' id='proxybelong'>";
                                        	foreach($namess as $names) 
											{
												if($names[13] == 1 || $names[13] == null)
												{
													$selected = "";
													if(strlen($proxybelong)>0 && strlen($names[0]) > 0 && $proxybelong == $names[0])
														$selected = "selected";
														
													if(strlen($names[7]) > 0)
														echo "<option value=\"" . $names[0] . "\" " . $selected  . ">" . $names[0] . "(" . $names[7] . ")" . "</option>";
													else
														echo "<option value=\"" . $names[0] . "\" " . $selected  . ">" . $names[0] . "</option>";
												}
											}
                                    	echo "</select>";
                                    	echo "</div>";
                                   		echo "</div>";
									}
                                    ?>
                                    
                                    <div class="control-group">
                                       <label class="control-label" for="focusedInput"><?php echo $lang_array["proxy_edit_text19"] ?></label>
                                       <div class="controls">
                                          <input class="input-mini focused" id="tversion" name="tversion" type="text" value="<?php echo $version ?>">
                                       </div>
                                   	</div>
                                    
                                    <div class="control-group">
                                       <label class="control-label" for="focusedInput"><?php echo $lang_array["proxy_edit_text3"] ?></label>
                                       <div class="controls">
                                          <input class="input-xxlarge focused" id="tupdate" name="tupdate" type="text" value="<?php echo $download ?>">
                                       </div>
                                   	</div>
                                    <div class="control-group">
										<label class="control-label" ><?php echo $lang_array["proxy_edit_text5"] ?></label>
                                        <div class="controls">
											<input name="rupdate" type="radio" id="rupdate0" value="1" style="width:15px;height:28px;" <?php if($allow != "0") echo "checked"; ?>><?php  echo $lang_array["yes_text1"] ?>&nbsp;&nbsp;
                                           <input name="rupdate" type="radio" id="rupdate1" value="0" style="width:15px;height:28px;" <?php if($allow == "0") echo "checked"; ?>><?php  echo $lang_array["no_text1"] ?>	</div>    
                                    </div>
                                    <div class="control-group">
										<label class="control-label" ><?php echo $lang_array["proxy_edit_text6"] ?></label>
                                        <div class="controls">
											<input name="redit" type="radio" id="redit0" value="1" style="width:15px;height:28px;" <?php if($edit != 0) echo "checked"; ?>><?php  echo $lang_array["yes_text1"] ?>&nbsp;&nbsp;
                                            <input name="redit" type="radio" id="redit1" value="0" style="width:15px;height:28px;" <?php if($edit == 0) echo "checked"; ?>><?php  echo $lang_array["no_text1"] ?>
&nbsp;&nbsp;                                           
                                            <input name="redit" type="radio" id="redit2" value="2" style="width:15px;height:28px;" <?php if($edit == 2) echo "checked"; ?>><?php  echo $lang_array["proxy_edit_text20"] ?>
                                        </div>    
                                    </div>     
                                    <div class="control-group">
										<label class="control-label" ><?php echo $lang_array["proxy_edit_text15"] ?></label>
                                        <div class="controls">
											<input name="raccount" type="radio" id="raccount0" value="1" style="width:15px;height:28px;" <?php if($ccount == 1) echo "checked"; ?>><?php  echo $lang_array["yes_text1"] ?>&nbsp;&nbsp;
                                            <input name="raccount" type="radio" id="raccount1" value="0" style="width:15px;height:28px;" <?php if($ccount != 1) echo "checked"; ?>><?php  echo $lang_array["no_text1"] ?>
                                        </div>    
                                    </div>                                
                                    <div class="control-group">
                                       <label class="control-label" for="focusedInput"><?php echo $lang_array["proxy_edit_text7"] ?></label>
                                       <div class="controls">
                                          <input class="input-xxlarge" id="ptip" name="ptip" type="text" value="<?php echo $ptip ?>">
                                       </div>
                                   	</div>   
                                    
                                    <div class="control-group">
                                       <label class="control-label" for="focusedInput"><?php echo $lang_array["proxy_edit_text8"] ?></label>
                                       <div class="controls">
                                          <input class="input-mini" id="year" name="year" type="text" value="<?php echo $year ?>"><?php  echo $lang_array["proxy_edit_text9"] ?>
                                          <input class="input-mini" id="month" name="month" type="text" value="<?php echo $month ?>"><?php  echo $lang_array["proxy_edit_text10"] ?>
                                          <input class="input-mini" id="day" name="day" type="text" value="<?php echo $day ?>"><?php  echo $lang_array["proxy_edit_text11"] ?>
                                       </div>
                                   	</div>  
                                    
                                    <div class="control-group">
                                       <label class="control-label" for="focusedInput"><?php echo $lang_array["proxy_list_text11"] ?></label>
                                       <div class="controls">
                                          <input class="input-xxlarge" id="remark" name="remark" type="text" value="<?php echo $remark ?>">
                                       </div>
                                   	</div>  
                                    
                                    <div class="control-group">
										<label class="control-label" ><?php echo $lang_array["proxy_version_text11"] ?></label>
                                        <div class="controls">
											<input class="input-xxlarge focused" id="scrolltext" name="scrolltext" type="text" value="<?php echo $scrolltext;?>">
                                        </div>    
                                    </div> 
                                                                    
                                    <div class="form-actions">
   										<button type="button" class="btn btn-primary" onClick="save()"><?php echo $lang_array["save_text1"] ?></button>
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

        $(function() {
            
        });
		
		function save()
		{
			var password = document.getElementById("password").value;
			if(password.length <= 0)
			{
				alert("<?php echo $lang_array["proxy_edit_text17"] ?>");
				document.authform.submit();
			}
			else
			{
				alert("<?php echo $lang_array["proxy_edit_text18"] ?>" +　":" + password);
				document.authform.submit();
			}
		}
		
		function back_page()
		{
			window.location.href = "proxy_list.php?level=<?php echo $level ?>";
				
		}
        </script>
    </body>

</html>