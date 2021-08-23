<?php
	function post($url, $serverip, $data)
	{
		//file_get_content  ; 
        $postdata = http_build_query(array("clientip"=>$data,"serverip"=>$serverip));  
        $opts = array('http' =>   
            array( 'method'  => 'POST','header'  => 'Content-type: application/x-www-form-urlencoded', 'content' => $postdata ) );  
        $context = stream_context_create($opts);  
        $result = file_get_contents($url, false, $context);  
        return $result;  
    } 

	$serverip = $_POST["serverip"];
	$clientip = $_POST["clientip"];
	$serverid = $_POST["serverid"];
	
	//echo "serverid:" . $serverid;
	
	$url = "http://" . $serverip . ":18006/gp2p-distribution/set_distribution.php";
	
	
	$ret = post($url,$serverip,$clientip);
	
	//echo "ret=" . $ret . " serverid=" . $serverid;
	
	header("Location: stream_distribute_server_edit.php?id=" . $serverid);
?>