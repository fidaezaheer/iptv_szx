<!DOCTYPE html>
<?php
include_once "cn_lang.php";
include_once "common.php";
$sql = new DbSql();
$sql->login();

?>


<html class="no-js">
    <META http-equiv=Content-Type content="text/html; charset=utf-8">
    <head>
        <title><?php echo $lang_array["left_title"]?></title>
        <!-- Bootstrap -->
        <link href="bootstrap/css/bootstrap.min.css" rel="stylesheet" media="screen">
        <link href="bootstrap/css/bootstrap-responsive.min.css" rel="stylesheet" media="screen">
        <link href="vendors/easypiechart/jquery.easy-pie-chart.css" rel="stylesheet" media="screen">
        <link href="assets/styles.css" rel="stylesheet" media="screen">
        <script src="vendors/modernizr-2.6.2-respond-1.1.0.min.js"></script>
    </head>
    
    <body  onLoad="init()">
        <div class="navbar navbar-fixed-top">
            <div class="navbar-inner">
                <div class="container-fluid">
                    <a class="btn btn-navbar" data-toggle="collapse" data-target=".nav-collapse"> <span class="icon-bar"></span>
                     <span class="icon-bar"></span>
                     <span class="icon-bar"></span>
                    </a>
                    <a class="brand" href="#"><?php echo $lang_array["left_title"]?></a>
                    <div class="nav-collapse collapse">
                        <ul class="nav pull-right">
                            <li class="dropdown">
                                <a href="index.php"><i class="icon-user"></i> <?php echo $lang_array["left_text1"] ?></a>
                            </li>
                        </ul>
                        <ul class="nav">
                            <li class="dropdown">
                                <a href="left_1.php" role="button" class="dropdown-toggle"><?php echo $lang_array["left_text2"] ?></a></li>
                            <li class="dropdown">
                                <a href="left_2.php" role="button" class="dropdown-toggle"><?php echo $lang_array["left_text3"] ?></a></li>
                            <li class="dropdown">
                                <a href="left_3.php" role="button" class="dropdown-toggle"><?php echo $lang_array["left_text4"] ?></a></li>
                            <li class="dropdown">
                                <a href="left_4.php" role="button" class="dropdown-toggle"><?php echo $lang_array["left_text5"] ?></a></li>
                            <li class="dropdown">
                                <a href="left_5.php" role="button" class="dropdown-toggle"><?php echo $lang_array["left_text6"] ?></a></li>
                            <li class="dropdown">
                                <a href="left_6.php" role="button" class="dropdown-toggle"><?php echo $lang_array["left_text7"] ?></a></li>
                            <li class="dropdown">
                                <a href="left_7.php" role="button" class="dropdown-toggle"><?php echo $lang_array["left_text8"] ?></a></li> 
                        </ul>
                    </div>
                    <!--/.nav-collapse -->
                </div>
            </div>
        </div>
        <div class="container-fluid">
            <div class="row-fluid">
       	    	<table width="100%" border="0" align="center" height="1080">
  					<tr>
    					<td width="12%" align="center" valign="top" >
                        <ul class="nav nav-list bs-docs-sidenav nav-collapse collapse">
                        <li class="active">
                            <a href="#"><?php echo $lang_array["left6_text1"] ?></a>
                        </li>
                        <li>
                            <a href="safe_list.php" target=mainFrame><i class="icon-chevron-right"></i><?php echo $lang_array["left6_text2"] ?></a>
                        </li>
						</ul>
                        </td>
    					<td width="88%" align="center" valign="top" >
                        <iframe src="safe_list.php" width="100%" height="1080" frameBorder="0" name='mainFrame' id='mainFrame' title='mainFrame' onLoad="iFrameHeight()"></iframe>
                        </td>
  					</tr>
				</table>
          </div>
        </div>
        <!--/.fluid-container-->
        <script src="vendors/jquery-1.9.1.min.js"></script>
        <script src="bootstrap/js/bootstrap.min.js"></script>
        <script src="vendors/easypiechart/jquery.easy-pie-chart.js"></script>
        <script src="assets/scripts.js"></script>
        <script>
        $(function() {
            // Easy pie charts
            $('.chart').easyPieChart({animate: 1000});
        });
		
		function iFrameHeight() {   
			var ifm= document.getElementById("mainFrame");   
			var subWeb = document.frames ? document.frames["mainFrame"].document : ifm.contentDocument;   
			if(ifm != null && subWeb != null) {
  				ifm.height = subWeb.body.scrollHeight;
   				ifm.width = subWeb.body.scrollWidth;
			}   
		} 

		function init()
		{
			document.getElementById('mainFrame').src="safe_list.php";	
		}
        </script>
    </body>

</html>