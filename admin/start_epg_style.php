<!DOCTYPE html>
<?php
	include_once "cn_lang.php";
	include_once 'common.php';
	$sql = new DbSql();
	$sql->login();
	$mytable = "start_epg_table";
	$sql->connect_database_default();
	$mydb = $sql->get_database();
	$sql->create_database($mydb);
	$sql->create_table($mydb, $mytable, "tag text, value text");
	$style = $sql->query_data($mydb, $mytable, "tag", "style","value");
	if($style == null)
		$style = "live|vod|back|setting|exit";
	$styles = explode("|",$style);
	
	$style_item0 = $sql->query_data($mydb, $mytable, "tag", "style_item0","value");
	$style_item1 = $sql->query_data($mydb, $mytable, "tag", "style_item1","value");
	$style_item2 = $sql->query_data($mydb, $mytable, "tag", "style_item2","value");
	$style_item3 = $sql->query_data($mydb, $mytable, "tag", "style_item3","value");
	$style_item4 = $sql->query_data($mydb, $mytable, "tag", "style_item4","value");
	
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
    </head>
    
    <body>
        <div class="container-fluid">
            <div class="row-fluid">
              <div class="span13" id="content">
                <div class="row-fluid">
                   <!-- block -->
						<div class="block">
                            <div class="navbar navbar-inner block-header">
                                <div class="muted pull-left"><?php echo $lang_array["start_epg_style_text1"] ?></div>
                            </div>
                            <div class="block-content collapse in">
                                <div class="span12">                                    
                                    <table cellpadding="0" cellspacing="0" border="0" class="table table-bordered">
                                        <thead>
                                            <tr>
            								<th width="8%" style="vertical-align:middle; text-align:center;"><?php echo $lang_array["live_panal_2_text2"] ?></th>
          									<th width="92%" style="vertical-align:middle; text-align:center;"><?php echo $lang_array["live_panal_2_text4"] ?></th>
                                            </tr>
                                        </thead>
                                        
        <tr>
  		<td style='align:center; vertical-align:middle; text-align:center;'><?php echo $lang_array["start_epg_style_text2"] ?></td>
        <td style='align:center; vertical-align:middle; text-align:center;'>
        	<input style="width:15px;height:28px;" name="radio1" type="radio" value="live" id="radio1" <?php if($styles[0] == "live") echo "checked" ?>/><?php echo $lang_array["start_epg_style_text7"] ?>
        	<input style="width:15px;height:28px;" name="radio1" type="radio" value="vod" id="radio1" <?php if($styles[0] == "vod") echo "checked" ?>/><?php echo $lang_array["start_epg_style_text8"] ?>
            <input style="width:15px;height:28px;" name="radio1" type="radio" value="back" id="radio1" <?php if($styles[0] == "back") echo "checked" ?>/><?php echo $lang_array["start_epg_style_text9"] ?>
            <input style="width:15px;height:28px;" name="radio1" type="radio" value="app" id="radio1" <?php if($styles[0] == "app") echo "checked" ?>/><?php echo $lang_array["start_epg_style_text10"] ?>
            <input style="width:15px;height:28px;" name="radio1" type="radio" value="setting" id="radio1" <?php if($styles[0] == "setting") echo "checked" ?>/><?php echo $lang_array["start_epg_style_text11"] ?>
            <input style="width:15px;height:28px;" name="radio1" type="radio" value="exit" id="radio1" <?php if($styles[0] == "exit") echo "checked" ?>/><?php echo $lang_array["start_epg_style_text12"] ?>
            <input style="width:15px;height:28px;" name="radio1" type="radio" value="defined" id="radio1" <?php if(strstr($styles[0],"defined") != false) echo "checked" ?>/><?php echo $lang_array["start_epg_style_text13"] ?>
            <a href="#" onclick="item_defined(0)"><u><?php echo $lang_array["start_epg_style_text14"] ?> </u></a>
        </td>
        <td></td>
        </tr>
        
        <tr>
  		<td style='align:center; vertical-align:middle; text-align:center;'><?php echo $lang_array["start_epg_style_text3"] ?></td>
        <td style='align:center; vertical-align:middle; text-align:center;'><input name="radio2" type="radio" value="live" id="radio2" <?php if($styles[1] == "live") echo "checked" ?>/><?php echo $lang_array["start_epg_style_text7"] ?>
        	<input style="width:15px;height:28px;" name="radio2" type="radio" value="vod" id="radio2" <?php if($styles[1] == "vod") echo "checked" ?>/><?php echo $lang_array["start_epg_style_text8"] ?>
            <input style="width:15px;height:28px;" name="radio2" type="radio" value="back" id="radio2" <?php if($styles[1] == "back") echo "checked" ?>/><?php echo $lang_array["start_epg_style_text9"] ?>
            <input style="width:15px;height:28px;" name="radio2" type="radio" value="app" id="radio2" <?php if($styles[1] == "app") echo "checked" ?>/><?php echo $lang_array["start_epg_style_text10"] ?>
            <input style="width:15px;height:28px;" name="radio2" type="radio" value="setting" id="radio2" <?php if($styles[1] == "setting") echo "checked" ?>/><?php echo $lang_array["start_epg_style_text11"] ?>
            <input style="width:15px;height:28px;" name="radio2" type="radio" value="exit" id="radio2" <?php if($styles[1] == "exit") echo "checked" ?>/><?php echo $lang_array["start_epg_style_text12"] ?>
            <input style="width:15px;height:28px;" name="radio2" type="radio" value="defined" id="radio2" <?php if(strstr($styles[1],"defined") != false) echo "checked" ?>/><?php echo $lang_array["start_epg_style_text13"] ?>
            <a href="#" onclick="item_defined(1)"><u><?php echo $lang_array["start_epg_style_text14"] ?> </u></a>
        </td>
        </tr>
        
        
        <tr>
  		<td style='align:center; vertical-align:middle; text-align:center;'><?php echo $lang_array["start_epg_style_text4"] ?></td>
        <td style='align:center; vertical-align:middle; text-align:center;'><input name="radio3" type="radio" value="live" id="radio3" <?php if($styles[2] == "live") echo "checked" ?>/><?php echo $lang_array["start_epg_style_text7"] ?>
        	<input style="width:15px;height:28px;" name="radio3" type="radio" value="vod" id="radio3" <?php if($styles[2] == "vod") echo "checked" ?>/><?php echo $lang_array["start_epg_style_text8"] ?>
            <input style="width:15px;height:28px;" name="radio3" type="radio" value="back" id="radio3" <?php if($styles[2] == "back") echo "checked" ?>/><?php echo $lang_array["start_epg_style_text9"] ?>
            <input style="width:15px;height:28px;" name="radio3" type="radio" value="app" id="radio3" <?php if($styles[2] == "app") echo "checked" ?>/><?php echo $lang_array["start_epg_style_text10"] ?>
            <input style="width:15px;height:28px;" name="radio3" type="radio" value="setting" id="radio3" <?php if($styles[2] == "setting") echo "checked" ?>/><?php echo $lang_array["start_epg_style_text11"] ?>
            <input style="width:15px;height:28px;" name="radio3" type="radio" value="exit" id="radio3" <?php if($styles[2] == "exit") echo "checked" ?>/><?php echo $lang_array["start_epg_style_text12"] ?>
            <input style="width:15px;height:28px;" name="radio3" type="radio" value="defined" id="radio3" <?php if(strstr($styles[2],"defined") != false) echo "checked" ?>/><?php echo $lang_array["start_epg_style_text13"] ?>
            <a href="#" onclick="item_defined(2)"><u><?php echo $lang_array["start_epg_style_text14"] ?> </u></a>
        </td>
        </tr>
        
        
        <tr>
  		<td style='align:center; vertical-align:middle; text-align:center;'><?php echo $lang_array["start_epg_style_text5"] ?></td>
        <td style='align:center; vertical-align:middle; text-align:center;'><input name="radio4" type="radio" value="live" id="radio4" <?php if($styles[3] == "live") echo "checked" ?>/><?php echo $lang_array["start_epg_style_text7"] ?>
        	<input style="width:15px;height:28px;" name="radio4" type="radio" value="vod" id="radio4" <?php if($styles[3] == "vod") echo "checked" ?>/><?php echo $lang_array["start_epg_style_text8"] ?>
            <input style="width:15px;height:28px;" name="radio4" type="radio" value="back" id="radio4" <?php if($styles[3] == "back") echo "checked" ?>/><?php echo $lang_array["start_epg_style_text9"] ?>
            <input style="width:15px;height:28px;" name="radio4" type="radio" value="app" id="radio4" <?php if($styles[3] == "app") echo "checked" ?>/><?php echo $lang_array["start_epg_style_text10"] ?>
            <input style="width:15px;height:28px;" name="radio4" type="radio" value="setting" id="radio4" <?php if($styles[3] == "setting") echo "checked" ?>/><?php echo $lang_array["start_epg_style_text11"] ?>
            <input style="width:15px;height:28px;" name="radio4" type="radio" value="exit" id="radio4" <?php if($styles[3] == "exit") echo "checked" ?>/><?php echo $lang_array["start_epg_style_text12"] ?>
            <input style="width:15px;height:28px;" name="radio4" type="radio" value="defined" id="radio4" <?php if(strstr($styles[3],"defined") != false) echo "checked" ?>/><?php echo $lang_array["start_epg_style_text13"] ?>
            <a href="#" onclick="item_defined(3)"><u><?php echo $lang_array["start_epg_style_text14"] ?> </u></a>
        </td>
        </tr>

        <tr>
  		<td style='align:center; vertical-align:middle; text-align:center;'><?php echo $lang_array["start_epg_style_text6"] ?></td>
        <td style='align:center; vertical-align:middle; text-align:center;'><input name="radio5" type="radio" value="live" id="radio5" <?php if($styles[4] == "live") echo "checked" ?>/><?php echo $lang_array["start_epg_style_text7"] ?>
        	<input style="width:15px;height:28px;" name="radio5" type="radio" value="vod" id="radio5" <?php if($styles[4] == "vod") echo "checked" ?>/><?php echo $lang_array["start_epg_style_text8"] ?>
            <input style="width:15px;height:28px;" name="radio5" type="radio" value="back" id="radio5" <?php if($styles[4] == "back") echo "checked" ?>/><?php echo $lang_array["start_epg_style_text9"] ?>
            <input style="width:15px;height:28px;" name="radio5" type="radio" value="app" id="radio5" <?php if($styles[4] == "app") echo "checked" ?>/><?php echo $lang_array["start_epg_style_text10"] ?>
            <input style="width:15px;height:28px;" name="radio5" type="radio" value="setting" id="radio5" <?php if($styles[4] == "setting") echo "checked" ?>/><?php echo $lang_array["start_epg_style_text11"] ?>
            <input style="width:15px;height:28px;" name="radio5" type="radio" value="exit" id="radio5" <?php if($styles[4] == "exit") echo "checked" ?>/><?php echo $lang_array["start_epg_style_text12"] ?>
            <input style="width:15px;height:28px;" name="radio5" type="radio" value="defined" id="radio5" <?php if(strstr($styles[4],"defined") != false) echo "checked" ?>/><?php echo $lang_array["start_epg_style_text13"] ?>
            <a href="#" onclick="item_defined(4)"><u><?php echo $lang_array["start_epg_style_text14"] ?></u></a>
        </td>
        </tr>
                                                                          
                                    </table>
                                    
                                    <div class="form-actions">
   										<button type="button" class="btn btn-primary" onclick="save()"><?php echo $lang_array["save_text1"] ?></button>
                            		</div>
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
        
        <script src="bootstrap/js/bootstrap.min.js"></script>
        <script src="vendors/datatables/js/jquery.dataTables.min.js"></script>


        <script src="assets/scripts.js"></script>
        <script src="assets/DT_bootstrap.js"></script>
        
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
		
		var base64EncodeChars = "ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789+/";
		var base64DecodeChars = new Array(-1, -1, -1, -1, -1, -1, -1, -1, -1, -1, -1, -1, -1, -1, -1, -1, -1, -1, -1, -1, -1, -1, -1, -1, -1, -1, -1, -1, -1, -1, -1, -1, -1, -1, -1, -1, -1, -1, -1, -1, -1, -1, -1, 62, -1, -1, -1, 63, 52, 53, 54, 55, 56, 57, 58, 59, 60, 61, -1, -1, -1, -1, -1, -1, -1, 0, 1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12, 13, 14, 15, 16, 17, 18, 19, 20, 21, 22, 23, 24, 25, -1, -1, -1, -1, -1, -1, 26, 27, 28, 29, 30, 31, 32, 33, 34, 35, 36, 37, 38, 39, 40, 41, 42, 43, 44, 45, 46, 47, 48, 49, 50, 51, -1, -1, -1, -1, -1);
/**
 * base64编码
 * @param {Object} str
 */
		function base64encode(str){
    		var out, i, len;
    		var c1, c2, c3;
   		 	len = str.length;
    		i = 0;
    		out = "";
    		while (i < len) {
        		c1 = str.charCodeAt(i++) & 0xff;
        		if (i == len) {
            		out += base64EncodeChars.charAt(c1 >> 2);
            		out += base64EncodeChars.charAt((c1 & 0x3) << 4);
            		out += "==";
            		break;
        		}
        		c2 = str.charCodeAt(i++);
        		if (i == len) {
            		out += base64EncodeChars.charAt(c1 >> 2);
            		out += base64EncodeChars.charAt(((c1 & 0x3) << 4) | ((c2 & 0xF0) >> 4));
            		out += base64EncodeChars.charAt((c2 & 0xF) << 2);
           		 	out += "=";
           	 		break;
        		}
        		c3 = str.charCodeAt(i++);
        		out += base64EncodeChars.charAt(c1 >> 2);
        		out += base64EncodeChars.charAt(((c1 & 0x3) << 4) | ((c2 & 0xF0) >> 4));
        		out += base64EncodeChars.charAt(((c2 & 0xF) << 2) | ((c3 & 0xC0) >> 6));
        		out += base64EncodeChars.charAt(c3 & 0x3F);
    		}
    		return out;
		}
		
		function get_radio(name)
		{
			var check_id;
			var chkObjs = document.getElementsByName(name);
			for(var i=0;i<chkObjs.length;i++){
				if(chkObjs[i].checked){
					check_id = chkObjs[i].value;
					break;
				}
			}	
			return check_id;
		}


		function load_style(v)
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
					if(xmlhttp.responseText.indexOf("OK") >= 0)
					{
						alert("<?php echo $lang_array["stream_server_list_text23"] ?>");	
					}
					else
					{
						alert("<?php echo $lang_array["stream_server_list_text24"] ?>");
					}
				}
			}
			xmlhttp.open("GET","start_epg_style_post.php?style="+v,true);
			xmlhttp.send();
		}
		
		function save()
		{
			var v1 = get_radio("radio1");
			var v2 = get_radio("radio2");
			var v3 = get_radio("radio3");
			var v4 = get_radio("radio4");
			var v5 = get_radio("radio5");
	
			var style_item0 = "<?php echo $style_item0 ?>";
			var style_item1 = "<?php echo $style_item1 ?>";
			var style_item2 = "<?php echo $style_item2 ?>";
			var style_item3 = "<?php echo $style_item3 ?>";
			var style_item4 = "<?php echo $style_item4 ?>";
	
			var v = "";
	
			if(v1 == "defined")
			{
				if(style_item0.length > 5)
					v = v + style_item0 + "|";
				else
				{
					alert("<?php echo $lang_array["start_epg_style_text15"]?>");
					return;	
				}
			}
			else
			{
				v = v + v1 + "|";
			}
	
			if(v2 == "defined")
			{
				if(style_item1.length > 5)
					v = v + style_item1 + "|";
				else
				{
					alert("<?php echo $lang_array["start_epg_style_text16"]?>");
					return;	
				}
			}
			else
			{
				v = v + v2 + "|";
			}
	
			if(v3 == "defined")
			{
				if(style_item2.length > 5)
					v = v + style_item2 + "|";
				else
				{
					alert("<?php echo $lang_array["start_epg_style_text17"]?>");
					return;	
				}
			}
			else
			{
				v = v + v3 + "|";
			}
	
			if(v4 == "defined")
			{
				if(style_item3.length > 5)
					v = v + style_item3 + "|";
				else
				{
					alert("<?php echo $lang_array["start_epg_style_text18"]?>");
					return;	
				}
			}
			else
			{
				v = v + v4 + "|";
			}
	
			if(v5 == "defined")
			{
				if(style_item4.length > 5)
					v = v + style_item4 + "|";
				else
				{
					alert("<?php echo $lang_array["start_epg_style_text19"]?>");
					return;	
				}
			}
			else
			{
				v = v + v5;
			}	
			//var v = v1 + "|" + v2 + "|" + v3 + "|" + v4 + "|" + v5;
	
			load_style(base64encode(v))
			//window.location.href = "start_epg_style_post.php?style="+encodeURI(v);
		}
		
		function item_defined(index)
		{
			window.location.href = "start_epg_style_defined_edit.php?item="+index;
		}

		function edit_epg()
		{
			window.location.href = "start_epg_edit.php";
		}

		function edit_epg2()
		{
			window.location.href = "start_epg2_edit.php";
		}

		function edit_broadcast()
		{
			window.location.href = "start_broadcast_edit.php";
		}

		function recovery()
		{
			window.location.href = "start_load_del.php";	
		}

        </script>
    </body>

</html>