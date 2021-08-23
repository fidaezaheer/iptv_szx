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
	$sql = new DbSql();
	$sql->login();

	/*
	$sql->connect_database_default();
	$mydb = $sql->get_database();
	$sql->create_database($mydb);
	
	$mytable = "stream_channel_list_table";
	$sql->create_table($mydb, $mytable, "urlid int, name text, url text, online int, cache int, 
										path text, tabel text, serverip text, url1 text, url2 text, 
										url3 text, tip text, status text, receive text");
										
	$namess = array();
	if(isset($_GET["serverip"]))
		$namess = $sql->fetch_datas_where_asc($mydb, $mytable, "serverip", $_GET["serverip"], "urlid");	
	else
	{
		if(isset($_GET["find"]))
		{
			$namess = $sql->fetch_datas_like_5_or($mydb, $mytable, "name", "url", "tip", "url1", "url2", $_GET["find"]);
		}
		else
		{
			$namess = $sql->fetch_datas($mydb, $mytable);
		}
	}
	*/
	$serverip = trim($_GET["serverip"]);
	//echo $serverip;
	
	$cmd = "getplayxml";
	$ret = send($serverip,$cmd);
	//echo "ret:" . $ret;
	//$allonline = 0;
	//$allchannelcount = 0;
	
	$namess = array();
	
	if(strstr($ret,"?xml") != false && strstr($ret,"version=") != false)
	{
		$doc = new DOMDocument();
		$doc->loadXML($ret);
		$channels = $doc->getElementsByTagName("channel");
		foreach($channels as $channel){
			$names = array();
			$urlid = $channel->getElementsByTagName("id")->item(0)->nodeValue;
			$name = $channel->getElementsByTagName("name")->item(0)->nodeValue;
			$url = $channel->getElementsByTagName("url")->item(0)->nodeValue;
			$dir = $channel->getElementsByTagName("dir")->item(0)->nodeValue;
			$rtime = $channel->getElementsByTagName("time")->item(0)->nodeValue;
			$disable = $channel->getElementsByTagName("disable")->item(0)->nodeValue;
			$tabel = $channel->getElementsByTagName("tabel")->item(0)->nodeValue;
			$equilibrium = $channel->getElementsByTagName("equilibrium")->item(0)->nodeValue;
			$acc = $channel->getElementsByTagName("acc")->item(0)->nodeValue;
			
			array_push($names,$urlid);
			array_push($names,$name);
			array_push($names,$url);
			array_push($names,$dir);
			array_push($names,$rtime);
			array_push($names,$disable);
			array_push($names,$tabel);
			array_push($names,$equilibrium);
			array_push($names,$acc);
			array_push($names,0);
			
			array_push($namess,$names);
		}
	}
	
	/*
	$statuss_array = array();
	if(file_exists("xml/".$serverip."_statusall.xml"))
	{
		$doc = new DOMDocument();
		$doc->load("xml/".$serverip."_statusall.xml");
		$statuss = $doc->getElementsByTagName("status");
		foreach($statuss as $status){
			$status_array = array();
			$id = $status->getElementsByTagName("id")->item(0)->nodeValue;
			//$run = $status->getElementsByTagName("run")->item(0)->nodeValue;
			$online = $status->getElementsByTagName("online")->item(0)->nodeValue;
			$receive = $status->getElementsByTagName("receive")->item(0)->nodeValue;

			array_push($status_array,$id);
			array_push($status_array,"1");
			array_push($status_array,$online);
			array_push($status_array,$receive);
			
			array_push($statuss_array,$status_array);
		}
	}
	*/
	
	$dirnamess = array();
	
	$cmd = "getlivedirinfo#all";
	$ret = send($serverip,$cmd);
	if(strstr($ret,"?xml") != false && strstr($ret,"version=") != false)
	{
		$doc = new DOMDocument();
		$doc->loadXML($ret);
		$channels = $doc->getElementsByTagName("status");
		$index = 0;
		foreach($channels as $channel){
			//
			$index++;
			$exist = -1;
			$dirname = $channel->getElementsByTagName("name")->item(0)->nodeValue;
			//$dirrun = $channel->getElementsByTagName("run")->item(0)->nodeValue;
			$dirreceive = $channel->getElementsByTagName("receive")->item(0)->nodeValue;
			$dironline = $channel->getElementsByTagName("online")->item(0)->nodeValue;
			$dirpath = $channel->getElementsByTagName("dir")->item(0)->nodeValue;
			$dircurrent = $channel->getElementsByTagName("current")->item(0)->nodeValue;
			
			//foreach($namess as $names) 
			for($ii=0; $ii<count($namess); $ii++)
			{
				if($namess[$ii][1] == $dirname)
				{
					$exist = $ii;
					break;
				}
			}
			
			if($exist == -1)
			{
				$add = time()%1000000+$index;
				
				$dirnames = array();
				array_push($dirnames,$add);
				array_push($dirnames,$dirname);
				array_push($dirnames,"push");
				array_push($dirnames,$dirreceive);
				array_push($dirnames,$dironline);
				array_push($dirnames,$dirpath);
				array_push($dirnames,$dircurrent);
				array_push($dirnames,"");
				array_push($dirnames,"");
				array_push($dirnames,"");
				array_push($dirnames,"");
				array_push($dirnamess,$dirnames);
				
				/*
				$dirstatus_array = array();
				array_push($dirstatus_array,$dirname);
				array_push($dirstatus_array,"1");
				array_push($dirstatus_array,$dironline);
				array_push($dirstatus_array,$dirreceive);
				array_push($dirstatus_array,$dircurrent);
				
				array_push($statuss_array,$dirstatus_array);
				*/
			}
			else
			{
				$dirnames = array();
				array_push($dirnames,$namess[$exist][0]);
				array_push($dirnames,$dirname);
				array_push($dirnames,$namess[$exist][2]);
				array_push($dirnames,$dirreceive);
				array_push($dirnames,$dironline);
				array_push($dirnames,$dirpath);
				array_push($dirnames,$dircurrent);
				array_push($dirnames,$namess[$exist][6]);
				array_push($dirnames,$namess[$exist][4]);
				array_push($dirnames,$namess[$exist][7]);
				array_push($dirnames,$namess[$exist][8]);
				array_push($dirnamess,$dirnames);
				
				$namess[$exist][9] = 1;
			}
		}
	}
	
	for($ii=0; $ii<count($namess); $ii++)
	{
		if($namess[$ii][9] == 0)
		{
			$dirnames = array();
		
			array_push($dirnames,$namess[$ii][0]);
			array_push($dirnames,$namess[$ii][1]);
			array_push($dirnames,$namess[$ii][2]);
			array_push($dirnames,"0");
			array_push($dirnames,"0");
			array_push($dirnames,$namess[$ii][3]);
			array_push($dirnames,"0");
			array_push($dirnames,$namess[$ii][6]);
			array_push($dirnames,$namess[$ii][4]);
			array_push($dirnames,$namess[$ii][7]);
			array_push($dirnames,$namess[$ii][8]);
			
			array_push($dirnamess,$dirnames);
		}
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
                                    	&nbsp&nbsp&nbsp&nbsp&nbsp
                                      	<div class="btn-group">
                                            <input class="btn btn-success" name="" type="button" value="<?php echo $lang_array["stream_channel_list_text9"] ?>" onclick="add_url()"/>&nbsp;
                                      	</div>
                                        
                                        <div class="btn-group">
                                      		<input class="btn btn-success" name="" type="button" value="<?php echo $lang_array["stream_channel_list_text25"] ?>" onclick="import_url()"/>&nbsp;
                                     	</div>
                                        
                                        <div class="btn-group">
                                      		<input class="btn btn-success" name="" type="button" value="<?php echo $lang_array["stream_channel_list_text34"] ?>" onclick="export_url()"/>&nbsp;
                                     	</div>
                                        
                                        <div class="btn-group">
                                      		<input class="btn btn-success" name="" type="button" value="<?php echo $lang_array["stream_channel_list_text27"] ?>" onclick="batch_list()"/>&nbsp;
                                     	</div>
                                        <!--
                                        <div class="btn-group">
                                      		<input class="btn btn-success" name="" type="button" value="<?php echo $lang_array["stream_channel_list_text29"] ?>" onclick="sync_list()"/>&nbsp;
                                     	</div>
                                        -->
                                        <div class="btn-group">
                                      		<input class="btn btn-success" name="" type="button" value="<?php echo $lang_array["stream_channel_list_text30"] ?>" onclick="flash_list()"/>&nbsp;
                                     	</div>
                                        
                                        <div class="btn-group">
                                      		<input class="btn btn-success" name="" type="button" value="<?php echo $lang_array["stream_channel_list_text31"] ?>" onclick="batch_del()"/>&nbsp;
                                     	</div>
                                        &nbsp&nbsp&nbsp&nbsp&nbsp
                                        <!--
                                        <div class="btn-group">
                                        	<input class="input-medium focused" id="find_id" name="find_id" type="text" value="">&nbsp&nbsp
                                      		<input class="btn btn-success" name="" type="button" value="<?php echo $lang_array["stream_channel_list_text33"] ?>" onclick="find_context()"/>&nbsp;
                                     	</div>
                                        -->
                                        <br/>
                                        <br/>
                                   	</div>
                                                                    
                                    <table cellpadding="0" cellspacing="0" border="0" class="table table-striped table-bordered" id="example2">
                                        <thead>
                                            <tr>
            								<th width="4%" style="vertical-align:middle; text-align:center;"><?php echo $lang_array["stream_channel_list_text2"] ?></th>
                                            <!--
                                            <th width="5%" style="vertical-align:middle; text-align:center;"><?php echo $lang_array["stream_channel_list_text35"] ?></th>
                                            -->
                                            <th width="4%" style="vertical-align:middle; text-align:center;"><?php echo $lang_array["stream_channel_list_text12"] ?></th>
            								<th width="7%" style="vertical-align:middle; text-align:center;"><?php echo $lang_array["stream_channel_list_text3"] ?></th>
          									<th width="7%" style="vertical-align:middle; text-align:center;"><?php echo $lang_array["stream_channel_list_text4"] ?></th>
                                            <th width="22%" style="vertical-align:middle; text-align:center;"><?php echo $lang_array["stream_channel_list_text13"] ?></th>
            								<th width="10%" style="vertical-align:middle; text-align:center;"><?php echo $lang_array["stream_channel_add_text24"] ?></th>
                                            <th width="10%" style="vertical-align:middle; text-align:center;"><?php echo $lang_array["stream_channel_add_text25"] ?></th>
                                            <th width="6%" style="vertical-align:middle; text-align:center;"><?php echo $lang_array["stream_channel_list_text8"] ?></th>
                                            <th width="5%" style="vertical-align:middle; text-align:center;"><?php echo $lang_array["stream_channel_list_text6"] ?></th>
                                            <th width="6%" style="vertical-align:middle; text-align:center;"><?php echo $lang_array["stream_channel_list_text5"] ?></th>
                                            <th width="8%" style="vertical-align:middle; text-align:center;"><?php echo $lang_array["stream_channel_list_text23"] ?></th>
                                            <th width="22%" style="vertical-align:middle; text-align:center;"><?php echo $lang_array["stream_channel_list_text10"] ?></th>
                                            </tr>
                                        </thead>
<?php
                                        echo "<tbody>";
										$urlid = 0;
										$allchannelcount = count($namess);
										foreach($dirnamess as $dirnames) 
										{
        									echo "<tr>";
											
											$receive = intval($dirnames[3]);
											
											$online = $dirnames[4];
											$running = 0;
											$current = $dirnames[6];
											$equilibrium = urlencode($dirnames[9]);
											
											$allmtime = 0;
											$onemtime = 0;
											$nameexists = 0;
											
											/*
											if(file_exists("xml/".$serverip."_statusall.xml"))
												$allmtime=filemtime("xml/".$serverip."_statusall.xml");
											if(file_exists("xml/".$serverip."_".$names[1]."_".$path."_status.xml"))
												$onemtime=filemtime("xml/".$serverip."_".$names[1]."_".$path."_status.xml");
												
											//if(file_exists("xml/".$serverip."_statusall.xml") && $allmtime > $onemtime)
											{
												foreach($statuss_array as $status_array)
												{
													if($status_array[0] == $names[1])	
													{
														$running = intval($status_array[1]);
														$online = $status_array[2];
														$receive = $status_array[3];
														$current = $status_array[4];
														
														$nameexists = 1;
														break;
													}
												}
											}									
											
											if(file_exists("xml/".$serverip."_".$names[1]."_".$path."_status.xml") && $nameexists == 0)
											{
												$filename = "xml/".$serverip."_".$names[1]."_".$path."_status.xml";
												$dom = new DOMDocument('1.0', 'UTF-8');  
												$dom->load($filename);  
												$receive = $dom->getElementsByTagName("receive")->item(0)->nodeValue;  
												$online = $dom->getElementsByTagName("online")->item(0)->nodeValue;  
												$running = $dom->getElementsByTagName("run")->item(0)->nodeValue; 
											}
											*/
											
  											echo "<td style='word-wrap:break-word; align:center; vertical-align:middle; text-align:center;'>" . $urlid++ . "</td>";
											//echo "<td style='word-wrap:break-word; align:center; vertical-align:middle; text-align:center;'>" . $dirnames[0] . "</td>";
											$img = "<img src=\"images/stop.png\" width=\"36\" height=\"36\">";
											
											if($receive < 145000000 && $receive > 0)
											{
												$img = "<img src=\"images/run.png\" width=\"36\" height=\"36\">";
											}
											else if($dirnames[2] != "push")
											{
												if($receive == 0)
												{
													$img = "<img src=\"images/stop.png\" width=\"36\" height=\"36\">";
												}
												else if($current-intval($receive)*10 < 1000 && $receive > 0)
												{
													$img = "<img src=\"images/run.png\" width=\"36\" height=\"36\">";
												}
											}
											else
											{
												if($current-intval($receive)*10 < 1000 && $receive > 0)
												{
													$img = "<img src=\"images/run.png\" width=\"36\" height=\"36\">";
												}
											}
											
											$path = "0";
											if(strstr($dirnames[5],"/home/gemini/gserver0/") != false)
											{
												$path = "0";
											}
											else if(strstr($dirnames[5],"/home/gemini/gserver1/") != false)
											{
												$path = "1";
											}
											else if(strstr($dirnames[5],"/home/gemini/gserver2/") != false)
											{
												$path = "2";
											}
											else if(strstr($dirnames[5],"/home/gemini/gserver3/") != false)
											{
												$path = "3";
											}
											else if(strstr($dirnames[5],"/home/gemini/gserver4/") != false)
											{
												$path = "4";
											}
											else if(strstr($dirnames[5],"/tmp/gemini/gserver/") != false)
											{
												$path = "5";
											}
											echo "<td style='word-wrap:break-word; align:center; vertical-align:middle; text-align:center;' onDblClick='td_ondouleclick(\"" . $dirnames[0] . "\",\"" . $serverip . "\",\"" . $names[1] . "\",\"" . $path . "\")'>" . $img . "</td>";
											$tables = urldecode($dirnames[7]);
											$tabless = explode("@#@",$tables);
                                        	echo "<td style='word-wrap:break-word; align:center; vertical-align:middle; text-align:center;'>" . $tabless[0] . "</td>";
											
											/*
											if($sql->count_fetch_datas_where($mydb, $mytable,"name",$names[1]) > 1)
												echo "<td style='background-color:#7F7F7F; word-wrap:break-word; align:center; vertical-align:middle; text-align:center;'>" . $names[1] . "</td>";
											else
											*/
											echo "<td style='word-wrap:break-word; align:center; vertical-align:middle; text-align:center;'>" . $dirnames[1] . "</td>";
											
											echo "<td style='word-wrap:break-word; align:center; vertical-align:middle; text-align:center;'>";
											echo "<table width='300' border='0' style='table-layout:fixed;'>";
  											echo "<tr>";
											if(strstr($dirnames[2],"http://") != false || strstr($dirnames[2],"udp://") != false)
    											echo "<td style='word-wrap:break-word;'>" . $dirnames[2] . "</td>";
											else
												echo "<td style='word-wrap:break-word;'>" . "PUSH" . "</td>";
 							 				echo "</tr>";
  											echo "<tr>";
											if(strlen($dirnames[9]) > 4 && strlen($dirnames[10]) > 4)
    											echo "<td style='word-wrap:break-word;'>" . $lang_array["stream_channel_list_text24"] . ":" . "gp2p://" . $serverip . ":19350/?day=0&equ=" . $dirnames[9] . "&acc=" . $dirnames[10] . "&distribution=" . $dirnames[1] . "</td>";
											else if(strlen($dirnames[9]) > 4 && strlen($dirnames[10]) < 4)
    											echo "<td style='word-wrap:break-word;'>" . $lang_array["stream_channel_list_text24"] . ":" . "gp2p://" . $serverip . ":19350/?day=0&equ=" . $dirnames[9] . "&distribution=" . $dirnames[1] . "</td>";
											else if(strlen($dirnames[9]) < 4 && strlen($dirnames[10]) > 4)
    											echo "<td style='word-wrap:break-word;'>" . $lang_array["stream_channel_list_text24"] . ":" . "gp2p://" . $serverip . ":19350/?day=0&acc=" . $dirnames[10] . "&distribution=" . $dirnames[1] . "</td>";	
											else
												echo "<td style='word-wrap:break-word;'>" . $lang_array["stream_channel_list_text24"] . ":" . "gp2p://" . $serverip . ":19350/?day=0&distribution=" . $dirnames[1] . "</td>";
 								 			echo "</tr>";
											echo "</table>";
											echo "</td>";
											echo "<td style='word-wrap:break-word; align:center; vertical-align:middle; text-align:center;'>" . base64_decode($dirnames[9]) . "</td>";

											echo "<td style='word-wrap:break-word; align:center; vertical-align:middle; text-align:center;'>" . $dirnames[10] . "</td>";
											if(strlen($online) <= 0)
												$online = "0";
											$allonline = $allonline + intval($online);
											echo "<td style='word-wrap:break-word; align:center; vertical-align:middle; text-align:center;'>" . $online . "</td>";
											if($dirnames[2] != "push")
												echo "<td style='word-wrap:break-word; align:center; vertical-align:middle; text-align:center;'>" . $dirnames[8] . "</td>";
											else
												echo "<td style='word-wrap:break-word; align:center; vertical-align:middle; text-align:center;'>". $dirnames[8] . "</td>";
											$pdir = "";
											/*
											switch(intval($names[5]))
											{
												case 0:
													$pdir = $lang_array["stream_channel_list_text17"];
													break;	
												case 1:
													$pdir = $lang_array["stream_channel_list_text18"];
													break;	
												case 2:
													$pdir = $lang_array["stream_channel_list_text19"];
													break;	
												case 3:
													$pdir = $lang_array["stream_channel_list_text20"];
													break;	
												case 4:
													$pdir = $lang_array["stream_channel_list_text21"];
													break;	
												case 5:
													$pdir = $lang_array["stream_channel_list_text22"];
													break;																										
											}
											*/
											if(strstr($dirnames[5],"/home/gemini/gserver0/") != false)
											{
												$pdir = $lang_array["stream_channel_list_text17"];
											}
											else if(strstr($dirnames[5],"/home/gemini/gserver1/") != false)
											{
												$pdir = $lang_array["stream_channel_list_text18"];
											}
											else if(strstr($dirnames[5],"/home/gemini/gserver2/") != false)
											{
												$pdir = $lang_array["stream_channel_list_text19"];
											}
											else if(strstr($dirnames[5],"/home/gemini/gserver3/") != false)
											{
												$pdir = $lang_array["stream_channel_list_text20"];
											}
											else if(strstr($dirnames[5],"/home/gemini/gserver4/") != false)
											{
												$pdir = $lang_array["stream_channel_list_text21"];
											}
											else if(strstr($dirnames[5],"/tmp/gemini/gserver/") != false)
											{
												$pdir = $lang_array["stream_channel_list_text22"];
											}
											
											echo "<td style='align:center; vertical-align:middle; text-align:center;'>" . $pdir . "</td>";
											
											echo "<td style='align:center; vertical-align:middle; text-align:center;'>" . date('m/d H:i:s',intval($receive)*10) . "</td>";
											
											echo "<td style='vertical-align:middle; text-align:center;'>";
											if($dirnames[2] != "push")
											{
												echo "<a href='#' onclick='run_channel(\"".$dirnames[0]."\",\"" . $serverip . "\")'>".$lang_array["stream_channel_list_text15"]."</a>&nbsp;&nbsp;<a href='#' onclick='stop_channel(\"".$dirnames[0]."\",\"" . $serverip . "\")'>".$lang_array["stream_channel_list_text16"]."</a>&nbsp;&nbsp;<a href='#' onclick='edit_channel(\"".$dirnames[0]."\",\"" . $serverip . "\")'>".$lang_array["edit_text1"]."</a>&nbsp;&nbsp;<a href='#' onclick='delete_channel(\"".$dirnames[0]."\",\"" . $serverip . "\",\"" . $equilibrium . "\")'>".$lang_array["del_text1"]."</a>";
											}
											else
											{
												echo "<a href='#' onclick='edit_push_channel(\"".$dirnames[1]."\",\"" . $serverip . "\")'>".$lang_array["edit_text1"]."</a>&nbsp;&nbsp;<a href='#' onclick='delete_dir(\"".$dirnames[1]."\",\"" . $serverip . "\",\"" . $equilibrium . "\")'>".$lang_array["del_text1"]."</a>";
											}
											echo "</td>";
        									echo "</tr>";   
										}
                                        echo "<tbody>";        
?>
                                    </table>
                                    <table cellpadding="0" cellspacing="0" border="0" class="table table-bordered">                       
                                    </table>
                                    
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

		function load_channel(id,control,serverip)
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
			xmlhttp.open("GET","stream_channel_control.php?urlid="+ id + "&control=" + control + "&serverip=" + serverip + "&servercheck=1",true);
			xmlhttp.send();
		}
		
		function run_channel(id,serverip)
		{
			<?php
			/*
				if(isset($_GET["serverip"]))
				{
					echo "window.location.href = \"stream_channel_control.php?urlid=\" + id + \"&control=\" + \"run\" + \"&serverip=\" + serverip + \"&servercheck=1\";";
				}
				else
					echo "window.location.href = \"stream_channel_control.php?urlid=\" + id + \"&control=\" + \"run\" + \"&serverip=\" + serverip;";
			*/
			?>
			
			load_channel(id,"run",serverip);
			
		}
		
		function stop_channel(id,serverip)
		{
			<?php
			/*
				if(isset($_GET["serverip"]))
				{
					echo "window.location.href = \"stream_channel_control.php?urlid=\" + id + \"&control=\" + \"stop\" + \"&serverip=\" + serverip + \"&servercheck=1\";";
				}
				else
					echo "window.location.href = \"stream_channel_control.php?urlid=\" + id + \"&control=\" + \"stop\" + \"&serverip=\" + serverip;";
			*/
			?>
			
			load_channel(id,"stop",serverip);
		}
		
		function edit_channel(id,serverip)
		{
		<?php
			if(isset($_GET["serverip"]))
				echo "window.location.href = \"stream_channel_edit.php?urlid=\" + id + \"&serverip=\" + serverip + \"&servercheck=1\";";
			else
				echo "window.location.href = \"stream_channel_edit.php?urlid=\" + id + \"&serverip=\" + serverip;";
		?>
		}
		
		function edit_push_channel(name,serverip)
		{
		<?php
			if(isset($_GET["serverip"]))
				echo "window.location.href = \"stream_channel_push_edit.php?name=\" + name + \"&serverip=\" + serverip + \"&servercheck=1\";";
			else
				echo "window.location.href = \"stream_channel_push_edit.php?name=\" + name + \"&serverip=\" + serverip;";
		?>	
		}
		
		function delete_channel(id,serverip,equilibrium)
		{
			if(confirm("<?php echo $lang_array["stream_channel_list_text14"] ?>： " + id + " ?") == true)
  			{
				<?php
				if(isset($_GET["serverip"]))
					echo "window.location.href = \"stream_channel_del.php?urlid=\" + id + \"&serverip=\" + serverip + \"&servercheck=1\" + \"&equilibrium=\" + equilibrium;";
				else
					echo "window.location.href = \"stream_channel_del.php?urlid=\" + id + \"&serverip=\" + serverip + \"&equilibrium=\" + equilibrium;";
				?>
			}
		}
		
		function delete_dir(dir,serverip,equilibrium)
		{
			if(confirm("<?php echo $lang_array["stream_channel_list_text14"] ?>： " + dir + " ?") == true)
  			{
				<?php
				if(isset($_GET["serverip"]))
					echo "window.location.href = \"stream_dir_del.php?dir=\" + dir + \"&serverip=\" + serverip + \"&servercheck=1\" + \"&equilibrium=\" + equilibrium;";
				else
					echo "window.location.href = \"stream_dir_del.php?dir=\" + dir + \"&serverip=\" + serverip + \"&equilibrium=\" + equilibrium;";
				?>
			}
		}
		
		function td_ondouleclick(id,serverip,name,path)
		{
			window.location.href = "stream_channel_status.php?urlid=" + id + "&serverip=" + serverip + "&name=" + name + "&path=" + path;
			<?php
			/*
				if(isset($_GET["serverip"]))
					echo "window.location.href = \"stream_channel_status.php?urlid=\" + id + \"&serverip=\" + serverip + \"&servercheck=1\";";
				else
					echo "window.location.href = \"stream_channel_status.php?urlid=\" + id + \"&serverip=\" + serverip;";
			*/
			?>
		}
		
		function import_url()
		{
			window.location.href = "stream_channel_import_list.php?serverip=<?php echo $serverip ?>";
			//window.open("stream_channel_import_list2.php", "newwindow", "height=250, width=600, toolbar =no, menubar=no, scrollbars=no, resizable=no, location=no, status=no");//写成一行  
		}
		
		function init()
		{
			<?php 
				if(isset($_GET["control"]) && intval($_GET["control"]) == 0)
				{
					echo "alert('" . $lang_array["stream_channel_list_text28"] . "')";	
				}
			?>	
		}
		
		function batch_list()
		{
			window.open("stream_channel_batch_list.php?serverip=<?php echo $serverip ?>", "newwindow", "height=500, width=1280, toolbar =no, menubar=no, scrollbars=no, resizable=no, location=no, status=no");//写成一行 
		}
		
		function sync_list()
		{
			window.open("stream_channel_sync_list.php", "newwindow", "height=300, width=650, toolbar =no, menubar=no, scrollbars=no, resizable=no, location=no, status=no");//写成一行 
		}
		
		function flash_list()
		{
			<?php
				//echo "window.location.href = 'stream_channel_flash_post.php?servercheck=1&serverip=" . $_GET["serverip"] . "';";
				echo "window.location.href = 'stream_channel_live_list.php?serverip=" . $_GET["serverip"] . "';";
			?>
		}
		
		function batch_del()
		{
			var r=confirm("<?php echo $lang_array["stream_channel_list_text32"] ?>");
			if(r==true)
			{
				window.location.href = "stream_channel_batch_del_list.php?serverip=<?php echo $serverip ?>";
			}
		}
		
		function find_context()
		{
			var value = document.getElementById("find_id").value;
			window.location.href = "stream_channel_live_list.php?find=" + value + "&serverip=<?php echo $serverip ?>";			
		}
		
		function add_url()
		{
			<?php
				if(isset($_GET["serverip"]))
					echo "window.location.href = \"stream_channel_add.php\" + \"?servercheck=1\" + \"&serverip=" . $_GET["serverip"] . "\";";		
				else
					echo "window.location.href = \"stream_channel_add.php\";";			
			?>
		}
		
		function export_url()
		{
			window.open("stream_channel_export_list.php?serverip=<?php echo $serverip ?>", "newwindow", "height=300, width=650, toolbar =no, menubar=no, scrollbars=no, resizable=no, location=no, status=no");//写成
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