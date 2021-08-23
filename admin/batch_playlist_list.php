<!DOCTYPE html>
<?php
	include_once "cn_lang.php";
	include_once 'common.php';
	$sql = new DbSql();
	$sql->login();

	$sql->connect_database_default();
	$mydb = $sql->get_database();
	$sql->create_database($mydb);
	
	$mytable = "playlist_type_table";
	$sql->create_table($mydb, $mytable, "name text, space longtext, id text, playlist longtext");
	
	$namess = $sql->fetch_datas($mydb, $mytable);
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
                                <div class="muted pull-left"><?php echo $lang_array["custom_batch_list_text1"] ?></div>
                            </div>
                            
                            <div class="block-content collapse in">
                                <div class="span12">
                                	<form class="form-horizontal" method="post" action="user_post.php" enctype='multipart/form-data'>
                                    	<fieldset><br/>
     原分配列表：
<?php

	echo "<select id='playlist_select_id1' name='' style='width: 100px'>";
	echo "<option value='all'>" . $lang_array["batch_playlist_list_text1"] . "</option>";
	echo "<option value='auto'>" . $lang_array["batch_playlist_list_text2"] . "</option>";
	foreach($namess as $names) {
		echo "<option value='" . $names[2] . "'>" . $names[0] . "</option>";
	}
	echo "</select>";
	
	echo "&nbsp;&nbsp;<input name='' type='button' value='<-- -->' onclick='save()'/>&nbsp;&nbsp;";
	
	echo "<select id='playlist_select_id2' name='' style='width: 100px'>";
	echo "<option value='all'>" . $lang_array["batch_playlist_list_text1"] . "</option>";
	echo "<option value='auto'>" . $lang_array["batch_playlist_list_text2"] . "</option>";
	foreach($namess as $names) {
		echo "<option value='" . $names[2] . "'>" . $names[0] . "</option>";
	}
	echo "</select>";
?>
											<div class="form-actions">
												<input name="" class="btn btn-primary" type="button" value="返回" onclick="go_back()"/>
											</div> 
                                   		</fieldset>
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
		
		function get_type_checkbox_value()
		{
			var value = "";
			var checkboxs = document.getElementsByName("type_checkbox");
			for(var i = 0; i < checkboxs.length; i++)
			{
				if(checkboxs[i].type == "checkbox" && checkboxs[i].checked)
				{
					value = value + checkboxs[i].value;
					if(i < checkboxs.length - 1)
						value = value + "|";
				}
			}
	
			return value;
		}

		function save()
		{
			var v1 = document.getElementById("playlist_select_id1").value;
			var v2 = document.getElementById("playlist_select_id2").value;
			if(confirm("<?php echo $lang_array["batch_playlist_list_text3"] ?>" + "?") == true)
  			{
				window.location.href = "batch_playlist_post.php?v1=" + v1 + "&v2=" + v2;
			}	
		}

		function go_back()
		{
			window.location.href = "custom_batch_list.php";
		}
		
        </script>
    </body>

</html>