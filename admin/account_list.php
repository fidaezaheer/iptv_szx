<!DOCTYPE html>
<?php
	include_once "cn_lang.php";
	include_once "common.php";
	$sql = new DbSql();
	$sql->login();

	$sql->connect_database_default();
	$mydb = $sql->get_database();
	$sql->create_database($mydb);

	$mytable = "proxy_table";
	$sql->create_table($mydb, $mytable, "name text, password text, ptip text, edit int, watermark text, validity date, allow int, remark text, ccount int, panal int");
	$proxy_namess = $sql->fetch_datas($mydb, $mytable);


	$mytable = "version_table";
	$sql->create_table($mydb, $mytable, "name longtext, value longtext");
	$logonum = $sql->query_data($mydb, $mytable, "name", "accountnum", "value");		
	$logoselect = $sql->query_data($mydb, $mytable, "name", "accountselect", "value");	
	//echo "logoselect:" . $logoselect;
	
	$mytable = "account_table";
	$sql->create_table($mydb, $mytable, "id int, playlist text, days text, proxy text, export int, 
						used int, mac text, cpuid text, cdkey text, showtime text, 
						type text, member text, startime datetime, logonum text");
	
	//echo "find column:" . $sql->find_column($mydb, $mytable, "showtime");
	/*
	if($sql->find_column($mydb, $mytable, "showtime") == 0)
		$sql->add_column($mydb, $mytable,"showtime", "text");
	
	if($sql->find_column($mydb, $mytable, "type") == 0)
		$sql->add_column($mydb, $mytable,"type", "text");
	
	if($sql->find_column($mydb, $mytable, "member") == 0)
		$sql->add_column($mydb, $mytable,"member", "text");
			
	if($sql->find_column($mydb, $mytable, "startime") == 0)
		$sql->add_column($mydb, $mytable,"startime", "datetime");
	*/
		
	$pages = 0;
	$size = 100;
	$page = 0;
	$offset = 0;
	$proxy = "-1";
	
	if(isset($_GET["page"]))
	{
		$offset = $size*intval($_GET["page"]);
		$page = $_GET["page"];
	}
	
	if(isset($_GET["proxy"]))
	{
		$proxy = $_GET["proxy"];
	}
	
	$export = 0;
	if(isset($_GET["export"]))
	{
		$export = intval($_GET["export"]);
	}

	if(isset($_GET["findused"]) && $_GET["findused"] == "1")
	{
		$namess = $sql->fetch_datas_where($mydb, $mytable,"used", 1);
		$numrows = $sql->count_fetch_datas($mydb, $mytable);
	}
	else if(isset($_GET["find"]))
	{
		$namess = $sql->fetch_datas_limit_where_like($mydb, $mytable, $offset, $size, "cdkey", $_GET["find"]); 
		$numrows = count($sql->fetch_datas_where($mydb, $mytable, "cdkey", $_GET["find"]));
	}
	else
	{
		if(isset($_GET["proxy"]) && $proxy != "-1")
		{
			$namess = $sql->fetch_datas_limit_where_desc($mydb, $mytable, $offset, $size, "proxy", $proxy , "id");
			//$numrows = count($sql->fetch_datas($mydb, $mytable));
			//$numrows = count($sql->fetch_datas_where($mydb, $mytable, "proxy", $_GET["proxy"]));
			$numrows = $sql->count_fetch_datas_where($mydb, $mytable, "proxy", $_GET["proxy"]);
		}
		else
		{
			$namess = $sql->fetch_datas_limit_desc_2($mydb, $mytable, $offset, $size, "id", "startime");
			$numrows = $sql->count_fetch_datas($mydb, $mytable);
		}
	}
	
	$pages = intval($numrows/$size);
	if($numrows%$size)
	{
		$pages++;
	}
	
	if($proxy == -1)
	{
		$all_account = $sql->count_fetch_datas($mydb, $mytable);
		$used_account = $sql->count_fetch_datas_where($mydb, $mytable,"used", 1);
		$no_used_account = $sql->count_fetch_datas_where($mydb, $mytable,"used", 0);
	}
	else
	{
		$all_account = $sql->count_fetch_datas_where($mydb, $mytable, "proxy", $proxy);
		$used_account = $sql->count_fetch_datas_where_2($mydb, $mytable, "proxy", $proxy, "used", 1);
		$no_used_account = $sql->count_fetch_datas_where_2($mydb, $mytable, "proxy", $proxy, "used", 0);		
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
                                <div class="muted pull-left"><?php echo $lang_array["account_list_text14"] ?></div>
                            </div>
                            <div class="block-content collapse in">
                                <div class="span12">
                                	<div class="table-toolbar">
                                    	<div class="btn-group">
                                         	<a href="account_add.php"><button class="btn btn-success"><?php echo $lang_array["account_list_text15"] ?><i class="icon-plus icon-white"></i></button></a>
                                      	</div>
                                    </div>
                                    
									<form class="form-horizontal" name="authform" method="post" action="" enctype='multipart/form-data'>
                                    
                                    <div class="control-group" style="background-color:#CCC"> <br/>
                                        <label class="control-label"><?php echo $lang_array["account_list_text16"] ?></label>
                                        <div class="controls">
                                        <select id="proxy_select_id" name=""  style='width:135px;' onchange="select_proxy(this)">
										<?php
											$mytable = "proxy_table";
											$sql->create_table($mydb, $mytable, "name text, password text, ptip text, edit int, watermark text, validity date, allow int, remark text, ccount int, panal int");
											$proxyss = $sql->fetch_datas($mydb, $mytable);
											if($proxy == "-1")
											{
												echo "<option value='-1' selected='selected' style='width:120px;'>" . $lang_array["account_list_text23"] . "</option>";
											}
											else
											{
												echo "<option value='-1' style='width:120px;'>" . $lang_array["account_list_text23"] . "</option>";
											}
		
											if($proxy == "admin")
											{
												echo "<option value='admin' selected='selected' style='width:120px;'>" . $lang_array["account_list_text24"] . "</option>";
											}
											else
											{
												echo "<option value='admin' style='width:120px;'>" . $lang_array["account_list_text24"] . "</option>";
											}
		
											foreach($proxyss as $proxys) 
											{
												$checked = "";
												if($proxy == $proxys[0])
													$checked = "' selected='selected' ";	
												if(strlen($proxys[7]) > 0)	
													echo "<option value='" . $proxys[0] . "'" . $checked . " style='width:120px;'>" . $proxys[0] . "(" . $proxys[7] . ")" . "</option>";
												else
													echo "<option value='" . $proxys[0] . "'" . $checked . " style='width:120px;'>" . $proxys[0] . "</option>";
											}
										?>
    									</select>
                                        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                        <input class="btn btn-primary" name="" type="button" value="<?php echo $lang_array["account_list_text25"] ?>" onclick="del_batch()"/>
    									<input class="btn btn-primary" name="" type="button" value="<?php echo $lang_array["account_list_text26"] ?>" onclick="del_used_batch()"/>
    									<input class="btn btn-primary" name="" type="button" value="<?php echo $lang_array["account_list_text27"] ?>" onclick="export_batch()"/>
                                        
                                      	</div>    
                                        <br/> 
                                        
								    	<label class="control-label"><?php echo $lang_array["custom_list_text22"] ?></label>  
                                       	<div class="controls" style="text-align:left;">
                                        <input id="find_id" name="" type="text" size="32" />&nbsp;<input class="btn btn-primary" name="" type="button" value="<?php echo $lang_array["account_list_text28"] ?>" onclick="find_id2()"/>&nbsp;<input class="btn btn-primary"  name="" type="button" value="<?php echo $lang_array["account_list_text29"] ?>" onclick="find_all()"/>
                                        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                        <?php echo $lang_array["account_list_text30"] ?>：
    									<?php
											if($proxy == "-1") echo $lang_array["account_list_text32"];
											else if($proxy == "-1") echo $lang_array["account_list_text32"];
											else echo $proxy;
										?>
                                        &nbsp;&nbsp;&nbsp;
    									<?php echo $lang_array["account_list_text33"] ?>：<?php echo $all_account; ?> &nbsp;&nbsp;&nbsp;<a class="pointer" style="cursor:pointer;" onclick="find_used()"><?php echo $lang_array["account_list_text34"] ?>:<?php echo $used_account; ?> </a>&nbsp;&nbsp;&nbsp;<?php echo $lang_array["account_list_text35"] ?>:<?php echo $no_used_account; ?>
                                        </div>
                                        
                                        <br/> 
                                        <label class="control-label"><?php echo $lang_array["account_list_text44"] ?></label>  
                                       	<div class="controls" style="text-align:left;">
                                        	<input style="width:15px;height:28px;" type="radio" name="logonum_radio" value="0" id="logonum0_radio" <?php if($logonum == null || strlen($logonum)<=0) echo "checked"?>  onChange="logonum(0)"/><?php echo $lang_array["account_list_text45"] ?>&nbsp;
                                            
                                       		<input style="width:15px;height:28px;" type="radio" name="logonum_radio" value="1" id="logonum1_radio" <?php if($logonum != null && strlen($logonum) > 1 && ($logoselect == null || $logoselect == "")) echo "checked"?> onChange="logonum(1)"/><?php echo $lang_array["account_list_text46"] ?>&nbsp;
                                            <input style="width:15px;height:28px;" type="radio" name="logonum_radio" value="2" id="logonum1_radio" <?php if($logoselect != null && $logoselect == "2") echo "checked"?> onChange="logonum(2)"/><?php echo $lang_array["account_list_text52"] ?>&nbsp;
                                            <?php echo $lang_array["account_list_text48"] ?>:<input class="input-mini focused" id="logonum_id" name="logonum_id" type="text" value="<?php if($logonum != null && strlen($logonum)>0) echo "****"?>">
                                            <button type='button' class='btn btn-primary' onclick='save_logonum()'><?php echo $lang_array['sure_text1'] ?> </button>
                                        </div>
                                        <br/>                     
                                    </div>
                                    
                                     
                                    <table cellpadding="0" cellspacing="0" border="0" class="table table-striped table-bordered" style="table-layout:fixed;">
                                        <thead>
                                            <tr>
            									<th width="3%" style="vertical-align:middle; text-align:center;"><?php echo $lang_array["account_list_text1"] ?></th>
          										<th width="3%" style="vertical-align:middle; text-align:center;"><?php echo $lang_array["account_list_text2"] ?></th>
            									<th width="5%" style="vertical-align:middle; text-align:center;"><?php echo $lang_array["account_list_text3"] ?></th>
            									<th width="4%" style="vertical-align:middle; text-align:center;"><?php echo $lang_array["account_list_text4"] ?></th>
            									<th width="5%" style="vertical-align:middle; text-align:center;"><?php echo $lang_array["account_list_text5"] ?></th>
            									<th width="3%" style="vertical-align:middle; text-align:center;"><?php echo $lang_array["account_list_text6"] ?></th>
            									<th width="5%" style="vertical-align:middle; text-align:center;"><?php echo $lang_array["account_list_text7"] ?></th>
            									<th width="10%" style="vertical-align:middle; text-align:center;"><?php echo $lang_array["account_list_text8"] ?></th>
            									<th width="3%" style="vertical-align:middle; text-align:center;"><?php echo $lang_array["account_list_text9"] ?></th>
            									<th width="3%" style="vertical-align:middle; text-align:center;"><?php echo $lang_array["account_list_text10"] ?></th>
            									<th width="9%" style="vertical-align:middle; text-align:center;"><?php echo $lang_array["account_list_text11"] ?></th>
            									<th width="10%" style="vertical-align:middle; text-align:center;"><?php echo $lang_array["account_list_text12"] ?></th>
                                                <th width="7%" style="vertical-align:middle; text-align:center;"><?php echo $lang_array["account_list_text36"] ?></th>
            									<th width="5%" style="vertical-align:middle; text-align:center;"><?php echo $lang_array["account_list_text38"] ?></th>
                                                <th width="3%" style="vertical-align:middle; text-align:center;"><?php echo $lang_array["account_list_text48"] ?></th>
                                                <th width="7%" style="vertical-align:middle; text-align:center;"><?php echo $lang_array["account_list_text13"] ?></th>
                                                
                                            </tr>
                                        </thead>
                                        
<?php
			echo "<tbody>";
			foreach($namess as $names) {
				echo "<tr class='odd gradeA'>";
				if($export == 1 && $names[4] == 0)
					echo "<td style='vertical-align:middle; text-align:center;word-wrap:break-word;'>"."<input name='account_checkbox' type='checkbox' value='" . $names[0] . "' checked />"."</td>";
				else
				{
					echo "<td style='vertical-align:middle; text-align:center;word-wrap:break-word;'>"."<input name='account_checkbox' type='checkbox' value='" . $names[0] . "' />"."</td>";
				}
				echo "<td style='vertical-align:middle; text-align:center;word-wrap:break-word;'>" . $names[0] . "</td>";
				if(strcmp($names[1],"all") == 0)
					echo "<td style='vertical-align:middle; text-align:center;word-wrap:break-word;'>" .  $lang_array["account_list_text39"] . "</td>";
				else if(strcmp($names[1],"auto") == 0)
					echo "<td style='vertical-align:middle; text-align:center;word-wrap:break-word;'>" . $lang_array["account_list_text40"] . "</td>";
				else
				{
					$mytable = "playlist_type_table";
					$sql->create_table($mydb, $mytable, "name text, space longtext, id text, playlist longtext");	
					$playlist_name = $sql->query_data($mydb, $mytable,"id",$names[1],"name");
					echo "<td style='vertical-align:middle; text-align:center;word-wrap:break-word;'>" . $playlist_name . "</td>";
				}
				echo "<td style='vertical-align:middle; text-align:center;word-wrap:break-word;'>" . $names[2] . "</td>";
				$find_proxy_name = "";
				foreach($proxy_namess as $proxy_names)
				{
					if(strlen($proxy_names[7]) > 0 && strcmp($proxy_names[0],$names[3]) == 0)
					{
						$find_proxy_name = $proxy_names[7];
						break;
					}
				}
				if($names[3] == "admin")
				{
					echo "<td style='vertical-align:middle; text-align:center;word-wrap:break-word;'>" . $lang_array["account_list_text51"] . "</td>";
				}
				else if(strlen($find_proxy_name) > 0)
				{
					echo "<td style='vertical-align:middle; text-align:center;word-wrap:break-word;'>" . $find_proxy_name . "</td>";
				}
				else
				{	
					echo "<td style='vertical-align:middle; text-align:center;word-wrap:break-word;'>" . $names[3] . "</td>";
				}
				//echo "<td style='vertical-align:middle; text-align:center;word-wrap:break-word;'>" . $proxy_names[7] . "</td>";
				if($names[9] == "no")
					echo "<td style='vertical-align:middle; text-align:center;word-wrap:break-word;' bgcolor=#FF0000>" . $lang_array["no_text1"] . "</td>";
				else
					echo "<td style='vertical-align:middle; text-align:center;word-wrap:break-word;'>" . $lang_array["yes_text1"] . "</td>";
				if($names[10] == 1)
					echo "<td style='vertical-align:middle; text-align:center;word-wrap:break-word;'>" . $lang_array["account_list_text41"] . "</td>";
				else if($names[10] == 2)
					echo "<td style='vertical-align:middle; text-align:center;word-wrap:break-word;'>" . $lang_array["account_list_text42"] . "</td>";
				else if($names[10] == 3)
					echo "<td style='vertical-align:middle; text-align:center;word-wrap:break-word;'>" . $lang_array["account_list_text50"] . "</td>";
				else 
					echo "<td style='vertical-align:middle; text-align:center;word-wrap:break-word;'>" . $lang_array["account_list_text43"] . "</td>";
					
				echo "<td style='vertical-align:middle; text-align:center;word-wrap:break-word;'>" . $names[8] . "</td>";
				if($names[4] == 1)
					echo "<td style='vertical-align:middle; text-align:center;word-wrap:break-word; background-color:#F00;'>" . $lang_array["yes_text1"] . "</td>";
				else
					echo "<td style='vertical-align:middle; text-align:center;word-wrap:break-word; background-color:#00FFFF;'>" . $lang_array["no_text1"] . "</td>";
				
				if($names[5] == 1)
					echo "<td style='vertical-align:middle; text-align:center;word-wrap:break-word; background-color:#F00;'>" . $lang_array["yes_text1"] . "</td>";
				else
					echo "<td style='vertical-align:middle; text-align:center;word-wrap:break-word; background-color:#00FFFF;'>" . $lang_array["no_text1"] . "</td>";
				echo "<td style='vertical-align:middle; text-align:center;word-wrap:break-word;'>" . $names[6] . "</td>";
				echo "<td style='vertical-align:middle; text-align:center;word-wrap:break-word;'>" . $names[7] . "</td>";
				echo "<td style='vertical-align:middle; text-align:center;word-wrap:break-word;'>" . $names[11] . "</td>";
				if($names[5] == 1)
					echo "<td style='vertical-align:middle; text-align:center;word-wrap:break-word;'>" . $names[12] . "</td>";
				else
					echo "<td style='vertical-align:middle; text-align:center;word-wrap:break-word;'></td>";
					
				$v = "";
				if(strlen($names[13]) > 0) $v = "****";
				echo "<td style='vertical-align:middle; text-align:center;word-wrap:break-word;'>" . $v . "</td>";
				
				echo "<td style='vertical-align:middle; text-align:center;word-wrap:break-word;'><a href='#' onclick='edit_id(" . $names[0] . ")'>" . $lang_array["edit_text2"] . "</a>&nbsp;&nbsp;&nbsp;&nbsp;<a href='#' onclick='del_id(" . $names[0] . ")'>" . $lang_array["del_text1"] . "</a></td>";
				echo "</tr>";
			}
			echo "</tbody>";
			
			$sql->disconnect_database();
?>                                       
          	</table>
            
            <?php 
				if(!isset($_GET["findused"]) ||  $_GET["findused"] != "1")
				{
            		echo "<input name=\"\" class=\"btn btn-primary\" type=\"button\" value=\"" . $lang_array["account_add_text16"] . "\" onclick=\"selectAll()\"/>";
            		echo "<input name=\"\" class=\"btn btn-primary\" type=\"button\" value=\"" . $lang_array["account_add_text17"] . "\" onclick=\"noAll()\"/>";
            		echo "<input name=\"\" class=\"btn btn-primary\" type=\"button\" value=\"" . $lang_array["account_add_text18"] . "\" onclick=\"selectExport()\"/>";
                                   
					echo "<div class=\"form-actions\">";
					echo "<button type=\"button\" class=\"btn btn-primary\" onclick=\"go_pre()\">".$lang_array["custom_list_text17"]."</button>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
                	echo "<button type=\"button\" class=\"btn btn-primary\" onclick=\"go_page()\">".$lang_array["custom_list_text19"]."</button>&nbsp;<input class=\"input-mini focused\" id=\"pageid\" name=\"pageid\" type=\"text\" value=\"".($page+1)."\" >" . $lang_array["custom_list_text20"] . "/" . $pages . "&nbsp;" . $lang_array["custom_list_text20"] ."&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
                	echo "<button type=\"button\" class=\"btn btn-primary\" onclick=\"go_back()\">" . $lang_array["custom_list_text18"]. "</button>";
            		echo "</div>";                                    
            	}
            ?>
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
		
		function select_proxy(obj)
		{
			window.location.href = "account_list.php?proxy=" + obj.value;
		}
		
		function find_id2()
		{
			var name = document.getElementById("find_id").value;
			var url = "account_list.php?find=" + name;;
			window.location.href = url;	
		}
		
		function find_all()
		{
			window.location.href = "account_list.php?page=<?php echo $page ?>";
		}
		
		function export_batch()
		{
			var value = get_type_checkbox_value();
			if(value.length > 0)
			{
				window.location.href = "account_excel.php?export=" + get_type_checkbox_value() + "&page=" + <?php echo $page ?>;
			}
			else
			{
				alert("请选中要导出的授权码");	
			}
		}

		function del_batch()
		{
			var r=confirm("是否需要批量删除？");
			if(r==true)
  			{
				window.location.href = "account_del.php?del=" + get_type_checkbox_value() + "&page=" + <?php echo $page ?>;
			}
		}

		function del_id(id)
		{
			var r=confirm("是否删除:" + id + "？");
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
		
		function selectExport()
		{
			window.location.href = "account_list.php?export=1&page=" + <?php echo $page ?>;
	
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
		
		
		function del_used_batch()
		{
			var r=confirm("<?php echo $lang_array["account_list_text37"] ?>?");
			if(r==true)
  			{
				window.open("account_used_list.php?page=<?php echo $page ?>", "newwindow", "height=280, width=800, toolbar =no, menubar=no, scrollbars=no, resizable=no, location=no, status=no");
				//window.location.href = "account_used_del.php?page=<?php echo $page ?>";
			}
		}

		function go_page()
		{
			var pageid = document.getElementById("pageid").value;
			//var url = "account_list.php?page=" + (pageid-1);
<?php
			if(isset($_GET["proxy"]) && $proxy != "-1")
			{
				echo "var url = 'account_list.php?proxy=" . $proxy . "&page=' + (pageid-1);";
			}
			else
			{
				echo "var url = 'account_list.php?page=' + (pageid-1);";
			}
?>
			window.location.href = url;
		}
		
		function go_pre()
		{
<?php
			if($page-1 >= 0)
			{
				if(isset($_GET["proxy"]) && $proxy != "-1")
				{
					echo "var url = 'account_list.php?proxy=" . $proxy . "&page=".($page-1)."';";
				}
				else
				{
					echo "var url = 'account_list.php?page=".($page-1)."';";
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
				if(isset($_GET["proxy"]) && $proxy != "-1")
				{
					echo "var url = 'account_list.php?proxy=" . $proxy . "&page=".($page+1)."';";
				}
				else
				{
					echo "var url = 'account_list.php?page=".($page+1)."';";
				}
				echo "window.location.href = url;";
			}
?>			
		}
		
		function edit_id(id)
		{
			window.location.href = "account_edit.php?edit=" + id + "&page=" + <?php echo $page ?>;
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

		function isNumber(value) {
    		var patrn = /^(-)?\d+(\.\d+)?$/;
    		if (patrn.exec(value) == null || value == "") {
        		return false
    		} else {
        		return true
    		}
		} 
		
		function save_logonum()
		{
			var value = document.getElementById("logonum_id").value;
			var radio = GetRadioValue("logonum_radio");
			if((radio == "1"||radio == "2") && value.length <= 0)
			{
				alert("<?php echo $lang_array["account_list_text49"]; ?>");
				return;
			}
			
			if(radio == "0")
			{
				window.location.href = "account_logonum_post.php";
			}
			else if((radio == "1"||radio == "2") && value == "****" || value.length != 4 || !isNumber(value))
			{
				alert("<?php echo $lang_array["proxy_account_list_text20"] ?>");
			}
			else
			{
				if(radio == "1")
					window.location.href = "account_logonum_post.php?accountnum="+value;
				else if(radio == "2")
					window.location.href = "account_logonum_post.php?accountnum="+value+"&sselect="+radio;
			}
		}
		
		function find_used()
		{
			window.location.href = "account_list.php?findused=1";
			
		}
        </script>
    </body>
</html>