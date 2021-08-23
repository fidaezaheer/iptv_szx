<!DOCTYPE html>
<?php
	include_once "cn_lang.php";
	include_once "common.php";
	include_once 'gemini.php';
	
	$sql = new DbSql();
	$sql->login();

	$g = new Gemini();
	
	$urlid = $_GET["key"];
	
	$sql->connect_database_default();
	$mydb = $sql->get_database();
	$sql->create_database($mydb);
	
	$mytable = "playback_type_table";
	$sql->create_table($mydb, $mytable, "name text, id text ,need int, typepassword text, param0 text, param1 text, param2 text, param3 text");
	$namess = $sql->fetch_datas($mydb, $mytable);
	
	$mytable = "live_preview_table";
	$sql->create_table($mydb, $mytable, "name longtext, image longtext, url longtext, password longtext, type longtext, preview longtext, id longtext, urlid smallint, hd longtext");
	$row = $sql->get_row_data($mydb, $mytable, "urlid", $urlid);
	
	$urlss = base64_decode($g->j2($row["url"]));
	//echo "AAA:". $urlss;
	$urls = explode("geminihighlowgemini",$urlss);
	if(count($urls) >= 1)
		$high_urls = explode("|",$urls[0]);
	if(count($urls) >= 2)
		$low_urls = explode("|",$urls[1]);
		
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
                                <div class="muted pull-left"><?php echo $lang_array["playback_edit_text1"] ?></div>
                            </div>
                            
                            <div class="block-content collapse in">
                                <div class="span12">
                                	<form class="form-horizontal" name="authform" method="post" action="playback_post.php?id=<?php echo $urlid; ?>" enctype='multipart/form-data'>
                                    <fieldset><br/>

                                    <div class="control-group">
                                       <label class="control-label" for="focusedInput"><?php echo $lang_array["playback_add_text2"] ?></label>
                                       <div class="controls">
                                          <input class="input-mini focused" id="live_id" name="live_id" type="text" readonly="readonly" value="<?php echo (intval($row["urlid"])-10000) ?>">
                                          <label id="tipId" style="color:#F00"></label>
                                       </div>
                                   	</div>
                                    
                                    <div class="control-group">
                                       <label class="control-label" for="focusedInput"><?php echo $lang_array["playback_add_text3"] ?></label>
                                       <div class="controls">
                                          <input class="input-medium focused" id="name" name="name" type="text" value="<?php echo $row["name"]?>">&nbsp;&nbsp;
                                          <button type="button" class="btn btn-primary" onClick="open_language()"><?php echo $lang_array["live_type_add_text4"] ?></button>
                                          <label id="tipName" style="color:#F00"></label>
                                       </div>
                                   	</div>  
                                                                      
                                    <div class="control-group">
                                        <label class="control-label" for="fileInput"><?php echo $lang_array["playback_add_text4"] ?></label>
                                        <div class="controls">
                                          <input class="input-file uniform_on" id="file" name="file" type="file">
                                          <label id="tipFile" style="color:#F00"></label>
                                        </div>
                                    </div>
                                    
                                    <div style="background-color:#CCC">  
                                    <br/> 
                                    <div class="control-group">
                                       <label class="control-label" for="focusedInput"><?php echo $lang_array["playback_add_text5"] ?></label>
                                       <div class="controls">
                                          <input class="input-xxlarge focused" id="high_url" name="high_url" type="text" value="">
                                          <button type="button" class="btn btn-primary" onClick="add_high()"><?php echo $lang_array["add_text1"] ?></button>
                                          <label id="tipUrl" style="color:#F00"></label>
                                       </div>
                                   	</div>
                                    <table cellpadding="0" cellspacing="0" border="0" class="table table-bordered" id="high_table_id">
                                        <thead border="2">
                                            <tr>
          									<th width="70%" style="vertical-align:middle; text-align:center;"><?php echo $lang_array["playback_add_text10"] ?></th>
          									<th width="25%" style="vertical-align:middle; text-align:center;"><?php echo $lang_array["playback_add_text11"] ?></th>
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
                                       <label class="control-label" for="focusedInput"><?php echo $lang_array["playback_add_text6"] ?></label>
                                       <div class="controls">
                                          <input class="input-xxlarge focused" id="low_url" name="low_url" type="text" value="">
                                          <button type="button" class="btn btn-primary" onClick="add_low()"><?php echo $lang_array["add_text1"] ?></button>
                                       </div>
                                   	</div>
                                    <table cellpadding="0" cellspacing="0" border="0" class="table table-bordered" id="low_table_id">
                                        <thead>
                                            <tr>
          									<th width="70%" style="vertical-align:middle; text-align:center;"><?php echo $lang_array["playback_add_text10"] ?></th>
          									<th width="25%" style="vertical-align:middle; text-align:center;"><?php echo $lang_array["playback_add_text11"] ?></th>
                                            </tr>
                                        </thead>
                                       	<tbody id="lowbody"> 
										</tbody>
									</table>
                                    </div>
                                    <br/> 
                       
									<div class="control-group">
                                       	<label class="control-label" for="focusedInput"><?php echo $lang_array["playback_add_text7"] ?></label>
                                    	<div class='controls'>
<?php
									$index = 1;
									foreach($namess as $names) 
									{
										$checked = 0;
										foreach($types as $type)
										{
											if(strcmp($names[1],$type)==0)
											{
												$checked = 1;
											}
										}
										
										if($checked == 1)
										{
											echo "<input class='uniform_on' name='type_checkbox' type='checkbox' value='" . $names[1] . "' checked />" . $names[0] . "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
										}
										else
										{
											echo "<input class='uniform_on' name='type_checkbox' type='checkbox' value='" . $names[1] . "' />" . $names[0] . "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
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
  										<label class="control-label"><?php echo $lang_array["playback_add_text8"] ?></label>
  										<div class="controls">
  											<select class="input-medium " name="sourcetype" id="sourcetype" style="width:100px;">
        										<option value="sd" <?php if(strcmp($row["hd"],"sd") == 0)echo "selected"; ?>><?php echo $lang_array["live_preview_add_text11"] ?></option>
        										<option value="hd" <?php if(strcmp($row["hd"],"hd") == 0)echo "selected"; ?>><?php echo $lang_array["live_preview_add_text12"] ?></option>
        										<option value="p" <?php if(strcmp($row["hd"],"p") == 0)echo "selected"; ?>><?php echo $lang_array["live_preview_add_text13"] ?></option>
  											</select>
											(<?php echo $lang_array["live_preview_edit_text2"] ?>)
  										</div>
  									</div>
                                    
  									<div class="control-group">
  										<label class="control-label"><?php echo $lang_array["playback_add_text9"] ?></label>
  										<div class="controls">
  											<select class="input-medium " name="previewtype" id="previewtype" style="width:120px;">
        										<option value="tvsou"><?php echo $lang_array["live_preview_add_text14"] ?></option>
        										<option value="tvmao"><?php echo $lang_array["live_preview_add_text15"] ?></option>
        										<option value="olleh"><?php echo $lang_array["live_preview_add_text16"] ?></option>
                                                <option value="yahoo-japan"><?php echo $lang_array["live_preview_add_text28"] ?></option>
												<option value="ontvtonight"><?php echo $lang_array["live_preview_add_text42"] ?></option>
                                                <option value="tvmap"><?php echo $lang_array["live_preview_add_text43"] ?></option>
                                                <option value="mod"><?php echo $lang_array["live_preview_add_text44"] ?></option>
  											</select>
                                            
                                            <input class="input-xxlarge focused" id="preview" name="preview" type="text" <?php echo "value='". $row["id"] ."'";?> style="width:650px;">
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
			window.location.href = "playback_list.php";
				
		}
        </script>	


<script>

function escape2Html(str) {
 	var arrEntities={'lt':'<','gt':'>','nbsp':' ','amp':'&','quot':'"'};
 	return str.replace(/&(lt|gt|nbsp|amp|quot);/ig,function(all,t){return arrEntities[t];});
}
			
function add_selected_high_one(url)
{
	var length=document.getElementById("high_table_id").rows.length;
	var tr=document.createElement("tr");
	
	var td0=document.createElement("td"); 
	td0.width = '15%';
	if(length%2 == 1)
		td0.bgColor = '#ffffff';
	else
		td0.bgColor = '#dedede';
	td0.height = '10px';
	td0.style = 'text-align:center;vertical-align:middle;';
	var font0=document.createElement("font");
	font0.size = '4px'; 
	font0.appendChild(document.createTextNode(escape2Html(url)));
	td0.appendChild(font0);
	tr.appendChild(td0);

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
	td3.style = 'text-align:center;vertical-align:middle;';
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
		for(high_ii = 1; high_ii<high_rows; high_ii++)
		{
			urls.push(high_table.rows[high_ii].cells[0].childNodes[0].innerHTML.replace("&amp;","&"));
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

			for(ii = 0; ii<urls.length; ii++)
			{
				add_selected_high_one(urls[ii]);
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
	
	/*
	var high_pw = "";
	if(high_password_type() == 0)
	{
		high_pw = "geminipassword";
	}
	else
	{
		high_pw = document.getElementById("high_pw").value; 
	}
	var high_userid = document.getElementById("high_userid").value;
	*/
	
	if(high_url.substring(0,7) != "http://" && high_url.substring(0,7) != "rtmp://" && high_url.substring(0,6) != "udp://"
		&& high_url.substring(0,3) != "mms" && high_url.substring(0,7) != "rtsp://" && high_url.substring(0,9) != "gemini://" 
		&& high_url.substring(0,6) != "p2p://" && high_url.substring(0,6) != "wow://" && high_url.substring(0,9) != "ffmpeg://"
		&& high_url.substring(0,7) != "gp2p://")
	{
		alert("<?php echo $lang_array["live_preview_add_text21"] ?>");
		return;
	}	
	
	/*
	var high_pw_userid;
	if(high_userid.length > 0)
		high_pw_userid = high_pw + "@PWUSERID@" + high_userid;
	else
		high_pw_userid = high_pw;
	*/
		
	add_selected_high_one(high_url);
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

function add_selected_low_one(url)
{
	var length=document.getElementById("low_table_id").rows.length;
	var tr=document.createElement("tr");
	
	var td0=document.createElement("td"); 
	td0.width = '15%';
	if(length%2 == 1)
		td0.bgColor = '#ffffff';
	else
		td0.bgColor = '#dedede';
	td0.height = '10px';
	td0.style = 'text-align:center;vertical-align:middle;';
	var font0=document.createElement("font");
	font0.size = '4px'; 
	font0.appendChild(document.createTextNode(escape2Html(url)));
	td0.appendChild(font0);
	tr.appendChild(td0);

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
			
			for(ii = 0; ii<urls.length; ii++)
			{
				add_selected_low_one(urls[ii]);
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
	
	if(low_url.substring(0,7) != "http://" && low_url.substring(0,7) != "rtmp://" && low_url.substring(0,6) != "udp://"
		&& low_url.substring(0,3) != "mms" && low_url.substring(0,7) != "rtsp://" && low_url.substring(0,9) != "gemini://" 
		&& low_url.substring(0,6) != "p2p://" && low_url.substring(0,6) != "wow://" && low_url.substring(0,9) != "ffmpeg://"
		&& low_url.substring(0,7) != "gp2p://")
	{
		alert("<?php echo $lang_array["live_preview_add_text21"] ?>");
		return;
	}		
		
	add_selected_low_one(low_url);
}

function save()
{
	//alert(document.getElementById("preview_radio").value);
	var arrayId = new Array();
	<?php
		$mytable = "live_preview_table";
		$sql->create_table($mydb, $mytable, "name longtext, image longtext, url longtext, password longtext, type longtext, preview longtext, id longtext, urlid smallint, hd longtext");
		$namess = $sql->fetch_datas($mydb, $mytable);
		foreach($namess as $names)
		{
			if(intval($names[7]) > 10000)
				echo "arrayId.push('" . (intval($names[7])-10000) . "');";
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
	
	/*
	for(ii=0; ii<arrayId.length; ii++)
	{
		if(arrayId[ii].localeCompare(id) == 0)
		{
			document.getElementById("tipId").innerHTML = "<?php echo $lang_array["live_preview_add_text35"] ?>";
			return;
		}
	}
	//var url = document.getElementById("url").value;
	*/
	
	var url = get_live_url();
	if(url == "" || url.length < 7)
	{
		document.getElementById("tipUrl").innerHTML = "<?php echo $lang_array["live_preview_add_text37"] ?>";
		return;
	}
	document.authform.liveurl.value = url;

	//var pw = get_live_password();
	//document.authform.livepw.value = pw;

	var checkbox_value = get_type_checkbox_value();
	if(checkbox_value == "")
	{
		document.getElementById("tipType").innerHTML = "<?php echo $lang_array["live_preview_add_text38"] ?>";
		return;
	}
	
	document.authform.livetype.value = checkbox_value;
	
	var previewid = document.getElementById("preview").value;
	document.authform.previewid.value = previewid;
	document.authform.preview.value = "null";	
	document.authform.submit();
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
	window.location.href = "playback_language_edit.php?key=<?php echo $urlid ?>";	
}
</script>

<script>
<?php	
	if(count($urls) >= 1)
	{
		for($ii=0; $ii<count($high_urls); $ii++)
		{
		//echo $high_urls[$ii];
		$selected_url="";
		$high_url = explode("#",$high_urls[$ii]);
		if(count($high_url) >= 2)
			$selected_url =  $high_url[1];
		else if(count($high_url) >= 1)
			$selected_url =  $high_url[0];
		else
			$selected_url =  $high_urls[$ii];

		$selected_url = str_replace("&amp;","&",$selected_url);
		if(strlen($selected_url)>=7)
			echo "add_selected_high_one(\"" . $selected_url . "\");";
		}
	}

	if(count($urls) >= 2)
	{
		for($ii=0; $ii<count($low_urls); $ii++)
		{
		//echo $low_urls[$ii];
		$low_url = explode("#",$low_urls[$ii]);
		if(count($low_url) >= 2)
		{
			$selected_low_url = str_replace("&amp;","&",$low_url[1]);
			echo "add_selected_low_one(\"" . $selected_low_url . "\");";
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