<!DOCTYPE html>
<?php
	include_once "cn_lang.php";
	include_once "common.php";
	$sql = new DbSql();
	$sql->login();

	$mytable = "stream_server_list_table";
	$sql->connect_database_default();
	$mydb = $sql->get_database();
	$sql->create_database($mydb);
	//$sql->create_table($mydb, $mytable, "urlid int, name text, url text, online int, cache int, path text, tabel text, serverip text, url1 text, url2 text, url3 text");
	$sql->create_table($mydb, $mytable, "serverid int, serverip text, tip text, os text, version int, info text, online int, channelcount int");	
	if($sql->find_column($mydb, $mytable, "online") == 0)
		$sql->add_column($mydb, $mytable,"online", "int");
		
	if($sql->find_column($mydb, $mytable, "channelcount") == 0)
		$sql->add_column($mydb, $mytable,"channelcount", "int");			
				
	$namess = $sql->fetch_datas_order_asc($mydb, $mytable,"serverid");
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
    </head>
    
    <body>
        <div class="container-fluid">
            <div class="row-fluid">
              <div class="span13" id="content">
                <div class="row-fluid">
                   <!-- block -->
						<div class="block">
                            <div class="navbar navbar-inner block-header">
                                <div class="muted pull-left"><?php echo $lang_array["stream_server_list_text1"] ?></div>
                            </div>
                            <div class="block-content collapse in">
                                <div class="span12">   
                                   	<div class="table-toolbar">
                                      	<div class="btn-group">
                                         	<a href="stream_server_add.php"><button class="btn btn-success"><?php echo $lang_array["stream_server_list_text6"] ?><i class="icon-plus icon-white"></i></button></a>
                                      	</div>
                                   	</div>
                                                                    
                                    <table cellpadding="0" cellspacing="0" border="0" class="table table-bordered">
                                        <thead>
                                            <tr>
            								<th width="8%" style="vertical-align:middle; text-align:center;"><?php echo $lang_array["stream_server_list_text5"] ?></th>
            								<th width="12%" style="vertical-align:middle; text-align:center;"><?php echo $lang_array["stream_server_list_text2"] ?></th>
          									<th width="20%" style="vertical-align:middle; text-align:center;"><?php echo $lang_array["stream_server_list_text3"] ?></th>
                                            <th width="30%" style="vertical-align:middle; text-align:center;"><?php echo $lang_array["stream_server_list_text14"] ?></th>
                                            <th width="5%" style="vertical-align:middle; text-align:center;"><?php echo $lang_array["stream_server_list_text21"] ?></th>
                                            <th width="5%" style="vertical-align:middle; text-align:center;"><?php echo $lang_array["stream_server_list_text22"] ?></th>
            								<th width="20%" style="vertical-align:middle; text-align:center;"><?php echo $lang_array["stream_server_list_text4"] ?></th>
                                            </tr>
                                        </thead>
<?php
                                        echo "<tbody>";
										
										//$mytable = "stream_channel_list_table";
										//$sql->create_table($mydb, $mytable, "urlid int, name text, url text, online int, cache int, 
										//		path text, tabel text, serverip text, url1 text, url2 text, 
										//		url3 text, tip text, status text, receive text");
										
										foreach($namess as $names) 
										{
        									echo "<tr>";

  											echo "<td style='align:center; vertical-align:middle; text-align:center;'>" . $names[0] . "</td>";
                                        	echo "<td style='align:center; vertical-align:middle; text-align:center;'>" . $names[1] . "</td>";
											echo "<td style='align:center; vertical-align:middle; text-align:center;'>" . $names[2] . "</td>";
											
											$info = explode("|",$names[5]);
											$info_text = "";
											if(count($info) == 4)
											{
												$cpu = $info[0];
												$mem = $info[1];
												$mems = explode("/",$mem);
												$dev = $info[2];
												$devs = explode("/",$dev);
												$net = $info[3];
												$nets = explode("/",$net);
											
												$info_text = "CPU:" . $cpu . "%<br/>";
												$info_text = $info_text . $lang_array["stream_server_list_text15"] . ":" . $mems[1] . "GB &nbsp;&nbsp;&nbsp;&nbsp;" . $lang_array["stream_server_list_text20"] . ":"  . $mems[0] .  "%<br/>";
												$info_text = $info_text . $lang_array["stream_server_list_text16"] . ":" . $devs[1] . "GB &nbsp;&nbsp;&nbsp;&nbsp;" . $lang_array["stream_server_list_text20"] . ":" . $devs[0] . "%<br/>";
												$info_text = $info_text . $lang_array["stream_server_list_text17"] . ":" . $net . "&nbsp;&nbsp;&nbsp;&nbsp;" . $lang_array["stream_server_list_text19"] . ":" . $nets[0] . "MB/S" . "&nbsp;&nbsp;&nbsp;&nbsp;" . $lang_array["stream_server_list_text18"] . ":" . $nets[1] . "MB/S" . "<br/>";
											}
											
											
											echo "<td style='align:center; vertical-align:middle; text-align:left;'>";
											
											echo "<div id='bar1' class='progress progress-striped active' style='background:#bfc2c2'>";
                                            echo "<div class='bar' style='width:" . $cpu . "%'>CPU:" . $cpu . "%</div>";
                                            echo "</div>";
											
											echo "<div id='bar2' class='progress progress-striped active' style='background:#bfc2c2'>";
                                            echo "<div class='bar' style='width:" . $mems[0] . "%'>" . "MEM" . ":" . $mems[0] . "%</div>";
                                            echo "</div>";
											
											echo "<div id='bar3' class='progress progress-striped active' style='background:#bfc2c2'>";
                                            echo "<div class='bar' style='width:" . $devs[0] . "%'>" . "DISK" . ":" . $devs[0] . "%</div>";
                                            echo "</div>";
											
											echo "</td>";
											
											//$online_tatol = 0;
											//$serverss = $sql->fetch_datas_where($mydb, $mytable, "serverip", $names[1]);	
											
											//foreach($serverss as $servers)
											//{
											//	$online_tatol = $online_tatol + intval($servers[3]);
											//}
											echo "<td style='align:center; vertical-align:middle; text-align:center;'>" . $names[6] . "</td>";
											
											echo "<td style='align:center; vertical-align:middle; text-align:center;'>" . $names[7] . "</td>";
											
											echo "<td style='vertical-align:middle; text-align:center;'><a href='#' onclick='run_server(\"".$names[0]."\")'>".$lang_array["stream_server_list_text9"].
													"</a>&nbsp;&nbsp;<a href='#' onclick='reboot_server(\"".$names[0]."\")'>".$lang_array["stream_server_list_text11"].
													"</a>&nbsp;&nbsp;<a href='#' onclick='edit_server(\"".$names[0]."\")'>".$lang_array["edit_text1"].
													"</a>&nbsp;&nbsp;<a href='#' onclick='run_info(\"".$names[0]."\")'>".$lang_array["stream_server_list_text14"].
													"</a>&nbsp;&nbsp;<a href='#' onclick='delete_server(\"".$names[0]."\")'>".$lang_array["del_text1"].
													"</a><br/><br/>&nbsp;&nbsp;<a href='#' onclick='check_live(\"".$names[1]."\",\"".urlencode($names[2])."\")'>".$lang_array["stream_server_list_text25"].
													"</a>&nbsp;&nbsp;<a href='#' onclick='check_vod(\"".$names[1]."\",\"".urlencode($names[2])."\")'>".$lang_array["stream_server_list_text26"].
													"</a></td>";
											
        									echo "</tr>";   
										}
                                        echo "<tbody>";        
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
        
        <script src="bootstrap/js/bootstrap.min.js"></script>
        <script src="vendors/datatables/js/jquery.dataTables.min.js"></script>


        <script src="assets/scripts.js"></script>
        <script src="assets/DT_bootstrap.js"></script>
        
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
		function GetRadioValue(RadioName){
    		var obj;
    		obj=document.getElementsByName(RadioName);
    		if(obj!=null){
       			var i;
        		for(i=0;i<obj.length;i++)
				{
           	 		if(obj[i].checked){
                		return obj[i].value;
            		}
        		}
    		}
    		return null;
		}

		function loadXMLDoc(serverid,cmd)
		{
			var xmlhttp;
			if (window.XMLHttpRequest)
			{
				//  IE7+, Firefox, Chrome, Opera, Safari 浏览器执行代码
				xmlhttp=new XMLHttpRequest();
			}
			else
			{
				// IE6, IE5 浏览器执行代码
				xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
			}
			xmlhttp.onreadystatechange=function()
			{
				if(xmlhttp.readyState==4 && xmlhttp.status==200)
				{
					if(xmlhttp.responseText.indexOf("ReceiveCmdSuccessful") >= 0)
					{
						alert("<?php echo $lang_array["stream_server_list_text23"] ?>");	
					}
					else
					{
						alert("<?php echo $lang_array["stream_server_list_text24"] ?>");
					}
				}
			}
			xmlhttp.open("GET","stream_server_control.php?serverid="+ serverid + "&control=" + cmd,true);
			xmlhttp.send();
		}
		
		function run_server(serverid)
		{
			//window.location.href = "stream_server_control.php?serverid=" + serverid + "&control=runall";
			loadXMLDoc(serverid,"runall");
		}
		
		function stop_server(serverid)
		{
			//window.location.href = "stream_server_control.php?serverid=" + serverid + "&control=stopall";
			loadXMLDoc(serverid,"stopall");
		}
		
		function check_server(serverip,tip)
		{
			window.location.href = "stream_channel_live_list.php?serverip=" + serverip + "&tip=" + tip;
		}
		
		function check_live(serverip,tip)
		{
			window.location.href = "stream_channel_live_list.php?serverip=" + serverip + "&tip=" + tip + "&type=live";
		}
		
		function check_vod(serverip,tip)
		{
			window.location.href = "stream_channel_vod_list.php?serverip=" + serverip + "&tip=" + tip + "&type=vod";
		}

		function reboot_server(serverid)
		{
			if(confirm("<?php echo $lang_array["stream_server_list_text12"] ?>") == true)
  			{
				//window.location.href = "stream_server_control.php?serverid=" + serverid + "&control=reboot";
				loadXMLDoc(serverid,"reboot");
			}
		}
		
		function edit_server(serverid)
		{
			
			window.location.href = "stream_server_edit.php?serverid=" + serverid;
		}
		
		function run_info(serverid)
		{
			window.location.href = "stream_server_info.php?serverid=" + serverid + "&control=runall";
		}
		
		function delete_server(serverid)
		{
			if(confirm("<?php echo $lang_array["stream_server_list_text8"] ?>： " + serverid + " ?") == true)
  			{
				window.location.href = "stream_server_del.php?serverid=" + serverid;
			}
		}
        </script>
    </body>

</html>

<?php
	$sql->disconnect_database();

?>