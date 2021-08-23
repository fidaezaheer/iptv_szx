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
                                <div class="muted pull-left"><?php echo $lang_array["playback_list_text1"] ?></div>
                            </div>
                            <div class="block-content collapse in">
                                <div class="span12">
                                	
                                   	<div class="table-toolbar">
                                      <div class="btn-group">
                                         <a href="playback_add.php"><button class="btn btn-success"><?php echo $lang_array["playback_list_text2"] ?><i class="icon-plus icon-white"></i></button></a>
                                      </div>
                                      
                                      <div class="btn-group">
                                      	<input class="btn btn-success" name="" type="button" value="<?php echo $lang_array["playback_list_text4"] ?>" onclick="batch_url()"/>&nbsp;
                                      </div>
                                      
                                      <div class="btn-group">
                                      	<input class="btn btn-success" name="" type="button" value="<?php echo $lang_array["playback_list_text9"] ?>" onclick="batch_del()"/>&nbsp;   
                                      </div>
                                   	</div>
                                    
                                   	<form class="form-horizontal" name="" method="post" action="" enctype='multipart/form-data'>
                                   	<div class="control-group" style="background-color:#CCC">
                                   		<br/>
                                        <label class="control-label"><?php echo $lang_array["live_preview_list_text32"] ?></label>
                                        <div class="controls">
                                        <input class="input-medium focused" id="find_id" name="" type="text" style="width: 200px"/>&nbsp;<input name="" class="btn btn-primary" type="button" value="<?php echo $lang_array["live_preview_list_text32"] ?>" onclick="find_live()"/>&nbsp;<input name="" class="btn btn-primary" type="button" value="<?php echo $lang_array["live_preview_list_text33"] ?>" onclick="find_all()"/>
                                        </div>
                                        <br/>
                                   	</div>
                                   	</form>
                                    <!--<table cellpadding="0" cellspacing="0" border="0" class="table table-striped table-bordered" id="example2">-->
                                    <table cellpadding="0" cellspacing="0" border="0" class="table table-striped table-bordered" style="table-layout:fixed;">
                                        <thead>
                                            <tr>
          									<th width="4%" style="vertical-align:middle; text-align:center;"><?php echo $lang_array["live_preview_list_text3"] ?></th>
          									<th width="4%" style="vertical-align:middle; text-align:center;"><?php echo $lang_array["live_preview_list_text4"] ?></th>
          									<th width="5%" style="vertical-align:middle; text-align:center;"><?php echo $lang_array["live_preview_list_text5"] ?></th>
            								<th width="10%" style="vertical-align:middle; text-align:center;"><?php echo $lang_array["live_preview_list_text6"] ?></th>
            								<th width="46%" style="vertical-align:middle; text-align:center;"><?php echo $lang_array["live_preview_list_text7"] ?></th>
            								<th width="15%" style="vertical-align:middle; text-align:center;"><?php echo $lang_array["live_preview_list_text8"] ?></th>
          									<th width="10%" style="vertical-align:middle; text-align:center;"><?php echo $lang_array["live_preview_list_text9"] ?></th>
                                            </tr>
                                        </thead>
                                        
		<?php
		
			$sql->connect_database_default();
			$mydb = $sql->get_database();
			$sql->create_database($mydb);
			
			$mytable = "playback_type_table";
			$sql->create_table($mydb, $mytable, "name text, id text ,need int, typepassword text, param0 text, param1 text, param2 text, param3 text");
			$type_namess = $sql->fetch_datas($mydb, $mytable);
			foreach($type_namess as $type_names) {
				$type_array[$type_names[1]] = $type_names[0];
			}
			
			$mytable = "live_preview_table";
			$sql->create_table($mydb, $mytable, "name longtext, image longtext, url longtext, password longtext, type longtext, preview longtext, id longtext, urlid smallint, hd longtext");
			
			$sql->delete_data($mydb,$mytable,"urlid",20001);
			//$namess = $sql->fetch_datas_order_asc($mydb, $mytable,"urlid");
			//$namess = $sql->fetch_datas_limit_asc($mydb, $mytable, $offset, $size, "urlid");
			
			$namess = array();
			if(isset($_GET["find"]))
			{
				$find = $_GET["find"];
				$namess = $sql->fetch_datas_where_like_5_or($mydb, $mytable, "name", $find, "urlid", $find);
			}
			else
			{
				$namess = $sql->fetch_datas_order_where_limit_isplayback($mydb, $mytable, "urlid", "urlid", 10000, $offset, $size);
			}

			//$namess = $sql->fetch_datas_order_where_limit_isplayback($mydb, $mytable, "urlid", "urlid", 10000, $offset, $size);
			$numrows = count($sql->fetch_datas_order_where_isplayback($mydb, $mytable, "urlid", "urlid", 10000));	
			$pages = 0;
			$pages = intval($numrows/$size);
			if($numrows%$size)
			{
				$pages++;
			}
			echo "<tbody>";
			foreach($namess as $names) 
			{
				if(intval($names[7]) < 10000)
				{
					continue;
				}
				
				echo "<tr>";
				$key = null;
				$key = $names[7];
				echo "<td style='vertical-align:middle; text-align:center;'>";
				//echo "<input name='live_checkbox' type='checkbox' value='" . $key . "' />";
				echo "<div class='controls'><input class='uniform_on' name='playback_checkbox' type='checkbox' style='width:15px;height:25px;' value='" . $key . "' checked/></div>";
				echo "</td>";
				echo "<td style='vertical-align:middle; text-align:center;'>".(intval($key)-10000)."</td>";
				echo "<td style='vertical-align:middle; text-align:center;'><img src='" . "../images/livepic/" . $names[1] . "' width='24' height='24' /></td>";
				echo "<td style='vertical-align:middle; text-align:center;'>".$names[0]."</td>";
				
				$urlss = base64_decode($g->j2($names[2]));
				$urls = explode("geminihighlowgemini",$urlss);
				if(count($urls) >= 1)
					$high_urls = explode("|",$urls[0]);
					
				if(count($urls) >= 2)
					$low_urls = explode("|",$urls[1]);
				$value = "";
				if(count($urls) >= 1 && strlen($urls[0]) > 1) 
				{
					$value = $value . $lang_array["playback_list_text6"] . "--" . $lang_array["playback_list_text7"] . count($high_urls) . " " . $lang_array["playback_list_text8"] . "：";
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
					if(count($high_urls) > 1)
						$value = $value . " ......";
				}
				
				if(count($urls) >= 2 && strlen($urls[0]) > 1 && strlen($urls[1]) > 1) 
					$value = $value . "<br/>";
					
				if(count($urls) >= 2 && strlen($urls[1]) > 1) 
				{
					$value = $value . $lang_array["playback_list_text5"] . "--" . $lang_array["playback_list_text7"] . count($low_urls) . " " . $lang_array["playback_list_text8"] . "：";
					if(count($low_urls) > 0)
					{
						$low_url = explode("#",$low_urls[0]);
						$value = $value . $low_url[1];
					}
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
				
				echo "<td style='vertical-align:middle; text-align:center;'><a href='playback_edit.php?key=" . $key . "'>".$lang_array["edit_text1"]."</a>&nbsp;&nbsp;<a href='#' onclick='delete_live(\"".$names[0]."\",\"" . $key . "\")'>".$lang_array["del_text1"]."</a></td>";
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
        <!--/.fluid-container-->
        <FORM name="authform_del" action="batch_playback_del.php" method="post">  
    	<input name="del" id="del" type="hidden" value=""/>
    	</Form>
        
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
		
		function delete_live(name,id)
		{
			var r=confirm("<?php echo $lang_array["playback_list_text3"] ?>:" + name + " ?");
			if(r==true)
  			{
				var url = "playback_del.php?name=" + name + "&id=" + id;
				window.location.href = url;
  			}
		}

		function edit_user(name)
		{
  			{
				var url = "playback_edit.php?name=" + name;
				window.location.href = url;
  			}
		}
		
		function go_page()
		{
			var pageid = document.getElementById("pageid").value;
			var url = "playback_list.php?page=" + (pageid-1);
			window.location.href = url;
		}
		
		function go_pre()
		{
<?php
			if($page-1 >= 0)
			{
				echo "var url = 'playback_list.php?page=".($page-1)."';";
				echo "window.location.href = url;";
			}
?>
		}
		
		function go_back()
		{
<?php
			if($page+1 < $pages)
			{
				echo "var url = 'playback_list.php?page=".($page+1)."';";
				echo "window.location.href = url;";
			}
?>			
		}
		
		function selectAll() //全选
		{
			//alert(0);
			var objs = document.getElementsByName('playback_checkbox');
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
			var objs = document.getElementsByName('playback_checkbox');
			var i;
			for(i = 0; i < objs.length; i++)
			{
				if(objs[i].type == "checkbox")
				{
					objs[i].checked = false;
				}
			}
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
					window.location.href = "batch_playback_url.php?url0=" + new_url + "&url1=" + old_url;
				}
			}
		}
		
		function batch_del()
		{
			var r=confirm("<?php echo $lang_array["playback_list_text10"] ?>");
			if(r==true)
  			{	
				document.authform_del.del.value = get_type_checkbox_value();
      			document.authform_del.submit()	
			}
		}
		
		function get_type_checkbox_value()
		{
			var value = "";
			var value_array = new Array();
			var type_checkboxs = document.getElementsByName("playback_checkbox");
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
		
		function find_live()
		{
			var value = document.getElementById("find_id").value;
			var url = "playback_list.php?find=" + value;
			window.location.href = url;
		}
		
		function find_all()
		{
			var url = "playback_list.php";
			window.location.href = url;
		}
        </script>
    </body>

</html>