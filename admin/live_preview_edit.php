<!DOCTYPE html>
<?php
	include_once "cn_lang.php";
	include_once "common.php";
	$sql = new DbSql();
	$sql->login();
	
	include_once 'gemini.php';
	$g = new Gemini();
	$g->check_version();
	
	$id = $_GET["key"];
	$page = $_GET["page"];
	
	$sql->connect_database_default();
	$mydb = $sql->get_database();
	$sql->create_database($mydb);

	$mytable = "live_type_table";
	$sql->create_table($mydb, $mytable, "name longtext, id longtext");
	$namess_type = $sql->fetch_datas($mydb, $mytable);
	
	$mytable = "live_preview_table";
	$sql->create_table($mydb, $mytable, "name longtext, image longtext, url longtext, password longtext, type longtext, preview longtext, id longtext, urlid smallint, hd longtext, watermark text");
	
	$namess = $sql->fetch_datas($mydb, $mytable);
	$row = $sql->get_row($mydb, $mytable,"urlid",$id);
	
	$urlss = base64_decode($g->j2($row["url"]));
	$urls = explode("geminihighlowgemini",$urlss);
	if(count($urls) >= 1)
		$high_urls = explode("|",$urls[0]);
	if(count($urls) >= 2)
		$low_urls = explode("|",$urls[1]);	
		
	$pwss = $g->j2($row["password"]);
	$pws = explode("geminihighlowgemini",$pwss);
	if(count($pws) >= 1)
		$high_pws = explode("|",$pws[0]);
	if(count($pws) >= 2)
		$low_pws = explode("|",$pws[1]);
		
	$types = explode("|",$row["type"]);	
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
                                <div class="muted pull-left"><?php echo $lang_array["live_preview_edit_text1"] ?></div>
                            </div>
                            
                            <div class="block-content collapse in">
                                <div class="span12">
                                	<form class="form-horizontal" name="authform" method="post" action="live_preview_post.php?page=<?php echo $page ?>" enctype='multipart/form-data'>
                                    <fieldset><br/>

                                    <div class="control-group">
                                       <label class="control-label" for="focusedInput"><?php echo $lang_array["live_preview_add_text2"] ?></label>
                                       <div class="controls">
                                          <input class="input-mini focused" id="live_id" name="live_id" type="text" value="<?php echo $row["urlid"] ?>">
                                          <label id="tipId" style="color:#F00"></label>
                                       </div>
                                   	</div>
                                    
                                    <div class="control-group">
                                       <label class="control-label" for="focusedInput"><?php echo $lang_array["live_preview_add_text3"] ?></label>
                                       <div class="controls">
                                          <input class="input-medium focused" id="name" name="name" type="text" value="<?php echo $row["name"] ?>">&nbsp;&nbsp;
                                          <button type="button" class="btn btn-primary" onClick="open_language()"><?php echo $lang_array["live_type_add_text4"] ?></button>
                                          <label id="tipName" style="color:#F00"></label>
                                       </div>
                                   	</div>  
                                                                      
                                    <div class="control-group">
                                        <label class="control-label" for="fileInput"><?php echo $lang_array["live_preview_add_text4"] ?></label>
                                        <div class="controls">
										  <input class="input-file uniform_on" id="file" name="file" type="file">&nbsp;&nbsp;<?php echo $lang_array["live_preview_add_text40"] ?>&nbsp;&nbsp;
                                          <input class="input-xxlarge focused" id="imageurl" name="imageurl" type="text" value="<?php if(strstr($row["image"],"http://") != false) echo $row["image"] ?>">
                                          <label id="tipFile" style="color:#F00"></label>
                                        </div>
                                    </div>
                                    
                                    <div style="background-color:#CCC">  
                                    <br/> 
                                    <div class="control-group">
                                       <label class="control-label" for="focusedInput"><?php echo $lang_array["live_preview_add_text6"] ?></label>
                                       <div class="controls">
                                          <input class="input-xxlarge focused" id="high_url" name="high_url" type="text" value="">
                                          <label id="tipUrl" style="color:#F00"></label>
                                       </div>
                                   	</div>
                                    
                                    <div class="control-group">
										<label class="control-label" ><?php echo $lang_array["live_preview_add_text17"] ?></label>
  
                                       <div class="controls">
											<?php echo $lang_array["live_preview_add_text19"] ?><input name="high_ps_radio" type="radio" id="high_ps_radio0" value="0" style="width:15px;height:28px;" >&nbsp;&nbsp;
                                            <?php echo $lang_array["live_preview_add_text20"] ?><input name="high_ps_radio" type="radio" id="high_ps_radio1" value="1" style="width:15px;height:28px;" checked >
                                            <input class="input-medium focused" id="high_pw" name="high_pw" type="text" value="">&nbsp;&nbsp;&nbsp;&nbsp;
                                            <?php echo $lang_array["live_preview_add_text18"] ?>
                                            <input class="input-medium focused" id="high_userid" name="high_userid" type="text" value="">&nbsp;&nbsp;&nbsp;&nbsp;<button type="button" class="btn btn-primary" onClick="add_high()"><?php echo $lang_array["add_text1"] ?></button>
                                        </div>                                          
                                    </div> 
                                    <table cellpadding="0" cellspacing="0" border="0" class="table table-bordered" id="high_table_id">
                                        <thead border="2">
                                            <tr>
          									<th width="50%" style="vertical-align:middle; text-align:center;"><?php echo $lang_array["live_preview_add_text23"] ?></th>
            								<th width="15%" style="vertical-align:middle; text-align:center;"><?php echo $lang_array["live_preview_add_text24"] ?></th>
            								<th width="15%" style="vertical-align:middle; text-align:center;"><?php echo $lang_array["live_preview_add_text25"] ?></th>
          									<th width="15%" style="vertical-align:middle; text-align:center;"><?php echo $lang_array["live_preview_add_text26"] ?></th>
                                            </tr>
                                        </thead>
                                       	<tbody id="highbody"> 
										</tbody>
									</table>
                                    </div>
                                    <br/> 
                                    
                                               
                                    <div style="background-color:#CCC">  
                                    <br/>                                  
                                    <div class="control-group">
                                       <label class="control-label" for="focusedInput"><?php echo $lang_array["live_preview_add_text7"] ?></label>
                                       <div class="controls">
                                          <input class="input-xxlarge focused" id="low_url" name="low_url" type="text" value="">
                                          
                                       </div>
                                   	</div>
                                    
                                    <div class="control-group">
										<label class="control-label" ><?php echo $lang_array["live_preview_add_text17"] ?></label>
  
                                       <div class="controls">
											<?php echo $lang_array["live_preview_add_text19"] ?><input name="low_ps_radio" type="radio" id="low_ps_radio0" value="0" style="width:15px;height:28px;" >&nbsp;&nbsp;
                                            <?php echo $lang_array["live_preview_add_text20"] ?><input name="low_ps_radio" type="radio" id="low_ps_radio1" value="1" style="width:15px;height:28px;" checked >
                                            <input class="input-medium focused" id="low_pw" name="low_pw" type="text" value="">&nbsp;&nbsp;&nbsp;&nbsp;
                                            <?php echo $lang_array["live_preview_add_text18"] ?>
                                            <input class="input-medium focused" id="low_userid" name="low_userid" type="text" value="">&nbsp;&nbsp;&nbsp;&nbsp;<button type="button" class="btn btn-primary" onClick="add_low()"><?php echo $lang_array["add_text1"] ?></button>
                                        </div>                                          
                                    </div> 
                                    <table cellpadding="0" cellspacing="0" border="0" class="table table-bordered" id="low_table_id" style="table-layout:fixed;">
                                        <thead>
                                            <tr>
          									<th width="50%" style="vertical-align:middle; text-align:center;"><?php echo $lang_array["live_preview_add_text23"] ?></th>
            								<th width="15%" style="vertical-align:middle; text-align:center;"><?php echo $lang_array["live_preview_add_text24"] ?></th>
            								<th width="15%" style="vertical-align:middle; text-align:center;"><?php echo $lang_array["live_preview_add_text25"] ?></th>
          									<th width="15%" style="vertical-align:middle; text-align:center;"><?php echo $lang_array["live_preview_add_text26"] ?></th>
                                            </tr>
                                        </thead>
                                       	<tbody id="lowbody"> 
										</tbody>
									</table>
                                    </div>
                                    <br/> 
                       
									<div class="control-group">
                                       	<label class="control-label" for="focusedInput"><?php echo $lang_array["live_preview_add_text8"] ?></label>
                                    	<div class='controls'>
<?php
									$index = 1;
									foreach($namess_type as $names_type) 
									{
										$checked = 0;
										foreach($types as $type)
										{
											if($names_type[1] == $type)
											{
												$checked = 1;
											}
										}
										
										if($checked == 1)
										{
											echo "<input class='uniform_on' name='type_checkbox' type='checkbox' value='" . $names_type[1] . "' checked/>" . $names_type[0] . "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
										}
										else
										{
											echo "<input class='uniform_on' name='type_checkbox' type='checkbox' value='" . $names_type[1] . "' />" . $names_type[0] . "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
										}
										if($index%5==0)
											echo "<br/>";
										$index++;
                                        
									}
									
?>
										<label id="tipType" style="color:#F00"></label> 
										</div>                             
                                   	</div>
                                     
  									<div class="control-group">
  										<label class="control-label"><?php echo $lang_array["live_preview_add_text10"] ?></label>
  										<div class="controls">
  											<select class="input-medium " name="sourcetype" id="sourcetype">
        										<option value="sd" <?php if(strcmp($row["hd"],"sd") == 0)echo "selected"; ?>><?php echo $lang_array["live_preview_add_text11"] ?></option>
        										<option value="hd" <?php if(strcmp($row["hd"],"hd") == 0)echo "selected"; ?>><?php echo $lang_array["live_preview_add_text12"] ?></option>
        										<option value="p" <?php if(strcmp($row["hd"],"p") == 0)echo "selected"; ?>><?php echo $lang_array["live_preview_add_text13"] ?></option>
  											</select>
											(<?php echo $lang_array["live_preview_edit_text2"] ?>)
  										</div>
  									</div>
                                    
                                    <div class="control-group">
                                        <label class="control-label" for="fileInput"><?php echo $lang_array["live_preview_add_text29"] ?></label>
                                        <div class="controls">
                                        	<?php echo $lang_array["live_preview_add_text30"] ?><input name="radio_watermark" type="radio" id="radio_watermark0" value="0" style="width:15px;height:28px;" <?php if(strlen($row["watermark"]) <= 4) echo "checked" ?>>&nbsp;&nbsp;&nbsp;&nbsp;
                                          	<?php echo $lang_array["live_preview_add_text31"] ?><input name="radio_watermark" type="radio" id="radio_watermark1" value="1" style="width:15px;height:28px;" <?php if(strlen($row["watermark"]) > 4) echo "checked" ?> >
                                          	<input class="input-file uniform_on" id="watermark" name="watermark" type="file"> 
                                        </div>
                                    </div>
                                    
  									<div class="control-group">
  										<label class="control-label"><?php echo $lang_array["live_preview_add_text9"] ?></label>
  										<div class="controls">
  											<select class="input-medium " name="previewtype" id="previewtype">
        										<option value="tvsou"><?php echo $lang_array["live_preview_add_text14"] ?></option>
        										<option value="tvmao"><?php echo $lang_array["live_preview_add_text15"] ?></option>
        										<option value="olleh"><?php echo $lang_array["live_preview_add_text16"] ?></option>
                                                <option value="yahoo-japan"><?php echo $lang_array["live_preview_add_text28"] ?></option>
												<option value="ontvtonight"><?php echo $lang_array["live_preview_add_text42"] ?></option>
                                                <option value="tvmap"><?php echo $lang_array["live_preview_add_text43"] ?></option>
                                                <option value="mod"><?php echo $lang_array["live_preview_add_text44"] ?></option>
  											</select>
                                            <input class="input-xxlarge focused" id="preview" name="preview" type="text" <?php echo "value='". $row["id"] ."'";?>>
  										</div>
  									</div>                                                                                                         
                                    <div class="form-actions">
   										<button type="button" class="btn btn-primary" onclick="save()"><?php echo $lang_array["save_text1"] ?></button>
                                		<button type="reset" class="btn" onclick="back_page()"><?php echo $lang_array["back_text1"] ?></button>
                            		</div>
                                   	</fieldset>
                                    
                                    <input name="liveurl" id="liveurl" type="hidden" value=""/>
        							<input name="livepw" id="livepw" type="hidden" value=""/>
       								<input name="livetype" id="livetype" type="hidden" value=""/>
									<input name="preview" id="preview" type="hidden" value=""/>
									<input name="previewid" id="previewid" type="hidden" value=""/>
                                    <input name="liveold_id" id="liveold_id" type="hidden" value=""/>
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
		
		function back_page()
		{
			window.location.href = "live_preview_list.php?page=<?php echo $page ?>";
				
		}
        </script>	


<script>

function escape2Html(str) {
 	var arrEntities={'lt':'<','gt':'>','nbsp':' ','amp':'&','quot':'"'};
 	return str.replace(/&(lt|gt|nbsp|amp|quot);/ig,function(all,t){return arrEntities[t];});
}
			
function add_selected_high_one(url,password)
{
	var length=document.getElementById("high_table_id").rows.length;
	var tr=document.createElement("tr");
	
	var td0=document.createElement("td"); 
	td0.width = '50%';
	if(length%2 == 1)
		td0.bgColor = '#ffffff';
	else
		td0.bgColor = '#dedede';
	td0.height = '10px';
	td0.style = 'text-align:center;vertical-align:middle;word-wrap:break-word;word-break:break-all;white-space: pre-wrap;';
	var font0=document.createElement("font");
	font0.size = '4px'; 
	font0.appendChild(document.createTextNode(escape2Html(url)));
	td0.appendChild(font0);
	tr.appendChild(td0);

	var pwuserids = password.replace("&amp;","&");
	var pwuserid = pwuserids.split("@PWUSERID@");
	var pw = "";
	var userid = "";
	if(pwuserid.length >= 2)
	{
		pw = pwuserid[0];
		userid = pwuserid[1];
	}
	else
	{
		pw = pwuserids;
	}
	
	var td1=document.createElement("td"); 
	td1.width = '15%';
	if(length%2 == 1)
		td1.bgColor = '#ffffff';
	else
		td1.bgColor = '#dedede';
	td1.height = '10px';
	td1.style = 'text-align:center;vertical-align:middle;word-break:break-all;';
	var font1=document.createElement("font");
	font1.size = '4px'; 
	font1.appendChild(document.createTextNode(pw.replace("&amp;","&")));
	td1.appendChild(font1);
	tr.appendChild(td1);
	
	var td2=document.createElement("td"); 
	td2.width = '15%';
	if(length%2 == 1)
		td2.bgColor = '#ffffff';
	else
		td2.bgColor = '#dedede';
	td2.height = '10px';
	td2.style = 'text-align:center;vertical-align:middle;';
	var font2=document.createElement("font");
	font2.size = '4px'; 
	font2.appendChild(document.createTextNode(userid.replace("&amp;","&")));
	td2.appendChild(font2);
	tr.appendChild(td2);
	
	var td22=document.createElement("td"); 
	var tr22=document.createElement("tr");
	
	var td2=document.createElement("td"); 
	td2.width = '2%';
	if(length%2 == 1)
		td2.bgColor = '#ffffff';
	else
		td2.bgColor = '#dedede';
	td2.height = '10px';
	td2.style = 'text-align:center;vertical-align:middle;word-break:break-all;';
	var font2=document.createElement("font");
	font2.size = '4px'; 
	var a2=document.createElement("a");
	a2.href = "#";
	td2.onclick = function deleteRow()
	{
		var index=this.parentNode.parentNode.parentNode.rowIndex;
 		var table = document.getElementById("high_table_id");
		table.deleteRow(index);
	}
	a2.appendChild(document.createTextNode("<?php echo $lang_array["del_text1"] ?>"));
	font2.appendChild(a2);
	td2.appendChild(font2);
	
	var td3=document.createElement("td"); 
	td3.width = '2%';
	if(length%2 == 1)
		td3.bgColor = '#ffffff';
	else
		td3.bgColor = '#dedede';
	td3.height = '10px';
	td3.style = 'text-align:center;vertical-align:middle;word-break:break-all;';
	var font3=document.createElement("font");
	font3.size = '4px'; 
	var a3=document.createElement("a");
	a3.href = "#";
	td3.onclick = function deleteRow()
	{
		var index=this.parentNode.parentNode.parentNode.rowIndex;
		if(index -1 < 1)
			return;
			
 		var high_table = document.getElementById("high_table_id");
		var high_rows = high_table.rows.length;
		var urls = Array();
		var pws = Array();
		var userids = Array();
		for(high_ii = 1; high_ii<high_rows; high_ii++)
		{
			urls.push(high_table.rows[high_ii].cells[0].childNodes[0].innerHTML.replace("&amp;","&"));
			var userid = high_table.rows[high_ii].cells[2].childNodes[0].innerHTML;
			if(userid.length > 0)
				pws.push(high_table.rows[high_ii].cells[1].childNodes[0].innerHTML + "@PWUSERID@" + userid);
			else
				pws.push(high_table.rows[high_ii].cells[1].childNodes[0].innerHTML);
		}
		
		for(high_ii=high_rows-1; high_ii>=1; high_ii--)
		{
			high_table.deleteRow(high_ii);
		}
		
		if(index-1 >= 1)
		{
			var up_url0 = urls[index-2];
			var up_url1 = urls[index-1];
			
			urls.splice(index-2,1,up_url1);
			urls.splice(index-1,1,up_url0);
			 
			var up_pw0 = pws[index-2];
			var up_pw1 = pws[index-1];
			
			pws.splice(index-2,1,up_pw1);
			pws.splice(index-1,1,up_pw0);	

			for(ii = 0; ii<urls.length; ii++)
			{
				add_selected_high_one(urls[ii],pws[ii]);
			}	 
		}
	}
	a3.appendChild(document.createTextNode("<?php echo $lang_array["live_preview_add_text22"] ?>"));
	font3.appendChild(a3);
	td3.appendChild(font3);
	
	tr22.appendChild(td2);
	tr22.appendChild(td3);
	
	td22.appendChild(tr22);
	
	tr.appendChild(td22);
	
	document.getElementById("highbody").appendChild(tr); 		
}

function add_high()
{
	var high_url = document.getElementById("high_url").value; 
	var high_pw = "";
	if(high_password_type() == 0)
	{
		high_pw = document.getElementById("high_pw").value; 
		if(high_pw.length > 1)
			high_pw = "geminipassword#" + high_pw;
		else
			high_pw = "geminipassword";
	}
	else
	{
		high_pw = document.getElementById("high_pw").value; 
	}
	var high_userid = document.getElementById("high_userid").value;
	 
	if(high_url.substring(0,7) != "http://" && high_url.substring(0,7) != "rtmp://" && high_url.substring(0,6) != "udp://"
		&& high_url.substring(0,3) != "mms" && high_url.substring(0,7) != "rtsp://" && high_url.substring(0,9) != "gemini://" 
		&& high_url.substring(0,6) != "p2p://" && high_url.substring(0,6) != "wow://" && high_url.substring(0,9) != "ffmpeg://"
		&& high_url.substring(0,7) != "gp2p://" && high_url.substring(0,8) != "ghttp://" && high_url.substring(0,8) != "https://"
		&& high_url.substring(0,10) != "forcetv://" && high_url.substring(0,8) != "tvbus://" && high_url.substring(0,7) != "vjms://")
	{
		alert("<?php echo $lang_array["live_preview_add_text21"] ?>");
		return;
	}	
	var high_pw_userid;
	if(high_userid.length > 0)
		high_pw_userid = high_pw + "@PWUSERID@" + high_userid;
	else
		high_pw_userid = high_pw;
		
	add_selected_high_one(high_url,high_pw_userid);
}

function high_password_type()
{
	var check_id;
	var chkObjs = document.getElementsByName("high_ps_radio");
	for(var i=0;i<chkObjs.length;i++){
		if(chkObjs[i].checked){
			check_id = i;
			break;
		}
	}	
	
	return check_id;
}

function low_password_type()
{
	var check_id;
	var chkObjs = document.getElementsByName("low_ps_radio");
	for(var i=0;i<chkObjs.length;i++){
		if(chkObjs[i].checked){
			check_id = i;
			break;
		}
	}	
	
	return check_id;
}

function add_selected_low_one(url,password)
{
	var length=document.getElementById("low_table_id").rows.length;
	var tr=document.createElement("tr");
	
	var td0=document.createElement("td"); 
	td0.width = '50%';
	if(length%2 == 1)
		td0.bgColor = '#ffffff';
	else
		td0.bgColor = '#dedede';
	td0.height = '10px';
	td0.style = 'text-align:center;vertical-align:middle;word-wrap:break-word;word-break:break-all;white-space: pre-wrap;';
	var font0=document.createElement("font");
	font0.size = '4px'; 
	font0.appendChild(document.createTextNode(escape2Html(url)));
	td0.appendChild(font0);
	tr.appendChild(td0);

	var pwuserids = password.replace("&amp;","&");
	var pwuserid = pwuserids.split("@PWUSERID@");
	var pw = "";
	var userid = "";
	if(pwuserid.length >= 2)
	{
		pw = pwuserid[0];
		userid = pwuserid[1];
	}
	else
	{
		pw = pwuserids;
	}
	
	var td1=document.createElement("td"); 
	td1.width = '15%';
	if(length%2 == 1)
		td1.bgColor = '#ffffff';
	else
		td1.bgColor = '#dedede';
	td1.height = '10px';
	td1.style = 'text-align:center;vertical-align:middle;';
	var font1=document.createElement("font");
	font1.size = '4px'; 
	font1.appendChild(document.createTextNode(pw.replace("&amp;","&")));
	td1.appendChild(font1);
	tr.appendChild(td1);
	
	var td2=document.createElement("td"); 
	td2.width = '15%';
	if(length%2 == 1)
		td2.bgColor = '#ffffff';
	else
		td2.bgColor = '#dedede';
	td2.height = '10px';
	td2.style = 'text-align:center;vertical-align:middle;';
	var font2=document.createElement("font");
	font2.size = '4px'; 
	font2.appendChild(document.createTextNode(userid.replace("&amp;","&")));
	td2.appendChild(font2);
	tr.appendChild(td2);
	
	var td22=document.createElement("td"); 
	var tr22=document.createElement("tr");
	
	var td2=document.createElement("td"); 
	td2.width = '2%';
	if(length%2 == 1)
		td2.bgColor = '#ffffff';
	else
		td2.bgColor = '#dedede';
	td2.height = '10px';
	td2.style = 'text-align:center;vertical-align:middle;';
	var font2=document.createElement("font");
	font2.size = '4px'; 
	var a2=document.createElement("a");
	a2.href = "#";
	td2.onclick = function deleteRow()
	{
		var index=this.parentNode.parentNode.parentNode.rowIndex;
 		var table = document.getElementById("low_table_id");
		table.deleteRow(index);
	}
	a2.appendChild(document.createTextNode("<?php echo $lang_array["del_text1"] ?>"));
	font2.appendChild(a2);
	td2.appendChild(font2);
	
	var td3=document.createElement("td"); 
	td3.width = '2%';
	if(length%2 == 1)
		td3.bgColor = '#ffffff';
	else
		td3.bgColor = '#dedede';
	td3.height = '10px';
	td3.style = 'text-align:center;vertical-align:middle;';
	var font3=document.createElement("font");
	font3.size = '4px'; 
	var a3=document.createElement("a");
	a3.href = "#";
	td3.onclick = function deleteRow()
	{
		/*
		var index=this.parentNode.rowIndex;
 		var table = document.getElementById("low_table_id");
		table.deleteRow(index);
		*/
		var index=this.parentNode.parentNode.parentNode.rowIndex;
		if(index -1 < 1)
			return;
			
 		var low_table = document.getElementById("low_table_id");
		var low_rows = low_table.rows.length;
		var urls = Array();
		var pws = Array();
		for(low_ii = 1; low_ii<low_rows; low_ii++)
		{
			urls.push(low_table.rows[low_ii].cells[0].childNodes[0].innerHTML.replace("&amp;","&"));
			//pws.push(low_table.rows[low_ii].cells[1].childNodes[0].innerHTML);
			var userid = low_table.rows[low_ii].cells[2].childNodes[0].innerHTML;
			if(userid.length > 0)
				pws.push(low_table.rows[low_ii].cells[1].childNodes[0].innerHTML + "@PWUSERID@" + userid);
			else
				pws.push(low_table.rows[low_ii].cells[1].childNodes[0].innerHTML);			
			//high_table.deleteRow(high_ii);
		}
		
		for(low_ii=low_rows-1; low_ii>=1; low_ii--)
		{
			low_table.deleteRow(low_ii);
		}
		//alert(urls);
		
		if(index-1 >= 1)
		{
			var up_url0 = urls[index-2];
			var up_url1 = urls[index-1];
			
			urls.splice(index-2,1,up_url1);
			urls.splice(index-1,1,up_url0);
			 
			var up_pw0 = pws[index-2];
			var up_pw1 = pws[index-1];
			
			pws.splice(index-2,1,up_pw1);
			pws.splice(index-1,1,up_pw0);	
			
			for(ii = 0; ii<urls.length; ii++)
			{
				add_selected_low_one(urls[ii],pws[ii]);
				//alert(urls[high_ii]);
			}	 
		}
	}
	a3.appendChild(document.createTextNode("<?php echo $lang_array["live_preview_add_text22"] ?>"));
	font3.appendChild(a3);
	td3.appendChild(font3);
	
	tr22.appendChild(td2);
	tr22.appendChild(td3);
	
	td22.appendChild(tr22);
	
	tr.appendChild(td22);

	document.getElementById("lowbody").appendChild(tr); 		
}

function add_low()
{
	var low_url = document.getElementById("low_url").value; 
	var low_pw = "";
	if(low_password_type() == 0)
	{
		low_pw = "geminipassword";
	}
	else
	{
		low_pw = document.getElementById("low_pw").value; 
	}
	var low_userid = document.getElementById("low_userid").value;
	
	if(low_url.substring(0,7) != "http://" && low_url.substring(0,7) != "rtmp://" && low_url.substring(0,6) != "udp://"
		&& low_url.substring(0,3) != "mms" && low_url.substring(0,7) != "rtsp://" && low_url.substring(0,9) != "gemini://" 
		&& low_url.substring(0,6) != "p2p://" && low_url.substring(0,6) != "wow://" && low_url.substring(0,9) != "ffmpeg://"
		&& low_url.substring(0,7) != "gp2p://" && low_url.substring(0,8) != "ghttp://" && low_url.substring(0,8) != "https://"
		&& low_url.substring(0,10) != "forcetv://" && low_url.substring(0,8) != "tvbus://" && low_url.substring(0,7) != "vjms://")
	{
		alert("<?php echo $lang_array["live_preview_add_text21"] ?>");
		return;
	}		
	
	var low_pw_userid;
	if(low_userid.length > 0)
		low_pw_userid = low_pw + "@PWUSERID@" + low_userid;
	else
		low_pw_userid = low_pw;
		
	add_selected_low_one(low_url,low_pw_userid);
}

function save()
{
	//alert(document.getElementById("preview_radio").value);
	var arrayId = new Array();
<?php
	foreach($namess as $names)
	{
		echo "arrayId.push('" . $names[7] . "');";
	}
?>
	document.getElementById("tipId").innerHTML = "";
	document.getElementById("tipName").innerHTML = "";
	document.getElementById("tipFile").innerHTML = "";
	document.getElementById("tipUrl").innerHTML = "";
	document.getElementById("tipType").innerHTML = "";
	
	var id = document.getElementById("live_id").value;
	if(id == "")
	{
		document.getElementById("tipId").innerHTML = "<?php echo $lang_array["live_preview_add_text32"] ?>";
		return;
	}
	
	if(parseInt(id) > 10000)
	{
		document.getElementById("tipId").innerHTML = "<?php echo $lang_array["live_preview_add_text33"] ?>";
		return;
	}
	
	var name = document.getElementById("name").value;
	if(name == "")
	{
		document.getElementById("tipName").innerHTML = "<?php echo $lang_array["live_preview_add_text34"] ?>";
		return;

	}
	
	for(ii=0; ii<arrayId.length; ii++)
	{
		if(arrayId[ii].localeCompare(id) == 0 && id != <?php echo $row["urlid"] ?>)
		{
			var r=confirm("<?php echo $lang_array["live_preview_add_text35"] ?>");
			if(r==true)
			{
				
			}
			else
			{
				document.getElementById("tipId").innerHTML = "<?php echo $lang_array["live_preview_add_text36"] ?>";
				return;
			}
		}
	}

	//var url = document.getElementById("url").value;
	var url = get_live_url();
	if(url == "" || url.length < 7)
	{
		document.getElementById("tipUrl").innerHTML = "<?php echo $lang_array["live_preview_add_text37"] ?>";
		return;
	}
	document.authform.liveurl.value = url;

	var pw = get_live_password();
	document.authform.livepw.value = pw;

	var checkbox_value = get_type_checkbox_value();
	if(checkbox_value == "")
	{
		document.getElementById("tipType").innerHTML = "<?php echo $lang_array["live_preview_add_text38"] ?>";
		return;
	}
	
	document.authform.livetype.value = checkbox_value;
	
	var id = document.getElementById("preview").value;
	document.authform.previewid.value = id;
	document.authform.preview.value = "null";	
	document.authform.liveold_id.value = "<?php echo $row["urlid"] ?>";
	document.authform.submit();
	/*
	var check_id;
	var chkObjs = document.getElementsByName("preview_radio");
	for(var i=0;i<chkObjs.length;i++){
		if(chkObjs[i].checked){
			check_id = i;
			break;
		}
	}
	
	var checkbox_value = get_type_checkbox_value();
	if(checkbox_value == "")
	{
		document.getElementById("tipType").innerHTML = "类型不能为空";
		return;
	}
	
	document.authform.livetype.value = checkbox_value;
	//alert(check_id);
	if(check_id == 0)
	{
		var id = document.getElementById("preview").value;
		document.authform.previewid.value = id;
		document.authform.preview.value = "null";
		document.authform.submit();
	}
	else if(check_id == 1)
	{
		var id = document.getElementById("preview").value;
		document.authform.previewid.value = id;
		document.authform.preview.value = "null";
		document.authform.submit();
	}
	else if(check_id == 2)
	{
		var id = document.getElementById("preview").value;
		document.authform.previewid.value = id;
		document.authform.preview.value = "null";
		document.authform.submit();
	}
	else
	{
		var table = document.getElementById("table_id");
   		var rows = table.rows.length;
		var ii = 0;
		var option = "";
		for(ii = 1; ii<rows; ii++)
		{
//alert(document.getElementById("selected_table_id").rows[ii].cells[0].childNodes[0].innerHTML);
			var time = document.getElementById("table_id").rows[ii].cells[0].childNodes[0].innerHTML;
			var preview = document.getElementById("table_id").rows[ii].cells[1].childNodes[0].innerHTML;
			option = option + time + "#" + preview;
			if(ii<rows-1)
				option = option + "|";
		}
		document.authform.previewid.value = "null";
		document.authform.preview.value = new Date().pattern("yyyy-MM-dd")+ "$#geminidate#$" + option;
		document.authform.submit();
	}
	*/
}

function get_live_url()
{
		var num = -1;
		var high_table = document.getElementById("high_table_id");
   		var high_rows = high_table.rows.length;
		var high_ii = 0;
		var high_option = "";
		var high_url_one = "";
		for(high_ii = 1; high_ii<high_rows; high_ii++)
		{
			num++;
//alert(document.getElementById("selected_table_id").rows[ii].cells[0].childNodes[0].innerHTML);
			var high_url = high_url_one = document.getElementById("high_table_id").rows[high_ii].cells[0].childNodes[0].innerHTML;
			var high_num = num;
			//var high_pw = document.getElementById("high_table_id").rows[ii].cells[1].childNodes[0].innerHTML;
			high_option = high_option + high_num + "#" + high_url;

			if(high_ii<high_rows-1)
				high_option = high_option + "|";
		}
		
		
		var low_table = document.getElementById("low_table_id");
   		var low_rows = low_table.rows.length;
		var low_ii = 0;
		var low_option = "";
		for(low_ii = 1; low_ii<low_rows; low_ii++)
		{
			num++;
//alert(document.getElementById("selected_table_id").rows[ii].cells[0].childNodes[0].innerHTML);
			var low_url = document.getElementById("low_table_id").rows[low_ii].cells[0].childNodes[0].innerHTML;
			var low_num = num;
			//var low_pw = document.getElementById("low_table_id").rows[ii].cells[1].childNodes[0].innerHTML;
			low_option = low_option + low_num + "#" + low_url;
			if(low_ii<low_rows-1)
				low_option = low_option + "|";
		}

		if(high_option == "" && low_option == "")
			return "";
			
		var option = high_option + "geminihighlowgemini" + low_option;
		
		return escape2Html(option);	
}

function get_live_password()
{
		var num = -1;
		var high_table = document.getElementById("high_table_id");
   		var high_rows = high_table.rows.length;
		var high_ii = 0;
		var high_option = "";
		var high_pw_one = "";
		for(high_ii = 1; high_ii<high_rows; high_ii++)
		{
			num++;
			//var high_pw = high_pw_one = document.getElementById("high_table_id").rows[high_ii].cells[1].childNodes[0].innerHTML;
			var high_userid = document.getElementById("high_table_id").rows[high_ii].cells[2].childNodes[0].innerHTML;
			var high_pw = high_pw_one = document.getElementById("high_table_id").rows[high_ii].cells[1].childNodes[0].innerHTML;
			high_pw = high_pw.replace("&amp;","&");
			high_pw = high_pw.replace("#","gjinghaog");
			if(high_userid.length > 0)
				high_pw = high_pw_one = high_pw + "@PWUSERID@" + high_userid;
				
			var high_num = num;
			//var high_pw = document.getElementById("high_table_id").rows[ii].cells[1].childNodes[0].innerHTML;
			high_option = high_option + high_num + "#" + high_pw;
			if(high_ii<high_rows-1)
				high_option = high_option + "|";
		}
		
		var low_table = document.getElementById("low_table_id");
   		var low_rows = low_table.rows.length;
		var low_ii = 0;
		var low_option = "";
		for(low_ii = 1; low_ii<low_rows; low_ii++)
		{
			num++;
//alert(document.getElementById("selected_table_id").rows[ii].cells[0].childNodes[0].innerHTML);
			//var low_pw = document.getElementById("low_table_id").rows[low_ii].cells[1].childNodes[0].innerHTML;
			var low_userid = document.getElementById("low_table_id").rows[low_ii].cells[2].childNodes[0].innerHTML;
			var low_pw = document.getElementById("low_table_id").rows[low_ii].cells[1].childNodes[0].innerHTML;
			low_pw = low_pw.replace("&amp;","&");
			low_pw = low_pw.replace("#","gjinghaog");
			if(low_userid.length > 0)
				low_pw = low_pw + "@PWUSERID@" + low_userid;
				
			var low_num = num;
			//var low_pw = document.getElementById("low_table_id").rows[ii].cells[1].childNodes[0].innerHTML;
			low_option = low_option + low_num + "#" + low_pw;
			if(low_ii<low_rows-1)
				low_option = low_option + "|";
		}

		if(high_option == "" && low_option == "")
			return "";
			
		return 	escape2Html(high_option + "geminihighlowgemini" + low_option);	
		
		//return high_pw_one;
}

function get_type_checkbox_value()
{
	var value = "";
	var value_array = new Array();
	var type_checkboxs = document.getElementsByName("type_checkbox");
	for(var i = 0; i < type_checkboxs.length; i++)
	{
		if(type_checkboxs[i].type == "checkbox" && type_checkboxs[i].checked)
		{
			value_array.push(type_checkboxs[i].value);
			
			
		}
	}
	
	for(var ii = 0; ii < value_array.length; ii++)
	{
		value = value + value_array[ii];
		if(ii < value_array.length - 1)
			value = value + "|";
	}
	
	//alert(value);
	return value;
}

function open_language()
{
	window.location.href = "live_preview_language_edit.php?key=<?php echo $id ?>&page=<?php echo $page ?>";	
}
</script>


<script>
<?php
	/*
	if(strcmp($row["id"],"null") == 0)
	{
		$previewss = $row["preview"];
		//echo $previewss;
		
		$tmp = explode("$#geminidate#$",$previewss);
		if(count($tmp) > 1)
		{	
			$previews = explode("|",$tmp[1]);
		
			//echo count($previews);
			for($ii=0; $ii<count($previews); $ii++)
			{
				$preview = explode("#",$previews[$ii]);
				if(count($preview) >= 2)
					echo "add_selected_one(\"" . $preview[0] . "\",\"" . $preview[1] . "\");";
			}
		}
	}
	*/
	
	if(count($urls) >= 1)
	{
		for($ii=0; $ii<count($high_urls); $ii++)
		{
			//echo $high_urls[$ii];
			$selected_url="";
			$selected_pw="";
		
			$high_url = explode("#",$high_urls[$ii]);
			if(count($high_url) >= 2)
				$selected_url =  $high_url[1];
			else if(count($high_url) >= 1)
				$selected_url =  $high_url[0];
			else
				$selected_url =  $high_urls[$ii];
			
			$high_pw = explode("#",$high_pws[$ii]);
			if(count($high_pw) >= 2)
				$selected_pw =  $high_pw[1];
			else if(count($high_pw) >= 1)
				$selected_pw =  $high_pw[0];
			else
				$selected_pw =  $high_pws[$ii];
			
			$selected_url = str_replace("&amp;","&",$selected_url);
			$selected_pw = str_replace("&amp;","&",$selected_pw);
			$selected_pw = str_replace("gjinghaog","#",$selected_pw);
			//if(count($high_url) >= 2)
			
			//echo $selected_url;
			//echo $selected_pw;
			
			if(strlen($selected_url)>=7)
				echo "add_selected_high_one(\"" . $selected_url . "\",\"" . $selected_pw . "\");";
		}
	}

	if(count($urls) >= 2)
	{
		for($ii=0; $ii<count($low_urls); $ii++)
		{
			//echo $low_urls[$ii];
			$low_url = explode("#",$low_urls[$ii]);
			$low_pw = explode("#",$low_pws[$ii]);
		
			if(count($low_url) >= 2)
			{
				$selected_low_url = str_replace("&amp;","&",$low_url[1]);
				$selected_low_pw = str_replace("&amp;","&",$low_pw[1]);
				$selected_low_pw = str_replace("gjinghaog","#",$selected_low_pw);
				echo "add_selected_low_one(\"" . $selected_low_url . "\",\"" . $selected_low_pw . "\");";
			}
		}
	}
?>
</script>

    </body>


<?php
	$sql->disconnect_database();

?>
</html>