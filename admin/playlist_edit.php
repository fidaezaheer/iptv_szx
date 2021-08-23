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
                                <div class="muted pull-left"><?php echo $lang_array["playlist_edit_text6"] ?></div>
                            </div>
                            <div class="block-content collapse in">
                                <div class="span12">
                                   <div class="table-toolbar">
                                   </div>
                                    <?php
										if(isset($_GET["id"]))
											echo "<form action='playlist_post.php?id=" . $_GET["id"] . "'  name='authform' method='post' enctype='multipart/form-data'>";
										else
											echo "<form action='playlist_post.php?id=0' name='authform' method='post' enctype='multipart/form-data'>";
											
										
										echo "<input name='url' id='url' type='hidden' value=''/>";
										echo "</form>";
									?>
                                    
                                    <table cellpadding="0" cellspacing="0" border="0" class="table table-bordered">
                                        <thead>
          								<tr>
            							<th width="5%" style="vertical-align:middle; text-align:center;"><?php echo $lang_array["playlist_edit_text1"] ?></th>
            							<th width="5%" style="vertical-align:middle; text-align:center;"><?php echo $lang_array["playlist_edit_text2"] ?></th>
            							<th width="30%" style="vertical-align:middle; text-align:center;"><?php echo $lang_array["playlist_edit_text3"] ?></th>
            							<th width="10%" style="vertical-align:middle; text-align:center;"><?php echo $lang_array["playlist_edit_text4"] ?></th>
            							<th width="60%" style="vertical-align:middle; text-align:center;"><?php echo $lang_array["playlist_edit_text5"] ?></th>
          								</tr>
                                        </thead>
                                        
<?php
	$sql->connect_database_default();
	$mydb = $sql->get_database();
	$sql->create_database($mydb);
	$sql->create_database($mydb);
			
	$mytable = "playlist_type_table";
	$sql->create_table($mydb, $mytable, "name text, space longtext, id text, playlist longtext");
	
	if(isset($_GET["id"]))
	{
		$playlistss = $sql->query_data($mydb, $mytable, "id", $_GET["id"], "playlist");	
	}
	$playlists = explode("|", $playlistss);		
	
	$mytable = "live_preview_table";
	$sql->create_table($mydb, $mytable, "name longtext, image longtext, url longtext, password longtext, type longtext, preview longtext, id longtext, urlid smallint, hd longtext");
	$namess = $sql->fetch_datas_order_asc($mydb, $mytable,"urlid");
	
	$type_array = array();
	$mytable = "live_type_table";
	$sql->create_table($mydb, $mytable, "name longtext, id longtext");
	$type_namess = $sql->fetch_datas($mydb, $mytable);
	
	$playback_type_array = array();
	$mytable = "playback_type_table";
	$sql->create_table($mydb, $mytable, "name text, id text ,need int, typepassword text, param0 text, param1 text, param2 text, param3 text");
	$playback_type_namess = $sql->fetch_datas($mydb, $mytable);	
	
	foreach($type_namess as $type_names) {
		$type_array[$type_names[1]] = $type_names[0];
	}
	
	foreach($playback_type_namess as $playback_type_names) {
		$playback_type_array[$playback_type_names[1]] = $playback_type_names[0];
	}
	
	echo "<tbody>";
	foreach($namess as $names) 
	{
		$ischeck = false;
		foreach($playlists as $playlist)
		{
			if(intval($names[7]) == $playlist)
			{
				$ischeck = true;
				break;
			}		
		}
		
		if(intval($names[7]) < 10000)
		{
			$explode_type_names = explode("|",$names[4]);
			$explode_type_names_all = "";
			for($ii=0; $ii<count($explode_type_names); $ii++)
			{
				if(isset($explode_type_names[$ii]) && isset($type_array[$explode_type_names[$ii]]))
				{
				$explode_type_names_all = $explode_type_names_all . $type_array[$explode_type_names[$ii]];
				if($ii < count($explode_type_names) - 1)
					$explode_type_names_all = $explode_type_names_all . "|"; 
				}
			}
		}
		else
		{
			$explode_type_names = explode("|",$names[4]);
			$explode_type_names_all = "";
			for($ii=0; $ii<count($explode_type_names); $ii++)
			{
				if(isset($explode_type_names[$ii]) && isset($playback_type_array[$explode_type_names[$ii]]))
				{
					$explode_type_names_all = $explode_type_names_all . $playback_type_array[$explode_type_names[$ii]];
					if($ii < count($explode_type_names) - 1)
					$explode_type_names_all = $explode_type_names_all . "|"; 
				}
			}			
		}
				
		if($ischeck == false)
		{
			echo "<tr>";
			echo "<td style='vertical-align:middle; text-align:center;'> <div class='controls'><input class='uniform_on' name='playlist_checkbox' style='width:15px;height:25px;' type='checkbox' value='" . $names[7] . "' /></div></td>";
			if(intval($names[7]) > 10000)
				echo "<td style='vertical-align:middle; text-align:center;'>" . (intval($names[7]) - 10000) . "</td>";
			else
				echo "<td style='vertical-align:middle; text-align:center;'>" . $names[7] . "</td>";
			echo "<td style='vertical-align:middle; text-align:center;'>" . $names[0] . "</td>";
			if(intval($names[7]) > 10000)
				echo "<td style='vertical-align:middle; text-align:center;  background-color:#999999;'>" . $lang_array["playlist_edit_text8"] . "</td>";
			else
				echo "<td style='vertical-align:middle; text-align:center;'>" . $lang_array["playlist_edit_text7"] . "</td>";
			echo "<td style='vertical-align:middle; text-align:center;'>" . $explode_type_names_all. "</td>";
			echo "</tr>";			
			//echo "add_selected_one(false,\"" . $names[0] . "\",\"" . $names[7] . "\",\"" . $explode_type_names_all . "\");";
		}
		else
		{
			echo "<tr>";
			echo "<td style='vertical-align:middle; text-align:center;'> <div class='controls'><input class='uniform_on' name='playlist_checkbox' style='width:15px;height:25px;' type='checkbox' value='" . $names[7] . "' checked/></div></td>";
			if(intval($names[7]) > 10000)
				echo "<td style='vertical-align:middle; text-align:center;'>" . (intval($names[7]) - 10000) . "</td>";
			else
				echo "<td style='vertical-align:middle; text-align:center;'>" . $names[7] . "</td>";
			echo "<td style='vertical-align:middle; text-align:center;'>" . $names[0] . "</td>";
			if(intval($names[7]) > 10000)
				echo "<td style='vertical-align:middle; text-align:center;  background-color:#999999;'>" . $lang_array["playlist_edit_text8"] . "</td>";
			else
				echo "<td style='vertical-align:middle; text-align:center;'>" . $lang_array["playlist_edit_text7"] . "</td>";
			echo "<td style='vertical-align:middle; text-align:center;'>" . $explode_type_names_all. "</td>";
			echo "</tr>";	
			//echo "add_selected_one(true,\"" . $names[0] . "\",\"" . $names[7] . "\",\"" . $explode_type_names_all . "\");";
		}
	}
	echo "</tbody>";
	
	$sql->disconnect_database();		
	
?>                                       
                                    </table>                               
                                    
                                    <form class="form-horizontal">
                                    	<input name="" class="btn btn-primary" type="button" value="<?php echo $lang_array["account_add_text16"] ?>" onclick="selectAll()"/>
            							<input name="" class="btn btn-primary" type="button" value="<?php echo $lang_array["account_add_text17"] ?>" onclick="noAll()"/>
                                    
										<input name="" class="btn btn-primary" type="button" value="<?php echo $lang_array["playlist_list_text8"] ?>" onclick="batch()"/>
                                    	<input class="input-mini focused" id="start_input" name="start_input" type="text" value=""> ----
                                    	<input class="input-mini focused" id="end_input" name="end_input" type="text" value="">
                                        
                                        <input style="width:15px;height:28px;" type="radio" name="live_type" id="live_type" value="0" checked/><?php echo $lang_array["playlist_edit_text7"] ?>
                                       	&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
        							   	<input style="width:15px;height:28px;" type="radio" name="live_type" id="live_type" value="1" /><?php echo $lang_array["playlist_edit_text8"] ?>         	
                                    </form>
                                        
                                    
                                    
                                    <div class="form-actions">
   										<button onclick="save()" class="btn btn-primary"><?php echo $lang_array["save_text1"] ?></button>
                                		<button type="reset" class="btn" onclick="back_page()"><?php echo $lang_array["back_text1"] ?></button>
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
		
		function idNumClass(id,num) {
			this.id = id;
			this.num = num; 
		}

		function GetRadioValue(RadioName){
    		var obj;
    		obj=document.getElementsByName(RadioName);
    		if(obj!=null){
       	 		var i;
        		for(i=0;i<obj.length;i++){
            		if(obj[i].checked){
                		return obj[i].value;
            		}
        		}
    		}
   			return null;
		}
		
		function save()
		{
			var playlist = "";
			var chkObjs = document.getElementsByName("playlist_checkbox");
			for(var i=0;i<chkObjs.length;i++){
				if(chkObjs[i].checked){
					playlist = playlist + chkObjs[i].value;
					playlist = playlist + "|";
				}
			}
			//alert(playlist);
			
			document.authform.url.value = playlist;
			document.authform.submit();
			
			/*
			var ii = 0;
			var playlist = "";
			for(ii=0; ii<idNumArray.length; ii++)
			{
				if(document.getElementByNames("file_checkbox").checked == true)
				{
					//alert(idNumArray[ii].num);	
					if(playlist.length > 0)
						playlist = playlist + "|";
				
					playlist = playlist + idNumArray[ii].num;
				}
			}
	
			document.authform.url.value = playlist;
			document.authform.submit();
			*/
		}

		function back_page(name)
		{
			var url = "playlist_list.php";
			window.location.href = url;
		}
		
		function selectAll() //全选
		{
			//alert(0);
			var objs = document.getElementsByName('playlist_checkbox');
			var i;
			for(i = 0; i < objs.length; i++)
			{
				if(objs[i].type == "checkbox")
				{
					objs[i].checked = true;
				}
			}
		} 

		function noAll() //全选
		{
			//alert(0);
			var objs = document.getElementsByName('playlist_checkbox');
			var i;
			for(i = 0; i < objs.length; i++)
			{
				if(objs[i].type == "checkbox")
				{
					objs[i].checked = false;
				}
			}
		}
		
		function batch()
		{
			var objs = document.getElementsByName('playlist_checkbox');
			var start = document.getElementById("start_input").value;
			var end = document.getElementById("end_input").value;
			var type = GetRadioValue("live_type");
			if(start.length <= 0)
			{
				alert("<?php echo $lang_array["playlist_edit_text9"] ?>");
				return;
			}
			else if(end.length <= 0)
			{
				alert("<?php echo $lang_array["playlist_edit_text10"] ?>");
				return;
			}
			
			//alert(start);
			//alert(type);
			for(i = 0; i < objs.length; i++)
			{
				if(type == 0)
				{
					if(parseInt(objs[i].value) >= parseInt(start) && parseInt(objs[i].value) <= parseInt(end))
					{
						objs[i].checked = true;
					}
				}
				else if(type == 1)
				{
					if(parseInt(objs[i].value) >= parseInt(start)+10000 && parseInt(objs[i].value) <= parseInt(end)+10000)
					{
						objs[i].checked = true;
					}
				}
			}
		}
        </script>
    </body>

</html>

<?PHP
if(Extension_Loaded('zlib')) Ob_End_Flush();
?>