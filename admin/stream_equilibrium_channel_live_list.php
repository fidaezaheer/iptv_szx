<?PHP
if(extension_loaded('zlib') && strstr($_SERVER['HTTP_ACCEPT_ENCODING'],'gzip'))
{
	ob_end_clean();
    ob_start('ob_gzhandler');
}
?> 

<!DOCTYPE html>
<?php
	include_once "cn_lang.php";
	include_once "common.php";
	$sql = new DbSql();
	$sql->login();

	$serverip = $_GET["serverip"];
	$cmd = "getsyncxml";
	$ret = send($serverip,$cmd);

	$namess = array();
	
	if(strstr($ret,"?xml") != false && strstr($ret,"version=") != false)
	{
		$doc = new DOMDocument();
		$doc->loadXML($ret);
		$channels = $doc->getElementsByTagName("channel");
		//foreach($channels as $channel)
		foreach($channels as $channel)
		{

			$names = array();
			$urlid = $channel->getElementsByTagName("id")->item(0)->nodeValue;
			$name = $channel->getElementsByTagName("name")->item(0)->nodeValue;
			$url = $channel->getElementsByTagName("url")->item(0)->nodeValue;
			$buffertime = $channel->getElementsByTagName("buffertime")->item(0)->nodeValue;
			$dirpath = $channel->getElementsByTagName("dirpath")->item(0)->nodeValue;
			
			array_push($names,$urlid);
			array_push($names,$name);
			array_push($names,$url);
			array_push($names,$buffertime);
			array_push($names,$dirpath);
			
			array_push($namess,$names);
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
    
    <body onLoad="init()">
        <div class="container-fluid">
            <div class="row-fluid">
              <div class="span13" id="content">
                <div class="row-fluid">
                   <!-- block -->
						<div class="block">
                            <div class="navbar navbar-inner block-header">
                                <div class="muted pull-left">
								<?php 
									echo $lang_array["stream_server_list_text1"];
									if(isset($_GET["serverip"])) 
									{
										echo "&nbsp&nbsp&nbsp&nbsp&nbsp" . $_GET["serverip"];
										if(isset($_GET["tip"]) && strlen($_GET["tip"]) > 0)
										{
											echo "(" . urldecode($_GET["tip"]) . ")";
										}
									}
								?>
                               	</div>
                            </div>
                            
                            <div class="block-content collapse in">
                                    <table cellpadding="0" cellspacing="0" border="0" class="table table-striped table-bordered" >
                                        <thead>
                                            <tr>
            								<th width="3%" style="vertical-align:middle; text-align:center;"><?php echo $lang_array["stream_channel_list_text2"] ?></th>
                                            <th width="4%" style="vertical-align:middle; text-align:center;"><?php echo $lang_array["stream_channel_list_text35"] ?></th>
                                            <th width="8%" style="vertical-align:middle; text-align:center;"><?php echo $lang_array["stream_channel_list_text4"] ?></th>
            								<th width="50%" style="vertical-align:middle; text-align:center;"><?php echo $lang_array["stream_channel_list_text13"] ?></th>
          									<th width="8%" style="vertical-align:middle; text-align:center;"><?php echo $lang_array["stream_channel_list_text6"] ?></th>
                                            <th width="8%" style="vertical-align:middle; text-align:center;"><?php echo $lang_array["stream_channel_list_text5"] ?></th>
            								<th width="5%" style="vertical-align:middle; text-align:center;"><?php echo $lang_array["stream_equilibrium_server_list_text3"] ?></th>
                                            </tr>
                                        </thead>
<?php
                                        echo "<tbody>";
										$index = 0;
										foreach($namess as $names) 
										{
        									echo "<tr>";
											
											$id = $names[0];
											$name = $names[1];
											$url = $names[2];
											$buffertime = $names[3];
											$dirpath = $names[4];
											

  											echo "<td style='word-wrap:break-word; align:center; vertical-align:middle; text-align:center;'>" . $index++ . "</td>";
											echo "<td style='word-wrap:break-word; align:center; vertical-align:middle; text-align:center;'>" . $id . "</td>";
											echo "<td style='word-wrap:break-word; align:center; vertical-align:middle; text-align:center;'>" . $name . "</td>";
											echo "<td style='word-wrap:break-word; align:center; vertical-align:middle; text-align:center;'>" . urldecode($url) . "</td>";
											echo "<td style='word-wrap:break-word; align:center; vertical-align:middle; text-align:center;'>" . $buffertime . "</td>";
											
											if(intval($dirpath) == 0)
											{
												$pdir = $lang_array["stream_channel_list_text17"];
											}
											else if(intval($dirpath) == 1)
											{
												$pdir = $lang_array["stream_channel_list_text18"];
											}
											else if(intval($dirpath) == 2)
											{
												$pdir = $lang_array["stream_channel_list_text19"];
											}
											else if(intval($dirpath) == 3)
											{
												$pdir = $lang_array["stream_channel_list_text20"];
											}
											else if(intval($dirpath) == 4)
											{
												$pdir = $lang_array["stream_channel_list_text21"];
											}
											else if(intval($dirpath) == 5)
											{
												$pdir = $lang_array["stream_channel_list_text22"];
											}
											echo "<td style='word-wrap:break-word; align:center; vertical-align:middle; text-align:center;'>" . $pdir . "</td>";
											echo "<td style='word-wrap:break-word; align:center; vertical-align:middle; text-align:center;'>" . "<a href='#' onclick='delete_channel(\"".$id."\",\"".$serverip."\")'>".$lang_array["del_text1"]."</a>";
											echo "</td>";
        									echo "</tr>";   
										}
                                        echo "<tbody>";        
?>
                                    </table>
                                    <table cellpadding="0" cellspacing="0" border="0" class="table table-bordered">                       
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

		function load_channel(id,serverip)
		{
			var xmlhttp;
			if (window.XMLHttpRequest)
			{
				//  IE7+, Firefox, Chrome, Opera, Safari 浏览器执行代码
				xmlhttp=new XMLHttpRequest();
			}
			else
			{
				// IE6, IE5 浏览器执行代码
				xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
			}
			xmlhttp.onreadystatechange=function()
			{
				if(xmlhttp.readyState==4 && xmlhttp.status==200)
				{
					if(xmlhttp.responseText.indexOf("ReceiveCmdSuccessful") >= 0)
					{
						alert("<?php echo $lang_array["stream_server_list_text23"] ?>");	
					}
					else
					{
						alert("<?php echo $lang_array["stream_server_list_text24"] ?>");
					}
				}
			}
			xmlhttp.open("GET","stream_equilibrium_channel_live_del.php?urlid="+ id + "&serverip=" + serverip,true);
			xmlhttp.send();
		}
		
		
		function delete_channel(id,serverip)
		{
			if(confirm("<?php echo $lang_array["stream_channel_list_text14"] ?>： " + id + " ?") == true)
  			{
				<?php
					echo "window.location.href = \"stream_equilibrium_channel_live_del.php?urlid=\" + id + \"&serverip=\" + serverip;";
				?>
			}
		}
		
        </script>
    </body>

</html>

<?php
	$sql->disconnect_database();
?>

<?PHP
if(extension_loaded('zlib')){ob_end_flush();}
?>