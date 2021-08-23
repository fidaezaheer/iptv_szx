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
			$sql->create_table($mydb, $mytable, "name longtext, password longtext");
			$namess = $sql->fetch_datas($mydb, $mytable);
			
			echo "<tbody>";
			foreach($namess as $names) {
				echo "<tr class='odd gradeA'>";
				$key = null;
				foreach($names as $name) {
					if($key == null) {
						$key = $name;
					}
					echo "<td style='vertical-align:middle; text-align:center;'>".$name."</td>";
				}
				
				echo "<td style='vertical-align:middle; text-align:center;'><i class='icon-pencil'></i><a href='#' onclick='edit_user(\"".$key."\")'>".$lang_array["edit_text1"]."</a>&nbsp;&nbsp;<i class='icon-remove'></i><a href='#' onclick='delete_user(\"".$key."\")'>".$lang_array["del_text1"]."</a></td>";
				
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
		
		function delete_user(name)
		{
			if(confirm("是否删除用户： " + name + " ?") == true)
  			{
				var url = "user_del.php?name=" + name;
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