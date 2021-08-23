<!DOCTYPE html>
<?php
	include_once "cn_lang.php";
	include_once "common.php";
	$sql = new DbSql();
	$sql->login();
	
	set_zone();
	
	$urlid = $_GET["urlid"];
	$previewid = $_GET["id"];
			
	$date = date('Y-m-d');
	$dates = explode("-",$date);
	$date_seconds = mktime(0,0,0,$dates[1],$dates[2],$dates[0]);

	$sql->connect_database_default();
	$mydb = $sql->get_database();
	$sql->create_database($mydb);
	
	$mytable = "live_preview_table";
	$sql->create_table($mydb, $mytable, "name longtext, image longtext, url longtext, password longtext, type longtext, preview longtext, id longtext, urlid smallint, hd longtext");
				
	$name = $sql->query_data($mydb, $mytable,"urlid",$urlid,"name");
	
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
                                <div class="muted pull-left"><?php echo $name ?></div>
                            </div>
                            <div class="block-content collapse in">
                                <div class="span12">
                                   <div class="table-toolbar">
                                      <div class="btn-group">
                                         <a href="timer_preview.php"><button class="btn btn-success"><?php echo $lang_array["back_text1"] ?></button></a>
                                      </div>
                                   </div>
                                    <table cellpadding="0" cellspacing="0" border="0" class="table table-bordered" >
                                        <thead>
                                            <tr>
            									<th width="13%" style='word-break:break-all;vertical-align:middle; text-align:center;'><?php echo $lang_array["timer_preview_all_text1"] ?></th>
            									<th width="77%" style='word-break:break-all;vertical-align:middle; text-align:center;'><?php echo $lang_array["timer_preview_all_text2"] ?></th>
          										<th width="10%" style='word-break:break-all;vertical-align:middle; text-align:center;'><?php echo $lang_array["timer_preview_all_text3"] ?></th>
                                            </tr>
                                        </thead>
                                        
<?php
			echo "<tbody>";
			$previewss = $sql->query_data($mydb, $mytable,"urlid",$urlid,"preview");
			$previews = explode("$#geminipreview#$",$previewss);
			
			for($ii=0; $ii<7; $ii++)
			{
				$sdate = date("Y-m-d",$date_seconds - 86400*$ii);
				$spreview = $lang_array["timer_preview_all_text5"];
				for($kk=0; $kk<count($previews); $kk++)
				{
					if(strncmp(($previews[$kk]),$sdate,strlen($sdate)) == 0)
					{
						$preview = explode("$#geminidate#$",$previews[$kk]);
						$spreview = $preview[1];	
					}
				}
				
				echo "<tbody>";
				echo "<tr>";
					echo "<td style='word-break:break-all;vertical-align:middle; text-align:center;'>".$sdate."</td>";
					echo "<td style='word-break:break-all;vertical-align:middle; text-align:center;'>".$spreview."</td>";		
					echo "<td style='word-break:break-all;vertical-align:middle; text-align:center;'><a href='#' onclick='edit_preview(\"".$sdate."\",\"".$urlid."\",\"".$previewid."\")'>" . $lang_array["timer_preview_all_text4"] . "</a>&nbsp;&nbsp;<a href='#' onclick='del_preview(\"".$sdate."\",\"".$urlid."\",\"".$previewid."\")'>" . $lang_array["del_text2"] . "</a></td>";		
				echo "</tr>";
				echo "</tbody>";
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
		
		function ReplaceAll(str, sptr, sptr1)
		{
			while (str.indexOf(sptr) >= 0){
				str = str.replace(sptr, sptr1);
			}
			return str;
		}
			 
		function edit_preview(date,urlid,previewid)
		{
			
			if(previewid.indexOf("https://www.tvsou.com") >=0)
			{
				var url = "tvsou3_preview.php?previewid=" + previewid + "&urlid=" + urlid + "&date=" + date;
				window.location.href = url;
			}
			else if(previewid.indexOf("TV_") >=0 && previewid.indexOf("Channel_") >= 0)
  			{
				//var url = "tvmao_preview.php?name=" + name + "&id=" + id;
				var url = "tvsou2_preview.php?previewid=" + previewid + "&urlid=" + urlid + "&date=" + date;
				window.location.href = url;
  			}
			else if(previewid.indexOf("tvmao") >=0)
  			{
				var url = "tvmao_preview.php?previewid=" + previewid + "&urlid=" + urlid + "&date=" + date;
				window.location.href = url;
  			}
			else if(previewid.indexOf("suntv") >=0)
  			{
				var url = "suntv_preview.php?previewid=" + previewid + "&urlid=" + urlid + "&date=" + date;
				window.location.href = url;
  			}
			else if(previewid.indexOf("olleh") >=0)
  			{
				//var url = "tvmao_preview.php?name=" + name + "&id=" + id;
				//var url = "tvolleh2_preview.php?previewid=" + previewid + "&urlid=" + urlid + "&date=" + date;
				var url = "tvolleh3_preview.php?previewid=" + previewid + "&urlid=" + urlid + "&date=" + date;
				window.location.href = url;
  			}
			else if(previewid.indexOf("yahoo") >=0 && previewid.indexOf(".xml") >=0)
			{
				var url = "yahoo_japan_preview.php?previewid=" + previewid + "&urlid=" + urlid + "&date=" + date;
				window.location.href = url;
			}
			else if(previewid.indexOf("ontvtonight") >=0)
			{
				var url = "ontvtonight_preview.php?previewid=" + previewid + "&urlid=" + urlid + "&date=" + date;
				window.location.href = url;
			}
			else if(previewid.indexOf("tvmap") >=0)
			{
				var url = "tvmap_preview.php?previewid=" + previewid + "&urlid=" + urlid + "&date=" + date;
				window.location.href = url;
			}
			else if(previewid.indexOf("mod") >=0)
			{
				var url = "mod_preview.php?previewid=" + previewid + "&urlid=" + urlid + "&date=" + date;
				window.location.href = url;
			}
			else if(previewid.indexOf("meuguia") >=0)
			{
				var url = "meuguia_preview.php?previewid=" + previewid + "&urlid=" + urlid + "&date=" + date;
				window.location.href = url;
			}
			else if(previewid.length >=0)
			{
				var url = "tvmao2_preview.php?previewid=" + previewid + "&urlid=" + urlid + "&date=" + date;
				//alert(url);
				window.location.href = url;		
			}
		}
		
		function del_preview(date,urlid,previewid)
		{
			var r=confirm("<?php echo $lang_array["timer_preview_all_text6"] ?>" + "?");
			if(r==true)
  			{
				var url = "timer_preview_all_del.php?date=" + date + "&urlid=" + urlid + "&previewid=" + previewid;
				window.location.href = url;
  			}
			
		}
        </script>
    </body>

</html>