<!DOCTYPE html>
<?php
	include_once "cn_lang.php";
	include_once "common.php";
	include_once 'gemini.php';
	
	$sql = new DbSql();
	$sql->login();
	
	$g = new Gemini();
	$g->check_version();
	
	$sql->connect_database_default();
	$mydb = $sql->get_database();
	$sql->create_database($mydb);

	$mytable = "vod_table_".$_GET["type"];
	$sql->create_table($mydb, $mytable, "name text, image text, 
			url text, area text, year text, type text, intro1 longtext, intro2 longtext, 
			intro3 longtext, intro4 longtext, id int, clickrate int, recommend tinyint, chage float, updatetime int, firstletter text, status int");
	$row = $sql->get_row_data($mydb, $mytable, "id", $_GET["id"]);
		
	$mytable = "vod_type_table_" . $_GET["type"];
	$sql->create_table($mydb, $mytable, "value longtext, id smallint");
			
	$value0 = $sql->query_data($mydb, $mytable, "id", 0 , "value"); 
	$value1 = $sql->query_data($mydb, $mytable, "id", 1 , "value"); 
	$value2 = $sql->query_data($mydb, $mytable, "id", 2 , "value"); 
	
	if(strlen($value0) < 2)
		$value0 = "爱情|纪录|喜剧|科幻";
	if(strlen($value1) < 2)
		$value1 = "2013|2012|2011|2010";
	if(strlen($value2) < 2)
		$value2 = "中国|美国|香港";
			
	$type_value0s = explode("|",$value0);
	$type_value1s = explode("|",$value1);
	$type_value2s = explode("|",$value2);	
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
    
    <body onLoad="init()">
        <div class="container-fluid">
            <div class="row-fluid">
              	<div class="span13" id="content">
                <div class="row-fluid">
                   <!-- block -->
						<div class="block">
                          <div class="navbar navbar-inner block-header">
                                <div class="muted pull-left"><?php echo $lang_array["vod_add_text1"] ?></div>
                            </div>
                            
                            <div class="block-content collapse in">
                                <div class="span12">
                                	<form class="form-horizontal" name="authform" method="post" action="vod_post.php?type=<?php echo $_GET["type"] ?>&id=<?php echo $_GET["id"] ?>" enctype='multipart/form-data'>
                                    <fieldset><br/>

                                    <div class="control-group">
                                       <label class="control-label" for="focusedInput"><?php echo $lang_array["vod_add_text2"] ?></label>
                                       <div class="controls">
                                          <input class="input-large focused" id="name" name="name" type="text" value="<?php echo $row["name"] ?>">
                                          <label id="tipName" style="color:#F00"></label>
                                       </div>
                                   	</div>
                                    
                                    <div class="control-group">
                                        <label class="control-label" for="fileInput"><?php echo $lang_array["vod_add_text3"] ?></label>
                                        <div class="controls">
                                          <input class="input-file uniform_on" id="file" name="file" type="file">&nbsp;&nbsp;<?php echo $lang_array["vod_add_text38"] ?>&nbsp;&nbsp;<input class="input-xlarge focused" id="iconurl" name="iconurl" type="text" value="<?php if(strstr($row["image"],"http://") != false) echo $row["image"] ?>">
                                        </div>
                                    </div> 
                                                                       
  									<div class="control-group">
  										<label class="control-label"><?php echo $lang_array["vod_add_text4"] ?></label>
  										<div class="controls">
  											<select class="input-medium " name="area" id="area">
											<?php
												for($ii=0; $ii<count($type_value2s); $ii++)
												{
													if(intval($row['area']) == ($ii+1))
														echo "<option value='" . ($ii+1) . "' selected>" . $type_value2s[$ii] . "</option>";
													else
														echo "<option value='" . ($ii+1) . "'>" . $type_value2s[$ii] . "</option>";
												}
											?>
  											</select>
  										</div>
  									</div>  									
                                    
                                    <div class="control-group">
  										<label class="control-label"><?php echo $lang_array["vod_add_text5"] ?></label>
  										<div class="controls">
  											<select class="input-medium " name="year" id="year">
											<?php
												for($ii=0; $ii<count($type_value1s); $ii++)
												{
													if(intval($row['year']) == ($ii+1))
														echo "<option value='" . ($ii+1) . "' selected>" . $type_value1s[$ii] . "</option>";
													else
														echo "<option value='" . ($ii+1) . "'>" . $type_value1s[$ii] . "</option>";
												}
											?>
  											</select>
  										</div>
  									</div>
                                    
                                    <div class="control-group">
  										<label class="control-label"><?php echo $lang_array["vod_add_text6"] ?></label>
  										<div class="controls">
										<?php
											$types = explode("|",$row['type']);
											for($ii=0; $ii<count($type_value0s); $ii++)
											{
												$checked = 0;
												foreach($types as $type)
												{
													if(intval($ii) == (intval($type)-1))
													{
														$checked = 1;
													}
												}
			
												if($checked == 1)
												{
													echo "<input class='uniform_on' name='type_checkbox' type='checkbox' value='" . ($ii+1) . "' checked/>" . $type_value0s[$ii] . "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
												}
												else
												{
													echo "<input class='uniform_on' name='type_checkbox' type='checkbox' value='" . ($ii+1) . "' />" . $type_value0s[$ii] . "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
												}
											}
											if($ii+1%5==0)
												echo "<br/>";
										?>
  										</div>
  									</div>
                                    
                                    <div class="control-group">
  										<label class="control-label"><?php echo $lang_array["vod_add_text7"] ?></label>
  										<div class="controls">
                                        <?php
											for($ii=1; $ii<=5; $ii++)
											{
												if($ii == $row["recommend"])
												{
    												echo "<input type='radio' name='recommend' value='".$ii."' checked='checked' style='width:15px;height:28px;'/>".$ii.$lang_array["vod_add_text29"]."&nbsp;&nbsp;";
												}
												else
												{
													echo "<input type='radio' name='recommend' value='".$ii."' style='width:15px;height:28px;'/>".$ii.$lang_array["vod_add_text29"]."&nbsp;&nbsp;";
												}
											}
										?>
                                        <label id="tipType" style="color:#F00"></label>
                                        </div>
  									</div>
                                    
                                    <div class="control-group">
                                        <label class="control-label" for="fileInput"><?php echo $lang_array["vod_add_text9"] ?></label>
                                        <div class="controls">
                                          <input class="input-large focused" id="firstletter" name="firstletter" type="text" value="<?php echo $row["firstletter"] ?>">
                                        </div>
                                    </div>          
                                                              
                                    <div class="control-group">
                                        <label class="control-label" for="fileInput"><?php echo $lang_array["vod_add_text10"] ?></label>
                                        <div class="controls">
                                          <input class="input-large focused" id="introduction2" name="introduction2" type="text" value="<?php echo $row["intro2"] ?>">
                                        </div>
                                    </div> 
                                                                      
                                    <div class="control-group">
                                        <label class="control-label" for="fileInput"><?php echo $lang_array["vod_add_text11"] ?></label>
                                        <div class="controls">
                                          <input class="input-xxlarge focused" id="introduction3" name="introduction3" type="text" value="<?php echo $row["intro3"] ?>">
                                        </div>
                                    </div>   
                                                                             
                                    <div class="control-group">
                                        <label class="control-label" for="fileInput"><?php echo $lang_array["vod_add_text12"] ?></label>
                                        <div class="controls">
                                          <textarea class="input-xlarge focused" id="introduction4" name="introduction4" placeholder="Enter text ..." style="width: 810px; height: 80px"><?php echo $row["intro4"] ?></textarea>
                                        </div>
                                    </div>
                                    <div class="control-group">
  										<label class="control-label"><?php echo $lang_array["vod_add_text39"] ?></label>
  										<div class="controls">
											<?php echo $lang_array["vod_add_text40"] ?><input type="radio" name="vodstatus" value="0" style="width:15px;height:28px;"  <?php if(intval($row['status']) == 0) echo "checked";?>/>&nbsp;&nbsp;
                                            <?php echo $lang_array["vod_add_text41"] ?><input type="radio" name="vodstatus" value="1" style="width:15px;height:28px;"  <?php if(intval($row['status']) == 1) echo "checked";?>/>
                                        </div>
  									</div>									
                                    <div class="control-group">
  										<label class="control-label"><?php echo $lang_array["vod_add_text36"] ?></label>
  										<div class="controls">
											<?php echo $lang_array["vod_add_text34"] ?><input type="radio" name="vodurl" value="0" style="width:15px;height:28px;"  checked/>&nbsp;&nbsp;
                                            <?php echo $lang_array["vod_add_text35"] ?><input type="radio" name="vodurl" value="1" style="width:15px;height:28px;"  />
                                        </div>
  									</div>    
                                    <div class="control-group">
										<label class="control-label" ><?php echo $lang_array["vod_add_text13"] ?></label>
  
                                       <div class="controls">
											<input class="input-mini focused" id="num_id" name="num_id" type="text" value="">&nbsp;<?php echo $lang_array["vod_add_text25"] ?>&nbsp;&nbsp;&nbsp;
                                            <input class="input-xxlarge focused" id="url_id" name="url_id" type="text" value="">
                                            &nbsp;&nbsp;<?php echo $lang_array["vod_add_text33"] ?>:<input class="input-large focused" name='pw_id' id='pw_id' type="text" style="width:72px">
                                            <button type="button" class="btn btn-primary" onClick="add()"><?php echo $lang_array["add_text1"] ?></button><label id="tipUrl" style="color:#F00"></label>
                                        </div>                                          
                                    </div> 
                                    <table cellpadding="0" cellspacing="0" border="0" class="table table-bordered" id="table_id">
                                        <thead border="2">
                                            <tr>
          										<th width="10%" style="vertical-align:middle; text-align:center;"><?php echo $lang_array["vod_add_text22"] ?></th>
            									<th width="55%" style="vertical-align:middle; text-align:center;"><?php echo $lang_array["vod_add_text23"] ?></th>
                                            	<th width="15%" style="vertical-align:middle; text-align:center;"><?php echo $lang_array["vod_add_text33"] ?></th>
          										<th width="15%" style="vertical-align:middle; text-align:center;"><?php echo $lang_array["vod_add_text24"] ?></th>
                                            </tr>
                                        </thead>
                                       	<tbody id="newbody"> 
										</tbody>
									</table>
                                    
                                    <input name="url" id="url" type="hidden" value=""/>
									<input name="livetype" id="livetype" type="hidden" value=""/>     
                                                                                                              
                                    <div class="form-actions">
   										<button type="button" class="btn btn-primary" onclick="save()"><?php echo $lang_array["save_text1"] ?></button>
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
		
		function back_page()
		{
			window.location.href = "vod_list.php?type=<?php echo $_GET["type"] ?>";
				
		}
		
		function init()
		{
		<?php
			$vods = explode("|", $g->j4($row["url"]));
			$num_next = 1;
			for($ii=0; $ii<count($vods); $ii++)
			{
				//echo $tmps[0];
				$tmps = explode("#", $vods[$ii]);
				$tmpss = explode("geminipwgemini",$tmps[1]);
				if(count($tmpss) >= 2)
					echo "add_selected_one(\"" . $tmps[0] . "\",\"" . $tmpss[0] . "\",\"" . $tmpss[1] . "\");";
				else
					echo "add_selected_one(\"" . $tmps[0] . "\",\"" . $tmpss[0] . "\",\"\");";
					
				if($num_next < intval($tmps[0]))
					$num_next = intval($tmps[0]);
			}
		?>	

			document.getElementById("num_id").value = <?php echo ($num_next+1) ?>;
		}
		
        </script>	
<script>


function escape2Html(str) {
 	var arrEntities={'lt':'<','gt':'>','nbsp':' ','amp':'&','quot':'"'};
 	return str.replace(/&(lt|gt|nbsp|amp|quot);/ig,function(all,t){return arrEntities[t];});
}

function mitem()
{
    this.times = "";
    this.value = "";
	this.pw = "";
}

function pad(num, n) {  
    var len = num.toString().length;  
    while(len < n) {  
        num = "0" + num;  
        len++;  
    }  
    return num;  
}  

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
		value = value + pad(value_array[ii],3);
		if(ii < value_array.length - 1)
			value = value + "|";
	}
	return value;
}

function add_selected_one(num,url,pw)
{
	var length=document.getElementById("table_id").rows.length;
	var tr=document.createElement("tr");
	var td0=document.createElement("td"); 
	td0.width = '10%';
	if(length%2 == 1)
		td0.bgColor = '#ffffff';
	else
		td0.bgColor = '#dedede';
	td0.height = '10px';
	td0.style = 'text-align:center;vertical-align:middle;';
	var font0=document.createElement("font");
	font0.size = '2px'; 
	font0.appendChild(document.createTextNode(num));
	td0.appendChild(font0);
	tr.appendChild(td0);

	var td1=document.createElement("td"); 
	td1.width = '55%';
	if(length%2 == 1)
		td1.bgColor = '#ffffff';
	else
		td1.bgColor = '#dedede';
	td1.height = '10px';
	td1.style = 'text-align:center;vertical-align:middle;';
	var font1=document.createElement("font");
	font1.size = '2px'; 
	font1.appendChild(document.createTextNode(escape2Html(url)));
	td1.appendChild(font1);
	tr.appendChild(td1);
	
	var td3=document.createElement("td"); 
	td3.width = '15%';
	td3.style = 'text-align:center;vertical-align:middle;';
	if(length%2 == 1)
		td3.bgColor = '#ffffff';
	else
		td3.bgColor = '#dedede';
	td3.height = '15px';
	var font3=document.createElement("font");
	font3.size = '2px'; 
	font3.appendChild(document.createTextNode(escape2Html(pw)));
	td3.appendChild(font3);
	tr.appendChild(td3);
	
	var td2=document.createElement("td"); 
	td2.width = '15%';
	if(length%2 == 1)
		td2.bgColor = '#ffffff';
	else
		td2.bgColor = '#dedede';
	td2.height = '10px';
	td2.style = 'text-align:center;vertical-align:middle;';
	var font2=document.createElement("font");
	font2.size = '2px'; 
	var a2=document.createElement("a");
	a2.href = "#";
	td2.onclick = function deleteRow()
	{
		var index=this.parentNode.rowIndex;
 		var table = document.getElementById("table_id");
		table.deleteRow(index);
	}
	a2.appendChild(document.createTextNode("<?php echo $lang_array["del_text1"] ?>"));
	font2.appendChild(a2);
	td2.appendChild(font2);
	tr.appendChild(td2);

	document.getElementById("newbody").appendChild(tr); 		
}

function add()
{
	var items = new Array();
	var pushed = 0;
	
	var table = document.getElementById("table_id");
	var rows = table.rows.length;

	var url0 = escape2Html(document.getElementById("url_id").value); 
	var num0 = document.getElementById("num_id").value; 
	var pw0 = escape2Html(document.getElementById("pw_id").value);

	var num_next = 1;
	
	if(num0.length <= 0 || url0.length < 7)
	{
		alert("<?php echo $lang_array["vod_add_text32"] ?>");
		return;	
	}
	
	for(var ii = rows-1; ii>=1; ii--)
	{
		var num1 = document.getElementById("table_id").rows[ii].cells[0].childNodes[0].innerHTML;
		if(parseInt(num0) == parseInt(num1))
		{
			alert("<?php echo $lang_array["vod_add_text26"] ?>" + "：" +　num0 + "<?php echo $lang_array["vod_add_text27"] ?>");
			return;
		}
	}
	
	var type_url = GetRadioValue("vodurl");
	if(type_url == 1 || type_url == 3)
	{
		url0 = "youku@" + url0 +"?tudou";
	}

	if(rows < 2)
	{
		add_selected_one(num0,url0,pw0);
	}
	else
	{
		for(ii = rows-1; ii>=1; ii--)
		{
			var num1 = document.getElementById("table_id").rows[ii].cells[0].childNodes[0].innerHTML;
			var url1 = document.getElementById("table_id").rows[ii].cells[1].childNodes[0].innerHTML;
			var pw1 = document.getElementById("table_id").rows[ii].cells[2].childNodes[0].innerHTML;
			
			table.deleteRow(ii);
		
			if((parseInt(num0) > parseInt(num1)) && pushed == 0)
			{
				pushed = 1;
				var nitem = new mitem();
				nitem.times = num0;
				nitem.value = url0;
				nitem.pw = pw0;
				items.push(nitem);
			}

			var nitem = new mitem();
			nitem.times = num1;
			nitem.value = url1;
			nitem.pw = pw1;
			items.push(nitem);
		}

		for(var ii=items.length-1; ii>=0; ii--)
		{
			
			add_selected_one(items[ii].times,items[ii].value,items[ii].pw);
		}

	}
	
	for(var ii = rows-1; ii>=1; ii--)
	{
		var num1 = document.getElementById("table_id").rows[ii].cells[0].childNodes[0].innerHTML;
		if(num_next < num1)
			num_next = num1;
	}
	
	if(num_next < num0)
		num_next = num0;
		
	document.getElementById("num_id").value = (parseInt(num_next) + 1);
}
			
function save()
{
	document.getElementById("tipName").innerHTML = "";
	document.getElementById("tipUrl").innerHTML = "";
	document.getElementById("tipType").innerHTML = "";
	
	var name = document.getElementById("name").value;
	if(name == "")
	{
		alert("<?php echo $lang_array["vod_add_text28"] ?>");
		return;
	}
	
	var table = document.getElementById("table_id");
   	var rows = table.rows.length;
	var ii = 0;
	var option = "";
	for(ii = 1; ii<rows; ii++)
	{
		var num = document.getElementById("table_id").rows[ii].cells[0].childNodes[0].innerHTML;
		var url = document.getElementById("table_id").rows[ii].cells[1].childNodes[0].innerHTML.replace("&amp;","&");
		var pw = document.getElementById("table_id").rows[ii].cells[2].childNodes[0].innerHTML.replace("&amp;","&");
		//option = option + num + "#" + escape2Html(url);
		if(url.indexOf("p2p://") >= 0)
			option = option + num + "#" + escape2Html(url) + "geminipwgemini" + pw.replace("&amp;","&");
		else
			option = option + num + "#" + escape2Html(url);
			
		if(ii<rows-1)
			option = option + "|";
	}
	
	if(option == "")
	{
		alert("<?php echo $lang_array["vod_add_text30"] ?>");
		return;
	}
	
	var checkbox_value = get_type_checkbox_value();
	if(checkbox_value == "")
	{
		alert("<?php echo $lang_array["vod_add_text31"] ?>");
		return;
	}

	document.authform.livetype.value = checkbox_value;
	document.authform.url.value = option;
	document.authform.submit();
}
</script>
</body>


<?php
	$sql->disconnect_database();

?>
</html>