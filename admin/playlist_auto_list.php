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

	$sql->connect_database_default();
	$mydb = $sql->get_database();
	$sql->create_database($mydb);
	
	$mytable = "playlist_type_table";
	$sql->create_table($mydb, $mytable, "name text, space longtext, id text, playlist longtext");
	$namess = $sql->fetch_datas($mydb, $mytable);	
	
	$mytable = "allocation_table";
	$sql->create_table($mydb, $mytable, "name text, value text");
	$allocation = $sql->query_data($mydb, $mytable, "name", "cn", "value");


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
                                <div class="muted pull-left"><?php echo $lang_array["playlist_auto_list_text1"] ?></div>
                            </div>
                            <div class="block-content collapse in">
                                <div class="span12">
                                  
                                    <table cellpadding="0" cellspacing="0" border="0" class="table table-bordered" >
                                        <thead>
                                            <tr>
            									<th width="23%" style='vertical-align:middle; text-align:center;'><?php echo $lang_array["playlist_auto_list_text2"] ?></th>
            									<th width="63%" style='vertical-align:middle; text-align:center;'><?php echo $lang_array["playlist_auto_list_text3"] ?></th>
          										<th width="14%" style='vertical-align:middle; text-align:center;'><?php echo $lang_array["playlist_auto_list_text4"] ?></th>
                                            </tr>
                                        </thead>
                                        
        <tbody>
        <tr>
        <td style='vertical-align:middle; text-align:center;'><?php echo $lang_array["playlist_auto_list_text5"] ?></td>
        <td style='vertical-align:middle; text-align:center;'></td>
        <td style='vertical-align:middle; text-align:center;'>
        <select id="allocation_select_id_cn" name="">
<?php
        foreach($namess as $names) {
			if(strcmp($allocation,$names[2]) == 0)
			{
				echo "<option value='" . $names[2] . "' selected>" . $names[0] . "</option>";
			}
			else
			{
				echo "<option value='" . $names[2] . "'>" . $names[0] . "</option>";
			}
		}
?>
        </select>
        </td>
        </tr>
        
        <tr>
        <td style='vertical-align:middle; text-align:center;'><?php echo $lang_array["playlist_auto_list_text6"] ?></td>
        <td style='vertical-align:middle; text-align:center;'></td>
        <td style='vertical-align:middle; text-align:center;'>
        <select id="allocation_select_id_hk" name="">
<?php
		$allocation = $sql->query_data($mydb, $mytable, "name", "hk", "value");
        foreach($namess as $names) {
			if(strcmp($allocation,$names[2]) == 0)
			{
				echo "<option value='" . $names[2] . "' selected>" . $names[0] . "</option>";
			}
			else
			{
				echo "<option value='" . $names[2] . "'>" . $names[0] . "</option>";
			}
		}
?>
        </select>
        </td>
        </tr>
        
        <tr>
        <td style='vertical-align:middle; text-align:center;'><?php echo $lang_array["playlist_auto_list_text7"] ?></td>
        <td style='vertical-align:middle; text-align:center;'></td>
        <td style='vertical-align:middle; text-align:center;'>
        <select id="allocation_select_id_mo" name="">
<?php
		$allocation = $sql->query_data($mydb, $mytable, "name", "mo", "value");
        foreach($namess as $names) {
			if(strcmp($allocation,$names[2]) == 0)
			{
				echo "<option value='" . $names[2] . "' selected>" . $names[0] . "</option>";
			}
			else
			{
				echo "<option value='" . $names[2] . "'>" . $names[0] . "</option>";
			}
		}
?>
        </select>
        </td>
        </tr>
        
        <tr>
        <td style='vertical-align:middle; text-align:center;'><?php echo $lang_array["playlist_auto_list_text8"] ?></td>
        <td style='vertical-align:middle; text-align:center;'></td>
        <td style='vertical-align:middle; text-align:center;'>
        <select id="allocation_select_id_tw" name="">
<?php
		$allocation = $sql->query_data($mydb, $mytable, "name", "tw", "value");
        foreach($namess as $names) {
			if(strcmp($allocation,$names[2]) == 0)
			{
				echo "<option value='" . $names[2] . "' selected>" . $names[0] . "</option>";
			}
			else
			{
				echo "<option value='" . $names[2] . "'>" . $names[0] . "</option>";
			}
		}
?>
        </select>
        </td>
        </tr>
        
        <tr>
        <td style='vertical-align:middle; text-align:center;'><?php echo $lang_array["playlist_auto_list_text9"] ?></td>
        <td style='vertical-align:middle; text-align:center;'></td>
        <td style='vertical-align:middle; text-align:center;'>
        <select id="allocation_select_id_us" name="">
<?php
		$allocation = $sql->query_data($mydb, $mytable, "name", "us", "value");
        foreach($namess as $names) {
			if(strcmp($allocation,$names[2]) == 0)
			{
				echo "<option value='" . $names[2] . "' selected>" . $names[0] . "</option>";
			}
			else
			{
				echo "<option value='" . $names[2] . "'>" . $names[0] . "</option>";
			}
		}
		
		$sql->disconnect_database();
?>
        </select>        
        </td>
        </tr>
        
        </tbody>                           
                                    </table>
                                    <div class="form-actions">
   										<button type="button" class="btn btn-primary" onclick="save_allocation()"><?php echo $lang_array["save_text1"] ?></button>
                                		<button type="button" class="btn" onclick="back_page()"><?php echo $lang_array["back_text1"] ?></button>
                            		</div>
                                    
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

		function save_allocation()
		{
	
			var cn = document.getElementById("allocation_select_id_cn").value;
			var hk = document.getElementById("allocation_select_id_hk").value;
			var mo = document.getElementById("allocation_select_id_mo").value;
			var tw = document.getElementById("allocation_select_id_tw").value;
			var us = document.getElementById("allocation_select_id_us").value;
	
			var url = "allocation_post.php?" + "cn=" + cn + "&hk=" + hk + "&mo=" + mo + "&tw=" + tw + "&us=" + us;
			
			window.location.href = url;
		}

		function back_page()
		{
			window.location.href = "playlist_list.php";
				
		}
        </script>
    </body>

</html>

<?PHP
if(Extension_Loaded('zlib')) Ob_End_Flush();
?>