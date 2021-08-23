<!DOCTYPE html>
<?php
	include_once "cn_lang.php";
	include_once "common.php";
	$sql = new DbSql();
	$sql->login();
	
	$sql->connect_database_default();
	$mydb = $sql->get_database();
	$sql->create_database($mydb);
	
	$mytable = "custom_close_table";
	$sql->create_table($mydb, $mytable, "allow longtext, value longtext");
	
	$allow = $sql->query_data($mydb, $mytable, "allow", "allow", "value");
	$days = $sql->query_data($mydb, $mytable, "allow", "days", "value");
	$allocation = $sql->query_data($mydb, $mytable, "allow", "allocation", "value");
	$playlist = $sql->query_data($mydb, $mytable, "allow", "playlist", "value");
	$contact = $sql->query_data($mydb, $mytable, "allow", "contact", "value");
	$limit = $sql->query_data($mydb, $mytable, "allow", "limit", "value");
	$show = $sql->query_data($mydb, $mytable, "allow", "show", "value");
	$samemac = $sql->query_data($mydb, $mytable, "allow", "samemac", "value");
	$startup = $sql->query_data($mydb, $mytable, "allow", "startup", "value");

	if($days == null)
		$days = 0;
	if($samemac == null)
		$samemac = "no";
	if($startup == null)
		$startup = "3";
				
	$limitfile = "";
	$limitlists=array();
	if(isset($_GET["limitfile"]))
	{
		$limitfile = $_GET["limitfile"];
		if(strlen($limitfile) >= 4)
		{
			$handle = fopen('backup/' . $limitfile , 'r');
    		while(!feof($handle))
			{
				$l = fgets($handle);
				if(strlen($l) > 8)
					array_push($limitlists,$l);
    		}
    		fclose($handle);
		}	
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
                                <div class="muted pull-left"><?php echo $lang_array["custom_all_edit_text20"] ?></div>
                            </div>
                            
                            <div class="block-content collapse in">
                                <div class="span12">
                                	<form class="form-horizontal" name="authform" method="post" action="custom_all_edit_upload.php" enctype='multipart/form-data'>
                                    <fieldset><br/>
                                 	<div class="control-group">
                                       <label class="control-label" for="focusedInput" style="text-shadow:#0CF"><?php echo $lang_array["custom_all_edit_text1"] ?></label>
                                       <div class="controls">
                                       <input name="radio" type="radio" id="radio0" value="yes" style="width:15px;height:28px;" <?php if($allow != "no" && $allow != "pre")  echo "checked"?> onclick="" ><?php echo $lang_array["custom_all_edit_text2"] ?>&nbsp;&nbsp;&nbsp;&nbsp;
                                       <input name="radio" type="radio" id="radio1" value="no" style="width:15px;height:28px;" <?php if($allow == "no") echo "checked"?> onclick="" ><?php echo $lang_array["custom_all_edit_text3"] ?>&nbsp;&nbsp;&nbsp;&nbsp;
                                       <input name="radio" type="radio" id="radio2" value="pre" style="width:15px;height:28px;" <?php if($allow == "pre") echo "checked"?> onclick=""><?php echo $lang_array["custom_all_edit_text4"] ?>&nbsp;&nbsp;&nbsp;&nbsp;
                                       <input name="radio" type="radio" id="radio3" value="boxphone" style="width:15px;height:28px;" <?php if($allow == "boxphone") echo "checked"?> onclick=""><?php echo $lang_array["custom_all_edit_text27"] ?>&nbsp;&nbsp;&nbsp;&nbsp;
                                       <input name="radio" type="radio" id="radio4" value="cnusa" style="width:15px;height:28px;" <?php if($allow == "cnusa") echo "checked"?> onclick=""><?php echo $lang_array["custom_all_edit_text28"] ?>&nbsp;&nbsp;&nbsp;&nbsp;
                                       </div>
                                   	</div>

                                    <div class="control-group">
                                       <label class="control-label" for="focusedInput" style="text-shadow:#0CF"><?php echo $lang_array["custom_all_edit_text6"] ?></label>
                                       <div class="controls">
                                       		<input class="input-medium focused" id="days_text" name="" type="text" value="<?php echo $days ?>">&nbsp;&nbsp;&nbsp;<?php echo $lang_array["custom_all_edit_text13"] ?>
                                       </div>
                                   	</div>                                    
                                    
                                    <div class="control-group">
                                       	<label class="control-label" for="focusedInput" style="text-shadow:#0CF"><?php echo $lang_array["custom_all_edit_text7"] ?></label>
                                        <div class="controls">
											 <input name="allocation" type="radio" id="allocation2" value="all" style="width:15px;height:28px;" <?php if($allocation != "auto" && $allocation != "manually") echo "checked"?>><?php echo $lang_array["custom_all_edit_text8"] ?>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                             <input name="allocation" type="radio" id="allocation0" value="auto" style="width:15px;height:28px;" <?php if($allocation == "auto") echo "checked"?>><?php echo $lang_array["custom_all_edit_text9"] ?>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                             <input name="allocation" type="radio" id="allocation1" value="manually" style="width:15px;height:28px;" <?php if($allocation == "manually") echo "checked"?>><?php echo $lang_array["custom_all_edit_text10"] ?>  
                                             <select id="playlist_select_id" name="">
   	 										 <?php
												$mytable = "playlist_type_table";
												$sql->create_table($mydb, $mytable, "name text, space longtext, id text, playlist longtext");	
												$namess = $sql->fetch_datas($mydb, $mytable);
												foreach($namess as $names) {
													if(strcmp($playlist,$names[2]) == 0)
													{
														echo "<option value='" . $names[2] . "' selected='selected'>" . $names[0] . "</option>";
													}
													else
													{
														echo "<option value='" . $names[2] . "'>" . $names[0] . "</option>";
													}
												}
											?>
  											</select>
                                        </div> 
                                   	</div> 
                                    
                                    <div class="control-group">
                                       <label class="control-label" for="focusedInput" style="text-shadow:#0CF"><?php echo $lang_array["custom_all_edit_text18"] ?></label>
                                       <div class="controls">
                                       		<input name="limit" type="radio" id="" value="yes" style="width:15px;height:28px;" <?php if($limit == "yes") echo "checked"?>><?php echo $lang_array["yes_text1"] ?>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                             <input name="limit" type="radio" id="" value="no" style="width:15px;height:28px;" <?php if($limit != "yes") echo "checked"?>><?php echo $lang_array["no_text1"] ?>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                       </div>
                                   	</div> 
                                    
                                    <div class="control-group">
                                       <label class="control-label" for="focusedInput" style="text-shadow:#0CF"><?php echo $lang_array["custom_all_edit_text19"] ?></label>
                                       <div class="controls">
                                       		<input name="show" type="radio" id="" value="yes" style="width:15px;height:28px;" <?php if($show == "yes") echo "checked"?>><?php echo $lang_array["yes_text1"] ?>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                             <input name="show" type="radio" id="" value="no" style="width:15px;height:28px;" <?php if($show != "yes") echo "checked"?>><?php echo $lang_array["no_text1"] ?>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                       </div>
                                   	</div> 

                                    <div class="control-group">
                                       <label class="control-label" for="focusedInput" style="text-shadow:#0CF"><?php echo $lang_array["custom_all_edit_text11"] ?></label>
                                       <div class="controls">
                                            <input class="input-file uniform_on" id="file" name="file" type="file"><input class="btn btn-primary" name="" type="button" value="<?php echo $lang_array["custom_all_edit_text15"] ?>" onclick="look_limit()"/>&nbsp;<input class="btn btn-primary" type="submit" name="submit" value="<?php echo $lang_array["custom_all_edit_text12"] ?>" />&nbsp;<input class="btn btn-primary" name="" type="button" value="<?php echo $lang_array["custom_all_edit_text16"] ?>" onclick="save_limit()"/>
                                       </div>
                                   	</div> 
                                    
                                    <div class="control-group">
                                       <label class="control-label" for="focusedInput" style="text-shadow:#0CF"><?php echo $lang_array["custom_all_edit_text21"] ?></label>
                                       <div class="controls">
                                       		<input name="samemac" type="radio" id="" value="yes" style="width:15px;height:28px;" <?php if($samemac == "yes") echo "checked"?>><?php echo $lang_array["yes_text1"] ?>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                             <input name="samemac" type="radio" id="" value="no" style="width:15px;height:28px;" <?php if($samemac != "yes") echo "checked"?>><?php echo $lang_array["no_text1"] ?>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                       </div>
                                   	</div> 
                                    
                                    
                                    <div class="control-group">
                                       <label class="control-label" for="focusedInput" style="text-shadow:#0CF"><?php echo $lang_array["custom_all_edit_text22"] ?></label>
                                       <div class="controls">
                                       		<input name="startup" type="radio" id="" value="0" style="width:15px;height:28px;" <?php if(strcmp($startup,"0") == 0) echo "checked"?>><?php echo $lang_array["custom_all_edit_text23"] ?>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                            <input name="startup" type="radio" id="" value="1" style="width:15px;height:28px;" <?php if(strcmp($startup,"1") == 0) echo "checked"?>><?php echo $lang_array["custom_all_edit_text24"] ?>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                            <input name="startup" type="radio" id="" value="2" style="width:15px;height:28px;" <?php if(strcmp($startup,"2") == 0) echo "checked"?>><?php echo $lang_array["custom_all_edit_text25"] ?>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                            <input name="startup" type="radio" id="" value="3" style="width:15px;height:28px;" <?php if(strcmp($startup,"3") == 0) echo "checked"?>><?php echo $lang_array["custom_all_edit_text26"] ?>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                       </div>
                                   	</div>
                                    
                                    <div class="form-actions">
                                    	<button type="button" class="btn btn-primary" onclick="save()"><?php echo $lang_array["save_text1"] ?></button>
                                    	<input name="" class="btn btn-primary" type="button" value="<?php echo $lang_array["back_text1"] ?>" onclick="back_page()"/>
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

		function save_limit()
		{
			var limitfile2 = "";
			<?php
			$limitfile = "";
			if(isset($_GET["limitfile"]))
			{
				$limitfile = $_GET["limitfile"];
				echo "limitfile2 = '" . $_GET["limitfile"] . "';";
			}
			?>
			if(confirm("<?php echo $lang_array["custom_all_edit_text14"] ?>" + "?") == true)
			{
				if(limitfile2.length > 4)
					window.location.href = "custom_all_limit_post.php?limitfile="+limitfile2;
			}
		}

		function look_limit()
		{
			window.location.href = "custom_all_limit_list.php";
		}
		
		
		function save()
		{
			var param;
			var radio_value = GetRadioValue("radio");
			param = "allow=" + radio_value;
			if(radio_value == "pre" || radio_value == "boxphone" || radio_value == "cnusa")
			{
				var playlist;
				var days = document.getElementById("days_text").value;
				var allocation_value = GetRadioValue("allocation");
				if(allocation_value == "manually")
				{
					playlist = document.getElementById("playlist_select_id").value;
				}
				else if(allocation_value == "all")
				{
					playlist = "all";
				}
				else
				{
					playlist = "auto";
				}
		
				var limit_value = GetRadioValue("limit");
				if(limit_value == "yes")
				{
					param = param + "&limit=1";
		
				}
				
				var show_value = GetRadioValue("show");
				if(show_value == "yes")
				{
					param = param + "&show=yes";
		
				}
				
				var samemac_value = GetRadioValue("samemac");
				if(samemac_value == "yes")
				{
					param = param + "&samemac=yes";
				}
				
				var startup_value = GetRadioValue("startup");
				param = param + "&startup=" + startup_value;

				//var contact = document.getElementById("contact").value;
				//param = param + "&days=" + days + "&allocation=" + allocation_value + "&playlist=" + playlist + "&contact=" + contact;
				param = param + "&days=" + days + "&allocation=" + allocation_value + "&playlist=" + playlist;		
			}
	
			var cmd = "custom_all_post.php?"+param;
			window.location.href = cmd;
		}
		
		function back_page()
		{
			window.location.href = "custom_list.php";
				
		}
        </script>	

</body>
<?php
	$sql->disconnect_database();

?>
</html>
