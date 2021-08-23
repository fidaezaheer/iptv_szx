<!DOCTYPE html>
<?php
include_once "cn_lang.php";
include_once "common.php";
include_once "gemini.php";
$sql = new DbSql();
$sql->login();
$g = new Gemini();
set_zone();
$g->check_version();
$sql->connect_database_default();
$mydb = $sql->get_database();
$sql->create_database($mydb);

$mytable = "vod_table_".$_GET["type"];
$sql->create_table($mydb, $mytable, "name text, image text, url text, area text, year text, 
	type text, intro1 longtext, intro2 longtext, intro3 longtext, intro4 longtext,
	id int, clickrate int, recommend tinyint, chage float, updatetime int, 
	firstletter text");
			
if($sql->find_column($mydb, $mytable, "updatetime") == 0)
	$sql->add_column($mydb, $mytable,"updatetime", "int");
		
if($sql->find_column($mydb, $mytable, "firstletter") == 0)
	$sql->add_column($mydb, $mytable,"firstletter", "text");

$size = 20;
$offset = 0;
$page = 0;
if(isset($_GET["page"]))
{
	$offset = $size*intval($_GET["page"]);
	$page = $_GET["page"];
}
	
//$namess = $sql->fetch_datas_order($mydb, $mytable, "id"); 
$namess = array();
if(isset($_GET["find"]))
{
	$find = $_GET["find"];
	$namess = $sql->fetch_datas_where_like_5_or($mydb, $mytable, "name", $find, "id", $find);
}
else
{
	$namess = $sql->fetch_datas_limit_desc($mydb, $mytable, $offset, $size, "id");
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
    
    <body onLoad="init()">
        <div class="container-fluid">
            <div class="row-fluid">
              <div class="span13" id="content">
                <div class="row-fluid">
                   <!-- block -->
						<div class="block">
                            <div class="navbar navbar-inner block-header">
                                <div class="muted pull-left"><?php echo $lang_array["vod_list_text1"] ?></div>
                            </div>
                            <div class="block-content collapse in">
                                <div class="span12">
                                	
                                   	<div class="table-toolbar">
                                      <div class="btn-group">
                                         <a href="vod_add.php?type=<?php echo $_GET["type"] ?>"><button class="btn btn-success"><?php echo $lang_array["vod_list_text10"] ?><i class="icon-plus icon-white"></i></button></a>
                                      </div>
                                      
                                      <div class="btn-group">
                                      	<input class="btn btn-success" name="" type="button" value="<?php echo $lang_array["vod_list_text16"] ?>" onclick="batch_url()"/>&nbsp;
                                      </div>
                                      
                                      <div class="btn-group">
                                      	<input class="btn btn-success" name="" type="button" value="<?php echo $lang_array["vod_list_text17"] ?>" onclick="batch_password()"/>&nbsp;
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
                                    <table cellpadding="0" cellspacing="0" border="0" class="table table-striped table-bordered" style="table-layout:fixed;">
                                        <thead>
                                            <tr>
          									<th width="3%" style="vertical-align:middle; text-align:center;"><?php echo $lang_array["vod_list_text2"] ?></th>
          									<th width="4%" style="vertical-align:middle; text-align:center;"><?php echo $lang_array["vod_list_text3"] ?></th>
          									<th width="10%" style="vertical-align:middle; text-align:center;"><?php echo $lang_array["vod_list_text4"] ?></th>
            								<th width="7%" style="vertical-align:middle; text-align:center;"><?php echo $lang_array["vod_list_text5"] ?></th>
            								<th width="50%" style="vertical-align:middle; text-align:center;"><?php echo $lang_array["vod_list_text6"] ?></th>
            								<th width="6%" style="vertical-align:middle; text-align:center;"><?php echo $lang_array["vod_list_text7"] ?></th>
          									<th width="13%" style="vertical-align:middle; text-align:center;"><?php echo $lang_array["vod_list_text8"] ?></th>
                                            <th width="10%" style="vertical-align:middle; text-align:center;"><?php echo $lang_array["vod_list_text9"] ?></th>
                                            </tr>
                                        </thead>
                                        
		<?php
			echo "<tbody>";
			foreach($namess as $names) 
			{
				echo "<tr>";
				$key = $names[10];
				echo "<td style='vertical-align:middle; text-align:center;'>";
				//echo "<input name='live_checkbox' type='checkbox' value='" . $key . "' />";
				echo "<div class='controls'><input class='uniform_on' name='vod_checkbox' type='checkbox' style='width:15px;height:25px;' value='" . $key . "' checked/></div>";
				echo "</td>";
				echo "<td style='vertical-align:middle; text-align:center;'>".$names[10]."</td>";
				echo "<td style='vertical-align:middle; text-align:center;'>".$names[0]."</td>";
				if(preg_match("/^(http:\/\/|https:\/\/).*$/",$names[1])){
                    echo "<td style='vertical-align:middle; text-align:center;'><img src='" . $names[1] . "' width='24' height='24' /></td>";
                }else{
					echo "<td style='vertical-align:middle; text-align:center;'><img src='" . "../images/vodpic/" . $names[1] . "' width='24' height='24' /></td>";
				}
				$vods = explode("|", $g->j4($names[2]));
				$num = count($vods);
				if($num > 1)
				{
					$tmps = explode("#", $vods[0]);
					$tmpss = explode("geminipwgemini",$tmps[1]);
					if($tmpss >= 2)
						echo "<td style='vertical-align:middle; text-align:center;'>" . $lang_array["vod_list_text14"] . $num . $lang_array["vod_list_text15"] ."  ". $tmpss[0] . " ..." . "</td>";
					else
						echo "<td style='vertical-align:middle; text-align:center;'>" . $lang_array["vod_list_text14"] . $num . $lang_array["vod_list_text15"] . "  ". $tmps[1] . " ..." . "</td>";
					
					//echo "<td style='vertical-align:middle; text-align:center;'>" . "共" . $num . "集  ". $tmps[1] . " ..." . "</td>";
				}
				else if($num > 0)
				{
					$tmps = explode("#", $vods[0]);
					if(count($tmps) >= 2)
					{
						if(count($tmps) >= 2)
						{
							$tmpss = explode("geminipwgemini",$tmps[1]);
							if($tmpss >= 2)
								echo "<td style='vertical-align:middle; text-align:center;'>" . $lang_array["vod_list_text14"] . $num . $lang_array["vod_list_text15"] . "  " . $tmpss[0] . " ..." . "</td>";
							else
								echo "<td style='vertical-align:middle; text-align:center;'>" . $lang_array["vod_list_text14"] . $num . $lang_array["vod_list_text15"] . "  ". $tmps[1] . " ..." . "</td>";
					
							//echo "<td>" . $tmps[1] . "</td>";
						}
				
						//echo "<td style='vertical-align:middle; text-align:center;'>" . $tmps[1] . "</td>";
					}
					else
						echo "<td style='vertical-align:middle; text-align:center;'>" . $lang_array["vod_list_text11"] . "</td>";
				}
				echo "<td style='vertical-align:middle; text-align:center;'>" . $names[11] . "</td>";
				if( $names[14] != null)
					echo "<td style='vertical-align:middle; text-align:center;'>" . date("Y-m-d H:i:s",$names[14]) . "</td>";
				else
					echo "<td style='vertical-align:middle; text-align:center;'>" . $lang_array["vod_list_text12"] . "</td>";
				echo "<td style='vertical-align:middle; text-align:center;'><a href='vod_edit.php?type=" . $_GET["type"] . "&id=" . $names[10] . "'>" . $lang_array["edit_text1"] . "</a></img>";
				echo "&nbsp&nbsp";
				echo "<a href='#' onclick='delete_vod(\"" . $names[10] . "\",\"" . $_GET["type"] . "\",\"" . $names[0] . "\")'>" . $lang_array["del_text1"] . "</a></img></td>";
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
		
		function init()
		{

		}
		
		function delete_vod(id,type,name)
		{
			var r=confirm("<?php echo $lang_array["vod_list_text13"] ?>" + ": " + name + " ?");
			if(r==true)
  			{
				var url = "vod_del.php?id=" + id + "&type=" + <?php echo $_GET["type"] ?>;
				window.location.href = url;
  			}
		}
		
		function go_page()
		{
			var pageid = document.getElementById("pageid").value;
			var url = "vod_list.php?page=" + (pageid-1) + "&type=" + "<?php echo $_GET["type"] ?>";
			window.location.href = url;
		}
		
		function go_pre()
		{
<?php
			if($page-1 >= 0)
			{
				echo "var url = 'vod_list.php?page=".($page-1). "&type=" . $_GET["type"] . "';";
				echo "window.location.href = url;";
			}
?>
		}
		
		function go_back()
		{
<?php
			if($page+1 < $pages)
			{
				echo "var url = 'vod_list.php?page=".($page+1). "&type=" . $_GET["type"] . "';";
				echo "window.location.href = url;";
			}
?>			
		}
		
		function selectAll() //全选
		{
			//alert(0);
			var objs = document.getElementsByName('vod_checkbox');
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
			var objs = document.getElementsByName('vod_checkbox');
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
					window.location.href = "batch_vod_url.php?type=<?php echo $_GET["type"] ?>&url0=" + new_url + "&url1=" + old_url;
				}
			}
		}
		
		function batch_password()
		{
			var r=confirm("<?php echo $lang_array["vod_list_text18"] ?>" + "?");
			if(r==true)
  			{	
				var old_ps= window.prompt("<?php echo $lang_array["vod_list_text19"] ?>" + ":");
				var new_ps= window.prompt("<?php echo $lang_array["vod_list_text20"] ?>" + ":");
				var rr=confirm("<?php echo $lang_array["live_preview_list_text12"] ?>" + "：" + old_ps + " -> " + new_ps + "<?php echo $lang_array["live_preview_list_text13"] ?>" + "?");
				if(rr == true)
				{
					window.location.href = "batch_vod_ps.php?type=<?php echo $_GET["type"] ?>&ps0=" + new_ps + "&ps1=" + old_ps;
				}
			}
		}
		
		function find_live()
		{
			var value = document.getElementById("find_id").value;
			var url = "vod_list.php?find=" + value + "&type=<?php echo $_GET["type"] ?>";
			window.location.href = url;
		}
		
		function find_all()
		{
			var url = "vod_list.php" + "&type=<?php echo $_GET["type"] ?>";
			window.location.href = url;
		}
        </script>
    </body>

</html>