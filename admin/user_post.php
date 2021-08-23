<?php
	include 'common.php';
	$sql = new DbSql();
	$sql->login();
	
	$name = $_POST["name"];
	$ps = $_POST["password"];
	$needmd5 = $_POST["rmd5"];
	
	$mytable = "user_table";
	$sql->connect_database_default();
	$mydb = $sql->get_database();
	$sql->create_database($mydb);
	$sql->create_table($mydb, $mytable, "name longtext, password longtext, namemd5 text, passwordmd5 text, needmd5 int");
	$names = $sql->fetch_datas_where($mydb, $mytable, "name", $name);
	if(count($names) > 0)
	{
		//$sql->query_data($mydb, $mytable,"name",$name,""
		if($needmd5 == 2)
		{
			$sql->update_data_2($mydb, $mytable,"name",$name,"password","");
			$sql->update_data_2($mydb, $mytable,"name",$name,"passwordmd5",md5(md5($ps)));
			$sql->update_data_2($mydb, $mytable,"name",$name,"needmd5",2);
		}
		else if($needmd5 == 1)
		{
			$sql->update_data_2($mydb, $mytable,"name",$name,"password","");
			$sql->update_data_2($mydb, $mytable,"name",$name,"passwordmd5",md5($ps));
			$sql->update_data_2($mydb, $mytable,"name",$name,"needmd5",2);
		}
		else
		{
			$sql->update_data_2($mydb, $mytable,"name",$name,"password",$ps);
			$sql->update_data_2($mydb, $mytable,"name",$name,"passwordmd5","");
			$sql->update_data_2($mydb, $mytable,"name",$name,"needmd5",0);
		}
	}
	else
	{
		if($needmd5 == 2)
		{
			if($name != "" && $ps != "")
				$sql->insert_data($mydb, $mytable, "name, password, namemd5, passwordmd5, needmd5", $name . ", " . "" . ", ". "" . ", " . md5(md5($ps)) . ", " . $needmd5);
		}
		else if($needmd5 == 1)
		{
			if($name != "" && $ps != "")
				$sql->insert_data($mydb, $mytable, "name, password, namemd5, passwordmd5, needmd5", $name . ", " . "" . ", ". "" . ", " . md5($ps) . ", " . $needmd5);
		}
		else
		{
			if($name != "" && $ps != "")
				$sql->insert_data($mydb, $mytable, "name, password, namemd5, passwordmd5, needmd5", $name . ", " . $ps . ", ". "" . ", " . "" . ", " . $needmd5);
		}
	}
	
	$sql->disconnect_database();
	
	header("Location: user_list.php");
?>