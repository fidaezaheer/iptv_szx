<?PHP
if(extension_loaded('zlib') && strstr($_SERVER['HTTP_ACCEPT_ENCODING'],'gzip'))
{
	ob_end_clean();
    ob_start('ob_gzhandler');
}
?> 

<!DOCTYPE html>
<?php
	include_once "cn_lang.php";
	include_once "common.php";
	include_once "gemini.php";
	$sql = new DbSql();
	$sql->login();

	$liveurls = array();
	
	$sql->connect_database_default();
	$mydb = $sql->get_database();
	$sql->create_database($mydb);
	
	if(isset($_POST["type"]) && strlen($_POST["type"]) > 2)
	{
		$playlisttypess = explode("$",$_POST["type"]);
		
		for($ii=0; $ii<count($playlisttypess); $ii++)
		{
			$playlisttypes = explode("#",$playlisttypess[$ii]);
			if(count($playlisttypes) >= 2)
			{
				$mytable = "introduction_table_tmp";
				$sql->create_table($mydb, $mytable, "type text, id text");
				$type_namess = $sql->fetch_datas_where($mydb, $mytable, "id", $playlisttypes[0]);	
				if($type_namess == null)
				{
					$sql->insert_data($mydb, $mytable, "type, id", $playlisttypes[1].", ".$playlisttypes[0]);
				}
				else
				{
					$sql->update_data_2($mydb, $mytable, "id", $playlisttypes[0], "type", $playlisttypes[1]);
				}
			}
		}
	
	}
	
	$mytable = "live_type_table";
	$sql->create_table($mydb, $mytable, "name longtext, id longtext");
	$type_namess = $sql->fetch_datas($mydb, $mytable);

	$size = 100;
	$offset = 0;
	$page = 0;
	
	$dom;
	
	if(isset($_GET["page"]))
	{
		$offset = $size*intval($_GET["page"]);
		$page = intval($_GET["page"]);
	}
	else if(isset($_POST["page"]))
	{
		$offset = $size*intval($_POST["page"]);
		$page = intval($_POST["page"]);	
	}
	
	$playlist_file = "";
	$playlists=array();
	
	if(isset($_GET["playlist"]))
	{
		$playlist_file = $_GET["playlist"];
	}
	else if(isset($_POST["playlist"]))
	{
		$playlist_file = $_POST["playlist"];	
	}
	
	if(strlen($playlist_file) > 0)
	{
		
		if((get_extension($playlist_file) == "xml") && file_exists('backup/' . $playlist_file))
		{
			$filename = 'backup/' . $playlist_file;  
			$dom = new DOMDocument('1.0', 'UTF-8');  
	
			if(!file_exists($filename))
				return 0;

			$dom->load($filename);  
			$liveurls = $dom->getElementsByTagName("liveurl"); 
		}
		else if(file_exists('backup/' . $playlist_file))
		{
			$handle = fopen('backup/' . $playlist_file , 'r');
    		while(!feof($handle))
			{
				{
        			$l = fgets($handle);
					if(strlen($l) > 16)
						array_push($playlists,$l);
				}
    		}
    		fclose($handle);
		}
	}	
	
	$numrows = count($playlists);
	$pages = 0;
	$pages = intval($numrows/$size);
	if($numrows%$size)
	{
		$pages++;
	}
	
	$type_array = array();
	foreach($type_namess as $type_names)
	{
		$type_array[strval($type_names[1])] = $type_names[0];
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
                                <div class="muted pull-left"><?php echo $lang_array["batch_introduction_list_text7"] ?></div>
                            </div>
                            <div class="block-content collapse in">
                                <div class="span12">
									<form class="form-horizontal" method="post" action="batch_introduction_upload.php" enctype='multipart/form-data'>
                                    
                                    <div class="control-group" style="background-color:#CCC"> 
                                        <br/>
										<label class="control-label"><?php echo $lang_array["batch_introduction_list_text8"] ?>：</label>
                                        <div class="controls">
                                        	<input class="input-file uniform_on" type="file" name="file" id="file" /><input class="btn btn-primary" type="submit" name="submit" value="<?php echo $lang_array["batch_introduction_list_text8"] ?>" /> 
                                      	</div>    
                                      	<br/>                    
                                    </div>
                                    
                                     
                                    <table cellpadding="0" cellspacing="0" border="0" class="table table-striped table-bordered" style="table-layout:fixed;">
                                        <thead>
                                            <tr>
            									<th style="vertical-align:middle; text-align:center;" width="5%"><?php echo $lang_array["batch_introduction_list_text1"] ?></th>
            									<th style="vertical-align:middle; text-align:center;" width="5%"><?php echo $lang_array["batch_introduction_list_text2"] ?></th>
            									<th style="vertical-align:middle; text-align:center;" width="10%"><?php echo $lang_array["batch_introduction_list_text3"] ?></th>
            									<th style="vertical-align:middle; text-align:center;" width="45%"><?php echo $lang_array["batch_introduction_list_text4"] ?></th>
												<th style="vertical-align:middle; text-align:center;" width="15%"><?php echo $lang_array["batch_introduction_list_text5"] ?></th>
            									<th style="vertical-align:middle; text-align:center;" width="10%"><?php echo $lang_array["batch_introduction_list_text6"] ?></th>
                                            </tr>
                                        </thead>
                                        
<?php
			echo "<tbody>";
			
			
		if(get_extension($playlist_file) == "xml")
		{
			$ii = 0;
			foreach($liveurls as $liveurl){ 
				
				if($ii%2==0)
					echo "<tr bgcolor='#CCCCCC'>";
				else
					echo "<tr bgcolor='#FFFFFF'>";
						
				echo "<td style='vertical-align:middle; text-align:center;'>"."<input name='introduction_checkbox' type='checkbox' value='" . $ii . "' />"."</td>";
				echo "<td style='vertical-align:middle; text-align:center;'>" . ($ii+1) . "</td>";
				echo "<td style='vertical-align:middle; text-align:center;'>" . urldecode($liveurl->getElementsByTagName("name")->item(0)->nodeValue) . "</td>";
				echo "<td style='vertical-align:middle; text-align:center;'>" . "**************" . "</td>";
				echo "<td style='vertical-align:middle; text-align:center;'>" . "**************" . "</td>";
				echo "<td style='vertical-align:middle; text-align:center;'>" . "**************" . "</td>";
				$ii++;
				
				echo "</tr>";
			}
		}
		else
		{
			$mytable = "introduction_table_tmp";
			$sql->create_table($mydb, $mytable, "type text, id text");
				
         	for($ii=0; $ii<count($playlists); $ii++)
			{ 
				if($ii >= $page*$size && $ii < ($page+1)*$size)
				{
				$arr = explode(",",$playlists[$ii]);
				if(count($arr) >= 2)
				{
					if($ii%2==0)
						echo "<tr bgcolor='#CCCCCC'>";
					else
						echo "<tr bgcolor='#FFFFFF'>";
					echo "<td style='vertical-align:middle; text-align:center;'>"."<input name='introduction_checkbox' type='checkbox' value='" . $ii . "' />"."</td>";
					echo "<td style='vertical-align:middle; text-align:center;'>" . ($ii+1) . "</td>";
					echo "<td style='vertical-align:middle; text-align:center;'>" . trim($arr[0]) . "</td>";
					$value = "";
					$password = "";
					$arrs = explode("#",$arr[1]);
					if(count($arrs) > 1)
					{
						$links = explode("&link=",$arrs[0]);
						if(count($links) >=2)
						{
							$value = "共" . count($arrs) . " 路：";	
							$value = $value . trim($links[0]) . "......";
							$password = $links[1];
						}
						else
						{
							$value = "共" . count($arrs) . " 路：";	
							$value = $value . trim($arrs[0]) . "......";
						}
					}
					else
					{
						$links = explode("&link=",$arrs[0]);
						if(count($links) >=2)
						{
							$value = "共" . count($arrs) . " 路：";	
							$value = $value . trim($links[0]) . "......";
							$password = $links[1];
						}
						else
						{
							$value = trim($arr[1]);
						}
					}
					echo "<td style='vertical-align:middle; text-align:center;'>" . trim($value) . "</td>";
					echo "<td style='vertical-align:middle; text-align:center;'>" . trim($password) . "</td>";
					$typess = $sql->query_data($mydb, $mytable,"id",$ii,"type");
					$types = explode("|",$typess);
					//foreach($types as $type)
					$types_all = "";
					for($jj=0; $jj<count($types); $jj++)
					{
						if(strlen($types[$jj]) > 2)
						{
							$types_all = $types_all . $type_array[strval($types[$jj])];
							if($jj < count($types)-1)
								$types_all = $types_all . "|";
						}
					}
					echo "<td style='vertical-align:middle; text-align:center;'><label id=\"td_introduction_" . $ii . "\">" . $types_all . "</label></td>";
					echo "</tr>";
				}
				}
			}
		}
		echo "</tbody>";
			
		$sql->disconnect_database();
?>                                       
          	</table>
            <input name="" type="button" class="btn btn-primary" value="<?php echo $lang_array["batch_introduction_list_text9"] ?>" onclick="selectAll()"/><input name="" type="button" class="btn btn-primary" value="<?php echo $lang_array["batch_introduction_list_text10"] ?>" onclick="noAll()"/>
                                    
			<div class="form-actions">
				<button type="button" class="btn btn-primary" onclick="go_pre()"><?php echo $lang_array["custom_list_text17"] ?></button>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                <button type="button" class="btn btn-primary" onclick="go_page()"><?php echo $lang_array["custom_list_text19"] ?></button>&nbsp;<input class="input-mini focused" id="pageid" name="pageid" type="text" value="<?php echo ($page+1) ?>" ><?php echo $lang_array["custom_list_text20"] ?>/<?php echo $pages ?>&nbsp;<?php echo $lang_array["custom_list_text20"] ?>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                <button type="button" class="btn btn-primary" onclick="go_back()"><?php echo $lang_array["custom_list_text18"] ?></button>
            </div>           
            
                                     
			<div class="control-group" style="background-color:#CCC">
            	<br/>  
<?php         
			if(get_extension($playlist_file) != "xml")
			{ 
					   
  				echo "<label class='control-label'>" . $lang_array["batch_introduction_list_text13"]. "</label>";
     			echo "<div class='controls'>";  

				$index = 1;							
     			foreach($type_namess as $type_names) {
					echo "<input class='uniform_on' name='type_checkbox' type='checkbox' value='" . $type_names[1] . "' > " . $type_names[0] . "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;" . "</input>";
					if($index%5==0)
						echo "<br/>";	
					$index++;
	 			}
			
				echo "<input class='btn btn-primary' name='' type='button' value='" . $lang_array["batch_introduction_list_text11"] . "' onclick='get_introduction_checkbox_value()'/>";	
				echo "</div>";
                echo "<br/>";
			}
?>                
                <label class="control-label"><?php echo $lang_array["batch_introduction_list_text14"] ?></label>
                <div class="controls">
                	<input type="radio" id="itype" name="itype" style="width:15px;height:28px;"/><?php echo $lang_array["batch_introduction_list_text15"] ?>&nbsp;&nbsp;&nbsp;&nbsp;<input type="radio" id="itype" name="itype" style="width:15px;height:28px;" checked/><?php echo $lang_array["batch_introduction_list_text16"] ?>
                </div>
                <br/>
  			</div>
            
            <div class="form-actions">
            	<?php
					if(get_extension($playlist_file) == "xml")
					{
						echo "<input name='' class='btn btn-primary' type='button' value='" . $lang_array['batch_introduction_list_text17'] . "' onclick='save_xml()'/>";
					}
					else
					{
						echo "<input name='' class='btn btn-primary' type='button' value='" . $lang_array['batch_introduction_list_text17'] . "' onclick='save()'/>";
					}
					
				?>
                <input name="" class="btn btn-primary" type="button" value="<?php echo $lang_array["back_text1"] ?>" onclick="live_back()"/>
            </div> 
                                    
									</form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

         </div>    
         
		<FORM name="authform_type" action="batch_introduction_list.php" method="post">  
    		<input name="playlist" id="playlist" type="hidden" value=""/>
    		<input name="page" id="page" type="hidden" value=""/>
    		<input name="type" id="type" type="hidden" value=""/>
		</Form>
        
        <?php
		if(get_extension($playlist_file) == "xml")
		{
        	echo "<form name='authform' method='post' action='batch_introduction_xml_post.php' enctype='multipart/form-data' >";
		}
		else
		{
			echo "<form name='authform' method='post' action='batch_introduction_post.php' enctype='multipart/form-data' >";
		}
		?>
        	<input name="playlistfile" id="playlistfile" type="hidden" value=""/>
        	<input name="playlisttype" id="playlisttype" type="hidden" value=""/>
        	<input name="playlistitype" id="playlistitype" type="hidden" value=""/>
        	<input name="type" id="type" type="hidden" value=""/>
     	</form>
     
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
		
		var type_id_array = new Array();
		var type_name_array = new Array();

		var introduction_type_array = new Array(5000);
		for(var i=0; i<5000; i++)
		{
			introduction_type_array[i] = "";	
		}

		<?php
			$type_array = array();
			foreach($type_namess as $type_names)
			{
				$type_array[strval($type_names[1])] = $type_names[0];
			}
		?>
		
		function get_type_checkbox_value()
		{
			var value = "";
			var checkboxs = document.getElementsByName("type_checkbox");
			for(var i = 0; i < checkboxs.length; i++)
			{
				if(checkboxs[i].type == "checkbox" && checkboxs[i].checked)
				{
					value = value + checkboxs[i].value;
					if(i < checkboxs.length - 1)
						value = value + "|";
				}
			}
	
			return value;
		}

		function next_page(playlist_file,kk)
		{
			var introduction_type_array_tmp = new Array(5000);
			for(var i=0; i<5000; i++)
			{
				introduction_type_array_tmp[i] = "";	
			}
	
			var checkboxs = document.getElementsByName("introduction_checkbox");
			for(var i = 0; i < checkboxs.length; i++)
			{
				if(checkboxs[i].type == "checkbox" && checkboxs[i].checked)
				{
					introduction_type_array_tmp[i] = checkboxs[i].value + "#" + get_type_checkbox_value();
				}
			}
	
			var value = "";
			var len = checkboxs.length;
	
			for(var ii=0; ii<len; ii++)
			{
				if(introduction_type_array_tmp[ii].length > 3)
				{
					value = value + introduction_type_array_tmp[ii];
					if(ii < introduction_type_array_tmp.length - 1)
						value = value + "$";
				}
			}
	
			//window.location.href = "batch_introduction_list.php?playlist="+playlist_file+"&page="+kk+"&type="+value;
			
			document.authform_type.playlist.value = playlist_file;
			document.authform_type.page.value = kk;
			document.authform_type.type.value = value;
			document.authform_type.submit();
		}

		function selectAll() //全选
		{
			//alert(1);
			var objs = document.getElementsByName('introduction_checkbox');
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
			var objs = document.getElementsByName('introduction_checkbox');
			var i;
			for(i = 0; i < objs.length; i++)
			{
				if(objs[i].type == "checkbox")
				{
					objs[i].checked = false;
				}
			}
		} 
		
		
		function get_introduction_checkbox_value()
		{
	
			var introduction_type_array_tmp = new Array(5000);
			for(var i=0; i<5000; i++)
			{
				introduction_type_array_tmp[i] = "";	
			}
	
			var checkboxs = document.getElementsByName("introduction_checkbox");
			for(var i = 0; i < checkboxs.length; i++)
			{
				if(checkboxs[i].type == "checkbox" && checkboxs[i].checked)
				{
					introduction_type_array_tmp[i] = checkboxs[i].value + "#" + get_type_checkbox_value();
				}
			}
	
			var value = "";
			var len = checkboxs.length;
	
			for(var ii=0; ii<len; ii++)
			{
				if(introduction_type_array_tmp[ii].length > 3)
				{
					value = value + introduction_type_array_tmp[ii];
					if(ii < introduction_type_array_tmp.length - 1)
						value = value + "$";
				}
			}
	
			var playlist_file = "";
<?php
			if(isset($_GET["playlist"]))
			{
				echo "playlist_file = '" . $_GET["playlist"] . "';";
			}
			else if(isset($_POST["playlist"]))
			{
				echo "playlist_file = '" . $_POST["playlist"] . "';";
			}
?>
	
			var page = 0;
<?php
	
			if(isset($_GET["page"]))
			{
				echo "page = '" . $_GET["page"] . "';";
			}
			else if(isset($_POST["page"]))
			{
				echo "page = '" . $_POST["page"] . "';";
			}
?>
			document.authform_type.playlist.value = playlist_file;
			document.authform_type.page.value = page;
			document.authform_type.type.value = value;
			document.authform_type.submit();	
		}

		function go_page()
		{
			var pageid = document.getElementById("pageid").value;
			next_page("<?php echo $playlist_file ?>",(pageid-1));
		}
		
		function go_pre()
		{
<?php
			if($page-1 >= 0)
			{
				echo "next_page('" . $playlist_file . "'," . ($page-1) .  ");";
			}
?>
		}

		function go_back()
		{
<?php
			if($page+1 < $pages)
			{
				echo "next_page('" . $playlist_file . "'," . ($page+1) .  ");";
			}
?>			
		}

		function save_xml()
		{
			var playlist_file = "";
<?php
			if(isset($_GET["playlist"]))
			{
				echo "playlist_file = '" . $_GET["playlist"] . "';";
			}
			else if(isset($_POST["playlist"]))
			{
				echo "playlist_file = '" . $_POST["playlist"] . "';";
			}
?>
			var check_id = 0;
			var chkObjs = document.getElementsByName("itype");
			for(var i=0;i<chkObjs.length;i++){
				if(chkObjs[i].checked){
					check_id = i;
					break;
				}
			}
				
			document.authform.playlistfile.value = playlist_file;
			document.authform.playlistitype.value = check_id;
			document.authform.submit();
		}
		
		function save()
		{
			var playlist_file = "";
<?php
			if(isset($_GET["playlist"]))
			{
				echo "playlist_file = '" . $_GET["playlist"] . "';";
			}
			else if(isset($_POST["playlist"]))
			{
				echo "playlist_file = '" . $_POST["playlist"] . "';";
			}
?>
			if(playlist_file.length > 4)
			{
				check_id = 0;
				var chkObjs = document.getElementsByName("itype");
				for(var i=0;i<chkObjs.length;i++){
					if(chkObjs[i].checked){
						check_id = i;
						break;
					}
				}
				
				if(confirm("是否导入列表,是否继续?") == true)
  				{
					document.authform.playlistfile.value = playlist_file;
			
					var introduction_type_array_tmp = new Array(5000);
					for(var i=0; i<5000; i++)
					{
						introduction_type_array_tmp[i] = "";	
					}
	
					var checkboxs = document.getElementsByName("introduction_checkbox");
					for(var i = 0; i < checkboxs.length; i++)
					{
						if(checkboxs[i].type == "checkbox" && checkboxs[i].checked)
						{
							introduction_type_array_tmp[i] = checkboxs[i].value + "#" + get_type_checkbox_value();
						}
					}
	
					var value = "";
					var len = checkboxs.length;
	
					for(var ii=0; ii<len; ii++)
					{
						if(introduction_type_array_tmp[ii].length > 3)
						{
							value = value + introduction_type_array_tmp[ii];
							if(ii < introduction_type_array_tmp.length - 1)
								value = value + "$";
						}
					}
			
					document.authform.type.value = value;
					document.authform.playlistitype.value = check_id;
					document.authform.submit();
				}
			}
			else
			{
				alert("请导入列表！");
			}
		}
		
		function live_back()
		{
			window.location.href = "live_preview_list.php";
		}

        </script>
    </body>
    
</html>

<?PHP
if(extension_loaded('zlib')){ob_end_flush();}
?>