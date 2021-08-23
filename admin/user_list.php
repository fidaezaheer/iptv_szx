<!DOCTYPE html>
<?php
include_once "cn_lang.php";
include_once "common.php";
$sql = new DbSql();
$sql->login();
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
                                <div class="muted pull-left"><?php echo $lang_array["left1_text1"] ?></div>
                            </div>
                            <div class="block-content collapse in">
                                <div class="span12">
                                   <div class="table-toolbar">
                                      <div class="btn-group">
                                         <a href="user_add.php"><button class="btn btn-success"><?php echo $lang_array["user_list_text1"] ?><i class="icon-plus icon-white"></i></button></a>
                                      </div>
                                   </div>
                                    
                                    <table cellpadding="0" cellspacing="0" border="0" class="table table-striped table-bordered" id="example2">
                                        <thead>
                                            <tr>
            									<th width="43%" style="vertical-align:middle; text-align:center;"><?php echo $lang_array["user_list_text2"] ?></th>
            									<th width="43%" style="vertical-align:middle; text-align:center;"><?php echo $lang_array["user_list_text3"] ?></th>
          										<th width="14%" style="vertical-align:middle; text-align:center;"><?php echo $lang_array["user_list_text4"] ?></th>
                                            </tr>
                                        </thead>
                                        
<?php

			$mytable = "user_table";
			$sql->connect_database_default();
			$mydb = $sql->get_database();
			$sql->create_database($mydb);
			$sql->create_table($mydb, $mytable, "name longtext, password longtext, namemd5 text, passwordmd5 text, needmd5 int");
			if($sql->find_column($mydb, $mytable, "namemd5") == 0)
				$sql->add_column($mydb, $mytable,"namemd5", "text");
				
			if($sql->find_column($mydb, $mytable, "passwordmd5") == 0)
				$sql->add_column($mydb, $mytable,"passwordmd5", "text");
				
			if($sql->find_column($mydb, $mytable, "needmd5") == 0)
				$sql->add_column($mydb, $mytable,"needmd5", "int");
				
			$namess = $sql->fetch_datas($mydb, $mytable);
			
			echo "<tbody>";
			foreach($namess as $names) {
				echo "<tr class='odd gradeA'>";
				$key = $names[0];
				
				echo "<td style='vertical-align:middle; text-align:center;'>".$names[0]."</td>";
				
				if($names[4] == 1 || $names[4] == 2)
					echo "<td style='vertical-align:middle; text-align:center;'>"."********"."</td>";
				else
					echo "<td style='vertical-align:middle; text-align:center;'>".$names[1]."</td>";
				
				/*
				foreach($names as $name) {
					if($key == null) {
						$key = $name;
					}
					echo "<td style='vertical-align:middle; text-align:center;'>".$name."</td>";
				}
				*/
				echo "<td style='vertical-align:middle; text-align:center;'><a href='#' onclick='edit_user(\"".$key."\")'>".$lang_array["edit_text1"]."</a>&nbsp;&nbsp;<a href='#' onclick='delete_user(\"".$key."\")'>".$lang_array["del_text1"]."</a></td>";
				
				echo "</tr>";
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
		
		var base64EncodeChars = "ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789+/";  
		var base64DecodeChars = new Array(-1, -1, -1, -1, -1, -1, -1, -1, -1, -1, -1, -1, -1, -1, -1, -1, -1, -1, -1, -1, -1, -1, -1, -1, -1, -1, -1, -1, -1, -1, -1, -1, -1, -1, -1, -1, -1, -1, -1, -1, -1, -1, -1, 62, -1, -1, -1, 63, 52, 53, 54, 55, 56, 57, 58, 59, 60, 61, -1, -1, -1, -1, -1, -1, -1, 0, 1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12, 13, 14, 15, 16, 17, 18, 19, 20, 21, 22, 23, 24, 25, -1, -1, -1, -1, -1, -1, 26, 27, 28, 29, 30, 31, 32, 33, 34, 35, 36, 37, 38, 39, 40, 41, 42, 43, 44, 45, 46, 47, 48, 49, 50, 51, -1, -1, -1, -1, -1);  
/** 
 * base64编码 
 * @param {Object} str 
 */  
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
			if(confirm("<?php echo $lang_array["user_list_text5"] ?>： " + name + " ?") == true)
  			{
				var url = "user_del.php?name=" + base64encode(encodeURI(name));
				window.location.href = url;
  			}
		}

		function edit_user(name)
		{
  			{
				var url = "user_edit.php?name=" + name;
				window.location.href = url;
  			}
		}
        </script>
    </body>
</html>