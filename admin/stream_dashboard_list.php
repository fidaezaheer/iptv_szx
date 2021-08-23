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
	
	$mytable = "stream_dashboard_table";
	$sql->create_table($mydb, $mytable, "id text, name text, serverip text, run text, receive int, online text, onlinedate datetime, dir text, current text");
	
	$sql->delete_date_little($mydb, $mytable, "date", date('Y-m-d H:i:s',strtotime('-1 day'))); 
	
	$serverip = "all";
	if(isset($_GET["serverip"]))
		$serverip = $_GET["serverip"];
		
	$name = "all";
	if(isset($_GET["name"]))
		$name = $_GET["name"];
		
	$page = 0;
	$size = 100;
	if(isset($_GET["page"]))
		$page = intval($_GET["page"]);

		
	if($name == "all" && $serverip == "all")
		$namess = $sql->fetch_datas_where_like_2($mydb, $mytable,"onlinedate",date("Y-m-d"),"id","serverstotalonline");
	else if($name == "all" && $serverip != "all")	
		$namess = $sql->fetch_datas_where_like_2_and($mydb, $mytable,"onlinedate",date("Y-m-d"),"id","totalonline","serverip",$serverip);
	else
		$namess = $sql->fetch_datas_where_like_2_and($mydb, $mytable,"onlinedate",date("Y-m-d"),"name",$name,"serverip",$serverip);	

			
	$channelss = array();		
	$rowss = $sql->fetch_datas_where_like_desc($mydb, $mytable,"onlinedate",date("Y-m-d"),"onlinedate");
	foreach($rowss as $rows) 
	{
		$exist = 0;
		foreach($channelss as $channels)
		{
			if($channels[0] == $rows[0])	
			{
				$exist = 1;
				break;
			}
		}
		
		if($exist == 0)
		{
			if($rows[1] != "totalonline" && $rows[1] != "serverstotalonline")
				array_push($channelss,$rows);	
		}
		
	}
	
	
	$allpage = count($channelss)/$size;
	if(count($channelss)%$size > 0)
		$allpage = $allpage + 1;
?>


<html>
    <head>
        <title></title>
		<meta http-equiv="Content-Type" content="text/html;charset=utf-8">
        <!-- Bootstrap -->
        <link href="bootstrap/css/bootstrap.min.css" rel="stylesheet" media="screen">
        <link href="bootstrap/css/bootstrap-responsive.min.css" rel="stylesheet" media="screen">
        <link href="assets/styles.css" rel="stylesheet" media="screen">
        <!--[if lte IE 8]><script language="javascript" type="text/javascript" src="vendors/flot/excanvas.min.js"></script><![endif]-->
        <!-- HTML5 shim, for IE6-8 support of HTML5 elements -->
        <!--[if lt IE 9]>
            <script src="http://html5shim.googlecode.com/svn/trunk/html5.js"></script>
        <![endif]-->
        <script src="vendors/modernizr-2.6.2-respond-1.1.0.min.js"></script>
    </head>
    
    <body onLoad="init_load()">
		<div class="container-fluid">
            <div class="row-fluid">
              <div class="span13" id="content">
                <div class="row-fluid">
                   <!-- block -->
						<div class="block">
                            <div class="navbar navbar-inner block-header">
                                <div class="muted pull-left"><?php echo $lang_array["stream_dashboard_list_text1"] ?></div>
                                <div class="pull-right"><span class="badge badge-warning"></span>
									
                                </div>
                            </div>
                            <div class="block-content collapse in">
                            	
                                <div class="span12">
                                <?php echo $lang_array["stream_dashboard_list_text19"] ?>
                            	<select id="server_select_id" name="" onChange="select_onchange()">
                                <?php
									$selected = "";
									if($serverss[1] == "all")
										$selected = "selected='selected'";
									echo "<option value='all' " . $selected . ">" . $lang_array["stream_dashboard_list_text20"] . "</option>";	
									foreach($serverss as $serverss)
									{
										$selected = "";
										if($serverss[1] == $serverip)
											$selected = "selected='selected'";
										if(strlen($serverss[2]) >= 1)
											echo "<option value='" . $serverss[1] . "' " . $selected . ">" . $serverss[1] . "(".$serverss[2] . ")". "</option>";
										else
											echo "<option value='" . $serverss[1] . "' ". $selected . ">" . $serverss[1] . "</option>";	
									}
								?>
                                </select>
                                	
									&nbsp&nbsp&nbsp&nbsp&nbsp
									<?php echo $lang_array["stream_dashboard_list_text21"] ?>:<?php echo $name ?>
                                    &nbsp&nbsp&nbsp&nbsp&nbsp
                                    <div class="btn-group">
                                      	<input class="btn btn-success" name="" type="button" value="<?php echo $lang_array["stream_dashboard_list_text26"] ?>" onclick="clean_cache()"/>&nbsp;
                                    </div>
                                    
                                    <div id="hero-area" style="height: 250px;"></div>
                                    
                                    
                                </div>
                            </div>
                            <div class="block-content collapse in">
                                <div class="span12">
                                   
                        			<table cellpadding="0" cellspacing="0" border="0" class="table table-striped table-bordered" id="example2">
                            	 		<thead>
                                 			<tr>
            								<th width="5%" style="vertical-align:middle; text-align:center;"><?php echo $lang_array["stream_dashboard_list_text9"] ?></th>
                                            <th width="5%" style="vertical-align:middle; text-align:center;"><?php echo $lang_array["stream_dashboard_list_text22"] ?></th>
                                    		<th width="10%" style="vertical-align:middle; text-align:center;"><?php echo $lang_array["stream_dashboard_list_text4"] ?></th>
                                        	<th width="10%" style="vertical-align:middle; text-align:center;"><?php echo $lang_array["stream_dashboard_list_text12"] ?></th>
                                    		<th width="5%" style="vertical-align:middle; text-align:center;"><?php echo $lang_array["stream_dashboard_list_text5"] ?></th>
            								<th width="10%" style="vertical-align:middle; text-align:center;"><?php echo $lang_array["stream_dashboard_list_text6"] ?></th>
          									<th width="10%" style="vertical-align:middle; text-align:center;"><?php echo $lang_array["stream_dashboard_list_text7"] ?></th>
                                        	<th width="10%" style="vertical-align:middle; text-align:center;"><?php echo $lang_array["stream_dashboard_list_text8"] ?></th>
                                        	<th width="8%" style="vertical-align:middle; text-align:center;"><?php echo $lang_array["stream_dashboard_list_text10"] ?></th>
                                            
                                			</tr>
                            			</thead>

                                		<tbody>
                                <?php
                                	$ii = 0;
                               	 	foreach($channelss as $channels) 
									{
										if($ii >= $page*$size && $ii < ($page+1)*$size)
										{
                                		echo "<tr>";
  											echo "<td style='align:center; vertical-align:middle; text-align:center;'>" . $ii . "</td>";
											
											if(intval($channels[4]) < 145000000)
												echo "<td style='align:center; vertical-align:middle; text-align:center;'>" . $lang_array["stream_dashboard_list_text24"] . "</td>";
											else
												echo "<td style='align:center; vertical-align:middle; text-align:center;'>" . $lang_array["stream_dashboard_list_text23"] . "</td>";
												
                                        	echo "<td style='align:center; vertical-align:middle; text-align:center;'>" . $channels[1] . "</td>";
											echo "<td style='align:center; vertical-align:middle; text-align:center;'>" . $channels[2] . "</td>";
											if(intval($channels[8])-intval($channels[4])*10 > 1000 && intval($channels[4]) > 145000000)
												$img = "<img src=\"images/stop.png\" width=\"36\" height=\"36\">";
											else
												$img = "<img src=\"images/run.png\" width=\"36\" height=\"36\">";
												
											echo "<td style='align:center; vertical-align:middle; text-align:center;'>" . $img . "</td>";
											echo "<td style='align:center; vertical-align:middle; text-align:center;'>" . $channels[5] . "</td>";
											echo "<td style='align:center; vertical-align:middle; text-align:center;'>" . date("Y-m-d H:i:s",intval($channels[4])*10) . "</td>";
											$path = "0";
											if(strstr($channels[7],"/home/gemini/gserver0/") != false)
											{
												$path = $lang_array["stream_dashboard_list_text13"];
											}
											else if(strstr($channels[7],"/home/gemini/gserver1/") != false)
											{
												$path = $lang_array["stream_dashboard_list_text14"];
											}
											else if(strstr($channels[7],"/home/gemini/gserver2/") != false)
											{
												$path = $lang_array["stream_dashboard_list_text15"];
											}
											else if(strstr($channels[7],"/home/gemini/gserver3/") != false)
											{
												$path = $lang_array["stream_dashboard_list_text16"];
											}
											else if(strstr($channels[7],"/home/gemini/gserver4/") != false)
											{
												$path = $lang_array["stream_dashboard_list_text17"];
											}
											else if(strstr($channels[7],"/tmp/gemini/gserver/") != false)
											{
												$path = $lang_array["stream_dashboard_list_text18"];
											}
											echo "<td style='align:center; vertical-align:middle; text-align:center;'>" . $path . "</td>";
											echo "<td style='align:center; vertical-align:middle; text-align:center;'>" . "<a href='#' onclick='loadone(\"".$channels[1]."\",\"".$channels[2]."\")'>".$lang_array["stream_dashboard_list_text11"]."</a>" . "</td>";
										echo "</tr>";
										}
										$ii++;
									}
								?>
                                	</tbody>
                        		</table>
                                
                                <div class="pagination">
									<ul>
                                    <?php
										/*
										$pagelen = 2;
										if($allpage <= $pagelen)
										{
											for($ii=0; $ii<$allpage; $ii++)
											{
												echo "<li><a href='stream_dashboard_list.php?page=" . $ii . "'>" . $ii . "</a></li>";
											}
											
										}
										else if($allpage > $pagelen)
										{
											if($page + ($pagelen-1) >= $allpage)
											{
												for($ii=$allpage-$pagelen; $ii<$allpage; $ii++)
												{
													echo "<li><a href='stream_dashboard_list.php?page=" . $ii . "'>" . $ii . "</a></li>";
												}
												
											}
											else
											{
												for($ii=$page; $ii<$page+$pagelen; $ii++)
												{
													echo "<li><a href='stream_dashboard_list.php?page=" . $ii . "'>" . $ii . "</a></li>";
												}
											}
										}
										*/
										for($ii=0; $ii<$allpage; $ii++)
										{
											if($page == $ii)
												echo "<li class='active'><a href='stream_dashboard_list.php?page=" . $ii . "'>" . $ii . "</a></li>";
											else
												echo "<li><a href='stream_dashboard_list.php?page=" . $ii . "'>" . $ii . "</a></li>";
										}
									?>
									</ul>
								</div>
                                
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>                                          
                                       
        <!--/.fluid-container-->
        <link rel="stylesheet" href="vendors/morris/morris.css">


        <script src="vendors/jquery-1.9.1.min.js"></script>
        <script src="vendors/jquery.knob.js"></script>
        <script src="vendors/raphael-min.js"></script>
        <script src="vendors/morris/morris.min.js"></script>

        <script src="bootstrap/js/bootstrap.min.js"></script>
        <script src="vendors/flot/jquery.flot.js"></script>
        <script src="vendors/flot/jquery.flot.categories.js"></script>
        <script src="vendors/flot/jquery.flot.pie.js"></script>
        <script src="vendors/flot/jquery.flot.time.js"></script>
        <script src="vendors/flot/jquery.flot.stack.js"></script>
        <script src="vendors/flot/jquery.flot.resize.js"></script>

        <script src="assets/scripts.js"></script>
        <script>
		
		function loadflash()
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
				//alert(xmlhttp.status);
				if(xmlhttp.readyState==4 && xmlhttp.status==200)
				{
				}
			}
			xmlhttp.open("GET","stream_dashboard_flash.php",true);
			xmlhttp.send();
		}
		
		
		function loadclean()
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
				//alert(xmlhttp.status);
				if(xmlhttp.readyState==4 && xmlhttp.status==200)
				{
					if(xmlhttp.responseText.indexOf("OK") >= 0)
					{
						alert("<?php echo $lang_array["stream_server_list_text23"] ?>");	
					}
					else
					{
						alert("<?php echo $lang_array["stream_server_list_text24"] ?>");
					}
				}
			}
			xmlhttp.open("GET","stream_dashboard_clean.php",true);
			xmlhttp.send();
		}
		
		
		function init_load()
		{
			loadflash();
			
			var timer = setInterval(function(){ 
        		loadflash();
			}, 30*60000);
		}
		
		function select_onchange()
		{
			var select_value = document.getElementById("server_select_id").value;
			window.location.href = "stream_dashboard_list.php?serverip="+select_value;
		}
		
		function loadone(name,serverip)
		{
			window.location.href = "stream_dashboard_list.php?serverip="+serverip+"&name="+name;
		}
		
		function clean_cache()
		{
			loadclean();
		}
        // Morris Area Chart
        Morris.Area({
            element: 'hero-area',
            data: [
			<?php
				for($ii=0; $ii<count($namess); $ii++)
				{
					echo "{period: '" . $namess[$ii][6] . "', " . $lang_array["stream_dashboard_list_text2"]. ": " . $namess[$ii][5]. "}";       
					if($ii < count($namess)-1)
						echo ",";
				}
			?>
			],
            xkey: 'period',
            ykeys: ['<?php echo $lang_array["stream_dashboard_list_text2"] ?>'],
            labels: ['<?php echo $lang_array["stream_dashboard_list_text2"] ?>'],
            lineWidth: 2,
            hideHover: 'auto',
            lineColors: ["#81d5d9"]
          });

        </script>
    </body>

</html>