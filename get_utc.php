<?php
session_start();
$allowTime = 10;
$allowT = "geminiallowtime_getutc";
if(!isset($_SESSION[$allowT]))
{
	$_SESSION[$allowT] = time();
}
else if(time() - $_SESSION[$allowT]>$allowTime)
{
	$_SESSION[$allowT] = time();
}else{
	return;
}

	date_default_timezone_set('UTC');
	echo date("Y-m-d#h:i:s");
?>