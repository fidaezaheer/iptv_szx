<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="content-type" content="text/html; charset=utf-8" />
<title></title>
<style type="text/css">
body {
	/*background-image: url(images/error.jpg);*/
}
a:link,a:visited{color:#007ab7;text-decoration:none;}
h1{
	position:relative;
	z-index:2;
	width:540px;
	height:0;
	margin:110px auto 15px;
	padding:230px 0 0;
	overflow:hidden;
	xxxxborder:1px solid;
	background-image: url(main.jpg);
	background-repeat: no-repeat;
}
h2{
	position:absolute;
	top:17px;
	left:187px;
	margin:0;
	font-size:0;
	text-indent:-999px;
	-moz-user-select:none;
	-webkit-user-select:none;
	user-select:none;
	cursor:default;
	width: 534px;
}
h2 em{display:block;font:italic bold 200px/120px "Times New Roman",Times,Serif;text-indent:0;letter-spacing:-5px;color:rgba(216,226,244,0.3);}
.link a{margin-right:1em;}
.link,.texts{width:540px;margin:0 auto 15px;color:#505050;}
.texts{line-height:2;}
.texts dd{margin:0;padding:0 0 0 15px;}
.texts ul{margin:0;padding:0;}
.portal{color:#505050;text-align:center;white-space:nowrap;word-spacing:0.45em;}
.portal a:link,.portal a:visited{color:#505050;word-spacing:0;}
.portal a:hover,.portal a:active{color:#007ab7;}
.portal span{display:inline-block;height:38px;line-height:35px;background:url(img/portal.png) repeat-x;}
.portal span span{padding:0 0 0 20px;background:url(img/portal.png) no-repeat 0 -40px;}
.portal span span span{padding:0 20px 0 0;background-position:100% -80px;}
.STYLE1 {
	font-family: Arial, Helvetica, sans-serif;
	font-size: 65px;
}

* { margin:0; padding:0; list-style:none; font-size:14px;}
html { height:100%;}
body { height:100%; text-align:center;}
.centerDiv { display:inline-block; zoom:1; *display:inline; vertical-align:middle; text-align:left; width:620px; padding:0px; font-size: 36px; color: #FFF;}
.hiddenDiv { height:100%; overflow:hidden; display:inline-block; width:1px; overflow:hidden; margin-left:-1px; zoom:1; *display:inline; *margin-top:-1px; _margin-top:0; vertical-align:middle;}
.Absolute-Center {  
  width: 70%;  
  height: 10%;  
  overflow: auto;  
  margin: auto;  
  position: absolute;  
  left: 0; right: 0;  
}  
</style>
<!--[if lte IE 8]>
<style type="text/css">
h2 em{color:#e4ebf8;}
</style>
<![endif]-->
</head>


<?php
	include_once 'admindir.php';
	$a = new Adminer();
	include_once $a->ad . 'common.php';
	include_once "cn_lang.php";
	
	$sql = new DbSql();	
	
	$version = 88;
	$key = "";
	if(isset($_GET["version"]) && isset($_GET["key"]))
	{
		$version = intval($_GET["version"]); 
		$key = $sql->str_safe($_GET["key"]);	
	}
	check_key_out($version,$key);
		
	$sql->connect_database_default();
	$mydb = $sql->get_database();
	$sql->create_database($mydb);
	$ptip = "";
	$txt_invaild = "";
	
	
	if(isset($_GET["error"]) && intval($_GET["error"]) == 1)
	{
		echo "<script language='javascript' type='text/javascript'>";
		echo "alert('" . $lang_array["error_text1"] . "');";
		echo "</script>";
	}
	else if(isset($_GET["error"]) && intval($_GET["error"]) == 2)
	{
		echo "<script language='javascript' type='text/javascript'>";
		echo "alert('" .$lang_array["error_text2"] . "');";
		echo "</script>";		
	}
	else if(isset($_GET["error"]) && intval($_GET["error"]) == 3)
	{
		echo "<script language='javascript' type='text/javascript'>";
		echo "alert('" .$lang_array["error_text9"] . "');";
		echo "</script>";		
	}
	else if(isset($_GET["error"]) && intval($_GET["error"]) == 4)
	{
		echo "<script language='javascript' type='text/javascript'>";
		echo "alert('" .$lang_array["error_text10"] . "');";
		echo "</script>";		
	}
	else if(isset($_GET["error"]) && intval($_GET["error"]) == 5)
	{
		echo "<script language='javascript' type='text/javascript'>";
		echo "alert('" .$lang_array["error_text11"] . "');";
		echo "</script>";		
	}
	else if(isset($_GET["error"]) && (intval($_GET["error"]) == 6 || intval($_GET["error"]) == 7 || intval($_GET["error"]) == 8))
	{
		echo "<script language='javascript' type='text/javascript'>";
		echo "alert('" .$lang_array["error_text12"] . "');";
		echo "</script>";		
	}
	else if(isset($_GET["error"]) && intval($_GET["error"]) == 9)
	{
		echo "<script language='javascript' type='text/javascript'>";
		echo "alert('" .$lang_array["error_text14"] . "');";
		echo "</script>";		
	}
	
	if(isset($_GET["mac"]) && isset($_GET["cpuid"]))
	{
		$mytable = "custom_table";
		$sql->create_table($mydb, $mytable, 
			"mac text,cpu text,ip text,space text, date text,		
			time text,allow text, playlist text, online text, allocation text, 
			proxy text, balance float,showtime text,contact text,member text,
			panal text,number text,ips text, onescrolltext text, onescrolltexttimes int");
	
		$proxy = "admin";
		$allow = "no";
		$date = "null";
		$time = "null";

		$rows = $sql->fetch_datas_where_2($mydb, $mytable,"mac",$_GET["mac"],"cpu",$_GET["cpuid"]);
		if(count($rows) > 0)
		{
			$proxy = $rows[0][10];
			$allow = $rows[0][6];
			$date = $rows[0][4];
			$time = $rows[0][5];
		}
		
		$mytable = "proxy_table";
		$sql->create_table($mydb, $mytable, "name text, password text, ptip text");
		$ptip = $sql->query_data($mydb, $mytable,"name",$proxy,"ptip");
		
		if($allow == "no" && $date != "null" && $time != "null")
		{
			$mytable = "scroll_table";
			$sql->create_table($mydb, $mytable, "name longtext, value longtext");
			$txt_invaild = $sql->query_data($mydb, $mytable, "name", "txt_invalid", "value");
		}
		//$sql->disconnect_database();
	}
	
?>

<script>

var focusnum = 0;
var focusnum_index = 0;

function on_keyback()
{
	window.Authentication.exitApp();
}

function on_keyleft()
{
	
}

function on_keyenter()
{
	if(window.Authentication)
	{
		var mac = window.Authentication.CTCGetMac();
		var cpuid = window.Authentication.CTCGetID();
		var number = document.getElementById("number").value;
		if(number.length > 8)
		{
			if(confirm( "<?php echo $lang_array["error_text3"] ?>" + ":" + number + " " + "<?php echo $lang_array["error_text4"] ?>" + "?") == true)
  			{
				var cmd = "code.php?mac=" + mac + "&cpuid=" + cpuid + "&number=" + number + "&key=<?php echo $key ?>" + "&version=<?php echo $version ?>";
				window.location.href = cmd;
			}
		}
		else
		{
			alert("<?php echo $lang_array["error_text5"] ?>" + "！");
		}
	}
	else
	{
		alert("<?php echo $lang_array["error_text6"] ?>");	
	}
}

function FSubmit(e)
{
	if(e ==13|| e ==32)
	{
		if(focusnum == 0)
		{
			on_keyenter();
		}
	}
	else (e == 38 || e == 40)
	{
		//if(window.Authentication.CTCIsExistsInterface('CTCOutputKeyBroad') == true)
		//	window.Authentication.CTCOutputKeyBroad();	
		//document.getElementById("number").focus(); 
	}
}

function show_id()
{
	select_num_index(-1);
	var mac = window.Authentication.CTCGetMac();
	var cpuid = window.Authentication.CTCGetID();
	
	//alert("error.php?mac="+mac+"&cpuid="+cpuid);
<?php
	if(!isset($_GET['mac']) || !isset($_GET['cpuid']))
	{
		echo "var cmd = \"error.php?mac=\"+mac+\"&cpuid=\"+cpuid+\"&key=" . $key . "&version=" . $version . "\";";
		echo "window.location.href = cmd;";
	}
	else
	{
		echo "var text2 = \"MAC: \" + mac;";
		echo "document.getElementById(\"li_id2\").innerHTML = text2;";
		echo "var text = \"ID: \" + cpuid;";
		echo "document.getElementById(\"li_id\").innerHTML = text;";
		echo "document.getElementById(\"number\").focus();";		
	}
?>	
	//var member = "<?php //if(isset($_GET['member']))echo $_GET['member'];else echo "NULL";?>";
	var text2 = "MAC: " + mac;
	document.getElementById("li_id2").innerHTML = text2;
	var text = "ID: " + cpuid;
	document.getElementById("li_id").innerHTML = text;
	document.getElementById("number").focus(); 
}


function ondown(e)
{
	switch(e.keyCode)
	{
		
		case 37: //left
			if(focusnum == 1)
			{
				focusnum_index--;
				if(focusnum_index < 0)
					focusnum_index = 11;
				select_num_index(focusnum_index);	
			}
		 	break;
		case 38:	//up
			focusnum = 0;
			focusnum_index = -1;
			select_num_index(focusnum_index);
			document.getElementById("number").onfocus=function(){this.focus();} 
			document.getElementById("number").focus(); 
			break;
		case 39:	//right
			if(focusnum == 1)
			{
				focusnum_index++;
				if(focusnum_index > 11)
					focusnum_index = 0;
				select_num_index(focusnum_index);
			}
			break;
		case 40:	//down
			focusnum = 1;
			focusnum_index = 0;
			select_num_index(focusnum_index);
			document.getElementById("number").onfocus=function(){this.blur();}
			document.getElementById("number").blur();
			break;
			
		case 13:
		case 32:
			if(focusnum == 1)
			{
				var number_value = document.getElementById("number").value;
				if(focusnum_index >= 0 && focusnum_index <= 8)
				{
					document.getElementById("number").value = number_value + (focusnum_index+1);
				}
				else if(focusnum_index == 9)
				{
					document.getElementById("number").value = number_value + 0;
				}
				else if(focusnum_index == 10)
				{
					var num_lenght = number_value.length;
					number_value = number_value.substr(0,num_lenght-1);
					document.getElementById("number").value = number_value;
				}
				else if(focusnum_index == 11)
				{
					on_keyenter();
				}
			}
			break;
		case 48:
		case 49:
		case 50:
		case 51:
		case 52:
		case 53:
		case 54:
		case 55:
		case 56:
		case 57:
			if(focusnum == 1)
			{
				var number_value = document.getElementById("number").value;
				document.getElementById("number").value = number_value + (e.keyCode-48);
				document.getElementById("number").onfocus=function(){this.blur();}
			}
			break;
	} 
}

function red_background(num)
{
	document.getElementById("num"+num).style.backgroundColor="#ff0000";
}

function white_background(num)
{
	document.getElementById("num"+num).style.backgroundColor="#ffffff";
}

function input_num(num)
{
	if(focusnum == 0)
	{
	if(num >= 0 && num <= 8)
	{
		var number_value = document.getElementById("number").value;
		document.getElementById("number").value = number_value + (num+1);
		document.getElementById("number").onfocus=function(){this.blur();}
	}
	else if(num == 9)
	{
		var number_value = document.getElementById("number").value;
		document.getElementById("number").value = number_value + 0;
	}
	else if(num == 10)
	{
		var number_value = document.getElementById("number").value;
		var num_lenght = number_value.length;
		number_value = number_value.substr(0,num_lenght-1);
		document.getElementById("number").value = number_value;
	}
	else if(num == 11)
	{
		on_keyenter();
	}
	}
	
	//red_background(num);
	
	//setTimeout(select_num_index(-1),1000)
}

function select_num_index(index)
{
	for(var ii=0; ii<12; ii++)
	{
		if(ii == index)
		{
			document.getElementById("num"+ii).focus(); 
			document.getElementById("num"+ii).style.backgroundColor="#ff0000";
		}
		else
		{
			document.getElementById("num"+ii).style.backgroundColor="#ffffff";
		}
	}
	
}

</script>

<body onload="show_id()" onkeydown="ondown(event)" onmousewheel="return false;">
	<br/>
    <br/>
    <br/>
    <br/>
	<div class="centerDiv">
    	<br/>
		<label style="font-size:28px;color:#FFF"><?php echo $lang_array["error_text7"] ?>：</label><input name="" type="text" size="16" maxlength="16" id="number" style="font-size:28px;" onkeydown="FSubmit(event.keyCode||event.which);"/ ><!--&nbsp;&nbsp;<input name="" type="button" value=" OK " style="font-size:28px;" onclick=""/>-->
        <br/>
        <br/>
        <label id="li_id2" style="font-size:28px;color:#FFF"></label>
        <br/>
		<label id="li_id" style="font-size:28px;color:#FFF"></label>
        <br/>
<?php
        if(strlen($ptip)>1)
		{
			echo "<label style=\"font-size:28px;color:#FFF\">" . $ptip . "</label>";
		}
		else
		{
			$mytable = "version_table";
			$sql->create_table($mydb, $mytable, "name longtext, value longtext");
			$account_tip = $sql->query_data($mydb, $mytable, "name", "account_tip", "value");
			
			if(strlen($account_tip) > 1)
				echo "<label style=\"font-size:28px;color:#FFF\">" . $account_tip . "</label>";
			else
        		echo "<label style=\"font-size:28px;color:#FFF\">" . $lang_array["error_text8"] . "</label>";
		}
		
		
		
		$sql->disconnect_database();
		
		
?>    
		<br/>
		<label style="font-size:28px;color:#FFF"><?php echo $txt_invaild ?></label>
	</div>
	<br/>
    <br/>
    <br/>
	<table width="720" border="0" class="Absolute-Center">
  		<tr>
    		<td style="color:#000000;font-size:48px;text-align:center; width:120px; height:60px;" id="num0" onclick="input_num(0)" >1</td>
    		<td style="color:#000000;font-size:48px;text-align:center; width:120px; height:60px;" id="num1" onclick="input_num(1)" >2</td>
    		<td style="color:#000000;font-size:48px;text-align:center; width:120px; height:60px;" id="num2" onclick="input_num(2)">3</td>
    		<td style="color:#000000;font-size:48px;text-align:center; width:120px; height:60px;" id="num3" onclick="input_num(3)">4</td>
    		<td style="color:#000000;font-size:48px;text-align:center; width:120px; height:60px;" id="num4" onclick="input_num(4)">5</td>
    		<td style="color:#000000;font-size:48px;text-align:center; width:120px; height:60px;" id="num5" onclick="input_num(5)">6</td>
    		<td style="color:#000000;font-size:48px;text-align:center; width:120px; height:60px;" id="num6" onclick="input_num(6)">7</td>
    		<td style="color:#000000;font-size:48px;text-align:center; width:120px; height:60px;" id="num7" onclick="input_num(7)">8</td>
            <td style="color:#000000;font-size:48px;text-align:center; width:120px; height:60px;" id="num8" onclick="input_num(8)">9</td>
            <td style="color:#000000;font-size:48px;text-align:center; width:120px; height:60px;" id="num9" onclick="input_num(9)">0</td>
            <td style="color:#000000;font-size:32px;text-align:center; width:120px; height:60px;" id="num10" onclick="input_num(10)">Del</td>
            <td style="color:#000000;font-size:32px;text-align:center; width:120px; height:60px;" id="num11" onclick="input_num(11)">OK</td>
  		</tr>
	</table>
</body>
</html>
