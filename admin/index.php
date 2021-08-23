<!DOCTYPE html>

<?php
	
	//header("Location: http://good.123178.net/gemini-iptv/?mac=".$_GET["mac"]."&cpuid=".$_GET["cpuid"]."&key=".$_GET["key"]."&mv=".$_GET["mv"]);
	
	$index_lang = 2;
	if(isset($_GET["lang"]))
	{
		$index_lang = intval($_GET["lang"]);
	}
	
	setcookie("lang", $index_lang);
	if($index_lang != intval($_COOKIE["lang"]))
	{
		header("Location: index.php?lang=" . $index_lang);
	}
	//echo $index_lang;
	//echo "cookie:" . $_COOKIE["lang"];
?>

<?php
	function save_md5_xml($save0)
	{
		$dom = new DOMDocument('1.0', 'UTF-8');  
		$dom->formatOutput = true;  
		$rootelement = $dom->createElement("root");  
		$option = $dom->createElement("option");  
    	$key = $dom->createElement("key", $save0);  
    	$option->appendChild($key);  
    	$rootelement->appendChild($option);  
		$dom->appendChild($rootelement);  
		$filename = "md5.xml"; 
		$dom->save($filename); 
	}

	function get_set_md5_xml()
	{
		$filename = "md5.xml";  
		$dom = new DOMDocument('1.0', 'UTF-8');  
	
		if(!file_exists($filename))
			return 0;

		$dom->load($filename);  
		$key = $dom->getElementsByTagName("key")->item(0)->nodeValue;  
		return $key;
	}

?>

<?php
	error_reporting(E_ALL||~E_NOTICE);

	include_once "cn_lang.php";
	$opts = array(   
  		'http'=>array(   
    	'method'=>"GET",   
    	'timeout'=>3,//单位秒  
   		)   
	);    
 
 	$tip = "";
	//$tip = file_get_contents("http://www.gemini-iptv.com/update_text2.php", false, stream_context_create($opts)); 
	//$tip = iconv("GBK//IGNORE", "UTF-8" , $tip);
	
	$md5_xml = get_set_md5_xml();
	if($md5_xml == 0)
	{
		include_once "number.php";
		$num = new Number();
		
		$content = "<?php class Number{public \$number = '" . md5(md5($num->number)) . "';} ?>";
		$fpp1 = fopen('number.php', 'w');
		fwrite($fpp1, $content) or die('写文件错误');
		fclose($fpp1);
		
		save_md5_xml(2);
	}
	else if($md5_xml == 1)
	{
		include_once "number.php";
		$num = new Number();
		
		$content = "<?php class Number{public \$number = '" . md5($num->number) . "';} ?>";
		$fpp1 = fopen('number.php', 'w');
		fwrite($fpp1, $content) or die('写文件错误');
		fclose($fpp1);
		
		save_md5_xml(2);
	}
?>


<?php
	$captcha = "captcha_linux.php";
	include_once "common.php";
	if(get_server_os() == "WINNT")
	{
		$captcha = "captcha_windows.php";
	}
	

?>
<html>
	<META http-equiv=Content-Type content="text/html; charset=utf-8">
  	<head>
    <title><?php echo $lang_array["login_text1"] ?></title>
    <!-- Bootstrap -->
    <link href="bootstrap/css/bootstrap.min.css" rel="stylesheet" media="screen">
    <link href="bootstrap/css/bootstrap-responsive.min.css" rel="stylesheet" media="screen">
    <link href="assets/styles.css" rel="stylesheet" media="screen">
    <script src="js/vendor/modernizr-2.6.2-respond-1.1.0.min.js"></script>
  	</head>
  	<body onLoad="tip()" id="login">
    	<div class="container">
      		<form class="form-signin" method="post" action="go.php" enctype='multipart/form-data'>
        	<h2 class="form-signin-heading"><?php echo $lang_array["login_text2"] ?></h2>
        	<?php echo $lang_array["login_text4"] ?>:<input type="text" id="user" name="user" class="input-block-level" />
        	<?php echo $lang_array["login_text5"] ?>:<input type="password" id="password" name="password" class="input-block-level" placeholder="Password"/>
            <?php echo $lang_array["login_text6"] ?>:<input type="password" id="number" name="number" class="input-block-level" placeholder="Password"/>
            
            <br/>
            <?php echo $lang_array["lang_text1"] ?>
            <br/>
            <select id="language_id" name="language_id" onChange="reload_page()">
				<option value="1" <?php if($index_lang == 1){echo "selected=\"selected\"";} ?>><?php echo $lang_array["lang_text2"]?></option>;
				<option value="2" <?php if($index_lang == 2){echo "selected=\"selected\"";} ?>><?php echo $lang_array["lang_text3"]?></option>;
			</select>     
            <br/>
			
			<?php echo $lang_array["login_text8"] ?>:<br/><input type="password" id="testid" name="testid" class="input-block-level" style="width: 100px" />&nbsp;&nbsp;&nbsp;<img src="<?php echo $captcha ?>" align="top" style="vertical-align:top;" />
            <br/>
        	<button class="btn btn-large btn-primary" type="submit"><?php echo $lang_array["login_text3"] ?></button>
      		</form>
   		 </div> <!-- /container --> 
         <font size="+1"><marquee width="1280" border="0" align="bottom" scrolldelay="120"  height="395"><?php echo $tip ?></marquee></font>
  	</body>     
    
    <script src="vendors/jquery-1.9.1.min.js"></script>
    <script src="bootstrap/js/bootstrap.min.js"></script>

	<script>
	function tip()
	{
		<?php
		if(isset($_GET["error"]) && ($_GET["error"] == 1 || $_GET["error"] == 3))
		{
			echo "alert(' " . $lang_array["login_text7"]. "！！')";
		}
		else if(isset($_GET["error"]) && $_GET["error"] == 2)
		{
			echo "alert(' " . $lang_array["login_text9"]. "！！')";
		}
		
		?>	
	}
	
	function reload_page()
	{
		var lang = document.getElementById("language_id").value;
		window.location.href = "index.php?lang=" +lang;
	}
</script>
</html>