<?PHP
if(extension_loaded('zlib') && strstr($_SERVER['HTTP_ACCEPT_ENCODING'],'gzip'))
{
	ob_end_clean();
    ob_start('ob_gzhandler');
}

function secToTime($times){
        $result = '00:00:00';
        if ($times>0) {
                $hour = floor($times/3600);
                $minute = floor(($times-3600 * $hour)/60);
                $second = floor((($times-3600 * $hour) - 60 * $minute) % 60);
                $result = sprintf("%02d",$hour).':'.sprintf("%02d",$minute).':'.sprintf("%02d",$second);
        }
        return $result;
}
?> 

<!DOCTYPE html>
<?php
	include_once "cn_lang.php";
	include_once "common.php";
	$sql = new DbSql();
	$sql->login();

	$serverip = $_GET["serverip"];
	$offset = $_GET["offset"];
	$size = 50;
	$partition = $_GET["partition"];
	
	$dirnamess = array();
	$cmd = "";
	if(isset($_GET["find"]))
		$cmd = "findvoddirinfo#" . $_GET["find"];
	else
		$cmd = "getvoddirinfo#" . $partition . "|" . $offset . "|" . $size;
	$ret = send($serverip,$cmd);
	//echo $ret;
	if(strstr($ret,"?xml") != false && strstr($ret,"version=") != false)
	{
		$doc = new DOMDocument();
		$doc->loadXML($ret);
		$channels = $doc->getElementsByTagName("vod");
		$index = 0;
		//echo "count:" . count($channels);
		foreach($channels as $channel)
		{
			$index++;
			$dirname = $channel->getElementsByTagName("name")->item(0)->nodeValue;
			$dirlongtime = $channel->getElementsByTagName("longtime")->item(0)->nodeValue;
			$dirpath = $channel->getElementsByTagName("dir")->item(0)->nodeValue;
			$dirvideoname = $channel->getElementsByTagName("videoname")->item(0)->nodeValue;
		
			//echo $dirvideoname;
			
			$dirnames = array();
			array_push($dirnames,$index);
			array_push($dirnames,$dirname);
			array_push($dirnames,$dirlongtime);
			array_push($dirnames,$dirpath);
			array_push($dirnames,$dirvideoname);
			
			array_push($dirnamess,$dirnames);
		}
	}
	
	
	$total = 0;
	$offset = 0;
	$size = 0;
	if(strstr($ret,"?xml") != false && strstr($ret,"version=") != false)
	{
		$doc = new DOMDocument();
		$doc->loadXML($ret);
		$channels = $doc->getElementsByTagName("vodinfo");
		foreach($channels as $channel){
			$total = $channel->getElementsByTagName("total")->item(0)->nodeValue;
			$offset = $channel->getElementsByTagName("offset")->item(0)->nodeValue;
			$size = $channel->getElementsByTagName("size")->item(0)->nodeValue;
		}
	}
	
	$pages = intval($total/$size);
	if($total%$size > 0)
		$pages = $pages + 1;
	$page = $offset;

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
    
    <body onLoad="init()">
        <div class="container-fluid">
            <div class="row-fluid">
              <div class="span13" id="content">
                <div class="row-fluid">
                   <!-- block -->
						<div class="block">
                            <div class="navbar navbar-inner block-header">
                                <div class="muted pull-left">
								<?php 
									echo $lang_array["stream_server_list_text1"];
									if(isset($_GET["serverip"])) 
									{
										echo "&nbsp&nbsp&nbsp&nbsp&nbsp" . $_GET["serverip"];
										if(isset($_GET["tip"]) && strlen($_GET["tip"]) > 0)
										{
											echo "(" . urldecode($_GET["tip"]) . ")";
										}
									}
								?>
                               	</div>
                            </div>
                            
                            <div class="block-content collapse in">
                                <div class="span12">   
                                	<form class="form-horizontal" name="authform" method="post" action="" enctype='multipart/form-data'>
                                   	<div class="table-toolbar" style="background-color:#CCC"><br/>
                                    	
                                        
                                        
										<label class="control-label"><?php echo $lang_array["stream_channel_vod_list_text8"] ?></label>
                                        <div class="controls" style="text-align:left;">
                                        	<select id="find_partition_id" name=""  style='width:180px;' onchange="reload_partition()">
											<?php
											if($partition == 0)
												echo "<option value='0' selected='selected' style='width:120px;'>" . $lang_array["stream_channel_vod_list_text9"] . "</option>";
											else
												echo "<option value='0' style='width:120px;'>" . $lang_array["stream_channel_vod_list_text9"] . "</option>";
											if($partition == 1)	
												echo "<option value='1' selected='selected' style='width:120px;'>" . $lang_array["stream_channel_vod_list_text10"] . "</option>";
											else
												echo "<option value='1' style='width:120px;'>" . $lang_array["stream_channel_vod_list_text10"] . "</option>";
											if($partition == 2)
												echo "<option value='2' selected='selected' style='width:120px;'>" . $lang_array["stream_channel_vod_list_text11"] . "</option>";
											else
												echo "<option value='2' style='width:120px;'>" . $lang_array["stream_channel_vod_list_text11"] . "</option>";
											if($partition == 3)
												echo "<option value='3' selected='selected' style='width:120px;'>" . $lang_array["stream_channel_vod_list_text12"] . "</option>";
											else
												echo "<option value='3' style='width:120px;'>" . $lang_array["stream_channel_vod_list_text12"] . "</option>";
											if($partition == 4)
												echo "<option value='4' selected='selected' style='width:120px;'>" . $lang_array["stream_channel_vod_list_text13"] . "</option>";
											else
												echo "<option value='4' style='width:120px;'>" . $lang_array["stream_channel_vod_list_text13"] . "</option>";
											?>
    										</select>
                                        
                                            <input class="input-medium focused" id="find_id" name="find_id" type="text" value="">
                                            <button type="button" class="btn btn-success" onClick="find_content()"><?php echo $lang_array["custom_list_text22"] ?></button>
                                            <button type="button" class="btn btn-success" onClick="reduction()"><?php echo $lang_array["custom_list_text29"] ?></button>

											&nbsp&nbsp&nbsp&nbsp&nbsp
                                        
											<div class="btn-group">
                                      			<input class="btn btn-success" name="" type="button" value="<?php echo $lang_array["stream_channel_list_text34"] ?>" onclick="export_url()"/>&nbsp;
                                     		</div>

                                        	<div class="btn-group">
                                      			<input class="btn btn-success" name="" type="button" value="<?php echo $lang_array["stream_channel_list_text30"] ?>" onclick="flash_list()"/>&nbsp;
                                     		</div>
                                        
                                        	<!--
                                        	<div class="btn-group">
                                      			<input class="btn btn-success" name="" type="button" value="<?php echo $lang_array["stream_channel_list_text31"] ?>" onclick="batch_del()"/>&nbsp;
                                     		</div>
                                            -->
                                        </div>
                                        
                                        
                                        
                                        
                                        
                                        <!--
                                        <div class="btn-group">
                                        	<input class="input-medium focused" id="find_id" name="find_id" type="text" value="">&nbsp&nbsp
                                      		<input class="btn btn-success" name="" type="button" value="<?php echo $lang_array["stream_channel_vod_list_text1"] ?>" onclick="find_context()"/>&nbsp;
                                     	</div>
                                        -->
                                        <br/>
                                        <br/>
                                   	</div>
                                                                    
                                    <table cellpadding="0" cellspacing="0" border="0" class="table table-striped table-bordered">
                                        <thead>
                                            <tr>
                                            <th width="4%" style="vertical-align:middle; text-align:center;"><?php echo $lang_array["stream_channel_vod_list_text2"] ?></th>
            								<th width="10%" style="vertical-align:middle; text-align:center;"><?php echo $lang_array["stream_channel_vod_list_text3"] ?></th>
          									<th width="10%" style="vertical-align:middle; text-align:center;"><?php echo $lang_array["stream_channel_vod_list_text4"] ?></th>
                                            <th width="32%" style="vertical-align:middle; text-align:center;"><?php echo $lang_array["stream_channel_vod_list_text6"] ?></th>
                                            <th width="10%" style="vertical-align:middle; text-align:center;"><?php echo $lang_array["stream_channel_vod_list_text5"] ?></th>
                                            <th width="10%" style="vertical-align:middle; text-align:center;"><?php echo $lang_array["stream_channel_vod_list_text8"] ?></th>
            								<th width="10%" style="vertical-align:middle; text-align:center;"><?php echo $lang_array["stream_channel_vod_list_text7"] ?></th>
                                            </tr>
                                        </thead>
<?php
                                        echo "<tbody>";
										$urlid = 1;
										$allchannelcount = count($namess);
										foreach($dirnamess as $dirnames) 
										{
        									echo "<tr>";
											
											echo "<td style='word-wrap:break-word; align:center; vertical-align:middle; text-align:center;'>" . ($offset*$size+intval($dirnames[0])) . "</td>";
											echo "<td style='word-wrap:break-word; align:center; vertical-align:middle; text-align:center;'>" . urldecode($dirnames[4]) . "</td>";
											echo "<td style='word-wrap:break-word; align:center; vertical-align:middle; text-align:center;'>" . $dirnames[1] . "</td>";
											echo "<td style='word-wrap:break-word; align:center; vertical-align:middle; text-align:center;'>" . "gp2p://" . $serverip . ":19350/?day=-1&distribution=" . $dirnames[1] . "</td>";
											echo "<td style='word-wrap:break-word; align:center; vertical-align:middle; text-align:center;'>" . secToTime(intval($dirnames[2])) . "</td>";
											
											$pdir = "";
											if(strstr($dirnames[3],"/home/gemini/gserver0/") != false)
											{
												$pdir = $lang_array["stream_channel_list_text17"];
											}
											else if(strstr($dirnames[3],"/home/gemini/gserver1/") != false)
											{
												$pdir = $lang_array["stream_channel_list_text18"];
											}
											else if(strstr($dirnames[3],"/home/gemini/gserver2/") != false)
											{
												$pdir = $lang_array["stream_channel_list_text19"];
											}
											else if(strstr($dirnames[3],"/home/gemini/gserver3/") != false)
											{
												$pdir = $lang_array["stream_channel_list_text20"];
											}
											else if(strstr($dirnames[3],"/home/gemini/gserver4/") != false)
											{
												$pdir = $lang_array["stream_channel_list_text21"];
											}
											else if(strstr($dirnames[3],"/tmp/gemini/gserver/") != false)
											{
												$pdir = $lang_array["stream_channel_list_text22"];
											}
											echo "<td style='word-wrap:break-word; align:center; vertical-align:middle; text-align:center;'>" . $pdir . "</td>";
											
											echo "<td style='word-wrap:break-word; align:center; vertical-align:middle; text-align:center;'>" . "<a href='#' onclick='delete_dir(\"".$dirnames[1]."\",\"" . $serverip . "\")'>".$lang_array["del_text1"]."</a>" . "</td>";
											
        									echo "</tr>";   
										}
                                        echo "<tbody>";        
?>
                                    </table>
                                    <table cellpadding="0" cellspacing="0" border="0" class="table table-bordered">                       
                                    </table>
                                    
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
<?php
	
	$sql->connect_database_default();
	$mydb = $sql->get_database();
	$sql->create_database($mydb);
	$mytable = "stream_server_list_table";
	//$sql->create_table($mydb, $mytable, "urlid int, name text, url text, online int, cache int, path text, tabel text, serverip text, url1 text, url2 text, url3 text");
	$sql->create_table($mydb, $mytable, "serverid int, serverip text, tip text, os text, version int, info text, online int, channelcount int");	
	
	$sql->update_data_2($mydb, $mytable, "serverip", $serverip, "online", $allonline);
	$sql->update_data_2($mydb, $mytable, "serverip", $serverip, "channelcount", $allchannelcount);
?>
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

		function delete_dir_js(serverip,dir)
		{
			var xmlhttp;
			if (window.XMLHttpRequest)
			{
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
					if(xmlhttp.responseText.indexOf("delok") >= 0)
					{
						var value = document.getElementById("find_partition_id").value;
						window.location.href = "stream_channel_vod_list.php?serverip=<?php echo $serverip ?>&offset=<?php echo $offset ?>&partition=" + value;
					}
					else
					{
						alert("<?php echo $lang_array["stream_channel_vod_list_text14"] ?>");
					}
				}
			}
			xmlhttp.open("GET","stream_dir_vod_del.php?dir="+ dir + "&serverip=" + serverip,true);
			xmlhttp.send();
		}
		
		function delete_dir(dir,serverip)
		{
			if(confirm("<?php echo $lang_array["stream_channel_list_text14"] ?>： " + dir + " ?") == true)
  			{
				delete_dir_js(serverip,dir)
			}
		}

		function flash_list()
		{
			
			var value = document.getElementById("find_partition_id").value;
			window.location.href = "stream_channel_vod_list.php?serverip=<?php echo $serverip ?>&offset=0&partition=" + value;
		}
		
		function batch_del()
		{
			var r=confirm("<?php echo $lang_array["stream_channel_list_text32"] ?>");
			if(r==true)
			{
				window.location.href = "stream_channel_batch_del_list.php?serverip=<?php echo $serverip ?>";
			}
		}
		
		function find_content()
		{
			var value = document.getElementById("find_id").value;
			var partition = document.getElementById("find_partition_id").value;
			window.location.href = "stream_channel_vod_list.php?find=" + encodeURI(value) + "&serverip=<?php echo $serverip ?>&offset=0&partition=" + partition;			
		}
		
		function reduction()
		{
			flash_list();
		}
		
		function export_url()
		{
			window.location.href = "stream_channel_vod_export_list.php?serverip=<?php echo $serverip ?>";
		}
		
		function reload_partition()
		{
			var value = document.getElementById("find_partition_id").value;
			window.location.href = "stream_channel_vod_list.php?serverip=<?php echo $serverip ?>&offset=0&partition=" + value;	
		}
		
		
		function go_pre()
		{
			var value = document.getElementById("find_partition_id").value;
<?php
			if($page-1 >= 0)
			{
				echo "var url = 'stream_channel_vod_list.php?offset=" . ($page-1) . "&serverip=" . $serverip . "&partition=" . "' + value;";
				echo "window.location.href = url;";
			}
?>			
		}
		
		function go_page()
		{
			var pageid = document.getElementById("pageid").value;
			var value = document.getElementById("find_partition_id").value;
			
			var url = "stream_channel_vod_list.php?offset=" + (pageid-1) + "&serverip=<?php echo $serverip ?>&partition=" + value;
			window.location.href = url;
		}
		
		function go_back()
		{
			var value = document.getElementById("find_partition_id").value;
			<?php
			if($page+1 < $pages)
			{
				echo "var url = 'stream_channel_vod_list.php?offset=" . ($page+1) . "&serverip=" . $serverip . "&partition=" . "' + value;";
				echo "window.location.href = url;";
			}
?>
		}
        </script>
    </body>

</html>

<?php
	$sql->disconnect_database();
?>

<?PHP
if(extension_loaded('zlib')){ob_end_flush();}
?>