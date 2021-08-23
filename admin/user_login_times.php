<!DOCTYPE html>
<?php
		include_once "cn_lang.php";
		include_once 'common.php';
		$sql = new DbSql();
		$sql->login();
		
		$sql->connect_database_default();
		$mydb = $sql->get_database();
		$sql->create_database($mydb);
		$mytable = "custom_tree_table";
		$sql->create_table($mydb, $mytable, "mac text,cpuid text,date text,timers int, logindate datetime");
		if($sql->find_column($mydb, $mytable, "logindate") == 0)
		{	
			$sql->add_column($mydb, $mytable,"logindate", "datetime");
			$sql->delete_data_all($mydb, $mytable);
		}
		
		if(isset($_GET["clear"]) && intval($_GET["clear"]) == 1)
		{
			$sql->delete_table($mydb, $mytable);
		}
		
		$sql->delete_date_little($mydb, $mytable, "logindate", date('Y-m-d H:i:s',strtotime('-1 day'))); 
		
		$namess = $sql->fetch_datas_limit_desc($mydb, $mytable, 0, 200, "timers"); 
		
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
			evernumber longtext, prekey text, cpuinfo text, contactkey text");

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
                                <div class="muted pull-left"><?php echo $lang_array["user_login_times_text8"] ?></div>
                            </div>
                            <div class="block-content collapse in">
                                <div class="span12">                                    
                                    <table cellpadding="0" cellspacing="0" border="0" class="table table-bordered">
                                        <thead>
                                            <tr>
            								<th width="10%" style="vertical-align:middle; text-align:center;"><?php echo $lang_array["user_login_times_text1"] ?></th>
            								<th width="20%" style="vertical-align:middle; text-align:center;"><?php echo $lang_array["user_login_times_text2"] ?></th>
          									<th width="10%" style="vertical-align:middle; text-align:center;"><?php echo $lang_array["user_login_times_text3"] ?></th>
            								<th width="20%" style="vertical-align:middle; text-align:center;"><?php echo $lang_array["user_login_times_text4"] ?></th>
                                            <th width="10%" style="vertical-align:middle; text-align:center;"><?php echo $lang_array["user_login_times_text5"] ?></th>
                                            <th width="10%" style="vertical-align:middle; text-align:center;"><?php echo $lang_array["user_login_times_text6"] ?></th>
                                            <th width="20%" style="vertical-align:middle; text-align:center;"><?php echo $lang_array["user_login_times_text7"] ?></th>
                                            </tr>
                                        </thead>
                                        
<?php       

		foreach($namess as $names) 
		{
			if(strcmp(date("Y-m-d"),$names[2]) == 0)
			{
				$ip = $sql->query_data_2($mydb, $mytable, "mac", $names[0], "cpu", $names[1], "ip");
				$space = $sql->query_data_2($mydb, $mytable, "mac", $names[0], "cpu", $names[1], "space");
				echo "<tr>";
				echo "<td style='vertical-align:middle; text-align:center;'>" . $names[0]. "</td>";
				echo "<td style='vertical-align:middle; text-align:center;'>" . $names[1]. "</td>";
				echo "<td style='vertical-align:middle; text-align:center;'>" . $ip. "</td>";
				echo "<td style='vertical-align:middle; text-align:center;'>" . $space. "</td>";
				echo "<td style='vertical-align:middle; text-align:center;'>" . $names[2]. "</td>";
				echo "<td style='vertical-align:middle; text-align:center;'>" . $names[3]. "</td>";
				echo "<td style='vertical-align:middle; text-align:center;'><a href='custom_list.php?page=0&find=" . $names[0]  . "'>"  . $lang_array["user_login_times_text9"] . "</a>";
				echo "&nbsp;&nbsp;&nbsp;";
				echo "<a href='#' onclick='del_times(\"" . $names[0] . "\",\"". $names[1] . "\")'>". $lang_array["del_text1"] ."</a>";
				echo "</td>";
				echo "</tr>";
			}
		}
?>                                  
                                    </table>
                                    
                                    <div class="form-actions">
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
		
		function back_page()
		{
			window.location.href = "custom_list.php";
		}

		function statistics_clear()
		{
			if(confirm("<?php echo $lang_array["user_login_times_text10"] ?>?") == true)
				window.location.href = "user_login_timers.php?clear=1";
		}
		
		function del_times(mac,cpuid)
		{
			if(confirm("<?php echo $lang_array["user_login_times_text10"] ?>?") == true)
				window.location.href = "user_login_times_del.php?page=0&mac=" + mac + "&cpuid=" + cpuid ;
		}
		
        </script>
    </body>

</html>