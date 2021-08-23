<!DOCTYPE html>
<?php
	include_once "cn_lang.php";
	include_once "common.php";
	$sql = new DbSql();
	$sql->login();
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
                                <div class="muted pull-left"><?php echo $lang_array["batch_export_list_text1"] ?></div>
                            </div>
                            
                            <div class="block-content collapse in">
                                <div class="span12">
                                	<form class="form-horizontal" method="post" action="user_post.php" enctype='multipart/form-data'>
                                    	<fieldset>
                                        	<div class="control-group">
                                       		<label class="control-label" for="focusedInput" style="text-shadow:#0CF"><?php echo $lang_array["batch_ps_list_text1"] ?></label>
                                       			<div class="controls">
                                       			<input class="input-medium focused" id="ps0_id" name="" type="text" style="width: 150px" placeholder="<?php echo $lang_array["batch_ps_list_text3"] ?>"/> -> <input class="input-medium focused" id="ps1_id" name="" type="text" style="width: 150px"/>&nbsp;&nbsp;
                                                <input class='uniform_on' id='ps_checkbox' type='checkbox' style='width:15px;height:25px;' value='clear_ps' /> <?php echo $lang_array["batch_ps_list_text5"] ?>
                                                </div>
                                            </div>    
                                            <div class="control-group">
                                       		<label class="control-label" for="focusedInput" style="text-shadow:#0CF"><?php echo $lang_array["batch_ps_list_text2"] ?></label>
                                            	<div class="controls">
                                       			<input class="input-medium focused" id="userid0_id" name="" type="text" style="width: 150px" placeholder="<?php echo $lang_array["batch_ps_list_text4"] ?>"/> -> <input class="input-medium focused" id="userid1_id" name="" type="text" style="width: 150px"/>&nbsp;&nbsp;
                                                <input class='uniform_on' id='userid_checkbox' type='checkbox' style='width:15px;height:25px;' value='clear_userid' /> <?php echo $lang_array["batch_ps_list_text6"] ?>
                                                </div>
                                            </div>
                                            
                                            <div class="control-group">
                                       		<label class="control-label" for="focusedInput" style="text-shadow:#0CF"><?php echo $lang_array["batch_ps_list_text11"] ?></label>
                                            	<div class="controls">
                                       			<input class="input-medium focused" id="urltext_id" name="" type="text" style="width: 150px" placeholder=""/>&nbsp;&nbsp;&nbsp;&nbsp;<?php echo $lang_array["batch_ps_list_text12"] ?>:<input class="input-medium focused" id="pstext_id" name="" type="text" style="width: 150px"/>&nbsp;&nbsp;<?php echo $lang_array["batch_ps_list_text13"] ?>:<input class="input-xxlarge focused" id="useridtext_id" name="" type="text" style="width: 450px"/>&nbsp;&nbsp;
                                                
                                                </div>
                                            </div>
                                            
                                            <div class="form-actions">
                                       			<input name="" class="btn btn-primary" type="button" value="<?php echo $lang_array["edit_text1"] ?>" onclick="batch_ps()"/>
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

		function batch_ps()
		{
			var r=confirm("<?php echo $lang_array["live_preview_list_text31"] ?>" + "？");
			if(r==true)
  			{	
				var ps_checkbox = document.getElementById("ps_checkbox");
				var userid_checkbox = document.getElementById("userid_checkbox");
				
				var old_ps= document.getElementById("ps0_id").value;
				var new_ps= document.getElementById("ps1_id").value;
				
				var old_userid= document.getElementById("userid0_id").value;
				var new_userid= document.getElementById("userid1_id").value;
				
				var urltext= document.getElementById("urltext_id").value;
				var pstext= document.getElementById("pstext_id").value;

				var useridtext= document.getElementById("useridtext_id").value;
				
				//alert(urltext + pstext);
				
				var t1 = "";
				if(ps_checkbox.checked)
				{
					t1 = "<?php echo $lang_array["batch_ps_list_text9"] ?>";
				}
				else if(new_ps.length > 0)
				{
					var old_ps_text = old_ps;
					if(old_ps_text.length <= 0)
						old_ps_text = "<?php echo $lang_array["live_preview_list_text40"] ?>";
					t1 = "<?php echo $lang_array["live_preview_list_text29"] ?>" + "：" + old_ps_text + " -> " + new_ps;
				}
				else
				{
					t1 = "<?php echo $lang_array["batch_ps_list_text7"] ?>";
				}
				
				var t2 = "";
				if(userid_checkbox.checked)
				{
					t2 = "<?php echo $lang_array["batch_ps_list_text10"] ?>";
				}
				else if(new_userid.length > 0)
				{
					var old_userid_text = old_userid;
					if(old_userid_text.length <= 0)
						old_userid_text = "<?php echo $lang_array["live_preview_list_text43"] ?>";
					t2 = "<?php echo $lang_array["live_preview_list_text42"] ?>" + "：" + old_userid_text + " -> " + new_userid;
				}
				else
				{
					t2 = "<?php echo $lang_array["batch_ps_list_text8"] ?>";
				}	
				
				
				if(urltext.length > 0 && (pstext.length > 0 || useridtext.length > 0))
				{
					var rr=confirm("<?php echo $lang_array["live_preview_list_text44"] ?>");
					if(rr == true)
					{
						var cmd = "batch_ps_post.php?id=0";
						cmd = cmd + "&urltext=" + base64encode(encodeURI(urltext)) + "&pstext=" + base64encode(encodeURI(pstext)) + "&useridtext=" + base64encode(encodeURI(useridtext));	
						window.location.href = cmd;
					}
				}
				else
				{
					var rr=confirm(t1 + "    " + t2 + "    " +"<?php echo $lang_array["live_preview_list_text13"] ?>" + "?");
					if(rr == true)
					{
						var cmd = "batch_ps_post.php?id=0";
					
						if(ps_checkbox.checked)
							cmd = cmd + "&psclear=1";
						
						if(userid_checkbox.checked)
							cmd = cmd + "&useridclear=1";
							
							
						if(new_ps.length > 0 || new_userid.length > 0 || (urltext.length > 0 && pstext.length > 0))
						{
							cmd = cmd +"&ps0=" + base64encode(encodeURI(new_ps)) + "&ps1=" + base64encode(encodeURI(old_ps)) + "&userid0=" + base64encode(encodeURI(new_userid)) + "&userid1=" + base64encode(encodeURI(old_userid));
						}
					
						window.location.href = cmd;
					}
				}
			}
		}
        </script>
    </body>

</html>