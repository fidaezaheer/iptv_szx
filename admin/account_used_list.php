<!DOCTYPE html>
<?php
	include_once "cn_lang.php";
	include_once "common.php";
	$sql = new DbSql();
	$sql->login();
	
	$page = $_GET["page"]
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
                                <div class="muted pull-left"><?php echo $lang_array["account_used_list_text3"] ?></div>
                            </div>
                            
                            <div class="block-content collapse in">
                                <div class="span12">
                                	<form class="form-horizontal" method="post" action="user_post.php" enctype='multipart/form-data'>
                                    	<fieldset>
                                        	<div class="control-group">
                                       		<label class="control-label" for="focusedInput" style="text-shadow:#0CF"><?php echo $lang_array["account_used_list_text3"] ?></label>
                                       			<div class="controls">
                                       			<input style="width:15px;height:28px;" type="radio" name="deltype" value="yes" id="export0" checked/><?php echo $lang_array["account_used_list_text1"] ?>&nbsp;
                                                <br/>
                                                <br/>
                                       			<input style="width:15px;height:28px;" type="radio" name="deltype" value="no" id="export1" /><?php echo $lang_array["account_used_list_text2"] ?>&nbsp;
                                                <input class="input-medium focused" id="day" name="" type="text" size="5" maxlength="5" style="width: 30px"/>&nbsp;<?php echo $lang_array["account_used_list_text4"] ?>
                                                <br/>
                                                <br/>
                                                &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                       			<input name="" class="btn btn-primary" type="button" value="<?php echo $lang_array["del_text1"] ?>" onclick="del_used_batch()"/>
                                       			</div>
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
		
		function del_used_batch()
		{
			var value = GetRadioValue("deltype");
			var day = document.getElementById("day").value;
			if(value == "no" && day.length <= 0)
			{
				alert("<?php echo $lang_array["account_used_list_text5"] ?>");
			}
			else
			{
				var cmd = "account_used_del.php?page=<?php echo $page ?>&day=" + day + "&type=" + value;
				window.location.href = cmd;
			}
		}
        </script>
    </body>

</html>