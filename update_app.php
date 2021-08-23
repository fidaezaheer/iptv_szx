<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="content-type" content="text/html; charset=utf-8" />
<title>404</title>
<style type="text/css">
* { margin:0; padding:0; list-style:none; font-size:14px;}/*---该css reset仅用于demo，请自行换成适合您自己的css reset---*/
html { height:100%;}
body { height:100%; text-align:center;}
.centerDiv { display:inline-block; zoom:1; *display:inline; vertical-align:middle; text-align:left; width:576px; padding:0px; font-size: 36px; color: #FF0;}
.hiddenDiv { height:100%; overflow:hidden; display:inline-block; width:1px; overflow:hidden; margin-left:-1px; zoom:1; *display:inline; *margin-top:-1px; _margin-top:0; vertical-align:middle;}
</style>
</head>
<?php 
 	$lang_array = array(
		"code_text1"=>"提示：检测到新版本，请升级后使用！",
		"code_text2"=>"操作步骤:",
		"code_text3"=>"1.按遥控器上的\"MENU\" 键，选择\"升级\"进行升级应用",
		"code_text4"=>"2.确定升级后，耐心等待下载，按提示操作",
		"code_text5"=>"3.如无法正常使用，请与你的服务商联系！",
		
		"code_text6"=>"Notice：New version are available，Please update！",
		"code_text7"=>"Step:",
		"code_text8"=>"  Select the update button to do the update!",
		"code_text9"=>"  Then waiting for the download,be patient!,thanks",
		"code_text10"=>"  Any problem，Contact dealer！"
		
	);
	
	
	$action = "Device authorization code：" . $_GET["number"] . "";
	
?>
<script>

function on_keyback()
{
	window.Authentication.exitApp();
}

function show_id()
{
	var mac = window.Authentication.CTCGetMac();
	var id = window.Authentication.CTCGetID();
	var text = "MAC: " + mac;
	document.getElementById("li_id").innerHTML = text;
	
	window.Authentication.showUpdateUI();
}

</script>

<body onload="show_id()">
<div class="centerDiv">
		<label style="font-size:24px;color:F00"><?php echo $action; ?></label>
        <br/>
        <label id="li_id" style="font-size:24px;color:#FFF"></label>
        <br/>
        <label style="font-size:24px;color:#FFF"><?php echo $lang_array["code_text1"]; ?></label>
        <br/>
        <label style="font-size:24px;color:#FFF"><?php echo $lang_array["code_text6"]; ?></label>
        <br/>
			<font color="#FFF"><li style="font-size:20px;color:#FFF"><?php echo $lang_array["code_text2"]; ?></li></font>
            <font color="#FFF"><li style="font-size:20px;color:#FFF"><?php echo $lang_array["code_text7"]; ?></li></font>
			<font color="#FFF"><li style="font-size:20px;color:#FFF"><?php echo $lang_array["code_text3"]; ?></li></font>
            <font color="#FFF"><li style="font-size:20px;color:#FFF"><?php echo $lang_array["code_text8"]; ?></li></font>
			<font color="#FFF"><li style="font-size:20px;color:#FFF"><?php echo $lang_array["code_text4"]; ?></li></font>
            <font color="#FFF"><li style="font-size:20px;color:#FFF"><?php echo $lang_array["code_text9"]; ?></li></font>
			<font color="#FFF"><li style="font-size:20px;color:#FFF"><?php echo $lang_array["code_text5"]; ?></li></font>
            <font color="#FFF"><li style="font-size:20px;color:#FFF"><?php echo $lang_array["code_text10"]; ?></li></font>
    </div><div class="hiddenDiv"></div>
</body>
</html>