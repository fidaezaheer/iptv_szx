<?PHP
//if(Extension_Loaded('zlib')) Ob_Start('ob_gzhandler');
//Header("Content-type: text/html");
?> 




<?php
	include_once "cn_lang.php";
	include_once "common.php";
	$sql = new DbSql();
	$sql->login();

	$size = 20;
	$offset = 0;
	$page = 0;
	$online_numrows = 0;
	if(isset($_GET["page"]))
	{
		$offset = $size*intval($_GET["page"]);
		$page = $_GET["page"];
	}

?>

<!DOCTYPE html>
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
                                <div class="muted pull-left"><?php echo $lang_array["left2_text4"] ?></div>
                            </div>
                            <div class="block-content collapse in">
                                <div class="span12">
                                   <form class="form-horizontal" name="authform" method="post" action="" enctype='multipart/form-data'>
                                   <div class="table-toolbar">
                                      <div class="btn-group">
                                        <button type="button" class="btn btn-primary" onclick="clear_content()"><?php echo $lang_array["log_record_list_text1"] ?></button>
                                      </div>
                                      <div style="width:900px;background:white; float:right;">
                                        <input class="input-xxlarge focused" id="find_id" name="find_id" type="text" value="" />
                                        <button type="button" class="btn btn-primary" onclick="find_content()"><?php echo $lang_array["log_record_list_text8"] ?></button>
                                      </div>
                                   </div>
                                    
                                    <table cellpadding="0" cellspacing="0" border="0" class="table table-striped table-bordered" style="table-layout:fixed;">
                                        <thead>
          								<tr>
            							<th width="10%" style="vertical-align:middle; text-align:center;"><?php echo $lang_array["log_record_list_text2"] ?></th>
            							<th width="6%" style="vertical-align:middle; text-align:center;"><?php echo $lang_array["log_record_list_text3"] ?></th>
            							<th width="8%" style="vertical-align:middle; text-align:center;"><?php echo $lang_array["log_record_list_text4"] ?></th>
            							<th width="8%" style="vertical-align:middle; text-align:center;"><?php echo $lang_array["log_record_list_text5"] ?></th>
                                        <th width="8%" style="vertical-align:middle; text-align:center;"><?php echo $lang_array["log_record_list_text7"] ?></th>
            							<th width="60%" style="vertical-align:middle; text-align:center;"><?php echo $lang_array["log_record_list_text6"] ?></th>
          								</tr>
                                        </thead>
                                        
<?php
			$sql->connect_database_default();
			$mydb = $sql->get_database();
			$mytable = "log_record_table";
			$sql->create_database($mydb);
			$sql->create_table($mydb, $mytable, "date datetime, user text, mac text, cpuid text, content text, other text, ip text");
			if($sql->find_column($mydb, $mytable, "ip") == 0)
				$sql->add_column($mydb, $mytable,"ip", "text");
				
			$size = 20;
			$offset = 0;
			$page = 0;
			if(isset($_GET["page"]))
			{
				$offset = $size*intval($_GET["page"]);
				$page = $_GET["page"];
			}
			
			$numrows = $sql->count_fetch_datas($mydb, $mytable);
			$pages = 0;
			$pages = intval($numrows/$size);
			if($numrows%$size)
			{
				$pages++;
			}
			
			$sql->delete_date_little($mydb, $mytable, "date", date('Y-m-d H:i:s',strtotime('-365 day'))); 
			
			//$namess = $sql->fetch_datas_order($mydb, $mytable, "date"); 
			if(isset($_GET["find"]))
			{
				$find = $_GET["find"];
				$namess = $sql->fetch_datas_like_5_or($mydb, $mytable, "mac", "cpuid", "content", "other","ip",$find);
			}
			else
			{
				$namess = $sql->fetch_datas_limit_desc($mydb, $mytable, $offset, $size, "date"); 
				$numrows = count($sql->fetch_datas($mydb, $mytable));
				$pages = 0;
				$pages = intval($numrows/$size);
				if($numrows%$size)
				{
					$pages++;
				}
			}
			
			echo "<tbody>";
			foreach($namess as $names) 
			{
				
				echo "<tr>";
				echo "<td style='vertical-align:middle; text-align:center;word-wrap:break-word;'>" . $names[0]. "</td>";
				echo "<td style='vertical-align:middle; text-align:center;word-wrap:break-word;'>" . $names[1]. "</td>";
				echo "<td style='vertical-align:middle; text-align:center;word-wrap:break-word;'>" . $names[2]. "</td>";
				echo "<td style='vertical-align:middle; text-align:center;word-wrap:break-word;'>" . $names[3]. "</td>";
				echo "<td style='vertical-align:middle; text-align:center;word-wrap:break-word;'>" . $names[6]. "</td>";
				echo "<td style='vertical-align:middle; text-align:center;word-wrap:break-word;'>" . $names[4]. "</td>";
				echo "</tr>";
			}
			echo "</tbody>";
        ?>                                       
                                    </table>
                                    
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
        <!--/.fluid-container-->

        <script src="vendors/jquery-1.9.1.js"></script>
        <script src="bootstrap/js/bootstrap.min.js"></script>
        <script src="vendors/datatables/js/jquery.dataTables.min.js"></script>


        <script src="assets/scripts.js"></script>
        <script src="assets/DT_bootstrap.js"></script>
        
        <script>
		function find_content()
		{
			var value = document.getElementById("find_id").value;
			var url = "log_record_list.php?find=" + value + "&page=0";
			window.location.href = url;
			//window.open(url,"_self");
		}
		
		function clear_content()
		{
			window.location.href = "log_record_del.php";
		}
		
		function delete_user(name)
		{
			if(confirm("是否删除代理商： " + name + " ?") == true)
  			{
				var url = "proxy_del.php?name=" + name;
				window.location.href = url;
  			}
		}

		function edit_user(name)
		{
			//if(confirm("是否删除用户： " + name + " ?") == true)
  			{
				var url = "proxy_edit.php?name=" + name;
				window.location.href = url;
  			}
		}

		function edit_proxy(name)
		{
			//window.location.href = "version_proxy_edit.php?proxy=" + name;
			var url = "proxy_edit.php?proxy=" + name;
			window.location.href = url;
		}
		
		function go_page()
		{
			var pageid = document.getElementById("pageid").value;
			var url = "log_record_list.php?page=" + (pageid-1);
			window.location.href = url + "<?php echo $param ?>";
		}
		
		function go_pre()
		{
<?php
			if($page-1 >= 0)
			{
				echo "var url = 'log_record_list.php?page=".($page-1)."';";	
				echo "window.location.href = url;";
			}
?>
		}
		
		function go_back()
		{
<?php
			if($page+1 < $pages)
			{
				echo "var url = 'log_record_list.php?page=".($page+1)."';";
				echo "window.location.href = url;";
			}
?>			
		}

        </script>
    </body>

</html>

<?PHP
//if(Extension_Loaded('zlib')) Ob_End_Flush();
?>