
<?php
session_start();
$allowTime = 10;
$allowT = "geminiallowtime_getprc";
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

date_default_timezone_set("PRC");
echo "adfasdfwefsdfwefasadfefdtime#" . time() . "#time";
?>
