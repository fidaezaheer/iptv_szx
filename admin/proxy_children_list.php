<!DOCTYPE html>
<?php
include_once "cn_lang.php";
include_once "common.php";
$sql = new DbSql();
$sql->login_proxy();
$level = 2;
?>


<?php
function dayToday($time)
{
	$startdate=strtotime(date("Y-m-d"));
	$enddate=strtotime($time);
	$days=round(($enddate-$startdate)/86400)+1;
	if($days < 0)
		$days = 0;
		
	return $days;	
	
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
                                <?php
									if($level == 1)
                                		echo "<div class=\"muted pull-left\">" . $lang_array["left1_text2"] . "</div>";
									else
										echo "<div class=\"muted pull-left\">" . $lang_array["proxy_list_text23"] . "</div>";
                                ?>
                            </div>
                            <div class="block-content collapse in">
                                <div class="span12">
                                   <div class="table-toolbar">
                                      <div class="btn-group">
                                         <a href="proxy_children_add.php"><button class="btn btn-success"><?php echo $lang_array["proxy_list_text3"] ?><i class="icon-plus icon-white"></i></button></a>
                                      </div>
                                   </div>
                                    
                                    <table cellpadding="0" cellspacing="0" border="0" class="table table-striped table-bordered" id="example2">
                                        <thead>
                                            <tr>
            								<th style='vertical-align:middle; text-align:center;word-wrap:break-word;' width="8%"><?php echo $lang_array["proxy_list_text12"] ?></th>
            								<th style='vertical-align:middle; text-align:center;word-wrap:break-word;' width="6%"><?php echo $lang_array["proxy_list_text13"] ?></th>
            								<th style='vertical-align:middle; text-align:center;word-wrap:break-word;' width="5%"><?php echo $lang_array["proxy_list_text14"] ?></th>
            								<th style='vertical-align:middle; text-align:center;word-wrap:break-word;' width="5%"><?php echo $lang_array["proxy_list_text15"] ?></th>	
                                            <th style='vertical-align:middle; text-align:center;word-wrap:break-word;' width="5%"><?php echo $lang_array["proxy_list_text16"] ?></th>
                                            <th style='vertical-align:middle; text-align:center;word-wrap:break-word;' width="5%"><?php echo $lang_array["proxy_list_text17"] ?></th>
                                            <th style='vertical-align:middle; text-align:center;word-wrap:break-word;' width="8%"><?php echo $lang_array["proxy_list_text18"] ?></th>	
                                            <th style='vertical-align:middle; text-align:center;word-wrap:break-word;' width="6%"><?php echo $lang_array["proxy_list_text24"] ?></th>		
          									<th style='vertical-align:middle; text-align:center;word-wrap:break-word;' width="10%"><?php echo $lang_array["proxy_list_text19"] ?></th>
                                            </tr>
                                        </thead>
                                        
<?php

			$sql->connect_database_default();
			$mydb = $sql->get_database();
			$sql->create_database($mydb);
			$mytable = "proxy_table";
			$sql->create_table($mydb, $mytable, "name text, password text, ptip text, edit int, watermark text, validity date, allow int, remark text, ccount int, panal int, pwmd5 int, proxylevel int, proxybelong text");				
				
			$namess = array();
			$namess = $sql->fetch_datas_where_2($mydb, $mytable, "proxylevel", $level,"proxybelong",$_COOKIE["user"]);
				
			foreach($namess as $names) {
				if(strlen($names[5]) <= 0)
				{
					$sql->update_data_2($mydb, $mytable,"name",$names[0],"allow",-1);
				}
				else if(dayToday($names[5]) <= 0)
				{
					$sql->update_data_2($mydb, $mytable,"name",$names[0],"allow",0);
				}
			}
			
			$namess = $sql->fetch_datas_where_2($mydb, $mytable, "proxylevel", $level,"proxybelong",$_COOKIE["user"]);
			
			$mytable = "proxy_download_table";
			$sql->create_table($mydb, $mytable, "name text, sbackground text, sepg text, ebackground text, livepanel text, vodpanel text, download text, allow text, watermark text, scrolltext text, version int");
			
			if($sql->find_column($mydb, $mytable, "watermark") == 0)
				$sql->add_column($mydb, $mytable,"watermark", "text");
				
			echo "<tbody>";
			foreach($namess as $names) {
				echo "<tr class='odd gradeA'>";
				$key = $names[0];
				
				echo "<td style='vertical-align:middle; text-align:center;word-wrap:break-word;'>".$names[0]."</td>";
				
				if($names[10] == 0)
					echo "<td style='vertical-align:middle; text-align:center;word-wrap:break-word;'>".$names[1]."</td>";
				else
					echo "<td style='vertical-align:middle; text-align:center;word-wrap:break-word;'>******</td>";
					
				$download = $sql->query_data($mydb, $mytable,  "name", $names[0],"download");
				$allow = $sql->query_data($mydb, $mytable, "name", $names[0], "allow");
					
				if($names[3] == 1)
					echo "<td style='vertical-align:middle; text-align:center;word-wrap:break-word;'>" . $lang_array["yes_text1"] . "</td>";
				else 
					echo "<td style='vertical-align:middle; text-align:center;word-wrap:break-word;'>" . $lang_array["no_text1"] . "</td>";
										
				if($allow == "1")
					echo "<td style='vertical-align:middle; text-align:center;word-wrap:break-word;'>" . $lang_array["yes_text1"] . "</td>";
				else 
					echo "<td style='vertical-align:middle; text-align:center;word-wrap:break-word;'>" . $lang_array["no_text1"] . "</td>";
				
				if($names[6] == -1)
					echo "<td style='vertical-align:middle; text-align:center;word-wrap:break-word;'>" . $lang_array["proxy_list_text9"] . "</td>";
				else	
					echo "<td style='vertical-align:middle; text-align:center;word-wrap:break-word;'>" . dayToday($names[5]) . "</td>";
				
				//echo $names[6];
				if($names[6] == -1 || $names[6] == 1)
					echo "<td style='vertical-align:middle; text-align:center;word-wrap:break-word;'>" . $lang_array["yes_text1"] . "</td>";
				else 
					echo "<td style='vertical-align:middle; text-align:center;word-wrap:break-word;'>" . $lang_array["no_text1"] . "</td>";
				echo "<td style='vertical-align:middle; text-align:center;word-wrap:break-word;'>".$names[7]."</td>";	
				if($level == 1)	
					echo "<td style='vertical-align:middle; text-align:center;word-wrap:break-word;'>".$lang_array["proxy_list_text25"] ."</td>";
				else
					echo "<td style='vertical-align:middle; text-align:center;word-wrap:break-word;'>".$names[14]."</td>";	
				echo "<td style='vertical-align:middle; text-align:center;word-wrap:break-word;'><a href='#' onclick='start_proxy(\"".$names[0]."\")'>" . $lang_array["proxy_list_text21"] . "</a>&nbsp;&nbsp<a href='#' onclick='edit_proxy(\"".$names[0]."\")'>" . $lang_array["edit_text2"] . "</a>&nbsp;&nbsp<a href='#' onclick='statistics_proxy(\"".$names[0]."\")'>" . $lang_array["proxy_list_text10"] . "</a>&nbsp;&nbsp<a href='#' onclick='delete_user(\"".$key."\")'>" . $lang_array["del_text1"] . "</a></td>";
				echo "</tr>";
			}
			echo "</tbody>";
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
		
		var base64EncodeChars = "ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789+/";  
		var base64DecodeChars = new Array(-1, -1, -1, -1, -1, -1, -1, -1, -1, -1, -1, -1, -1, -1, -1, -1, -1, -1, -1, -1, -1, -1, -1, -1, -1, -1, -1, -1, -1, -1, -1, -1, -1, -1, -1, -1, -1, -1, -1, -1, -1, -1, -1, 62, -1, -1, -1, 63, 52, 53, 54, 55, 56, 57, 58, 59, 60, 61, -1, -1, -1, -1, -1, -1, -1, 0, 1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12, 13, 14, 15, 16, 17, 18, 19, 20, 21, 22, 23, 24, 25, -1, -1, -1, -1, -1, -1, 26, 27, 28, 29, 30, 31, 32, 33, 34, 35, 36, 37, 38, 39, 40, 41, 42, 43, 44, 45, 46, 47, 48, 49, 50, 51, -1, -1, -1, -1, -1);
		
		function base64encode(str){  
    	var out, i, len;  
    	var c1, c2, c3;  
    	len = str.length;  
    	i = 0;  
    	out = "";  
    	while (i < len) {  
       	 	c1 = str.charCodeAt(i++) & 0xff;  
        	if (i == len) {  
            	out += base64EncodeChars.charAt(c1 >> 2);  
            	out += base64EncodeChars.charAt((c1 & 0x3) << 4);  
            	out += "==";  
            	break;  
        	}  
        	c2 = str.charCodeAt(i++);  
        	if (i == len) {  
           	 	out += base64EncodeChars.charAt(c1 >> 2);  
            	out += base64EncodeChars.charAt(((c1 & 0x3) << 4) | ((c2 & 0xF0) >> 4));  
            	out += base64EncodeChars.charAt((c2 & 0xF) << 2);  
            	out += "=";  
            	break;  
        	}  
        	c3 = str.charCodeAt(i++);  
        	out += base64EncodeChars.charAt(c1 >> 2);  
        	out += base64EncodeChars.charAt(((c1 & 0x3) << 4) | ((c2 & 0xF0) >> 4));  
        	out += base64EncodeChars.charAt(((c2 & 0xF) << 2) | ((c3 & 0xC0) >> 6));  
        	out += base64EncodeChars.charAt(c3 & 0x3F);  
    	}  
    	return out;  
		}
		
		function delete_user(name)
		{
			if(confirm("<?php echo $lang_array["proxy_list_text20"] ?>： " + name + " ?") == true)
  			{
				var url = "proxy_children_del.php?name=" + base64encode(encodeURI(name)) + "&level=<?php echo $level ?>";
				window.location.href = url;
  			}
		}

		function edit_user(name)
		{
			//if(confirm("是否删除用户： " + name + " ?") == true)
  			{
				var url = "proxy_children_edit.php?name=" + name + "&level=<?php echo $level ?>";
				window.location.href = url;
  			}
		}

		function edit_proxy(name)
		{
			//window.location.href = "version_proxy_edit.php?proxy=" + name;
			var url = "proxy_children_edit.php?proxy=" + name + "&level=<?php echo $level ?>";
			window.location.href = url;
		}
		
		function start_proxy(name)
		{
			window.location.href = "proxy_children_start.php?proxy=" + name +　"&level=<?php echo $level ?>";
		}
		
		function statistics_proxy(name)
		{
			window.location.href = "proxy_children_statistics.php?proxy=" + name + "&level=<?php echo $level ?>";
		}
        </script>
    </body>
<?php
	$sql->disconnect_database();
?>
</html>