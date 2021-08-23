<!DOCTYPE html>
<?php
	include_once "cn_lang.php";
	include_once "common.php";
	$sql = new DbSql();
	$sql->login_proxy();
	
	$sql->connect_database_default();
	$mydb = $sql->get_database();
	$sql->create_database($mydb);
	
	$mytable = "proxy_playlist_table";
	$sql->create_table($mydb, $mytable, "proxy text, playlist text");	
	$proxy_playlist = $sql->query_data($mydb, $mytable,"proxy",$_COOKIE["user"],"playlist");
	
	$mytable = "proxy_table";
	$sql->create_table($mydb, $mytable, "name text, password text, ptip text, edit int, watermark text, validity date, allow int, remark text, ccount int");
	$ccount = $sql->query_data($mydb, $mytable,"name",$_COOKIE["user"], "ccount");
	if($ccount != 1)
	{
		$sql->disconnect_database();
		exit;
	}
	
	$mytable = "custom_close_table";
	$sql->create_table($mydb, $mytable, "allow longtext, value longtext");
	
	$allow = $sql->query_data($mydb, $mytable, "allow", "allow", "value");
	$days = $sql->query_data($mydb, $mytable, "allow", "days", "value");
	$allocation = $sql->query_data($mydb, $mytable, "allow", "allocation", "value");
	$playlist = $sql->query_data($mydb, $mytable, "allow", "playlist", "value");
	$contact = $sql->query_data($mydb, $mytable, "allow", "contact", "value");
	$limit = $sql->query_data($mydb, $mytable, "allow", "limit", "value");
	
	if($days == null)
		$days = 0;
		
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
                                <div class="muted pull-left"><?php echo $lang_array["account_add_text1"] ?></div>
                            </div>
                            
                            <div class="block-content collapse in">
                                <div class="span12">
                                	<form class="form-horizontal" name="authform" method="post" action="" enctype='multipart/form-data'>
                                    <fieldset><br/>
                                    <div class="control-group">
                                       <label class="control-label" for="focusedInput" style="text-shadow:#0CF"><?php echo $lang_array["account_add_text2"] ?></label>
                                       <div class="controls">
                                            <input name="" style="width:80px" type="text" value="365" size="6" maxlength="6" id="days"/>
                                       </div>
                                   	</div> 
                                    
                                    <div class="control-group">
                                       <label class="control-label" for="focusedInput" style="text-shadow:#0CF"><?php echo $lang_array["account_add_text3"] ?></label>
                                       <div class="controls">
  	<input type="radio" name="allocation" value="all" id="allocation0" style="width:15px;height:28px;" checked/><?php echo $lang_array["account_add_text4"] ?>&nbsp;&nbsp;&nbsp;&nbsp;
  	<input type="radio" name="allocation" value="auto" id="allocation1" style="width:15px;height:28px;" /><?php echo $lang_array["account_add_text5"] ?>&nbsp;&nbsp;&nbsp;&nbsp;
    <input type="radio" name="allocation" value="manually" id="allocation2" style="width:15px;height:28px;" /><?php echo $lang_array["account_add_text6"] ?>&nbsp;&nbsp;&nbsp;&nbsp;
    
  	<select id="playlist_select_id" name="">
    <?php
	$mytable = "playlist_type_table";
	$sql->create_table($mydb, $mytable, "name text, space longtext, id text, playlist longtext");
	
	$namess = $sql->fetch_datas($mydb, $mytable);
	foreach($namess as $names) 
	{
		if($proxy_playlist == NULL || strlen($proxy_playlist)<= 0 || strstr($proxy_playlist,$names[2]) != false)
		{
			if(strcmp($playlist,$names[2]) == 0)
			{
				echo "<option value='" . $names[2] . "' selected='selected'>" . $names[0] . "</option>";
			}
			else
			{
				echo "<option value='" . $names[2] . "'>" . $names[0] . "</option>";
			}
		}
	}
	?>
    </select>
                                       </div>
                                   	</div> 
                                    
                                    <!--
                                    <div class="control-group">
                                       <label class="control-label" for="focusedInput" style="text-shadow:#0CF"><?php echo $lang_array["account_add_text7"] ?></label>
                                       <div class="controls">
    										<input type="radio" name="proxy" value="admin" id="proxy0" style="width:15px;height:28px;" checked /><?php echo $lang_array["account_add_text8"] ?>&nbsp;&nbsp;&nbsp;&nbsp;
    										<input type="radio" name="proxy" value="proxy" id="proxy1" style="width:15px;height:28px;" /><?php echo $lang_array["account_add_text9"] ?>&nbsp;&nbsp;&nbsp;&nbsp;
											<select id="proxy_select_id" name="">
											<?php
												$mytable = "proxy_table";
												$sql->create_table($mydb, $mytable, "name text, password text");
												$namess = $sql->fetch_datas($mydb, $mytable);
												foreach($namess as $names) {
													if(strcmp($proxy,$names[0]) == 0)
													{
														echo "<option value='" . $names[0] . "' selected='selected'>" . $names[0] . "</option>";
													}
													else
													{
														echo "<option value='" . $names[0] . "'>" . $names[0] . "</option>";
													}
												}
											?>
											</select>
                                       </div>
                                   	</div>
                                    -->
                                    
                                    <div class="control-group">
                                       <label class="control-label" for="focusedInput" style="text-shadow:#0CF"><?php echo $lang_array["account_add_text10"] ?></label>
                                       <div class="controls">
                                            <input type="radio" name="show" value="yes" id="show0" style="width:15px;height:28px;" checked/><?php echo $lang_array["yes_text1"] ?>&nbsp;&nbsp;&nbsp;&nbsp;
      										<input type="radio" name="show" value="no" id="show1" style="width:15px;height:28px;" /><?php echo $lang_array["no_text1"] ?>&nbsp;&nbsp;&nbsp;&nbsp;
                                       </div>
                                   	</div> 
                                    
                                    
                                    <div class="control-group">
                                       <label class="control-label" for="focusedInput" style="text-shadow:#0CF"><?php echo $lang_array["account_add_text11"] ?></label>
                                       <div class="controls">
      										<input type="radio" name="panel" value="0" id="panel0" style="width:15px;height:28px;"/><?php echo $lang_array["account_add_text12"] ?>&nbsp;&nbsp;&nbsp;&nbsp;
      										<input type="radio" name="panel" value="2" id="panel2" style="width:15px;height:28px;"/><?php echo $lang_array["account_add_text13"] ?>&nbsp;&nbsp;&nbsp;&nbsp;
      										<input type="radio" name="panel" value="1" id="panel1" style="width:15px;height:28px;" checked/><?php echo $lang_array["account_add_text14"] ?>&nbsp;&nbsp;&nbsp;&nbsp;
                                       </div>
                                   	</div> 
                                    
                                    <div class="control-group">
                                       <label class="control-label" for="focusedInput" style="text-shadow:#0CF"><?php echo $lang_array["account_add_text15"] ?></label>
                                       <div class="controls">
      										<input class="input-medium focused" name="" type="text" value="100" style="width:80px" id="len"/>
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
		
		
		function save()
		{
			var playlist;
			var allocation_value = GetRadioValue("allocation");
			var panel = GetRadioValue("panel");
			var show = GetRadioValue("show");
	
			if(allocation_value == "manually")
			{
				playlist = document.getElementById("playlist_select_id").value;
				if(playlist == null || playlist.length <= 0)
				{
					alert("无有效列表，请添加播放列表！");
					return;
				}
			}
			else if(allocation_value == "auto")
			{
				playlist = "auto";
			}
			else if(allocation_value == "all")
			{
				playlist = "all";
			}
	
			/*
			var proxy;
			var proxy_value = GetRadioValue("proxy");
			if(proxy_value == "proxy")
			{
				proxy = document.getElementById("proxy_select_id").value;
				if(proxy == null || proxy.length <= 0)
				{
					alert("无有效代理商，请添加代理商！");
					return;
				}
			}
			else
			{
				proxy = "admin";
			}
			*/
			proxy = "<?php echo $_COOKIE["user"] ?>";
			var days = document.getElementById("days").value;
			var len = document.getElementById("len").value;
			window.location.href = "proxy_account_post.php?proxy=" + proxy + "&playlist=" + playlist + "&days=" + days + "&len=" + len + "&show=" + show + "&panel=" + panel;
		}


		function back_page()
		{
			window.location.href = "proxy_account_list.php";
		}
        </script>	

</body>
<?php
	$sql->disconnect_database();

?>
</html>
