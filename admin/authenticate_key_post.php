<?php
function getKey()
{
	$cdkey = "";	
	for($kk=0; $kk<16; $kk++)
	{
		srand((float)microtime()*1000000); 
		$cdkey = $cdkey . rand(0,9);
		usleep(100);
	}
	return $cdkey;
}
	include 'common.php';
	$sql = new DbSql();
	$sql->login();
	$sql->connect_database_default();
	$mydb = $sql->get_database();
	$sql->create_database($mydb);
	
	$mac=$_GET["mac"];
	$cpuid = $_GET["cpuid"];
	
	$number = "";
	if(isset($_GET["number"]))
	{
		$number = $_GET["number"];
	}
	else
	{
		$number = getKey();
	}
	
	$mytable = "custom_table";
	$sql->create_table($mydb, $mytable, 
	"mac text,cpu text,ip text,space text, date text,
	time text,allow text, playlist text, online text, allocation text,
	proxy text, balance float,showtime text,contact text,member text,
	panal text,number text,ips text, onescrolltext text, onescrolltexttimes int, 
	numberdate int, scrollcontent text, scrolldate text, scrolltimes text, controlurl text, 		
	controltime int");
	$cdkey = $sql->update_data_3($mydb, $mytable,"mac",$mac,"cpu",$cpuid,"number",$number);

	$sql->disconnect_database();
	
	header("Location: authenticate_list.php?mac=" . $mac . "&cpuid=" . $cpuid);
?>