<?php
include "memcache.php";
	
	
class DbMemCache {

	private $mCon = null;
	public $cacheTime=180;
	
	
	private function make_symble($val) {
		$vals = explode(", ", $val);
		for($i = 0; $i < count($vals); $i++) {
			$vals[$i] = "'" . $vals[$i] . "'";
		}
		return implode(", ", $vals);
	}
	
	public function connect_database($addr, $user, $pwd) {
		$this->mCon = mysql_connect($addr, $user, $pwd);
		//mysql_query("set names utf8");
		if(!$this->mCon) {
			die("Error connect database: " . mysql_error());
		}
		
		$this->mem = new GMemCache();
		$ret = $this->mem->connect();
		if(!$ret) {
			die("Error connect memcache");
		}
	}
	
	public function disconnect_database() {
		mysql_close($this->mCon);
		$this->mCon = null;
		$this->mem->close();
	}
	
	
	
}

?>