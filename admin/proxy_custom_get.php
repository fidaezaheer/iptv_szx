<?php
function getIP()
{
	return $_SERVER["REMOTE_ADDR"];
}
?>

<?php
	include_once "common.php";
	$sql = new DbSql();
	$sql->login_proxy();
	
	$sql->connect_database_default();
	$mydb = $sql->get_database();
	$sql->create_database($mydb);

	set_zone();
	
	$mac = $_GET["mac"];
	$cpuid = $_GET["cpuid"];
	
	//$ip = $_GET["ip"];
	$date="null";
	$time="null";
	$allow="no";
	$playlist="null";
	$allocation="null";
	$proxy="null";
	$show="null";

	if(isset($_GET["allow"]))
		$allow=$_GET["allow"];
		
	if(isset($_GET["date"]))
		$date= $_GET["date"];
		
	
	if(isset($_GET["time"]))
		$time= $_GET["time"];

	if(isset($_GET["playlist"]))
		$playlist=$_GET["playlist"];

	
	if(isset($_GET["allocation"]))
		$allocation=$_GET["allocation"];
					
	//if(isset($_COOKIE["user"]))
	//	$proxy=$_COOKIE["user"];

	if(isset($_GET["proxy"]))
		$proxy=$_GET["proxy"];
	
	if(isset($_GET["show"]))
		$show=$_GET["show"];
	
	$contact="null";
	if(isset($_GET["contact"]))
		$contact=$_GET["contact"];

	$panel="0";
	if(isset($_GET["panel"]))
		$panel=$_GET["panel"];
		
	$member="";
	if(isset($_GET["member"]))
		$member=$_GET["member"];
		
	$remarks="";
	if(isset($_GET["remarks"]))
		$remarks=$_GET["remarks"];
				
	$scrollcontent="";
	if(isset($_POST["scrollcontent"]))
		$scrollcontent=$_POST["scrollcontent"];
		
	$mytable = "proxy_table";
	$sql->create_table($mydb, $mytable, "name text, password text, ptip text, edit int");
	$edit = $sql->query_data($mydb, $mytable,"name",$_COOKIE["user"],"edit");
	
	$mytable = "custom_table";
	$sql->create_table($mydb, $mytable, 
	"mac text,cpu text,ip text,space text, date text,
	time text,allow text, playlist text, online text, allocation text,
	proxy text, balance float,showtime text,contact text,member text,
	panal text,number text,ips text, onescrolltext text, onescrolltexttimes int, 
	numberdate int, scrollcontent text, scrolldate text, scrolltimes text, controlurl text, 		
	controltime int, unbundling int, accessdate timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP, mv text, isupdate int,
	remarks text, startime date");

	$content = "";
	
	if(strcmp($date,"null") != 0 && $edit == 1)
	{
		$sql->update_data_3($mydb, $mytable, "cpu", $cpuid, "mac", $mac, "date", $date);
		$sql->update_data_3($mydb, $mytable, "cpu", $cpuid, "mac", $mac, "startime", date("Y-m-d H:i:s"));
		
		if(strcmp($date,"proxy") == 0)
		{
			$content = $content . "授权代理开通; ";
		}
		else
		{
			$content = $content . "授权开始时间:" . $date . "; ";
		}
	}
	
	if(strcmp($time,"null") != 0 && $edit == 1)
	{
		$sql->update_data_3($mydb, $mytable, "cpu", $cpuid, "mac", $mac, "time", $time);

		if(strcmp($date,"proxy") == 0)
		{
			if(strcmp($time,"-1") == 0)
				$content = $content . "分配代理授权时长:永久; ";
			else
				$content = $content . "分配代理授权时长:" . $time . "; ";
		}
		else
		{
			if(strcmp($time,"-1") == 0)
				$content = $content . "分配代理授权时长:永久; ";
			else
				$content = $content . "授权结束时间:" . $time . "; ";
		}
	}
	
	//if($edit == 1)
	{
		$sql->update_data_3($mydb, $mytable, "cpu", $cpuid, "mac", $mac, "allow", $allow);
		$content = $content . "是否授权:" . $allow . "; ";
	}
	
	if(strcmp($playlist,"null") != 0 && $edit == 1)
	{
		$sql->update_data_3($mydb, $mytable, "cpu", $cpuid, "mac", $mac, "playlist", $playlist);

		if(strcmp($playlist,"auto") == 0)
		{
			$content = $content . "列表分配:自动分配" . "; ";
		}
		else if(strcmp($playlist,"all") == 0)
		{
			$content = $content . "列表分配:分配全部" . "; ";
		}
		else
		{
			$type_array = array();
			$mytable = "playlist_type_table";
			$sql->create_table($mydb, $mytable, "name text, space longtext, id text, playlist longtext");
			$type_namess = $sql->fetch_datas($mydb, $mytable);
			foreach($type_namess as $type_names)
			{
				$type_array[$type_names[2]] = $type_names[0];
			}
			$content = $content . "列表手动分配:" . $type_array[$playlist] . "; ";
		}
	}
	
	
	if(strcmp($allocation,"null") != 0 && $edit == 1)
	{
		$mytable = "custom_table";
		$sql->create_table($mydb, $mytable, 
		"mac text,cpu text,ip text,space text, date text,
		time text,allow text, playlist text, online text, allocation text,
		proxy text, balance float,showtime text,contact text,member text,
		panal text,number text,ips text, onescrolltext text, onescrolltexttimes int, 
		numberdate int, scrollcontent text, scrolldate text, scrolltimes text, controlurl text, 		
		controltime int");
		
		$sql->update_data_3($mydb, $mytable, "cpu", $cpuid, "mac", $mac, "allocation", $allocation);
	}
	
	if(strcmp($proxy,"null") != 0 && $edit == 1)
	{
		$mytable = "custom_table";
		$sql->create_table($mydb, $mytable, 
		"mac text,cpu text,ip text,space text, date text,
		time text,allow text, playlist text, online text, allocation text,
		proxy text, balance float,showtime text,contact text,member text,
		panal text,number text,ips text, onescrolltext text, onescrolltexttimes int, 
		numberdate int, scrollcontent text, scrolldate text, scrolltimes text, controlurl text, 		
		controltime int");
		
		$proxy = strval($proxy);
		$sql->update_data_3($mydb, $mytable, "cpu", $cpuid, "mac", $mac, "proxy", $proxy);
		
		if(strcmp($proxy,"admin") == 0)
		{
			$content = $content . "只允许超级管理员操作" . "; ";
		}
		else
		{
			$content = $content . "授权代理:" . $proxy . "; ";
		}
	}
	
	$mytable = "custom_table";
	$sql->create_table($mydb, $mytable, 
	"mac text,cpu text,ip text,space text, date text,
	time text,allow text, playlist text, online text, allocation text,
	proxy text, balance float,showtime text,contact text,member text,
	panal text,number text,ips text, onescrolltext text, onescrolltexttimes int, 
	numberdate int, scrollcontent text, scrolldate text, scrolltimes text, controlurl text, 		
	controltime int, unbundling int, accessdate timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP, mv text, isupdate int");
		
	if(strlen($proxy) > 0)
	{
		$sql->update_data_3($mydb, $mytable, "cpu", $cpuid, "mac", $mac, "showtime", $show);
		$sql->update_data_3($mydb, $mytable, "cpu", $cpuid, "mac", $mac, "contact", $contact);
		$sql->update_data_3($mydb, $mytable, "cpu", $cpuid, "mac", $mac, "panal", $panel);
		$sql->update_data_3($mydb, $mytable, "cpu", $cpuid, "mac", $mac, "member", $member);
		$sql->update_data_3($mydb, $mytable, "cpu", $cpuid, "mac", $mac, "remarks", $remarks);
		$sql->update_data_3($mydb, $mytable, "cpu", $cpuid, "mac", $mac, "scrollcontent", $scrollcontent);
	}
	
	$date = date("Y-m-d H:i:s");
	$mytable = "log_record_table";
	$sql->create_table($mydb, $mytable, "date datetime, user text, mac text, cpuid text, content text, other text, ip text");
	if($sql->find_column($mydb, $mytable, "ip") == 0)
		$sql->add_column($mydb, $mytable,"ip", "text");	
		
	$sql->insert_data($mydb, $mytable, "date, user, mac, cpuid, content, other, ip", $date.", ".$_COOKIE["user"].", ".$mac.", ".$cpuid.", ".$content.", "."null".", ".getIP());
	
	$sql->disconnect_database();
	
	header("Location: proxy_custom_list.php");
?>