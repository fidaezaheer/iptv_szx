<?php
	set_time_limit(1800);
	
	$id = $_GET["urlid"];
	$previewid = $_GET["previewid"];
	$date = date('Y-m-d');
	if(isset($_GET["date"]))
		$date = $_GET["date"];
		
	include 'tvmao_class.php';
	$tvmaoer = new tvmaoclass();
	$tvmaoer->preview_form_server($id,$previewid,$date);
	
	if(isset($_GET["date"]))
	{
		echo "=2=";
		echo "<script language='javascript' type='text/javascript'>";
		echo "window.location.href='timer_preview_all.php?id=". $previewid . "&urlid=" . $id . "'";
		echo "</script>";
	}
	else
	{
		echo "=3=";
		echo "<script language='javascript' type='text/javascript'>";
		echo "window.location.href='timer_preview.php'";
		echo "</script>";
	}
?>