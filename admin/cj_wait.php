<!DOCTYPE html>
<?php
    $dscaiji = $_POST['dscaiji'];
	if(isset($_POST['dsurl'])){
		$dsurl = "";
		foreach($_POST['dsurl'] as $k => $v){
		    $dsurl = $dsurl."array_url[".$k."]='".$v."';";
	    }
	}
?>

<html>
    <META http-equiv=Content-Type content="text/html; charset=utf-8">
    <head>
        <title>采集任务等待中</title>
    </head>
    </head>
    <body>
        <table border='0' cellpadding='0' cellspacing='0' width='800' height='100%' align='center' style="border:1px solid #CCCCCC; font-size:12px">
            <tr><td valign='top' style="height:40px;line-height:40px;background:#DEEDFE;color:#0000FF;text-align:center;font-size:16px">API视频资源库定时采集器任务执行中（请勿关闭该页面）</td></tr>
            <tr><td valign='top' height='1' style="background:#e8e8e8"></td></tr>
            <tr><td valign='top' id='caiji' style="height:90%;text-align:center; padding:20px; overflow:hidden"><h2>定时采集任务等侍中...</h2></td></tr>
        </table>
		<span id="session"></span>
        <script>
		    var $time = new Array();
			$time['caiji'] = <?=$dscaiji?>;
			if($time['caiji'] > 0){
	            setInterval('$caiji()',1000*60*<?=$dscaiji?>);
            }
			var urln = 0;
            var array_url = new Array();<?=$dsurl?>
            function $caiji(){
	            if(urln < array_url.length-1){
		            urln++;
	            }else{
		            urln = 0;
	            }
	            if(array_url[urln]){
		            document.getElementById("caiji").innerHTML = "<iframe width='95%' height='95%' src='"+array_url[urln]+"' ></iframe>";
	            }
            }
        </script>
    </body>
</html>