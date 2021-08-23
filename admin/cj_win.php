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
                                <div class="muted pull-left">API视频资源库定时采集</div>
                            </div>
                            <div class="block-content collapse in">
                                <div class="span12">   
                                   	<form class="form-horizontal" name="authform" method="post" action="cj_wait.php" target="_blank">
                                    <table cellpadding="0" cellspacing="0" border="0" class="table table-bordered">
                                        <tr>
                                            <td width="50%" rowspan="4">
                                                <select name="dsurl[]" style="width:500px" multiple>
												<?php
												    foreach($namess as $names){
														echo "<option value=\"cj_show.php?cjid=".$names[0]."&appkey=".$names[3]."&action=days&xmlurl=".base64_encode($names[2])."&h=24\">". $names[0] ."、". $names[1] ." ". $names[2] ."</option>";
													}
												?>	
												</select>
                                            </td>
                                        </tr>
                                        <tr><td>采集当天数据频率(分钟)： <input name="dscaiji" type="text"  value="30" class="w100"/></td></tr>
                                        <tr><td>使用方法：从左侧选择需要定时采集的资源库，并设定定时频率提交即可。</td></tr>
                                        <tr><td>注：为节约服务器资源，不需要定时操作的模块，请填写0。</td></tr>
                                        <tr><td colspan="2" style="text-align:center"><input class="btn btn-primary" type="submit" value="执行定时任务"/></td></tr>
                                    </table>
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
     
    </body>

</html>

<?php
	$sql->disconnect_database();

?>