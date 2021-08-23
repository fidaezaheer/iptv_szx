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
	$sql->create_table($mydb, $mytable, "name text, password text, ptip text, edit int, watermark text, validity date, allow int, remark text, ccount int, panal int, pwmd5 int, logonum text");
	$ccount = $sql->query_data($mydb, $mytable,"name",$_COOKIE["user"], "ccount");
	$logonum = $sql->query_data($mydb, $mytable,"name",$_COOKIE["user"], "logonum");
	
	$mytable = "account_table";
	$sql->create_table($mydb, $mytable, "id int, playlist text, days text, proxy text, 
			export int, used int, mac text, cpuid text, cdkey text, showtime text, type text, member text, startime datetime");
	
	//echo "find column:" . $sql->find_column($mydb, $mytable, "showtime");
	if($sql->find_column($mydb, $mytable, "showtime") == 0)
		$sql->add_column($mydb, $mytable,"showtime", "text");
	
	if($sql->find_column($mydb, $mytable, "type") == 0)
		$sql->add_column($mydb, $mytable,"type", "text");
		
	if($sql->find_column($mydb, $mytable, "member") == 0)
		$sql->add_column($mydb, $mytable,"member", "text");
			
	if($sql->find_column($mydb, $mytable, "startime") == 0)
		$sql->add_column($mydb, $mytable,"startime", "datetime");
		
	$pages = 0;
	$size = 20;
	$page = 0;
	$offset = 0;
	$select_playlist_value = "-1";
	if(isset($_GET["select_playlist"]))
	{
		$select_playlist_value = $_GET["select_playlist"];
	}

	if(isset($_GET["page"]))
	{
		$offset = $size*intval($_GET["page"]);
		$page = $_GET["page"];
	}
	
	
	$export = 0;
	if(isset($_GET["export"]))
	{
		$export = intval($_GET["export"]);
	}

	if(isset($_GET["find"]))
	{
		$namess = $sql->fetch_datas_limit_where_like_and($mydb, $mytable, $offset, $size, "cdkey", $_GET["find"], "proxy", $_COOKIE["user"]); 
		$numrows = count($sql->fetch_datas_where_like_2($mydb, $mytable, "cdkey", $_GET["find"], "proxy", $_COOKIE["user"]));
	}
	else
	{
		//$namess = $sql->fetch_datas_limit_desc($mydb, $mytable, $offset, $size,"id");
		if(isset($_GET["select_playlist"]) && $_GET["select_playlist"] != "-1")
		{
			$namess = $sql->fetch_datas_limit_where_2($mydb, $mytable, $offset, $size,"proxy", $_COOKIE["user"], "playlist", $select_playlist_value);
			$numrows = count($sql->fetch_datas_where_2($mydb, $mytable, "proxy", $_COOKIE["user"], "playlist", $select_playlist_value));
		}
		else
		{
			
			$namess = $sql->fetch_datas_limit_where_desc($mydb, $mytable, $offset, $size,"proxy", $_COOKIE["user"], "startime");
			$numrows = count($sql->fetch_datas_where_desc($mydb, $mytable, "proxy", $_COOKIE["user"], "id"));
		}
	}
	
	$pages = intval($numrows/$size);
	if($numrows%$size > 0)
	{
		$pages++;
	}

	$all_account = count($sql->fetch_datas_where_desc($mydb, $mytable, "proxy", $_COOKIE["user"], "id"));
	$used_account = count($sql->fetch_datas_where_2($mydb, $mytable, "proxy", $_COOKIE["user"], "used", 1));
	$no_used_account = count($sql->fetch_datas_where_2($mydb, $mytable, "proxy", $_COOKIE["user"], "used", 0));
	
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
                                <div class="muted pull-left"><?php echo $lang_array["proxy_account_list_text1"] ?></div>
                            </div>
                            <div class="block-content collapse in">
                                <div class="span12"><div class="table-toolbar">
                                	<?php
									if($ccount == 1)
									{
                                		echo "<div class='btn-group'>";
                                    	echo "<a href='proxy_account_add.php'><button class='btn btn-success'>" . $lang_array["proxy_account_list_text1"] . "<i class='icon-plus icon-white'></i></button></a>";
                                    	echo "</div>";
                                    	echo "<br/>";
                                    	echo "<br/>";
									}
                                    ?>
									<form class="form-horizontal" name="authform" method="post" action="" enctype='multipart/form-data'>
                                    <div class="control-group" style="background-color:#CCC"><br/>
										<label class="control-label" ><?php echo $lang_array["proxy_account_list_text2"] ?></label>
                                        <div class="controls">
											<input id="find_id" name="" type="text" size="32" />&nbsp;<input class="btn btn-primary" name="" type="button" value=<?php echo $lang_array["proxy_account_list_text18"] ?> onclick="find_id2()"/>&nbsp;<input class="btn btn-primary"  name="" type="button" value=<?php echo $lang_array["proxy_account_list_text19"] ?> onclick="find_all()"/>
                                        </div>    

										<br/>
                                        
										<label class="control-label" ><?php echo $lang_array["proxy_account_list_text3"] ?></label>
                                        <div class="controls">
											<select id="proxy_select_id" name=""  style='width:220px;' onchange="select_playlist(this)">
											<?php
												$mytable = "playlist_type_table";
												$sql->create_table($mydb, $mytable, "name text, space longtext, id text, playlist longtext");
												$playlistss = $sql->fetch_datas($mydb, $mytable);
												if($select_playlist_value == "-1")
												{
													echo "<option value='-1' selected='selected'>" . $lang_array["proxy_account_list_text4"] . "</option>";
												}
												else
												{
													echo "<option value='-1'>" . $lang_array["proxy_account_list_text4"] . "</option>";
												}
		
												$mytable = "account_table";
												$sql->create_table($mydb, $mytable, "id int, playlist text, days text, proxy text, export int, used int, mac text, cpuid text, cdkey text, showtime text, type text");
		
												if(count($sql->fetch_datas_where_2($mydb, $mytable,"proxy", $_COOKIE["user"], "playlist", "all")) > 0)
												{
													if($select_playlist_value == "all")
													{
														echo "<option value='all' selected='selected'>" . $lang_array["proxy_account_list_text5"] . "</option>";
													}
													else
													{
														echo "<option value='all'>" . $lang_array["proxy_account_list_text5"] . "</option>";
													}
												}
		
												if(count($sql->fetch_datas_where_2($mydb, $mytable,"proxy", $_COOKIE["user"], "playlist", "auto")) > 0)
												{
													if($select_playlist_value == "auto")
													{
														echo "<option value='auto' selected='selected'>" . $lang_array["proxy_account_list_text6"] . "</option>";
													}
													else
													{
														echo "<option value='auto'>" . $lang_array["proxy_account_list_text6"] . "</option>";
													}
												}
		
												foreach($playlistss as $playlists) 
												{
													if(strstr($proxy_playlist,$playlists[2]) != false)
													{
														$checked = "";
														if($select_playlist_value == $playlists[2])
															$checked = "' selected='selected' ";	
													
														//if(count($sql->fetch_datas_where_2($mydb, $mytable,"proxy", $_COOKIE["user"], "playlist", $playlists[2])) > 0)
															echo "<option value='" . $playlists[2] . "'" . $checked . ">" . $playlists[0] . "</option>";
													}
												}
											?>
											</select>
                                            &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                            <?php echo $lang_array["proxy_account_list_text7"] ?>：<?php echo $all_account; ?> &nbsp;&nbsp;&nbsp;<?php echo $lang_array["proxy_account_list_text8"] ?>:<?php echo $used_account; ?> &nbsp;&nbsp;&nbsp;<?php echo $lang_array["proxy_account_list_text9"] ?>:<?php echo $no_used_account; ?>
                                        </div> 
                                        <br/>   

                                        <label class="control-label"><?php echo $lang_array["account_list_text44"] ?></label>  
                                       	<div class="controls" style="text-align:left;">
                                        	<input style="width:15px;height:28px;" type="radio" name="logonum_radio" value="0" id="logonum0_radio" <?php if($logonum == null || strlen($logonum)<=0) echo "checked"?>  onChange="logonum(0)"/><?php echo $lang_array["account_list_text45"] ?>&nbsp;
                                       		<input style="width:15px;height:28px;" type="radio" name="logonum_radio" value="1" id="logonum1_radio" <?php if($logonum != null && strlen($logonum)>0) echo "checked"?> onChange="logonum(1)"/><?php echo $lang_array["account_list_text46"] ?>&nbsp;
                                            <?php echo $lang_array["account_list_text48"] ?>:<input class="input-mini focused" id="logonum_id" name="logonum_id" type="text" value="<?php if($logonum != null && strlen($logonum)>0) echo "****"?>">
                                            <button type='button' class='btn btn-primary' onclick='save_logonum()'><?php echo $lang_array['sure_text1'] ?> </button>
                                        </div>
                                        <br/>
                                    </div>
                                    
                                    <table cellpadding="0" cellspacing="0" border="0" class="table table-striped table-bordered" style="table-layout:fixed;">
                                        <thead>
                                            <tr>
            									<th width="3%" style="vertical-align:middle; text-align:center;"><?php echo $lang_array["account_list_text1"] ?></th>
          										<th width="4%" style="vertical-align:middle; text-align:center;"><?php echo $lang_array["account_list_text2"] ?></th>
            									<th width="6%" style="vertical-align:middle; text-align:center;"><?php echo $lang_array["account_list_text3"] ?></th>
            									<th width="5%" style="vertical-align:middle; text-align:center;"><?php echo $lang_array["account_list_text4"] ?></th>
            									<th width="5%" style="vertical-align:middle; text-align:center;"><?php echo $lang_array["account_list_text5"] ?></th>
            									<th width="4%" style="vertical-align:middle; text-align:center;"><?php echo $lang_array["account_list_text6"] ?></th>
            									<th width="5%" style="vertical-align:middle; text-align:center;"><?php echo $lang_array["account_list_text7"] ?></th>
            									<th width="11%" style="vertical-align:middle; text-align:center;"><?php echo $lang_array["account_list_text8"] ?></th>
            									<th width="3%" style="vertical-align:middle; text-align:center;"><?php echo $lang_array["account_list_text9"] ?></th>
            									<th width="3%" style="vertical-align:middle; text-align:center;"><?php echo $lang_array["account_list_text10"] ?></th>
            									<th width="10%" style="vertical-align:middle; text-align:center;"><?php echo $lang_array["account_list_text11"] ?></th>
            									<th width="10%" style="vertical-align:middle; text-align:center;"><?php echo $lang_array["account_list_text12"] ?></th>
            									<th width="7%" style="vertical-align:middle; text-align:center;"><?php echo $lang_array["account_list_text13"] ?></th>
                                            </tr>
                                        </thead>
                                        
<?php    
		$mytable = "playlist_type_table";
		$sql->create_table($mydb, $mytable, "name text, space longtext, id text, playlist longtext");	
   	 	
		echo "<tbody>";
		foreach($namess as $names) {
			echo "<tr>";
			if($export == 1 && $names[4] == 0)
				echo "<td style='vertical-align:middle; text-align:center;word-wrap:break-word;'>"."<input name='account_checkbox' type='checkbox' value='" . $names[0] . "' checked />"."</td>";
			else
			{
				echo "<td style='vertical-align:middle; text-align:center;word-wrap:break-word;'>"."<input name='account_checkbox' type='checkbox' value='" . $names[0] . "' />"."</td>";
			}
			echo "<td style='vertical-align:middle; text-align:center;word-wrap:break-word;'>" . $names[0] . "</td>";
			if(strcmp($names[1],"all") == 0)
				echo "<td style='vertical-align:middle; text-align:center;word-wrap:break-word;'>" . $lang_array["proxy_account_list_text10"] . "</td>";
			else if(strcmp($names[1],"auto") == 0)
				echo "<td style='vertical-align:middle; text-align:center;word-wrap:break-word;'>" . $lang_array["proxy_account_list_text11"] . "</td>";
			else
			{
				$playlist_name = $sql->query_data($mydb, $mytable,"id",$names[1],"name");
				echo "<td style='vertical-align:middle; text-align:center;word-wrap:break-word;'>" . $playlist_name . "</td>";
			}
			echo "<td style='vertical-align:middle; text-align:center;word-wrap:break-word;'>" . $names[2] . "</td>";
			echo "<td style='vertical-align:middle; text-align:center;word-wrap:break-word;'>" . $names[3] . "</td>";
			if($names[9] == "no")
				echo "<td bgcolor=#FF0000 style='vertical-align:middle; text-align:center;word-wrap:break-word;'>" . $lang_array["no_text1"] . "</td>";
			else
				echo "<td style='vertical-align:middle; text-align:center;word-wrap:break-word;'>" . $lang_array["yes_text1"] . "</td>";
			if($names[10] == 1)
				echo "<td style='vertical-align:middle; text-align:center;word-wrap:break-word;'>" . $lang_array["proxy_account_list_text12"] . "</td>";
			else if($names[10] == 2)
				echo "<td style='vertical-align:middle; text-align:center;word-wrap:break-word;'>" . $lang_array["proxy_account_list_text13"] . "</td>";
			else if($names[10] == 3)
				echo "<td style='vertical-align:middle; text-align:center;word-wrap:break-word;'>" . $lang_array["account_list_text50"] . "</td>";				
			else 
				echo "<td style='vertical-align:middle; text-align:center;word-wrap:break-word;'>" . $lang_array["proxy_account_list_text14"] . "</td>";
			echo "<td style='vertical-align:middle; text-align:center;word-wrap:break-word;'>" . $names[8] . "</td>";
			if($names[4] == 1)
				echo "<td bgcolor=#FF0000 style='vertical-align:middle; text-align:center;word-wrap:break-word;background-color:#F00;'>" . $lang_array["yes_text1"] . "</td>";
			else
				echo "<td bgcolor=#00FFFF style='vertical-align:middle; text-align:center;word-wrap:break-word;background-color:#00FFFF;'>" . $lang_array["no_text1"] . "</td>";
				
			if($names[5] == 1)
				echo "<td bgcolor=#FF0000 style='vertical-align:middle; text-align:center;word-wrap:break-word;background-color:#F00;'>" . $lang_array["yes_text1"] . "</td>";
			else
				echo "<td bgcolor=#00FFFF style='vertical-align:middle; text-align:center;word-wrap:break-word;background-color:#00FFFF;'>" . $lang_array["no_text1"] . "</td>";
			echo "<td style='vertical-align:middle; text-align:center;word-wrap:break-word;'>" . $names[6] . "</td>";
			echo "<td style='vertical-align:middle; text-align:center;word-wrap:break-word;'>" . $names[7] . "</td>";
			
			echo "<td style='vertical-align:middle; text-align:center;word-wrap:break-word;'><a href='#' onclick='edit_id(" . $names[0] . ")'>" . $lang_array["edit_text1"] . "</a></td>";
			echo "</tr>";
		}
		echo "</tbody>";	
		$sql->disconnect_database();		
?>                                     
          	</table>
            
            <input name="" class="btn btn-primary" type="button" value="<?php echo $lang_array["account_add_text16"] ?>" onclick="selectAll()"/>
            <input name="" class="btn btn-primary" type="button" value="<?php echo $lang_array["account_add_text17"] ?>" onclick="noAll()"/>
            <input name="" class="btn btn-primary" type="button" value="<?php echo $lang_array["account_add_text18"] ?>" onclick="selectExport()"/> 
            <input name="" class="btn btn-primary" type="button" value="<?php echo $lang_array["account_list_text22"] ?>" onclick="export_batch()"/>
            
			<div class="form-actions">
				<button type="button" class="btn btn-primary" onclick="go_pre()"><?php echo $lang_array["custom_list_text17"] ?></button>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                <button type="button" class="btn btn-primary" onclick="go_page()"><?php echo $lang_array["custom_list_text19"] ?></button>&nbsp;<input class="input-mini focused" id="pageid" name="pageid" type="text" value="<?php echo ($page+1) ?>" ><?php echo $lang_array["custom_list_text20"] ?>/<?php echo $pages ?>&nbsp;<?php echo $lang_array["custom_list_text20"] ?>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                <button type="button" class="btn btn-primary" onclick="go_back()"><?php echo $lang_array["custom_list_text18"] ?></button>
            </div>                                    
<?php			
		/*
		for($ii=0; $ii<$pages; $ii++)
		{
			$kk = $ii + 1;
			echo "<a href='custom_list.php?page=".$ii."'>[". $kk ."]</a>";
		}
		echo "<br/>";
		$page_show = $page + 1;
		echo "共" . $pages . "页" . "/" . "第" . $page_show . "页";
		*/
?>
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
		
		function isNumber(value) {
    		var patrn = /^(-)?\d+(\.\d+)?$/;
    		if (patrn.exec(value) == null || value == "") {
        		return false
    		} else {
        		return true
    		}
		} 

		function select_proxy(obj)
		{
			window.location.href = "proxy_account_list.php?proxy=" + obj.value;
		}
		
		function find_id2()
		{
			var name = document.getElementById("find_id").value;
			var url = "proxy_account_list.php?find=" + name;;
			window.location.href = url;	
		}
		
		function find_all()
		{
			window.location.href = "proxy_account_list.php?page=<?php echo $page ?>";
		}
		
		function select_playlist(obj)
		{
			window.location.href = "proxy_account_list.php?select_playlist=" + obj.value;
		}

		function export_batch()
		{
			var value = get_type_checkbox_value();
			if(value.length > 0)
			{
				window.location.href = "proxy_account_excel.php?export=" + get_type_checkbox_value() + "&page=" + <?php echo $page ?>;
			}
			else
			{
				alert("<?php echo $lang_array["proxy_account_list_text15"] ?>");	
			}
		}

		function del_batch()
		{
			var r=confirm("<?php echo $lang_array["proxy_account_list_text16"] ?>？");
			if(r==true)
  			{
				window.location.href = "account_del.php?del=" + get_type_checkbox_value() + "&page=" + <?php echo $page ?>;
			}
		}

		function del_id(id)
		{
			var r=confirm("<?php echo $lang_array["proxy_account_list_text17"] ?>:" + id + "？");
			if(r==true)
  			{
				window.location.href = "account_del.php?del=" + id + "&page=" + <?php echo $page ?>;
			}
		}

		function selectAll() //全选
		{
			//alert(0);
			var objs = document.getElementsByName('account_checkbox');
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
			var objs = document.getElementsByName('account_checkbox');
			var i;
			for(i = 0; i < objs.length; i++)
			{
				if(objs[i].type == "checkbox")
				{
					objs[i].checked = false;
				}
			}
		} 
		
		function edit_id(id)
		{
			window.location.href = "proxy_account_edit.php?edit=" + id + "&page=" + <?php echo $page ?>;
		}
		
		function selectExport()
		{
			window.location.href = "proxy_account_list.php?export=1&page=" + <?php echo $page ?>;
		}
		
		function get_type_checkbox_value()
		{
			var value = "";
			var value_array = new Array();
			var type_checkboxs = document.getElementsByName("account_checkbox");
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
		
		function go_page()
		{
			var pageid = document.getElementById("pageid").value;
<?php
			if(isset($_GET["select_playlist"]) && $_GET["select_playlist"] != "-1")
			{
				echo "var url = 'proxy_account_list.php?select_playlist=" . $_GET["select_playlist"] . "&page=' + (pageid-1);";
			}
			else
			{
				echo "var url = 'proxy_account_list.php?page=' + (pageid-1);";
			}
?>
			window.location.href = url;
		}
		
		function go_pre()
		{
<?php
			
			if($page-1 >= 0)
			{
				if(isset($_GET["select_playlist"]) && $_GET["select_playlist"] != "-1")
				{
					echo "var url = 'proxy_account_list.php?select_playlist=" . $_GET["select_playlist"] . "&page=".($page-1)."';";
				}
				else
				{
					echo "var url = 'proxy_account_list.php?page=".($page-1)."';";
				}
				echo "window.location.href = url;";
			}
?>
		}
		
		
		function go_back()
		{
<?php
			if($page+1 < $pages)
			{
				if(isset($_GET["select_playlist"]) && $_GET["select_playlist"] != "-1")
				{
					echo "var url = 'proxy_account_list.php?select_playlist=" . $_GET["select_playlist"] . "&page=".($page+1)."';";
				}
				else
				{
					echo "var url = 'proxy_account_list.php?page=".($page+1)."';";
				}
				echo "window.location.href = url;";
			}
?>			
		}
		
		function logonum(value)
		{
			if(value == 0)
				document.getElementById("logonum_id").value = "";
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
		
		function save_logonum()
		{
			var value = document.getElementById("logonum_id").value;
			var radio = GetRadioValue("logonum_radio");
			if(radio == "1" && value.length <= 0)
			{
				alert("<?php echo $lang_array["account_list_text49"]; ?>");
				return;
			}
			
			if(radio == "0")
			{
				window.location.href = "proxy_account_logonum_post.php";
			}
			else if(radio == "1" && value == "****" || value.length != 4 || !isNumber(value))
			{
				alert("<?php echo $lang_array["proxy_account_list_text20"] ?>");
			}
			else
			{
				window.location.href = "proxy_account_logonum_post.php?accountnum="+value+"&page=" + <?php echo $page ?>;
			}
		}
		
        </script>
    </body>
</html>