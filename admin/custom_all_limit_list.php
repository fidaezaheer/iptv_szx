<!DOCTYPE html>
<?php
	include_once "cn_lang.php";
	include_once 'common.php';
	$sql = new DbSql();
	$sql->login();
	
	$sql->connect_database_default();
	$mydb = $sql->get_database();
	$sql->create_database($mydb);
	$mytable = "limit_mac_table";
	$sql->create_table($mydb, $mytable, "id int, mac text");
	
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
                                <div class="muted pull-left"><?php echo $lang_array["proxy_list_text3"] ?></div>
                            </div>
                            <div class="block-content collapse in">
                                <div class="span12">
                                   <div class="table-toolbar">
                                   </div>
                                    
                                    <table cellpadding="0" cellspacing="0" border="0" class="table table-striped table-bordered" id="example2">
                                        <thead>
          								<tr>
            							<th width="10%" style="vertical-align:middle; text-align:center;"><?php echo $lang_array["custom_all_limit_list_text2"] ?></th>
            							<th width="40%" style="vertical-align:middle; text-align:center;"><?php echo $lang_array["custom_all_limit_list_text3"] ?></th>
            							<th width="50%" style="vertical-align:middle; text-align:center;"><?php echo $lang_array["custom_all_limit_list_text4"] ?></th>
          								</tr>
                                        </thead>
                                        
		<?php		
				echo "<tbody>";
				foreach($namess as $names) {
					echo "<tr>";
					echo "<td style='vertical-align:middle; text-align:center;'>"."<input name='limit_checkbox' type='checkbox' value='" . trim($names[1]) . "' />"."</td>";
					echo "<td style='vertical-align:middle; text-align:center;'>".trim($names[1])."</td>";
					echo "<td style='vertical-align:middle; text-align:center;'><a href='#' onclick='custom_limit_mac_del(\"".trim($names[1])."\")'>" . $lang_array["del_text1"] . "</a></td>";
					echo "</tr>";
				}
				echo "</tbody>";
        ?>                                       
                                    </table>
                                    
                                    <div class="form-actions">
                                    	<input class="btn btn-primary" name="" type="button" value="全选" onclick="selectAll()"/>
                                        <input class="btn btn-primary" name="" type="button" value="全否" onclick="noAll()"/>
                                        <input class="btn btn-primary" name="" type="button" value="删除选中" onclick="selectDel()">
                                        <input class="btn btn-primary" name="" type="button" value="返回" onclick="custom_back()"/>
                                    </div>
                                    
                                    
                                    <form name="authform_del" action="custom_all_limit_del.php" method="post">  
    								<input name="del" id="del" type="hidden" value=""/>
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

		function custom_limit_mac_del(mac)
		{
			if(confirm("<?php echo $lang_array["custom_all_edit_text17"] ?>" + ": " + mac + " ?") == true)
			{
				if(mac.length > 4)
					window.location.href = "custom_all_limit_del.php?mac="+mac;
			}
		}

		function selectAll() //全选
		{
			//alert(1);
			var objs = document.getElementsByName('limit_checkbox');
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
			var objs = document.getElementsByName('limit_checkbox');
			var i;
			for(i = 0; i < objs.length; i++)
			{
				if(objs[i].type == "checkbox")
				{
					objs[i].checked = false;
				}
			}
		} 

		function selectDel()
		{
			var r=confirm("是否需要批量删除MAC？");
			if(r==true)
  			{	
				document.authform_del.del.value = get_checkbox_value();
      			document.authform_del.submit()
			}	
		}

		function get_checkbox_value()
		{
			var value = "";
			var value_array = new Array();
			var limit_checkboxs = document.getElementsByName("limit_checkbox");
			for(var i = 0; i < limit_checkboxs.length; i++)
			{
				if(limit_checkboxs[i].type == "checkbox" && limit_checkboxs[i].checked)
				{
					value_array.push(limit_checkboxs[i].value);
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

		function custom_back()
		{
			//window.location.href = "version_proxy_edit.php?proxy=" + name;
			var url = "custom_all_edit.php";
			window.location.href = url;
		}
        </script>
    </body>

</html>