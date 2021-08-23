<!DOCTYPE html>
<?php
	include_once "cn_lang.php";
	include_once "common.php";
	include_once 'gemini.php';

	$sql = new DbSql();
	
	if($sql->login_type() != 2)
		$sql->login();
	else
		$sql->login_proxy();
	
	$mydb = $sql->get_database();
	$sql->connect_database_default();
	$sql->create_database($mydb);
	
	$mytable = "proxy_download_table";
	$sql->create_table($mydb, $mytable, "name text, sbackground text, sepg text, ebackground text, livepanel text, vodpanel text, download text, allow text, watermark text, scrolltext text, version int");
	//$names = $sql->fetch_datas_where($mydb, $mytable, "name", $proxy);
	$sbackground = $sql->query_data($mydb, $mytable,"name",$_COOKIE["user"],"sbackground");
	$sepg = $sql->query_data($mydb, $mytable,"name",$_COOKIE["user"],"sepg");
	$liveuitype = $sql->query_data($mydb, $mytable,"name",$_COOKIE["user"],"livepanel");
	$ebackground = $sql->query_data($mydb, $mytable,"name",$_COOKIE["user"],"ebackground");
	$download = $sql->query_data($mydb, $mytable,"name",$_COOKIE["user"],"download");
	$allow = $sql->query_data($mydb, $mytable,"name",$_COOKIE["user"],"allow");
	$scrolltext = $sql->query_data($mydb, $mytable,"name",$_COOKIE["user"],"scrolltext");
	$version = $sql->query_data($mydb, $mytable,"name",$_COOKIE["user"],"version");
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
    <script type="text/javascript" src="http://code.jquery.com/jquery-latest.min.js"></script>
<script type="text/javascript" src="jquery.customselect.js"></script>
<title>Custom select boxes with icons</title>
<style type="text/css">
#iconselect {
background: url(select-bg.gif) no-repeat;
height: 25px;
width: 250px;
font: 13px Arial, Helvetica, sans-serif;
padding-left: 15px;
padding-top: 4px;
}
.selectitems {
width: 230px;
height: 25px;
border-bottom: dashed 1px #ddd;
padding-left: 10px;
padding-top: 2px;
}
.selectitems span {
margin-left: 5px;
}
#iconselectholder {
width: 250px;
overflow: auto;
display: none;
position: absolute;
background-color:#fff5ec;
}
.hoverclass{
background-color: #fff;
cursor: pointer;
}
.selectedclass{
background-color: #ff9;
}
</style>
<script type="text/javascript">
$(function(){
$('#customselector').customSelect();
});
</script>
    <body onLoad="init()">
        <div class="container-fluid">
            <div class="row-fluid">
              	<div class="span13" id="content">
                <div class="row-fluid">
                   <!-- block -->
						<div class="block">
                          <div class="navbar navbar-inner block-header">
                                <div class="muted pull-left"><?php echo $lang_array["proxy_version_text1"] ?></div>
                            </div>
                            
                            <div class="block-content collapse in">
                                <div class="span12">
                                	<form class="form-horizontal" name="authform" method="post" action="proxy_version_post.php" enctype='multipart/form-data'>
                                    <fieldset><br/>
                                    <div class="control-group">
                                        <label class="control-label" for="fileInput"><?php echo $lang_array["proxy_version_text2"] ?></label>
                                        <div class="controls">
                                          <input class="input-file uniform_on" id="sbackground" name="sbackground" type="file">&nbsp;&nbsp;<input class="btn btn-primary" name="" type="button" value="<?php echo $lang_array["proxy_version_text8"] ?>" onclick="sbackground_recovery()"/>&nbsp;&nbsp;<?php echo $lang_array["proxy_version_text9"] ?>:<?php if(strcmp($sbackground,"0")==0 || $sbackground==null) echo $lang_array["proxy_version_text10"]; else echo $sbackground ?>
                                        </div>
                                    </div> 
                                    
                                    <div class="control-group">
                                       <label class="control-label" for="focusedInput"><?php echo $lang_array["proxy_edit_text19"] ?></label>
                                       <div class="controls">
                                          <input class="input-mini focused" id="tversion" name="tversion" type="text" value="<?php echo $version ?>">
                                       </div>
                                   	</div>
                                    
                                    <!--
                                    <div class="control-group">
                                        <label class="control-label" for="fileInput"><?php echo $lang_array["proxy_version_text3"] ?></label>
                                        <div class="controls">
                                          <input class="input-file uniform_on" id="ebackground" name="ebackground" type="file">&nbsp;&nbsp;<input class="btn btn-primary" name="" type="button" value="<?php echo $lang_array["proxy_version_text8"] ?>" onclick="ebackground_recovery()" />&nbsp;&nbsp;<?php echo $lang_array["proxy_version_text9"] ?>:<?php if(strcmp($ebackground,"0")==0 || $ebackground==null) echo $lang_array["proxy_version_text10"]; else echo $ebackground ?>
                                        </div>
                                    </div>     
                                    -->
                                                                    
                                   	<div class="control-group">
										<label class="control-label" ><?php echo $lang_array["proxy_version_text4"] ?></label>
                                        <div class="controls">
											<input class="input-xxlarge focused" id="tupdate0" name="tupdate" type="text" value="<?php echo $download;?>">&nbsp;&nbsp;&nbsp;<input name="" class="btn btn-primary" type="button" value="<?php echo $lang_array["proxy_version_text6"] ?>" onclick="download_recovery()"/>
                                        </div>    
                                    </div>
                                    
                                    
                                    
                                   	<div class="control-group">
										<label class="control-label" ><?php echo $lang_array["proxy_version_text5"] ?></label>
                                        <div class="controls">
											<input name="rupdate" type="radio" id="radio0" value="1" style="width:15px;height:28px;" <?php if(intval($allow) != 0) echo "checked"; ?>><?php echo $lang_array["yes_text1"] ?>&nbsp;&nbsp;&nbsp;&nbsp;
                                          	<input name="rupdate" type="radio" id="radio1" value="0" style="width:15px;height:28px;" <?php if(intval($allow) == 0) echo "checked"; ?>><?php echo $lang_array["no_text1"] ?>&nbsp;&nbsp;&nbsp;&nbsp;   
                                        </div>    
                                    </div>                                    
                                    
                                    <div class="control-group">
										<label class="control-label" ><?php echo $lang_array["proxy_version_text11"] ?></label>
                                        <div class="controls">
											<input class="input-xxlarge focused" id="scrolltext" name="scrolltext" type="text" value="<?php echo $scrolltext;?>">
                                        </div>    
                                    </div>                        
                                     
                                    <div class="control-group">
										<label class="control-label" ><?php echo $lang_array["proxy_version_text12"] ?></label>
                                        <div class="controls">
											<table width="200" border="0">
  												<tr>
    												<td>
                                                    	<table width="150" border="0">
  														<tr>
    														<td><input style='width:15px;height:15px;' name='livetype' type='radio' value='1' <?php if($liveuitype == 1) echo "checked"?>/></td>
    														<td><img src="images/p6.jpg" width="64" height="32" ></td>
  														</tr>
														</table>
													</td>
    												<td>
                                                    	<table width="150" border="0">
  														<tr>
    														<td><input style='width:15px;height:15px;' name='livetype' type='radio' value='2' <?php if($liveuitype == 2) echo "checked"?>/></td>
    														<td><img src="images/p5.jpg" width="64" height="32" ></td>
  														</tr>
														</table>
                                                    </td>
  												</tr>
											</table>
                                        </div>    
                                    </div>                                              
                                    <div class="form-actions">
   										<button type="submit" class="btn btn-primary" ><?php echo $lang_array["save_text1"] ?></button>
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
		
		function download_recovery()
		{
			document.getElementById("tupdate0").value = "0";
		}
		
		function sbackground_recovery()
		{
			window.location.href = "proxy_version_sbackground_post.php?recovery=1";
		}

		function ebackground_recovery()
		{
			window.location.href = "proxy_version_ebackground_post.php?recovery=1";
		}

		function back_page()
		{
			
				
		}
		

		</script>
</body>


<?php
	$sql->disconnect_database();

?>
</html>