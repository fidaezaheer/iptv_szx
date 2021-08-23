<?php
	$cnlang = 2;
	if(isset($_COOKIE["lang"]))
		$cnlang = intval($_COOKIE["lang"]);
		
	//echo "cnlang:" . $cnlang;	
	if($cnlang == 1)
		include_once "lang_english.php";
	else
		include_once "lang_chinese.php";
?>