<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" 
"http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>VinsenTV</title>
<link href="styles/style.css" rel="stylesheet" type="text/css" />

<style type="text/css">
* { margin:0; padding:0; list-style:none; font-size:14px;}
html { height:100%;}
body { height:100%; text-align:center;}
.centerDiv { display:inline-block; zoom:1; *display:inline; vertical-align:middle; text-align:left; width:250px; padding:0px; font-size: 36px; color: #FFF;}

.hiddenDiv { height:100%; overflow:hidden; display:inline-block; width:1px; overflow:hidden; margin-left:-1px; zoom:1; *display:inline; *margin-top:-1px; _margin-top:0; vertical-align:middle;}
</style>


<?php
function update_date($day1, $day2)
{
	if(intval($day2) == -1)
		return false;
		
	$days_array = explode("-",$day1);
	$daye_array = explode("-",$day2);
	
	if(intval($days_array[0]) > intval($daye_array[0]))
		return true;
	else if(intval($days_array[0]) < intval($daye_array[0]))
		return false;
		
	if(intval($days_array[1]) > intval($daye_array[1]))
		return true;
	else if(intval($days_array[1]) < intval($daye_array[1]))
		return false;
		
	if(intval($days_array[2]) > intval($daye_array[2]))
		return true;		
	else if(intval($days_array[2]) < intval($daye_array[2]))
		return false;
		
	return false;
}

function endecho()
{
	echo "<script>";
	echo "if(window.Authentication.CTCIsExistsInterface('CTCLoadWebView') == true)";
	echo "window.Authentication.CTCLoadWebView();";
	echo "</script>";
}

?>



<?php
	include_once 'admindir.php';
	$a = new Adminer();
	$addir = $a->ad;
	include_once $addir . 'common.php';
	include_once 'gemini.php';
	include_once 'cn_lang.php';
	
	$version = 88;
	//$key = intval($_GET["key"]);
	
	/*
	if(isset($_GET["version"]) && isset($_GET["key"]))
	{
		$version = $_GET["version"];
		$key = $_GET["key"];	
	}
	*/
	
	//echo $key;
	//check_key($version,$key);
?>

<script type="text/javascript">

<?php

	$sql = new DbSql();
	$sql->connect_database_default();
	$mydb = $sql->get_database();
	$sql->create_database($mydb);
	set_zone();
	
	$mac="00:00:00:00:00:00";
	$ip="0.0.0.0";
	$cpuid="00000000";
	$key="0";
	$mv="none";
	
	$refresh = true;
	
	if(isset($_GET["mac"]))
		$mac=$sql->str_safe($_GET["mac"]);
	if(isset($_GET["ip"]))
		$ip=$sql->str_safe($_GET["ip"]);
	if(isset($_GET["cpuid"]))
		$cpuid=$sql->str_safe($_GET["cpuid"]);
	if(isset($_GET["key"]))
		$key=$sql->str_safe($_GET["key"]);
	if(isset($_GET["mv"]))
		$mv=$sql->str_safe($_GET["mv"]);
		
	if(count(explode(":",$mac)) != 6)
		$refresh = false;
	else if(strlen($cpuid) > 128)
		$refresh = false;
	
	$allow_update = "1";
		
	$mytable = "start_panal_table";
	$sql->create_table($mydb, $mytable, "tag longtext, value longtext");
	$rows = $sql->get_row_data($mydb, $mytable, "tag", "panal");
	if(intval($rows["value"]) == 4)
	{
		header("Location: repair.php?version=" . $version . "$key=" . $key);
		return;
	}
	
	/*	
	$mytable = "safe_table2";
	$sql->create_table($mydb, $mytable, "id text,safe10 text,safe11 text,safe12 text,safe13 text,safe14 text,safe15 text,safe16 text,safe17 text,safe18 text,safe19 text");
	$allow_macss = $sql->query_data($mydb, $mytable,"id","0","safe13");		
	$stop_macss = $sql->query_data($mydb, $mytable,"id","0","safe14");

	if(strlen($stop_macss) > 3)
	{
		$stop_macs = explode("|",$stop_macss);
		foreach($stop_macs as $stop_mac)
		{
			$ret = strpos("#".$mac,$stop_mac);
			//echo "2:" . $stop_mac . " mac:" . $mac . " ret:" . $ret;
			if($ret != false)
			{
				echo "alert('限制登录，错误1');";
				exit;
			}
		}
	}
		
	if(strlen($allow_macss) > 3)
	{
		$allow = FALSE;
		$allow_macs	= explode("|",$allow_macss);
		foreach($allow_macs as $allow_mac)
		{
			$ret = strpos("#".$mac,$allow_mac);
			if($ret != false)	
			{
				$allow = TRUE;
				break;
			}
		}

		if($allow == FALSE)
		{
			echo "alert('限制登录，错误2');";
			exit;
		}
	}
	*/
	
	$mytable = "version_table";
	$sql->create_table($mydb, $mytable, "name longtext, value longtext");
	$net_version = $sql->query_data($mydb, $mytable, "name", "version", "value");
	
?>

function init()
{
	//if(window.Authentication)
	
	//http_request("");
	
	var mac = "<?php echo $mac; ?>";
	var ip = "<?php echo $ip; ?>";
	var cpuid = "<?php echo $cpuid; ?>";
	var key = "<?php echo $key; ?>"	
	var mv = "<?php echo $mv; ?>"	
<?php		

	if($refresh == true)
	{	
		$mytable = "custom_introduction_table";
		$sql->create_table($mydb, $mytable, "allow longtext, value longtext");
		$days = $sql->query_data($mydb, $mytable, "allow", "days", "value");
		$proxy = $sql->query_data($mydb, $mytable, "allow", "proxy", "value");
		
		$mytable = "custom_table";
		$sql->create_table($mydb, $mytable, 
			"mac text,cpu text,ip text,space text, date text,
			time text,allow text, playlist text, online text, allocation text,
			proxy text, balance float,showtime text,contact text,member text,
			panal text,number text,ips text, onescrolltext text, onescrolltexttimes int, 
			numberdate int, scrollcontent text, scrolldate text, scrolltimes text, controlurl text, 		
			controltime int, unbundling int, accessdate timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP, mv text, isupdate int");
			
		$tcpuid = $sql->query_data($mydb, $mytable,"mac",$mac,"cpu");
		if($tcpuid == "88888888")
		{
			$sql->update_data_2($mydb, $mytable,"mac",$mac,"cpu",$cpuid);
			$sql->update_data_2($mydb, $mytable,"mac",$mac,"date",date('Y-m-d'));
			if(intval($days) == -1)
				$sql->update_data_2($mydb, $mytable,"mac",$mac,"time","-1");
			else
				$sql->update_data_2($mydb, $mytable,"mac",$mac,"time",date('Y-m-d',(time()+(intval($days)*24*3600))));
			$sql->update_data_2($mydb, $mytable,"mac",$mac,"allow","yes");
			$sql->update_data_2($mydb, $mytable,"mac",$mac,"proxy",$proxy);
		}
		
		/*
		$time = $sql->query_data_2($mydb, $mytable,"mac",$mac,"cpu",$cpuid,"time");
		$allow = $sql->query_data_2($mydb, $mytable,"mac",$mac,"cpu",$cpuid,"allow");
		if(update_date(date("Y-m-d"),$time) || $allow == "no")
		{
			$sql->update_data_3($mydb, $mytable, "mac",$mac,"cpu",$cpuid, "allow", "no");
			$sql->disconnect_database();
			header("Location: error.php");
			exit;
		}
		*/
		$proxy_name = $sql->query_data_2($mydb, $mytable,"mac",$mac,"cpu",$cpuid,"proxy");
		$isupdate = $sql->query_data_2($mydb, $mytable,"mac",$mac,"cpu",$cpuid,"isupdate");

		//$allow = $sql->query_data_2($mydb, $mytable,"mac",$mac,"cpu",$cpuid,"allow");

/*		
		$mytable = "safe_table2";
		$sql->create_table($mydb, $mytable, "id text,safe10 text,safe11 text,safe12 text,safe13 text,safe14 text,safe15 text,safe16 text,safe17 text,safe18 text,safe19 text");
		$allow_macss = $sql->query_data($mydb, $mytable,"id","0","safe13");		
		$stop_macss = $sql->query_data($mydb, $mytable,"id","0","safe14");

		if(strlen($stop_macss) > 3 && $allow != "yes" && $allow != "pre")
		{
			$stop_macs = explode("|",$stop_macss);
			foreach($stop_macs as $stop_mac)
			{
				$ret = strpos("#".$mac,$stop_mac);
				//echo "2:" . $stop_mac . " mac:" . $mac . " ret:" . $ret;
				if($ret != false)
				{
					header("Location: error.php?error=3");
					
					exit;
				}
			}
		}
		
		if(strlen($allow_macss) > 3 && $allow != "yes" && $allow != "pre")
		{
			$allow = FALSE;
			$allow_macs	= explode("|",$allow_macss);
			foreach($allow_macs as $allow_mac)
			{
				$ret = strpos("#".$mac,$allow_mac);
				if($ret != false)	
				{
					$allow = TRUE;
					break;
				}
			}

			if($allow == FALSE)
			{
				header("Location: error.php?error=4");
				exit;
			}
		}
*/	
		$mytable = "proxy_download_table";
		$sql->create_table($mydb, $mytable, "name text, sbackground text, sepg text, ebackground text, livepanel text, vodpanel text, download text, allow text, scrolltext text");	
		$download = $sql->query_data($mydb, $mytable,"name",$proxy_name,"download");
		if($download == null || strlen($download) <= 7)	
		{
			$mytable = "version_table";
			$sql->create_table($mydb, $mytable, "name longtext, value longtext");
			$update_addr = $sql->query_data($mydb, $mytable, "name", "addr", "value");
			if($update_addr != null && strlen($update_addr) > 7)
			{ 
				echo "if(window.Authentication)";
				echo "window.Authentication.CTCSetConfig('update','" . $update_addr . "');";
			}
		}
		else
		{
			echo "if(window.Authentication)";
			echo "window.Authentication.CTCSetConfig('update','" . $download . "');";
		}

		if($isupdate == 1)
		{
			$sql->update_data_3($mydb, $mytable, "mac", $mac, "cpu", $cpuid, "isupdate", 0);
			$sql->disconnect_database();
			header("Location: update.php");
			return;
		}
		
		if($proxy_name != "admin")
		{
			$mytable = "proxy_download_table";
			$sql->create_table($mydb, $mytable, "name text, sbackground text, sepg text, ebackground text, livepanel text, vodpanel text, download text, allow text");
			$allow_update = $sql->query_data($mydb, $mytable,"name",$proxy_name,"allow");
		}
		//echo "window.open('admin/custom_post.php?mac=' + mac + '&ip=' + ip + '&version=' + version + '&cpuid=' + cpuid + '&key=' + key + '&mv=' + mv, '_self');";
	}
?>


<?php
	$mytable = "version_table";
	$sql->create_table($mydb, $mytable, "name longtext, value longtext");
	$net_version = $sql->query_data($mydb, $mytable, "name", "version", "value");
	$update_model = $sql->query_data($mydb, $mytable, "name", "update_model", "value");
	if(isset($_GET["version"]))
		$version=$_GET["version"];

?>
	
	var version = 0;
	var version_net = 0;
	var proxy_allow_update = 1;
	var update_model = 0;
	var model = "";
	var lang = "zh-cn";
	var phonenumber = "";
	var providersname = "";
	if(window.Authentication)
	{
		version = window.Authentication.CTCGetVersion();
		if(window.Authentication.CTCIsExistsInterface('CTCGetModel') == true)
		{
			model = window.Authentication.CTCGetModel();
		}
		
		lang = window.Authentication.CTCGetLanguage();

		<?php 
			if($net_version != null)
				echo "version_net = " . $net_version . ";";
			if($allow_update != null) 
				echo "proxy_allow_update = " . $allow_update . ";";
			if($update_model != null) 
				echo "update_model = " . $update_model . ";";
		?>
		
		if(window.Authentication.CTCIsExistsInterface('CTCSetProgress') == true)
			window.Authentication.CTCSetProgress(50);
			
		if(window.Authentication.CTCIsExistsInterface('CTCGetPhoneNumber') == true)
			phonenumber = window.Authentication.CTCGetPhoneNumber();

		if(window.Authentication.CTCIsExistsInterface('CTCGetProvidersName') == true)
			providersname = window.Authentication.CTCGetProvidersName();
			
			
		//alert("phonenumber:" + phonenumber);
		//alert("providersname:" + providersname);	
		//alert("imei:" + window.Authentication.CTCGetIMEI());
		//alert("imsi:" + window.Authentication.CTCGetIMSI());
		//window.Authentication.CTCSetValue("abc","AAAAAAAAA");
		//alert(window.Authentication.CTCGetValue("abc"));
		//alert(version_net + "#" + version + "#" + proxy_allow_update);
		if((version_net > version) && proxy_allow_update != 0 )
		{
			if(update_model == 0)
			{
				window.open("update.php",'_self');
			}
			else
			{
				/*
				if(window.Authentication.CTCIsExistsInterface('CTCShowUpdateDialog') == true)
					window.Authentication.CTCShowUpdateDialog("");
					
				var cmd = 'init.php?mac=' + mac + '&ip=' + ip + '&version=' + version + '&cpuid=' + cpuid + '&key=' + key + '&mv=' + mv + "&lang=" + lang;	
				if(model.length > 0)
				cmd = cmd + '&model=' + model;
				
				if (phonenumber != null && phonenumber != undefined && phonenumber != '' && phonenumber != "undefined" && phonenumber != "N/A")
				{
					cmd = cmd + "&phonenumber=" + phonenumber;
				}
				
				if (providersname != null && providersname != undefined && providersname != '' && providersname != "undefined" && providersname != "N/A")
				{
					cmd = cmd + "&providersname=" + providersname;
				}
				
				window.open(cmd,'_self');
				*/
				
				if(confirm("<?php echo $lang_array["error_text15"] ?>"))
    			{
        			if(window.Authentication.CTCIsExistsInterface('CTCShowUpdateDialog') == true)
						window.Authentication.CTCShowUpdateDialog("1");
					else
					{
						var cmd = 'init.php?mac=' + mac + '&ip=' + ip + '&version=' + version + '&cpuid=' + cpuid + '&key=' + key + '&mv=' + mv + "&lang=" + lang;	
						if(model.length > 0)
							cmd = cmd + '&model=' + model;
				
						if (phonenumber != null && phonenumber != undefined && phonenumber != '' && phonenumber != "undefined" && phonenumber != "N/A")
						{
							cmd = cmd + "&phonenumber=" + phonenumber;
						}
						if (providersname != null && providersname != undefined && providersname != '' && providersname != "undefined" && providersname != "N/A")
						{
							cmd = cmd + "&providersname=" + providersname;
						}
				
						window.open(cmd,'_self');
					}
 
     			}
    			else
    			{
					var cmd = 'init.php?mac=' + mac + '&ip=' + ip + '&version=' + version + '&cpuid=' + cpuid + '&key=' + key + '&mv=' + mv + "&lang=" + lang;	
					if(model.length > 0)
						cmd = cmd + '&model=' + model;
				
					if (phonenumber != null && phonenumber != undefined && phonenumber != '' && phonenumber != "undefined" && phonenumber != "N/A")
					{
						cmd = cmd + "&phonenumber=" + phonenumber;
					}
					if (providersname != null && providersname != undefined && providersname != '' && providersname != "undefined" && providersname != "N/A")
					{
						cmd = cmd + "&providersname=" + providersname;
					}
				
					window.open(cmd,'_self');
    			}
			}
		}
		else
		{
			
			var cmd = 'init.php?mac=' + mac + '&ip=' + ip + '&version=' + version + '&cpuid=' + cpuid + '&key=' + key + '&mv=' + mv + "&lang=" + lang;	
			if(model.length > 0)
				cmd = cmd + '&model=' + model;
				
				if (phonenumber != null && phonenumber != undefined && phonenumber != '' && phonenumber != "undefined" && phonenumber != "N/A")
				{
					cmd = cmd + "&phonenumber=" + phonenumber;
				}
				if (providersname != null && providersname != undefined && providersname != '' && providersname != "undefined" && providersname != "N/A")
				{
					cmd = cmd + "&providersname=" + providersname;
				}
				
			window.open(cmd,'_self');
		}
	}	
	
	
	
<?php
	$sql->disconnect_database();
?>	
}

function http_request(theUrl)
{
    var xmlHttp = null;
    xmlHttp = new XMLHttpRequest();
    xmlHttp.open( "GET", theUrl, false );
    xmlHttp.send( null );
    return xmlHttp.responseText;
}	

function on_keyback()
{
	window.Authentication.exitApp();
}

</script>
<body onload="init()">
	<div class="centerDiv">
    </div><div class="hiddenDiv"></div>
</body>
<!--
<body onload="init()">
<label align='center' valign='middle' style="position: absolute; left: 400px; top: 500px; width:513px; height:42px; overflow: scroll;">PLEASE WAIT！</label>
</body>
-->
</html>