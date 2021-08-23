<!DOCTYPE html>
<?php
	include_once "cn_lang.php";
	include_once "common.php";
	$sql = new DbSql();
	$sql->login();

	$mytable = "start_epg_table";
	$sql->connect_database_default();
	$mydb = $sql->get_database();
	$sql->create_database($mydb);
	$sql->create_table($mydb, $mytable, "tag text, value text");
	
	$item = "style_item" . $_GET["item"];
	$style = $sql->query_data($mydb, $mytable, "tag", $item, "value");
	$styles = explode("*",$style);
	
	$name = "";
	$url = "";
	$id = "";
	
	if(count($styles) > 3)
	{
		$name = $styles[1];
		$url = $styles[2];
		$id = $styles[3];
	}
	
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
                                <div class="muted pull-left"><?php echo $lang_array["start_epg_style_defined_edit_text1"] ?></div>
                            </div>
                            
                            <div class="block-content collapse in">
                                <div class="span12">
                                	<form class="form-horizontal" method="post" action="" enctype='multipart/form-data'>
                                    	<fieldset><br/>
                                   	<div class="control-group">
                                       <label class="control-label" for="focusedInput"><?php echo $lang_array["start_epg_style_defined_edit_text2"] ?></label>
                                       <div class="controls">
                                          <input class="input-mini focused" name="" type="text" size="16" id="text_name" value="<?php echo urldecode($name) ?>"/>
                                       </div>
                                   	</div>
                                    <div class="control-group">
                                       <label class="control-label" for="focusedInput"><?php echo $lang_array["start_epg_style_defined_edit_text3"] ?></label>
                                       <div class="controls">
                                          <input class="input-large focused" name="" type="text" size="16" id="text_id" value="<?php echo $id ?>"/>
                                       </div>
                                   	</div>
                                    
                                    <div class="control-group">
                                       <label class="control-label" for="focusedInput"><?php echo $lang_array["start_epg_style_defined_edit_text4"] ?></label>
                                       <div class="controls">
                                          <input class="input-xxlarge focused" name="" type="text" size="64" id="text_url" value="<?php echo urldecode($url) ?>"/>
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

        <script src="vendors/jquery-1.9.1.js"></script>
        <script src="bootstrap/js/bootstrap.min.js"></script>
        <script src="vendors/datatables/js/jquery.dataTables.min.js"></script>


        <script src="assets/scripts.js"></script>
        <script src="assets/DT_bootstrap.js"></script>
        <script>
		
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

        $(function() {
            
        });
		
		function save()
		{
			var name = encodeURI(document.getElementById("text_name").value); 
			var id = document.getElementById("text_id").value; 
			var url = document.getElementById("text_url").value; 
	
			var item = "<?php echo $_GET["item"] ?>";
			var cmd = "start_epg_style_defined_post.php?item="+item+"&name="+name+"&url="+encodeURI(base64encode(url))+"&id="+id;
			window.location.href = cmd;
		}

		function back_page()
		{
			window.location.href = "start_epg_style.php";
				
		}
        </script>
    </body>

</html>