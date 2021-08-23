<!DOCTYPE html>
<?php
include_once "cn_lang.php";
include_once "common.php";
include_once "gemini.php";
$sql = new DbSql();
$sql->login();

$sql->connect_database_default();
$mydb = $sql->get_database();
$sql->create_database($mydb);
$mytable = "live_preview_table";

$sql->create_table($mydb, $mytable, "name longtext, image longtext, url longtext, password longtext, type longtext, preview longtext, id longtext, urlid smallint, hd longtext");

$namess = $sql->fetch_datas_order_asc($mydb, $mytable,"urlid");

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
    
    <body onLoad="preivew_timeout()">
        <div class="container-fluid">
            <div class="row-fluid">
              <div class="span13" id="content">
                <div class="row-fluid">
                   <!-- block -->
						<div class="block">
                            <div class="navbar navbar-inner block-header">
                                <div class="muted pull-left"><?php echo $lang_array["timer_preview_text1"] ?></div>
                                
                            </div>
                            <div class="block-content collapse in">
                                <div class="span12">
                                
                                	<table width="800" border="0">
  									<tr>
    									<td>
                                        	<div class="table-toolbar">
                                      		<div class="btn-group">
                                         	<a href="#" onClick="preview_all(0)"><button class="btn btn-success"><?php echo $lang_array["timer_preview_text11"] ?></button></a>
                                         	<a href="#" onClick="preview_all(1)"><button class="btn btn-success"><?php echo $lang_array["timer_preview_text19"] ?></button></a>
                                      		</div>
                                   			</div>
                                        </td>
    									<td width="600px"><label id="time_label"><?php echo $lang_array["timer_preview_text18"] ?></label></td>
  									</tr>
									</table>

                                    <table cellpadding="0" cellspacing="0" border="0" class="table table-striped table-bordered" id="example2">
                                        <thead>
                                            <tr>
          									<th width="4%" style="vertical-align:middle; text-align:center;"><?php echo $lang_array["timer_preview_text2"] ?></th>
          									<th width="5%" style="vertical-align:middle; text-align:center;"><?php echo $lang_array["timer_preview_text3"] ?></th>
          									<th width="4%" style="vertical-align:middle; text-align:center;"><?php echo $lang_array["timer_preview_text4"] ?></th>
            								<th width="8%" style="vertical-align:middle; text-align:center;"><?php echo $lang_array["timer_preview_text5"] ?></th>
            								<th width="50%" style="vertical-align:middle; text-align:center;"><?php echo $lang_array["timer_preview_text6"] ?></th>
            								<th width="7%" style="vertical-align:middle; text-align:center;"><?php echo $lang_array["timer_preview_text7"] ?></th>
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

			$namess = $sql->fetch_datas_order_asc($mydb, $mytable,"urlid");

			echo "<tbody>";
			foreach($namess as $names) 
			{
				if(strcmp($names[6],"null") == 0)
				{
					continue;
				}
				
				echo "<tr>";
				
				
				$key = intval($names[7]);
				if($key > 10000)
				{
					echo "<td style='vertical-align:middle; text-align:center;'>".$lang_array["timer_preview_text8"]."</td>";
					echo "<td style='vertical-align:middle; text-align:center;'>".($key-10000)."</td>";
				}
				else
				{
					echo "<td style='vertical-align:middle; text-align:center;'>".$lang_array["timer_preview_text9"]."</td>";
					echo "<td style='vertical-align:middle; text-align:center;'>".$names[7]."</td>";					
				}
				echo "<td style='vertical-align:middle; text-align:center;'><img src='" . "../images/livepic/" . $names[1] . "' width='48' height='48' /></td>";
				echo "<td style='vertical-align:middle; text-align:center;'>".$names[0]."</td>";
				
				$preview = $lang_array["timer_preview_text10"];
				$date = date('Y-m-d');
				//echo "<br/>" . $date . "<br/>";
				$previews = explode("$#geminipreview#$",$names[5]);
				for($ii=0; $ii<count($previews); $ii++)
				{
					
					if(strncmp($previews[$ii],$date,strlen($date)) == 0)
					{
						$tmp = explode("$#geminidate#$",$previews[$ii]);
						//echo "<br/>" . $previews[$ii] . "<br/>";
						
						$preview = $tmp[1];
						break;
					}
				}
				echo "<td style='word-break:break-all;vertical-align:middle; text-align:center;'>".$preview."</td>";
				
				echo "<td style='vertical-align:middle; text-align:center;'><a href='#' onclick='live_preview(\"".$names[6]."\",\"" . $key . "\")'>" . $lang_array["timer_preview_text12"] . "</a>
					&nbsp;<a href='#' onclick='live_preview_all(\"".$names[6]."\",\"" . $key . "\")'>" . $lang_array["timer_preview_text13"] . "</a>&nbsp;&nbsp;<a href='#' onclick='live_preview_del(\"".$names[6]."\",\"" . $key . "\")'>" . $lang_array["del_text2"] . "</a></td>";				
				
			}
			echo "</tbody>";
			
			
			$sql->disconnect_database();
        ?>                                   
                                    </table>
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
		
		function live_preview(previewid,id)
		{
			if(previewid.indexOf("https://www.tvsou.com") >=0)
			{
				var url = "tvsou3_preview.php?previewid=" + previewid + "&urlid=" + id;
				window.location.href = url;
			}
			else if(previewid.indexOf("TV_") >=0 && previewid.indexOf("Channel_") >= 0)
  			{
				//var url = "tvmao_preview.php?name=" + name + "&id=" + id;
				var url = "tvsou2_preview.php?previewid=" + previewid + "&urlid=" + id;
				window.location.href = url;
  			}
			else if(previewid.indexOf("tvmao") >=0)
  			{
				var url = "tvmao_preview.php?previewid=" + previewid + "&urlid=" + id;
				window.location.href = url;
  			}
			else if(previewid.indexOf("suntv") >=0)
  			{
				var url = "suntv_preview.php?previewid=" + previewid + "&urlid=" + id;
				window.location.href = url;
  			}
			else if(previewid.indexOf("olleh") >=0)
  			{
				//var url = "tvmao_preview.php?name=" + name + "&id=" + id;
				var url = "tvolleh3_preview.php?previewid=" + previewid + "&urlid=" + id;
				window.location.href = url;
  			}
			else if(previewid.indexOf("yahoo") >=0 && previewid.indexOf(".xml") >=0)
			{
				var url = "yahoo_japan_preview.php?previewid=" + previewid + "&urlid=" + id;
				window.location.href = url;
			}
			else if(previewid.indexOf("ontvtonight") >=0)
			{
				var url = "ontvtonight_preview.php?previewid=" + previewid + "&urlid=" + id;
				window.location.href = url;
			}
			else if(previewid.indexOf("tvmap") >=0)
			{
				var url = "tvmap_preview.php?previewid=" + previewid + "&urlid=" + id;
				window.location.href = url;
			}
			else if(previewid.indexOf("mod") >=0)
			{
				var url = "mod_preview.php?previewid=" + previewid + "&urlid=" + id;
				window.location.href = url;
			}
			else if(previewid.indexOf("meuguia") >=0)
			{
				var url = "meuguia_preview.php?previewid=" + previewid + "&urlid=" + id;
				window.location.href = url;
			}
			else
			{	
				
				var url = previewid + "&urlid=" + id;
				window.location.href = url;			
			}
		}

		function live_preview_all(previewid,id)
		{
			var url = "timer_preview_all.php?id=" + previewid + "&urlid=" + id;
			window.location.href = url;			
		}
		
		function live_preview_del(previewid,id)
		{
			var r=confirm("<?php echo $lang_array["timer_preview_text20"] ?>" + "？");
			if(r==true)
  			{
				var url = "timer_preview_del.php?id=" + previewid + "&urlid=" + id;
				window.location.href = url;	
			}
		}
		
		function preview_all(v)
		{
			window.location.href = 'tvsou_tvmao_preview_all.php?value=' + v;
		}
		
		function preivew_timeout()
		{
			var p = window.setInterval("preview_scroll()",60000*5);	
		}
		
		function preview_scroll_showtime()
		{
			var t = new Date();
			var year = t.getFullYear();
			var month = t.getMonth() + 1;
			var day = t.getDate();
			var hours = t.getHours();
			var minutes = t.getMinutes();
			var week = t.getDay();
	
			var between_hours = 0;
			var between_min = 0;

			if(hours <= 0)
				between_hours = 0;
			else
				between_hours = 24 - hours;
		
			if(minutes < 10)
				between_min = 10 - minutes;
			else
			{
				between_min = 59 - minutes + 10;
				between_hours = 23 - hours;
			}
	
			var t = "<?php echo $lang_array["timer_preview_text14"] ?>" + "：" + between_hours + "<?php echo $lang_array["timer_preview_text15"] ?>" + between_min + "<?php echo $lang_array["timer_preview_text16"] ?>";
	
			var id = document.getElementById("time_label").innerHTML = t;
	
		}

		function preview_scroll()
		{
			
			var t = new Date();
			var year = t.getFullYear();
			var month = t.getMonth() + 1;
			var day = t.getDate();
			var hours = t.getHours();
			var minutes = t.getMinutes();
			var week = t.getDay();
			
			/*
			var between_hours = 0;
			var between_min = 0;

			if(hours <= 0)
				between_hours = 0;
			else
				between_hours = 24 - hours;
		
			if(minutes < 10)
				between_min = 10 - minutes;
			else
			{
				between_min = 59 - minutes + 10;
				between_hours = 23 - hours;
			}
	
			var t = "<?php //echo $lang_array["timer_preview_text14"] ?>" + "：" + between_hours + "<?php //echo $lang_array["timer_preview_text15"] ?>" + between_min + "<?php //echo $lang_array["timer_preview_text16"] ?>";
	
			var id = document.getElementById("time_label").innerHTML = t;
	
			if((hours == 0) && (minutes == 10))
			{
				window.location.href = "tvsou_tvmao_preview_all.php";
			}
			*/
			
			if(hours > 2)
				window.location.href = "tvsou_tvmao_preview_all.php";
			
			//alert(t);
		}

        </script>
    </body>

</html>