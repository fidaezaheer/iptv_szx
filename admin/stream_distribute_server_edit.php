<!DOCTYPE html>
<?php
	include_once "cn_lang.php";
	include_once "common.php";
	$sql = new DbSql();
	$sql->login();

	$serverid = $_GET["id"];
	
	$sql->connect_database_default();
	$mydb = $sql->get_database();
	$sql->create_database($mydb);	
	
	$mytable = "stream_server_list_table";
	$sql->create_table($mydb, $mytable, "serverid int, serverip text, tip text, os text, version int, info text, online int, channelcount int");
	$namess = $sql->fetch_datas($mydb, $mytable);
	
	
	$mytable = "stream_distribute_server_list_table";
	$sql->create_table($mydb, $mytable, "serverid int, serverip text, tip text, os text, version int, info text, online int, seekcount int, seekid longtext");	
	$serverip = $sql->query_data($mydb, $mytable, "serverid", $serverid, "serverip");
	
	$ret = get_distribute($serverip);
	//echo "serverip:" . $serverip . " ret:" . $ret;
	
	$servers_array = array();
	
	if(strstr($ret,"?xml") != false && strstr($ret,"version=") != false)
	{
		$doc = new DOMDocument();
		$doc->loadXML($ret);

		$servers = $doc->getElementsByTagName("server");
		foreach($servers as $server)
		{
			$ip = $server->getAttribute('ip');
			
			if($serverip == $ip)
			{
				$clients = $server->getElementsByTagName("client");
				
				foreach($clients as $client)
				{
					$client_array = array();
					
					$clientip = $client->getAttribute('ip');
					$clientonline = $client->nodeValue;
				
					array_push($client_array,$serverip);
					array_push($client_array,$clientip);
					array_push($client_array,$clientonline);
				
					array_push($servers_array,$client_array);	
				}
			}
		}	
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
    </head>
    
    <body>
        <div class="container-fluid">
            <div class="row-fluid">
              <div class="span13" id="content">
                <div class="row-fluid">
                   <!-- block -->
						<div class="block">
                            <div class="navbar navbar-inner block-header">
                                <div class="muted pull-left"><?php echo $lang_array["stream_server_list_text1"] ?></div>
                            </div>
                            <div class="block-content collapse in">
                                <div class="span12">   
                           			<div class="table-toolbar">
                                   	</div>
                                    <form class="form-horizontal" name="authform" method="post" action="stream_distribution_seek_set.php" enctype='multipart/form-data'>
                                    <table cellpadding="0" cellspacing="0" border="0" class="table table-bordered">
                                        <thead>
                                            <tr>
                                            <th width="8%" style="vertical-align:middle; text-align:center;"><?php echo $lang_array["stream_distribute_edit_text1"] ?></th>
            								<th width="8%" style="vertical-align:middle; text-align:center;"><?php echo $lang_array["stream_server_list_text5"] ?></th>
            								<th width="12%" style="vertical-align:middle; text-align:center;"><?php echo $lang_array["stream_server_list_text2"] ?></th>
          									<th width="20%" style="vertical-align:middle; text-align:center;"><?php echo $lang_array["stream_server_list_text3"] ?></th>
                                            <th width="30%" style="vertical-align:middle; text-align:center;"><?php echo $lang_array["stream_server_list_text14"] ?></th>
                                            <th width="5%" style="vertical-align:middle; text-align:center;"><?php echo $lang_array["stream_server_list_text21"] ?></th>
                                            <th width="5%" style="vertical-align:middle; text-align:center;"><?php echo $lang_array["stream_server_list_text22"] ?></th>
                                            </tr>
                                        </thead>
<?php
                                        echo "<tbody>";
										
										//$mytable = "stream_channel_list_table";
										//$sql->create_table($mydb, $mytable, "urlid int, name text, url text, online int, cache int, 
										//		path text, tabel text, serverip text, url1 text, url2 text, 
										//		url3 text, tip text, status text, receive text");
										
										foreach($namess as $names) 
										{
        									echo "<tr>";
											
											$checked = 0;
											$online = 0;
											foreach($servers_array as $server_array)
											{
												//echo $server_array[1] . "<br/>";
												//echo $names[1] . "<br/>";
												if($server_array[1] == $names[1])
												{
													$checked = 1;
													$online = $server_array[2];
													break;
												}
											}
											
											echo "<td style='vertical-align:middle; text-align:center;'>";
											if($checked == 1)
											{
												echo "<input class='uniform_on' name='server_checkbox' type='checkbox' style='width:15px;height:25px;' value='" . $names[1] . "' checked/>";
											}
											else
											{
												echo "<input class='uniform_on' name='server_checkbox' type='checkbox' style='width:15px;height:25px;' value='" . $names[1] . "' />";
											}
											echo "</td>";
  											echo "<td style='align:center; vertical-align:middle; text-align:center;'>" . $names[0] . "</td>";
                                        	echo "<td style='align:center; vertical-align:middle; text-align:center;'>" . $names[1] . "</td>";
											echo "<td style='align:center; vertical-align:middle; text-align:center;'>" . $names[2] . "</td>";
											echo "<td style='align:center; vertical-align:middle; text-align:left;'>" . $info_text . "</td>";
											echo "<td style='align:center; vertical-align:middle; text-align:center;'>" . $online . "</td>";
											echo "<td style='align:center; vertical-align:middle; text-align:center;'>" . $names[7] . "</td>";
											
											
        									echo "</tr>";   
										}
                                        echo "<tbody>";        
?>                       
                                    </table>
                                    
                                    <div class="form-actions">
   										<button type="button" onclick="save('<?php echo $serverid; ?>','<?php echo $serverip; ?>')" class="btn btn-primary"><?php echo $lang_array["save_text1"] ?></button>
                                		<button type="button" class="btn" onclick="back_page()"><?php echo $lang_array["back_text1"] ?></button>
                            		</div>
                                    
                                    <input name="clientip" id="clientip" type="hidden" value=""/>
                                    <input name="serverip" id="serverip" type="hidden" value=""/>
                                    <input name="serverid" id="serverid" type="hidden" value=""/>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!--/.fluid-container-->

        <link href="vendors/datepicker.css" rel="stylesheet" media="screen">
        <link href="vendors/uniform.default.css" rel="stylesheet" media="screen">
        <link href="vendors/chosen.min.css" rel="stylesheet" media="screen">

        <link href="vendors/wysiwyg/bootstrap-wysihtml5.css" rel="stylesheet" media="screen">
        
        <script src="vendors/jquery-1.9.1.js"></script>
        <script src="bootstrap/js/bootstrap.min.js"></script>
        <script src="vendors/jquery.uniform.min.js"></script>
        <script src="vendors/chosen.jquery.min.js"></script>
        <script src="vendors/bootstrap-datepicker.js"></script>

        <script src="vendors/wysiwyg/wysihtml5-0.3.0.js"></script>
        <script src="vendors/wysiwyg/bootstrap-wysihtml5.js"></script>

        <script src="vendors/wizard/jquery.bootstrap.wizard.min.js"></script>

		<script type="text/javascript" src="vendors/jquery-validation/dist/jquery.validate.min.js"></script>
		<script src="assets/form-validation.js"></script>
        
		<script src="assets/scripts.js"></script>
        
        <script src="bootstrap/js/bootstrap.min.js"></script>
        <script src="vendors/datatables/js/jquery.dataTables.min.js"></script>


        <script src="assets/scripts.js"></script>
        <script src="assets/DT_bootstrap.js"></script>
        
        <script>
		
		jQuery(document).ready(function() {   
	   		FormValidation.init();
		});
	

        $(function() {
            $(".datepicker").datepicker();
            $(".uniform_on").uniform();
            $(".chzn-select").chosen();
            $('.textarea').wysihtml5();

            $('#rootwizard').bootstrapWizard({onTabShow: function(tab, navigation, index) {
                var $total = navigation.find('li').length;
                var $current = index+1;
                var $percent = ($current/$total) * 100;
                $('#rootwizard').find('.bar').css({width:$percent+'%'});
                // If it's the last tab then hide the last button and show the finish instead
                if($current >= $total) {
                    $('#rootwizard').find('.pager .next').hide();
                    $('#rootwizard').find('.pager .finish').show();
                    $('#rootwizard').find('.pager .finish').removeClass('disabled');
                } else {
                    $('#rootwizard').find('.pager .next').show();
                    $('#rootwizard').find('.pager .finish').hide();
                }
            }});
            $('#rootwizard .finish').click(function() {
                alert('Finished!, Starting over!');
                $('#rootwizard').find("a[href*='tab1']").trigger('click');
            });
        });
		
        $(function() {
            
        });
		function GetRadioValue(RadioName){
    		var obj;
    		obj=document.getElementsByName(RadioName);
    		if(obj!=null){
       			var i;
        		for(i=0;i<obj.length;i++)
				{
           	 		if(obj[i].checked){
                		return obj[i].value;
            		}
        		}
    		}
    		return null;
		}

		function get_clientip_value()
		{
			var value = "";
			var value_array = new Array();
			var type_checkboxs = document.getElementsByName("server_checkbox");
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

		function save(serverid,serverip)
		{
			var clientip = get_clientip_value();
			document.authform.serverid.value = serverid;
			document.authform.clientip.value = clientip;
			document.authform.serverip.value = serverip;
			document.authform.submit();
		}
		
		function back_page()
		{
			window.location.href = "stream_distribute_server_list.php";
		}
        </script>
    </body>

</html>

<?php
	$sql->disconnect_database();

?>