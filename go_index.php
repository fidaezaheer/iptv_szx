<?php	
	include_once 'admindir.php';
	
	$a = new Adminer();
	$addir = $a->ad;
	include_once $addir . 'common.php';
	include_once 'gemini.php';

	$terlang = "zh_cn";
	if(isset($_GET["terlang"]))
		$terlang = $_GET["terlang"];
		
	if($terlang == "english")
		include_once "lang_english.php";
	else if($terlang == "tw_cn")
		include_once "lang_tw.php";
	else if($terlang == "hk_cn")
		include_once "lang_hk.php";
	else if($terlang == "kr")
		include_once "lang_kr.php";
	else if($terlang == "ja")
		include_once "lang_ja.php";
	else if($terlang == "spain")
		include_once "lang_spain.php";
	else if($terlang == "portugal")
		include_once "lang_portugal.php";
	else
		include_once "lang_chinese.php";
		
	include_once $addir . 'memcache.php';
	$mem = new GMemCache();
	$usememcache = 0;
	$mem_timeout = 60;
	if($mem->used() == 1)	
	{
		$ret = $mem->connect();
		if($ret == true)
		{
			$usememcache = 1;	
		}
	}

	$md5_logonum = "";
	if(isset($_GET["inputcode"]))	
		$md5_logonum = md5(md5($_GET["inputcode"]));
	$xml_logonum = get_set_xml_file($addir . "safe3.xml");
	
	if(strlen($xml_logonum) > 1 && $xml_logonum != 1 && $xml_logonum != $_GET["inputcode"] )
	{
		//delRandomcodeXML("randomcode.xml",md5($mac.$cpuid));	
		//delXML("xmls/".md5($mac.$cpuid).".xml");
		echo "<script>setTimeout('window.Authentication.CTCLoadWebView()'," . "5000" . ");</script>";
		exit;
	}
	else if($xml_logonum == 1 && (!isset($_COOKIE['vcode']) || !isset($_GET["inputcode"]) || ($_COOKIE['vcode'] != $md5_logonum)))		//开机验证码
	{
		//delRandomcodeXML("randomcode.xml",md5($mac.$cpuid));	
		//delXML("xmls/".md5($mac.$cpuid).".xml");
		echo "<script>setTimeout('window.Authentication.CTCLoadWebView()'," . "5000" . ");</script>";
		exit;
	}
	
	//delRandomcodeXML("randomcode.xml",md5($mac.$cpuid));
	//delXML("xmls/".md5($mac.$cpuid).".xml");
	
	/*
	if(get_set_xml_file($addir . "safe3.xml") == 0)
	{
		$timeout = 3;
		$mem = new GMemCache();
		$ip = $_SERVER["REMOTE_ADDR"];
		if(get_set_xml_file($addir . "safe2.xml") == 1 && $mem->step1(__FILE__) == false)
		{
			echo "<script>setTimeout('window.Authentication.CTCLoadWebView()'," . (intval($timeout))*1000 . ");</script>";
			exit;		
		}
	}
	*/
	
	
	//setcookie("randomcode",$randomcode,time()+3600);
	
	/*
	$mem->connect();
	$content_mem = $mem->get(md5($mac.$cpuid.__FILE__));
	if($content_mem != false)
	{
		$content = "<script>";
		$content = $content . "var randomcode2 = \"" . $randomcode . "\";";
		$content = $content . "var randomcode = \"" . $randomcode . "\";";
		$content = $content . "</script>";
		$content = $content . $content_mem;
		echo $content;
		//file_put_contents('test.txt', $content);
		exit;
	}
	else
	{
		ob_start();	
	}
	*/
	

?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Gemini-Iptv</title>
<link href="styles/style.css" rel="stylesheet" type="text/css" />

<style type="text/css">
* { margin:0; padding:0; list-style:none; font-size:14px;}/*---该css reset仅用于demo，请自行换成适合您自己的css reset---*/
html { height:100%;}
body { height:100%; text-align:center;}
.centerDiv { display:inline-block; zoom:1; *display:inline; vertical-align:middle; text-align:left; width:250px; padding:0px; font-size: 36px; color: #FFF;}

.hiddenDiv { height:100%; overflow:hidden; display:inline-block; width:1px; overflow:hidden; margin-left:-1px; zoom:1; *display:inline; *margin-top:-1px; _margin-top:0; vertical-align:middle;}
</style>

<script type="text/javascript">

</script>

<script type="text/javascript">
var hexcase = 0;  /* hex output format. 0 - lowercase; 1 - uppercase        */
var b64pad  = ""; /* base-64 pad character. "=" for strict RFC compliance   */
var chrsz   = 8;  /* bits per input character. 8 - ASCII; 16 - Unicode      */

/*
 * These are the functions you'll usually want to call
 * They take string arguments and return either hex or base-64 encoded strings
 */
function hex_md5(s){ return binl2hex(core_md5(str2binl(s), s.length * chrsz));}
function b64_md5(s){ return binl2b64(core_md5(str2binl(s), s.length * chrsz));}
function str_md5(s){ return binl2str(core_md5(str2binl(s), s.length * chrsz));}
function hex_hmac_md5(key, data) { return binl2hex(core_hmac_md5(key, data)); }
function b64_hmac_md5(key, data) { return binl2b64(core_hmac_md5(key, data)); }
function str_hmac_md5(key, data) { return binl2str(core_hmac_md5(key, data)); }

/*
 * Perform a simple self-test to see if the VM is working
 */
function md5_vm_test()
{
  return hex_md5("abc") == "900150983cd24fb0d6963f7d28e17f72";
}

/*
 * Calculate the MD5 of an array of little-endian words, and a bit length
 */
function core_md5(x, len)
{
  /* append padding */
  x[len >> 5] |= 0x80 << ((len) % 32);
  x[(((len + 64) >>> 9) << 4) + 14] = len;

  var a =  1732584193;
  var b = -271733879;
  var c = -1732584194;
  var d =  271733878;

  for(var i = 0; i < x.length; i += 16)
  {
    var olda = a;
    var oldb = b;
    var oldc = c;
    var oldd = d;

    a = md5_ff(a, b, c, d, x[i+ 0], 7 , -680876936);
    d = md5_ff(d, a, b, c, x[i+ 1], 12, -389564586);
    c = md5_ff(c, d, a, b, x[i+ 2], 17,  606105819);
    b = md5_ff(b, c, d, a, x[i+ 3], 22, -1044525330);
    a = md5_ff(a, b, c, d, x[i+ 4], 7 , -176418897);
    d = md5_ff(d, a, b, c, x[i+ 5], 12,  1200080426);
    c = md5_ff(c, d, a, b, x[i+ 6], 17, -1473231341);
    b = md5_ff(b, c, d, a, x[i+ 7], 22, -45705983);
    a = md5_ff(a, b, c, d, x[i+ 8], 7 ,  1770035416);
    d = md5_ff(d, a, b, c, x[i+ 9], 12, -1958414417);
    c = md5_ff(c, d, a, b, x[i+10], 17, -42063);
    b = md5_ff(b, c, d, a, x[i+11], 22, -1990404162);
    a = md5_ff(a, b, c, d, x[i+12], 7 ,  1804603682);
    d = md5_ff(d, a, b, c, x[i+13], 12, -40341101);
    c = md5_ff(c, d, a, b, x[i+14], 17, -1502002290);
    b = md5_ff(b, c, d, a, x[i+15], 22,  1236535329);

    a = md5_gg(a, b, c, d, x[i+ 1], 5 , -165796510);
    d = md5_gg(d, a, b, c, x[i+ 6], 9 , -1069501632);
    c = md5_gg(c, d, a, b, x[i+11], 14,  643717713);
    b = md5_gg(b, c, d, a, x[i+ 0], 20, -373897302);
    a = md5_gg(a, b, c, d, x[i+ 5], 5 , -701558691);
    d = md5_gg(d, a, b, c, x[i+10], 9 ,  38016083);
    c = md5_gg(c, d, a, b, x[i+15], 14, -660478335);
    b = md5_gg(b, c, d, a, x[i+ 4], 20, -405537848);
    a = md5_gg(a, b, c, d, x[i+ 9], 5 ,  568446438);
    d = md5_gg(d, a, b, c, x[i+14], 9 , -1019803690);
    c = md5_gg(c, d, a, b, x[i+ 3], 14, -187363961);
    b = md5_gg(b, c, d, a, x[i+ 8], 20,  1163531501);
    a = md5_gg(a, b, c, d, x[i+13], 5 , -1444681467);
    d = md5_gg(d, a, b, c, x[i+ 2], 9 , -51403784);
    c = md5_gg(c, d, a, b, x[i+ 7], 14,  1735328473);
    b = md5_gg(b, c, d, a, x[i+12], 20, -1926607734);

    a = md5_hh(a, b, c, d, x[i+ 5], 4 , -378558);
    d = md5_hh(d, a, b, c, x[i+ 8], 11, -2022574463);
    c = md5_hh(c, d, a, b, x[i+11], 16,  1839030562);
    b = md5_hh(b, c, d, a, x[i+14], 23, -35309556);
    a = md5_hh(a, b, c, d, x[i+ 1], 4 , -1530992060);
    d = md5_hh(d, a, b, c, x[i+ 4], 11,  1272893353);
    c = md5_hh(c, d, a, b, x[i+ 7], 16, -155497632);
    b = md5_hh(b, c, d, a, x[i+10], 23, -1094730640);
    a = md5_hh(a, b, c, d, x[i+13], 4 ,  681279174);
    d = md5_hh(d, a, b, c, x[i+ 0], 11, -358537222);
    c = md5_hh(c, d, a, b, x[i+ 3], 16, -722521979);
    b = md5_hh(b, c, d, a, x[i+ 6], 23,  76029189);
    a = md5_hh(a, b, c, d, x[i+ 9], 4 , -640364487);
    d = md5_hh(d, a, b, c, x[i+12], 11, -421815835);
    c = md5_hh(c, d, a, b, x[i+15], 16,  530742520);
    b = md5_hh(b, c, d, a, x[i+ 2], 23, -995338651);

    a = md5_ii(a, b, c, d, x[i+ 0], 6 , -198630844);
    d = md5_ii(d, a, b, c, x[i+ 7], 10,  1126891415);
    c = md5_ii(c, d, a, b, x[i+14], 15, -1416354905);
    b = md5_ii(b, c, d, a, x[i+ 5], 21, -57434055);
    a = md5_ii(a, b, c, d, x[i+12], 6 ,  1700485571);
    d = md5_ii(d, a, b, c, x[i+ 3], 10, -1894986606);
    c = md5_ii(c, d, a, b, x[i+10], 15, -1051523);
    b = md5_ii(b, c, d, a, x[i+ 1], 21, -2054922799);
    a = md5_ii(a, b, c, d, x[i+ 8], 6 ,  1873313359);
    d = md5_ii(d, a, b, c, x[i+15], 10, -30611744);
    c = md5_ii(c, d, a, b, x[i+ 6], 15, -1560198380);
    b = md5_ii(b, c, d, a, x[i+13], 21,  1309151649);
    a = md5_ii(a, b, c, d, x[i+ 4], 6 , -145523070);
    d = md5_ii(d, a, b, c, x[i+11], 10, -1120210379);
    c = md5_ii(c, d, a, b, x[i+ 2], 15,  718787259);
    b = md5_ii(b, c, d, a, x[i+ 9], 21, -343485551);

    a = safe_add(a, olda);
    b = safe_add(b, oldb);
    c = safe_add(c, oldc);
    d = safe_add(d, oldd);
  }
  return Array(a, b, c, d);

}

/*
 * These functions implement the four basic operations the algorithm uses.
 */
function md5_cmn(q, a, b, x, s, t)
{
  return safe_add(bit_rol(safe_add(safe_add(a, q), safe_add(x, t)), s),b);
}
function md5_ff(a, b, c, d, x, s, t)
{
  return md5_cmn((b & c) | ((~b) & d), a, b, x, s, t);
}
function md5_gg(a, b, c, d, x, s, t)
{
  return md5_cmn((b & d) | (c & (~d)), a, b, x, s, t);
}
function md5_hh(a, b, c, d, x, s, t)
{
  return md5_cmn(b ^ c ^ d, a, b, x, s, t);
}
function md5_ii(a, b, c, d, x, s, t)
{
  return md5_cmn(c ^ (b | (~d)), a, b, x, s, t);
}

/*
 * Calculate the HMAC-MD5, of a key and some data
 */
function core_hmac_md5(key, data)
{
  var bkey = str2binl(key);
  if(bkey.length > 16) bkey = core_md5(bkey, key.length * chrsz);

  var ipad = Array(16), opad = Array(16);
  for(var i = 0; i < 16; i++)
  {
    ipad[i] = bkey[i] ^ 0x36363636;
    opad[i] = bkey[i] ^ 0x5C5C5C5C;
  }

  var hash = core_md5(ipad.concat(str2binl(data)), 512 + data.length * chrsz);
  return core_md5(opad.concat(hash), 512 + 128);
}

/*
 * Add integers, wrapping at 2^32. This uses 16-bit operations internally
 * to work around bugs in some JS interpreters.
 */
function safe_add(x, y)
{
  var lsw = (x & 0xFFFF) + (y & 0xFFFF);
  var msw = (x >> 16) + (y >> 16) + (lsw >> 16);
  return (msw << 16) | (lsw & 0xFFFF);
}

/*
 * Bitwise rotate a 32-bit number to the left.
 */
function bit_rol(num, cnt)
{
  return (num << cnt) | (num >>> (32 - cnt));
}

/*
 * Convert a string to an array of little-endian words
 * If chrsz is ASCII, characters >255 have their hi-byte silently ignored.
 */
function str2binl(str)
{
  var bin = Array();
  var mask = (1 << chrsz) - 1;
  for(var i = 0; i < str.length * chrsz; i += chrsz)
    bin[i>>5] |= (str.charCodeAt(i / chrsz) & mask) << (i%32);
  return bin;
}

/*
 * Convert an array of little-endian words to a string
 */
function binl2str(bin)
{
  var str = "";
  var mask = (1 << chrsz) - 1;
  for(var i = 0; i < bin.length * 32; i += chrsz)
    str += String.fromCharCode((bin[i>>5] >>> (i % 32)) & mask);
  return str;
}

/*
 * Convert an array of little-endian words to a hex string.
 */
function binl2hex(binarray)
{
  var hex_tab = hexcase ? "0123456789ABCDEF" : "0123456789abcdef";
  var str = "";
  for(var i = 0; i < binarray.length * 4; i++)
  {
    str += hex_tab.charAt((binarray[i>>2] >> ((i%4)*8+4)) & 0xF) +
           hex_tab.charAt((binarray[i>>2] >> ((i%4)*8  )) & 0xF);
  }
  return str;
}

/*
 * Convert an array of little-endian words to a base-64 string
 */
function binl2b64(binarray)
{
  var tab = "ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789+/";
  var str = "";
  for(var i = 0; i < binarray.length * 4; i += 3)
  {
    var triplet = (((binarray[i   >> 2] >> 8 * ( i   %4)) & 0xFF) << 16)
                | (((binarray[i+1 >> 2] >> 8 * ((i+1)%4)) & 0xFF) << 8 )
                |  ((binarray[i+2 >> 2] >> 8 * ((i+2)%4)) & 0xFF);
    for(var j = 0; j < 4; j++)
    {
      if(i * 8 + j * 6 > binarray.length * 32) str += b64pad;
      else str += tab.charAt((triplet >> 6*(3-j)) & 0x3F);
    }
  }
  return str;
}

</script>

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
	$version = "";
	
	if(isset($_GET["mac"])  && strlen($_GET["mac"]) == 17)
		$mac=$sql->str_safe($_GET["mac"]);
	if(isset($_GET["ip"]))
		$ip=$sql->str_safe($_GET["ip"]);
	if(isset($_GET["cpuid"]) && strlen($_GET["cpuid"]) <= 33)
		$cpuid=$sql->str_safe($_GET["cpuid"]);
	if(isset($_GET["key"]))
		$key=$sql->str_safe($_GET["key"]);
	if(isset($_GET["mv"]))
		$mv=$sql->str_safe($_GET["mv"]);
		
	if(isset($_GET["version"]) && isset($_GET["key"]))
	{
		$version = $_GET["version"];
		$key = $_GET["key"];	
	}
	
	//echo $key;
	check_key_go_index($version,$key,$mac,$cpuid,$a->ad);
	
	//$randomcode = generate_code(6);	
	//addRandomcodeXML("randomcode.xml",md5($mac.$cpuid),md5(md5($randomcode)));	
	//saveXML("xmls/", md5($mac.$cpuid).".xml", md5(md5($randomcode)));	
/*
	$param = "";
	foreach   ($_GET as $key=>$value)  
	{
 		//echo   "Key: $key; Value: $value <br/>\n ";
		$param = $param . $key . "=" . $value . "&";
	}
	
	if($_SERVER['SERVER_NAME'] == "alltheglobal.com")
	{
		header("Location: http://" . $_SERVER['SERVER_NAME'] . ":18006/gemini-iptv-c/" . $param . "none=0"); 	
	}
*/
?>

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

function generate_code($length = 4) {
    return rand(pow(10,($length-1)), pow(10,$length)-1);
}

?>





<script type="text/javascript">

<?php


	
	$refresh = true;
		
	if(count(explode(":",$mac)) != 6)
		$refresh = false;
	else if(strlen($cpuid) > 128)
		$refresh = false;
	
	$allow_update = "1";
	
	$mytable = "system_table";	
	$terlang = "";
	if($usememcache == 1)
	{
		$memkey = md5($mytable . "query_data" .  "name" . "terlang" . "value");	
		$mem_value = $mem->get($memkey);
		if($mem_value != false)
		{
			$terlang = unserialize($mem_value);
		}
		else
		{
			$sql->create_table($mydb, $mytable, "name text, value text");
			$terlang = $sql->query_data($mydb, $mytable, "name", "terlang", "value");
			$mem->set_timeout($memkey,serialize($terlang),$mem_timeout);
		}
	}
	else
	{
		$sql->create_table($mydb, $mytable, "name text, value text");
		$terlang = $sql->query_data($mydb, $mytable, "name", "terlang", "value");
	}
	
	
	$mytable = "start_panal_table";
	$rows = array();
	if($usememcache == 1)
	{
		$memkey = md5($mytable . "get_row_data" . "tag" . "panal");	
		$mem_value = $mem->get($memkey);
		if($mem_value != false)
		{
			$rows = unserialize($mem_value);
		}
		else
		{
			$sql->create_table($mydb, $mytable, "tag longtext, value longtext");
			$rows = $sql->get_row_data($mydb, $mytable, "tag", "panal");
			$mem->set_timeout($memkey,serialize($rows),$mem_timeout);
		}
		
	}
	else
	{
		$sql->create_table($mydb, $mytable, "tag longtext, value longtext");
		$rows = $sql->get_row_data($mydb, $mytable, "tag", "panal");
	}
	
	if(intval($rows["value"]) == 4)
	{
		header("Location: repair.php?version=" . $version . "$key=" . $key . "&randomcode=" . $randomcode);
		return;
	}
	

	$mytable = "safe_table2";
	$open_prekey = "";
	if($usememcache == 1)
	{
		$memkey = md5($mytable . "query_data" . "id" . "0" . "prekey");	
		$mem_value = $mem->get($memkey);
		if($mem_value != false)
		{
			$open_prekey = unserialize($mem_value);
		}
		else
		{
			$sql->create_table($mydb, $mytable, "id text,safe10 text,safe11 text,safe12 text,safe13 text,safe14 text,safe15 text,safe16 text,safe17 text,safe18 text,safe19 text,model text,logintime int, unbundling int, disabel_model text, limitarea text, prekey text");
			$open_prekey = $sql->query_data($mydb, $mytable,"id","0","prekey");		
			$mem->set_timeout($memkey,serialize($open_prekey),$mem_timeout);
		}
	}
	else
	{
		$sql->create_table($mydb, $mytable, "id text,safe10 text,safe11 text,safe12 text,safe13 text,safe14 text,safe15 text,safe16 text,safe17 text,safe18 text,safe19 text,model text,logintime int, unbundling int, disabel_model text, limitarea text, prekey text");
		$open_prekey = $sql->query_data($mydb, $mytable,"id","0","prekey");		
	}
	
	if($open_prekey == null || strlen($open_prekey) <= 0)
		$open_prekey = 0;
		
	$stop_macss = "";
	if($usememcache == 1)
	{
		$memkey = md5($mytable . "query_data" . "id" . "0" . "safe14");	
		$mem_value = $mem->get($memkey);
		if($mem_value != false)
		{
			$stop_macss = unserialize($mem_value);
		}
		else
		{
			$sql->create_table($mydb, $mytable, "id text,safe10 text,safe11 text,safe12 text,safe13 text,safe14 text,safe15 text,safe16 text,safe17 text,safe18 text,safe19 text,model text,logintime int, unbundling int, disabel_model text, limitarea text, prekey text");
			$stop_macss = $sql->query_data($mydb, $mytable,"id","0","safe14");		
			$mem->set_timeout($memkey,serialize($stop_macss),$mem_timeout);
		}
	}
	else
	{
		$sql->create_table($mydb, $mytable, "id text,safe10 text,safe11 text,safe12 text,safe13 text,safe14 text,safe15 text,safe16 text,safe17 text,safe18 text,safe19 text,model text,logintime int, unbundling int, disabel_model text, limitarea text, prekey text");
		$stop_macss = $sql->query_data($mydb, $mytable,"id","0","safe14");
	}
	
	if(strlen($stop_macss) > 3)
	{
		$stop_macs = explode("|",$stop_macss);
		foreach($stop_macs as $stop_mac)
		{
			$ret = strpos("#".$mac,$stop_mac);
			//echo "2:" . $stop_mac . " mac:" . $mac . " ret:" . $ret;
			if($ret != false)
			{
				echo "alert('" . "Error MAC" . "');</script>";
				//header("Location: error.php?" . "mac=" . $mac . "&cpuid=" . $cpuid . "&version=" . $version . "&key=" . $key . "&error=13" . "&randomcode=" . $randomcode . "&terlang=" . $terlang);
				exit;
			}
		}
	}
		
	/*
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
	$net_version = "";
	$update_model = "";
	$libforcetv_version = "0";
	$libforcetv_open = "0";
	$version_namess = "";
	$mytable = "version_table";
	if($usememcache == 1)
	{
		$memkey = md5($mytable . "fetch_datas");
		$mem_value = $mem->get($memkey);
		if($mem_value != false)
		{
			$version_namess = unserialize($mem_value);
		}
		else
		{
			$sql->create_table($mydb, $mytable, "name longtext, value longtext");
			$version_namess = $sql->fetch_datas($mydb, $mytable);	
			$mem->set_timeout($memkey,serialize($version_namess),$mem_timeout);
		}
	}
	else
	{
		
		$sql->create_table($mydb, $mytable, "name longtext, value longtext");
		$version_namess = $sql->fetch_datas($mydb, $mytable);
	}
	
	foreach($version_namess as $version_names) {
		if($version_names[0] == "version")
		{
			$net_version = $version_names[1];
		}
		else if($version_names[0] == "update_model")
		{
			$update_model = $version_names[1];
		}
		else if($version_names[0] == "libforcetv_version")
		{
			$libforcetv_version = $version_names[1];
		}
		else if($version_names[0] == "libforcetv_open")
		{
			$libforcetv_open = $version_names[1];
		}
	}
	
	

	if(isset($_GET["version"]))
		$version=$_GET["version"];
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

	//if($refresh == true)
	{	
		
		$days = "";
		$proxy = "";
		$mytable = "custom_introduction_table";
		
		$rows = array();
		if($usememcache == 1)
		{
			$memkey = md5($mytable . "fetch_datas");
			$mem_value = $mem->get($memkey);
			if($mem_value != false)
			{
				$rows = unserialize($mem_value);
			}
			else
			{
				$sql->create_table($mydb, $mytable, "allow longtext, value longtext");
				$rows = $sql->fetch_datas($mydb, $mytable);	
				$mem->set_timeout($memkey,serialize($rows),$mem_timeout);
			}			
		}
		else
		{
			$sql->create_table($mydb, $mytable, "allow longtext, value longtext");
			$rows = $sql->fetch_datas($mydb, $mytable);
		}
		
		foreach($rows as $row) {
			if($row[0] == "days")
			{
				$days = $row[1];
			}
			else if($row[0] == "proxy")
			{
				$proxy = $row[1];
			}
		}
		
		//$days = $sql->query_data($mydb, $mytable, "allow", "days", "value");
		//$proxy = $sql->query_data($mydb, $mytable, "allow", "proxy", "value");
		
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
		
		$namess = $sql->fetch_datas_where_2($mydb, $mytable,"mac",$mac,"cpu",$cpuid);
		$proxy_name = "";
		$$isupdate = 0;
		$prekey = "";
		$ghost = 0;
		$number = "";
		$contact = "";
		$contactkey = "";

		if(count($namess) > 0)
		{
			for($ii=0; $ii<count($namess[0]); $ii++)
			{
			
			if($ii == 10)
				$proxy_name = $namess[0][$ii];
			else if($ii == 16)
				$number = $namess[0][$ii];
			else if($ii == 18)
				$contact = $namess[0][$ii];	
			else if($ii == 29)
			{
				$isupdate = $namess[0][$ii];	
				if($isupdate == null)
					$isupdate = 0;
			}
			else if($ii == 38)
			{
				$ghost = $namess[0][$ii];
				if($ghost == null)
					$ghost = 0;
			}
			else if($ii == 41)
			{
				$prekey = $namess[0][$ii];
			}
			else if($ii == 41)
				$contactkey = $namess[0][$ii];
			
			}
		}
		//echo "proxy_name:" . $proxy_name . " X:" . $namess[0][10];
		/*
		$proxy_name = $sql->query_data_2($mydb, $mytable,"mac",$mac,"cpu",$cpuid,"proxy");
		$isupdate = $sql->query_data_2($mydb, $mytable,"mac",$mac,"cpu",$cpuid,"isupdate");
		if($isupdate == null)
			$isupdate = 0;
			
		$prekey = $sql->query_data_2($mydb, $mytable,"mac",$mac,"cpu",$cpuid,"prekey");
		if($prekey == null || strlen($prekey) <= 0)
			$prekey == "";
			
		$ghost = $sql->query_data_2($mydb, $mytable,"mac",$mac,"cpu",$cpuid,"ghost");
		if($ghost == null || strlen($ghost) <= 0)
			$ghost = 0;
		
		$number = $sql->query_data_2($mydb, $mytable,"mac",$mac,"cpu",$cpuid,"number");
		
		$contact = $sql->query_data_2($mydb, $mytable,"mac",$mac,"cpu",$cpuid,"contact");
		$contactkey = $sql->query_data_2($mydb, $mytable,"mac",$mac,"cpu",$cpuid,"contactkey");	
		*/
		
		/*
		$nextkey = generate_code();
		$prekey = $sql->query_data_2($mydb, $mytable,"mac",$mac,"cpu",$cpuid,"prekey");
		$sql->update_data_3($mydb, $mytable,"mac",$mac,"cpu",$cpuid,"prekey",md5($nextkey));
		*/
		
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
		
		//$mytable = "version_table";
		//$sql->create_table($mydb, $mytable, "name longtext, value longtext");



		$mytable = "proxy_download_table";
		$proxy_names = array();
		if($usememcache == 1)
		{
			$memkey = md5($mytable . "fetch_datas_where" . "name" . $proxy_name);
			$mem_value = $mem->get($memkey);
			if($mem_value != false)
			{
				$proxy_names = unserialize($mem_value);
			}
			else
			{
				$sql->create_table($mydb, $mytable, "name text, sbackground text, sepg text, ebackground text, livepanel text, vodpanel text, download text, allow text, watermark text, scrolltext text, version int");
				$proxy_names = $sql->fetch_datas_where($mydb, $mytable,"name",$proxy_name);
				$mem->set_timeout($memkey,serialize($proxy_names),$mem_timeout);
			}			
		}
		else		
		{
			$sql->create_table($mydb, $mytable, "name text, sbackground text, sepg text, ebackground text, livepanel text, vodpanel text, download text, allow text, watermark text, scrolltext text, version int");
			$proxy_names = $sql->fetch_datas_where($mydb, $mytable,"name",$proxy_name);
		}
		
		$download = null;
		$proxy_version = 0;
		if(count($proxy_names) > 0)
		{
			$download = $proxy_names[0][6];
			$proxy_version = intval($proxy_names[0][10]);
		}
		
		//echo "proxy_name:" . $proxy_name;
		//echo "download:" . $download;
		//echo "proxy_version:" . $proxy_version;
		
		if(($download == null || strlen($download) <= 7) || ($update_model == 2)) 	
		{
			$mytable = "version_table";
			$update_addr = "";
			if($usememcache == 1)
			{	
				$memkey = md5($mytable . "query_data" . "name" . "addr" . "value");
				$mem_value = $mem->get($memkey);
				if($mem_value != false)
				{
					$update_addr = unserialize($mem_value);
				}
				else
				{
					$sql->create_table($mydb, $mytable, "name longtext, value longtext");
					$update_addr = $sql->query_data($mydb, $mytable, "name", "addr", "value");
					$mem->set_timeout($memkey,serialize($update_addr),$mem_timeout);
				}			
			}
			else
			{
				$sql->create_table($mydb, $mytable, "name longtext, value longtext");
				$update_addr = $sql->query_data($mydb, $mytable, "name", "addr", "value");
			}
			
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

		//echo "isupdate : " . $isupdate;
		if($isupdate == 1)
		{
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
				evernumber longtext, prekey text");
							
			$sql->update_data_3($mydb, $mytable, "mac", $mac, "cpu", $cpuid, "isupdate", 0);
			$sql->disconnect_database();
			header("Location: update_app.php?number=" . $number);
			exit;
		}
		
		if($proxy_name != "admin")
		{
			//$mytable = "proxy_download_table";
			//$sql->create_table($mydb, $mytable, "name text, sbackground text, sepg text, ebackground text, livepanel text, vodpanel text, download text, allow text, watermark text, scrolltext text, version int");
			//$allow_update = $sql->query_data($mydb, $mytable,"name",$proxy_name,"allow");
			if(count($proxy_names) > 0)
			{
				$allow_update = $proxy_names[0][7];
			}
		}
		//echo "window.open('admin/custom_post.php?mac=' + mac + '&ip=' + ip + '&version=' + version + '&cpuid=' + cpuid + '&key=' + key + '&mv=' + mv, '_self');";
	}
	
	
	if($usememcache == 1)
	{
		$mem->close();	
	}
?>


	if(window.Authentication)
	{
		if(window.Authentication.CTCIsExistsInterface('CTCSetProgress') == true)
			window.Authentication.CTCSetProgress(20);
	}
	
	var version = 0;
	var version_net = 0;
	var proxy_allow_update = 1;
	var update_model = 0;
	var model = "";
	var lang = "zh-cn";
	var phonenumber = "";
	var providersname = "";
	var prekey1 = "<?php echo $prekey; ?>";
	var nextkey = "";
	var open_prekey = <?php echo $open_prekey; ?>;
	var input_prekey = "";
	var randomcode = "<?php echo $randomcode ?>";
	var ghost = <?php echo $ghost; ?>;
	var clear_logintime = 0;
	var cpuinfo = "";
	var terlang = "<?php echo $terlang ?>";
	var sign = "";
	var number = "<?php echo $number ?>";
	var contact = "<?php echo $contact ?>";
	var contactkey = "<?php echo $contactkey ?>";
	var usesms = 0;
	if(window.Authentication)
	{
		if(usesms == 1)
		{
			if(loadsms(contactkey,contact) == 0)
			{
				return 0;
			}
		}
		
		if(window.Authentication.CTCIsExistsInterface('CTCSetProgress') == true)
			window.Authentication.CTCSetProgress(30);
			
		version = window.Authentication.CTCGetVersion();
		if(window.Authentication.CTCIsExistsInterface('CTCGetModel') == true)
		{
			model = window.Authentication.CTCGetModel();
		}
		
		lang = window.Authentication.CTCGetLanguage();

		<?php 
			
			if($update_model != null) 
				echo "update_model = " . $update_model . ";";
				
			if($net_version != null || $proxy_version != null)
			{
				if($proxy_version > intval($net_version) && intval($update_model) != 2)
					echo "version_net = " . $proxy_version . ";";
				else
					echo "version_net = " . $net_version . ";";
			}
			
			if($allow_update != null) 
				echo "proxy_allow_update = " . $allow_update . ";";
		?>
		
		//if(window.Authentication.CTCIsExistsInterface('CTCGetCpuNameInfo') == true)
		//{
			//cpuinfo = window.Authentication.CTCGetCpuNameInfo();
			//alert(cpuinfo);
		//}
		
		if(window.Authentication.CTCIsExistsInterface('CTCSetProgress') == true)
			window.Authentication.CTCSetProgress(50);
			
		/*
		if(window.Authentication.CTCIsExistsInterface('CTCGetPhoneNumber') == true)
			phonenumber = window.Authentication.CTCGetPhoneNumber();

		if(window.Authentication.CTCIsExistsInterface('CTCGetProvidersName') == true)
			providersname = window.Authentication.CTCGetProvidersName();
		*/	
		if(window.Authentication.CTCIsExistsInterface('CTCGetSign') == true)
		{
			sign = hex_md5(window.Authentication.CTCGetSign());
		}
		
		var prekey2 = window.Authentication.CTCGetConfig("prekey");
		//alert("prekey2:" +prekey2);
		//alert(hex_md5(hex_md5(prekey2)) + "----" + prekey1 + "----" + prekey2);
		//if(prekey1.length >= 4 && prekey2.length >= 4 && hex_md5(prekey2) != prekey1 && open_prekey == 1)
		
		if(open_prekey == 1 && prekey1.length == 32 && prekey2.length == 32 && ghost == 3)
		{
			 if(version < 114)
			 {
				 	alert("<?php echo $lang_array["index_text5"] ?>")
					window.Authentication.CTCLoadWebView();
					return;
			 }
			 
			 if(hex_md5(hex_md5(prekey2)) != prekey1)
			 {
				var r = prompt("<?php echo $lang_array["index_text2"] ?>" + ":");
				if(prekey1 != hex_md5(hex_md5(r)))
				{
					alert("<?php echo $lang_array["error_text16"] ?>")
					window.Authentication.CTCLoadWebView();
					return;
				}
				else
				{
					clear_logintime = 1; 
					window.Authentication.CTCSetConfig("prekey",r);	
				}
			 }
			 else
			 {
				 clear_logintime = 1;
			 }
		}
		else if((open_prekey == 1 && prekey1.length == 32 && prekey2.length <= 0) || (open_prekey == 1 && prekey1.length == 32 && prekey2.length <= 0 && ghost == 3))
		{
			if(version < 114)
			{
				alert("<?php echo $lang_array["index_text5"] ?>")
				window.Authentication.CTCLoadWebView();
				return;
			}
			
			var r=prompt("<?php echo $lang_array["index_text2"] ?>" + ":");
			if(prekey1 != hex_md5(hex_md5(r)))
			{
				alert("<?php echo $lang_array["error_text16"] ?>")
				window.Authentication.CTCLoadWebView();
				return;
			}
			else
			{
				clear_logintime = 1;
				window.Authentication.CTCSetConfig("prekey",r);	
			}
		}
		else if(open_prekey == 1 && prekey1.length <= 0 && ghost == 3)
		{
			if(version < 114)
			{
				alert("<?php echo $lang_array["index_text5"] ?>")
				window.Authentication.CTCLoadWebView();
				return;
			}
			
			var input_prekey = prompt("<?php echo $lang_array["index_text1"] ?>" + ":");
			if(input_prekey.length < 4)
			{
				alert("<?php echo $lang_array["index_text4"] ?>");
				window.Authentication.CTCLoadWebView();
				return;	
			}
			clear_logintime = 1;
			alert("<?php echo $lang_array["index_text3"] ?>:" + input_prekey);
			window.Authentication.CTCSetConfig("prekey",input_prekey);	
		}
		
		//alert("clear_logintime:" + clear_logintime);
		/*
		else
		{
			window.Authentication.CTCSetConfig("prekey",nextkey);
		}
		*/
		
		//alert("phonenumber:" + phonenumber);
		//alert("providersname:" + providersname);	
		//alert("imei:" + window.Authentication.CTCGetIMEI());
		//alert("imsi:" + window.Authentication.CTCGetIMSI());
		//window.Authentication.CTCSetValue("abc","AAAAAAAAA");
		//alert(window.Authentication.CTCGetValue("abc"));
		//alert(version_net + "#" + version + "#" + proxy_allow_update);
		var sofile = "libforcetv";
		var sofile_version = "<?php echo $libforcetv_version ?>";
		var sofile_open = "<?php echo $libforcetv_open ?>";
		var terminal_version = window.Authentication.CTCGetConfig(sofile + "_version");
		
		//alert("version_net:" + version_net + " version:" + version + " proxy_allow_update:" + proxy_allow_update + " update_model:" + update_model);
		if(parseInt(sofile_open) <= 0)
		{
			window.Authentication.CTCSetConfig(sofile + "_version","0");
		}
		
		if(parseInt(sofile_open) > 0 && parseInt(sofile_version) > 0 && (terminal_version == "" || (parseInt(terminal_version) < parseInt(sofile_version))))
		{		
			window.open("download_libs.php?version=" + sofile_version,'_self');
		}
		else if((version_net > version) && (proxy_allow_update != 0 || update_model == 2))
		{
			if(update_model == 0 || update_model == 2)
			{
				window.open("update_app.php?number="+number,'_self');
			}
			else if(update_model == 1)
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
				
						if(input_prekey.length >= 4)
						{
							cmd = cmd + "&inputprekey=" + input_prekey;
						}
						
						if(randomcode.length >= 4)
						{
							cmd = cmd + "&randomcode=" + randomcode;
						}
						
						if(clear_logintime == 1)
						{
							cmd = cmd + "&clear_logintime=1";
						}	
					
						if(terlang.length > 0)
						{
							cmd = cmd + "&terlang=" + terlang;
						}
						
						if(sign.length > 0)
						{
							cmd = cmd + "&sign=" + sign;
						}
						//if(cpuinfo.length > 1)
						//{
						//	cmd = cmd + "&cpuinfo=" + cpuinfo;
						//}
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
				
					if(input_prekey.length >= 4)
					{
						cmd = cmd + "&inputprekey=" + input_prekey;
					}
						
					if(randomcode.length >= 4)
					{
						cmd = cmd + "&randomcode=" + randomcode;
					}
					
					if(clear_logintime == 1)
					{
						cmd = cmd + "&clear_logintime=1";
					}
					
					if(terlang.length > 0)
					{
						cmd = cmd + "&terlang=" + terlang;
					}
					
					if(sign.length > 0)
					{
						cmd = cmd + "&sign=" + sign;
					}
					//if(cpuinfo.length > 1)
					//{
					//	cmd = cmd + "&cpuinfo=" + cpuinfo;
					//}
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
				
				if(input_prekey.length >= 4)
				{
					cmd = cmd + "&inputprekey=" + input_prekey;
				}
				
				if(randomcode.length >= 4)
				{
					cmd = cmd + "&randomcode=" + randomcode;
				}
						
				if(clear_logintime == 1)
				{
					cmd = cmd + "&clear_logintime=1";
				}
				
				if(terlang.length > 0)
				{
					cmd = cmd + "&terlang=" + terlang;
				}	
				
				if(sign.length > 0)
				{
					cmd = cmd + "&sign=" + sign;
				}
				
				//if(cpuinfo.length > 1)
				//{
				//	cmd = cmd + "&cpuinfo=" + cpuinfo;
				//}
				window.open(cmd,'_self');
		}
	}	
	
	
	
<?php
	$sql->disconnect_database();
?>	
}

function loadsms(contactkey,contact)
{
	var ctc_contactkey = window.Authentication.CTCGetConfig("contactkey");
	//alert("ctc_contactkey:" + ctc_contactkey + " contactkey:" + contactkey);
	if(ctc_contactkey.length<32 || contactkey.length<32 || contactkey != ctc_contactkey)
	{
			var phone = "";
			if(contact.length <= 6)
				phone = prompt("<?php echo $lang_array["go_index_text1"] ?>");
			else
			{

				phone = contact;
			}
			
			
			
			if(phone.length > 6)
			{
				var len0 = phone.length-5;
				var num = "*******" + phone.substring(len0);
				alert("<?php echo $lang_array["go_index_text2"] ?>:" + num);	
				sendsms("<?php echo $mac ?>","<?php echo $cpuid ?>",phone);	
			}
			else
			{
				alert("<?php echo $lang_array["go_index_text3"] ?>");	
				loadsms(inputcontactkey);
			}
			
				
			var inputcontactkey = prompt("<?php echo $lang_array["go_index_text4"] ?>");
			if(inputcontactkey.length == 6)
			{
				checksms("<?php echo $mac ?>","<?php echo $cpuid ?>",inputcontactkey);	
			}
			else
			{
				alert("<?php echo $lang_array["go_index_text5"] ?>");
				var inputcontactkey = prompt("<?php echo $lang_array["go_index_text6"] ?>");
				if(inputcontactkey.length == 6)
				{
					checksms("<?php echo $mac ?>","<?php echo $cpuid ?>",inputcontactkey);	
				}	
				else
				{
					loadsms(inputcontactkey);
				}
			}
			
			return 0;
	}	
	else
	{
		return 1;	
	}
}

function sendsms(mac,cpuid,phone)
{
	var xmlhttp;
	if (window.XMLHttpRequest)
	{
		xmlhttp=new XMLHttpRequest();
	}
	else
	{
		xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
	}
	xmlhttp.onreadystatechange=function()
	{
				//alert(xmlhttp.status);
		if(xmlhttp.readyState==4 && xmlhttp.status==200)
		{
		}
	}
	
	var url = "send_sms.php?mac="+mac+"&cpuid="+cpuid+"&phone="+phone;
	xmlhttp.open("GET",url,true);
	xmlhttp.send();
}
		
function checksms(mac,cpuid,key)
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
		//alert(xmlhttp.status);
		if(xmlhttp.readyState==4 && xmlhttp.status==200)
		{
			//alert("xmlhttp.responseText:" + xmlhttp.responseText);
			if(xmlhttp.responseText == "SUCCESS")
			{
				//alert("SUCCESS:" + hex_md5(key));
				window.Authentication.CTCSetConfig("contactkey",hex_md5(key));	
				window.Authentication.CTCLoadWebView();
				
			}
			else if(xmlhttp.responseText == "FAIL")
			{
				alert("<?php echo $lang_array["go_index_text5"] ?>");
				var contactkey = prompt("<?php echo $lang_array["go_index_text6"] ?>");
				if(contactkey.length == 6)
				{
					checksms("<?php echo $mac ?>","<?php echo $cpuid ?>",contactkey);	
				}
				else
				{
					alert("<?php echo $lang_array["go_index_text5"] ?>");
					var contactkey = prompt("<?php echo $lang_array["go_index_text6"] ?>");
					if(contactkey.length == 6)
					{
						checksms("<?php echo $mac ?>","<?php echo $cpuid ?>",contactkey);	
					}
				}
			}
		}
	}
	var url = "check_sms.php?mac="+mac+"&cpuid="+cpuid+"&key="+key;
	xmlhttp.open("GET",url,true);
	xmlhttp.send();
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