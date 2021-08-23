<!DOCTYPE html>
<?php
	include_once "cn_lang.php";
	include_once "common.php";
	$sql = new DbSql();
	$sql->login_proxy();

	$level = 2;
	
	$sql->connect_database_default();
	$mydb = $sql->get_database();
	$sql->create_database($mydb);

  	set_zone();
  	$nowtime = time();
  	$rq = date("Y-m-d",$nowtime);

	$starttime = date("Y-m-d",strtotime("-1 month"));
	$endtime = $rq;
	
	if(isset($_GET["starttime"]))
	{
		$starttime = $_GET["starttime"];
	}
	
	if(isset($_GET["endtime"]))
	{
		$endtime = $_GET["endtime"];
	}

	//echo $starttime;
	//echo $endtime;
	
	$mytable = "playlist_type_table";
	$sql->create_table($mydb, $mytable, "name text, space longtext, id text, playlist longtext");
	$type_namess = $sql->fetch_datas($mydb, $mytable);
	foreach($type_namess as $type_names)
	{
		$type_array[$type_names[2]] = $type_names[0];
	}
	

	$mytable = "custom_table";
	$sql->create_table($mydb, $mytable, 
		"mac text,cpu text,ip text,space text, date text,
		time text,allow text, playlist text, online text, allocation text,
		proxy text, balance float,showtime text,contact text,member text,
		panal text,number text,ips text, onescrolltext text, onescrolltexttimes int, 
		numberdate int, scrollcontent text, scrolldate text, scrolltimes text, controlurl text, 		
		controltime int, unbundling int, accessdate timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP");

	$namess = $sql->fetch_datas_where($mydb, $mytable, "proxy", $_GET["proxy"]);
	
	
?>


<?
function dayToday($start,$end)
{
	$startdate=strtotime($start);
	$enddate=strtotime($end);
	$days=round(($enddate-$startdate)/86400)+1;
	if($days < 0)
		$days = 0;
		
	return $days;		
}

function intoDay($start,$end)
{
	$startdate=strtotime($start);
	$enddate=strtotime($end);
	$days=$enddate-$startdate;
	if($days <= 0)
	{
		return 0;
	}
	else
	{
		return 1;		
	}
}

function find_push_col($arrays, $col)
{
	foreach($arrays as $array)
	{
		//echo "array:" . $col;
		if(strcmp($array,$col) == 0)
			return 1;
	}
	
	//echo "col:" . $col;
	//array_push($arrays,$col);
	return 0;
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
    
    <body onLoad="createSelect(1)">
        <div class="container-fluid">
            <div class="row-fluid">
              <div class="span13" id="content">
                <div class="row-fluid">
                   <!-- block -->
						<div class="block">
                            <div class="navbar navbar-inner block-header">
                                <div class="muted pull-left"><?php echo $lang_array["proxy_statistics_text9"] . " " . $_GET["proxy"] ?></div>
                            </div>
                            <div class="block-content collapse in">
                                <div class="span12"><div class="table-toolbar">
                                    <div class="btn-group">
                                     
                                    </div>
                                    
									<form class="form-horizontal" name="authform" method="post" action="" enctype='multipart/form-data'>
                                    
                                    <div class="control-group" style="background-color:#CCC">
                                    	<br/>
                                        
                                        <div class="controls">
										开始时间:&nbsp;&nbsp;<input type="text" id="starttime" onfocus="MyCalendar.SetDate(this)" value="<?php echo $starttime;?>">
                                        &nbsp;&nbsp;&nbsp;&nbsp;
										结束时间:&nbsp;&nbsp;<input type="text" id="endtime" onfocus="MyCalendar.SetDate(this)" value="<?php echo $endtime;?>">
                                        &nbsp;&nbsp;&nbsp;&nbsp;
                                        <button type="button" class="btn btn-primary" onclick="statistiocs()"><?php echo $lang_array["proxy_statistics_text8"] ?></button>
                                        </div> 
                                        
                                        <br/>              
                                    </div>
                                    
                                     
                                    <table cellpadding="0" cellspacing="0" border="0" class="table table-striped table-bordered" style="table-layout:fixed;">
                                        <thead>
                                            <tr>
            									<th width="25%" style="vertical-align:middle; text-align:center;"><?php echo $lang_array["proxy_statistics_text3"] ?></th>
          										<th width="25%" style="vertical-align:middle; text-align:center;"><?php echo $lang_array["proxy_statistics_text4"] ?></th>
            									<th width="25%" style="vertical-align:middle; text-align:center;"><?php echo $lang_array["proxy_statistics_text5"] ?></th>
            									
                                            </tr>
                                        </thead>
                                        
<?php
			echo "<tbody>";
			
			{
				echo "<tr class='odd gradeA'>";
				echo "<td style='vertical-align:middle; text-align:center;word-wrap:break-word;'>" . "全部分配" . "</td>";
				$day_names = array();
				$time_names = array();
				$namess = $sql->fetch_datas_where_2($mydb, $mytable, "proxy", $_GET["proxy"], "playlist", "all");
				foreach($namess as $names)
				{
					
					if((intoDay($starttime,$names[4]) == 0) || (intoDay($endtime,$names[4]) == 1))
						continue;
						
					if(strcmp($names[5],"-1") == 0)
					{
						if(find_push_col($day_names,"-1") ==0)
						{
							array_push($day_names,"-1");	
							$time_names["-1"] = 1;
						}
						else
						{
							$time_names["-1"]++;
						}
					}
					else 
					{
						$day = dayToday($names[4],$names[5]);
						//echo $day . "#";
						if(find_push_col($day_names,strval($day)) == 0)
						{
							array_push($day_names,strval($day));	
							$time_names[strval($day)] = 1;
						}
						else
						{
							$time_names[strval($day)]++;
						}
					}		
				}
				$t = "";
				$total = 0;
				foreach($day_names as $day_name)
				{
					if(strcmp($day_name,"-1") == 0)
						$t = $t . "时长:" . "永久" . "   " . "数量：" . $time_names[$day_name] . "&nbsp;&nbsp;&nbsp;&nbsp;<a href='proxy_statistics_check_list.php?proxy=" . $_GET["proxy"] . "&day=-1&playlist=all&starttime=" . $starttime . "&endtime=" . $endtime . "'>查看</a><br/>";
					else
						$t = $t . "时长:" . $day_name . "天" . "   " . "数量：" . $time_names[$day_name] . "&nbsp;&nbsp;&nbsp;&nbsp;<a href='proxy_children_statistics_check_list.php?proxy=" . $_GET["proxy"] . "&day=" . $day_name . "&playlist=all&starttime=" . $starttime . "&endtime=" . $endtime . "&level=" . $level . "'>查看</a><br/>";
					$total = $total + intval($time_names[$day_name]);
				}
					
				echo "<td style='vertical-align:middle; text-align:center;word-wrap:break-word;'>" . $t . "</td>";
				echo "<td style='vertical-align:middle; text-align:center;word-wrap:break-word;'>" . "总数:"  . $total . "</td>";
					
				echo "</tr>";
			}
			
			{
				echo "<tr class='odd gradeA'>";
				echo "<td style='vertical-align:middle; text-align:center;word-wrap:break-word;'>" . "自动分配" . "</td>";
				$day_names = array();
				$time_names = array();
				$namess = $sql->fetch_datas_where_2($mydb, $mytable, "proxy", $_GET["proxy"], "playlist", "auto");
				foreach($namess as $names)
				{
					if((intoDay($starttime,$names[4]) == 0) || (intoDay($endtime,$names[4]) == 1))
						continue;
						
					if(strcmp($names[5],"-1") == 0)
					{
						if(find_push_col($day_names,"-1") ==0)
						{
							array_push($day_names,"-1");	
							$time_names["-1"] = 1;
						}
						else
						{
							$time_names["-1"]++;
						}
					}
					else 
					{
						$day = dayToday($names[4],$names[5]);
						//echo $day . "#";
						if(find_push_col($day_names,strval($day)) == 0)
						{
							array_push($day_names,strval($day));	
							$time_names[strval($day)] = 1;
						}
						else
						{
							$time_names[strval($day)]++;
						}
					}	
				}
				
				$t = "";
				$total = 0;
				foreach($day_names as $day_name)
				{
					if(strcmp($day_name,"-1") == 0)
						$t = $t . "时长:" . "永久" . "   " . "数量：" . $time_names[$day_name] . "&nbsp;&nbsp;&nbsp;&nbsp;<a href='proxy_statistics_check_list.php?proxy=" . $_GET["proxy"] . "&day=-1&playlist=auto&starttime=" . $starttime . "&endtime=" . $endtime . "'>查看</a><br/>";
					else
						$t = $t . "时长:" . $day_name . "天" . "   " . "数量：" . $time_names[$day_name] . "&nbsp;&nbsp;&nbsp;&nbsp;<a href='proxy_children_statistics_check_list.php?proxy=" . $_GET["proxy"] . "&day=" . $day_name . "&playlist=auto&starttime=" . $starttime . "&endtime=" . $endtime . "&level=" . $endtime . "'>查看</a><br/>";
					$total = $total + intval($time_names[$day_name]);
				}
					
				echo "<td style='vertical-align:middle; text-align:center;word-wrap:break-word;'>" . $t . "</td>";
				echo "<td style='vertical-align:middle; text-align:center;word-wrap:break-word;'>" . "总数:"  . $total . "</td>";
				echo "</tr>";
			}			
			
			
			
			
			foreach($type_namess as $type_names)
			{
					echo "<tr class='odd gradeA'>";
					echo "<td style='vertical-align:middle; text-align:center;word-wrap:break-word;'>" . $type_names[0] . "</td>";
					
					
					$day_names = array();
					$time_names = array();
					$namess = $sql->fetch_datas_where_2($mydb, $mytable, "proxy", $_GET["proxy"], "playlist", $type_names[2]);
					foreach($namess as $names)
					{
						if((intoDay($starttime,$names[4]) == 0) || (intoDay($endtime,$names[4]) == 1)) 
							continue;
						
						if(strcmp($names[5],"-1") == 0)
						{
							if(find_push_col($day_names,"-1") ==0)
							{
								array_push($day_names,"-1");	
								$time_names["-1"] = 1;
							}
							else
							{
								$time_names["-1"]++;
							}
						}
						else 
						{
							$day = dayToday($names[4],$names[5]);
							//echo $day . "#";
							if(find_push_col($day_names,strval($day)) == 0)
							{
								array_push($day_names,strval($day));	
								$time_names[strval($day)] = 1;
							}
							else
							{
								$time_names[strval($day)]++;
							}
						}
					}

					$t = "";
					$total = 0;
					foreach($day_names as $day_name)
					{					
						if(strcmp($day_name,"-1") == 0)
							$t = $t . "时长:" . "永久" . "   " . "数量：" . $time_names[$day_name] . "&nbsp;&nbsp;&nbsp;&nbsp;<a href='proxy_statistics_check_list.php?proxy=" . $_GET["proxy"] . "&day=-1&playlist=" . $type_names[2] . "&starttime=" . $starttime . "&endtime=" . $endtime . "'>查看</a><br/>";
						else
							$t = $t . "时长:" . $day_name . "天" . "   " . "数量：" . $time_names[$day_name] . "&nbsp;&nbsp;&nbsp;&nbsp;<a href='proxy_children_statistics_check_list.php?proxy=" . $_GET["proxy"] . "&day=" . $day_name . "&playlist=" . $type_names[2] . "&starttime=" . $starttime . "&endtime=" . $endtime . "'>查看</a><br/>";
						$total = $total + intval($time_names[$day_name]);
					}
					
					echo "<td style='vertical-align:middle; text-align:center;word-wrap:break-word;'>" . $t . "</td>";
					echo "<td style='vertical-align:middle; text-align:center;word-wrap:break-word;'>" . "总数:"  . $total . "</td>";
				echo "</tr>";
			}
			echo "</tbody>";
?>                                       
          							</table>
                                    
                                    <div class="form-actions">
                                		<button type="reset" class="btn btn-primary" onclick="back_page()"><?php echo $lang_array["back_text1"] ?></button>
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
        <script language="JavaScript" src="assets/mydate.js"></script>
        
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

		function statistiocs()
		{
			var starttime = document.getElementById("starttime").value;
			var endtime = document.getElementById("endtime").value;
			
			window.location.href = "proxy_children_statistics.php?starttime="+starttime+"&endtime="+endtime+"&proxy="+"<?php echo $_GET["proxy"]?>"+"&level=<?php echo $level ?>";
		}
		
		function back_page()
		{
			window.location.href = "proxy_children_list.php?level=<?php echo $level ?>";
		}
		
        </script>
    </body>
    
<?php
	$sql->disconnect_database();
?>
</html>