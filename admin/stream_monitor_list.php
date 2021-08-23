<!DOCTYPE html>
<?php
	include_once "cn_lang.php";
	include_once "common.php";
	$sql = new DbSql();
	$sql->login();

	$sql->connect_database_default();
	$mydb = $sql->get_database();
	$sql->create_database($mydb);
	

	$mytable = "stream_server_list_table";
	$sql->create_table($mydb, $mytable, "serverid int, serverip text, tip text, os text, version int, info text, online int, channelcount int");	
	$serverss = $sql->fetch_datas($mydb, $mytable);
	
	$namess = array();
		
	$serverip = "";
	if(count($serverss) > 0)
		$serverip = $serverss[0][1];
	
	if(isset($_GET["serverip"]))
		$serverip = trim($_GET["serverip"]);
		
	//foreach($serverss as $servers)
	if(strlen($serverip) >= 7)
	{
		$ret = "";
		$xmlfile = "xml/getdirinfo.xml";
		if(file_exists($xmlfile))
		{
			$ret = readXML($file);
			if(strlen($ret) < 32 || strstr($ret,"?xml") == false || strstr($ret,"version=") == false)
				$ret = "";
		}
		
		
		if(strlen($ret) < 32 || strstr($ret,"?xml") == false || strstr($ret,"version=") == false)
		{
			$cmd = "getdirinfo";
			$ret = send($serverip,$cmd);
			if(strstr($ret,"?xml") != false && strstr($ret,"version=") != false)
			{
				saveXML("xml/","getdirinfo.xml",$ret);
			}
		}
		
		
		if(strstr($ret,"?xml") != false && strstr($ret,"version=") != false)
		{
			$doc = new DOMDocument();
			$doc->loadXML($ret);
			$channels = $doc->getElementsByTagName("status");
			$index = 0;
			foreach($channels as $channel){
				$dirname = "";
				$dirreceive = "";
				$dironline = "";
				$dirpath = "";
				$dircurrent = "";
				
				$names = array();
				
				if($channel->getElementsByTagName("name") != false)
					$dirname = $channel->getElementsByTagName("name")->item(0)->nodeValue;
					
				if($channel->getElementsByTagName("receive") != false)
					$dirreceive = $channel->getElementsByTagName("receive")->item(0)->nodeValue;
					
				if($channel->getElementsByTagName("online") != false)
					$dironline = $channel->getElementsByTagName("online")->item(0)->nodeValue;
					
				if($channel->getElementsByTagName("dir") != false)	
					$dirpath = $channel->getElementsByTagName("dir")->item(0)->nodeValue;

				if($channel->getElementsByTagName("current") != false)	
					$dircurrent = $channel->getElementsByTagName("current")->item(0)->nodeValue;
					
				array_push($names,$dirname);
				array_push($names,$dirreceive);
				array_push($names,$dironline);
				array_push($names,$dirpath);
				array_push($names,$serverip);
				array_push($names,$dircurrent);
				
				array_push($namess,$names);
			}
		}
	}

	//$headInf = get_headers("http://198.255.14.26:23457/NBA/sample.jpg",1);   
	//echo date("Y-m-d H:i:s",strtotime($headInf['Last-Modified']));

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
					<div class="row-fluid">
                        <!-- block -->
                        <div class="block">
                            <div class="navbar navbar-inner block-header">
                                <div class="muted pull-left"><?php echo $lang_array["stream_monitor_list_text1"] ?></div>
                            </div>
							<div>
								<br/>&nbsp;&nbsp;&nbsp;&nbsp;
							    <?php echo $lang_array["stream_dashboard_list_text19"] ?>
                            	<select id="server_select_id" name="" onChange="select_onchange()">
                                <?php
									/*
									if($serverip == "all")
										echo "<option value='all'>" . $lang_array["stream_dashboard_list_text20"] . "</option>";	
									else
										echo "<option value='all' selected='selected'>" . $lang_array["stream_dashboard_list_text20"] . "</option>";	
									*/
									foreach($serverss as $servers)
									{
										$selected = "";
										if($serverip == $servers[1])
											$selected = "selected='selected'";
										
										if(strlen($servers[2]) >= 1)
											echo "<option value='" . $servers[1] . "' " . $selected . ">" . $servers[1] . "(".$servers[2] . ")". "</option>";
										else
											echo "<option value='" . $servers[1] . "' " . $selected . ">" . $servers[1] . "</option>";	
									}
								?>
                                </select>
                            </div>
                            <div class="block-content collapse in">
                                <?php
								  $index = 0;
								  foreach($namess as $names)
								  {
									
									if(intval($names[1]) < 145000000)
										continue;
										
									if($index%5 == 0)
										echo "<div class='row-fluid padd-bottom'>";
										
									$img = "http://" . $names[4] . ":23457/" . $names[0] . "/sample.jpg";
									//$headInf = get_headers($img,1); 
									$headInf = "";
									$date = date("m-d H:i:s",strtotime($headInf['Last-Modified']));
                                  	echo "<div class=\"span3\" style='width:200px;height:150px;'>";
                                    echo "<a href=\"#\" class=\"thumbnail\" onClick='reload_img(\"" . md5($names[3].$names[0]) . "\",\"" . $img . "\")'>";
									
                                    //echo "<img data-src=\"NO DATA\" alt=\"260x180\" style=\"width: 260px; height: 180px;\" src=\"" . $img . "\">";
									if(intval($names[5])-intval($names[1])*10 < 1000)
									{
										echo "<span style='font-size:18px'><div style='position:relative;'><img class='lazy' alt='260x180' style='font-size:18px;width:190px;height:120px;' id=" . md5($names[3].$names[0]). " data-original='" . $img . "' src='images/timg.jpg' /><div style='background:#000; color:#FFF; position:absolute; z-index:2; left:10px; top:20px;'>" . $names[0] . "</div></div></span>";
									}
									else
									{
										echo "<span style='font-size:18px'><div style='position:relative;'><img class='lazy' alt='260x180' style='font-size:18px;width:190px;height:120px;' id=" . md5($names[3].$names[0]). " data-original='' src='images/timg.jpg' /><div style='background:#000; color:#FFF; position:absolute; z-index:2; left:10px; top:20px;'>". $names[0] ."</div></div></span>";
									}
                                    echo "</a>";
									echo "<div class=\"pull-right\" onClick='reload_img(\"" . md5($names[3].$names[0]) . "\",\"" . $img . "\")'><span class=\"badge badge-info\">" . $lang_array["stream_monitor_list_text2"] . "</span></div>";
									if($index%5 == 0)
										echo "</div>";
								  }
								  
                                ?>   
                                
                            </div>
                        </div>       
                        <!-- /block -->
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
        <script src="scripts/jquery.lazyload.min.js" type="text/javascript"></script>
        <script>
		$(function() {
			$("img.lazy").lazyload({effect: "fadeIn",threshold : 200});
		});
	
		function reload_img(id,src)
		{
			document.getElementById(id).src = src + "?t=" +Math.random();
		}
		
		function select_onchange()
		{
			var select_value = document.getElementById("server_select_id").value;
			window.location.href = "stream_monitor_list.php?serverip=" + select_value;
		}
        </script>
    </body>
</html>