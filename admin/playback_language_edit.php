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

	$id = $_GET["key"];
	//$page = $_GET["page"];
	
	$sql->connect_database_default();
	$mydb = $sql->get_database();
	$sql->create_database($mydb);
	
	
	$sql->connect_database_default();
	$mydb = $sql->get_database();
	$sql->create_database($mydb);
	
	$mytable = "live_preview_table";
	$sql->create_table($mydb, $mytable, "name longtext, image longtext, url longtext, password longtext, type longtext, preview longtext, id longtext, urlid smallint, hd longtext, watermark text, lang text");
	$langss = $sql->query_data($mydb,$mytable,"urlid",$id,"lang");
	$names = explode("|",$langss);
	$name0="";
	$name1="";
	$name2="";
	$name3="";
	$name4="";
	$name5="";
	$name6="";
	for($ii=0; $ii<count($names); $ii++)
	{
		$name = explode("@",$names[$ii]);
		if($name[0] == "en" && count($name) >= 2)
			$name0 = $name[1];
		else if($name[0] == "es" && count($name) >= 2)
			$name1 = $name[1];
		else if($name[0] == "ja" && count($name) >= 2)
			$name2 = $name[1];
		else if($name[0] == "ko" && count($name) >= 2)
			$name3 = $name[1];
		else if($name[0] == "cn" && count($name) >= 2)
			$name4 = $name[1];
		else if($name[0] == "hk" && count($name) >= 2)
			$name5 = $name[1];
		else if($name[0] == "tw" && count($name) >= 2)
			$name6 = $name[1];
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
                                <div class="muted pull-left"><?php echo $lang_array["live_type_language_add_text1"] ?></div>
                            </div>
                            <div class="block-content collapse in">
                                <div class="span12">
                                  	<form class="form-horizontal" method="post" action="playback_language_post.php?id=<?php echo $id ?>" enctype='multipart/form-data'>
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
        <td style='vertical-align:middle; text-align:center;'><input class="input-xlarge focused" id="name0" name="name0" type="text" value="<?php echo $name0 ?>"></td>
        </tr>
        
        <tr>
        <td style='vertical-align:middle; text-align:center;'><?php echo $lang_array["live_type_language_add_text3"] ?></td>
        <td style='vertical-align:middle; text-align:center;'><input class="input-xlarge focused" id="name1" name="name1" type="text" value="<?php echo $name1 ?>"></td>
        <td style='vertical-align:middle; text-align:center;'></td>
        </tr>
        
        <tr>
        <td style='vertical-align:middle; text-align:center;'><?php echo $lang_array["live_type_language_add_text4"] ?></td>
        <td style='vertical-align:middle; text-align:center;'><input class="input-xlarge focused" id="name2" name="name2" type="text" value="<?php echo $name2 ?>"></td>
        </tr>
        
        <tr>
        <td style='vertical-align:middle; text-align:center;'><?php echo $lang_array["live_type_language_add_text5"] ?></td>
        <td style='vertical-align:middle; text-align:center;'><input class="input-xlarge focused" id="name3" name="name3" type="text" value="<?php echo $name3 ?>"></td>
        </tr>
        
        <tr>
        <td style='vertical-align:middle; text-align:center;'><?php echo $lang_array["live_type_language_add_text6"] ?></td>
        <td style='vertical-align:middle; text-align:center;'><input class="input-xlarge focused" id="name4" name="name4" type="text" value="<?php echo $name4 ?>"></td>
        </tr>

		<tr>
        <td style='vertical-align:middle; text-align:center;'><?php echo $lang_array["live_type_language_add_text7"] ?></td>
        <td style='vertical-align:middle; text-align:center;'><input class="input-xlarge focused" id="name5" name="name5" type="text" value="<?php echo $name5 ?>"></td>
        </tr>
        
        <tr>
        <td style='vertical-align:middle; text-align:center;'><?php echo $lang_array["live_type_language_add_text8"] ?></td>
        <td style='vertical-align:middle; text-align:center;'><input class="input-xlarge focused" id="name6" name="name6" type="text" value="<?php echo $name6 ?>"></td>
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
			window.location.href = "playback_edit.php?key=<?php echo $id ?>";
		}
        </script>
    </body>

</html>

<?PHP
if(Extension_Loaded('zlib')) Ob_End_Flush();
?>