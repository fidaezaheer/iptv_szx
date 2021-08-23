<?PHP
if(Extension_Loaded('zlib')) Ob_Start('ob_gzhandler');
Header("Content-type: text/html");
?> 

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
                                <div class="muted pull-left"><?php echo $lang_array["playlist_list_text4"] ?></div>
                            </div>
                            <div class="block-content collapse in">
                                <div class="span12">
                                   <div class="table-toolbar">
                                      <div class="btn-group">
                                         <a href="playlist_add.php"><button class="btn btn-success"><?php echo $lang_array["playlist_list_text5"] ?><i class="icon-plus icon-white"></i></button></a>
                                      </div>
                                      
                                      <div class="btn-group">
                                         <a href="playlist_auto_list.php"><button class="btn btn-success"><?php echo $lang_array["playlist_list_text7"] ?><i class="icon-plus icon-white"></i></button></a>
                                      </div>
                                   </div>
                                    
                                    <table cellpadding="0" cellspacing="0" border="0" class="table table-bordered" >
                                        <thead>
                                            <tr>
            									<th width="23%" style='vertical-align:middle; text-align:center;'><?php echo $lang_array["playlist_list_text1"] ?></th>
            									<th width="63%" style='vertical-align:middle; text-align:center;'><?php echo $lang_array["playlist_list_text2"] ?></th>
          										<th width="14%" style='vertical-align:middle; text-align:center;'><?php echo $lang_array["playlist_list_text3"] ?></th>
                                            </tr>
                                        </thead>
                                        
<?php
			$sql->connect_database_default();
			$mydb = $sql->get_database();
			$sql->create_database($mydb);
			
			$mytable = "playlist_type_table";
			$sql->create_table($mydb, $mytable, "name text, space longtext, id text, playlist longtext");
			$namess = $sql->fetch_datas($mydb, $mytable);
			
			echo "<tbody>";
			foreach($namess as $names) {
				echo "<tr>";
				$key = null;
				
				echo "<td style='vertical-align:middle; text-align:center;'>" . $names[0] . "</td>";
				echo "<td style='vertical-align:middle; text-align:center;'>" . $names[1] . "</td>";
				echo "<td style='vertical-align:middle; text-align:center;'><a href='playlist_edit.php?name=" . $names[0] . "&id=" . $names[2] . "'>".$lang_array["edit_text2"]."</a></img>";
				echo "&nbsp&nbsp";
				echo "<a href='playlist_content.php?name=" . $names[0] . "&id=" . $names[2] . "'>".$lang_array["edit_text1"]."</a>";
				echo "&nbsp&nbsp";
				echo "<a href='#' onclick='delete_playlist(\"" . $names[0] . "\",\"" . $names[2] . "\")'>".$lang_array["del_text1"]."</a></img></td>";	
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
		
		function delete_playlist(name,id)
		{
			if(confirm("<?php echo $lang_array["playlist_list_text6"] ?>" + ":" + name + " ?") == true)
  			{
				var url = "playlist_del.php?name=" + name + "&id=" + id;
				window.location.href = url;
  			}
		}

        </script>
    </body>

</html>

<?PHP
if(Extension_Loaded('zlib')) Ob_End_Flush();
?>