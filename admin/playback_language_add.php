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

	/*
	$sql->connect_database_default();
	$mydb = $sql->get_database();
	$sql->create_database($mydb);
	
	$mytable = "live_type_language_table";
	$sql->create_table($mydb, $mytable, "name text, space longtext, id text, playlist longtext");
	$namess = $sql->fetch_datas($mydb, $mytable);	
	
	$mytable = "allocation_table";
	$sql->create_table($mydb, $mytable, "name text, value text");
	$allocation = $sql->query_data($mydb, $mytable, "name", "cn", "value");
	*/

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
                                <div class="muted pull-left"><?php echo $lang_array["live_type_language_add_text1"] ?></div>
                            </div>
                            <div class="block-content collapse in">
                                <div class="span12">
                                  	<form class="form-horizontal" method="post" action="playback_language_post.php?id=20001" enctype='multipart/form-data'>
                                    <table cellpadding="0" cellspacing="0" border="0" class="table table-bordered" >
                                        <thead>
                                            <tr>
            									<th width="23%" style='vertical-align:middle; text-align:center;'><?php echo $lang_array["live_type_language_add_text9"] ?></th>
            									<th width="77%" style='vertical-align:middle; text-align:center;'><?php echo $lang_array["live_type_language_add_text10"] ?></th>
                                            </tr>
                                        </thead>
                                        
        <tbody>
        <tr>
        <td style='vertical-align:middle; text-align:center;'><?php echo $lang_array["live_type_language_add_text2"] ?></td>
        <td style='vertical-align:middle; text-align:center;'><input class="input-xlarge focused" id="name0" name="name0" type="text" value=""></td>
        </tr>
        
        <tr>
        <td style='vertical-align:middle; text-align:center;'><?php echo $lang_array["live_type_language_add_text3"] ?></td>
        <td style='vertical-align:middle; text-align:center;'><input class="input-xlarge focused" id="name1" name="name1" type="text" value=""></td>
        <td style='vertical-align:middle; text-align:center;'></td>
        </tr>
        
        <tr>
        <td style='vertical-align:middle; text-align:center;'><?php echo $lang_array["live_type_language_add_text4"] ?></td>
        <td style='vertical-align:middle; text-align:center;'><input class="input-xlarge focused" id="name2" name="name2" type="text" value=""></td>
        </tr>
        
        <tr>
        <td style='vertical-align:middle; text-align:center;'><?php echo $lang_array["live_type_language_add_text5"] ?></td>
        <td style='vertical-align:middle; text-align:center;'><input class="input-xlarge focused" id="name3" name="name3" type="text" value=""></td>
        </tr>
        
        <tr>
        <td style='vertical-align:middle; text-align:center;'><?php echo $lang_array["live_type_language_add_text6"] ?></td>
        <td style='vertical-align:middle; text-align:center;'><input class="input-xlarge focused" id="name4" name="name4" type="text" value=""></td>
        </tr>

		<tr>
        <td style='vertical-align:middle; text-align:center;'><?php echo $lang_array["live_type_language_add_text7"] ?></td>
        <td style='vertical-align:middle; text-align:center;'><input class="input-xlarge focused" id="name5" name="name6" type="text" value=""></td>
        </tr>
        
        <tr>
        <td style='vertical-align:middle; text-align:center;'><?php echo $lang_array["live_type_language_add_text8"] ?></td>
        <td style='vertical-align:middle; text-align:center;'><input class="input-xlarge focused" id="name6" name="name7" type="text" value=""></td>
        </tr>
        
        </tbody>                           
                                    </table>
                                    <div class="form-actions">
   										<button type="submit" class="btn btn-primary" ><?php echo $lang_array["save_text1"] ?></button>
                                		<button type="button" class="btn" onclick="back_page()"><?php echo $lang_array["back_text1"] ?></button>
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

        <script src="vendors/jquery-1.9.1.js"></script>
        <script src="bootstrap/js/bootstrap.min.js"></script>
        <script src="vendors/datatables/js/jquery.dataTables.min.js"></script>


        <script src="assets/scripts.js"></script>
        <script src="assets/DT_bootstrap.js"></script>
        <script>
        $(function() {
            
        });

		function back_page()
		{
			window.location.href = "playback_add.php";
		}
        </script>
    </body>

</html>

<?PHP
if(Extension_Loaded('zlib')) Ob_End_Flush();
?>