<?PHP
if(Extension_Loaded('zlib')) Ob_Start('ob_gzhandler');
Header("Content-type: text/html");
?> 

<!DOCTYPE html>
<?php
	include_once "cn_lang.php";
	include_once "common.php";

	$sql = new DbSql();
	$sql->login_proxy();
	
	set_time_limit(0);  
	
	$sql->connect_database_default();
	$mydb = $sql->get_database();
	$sql->create_database($mydb);

	$size = 100;
	$offset = 0;
	$page = 0;
	$online_numrows = 0;
	if(isset($_GET["page"]))
	{
		$offset = $size*intval($_GET["page"]);
		$page = $_GET["page"];
	}

	$mytable = "version_table";
	$sql->create_table($mydb, $mytable, "name longtext, value longtext");
	$status = $sql->query_data($mydb, $mytable, "name", "control", "value");
	
	$mytable = "custom_table";
	$sql->create_table($mydb, $mytable, 
		"mac text,cpu text,ip text,space text, date text,
		time text,allow text, playlist text, online text, allocation text,
		proxy text, balance float,showtime text,contact text,member text,
		panal text,number text,ips text, onescrolltext text, onescrolltexttimes int, 
		numberdate int, scrollcontent text, scrolldate text, scrolltimes text, controlurl text, 		
		controltime int, unbundling int, accessdate timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP, mv text, isupdate int,
		remarks text, startime date, model text, remotelogin int, limitmodel text, 
		modelerror int, limittimes int, limitarea text, ghost int, password text, 
		evernumber longtext, prekey text");
		
	$namess = $sql->fetch_datas_where_limit_desc($mydb, $mytable, "proxy", $_COOKIE["user"], $offset, $size, "remarks"); 
	
	$pages = 0;
	$total = $sql->count_fetch_datas($mydb, $mytable);
	$pages = intval($total/$size);
	if($total%$size > 0)
		$pages++;	
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
                                <div class="muted pull-left"><?php echo $lang_array["remote_list_text1"] ?></div>
                            </div>
                            <div class="block-content collapse in">
                                <div class="span12">
                                   <div class="table-toolbar">
                                   </div>
									<form class="form-horizontal" name="authform" method="post" action="" enctype='multipart/form-data'>
                                     
                                    <div class="control-group" >
                                       <label class="control-label" for="focusedInput" style="text-shadow:#0CF"><?php echo $lang_array["remote_list_text5"] ?></label>
                                       <div class="controls">
                                       <input class="input-xxlarge focused" id="remote_url" name="remote_url" type="text" value="<?php echo $net_version ?>">										
                                       <button type='button' class='btn btn-primary' onclick='remote_ctr_all()'><?php echo $lang_array['sure_text1'] ?></button>
                                       <button type='button' class='btn btn-primary' onclick='remote_ctr_all_del()'><?php echo $lang_array['del_text1'] ?></button>
                                       </div> 
                                   	</div>
                                    </div>
                                    </form>
                                    
                                    <table cellpadding="0" cellspacing="0" border="0" class="table table-bordered">
                                        <thead>
                                            <tr>
                                            	<th width="3%" style="vertical-align:middle; text-align:center;"><?php echo $lang_array["custom_list_text83"] ?></th>
            									<th width="12%" style="vertical-align:middle; text-align:center;"><?php echo $lang_array["custom_list_text2"] ?></th>
          										<th width="18%" style="vertical-align:middle; text-align:center;"><?php echo $lang_array["custom_list_text3"] ?></th>
                                                <th width="48%" style="vertical-align:middle; text-align:center;"><?php echo $lang_array["remote_list_text4"] ?></th>	
                                                <th width="10%" style="vertical-align:middle; text-align:center;"><?php echo $lang_array["remote_list_text10"] ?></th>	
                                                <th width="10%" style="vertical-align:middle; text-align:center;"><?php echo $lang_array["remote_list_text6"] ?></th>	
                             				</tr>
                                        </thead>
                                        
<?php
			$null_array = array();
			echo "<tbody>";
			foreach($namess as $names) 
			{
				if($names[4] != "null" && strlen($names[4]) > 5 && strlen($names[31]) < 5)
				{
					$sql->update_data_3($mydb, $mytable, "cpu", $names[1], "mac", $names[0], "startime", $names[4]);
				}

				if($names[4] == "null" && $names[5] == "null" && abs(time() - strtotime($names[8])) > 24*3600*3)
				{
					array_push($null_array,$names);
					//continue;
				}
				
				$space_bgcolor = "";
				
				//$key = $names[0].$names[1];
				echo "<tr>";
					echo "<td style='vertical-align:middle; text-align:center;'> <div class='controls'><input class='uniform_on' name='remote_checkbox' style='width:15px;height:25px;' type='checkbox' value='" . $names[0] . "' /></div></td>";
					echo "<td style='vertical-align:middle; text-align:center;word-wrap:break-word;'>" . $names[0]. "</td>";
					echo "<td style='vertical-align:middle; text-align:center;word-wrap:break-word;'>" . $names[1]. "</td>";
					echo "<td style='vertical-align:middle; text-align:center;word-wrap:break-word;'>" . $names[24]. "</td>";
					echo "<td style='vertical-align:middle; text-align:center;word-wrap:break-word;'>" . $names[30]. "</td>";		
					echo "<td style='vertical-align:middle; text-align:center;word-wrap:break-word;'><a href='#' onclick=\"remote_ctr('" . $names[0] . "','" .$names[1] ."')\">" . $lang_array["remote_list_text6"] . "</a>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<a href='#' onclick=\"remote_ctr_del('" . $names[0] . "','" .$names[1] ."')\">" . $lang_array["del_text1"] . "</a></td>";
				echo "</tr>";
			}
			
			echo "</tbody>";
			
			$sql->disconnect_database();	
	
?>                                       
                                    </table>                               
                                    
                                    <form class="form-horizontal">
                                    	<input name="" class="btn btn-primary" type="button" value="<?php echo $lang_array["account_add_text16"] ?>" onclick="selectAll()"/>
            							<input name="" class="btn btn-primary" type="button" value="<?php echo $lang_array["account_add_text17"] ?>" onclick="noAll()"/>       	
                                    </form>
                                        
                                    
            <form class="form-horizontal">                        
			<div class="form-actions">
            <?php
				if(!isset($_GET["loginremote"]) && !isset($_GET["pause"]) && !isset($_GET["ghost"]) && !isset($_GET["modelerror"]))
				{
					echo "<button type='button' class='btn btn-primary' onclick='go_pre()'>" . $lang_array["custom_list_text17"] . "</button>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
                	echo "<button type='button' class='btn btn-primary' onclick='go_page()'>" . $lang_array["custom_list_text19"] . "</button>&nbsp;<input class='input-mini focused' id='pageid' name='pageid' type='text' value='" . ($page+1) . "' >" .  $lang_array["custom_list_text20"] . "/" . $pages . "&nbsp;" . $lang_array["custom_list_text20"] . "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
                	echo "<button type='button' class='btn btn-primary' onclick='go_back()'>" . $lang_array["custom_list_text18"] . "</button>";
                }
            ?>
            </div> 
            </form>
                                </div>
                            </div>
                            
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <form name="remote_ctr_form" action="proxy_remote_all_post.php" method="post">  
    	<input name="macs" id="macs" type="hidden" value=""/>
        <input name="url" id="url" type="hidden" value=""/>
        <input name="ctr" id="ctr" type="hidden" value=""/>
    	</form>
        <!--/.fluid-container-->

        <script src="vendors/jquery-1.9.1.js"></script>
        <script src="bootstrap/js/bootstrap.min.js"></script>
        <script src="vendors/datatables/js/jquery.dataTables.min.js"></script>


        <script src="assets/scripts.js"></script>
        <script src="assets/DT_bootstrap.js"></script>
        <script>
        $(function() {
            
        });
		
		function getValue(_obj,ip){
   			//var tValue = _obj.innerText;
   			_obj.setAttribute("title","<?php echo $lang_array["custom_list_text88"] ?>:" + ip);
		}
		
		function go_page()
		{
			var pageid = document.getElementById("pageid").value;
			var url = "proxy_remote_list.php?page=" + (pageid-1);
			window.location.href = url;
		}
		
		function go_pre()
		{
<?php
			if($page-1 >= 0)
			{
				echo "var url = 'proxy_remote_list.php?page=".($page-1)."';";
				echo "window.location.href = url;";
			}
?>
		}
		
		function go_back()
		{
<?php
			if($page+1 < $pages)
			{
				echo "var url = 'proxy_remote_list.php?page=".($page+1)."';";
				echo "window.location.href = url;";
			}
?>			
		}
				

		
		function get_type_checkbox_value()
		{
			var value = "";
			var value_array = new Array();
			var type_checkboxs = document.getElementsByName("remote_checkbox");
			for(var i = 0; i < type_checkboxs.length; i++)
			{
				if(type_checkboxs[i].type == "checkbox" && type_checkboxs[i].checked)
				{
					value_array.push(type_checkboxs[i].value);
				}
			}
	
			for(var ii = 0; ii < value_array.length; ii++)
			{
				value = value + value_array[ii];
				if(ii < value_array.length - 1)
					value = value + "|";
			}
	
			//alert(value);
			return value;
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
		
		function remote_ctr(mac,cpuid)
		{
			var url= window.prompt("<?php echo $lang_array["remote_list_text4"] ?>" + ":");
			if(url.length > 7)
			{
				window.location.href = "proxy_remote_post.php?mac=" + mac + "&cpuid=" + cpuid + "&url=" + encodeURI(url);
				
				/*
				document.remote_ctr.macs.value = get_type_checkbox_value();
				document.remote_ctr.url.value = encodeURI(url);
				document.remote_ctr.ctr.value = 1;
      			document.remote_ctr.submit();	
				*/
			}
			else
			{
				alert("ERROR");	
			}
			
		}
	
		function remote_ctr_del(mac,cpuid)
		{
			
			window.location.href = "proxy_remote_post.php?mac=" + mac + "&cpuid=" + cpuid;
			
			/*
			document.remote_ctr.macs.value = get_type_checkbox_value();
			document.remote_ctr.url.value = "";
			document.remote_ctr.ctr.value = 1;
      		document.remote_ctr.submit();
			*/
		}
		
		function remote_ctr_all()
		{
			var url = document.getElementById("remote_url").value;
			if(url.length > 7)
			{
				//window.location.href = "remote_post.php?url=" + encodeURI(url);
				var macs = get_type_checkbox_value();
				document.remote_ctr_form.macs.value = macs;
				document.remote_ctr_form.url.value = encodeURI(url);
				document.remote_ctr_form.ctr.value = 1;
      			document.remote_ctr_form.submit();
			}
			else
			{
				alert("ERROR");	
			}
		}
		
		function remote_ctr_all_del()
		{
			//window.location.href = "remote_post.php";
			document.remote_ctr_form.macs.value = get_type_checkbox_value();
			document.remote_ctr_form.url.value = "";
			document.remote_ctr_form.ctr.value = 0;
      		document.remote_ctr_form.submit();
		}
		
		function selectAll() //全选
		{
			
			var objs = document.getElementsByName('remote_checkbox');
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
			
			var objs = document.getElementsByName('remote_checkbox');
			var i;
			for(i = 0; i < objs.length; i++)
			{
				if(objs[i].type == "checkbox")
				{
					objs[i].checked = false;
				}
			}
		} 
        </script>
    </body>

</html>

<?PHP
if(Extension_Loaded('zlib')) Ob_End_Flush();
?>