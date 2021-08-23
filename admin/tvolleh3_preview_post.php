<?php  
  
  	   $id = $_GET["olleh"];
	   $data = array();
	   if($_GET["date"])
	  	 	$data = array('ch_type'=>'1','service_ch_no'=>$id,"view_type"=>'1',"seldate"=>$_GET["date"]);
	   else
       		$data = array('ch_type'=>'1','service_ch_no'=>$id,"view_type"=>'1');  //定义参数  
  
       $data = @http_build_query($data);  //把参数转换成URL数据  
  
       $aContext = array('http' => array('method' => 'POST',  
       'header'  => 'Content-type:application/x-www-form-urlencoded',  
       'content' => $data ));  
  
       $cxContext  = stream_context_create($aContext);  
  
       $sUrl = 'http://tv.kt.com/tv/channel/pSchedule.asp'; //此处必须为完整路径  
  
       $d = @file_get_contents($sUrl,false,$cxContext);  
  
       echo $d;  
  
?>