<?php
function getIP()
{
	return $_SERVER["REMOTE_ADDR"];
}
?>

<?php
	include_once 'common.php';
	
	set_zone();
	
	$sql = new DbSql();
	$sql->login();
	$sql->connect_database_default();
	$mydb = $sql->get_database();
	$sql->create_database($mydb);
	
	
	$mac = $_GET["mac"];
	$cpuid = $_GET["cpuid"];
	
	//$ip = $_GET["ip"];
	$date="null";
	if(isset($_GET["date"]))
		$date= $_GET["date"];
		
	$time="null";
	if(isset($_GET["time"]))
		$time= $_GET["time"];
	
	$allow="no";
	if(isset($_GET["allow"]))
		$allow=$_GET["allow"];
	
	$playlist="null";
	if(isset($_GET["playlist"]))
		$playlist=$_GET["playlist"];

	$allocation="null";
	if(isset($_GET["allocation"]))
		$allocation=$_GET["allocation"];
					
	$proxy="null";
	if(isset($_GET["proxy"]))
		$proxy=$_GET["proxy"];
	
	$show="";
	if(isset($_GET["show"]))
		$show=$_GET["show"];
		
	$contact="";
	if(isset($_GET["contact"]))
		$contact=$_GET["contact"];

	$member="";
	if(isset($_GET["member"]))
		$member=$_GET["member"];
	
	$remarks="";
	if(isset($_GET["remarks"]))
		$remarks=$_GET["remarks"];
				
	$panel="0";
	if(isset($_GET["panel"]))
		$panel=$_GET["panel"];
	
	$scrollcontent="";
	if(isset($_POST["scrollcontent"]))
		$scrollcontent=$_POST["scrollcontent"];
		
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
	
	//if($date != "null")
	if(strcmp($date,"null") != 0)
	{
		//if(strcmp($sql->query_data($mydb,$mytable,"cpu", $cpuid, "mac"),$mac) == 0)
		{
			$sql->update_data_3($mydb, $mytable, "cpu", $cpuid, "mac", $mac, "date", $date);
			$sql->update_data_3($mydb, $mytable, "cpu", $cpuid, "mac", $mac, "startime", date("Y-m-d H:i:s"));
		}
		
		if(strcmp($date,"proxy") == 0)
		{
			$content = $content . "Authorized agent activation; ";
		}
		else
		{
			$content = $content . "Authorization start time:" . $date . "; ";
		}
	}
	
	//if($time != "null")
	if(strcmp($time,"null") != 0)
	{
		//if(strcmp($sql->query_data($mydb,$mytable,"cpu", $cpuid, "mac"),$mac) == 0)
		{
			$sql->update_data_3($mydb, $mytable, "cpu", $cpuid, "mac", $mac, "time", $time);
		}
		
		if(strcmp($date,"proxy") == 0)
		{
			if(strcmp($time,"-1") == 0)
				$content = $content . "Assign agent authorization time:Permanent; ";
			else
				$content = $content . "Assign agent authorization time:" . $time . "; ";
		}
		else
		{
			if(strcmp($time,"-1") == 0)
				$content = $content . "Assign agent authorization time:Permanent; ";
			else
				$content = $content . "Authorization end time:" . $time . "; ";
		}
	}
	
	//if(strcmp($sql->query_data($mydb,$mytable,"cpu", $cpuid, "mac"),$mac) == 0)
	{
		$sql->update_data_3($mydb, $mytable, "cpu", $cpuid, "mac", $mac, "allow", $allow);
		$content = $content . "是否授权:" . $allow . "; ";
	}
	
	//if($playlist != "null")
	if(strcmp($playlist,"null") != 0)
	{
		//if(strcmp($sql->query_data($mydb,$mytable,"cpu", $cpuid, "mac"),$mac) == 0)
		{
			$sql->update_data_3($mydb, $mytable, "cpu", $cpuid, "mac", $mac, "playlist", $playlist);
		}
		
		if(strcmp($playlist,"auto") == 0)
		{
			$content = $content . "List allocation:Automatic allocation" . "; ";
		}
		else if(strcmp($playlist,"all") == 0)
		{
			$content = $content . "List allocation:Assign all" . "; ";
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
			$content = $content . "List manual allocation:" . $type_array[$playlist] . "; ";
		}
	}
	
	$mytable = "custom_table";
	$sql->create_table($mydb, $mytable, 
		"mac text,cpu text,ip text,space text, date text,
		time text,allow text, playlist text, online text, allocation text,
		proxy text, balance float,showtime text,contact text,member text,
		panal text,number text,ips text, onescrolltext text, onescrolltexttimes int, 
		numberdate int, scrollcontent text, scrolldate text, scrolltimes text, controlurl text, 		
		controltime int, unbundling int, accessdate timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP, mv text, isupdate int,
		remarks text, startime date");
	//if($allocation != "null")
	if(strcmp($allocation,"null") != 0)
	{
		//if(strcmp($sql->query_data($mydb,$mytable,"cpu", $cpuid, "mac"),$mac) == 0)
		{
			//echo "allocation:" . $allocation;
			//echo "cpu:" . $cpuid;
			//echo "mac:" . $mac;
			$sql->update_data_3($mydb, $mytable, "cpu", $cpuid, "mac", $mac, "allocation", $allocation);
		}
	}
	
	//if($proxy != "null")
	if(strcmp($proxy,"null") != 0)
	{
		//if(strcmp($sql->query_data($mydb,$mytable,"cpu", $cpuid, "mac"),$mac) == 0)
		//echo $proxy;
		{
			$sql->update_data_3($mydb, $mytable, "cpu", $cpuid, "mac", $mac, "proxy", $proxy);
		}
		
		if(strcmp($proxy,"admin") == 0)
		{
			$content = $content . "Only allow super administrator operations" . "; ";
		}
		else
		{
			$content = $content . "Authorized agent:" . $proxy . "; ";
		}
	}
	
	
	$sql->update_data_3($mydb, $mytable, "cpu", $cpuid, "mac", $mac, "showtime", $show);
	$sql->update_data_3($mydb, $mytable, "cpu", $cpuid, "mac", $mac, "contact", $contact);
	$sql->update_data_3($mydb, $mytable, "cpu", $cpuid, "mac", $mac, "member", $member);
	$sql->update_data_3($mydb, $mytable, "cpu", $cpuid, "mac", $mac, "panal", $panel);
	$sql->update_data_3($mydb, $mytable, "cpu", $cpuid, "mac", $mac, "remarks", $remarks);
	$sql->update_data_3($mydb, $mytable, "cpu", $cpuid, "mac", $mac, "scrollcontent", $scrollcontent);
	
	if(true)
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
			modelerror int, limittimes int, limitarea text");
		
		$model = $sql->query_data_2($mydb, $mytable, "mac", $mac, "cpu", $cpuid, "model");
		echo "model:" . $model;
		$sql->update_data_3($mydb, $mytable, "cpu", $cpuid, "mac", $mac, "limitmodel", $model);
	}
	
	$date = date("Y-m-d H:i:s");
	$mytable = "log_record_table";
	$sql->create_table($mydb, $mytable, "date datetime, user text, mac text, cpuid text, content text, other text, ip text");
	if($sql->find_column($mydb, $mytable, "ip") == 0)
		$sql->add_column($mydb, $mytable,"ip", "text");	
		
	$sql->insert_data($mydb, $mytable, "date, user, mac, cpuid, content, other, ip", $date.", ".$_COOKIE["user"].", ".$mac.", ".$cpuid.", ".$content.", "."null".", ".getIP());

	$sql->disconnect_database();
	header("Location: custom_list.php");
?>