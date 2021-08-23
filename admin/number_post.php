<?php
	include 'common.php';
	include_once "number.php";
	$sql = new DbSql();
	$sql->login();

	$newps = $_POST["newps"];
	$oldps = $_POST["oldps"];
	
	$num = new Number();
	if(strcmp($num->number,md5(md5($oldps))) == 0)
	{ 
		$content = "<?php class Number{public \$number = '" . md5(md5($newps)) . "';} ?>";
		$fpp1 = fopen('number.php', 'w');
		fwrite($fpp1, $content) or die('error');
		fclose($fpp1);
		header("Location: number_edit.php");
	}
	else
	{
		header("Location: number_edit.php?error=1");
	}
	
?>