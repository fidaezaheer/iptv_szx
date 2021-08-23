<!DOCTYPE html>
<?php
	include_once "cn_lang.php";
	include_once "common.php";
	include_once "gemini.php";
	$sql = new DbSql();
	$sql->login();

	$g = new Gemini();

	$size = 100;
	$offset = 0;
	$page = 0;
	if(isset($_GET["page"]))
	{
		$offset = $size*intval($_GET["page"]);
		$page = $_GET["page"];
	}

			$sql->connect_database_default();
			$mydb = $sql->get_database();
			$sql->create_database($mydb);
			
			$mytable = "live_type_table";
			$sql->create_table($mydb, $mytable, "name longtext, id longtext");
			$type_namess = $sql->fetch_datas($mydb, $mytable);
			$type_find_id = "";
			foreach($type_namess as $type_names) {
				$type_array[$type_names[1]] = $type_names[0];
			}
			if(isset($_GET["findtype"]))
			{
				$findtype = $_GET["findtype"];
				$type_find_id = $sql->query_data($mydb, $mytable, "name", $findtype, "id");
			}
			
			$mytable = "live_preview_table";
			$sql->create_table($mydb, $mytable, "name longtext, image longtext, url longtext, password longtext, type longtext, preview longtext, id longtext, urlid smallint, hd longtext, watermark text, lang text");
			
			$sql->delete_data($mydb,$mytable,"urlid",20000);
			//$namess = $sql->fetch_datas_order_asc($mydb, $mytable,"urlid");
			$namess = array();
			if(isset($_GET["find"]))
			{
				$find = $_GET["find"];
				$namess = $sql->fetch_datas_where_like_5_or($mydb, $mytable, "name", $find, "urlid", $find);
			}
			else if(isset($_GET["findtype"]) && strlen($type_find_id) > 1)
			{
				$namess = $sql->fetch_datas_where_like_asc($mydb, $mytable, "type", $type_find_id, "urlid");
			}
			else if(isset($_GET["findurl"]))
			{
				$find = $_GET["findurl"];
				$namess_urls = $sql->fetch_datas($mydb, $mytable);
				foreach($namess_urls as $namess_url)
				{
					$urls = base64_decode($g->j2($namess_url[2]));
					if(strstr($urls,$find) != false)
					{
						array_push($namess,$namess_url);
					}
				}
			}
			else
			{
				$namess = $sql->fetch_datas_limit_asc($mydb, $mytable, $offset, $size, "urlid");
			}
			
			$numrows = count($sql->fetch_datas($mydb, $mytable));	
			$pages = 0;
			$pages = intval($numrows/$size);
			if($numrows%$size)
			{
				$pages++;
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
                                <div class="muted pull-left"><?php echo $lang_array["live_preview_list_text2"] ?></div>
                            </div>
                            <div class="block-content collapse in">
                                <div class="span12">
                                   <form class="form-horizontal" name="authform" method="post" action="" enctype='multipart/form-data'>
                                   <div class="table-toolbar">
                                      <div class="btn-group">
                                         <!--<a href="live_preview_add.php"><button class="btn btn-success"><?php //echo $lang_array["live_preview_list_text1"] ?></button></a>-->
                                         <input class="btn btn-success" name="" type="button" value="<?php echo $lang_array["live_preview_list_text1"] ?>" onclick="open_page('live_preview_add.php?page=<?php echo $page ?>')"/>
                                      </div>
                                      
                                      <div class="btn-group">
                                      	<input class="btn btn-success" name="" type="button" value="<?php echo $lang_array["live_preview_list_text14"] ?>" onclick="batch_url()"/>&nbsp;
                                         
                                      </div>
                                      
                                      <div class="btn-group">
                                      	<input class="btn btn-success" name="" type="button" value="<?php echo $lang_array["live_preview_list_text30"] ?>" onclick="batch_ps()"/>&nbsp;
                                         
                                      </div>
                                      
                                      <div class="btn-group">
                                      	<input class="btn btn-success" name="" type="button" value="<?php echo $lang_array["live_preview_list_text34"] ?>" onclick="batch_type()"/>&nbsp;
                                         
                                      </div>
                                      
                                      <div class="btn-group">
                                      	<input class="btn btn-success" name="" type="button" value="<?php echo $lang_array["live_preview_list_text18"] ?>" onclick="batch_Introduction()"/>&nbsp;
                                         
                                      </div>
                                      
                                      
                                      <div class="btn-group">
                                      	<input class="btn btn-success" name="" type="button" value="<?php echo $lang_array["live_preview_list_text19"] ?>" onclick="batch_export()"/>&nbsp;
                                         
                                      </div>
                                     
                                      
                                      <div class="btn-group">
                                      	<input class="btn btn-success" name="" type="button" value="<?php echo $lang_array["live_preview_list_text20"] ?>" onclick="batch_del()"/>&nbsp;
                                         
                                      </div>
                                      
                                        
                                   </div>
                                   
                                   
                                   <div class="control-group" style="background-color:#CCC">
                                   		<br/>
                                        <label class="control-label"><?php echo $lang_array["live_preview_list_text32"] ?></label>
                                        <div class="controls">
                                        	<select id="find_select_id" name=""  style='width:120px;' onchange="">
											<?php
											echo "<option value='0' selected='selected' style='width:120px;'>" . $lang_array["live_preview_list_text36"] . "</option>";
											echo "<option value='1' style='width:120px;'>" . $lang_array["live_preview_list_text37"] . "</option>";
											echo "<option value='2' style='width:120px;'>" . $lang_array["live_preview_list_text39"] . "</option>";
											?>
    										</select>
                                            
 											<input type="text" list="url_list" name="text" id="find_id" placeholder="<?php echo $lang_array["live_preview_list_text38"] ?>" onfocus="this.placeholder=''" onblur="if(this.placeholder==''){this.placeholder='<?php echo $lang_array["live_preview_list_text38"] ?>'}" />
											<datalist id="url_list">
                                            <?php
												foreach($type_namess as $type_names)
												{
													echo "<option label='" . $type_names[0] . "' value='" . $type_names[0] . "' />";
													
												}
											?>
                                            
                                            <!--
 											<option label="HZ赫兹工作室" value="http://weibo.com/hz421247910" />
 											<option label="提示1" value="列表项1" />
 											<option label="提示2" value="列表项2" />
 											<option label="" value="列表项3" />
                                            -->
                                            
											</datalist>
                                        <!--<input class="input-medium focused" id="find_id" name="" type="text" style="width: 200px"/>-->
                                        
                                        
                                        &nbsp;<input name="" class="btn btn-primary" type="button" value="<?php echo $lang_array["live_preview_list_text32"] ?>" onclick="find_live()"/>&nbsp;<input name="" class="btn btn-primary" type="button" value="<?php echo $lang_array["live_preview_list_text33"] ?>" onclick="find_all()"/>
                                        </div>
                                        <br/>
                                        <div class="btn-group">
                                      		<label class="control-label"><?php echo $lang_array["live_preview_list_text10"] ?></label>
                                        	<div class="controls">
                                        	<input class="input-medium focused" id="key_id" name="" type="text" size="5" maxlength="5" style="width: 30px"/>&nbsp;&nbsp;&nbsp;&nbsp;<input name="" class="btn btn-primary" type="button" value="<-<?php echo $lang_array["live_preview_list_text15"] ?>->" onclick="swap_live()"/>&nbsp;&nbsp;&nbsp;&nbsp;<input class="input-medium focused" id="key1_id" name="" type="text" size="5" maxlength="5" style="width: 30px"/>
                                        	</div>
                                      	</div>
                                        <br/><br/>
                                   </div>
                                   
                                   </form>
                                   <table cellpadding="0" cellspacing="0" border="0" class="table table-striped table-bordered" style="table-layout:fixed;">
                                        <thead>
                                            <tr>
          									<th width="3%" style="vertical-align:middle; text-align:center;"><?php echo $lang_array["live_preview_list_text3"] ?></th>
          									<th width="4%" style="vertical-align:middle; text-align:center;"><?php echo $lang_array["live_preview_list_text4"] ?></th>
          									<th width="5%" style="vertical-align:middle; text-align:center;"><?php echo $lang_array["live_preview_list_text5"] ?></th>
            								<th width="10%" style="vertical-align:middle; text-align:center;"><?php echo $lang_array["live_preview_list_text6"] ?></th>
            								<th width="46%" style="vertical-align:middle; text-align:center;"><?php echo $lang_array["live_preview_list_text7"] ?></th>
            								<th width="15%" style="vertical-align:middle; text-align:center;"><?php echo $lang_array["live_preview_list_text8"] ?></th>
          									<th width="10%" style="vertical-align:middle; text-align:center;"><?php echo $lang_array["live_preview_list_text9"] ?></th>
                                            </tr>
                                        </thead>
		<?php
		

			
			echo "<tbody>";
			foreach($namess as $names) 
			{
				if(intval($names[7]) > 10000)
				{
					continue;
				}
				echo "<tr>";
				$key = null;
				$key = $names[7];
				echo "<td style='vertical-align:middle; text-align:center;'>";
				//echo "<input name='live_checkbox' type='checkbox' value='" . $key . "' />";
				echo "<div class='controls'><input class='uniform_on' name='live_checkbox' type='checkbox' style='width:15px;height:25px;' value='" . $key . "' /></div>";
				echo "</td>";
				echo "<td style='vertical-align:middle; text-align:center;'>".$names[7]."</td>";
				if(preg_match("/^(http:\/\/|https:\/\/).*$/",$names[1])){
                    echo "<td style='vertical-align:middle; text-align:center;'><img src='" . $names[1] . "' width='24' height='24' /></td>";
                }else{
					echo "<td style='vertical-align:middle; text-align:center;'><img src='" . "../images/livepic/" . $names[1] . "' width='24' height='24' /></td>";
				}
				echo "<td style='vertical-align:middle; text-align:center;'>".$names[0]."</td>";
				
				$urlss = base64_decode($g->j2($names[2]));
				$urlss = str_replace(" ","%20",$urlss); 
				
				$urls = explode("geminihighlowgemini",$urlss);
				if(count($urls) >= 1)
					$high_urls = explode("|",$urls[0]);
					
				if(count($urls) >= 2)
					$low_urls = explode("|",$urls[1]);
				$value = "";
				if(count($urls) >= 1 && strlen($urls[0]) > 1) 
				{
					$value = $lang_array["live_preview_list_text24"] . "--" . $lang_array["live_preview_list_text25"] . count($high_urls) . " " . $lang_array["live_preview_list_text26"] . "：";
					if(count($high_urls) > 0)
					{
						$high_url = explode("#",$high_urls[0]);
						if(count($high_url) >= 2)
							$value = $value . $high_url[1];
						else if(count($high_url) >= 1)
							$value = $value . $high_url[0];
						else
							$value = $value . $high_urls[0];
							
					}
					if(strlen($value) > 96)
						$value = substr($value,0,96) . "......";
						
					if(count($high_urls) > 1)
						$value = $value . " ......";
				}
				
				if(count($urls) >= 2 && strlen($urls[0]) > 1 && strlen($urls[1]) > 1) 
					$value = $value . "<br/>";
					
				if(count($urls) >= 2 && strlen($urls[1]) > 1) 
				{
					$value = $value . $lang_array["live_preview_list_text23"] . "--" . $lang_array["live_preview_list_text25"] . count($low_urls) . " " . $lang_array["live_preview_list_text26"] . "：";
					if(count($low_urls) > 0)
					{
						$low_url = explode("#",$low_urls[0]);
						$value = $value . $low_url[1];
					}
					if(strlen($value) > 96)
						$value = substr($value,0,96) . "......";
						
					if(count($low_urls) > 1)
						$value = $value . " ......";
				}
				echo "<td style='vertical-align:middle; text-align:center;'>".$value."</td>";
				$explode_type_names = explode("|",$names[4]);
				$explode_type_names_all = "";
				for($ii=0; $ii<count($explode_type_names); $ii++)
				{
					$ok = 0;
					foreach($type_namess as $type_names)
					{
						if(strcmp($type_names[1],$explode_type_names[$ii]) == 0)
						{
							$ok = 1;
						}
					}
					
					if($ok == 1)
					{
						$explode_type_names_all = $explode_type_names_all . $type_array[$explode_type_names[$ii]];
						if($ii < count($explode_type_names) - 1)
							$explode_type_names_all = $explode_type_names_all . "|"; 
					}
				}
				
				echo "<td style='vertical-align:middle; text-align:center;'>".$explode_type_names_all."</td>";
				
				echo "<td style='vertical-align:middle; text-align:center;'><a href='live_preview_edit.php?key=" . $key . "&page=" . $page . "'>".$lang_array["edit_text1"]."</a>&nbsp;&nbsp;<a href='#' onclick='delete_live(\"".$names[0]."\",\"" . $key . "\")'>".$lang_array["del_text1"]."</a></td>";
				echo "</tr>";
			}
			echo "</tbody>";
			
			
			$sql->disconnect_database();
        ?>                                   
                                    </table>
                                    <form class="form-horizontal" name="authform" method="post" action="" enctype='multipart/form-data'>
                                    
                                    <input name="" class="btn btn-primary" type="button" value="<?php echo $lang_array["account_add_text16"] ?>" onclick="selectAll()"/>
            						<input name="" class="btn btn-primary" type="button" value="<?php echo $lang_array["account_add_text17"] ?>" onclick="noAll()"/>
            
                                    <div class="form-actions">
										<button type="button" class="btn btn-primary" onclick="go_pre()"><?php echo $lang_array["custom_list_text17"] ?></button>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                						<button type="button" class="btn btn-primary" onclick="go_page()"><?php echo $lang_array["custom_list_text19"] ?></button>&nbsp;<input class="input-mini focused" id="pageid" name="pageid" type="text" value="<?php echo ($page+1) ?>" ><?php echo $lang_array["custom_list_text20"] ?>/<?php echo $pages ?>&nbsp;<?php echo $lang_array["custom_list_text20"] ?>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                						<button type="button" class="btn btn-primary" onclick="go_back()"><?php echo $lang_array["custom_list_text18"] ?></button>
            						</div> 
                                    </form>
                                    
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <FORM name="authform_del" action="batch_del.php" method="post">  
    	<input name="del" id="del" type="hidden" value=""/>
        <input name="page" id="page" type="hidden" value=""/>
    	</Form>
    
    	<FORM name="authform_type" action="batch_type_list.php" method="post" target="newWin">  
    	<input name="type" id="type" type="hidden" value=""/>
    	</Form> 
    
        <!--/.fluid-container-->

        <script src="vendors/jquery-1.9.1.js"></script>
        <script src="bootstrap/js/bootstrap.min.js"></script>
        <script src="vendors/datatables/js/jquery.dataTables.min.js"></script>


        <script src="assets/scripts.js"></script>
        <script src="assets/DT_bootstrap.js"></script>
        
        <script>
        $(function() {
            
        });
		
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
		
		function delete_live(name,id)
		{
			var r=confirm("<?php echo $lang_array["live_preview_add_text27"] ?>:" + name + " ?");
			if(r==true)
  			{
				var url = "live_preview_del.php?name=" + name + "&id=" + id + "&page=" + <?php echo $page ?>;
<?php
				if($_GET["find"])
					echo "url = url + \"&find=" . $_GET["find"] . "\";";
					
				if($_GET["findtype"])
					echo "url = url + \"&findtype=" . $_GET["findtype"] . "\";";
					
				if($_GET["findurl"])
					echo "url = url + \"&findurl=" . $_GET["findurl"] . "\";";
				
?>
				window.location.href = url;
  			}
		}

		function open_page(page)
		{
			window.location.href = page;
		}
		
		function batch_url()
		{
			var r=confirm("<?php echo $lang_array["live_preview_list_text16"] ?>" + "？");
			if(r==true)
  			{	
				var old_url= window.prompt("<?php echo $lang_array["live_preview_list_text16"] ?>" + ":");
				var new_url= window.prompt("<?php echo $lang_array["live_preview_list_text17"] ?>" + ":");
				var rr=confirm("<?php echo $lang_array["live_preview_list_text12"] ?>" + "：" + old_url + " -> " + new_url + "<?php echo $lang_array["live_preview_list_text13"] ?>" + "?");
				if(rr == true)
				{
					var checked = get_type_checkbox_value();
					if(checked.length > 0)
						window.location.href = "batch_url.php?url0=" + new_url + "&url1=" + old_url + "&checked=" + base64encode(checked);
					else
						window.location.href = "batch_url.php?url0=" + new_url + "&url1=" + old_url;
				}
			}
		}
		
		function batch_ps()
		{
			
			window.open("batch_ps_list.php", "newwindow", "height=300, width=800, toolbar =no, menubar=no, scrollbars=no, resizable=no, location=no, status=no");
			
			/*
			var r=confirm("<?php echo $lang_array["live_preview_list_text31"] ?>" + "？");
			if(r==true)
  			{	
				var old_ps= window.prompt("<?php echo $lang_array["live_preview_list_text27"] . "," . $lang_array["live_preview_list_text41"] ?>" + ":");
				var new_ps= window.prompt("<?php echo $lang_array["live_preview_list_text28"] ?>" + ":");
				
				var text = old_ps;
				if(old_ps.length <= 0)
					text = "<?php echo $lang_array["live_preview_list_text40"] ?>";
				
				var rr=confirm("<?php echo $lang_array["live_preview_list_text29"] ?>" + "：" + text + " -> " + new_ps + "<?php echo $lang_array["live_preview_list_text13"] ?>" + "?");
				if(rr == true)
				{
					if(new_ps.length > 0)
						window.location.href = "batch_ps.php?ps0=" + new_ps + "&ps1=" + old_ps;
				}
			}
			*/
		}
		
		function batch_type()
		{
			var value = get_type_checkbox_value();
			if(value.length < 1)
				alert("<?php echo $lang_array["live_preview_list_text35"] ?>");
			else
			{
				//window.open("batch_type_list.php?type=" + value,"","height=180,width=600");
				window.open('','newWin','height=480,width=800');  
				document.authform_type.type.value = value;
      			document.authform_type.submit()
			}
		}

		function swap_live()
		{
			var key = document.getElementById("key_id").value;
			var key1 = document.getElementById("key1_id").value;
			var url = "live_preview_swap.php?key=" + key + "&key1=" + key1;
			window.location.href = url;
		}

		function edit_user(name)
		{
  			{
				var url = "user_edit.php?name=" + name;
				window.location.href = url;
  			}
		}
		
		function batch_Introduction()
		{
			var r=confirm("<?php echo $lang_array["live_preview_list_text21"] ?>");
			if(r==true)
  			{	
				window.location.href = "batch_introduction_list.php";
			}	
		}

		function selectAll() //全选
		{
			//alert(0);
			var objs = document.getElementsByName('live_checkbox');
			var i;
			for(i = 0; i < objs.length; i++)
			{
				if(objs[i].type == "checkbox")
				{
					objs[i].checked = true;
				}
			}
		} 

		function noAll() //全选
		{
			//alert(0);
			var objs = document.getElementsByName('live_checkbox');
			var i;
			for(i = 0; i < objs.length; i++)
			{
				if(objs[i].type == "checkbox")
				{
					objs[i].checked = false;
				}
			}
		} 
		
		function get_type_checkbox_value()
		{
			var value = "";
			var value_array = new Array();
			var type_checkboxs = document.getElementsByName("live_checkbox");
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

		function batch_del()
		{
			var r=confirm("<?php echo $lang_array["live_preview_list_text22"] ?>");
			if(r==true)
  			{	
				document.authform_del.del.value = get_type_checkbox_value();
				document.authform_del.page.value = <?php echo $page ?>;
      			document.authform_del.submit()	
			}
		}
		
		function batch_export()
		{
			
			//window.location.href = "batch_export_post.php";
			window.open("batch_export_list.php", "newwindow", "height=200, width=600, toolbar =no, menubar=no, scrollbars=no, resizable=no, location=no, status=no");//写成一行  
		}
		
		function go_page()
		{
			var pageid = document.getElementById("pageid").value;
			var url = "live_preview_list.php?page=" + (pageid-1);
			window.location.href = url;
		}
		
		function go_pre()
		{
<?php
			if($page-1 >= 0)
			{
				echo "var url = 'live_preview_list.php?page=".($page-1)."';";
				echo "window.location.href = url;";
			}
?>
		}
		
		function go_back()
		{
<?php
			if($page+1 < $pages)
			{
				echo "var url = 'live_preview_list.php?page=".($page+1)."';";
				echo "window.location.href = url;";
			}
?>			
		}
		
		function find_live()
		{
			var selectvalue = document.getElementById("find_select_id").value;
			var value = document.getElementById("find_id").value;
			if(selectvalue == "0")
			{
				var url = "live_preview_list.php?find=" + value;
				window.location.href = url;
			}
			else if(selectvalue == "1")
			{
				var url = "live_preview_list.php?findtype=" + value;
				window.location.href = url;
			}
			else if(selectvalue == "2")
			{
				var url = "live_preview_list.php?findurl=" + value;
				window.location.href = url;
			}
		}
		
		function find_all()
		{
			var url = "live_preview_list.php";
			window.location.href = url;
		}
		
        </script>
    </body>

</html>