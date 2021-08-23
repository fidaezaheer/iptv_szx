<!DOCTYPE html>
<?php
	include_once "cn_lang.php";
	include_once "common.php";
	$sql = new DbSql();
	$sql->login();

	$mytable = "cj_list_table";
	$sql->connect_database_default();
	$mydb = $sql->get_database();
	$sql->create_database($mydb);
	$sql->create_table($mydb, $mytable, "id int, name text, url text, appid text, appkey text");	

	$namess = $sql->fetch_datas_order_asc($mydb, $mytable,"id");
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
                                <div class="muted pull-left">API资源站（视频）列表</div>
                            </div>
                            <div class="block-content collapse in">
                                <div class="span12">   
                                   	<div class="table-toolbar">
                                      	<div class="btn-group">
                                         	<a href="cj_add.php"><button class="btn btn-success">添加资源库<i class="icon-plus icon-white"></i></button></a>
                                      	</div>
                                   	</div>
                                                                    
                                    <table cellpadding="0" cellspacing="0" border="0" class="table table-bordered">
                                        <thead>
                                            <tr>
            								<th width="8%" style="vertical-align:middle; text-align:center;">序号</th>
            								<th width="10%" style="vertical-align:middle; text-align:center;">资源库名称</th>
          									<th width="20%" style="vertical-align:middle; text-align:center;">资源库地址</th>
            								<th style="vertical-align:middle; text-align:center;">操作</th>
                                            </tr>
                                        </thead>
<?php
                                        echo "<tbody>";
										
										
										foreach($namess as $names) 
										{
        									echo "<tr>";

  											echo "<td style='align:center; vertical-align:middle; text-align:center;'>" . $names[0] . "</td>";
                                        	echo "<td style='align:center; vertical-align:middle; text-align:center;'>" . $names[1] . "</td>";
											echo "<td style='align:center; vertical-align:middle; text-align:center;'>" . $names[2] . "</td>";
											echo "<td style='vertical-align:middle; text-align:center;'><a href='#' onclick='cj_bind(\"".$names[0]."\",\"".$names[3]."\",\"".base64_encode($names[2])."\")'>".分类转换.
													"</a>&nbsp;&nbsp;<a href='#' onclick='cj_days(\"".$names[0]."\",\"".$names[3]."\",\"".base64_encode($names[2])."\",24)'>".采集当天.
													"</a>&nbsp;&nbsp;<a href='#' onclick='cj_days(\"".$names[0]."\",\"".$names[3]."\",\"".base64_encode($names[2])."\",98)'>".采集本周.
													"</a>&nbsp;&nbsp;<a href='#' onclick='cj_all(\"".$names[0]."\",\"".$names[3]."\",\"".base64_encode($names[2])."\")'>".采集所有.
													"</a>&nbsp;&nbsp;<a href='#' onclick='edit_cj(\"".$names[0]."\")'>".$lang_array["edit_text1"].
													"</a>&nbsp;&nbsp;<a href='#' onclick='delete_cj(\"".$names[0]."\")'>".$lang_array["del_text1"].
													"</a></td>";
											
        									echo "</tr>";   
										}
                                        echo "<tbody>";        
?>                       
                                    </table>
                                    
                                    
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
		
		function cj_bind(id,appkey,url)
		{
			window.location.href = "cj_show.php?cjid=" + id + "&appkey=" + appkey + "&action=show" + "&xmlurl=" + url + "#bind";
		}
		
		function cj_days(id,appkey,url,days)
		{
			window.open("cj_show.php?cjid=" + id + "&appkey=" + appkey + "&action=days" + "&xmlurl=" + url + "&h=" + days);
		}
		
        function cj_all(id,appkey,url)
		{
			window.open("cj_show.php?cjid=" + id + "&appkey=" + appkey + "&action=all" + "&xmlurl=" + url);
		}
		
		function edit_cj(id)
		{
			
			window.location.href = "cj_edit.php?id=" + id;
		}
		
		function delete_cj(id)
		{
			if(confirm("确定删除吗？") == true)
  			{
				window.location.href = "cj_del.php?id=" + id;
			}
		}
        </script>
    </body>

</html>

<?php
	$sql->disconnect_database();

?>