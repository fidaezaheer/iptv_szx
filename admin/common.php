<?php
include_once 'connect.php';
include_once "number.php";
include_once "gemini.php";
include_once "memcache.php";

//require_once('safe.php');

class DbSql {
	private $mCon = null;
	public $cacheTime=180;
	//public $dbIp = "localhost";
	//public $dbUser = "root";
	//public $dbPassword = "root";
	//public $dbName = "gemini-iptv";
	private function make_symble($val) {
		$vals = explode(", ", $val);
		for($i = 0; $i < count($vals); $i++) {
			$vals[$i] = "'" . $vals[$i] . "'";
		}
		return implode(", ", $vals);
	}
	
	public function connect_database($addr, $user, $pwd) {
		$this->mCon = mysql_connect($addr, $user, $pwd);
		mysql_query("set names utf8");
		if(!$this->mCon) {
			die("Error connect database: " . mysql_error());
		}
	}
	
	public function disconnect_database() {
		mysql_close($this->mCon);
		$this->mCon = null;
	}
	
	public function create_database($db) {
		mysql_query("CREATE DATABASE IF NOT EXISTS " . $db, $this->mCon);
	}
	
	public function delete_database($db) {
		mysql_query("DROP DATABASE IF EXISTS " . $db, $this->mCon);
	}
	
	public function add_column($db, $table, $column, $type)
	{
		if(! mysql_select_db($db, $this->mCon)) return null;
		
		$result = mysql_query("ALTER TABLE " . $table . " ADD COLUMN " . $column . " "  . $type . " NULL");	
	}
	
	public function del_column($db, $table, $column, $type)
	{
		if(! mysql_select_db($db, $this->mCon)) return null;
		
		$result = mysql_query("ALTER TABLE " . $table . " DROP COLUMN " . $column);	
	}
	
	public function find_column($db, $table, $column)
	{
		if(! mysql_select_db($db, $this->mCon)) return null;
		$rescolumns = mysql_query("SHOW FULL COLUMNS FROM ".$table."") ;
		while($row = mysql_fetch_array($rescolumns))
		{
			if($row['Field'] == $column)
				return 1;
			//echo '字段名称：'.$row['Field'].'-数据类型：'.$row['Type'];
			//echo '<br/><br/>';
  			//print_r($row);
		}
		return 0;
	}
	
	public function list_table($db)
	{
		$rs = mysql_query("SHOW TABLES FROM " . $db);
		$tables = array();
		while ($row = mysql_fetch_row($rs)){
			$tables[] = $row[0];
		}
		mysql_free_result($rs);
		return $tables;
	}
	
	public function create_table($db, $table, $context) {
		if(!mysql_select_db($db, $this->mCon)) return;
		$sql = "CREATE TABLE IF NOT EXISTS " . $table . " (" . $context . ")";
		mysql_query($sql, $this->mCon);
	}
	
	public function delete_table($db, $table) {
		if(!mysql_select_db($db, $this->mCon)) return;
		$sql = "DROP TABLE IF EXISTS " . $table;
		mysql_query($sql, $this->mCon);
	}
	
	public function insert_data($db, $table, $keys, $values) {
		if(! mysql_select_db($db, $this->mCon)) return;
		$values = $this->make_symble($values);
		$cmd = "INSERT INTO " . $table . " (" . 
			$keys . ") VALUES (" . $this->str_safe($values) . ")";
		//echo $cmd;
		$ret = mysql_query($cmd);
		
		return $ret;
	}
	
	public function insert_data_top($db, $table, $keys, $values) {
		if(! mysql_select_db($db, $this->mCon)) return;
		$values = $this->make_symble($values);
		$ret = mysql_query("INSERT INTO " . $table . " (" . 
			$keys . ") VALUES (" . $this->str_safe($values) . ")");
			
		return $ret;
	}
	
	public function delete_data_all($db, $table) {
		if(! mysql_select_db($db, $this->mCon)) return;
		$value = $this->make_symble($value);
		mysql_query("DELETE FROM " . $table);
	}
		
	public function delete_data($db, $table, $key, $value) {
		if(! mysql_select_db($db, $this->mCon)) return;
		$value = $this->make_symble($value);
		mysql_query("DELETE FROM " . $table . " WHERE " . $key . " = " . $this->str_safe($value));
	}
	
	
	public function delete_data_greater($db, $table, $key, $value) {
		if(! mysql_select_db($db, $this->mCon)) return;
		//$value = $this->make_symble($value);
		mysql_query("DELETE FROM " . $table . " WHERE " . $key . " > " . $this->str_safe($value));
	}
	
	
	public function delete_data_less($db, $table, $key, $value) {
		if(! mysql_select_db($db, $this->mCon)) return;
		//$value = $this->make_symble($value);
		mysql_query("DELETE FROM " . $table . " WHERE " . $key . " < " . $this->str_safe($value));
	}
	
	public function delete_data_2_less($db, $table, $key0, $value0, $key1, $value1) {
		if(! mysql_select_db($db, $this->mCon)) return;
		//$value = $this->make_symble($value);
		mysql_query("DELETE FROM " . $table . " WHERE " . $key0 . " = " . $this->str_safe($value0) . " AND (" . $key1 . " <= " . intval($value1) . ")");
	}
	
	public function delete_data_2($db, $table, $key0, $value0, $key1, $value1) {
		if(! mysql_select_db($db, $this->mCon)) return;
		$value0 = $this->make_symble($value0);
		$value1 = $this->make_symble($value1);
		mysql_query("DELETE FROM " . $table . 
			" WHERE " . $key0 . " = " . $this->str_safe($value0) . " AND " . $key1 . " = " . $this->str_safe($value1) . "");
	}
	
	public function delete_data_3($db, $table, $key0, $value0, $key1, $value1, $key2, $value2) {
		if(! mysql_select_db($db, $this->mCon)) return;
		$value0 = $this->make_symble($value0);
		$value1 = $this->make_symble($value1);
		$value2 = $this->make_symble($value2);
		mysql_query("DELETE FROM " . $table . 
			" WHERE " . $key0 . " = " . $this->str_safe($value0) . " AND " . $key1 . " = " . $this->str_safe($value1) . " AND " . $key2 . " = " . $this->str_safe($value2) . "");
	}
	
	
	public function delete_data_2_like($db, $table, $key0, $value0, $key1, $value1) {
		if(! mysql_select_db($db, $this->mCon)) return;
		$value0 = $this->make_symble($value0);
		$value1 = $this->make_symble($value1);
		mysql_query("DELETE FROM " . $table . 
			" WHERE " . $key0 . " = " . $this->str_safe($value0) . " AND " . $key1 . " LIKE " . "'%". $this->str_safe($value1) . "%'");
	}
	
	public function delete_data_like($db, $table, $key0, $value0) {
		if(! mysql_select_db($db, $this->mCon)) return;
		$value0 = $this->make_symble($value0);
		$value1 = $this->make_symble($value1);
		mysql_query("DELETE FROM " . $table . $key0 . " LIKE " . "'%". $this->str_safe($value0) . "%'");
	}
	
	public function update_data($db, $table, $key, $oldval, $newval) {
		if(! mysql_select_db($db, $this->mCon)) return;
		$oldval = $this->make_symble($oldval);
		$newval = $this->make_symble($newval);
		$ret = mysql_query("UPDATE " . $table . " SET " . $key . 
			" = " . $this->str_safe($newval) . " WHERE " . $key . " = " . $this->str_safe($oldval));
			
		return $ret;
	}
	
	public function update_data_0($db, $table, $key, $newval) {
		if(! mysql_select_db($db, $this->mCon)) return;
		$newval = $this->make_symble($newval);
		$ret = mysql_query("UPDATE " . $table . " SET " . $key . " = " . $this->str_safe($newval));
		
		return $ret;
	}
	
	public function update_data_2($db, $table, $key, $oldval, $nkey, $newval) {
		//mysql_query("set names utf8");
		if(! mysql_select_db($db, $this->mCon)) return;
		$oldval = $this->make_symble($oldval);
		$newval = $this->make_symble($newval);
		$ret = mysql_query("UPDATE " . $table . " SET " . $nkey . 
			" = " . $this->str_safe($newval) . " WHERE " . $key . " = " . $this->str_safe($oldval));
			
		return $ret;
	}
	
	public function update_data_3($db, $table, $key0, $oldval0, $key1, $oldval1, $nkey, $newval) 
	{
		//mysql_query("set names utf8");
		if(! mysql_select_db($db, $this->mCon)) return;
		$oldval0 = $this->make_symble($oldval0);
		$newval = $this->make_symble($newval);
		$oldval1 = $this->make_symble($oldval1);
		$ret = mysql_query("UPDATE " . $table . " SET " . $nkey . 
			" = " . $this->str_safe($newval) . " WHERE " . $key0 . " = " . $this->str_safe($oldval0) . " AND " . $key1 . " = " . $this->str_safe($oldval1));
		
		return $ret;
	}
	
	public function update_data_3_asyn($mem, $db, $table, $key0, $oldval0, $key1, $oldval1, $nkey, $newval) 
	{
		$oldval0 = $this->make_symble($oldval0);
		$newval = $this->make_symble($newval);
		$oldval1 = $this->make_symble($oldval1);
		
		$cmd = "UPDATE " . $table . " SET " . $nkey . " = " . $this->str_safe($newval) . " WHERE " . $key0 . " = " . $this->str_safe($oldval0) . " AND " . $key1 . " = " . $this->str_safe($oldval1);
		
		$cmd = base64_encode($cmd);
		$cmd = $cmd . "**18006**gemini-iptv";

		//$mem = new GMemCache();
		//$mem->step_connect_update();
		$mem->step_in_update($cmd);
		//$mem->step_close_update();
		//$url = "http://127.0.0.1:10087/cmd=" . $cmd;
		//$ret = file_get_contents($url, false, stream_context_create($opts));
	}
	
	public function update_data_cmd($db,$cmd) 
	{
		if(! mysql_select_db($db, $this->mCon)) return;
		$ret = mysql_query($cmd);
		
		return $ret;
	}
	
	
	public function update_data_4($db, $table, $key0, $oldval0, $key1, $oldval1, $key2, $oldval2, $nkey, $newval) 
	{
		//mysql_query("set names utf8");
		if(! mysql_select_db($db, $this->mCon)) return;
		$oldval0 = $this->make_symble($oldval0);
		$newval = $this->make_symble($newval);
		$oldval1 = $this->make_symble($oldval1);
		$oldval2 = $this->make_symble($oldval2);
		$ret = mysql_query("UPDATE " . $table . " SET " . $nkey . 
			" = " . $this->str_safe($newval) . " WHERE " . $key0 . " = " . $this->str_safe($oldval0) . " AND " . $key1 . " = " . $this->str_safe($oldval1) . " AND " . $key2 . " = " . $this->str_safe($oldval2));
			
		return $ret;
	}
	
	public function query_data($db, $table, $srckey, $value, $dstkey) {
		$key=$db.$table.$srckey.$value.$dstkey;
		if(! mysql_select_db($db, $this->mCon)) return;
		$value = $this->make_symble($this->str_safe($value));
		$cmd = "SELECT * FROM " . $table . " WHERE " . $srckey . " = " . $this->str_safe($value);
		$result = mysql_query($cmd);	
		if($result == false)
			return null;
			
		$row = mysql_fetch_array($result);
		if(isset($row[$dstkey])){
			return $row[$dstkey];	
		}else{
			return null;
		}
			
			
		
			
		/*
		$result = mysql_query("SELECT * FROM " . $table);
		if($result == false)
			return null;
			
		while($row = mysql_fetch_array($result)) {
			if($row[$srckey] == $this->str_safe($value))
				return $row[$dstkey];
		}
		return null;
		*/
	}

	public function query_data_3($db, $table, $srckey0, $value0, $srckey1, $value1, $srckey2, $value2, $dstkey) {
		if(! mysql_select_db($db, $this->mCon)) return;
		$value0 = $this->make_symble($this->str_safe($value0));
		$value1 = $this->make_symble($this->str_safe($value1));
		$value2 = $this->make_symble($this->str_safe($value2));
		
		$result = mysql_query("SELECT * FROM " . $table . " WHERE " . $srckey0 . " = " . $this->str_safe($value0) . " AND " . $srckey1 . " = " . $this->str_safe($value1) .  " AND " . $srckey2 . " = " . $this->str_safe($value2));
		if($result == false)
			return null;
			
		$row = mysql_fetch_array($result);
		if(isset($row[$dstkey]))
			return $row[$dstkey];	
		else
			return null;
	}
	
	public function query_data_2($db, $table, $srckey0, $value0, $srckey1, $value1, $dstkey) {
		$key=$db.$table.$srckey0.$value0.$srckey1.$value1.$dstkey;
		if(! mysql_select_db($db, $this->mCon)) return;
		$value0 = $this->make_symble($this->str_safe($value0));
		$value1 = $this->make_symble($this->str_safe($value1));
		
		$cmd = "SELECT * FROM " . $table . " WHERE " . $srckey0 . " = " . $this->str_safe($value0) . " AND " . $srckey1 . " = " . $this->str_safe($value1);
		//echo $cmd;
		$result = mysql_query($cmd);
		if($result == false)
			return null;
			
		$row = mysql_fetch_array($result);
		//echo "row:";
		//print_r($row);
		if(isset($row[$dstkey])){
			return $row[$dstkey];	
		}
		else{
			return null;
		}
		/*
		$result = mysql_query("SELECT * FROM " . $table);
		if($result == false)
			return null;
			
		while($row = mysql_fetch_array($result)) {
			if($row[$srckey0] == $this->str_safe($value0) && $row[$srckey1] == $this->str_safe($value1))
				return $row[$dstkey];
		}
		return null;
		*/
	}
	
	public function fetch_datas_order($db, $table, $order) {
		if(! mysql_select_db($db, $this->mCon)) return null;
		$list = array();
		$result = mysql_query("SELECT * FROM " . $table . " ORDER BY " . $order . " DESC");
		while($row = mysql_fetch_row($result)) {
			$list[] = $row;
		}
		return $list;	
	}
	
	public function fetch_datas_order_where_islive($db, $table, $order, $key, $value){
		if(! mysql_select_db($db, $this->mCon)) return null;
		$list = array();
		$result = mysql_query("SELECT * FROM " . $table . " WHERE " . $key . "<" . $this->str_safe($value) . " ORDER BY " . $order . " DESC");
		while($row = mysql_fetch_row($result)) {
			$list[] = $row;
		}
		return $list;		
	}
	
	public function fetch_datas_order_where_isplayback($db, $table, $order, $key, $value){
		if(! mysql_select_db($db, $this->mCon)) return null;
		$list = array();
		$result = mysql_query("SELECT * FROM " . $table . " WHERE " . $key . ">=" . $this->str_safe($value) . " ORDER BY " . $order . " DESC");
		while($row = mysql_fetch_row($result)) {
			$list[] = $row;
		}
		return $list;		
	}
	
	public function fetch_datas_order_where_limit_isplayback($db, $table, $order, $key, $value, $offset, $size){
		if(! mysql_select_db($db, $this->mCon)) return null;
		$list = array();
		$result = mysql_query("SELECT * FROM " . $table . " WHERE " . $key . ">=" . $this->str_safe($value) . " ORDER BY " . $this->str_safe($order) . " ASC" . " LIMIT " . intval($offset) . "," . intval($size));
		while($row = mysql_fetch_row($result)) {
			$list[] = $row;
		}
		return $list;		
	}
	
	public function fetch_datas_order_asc($db, $table, $order) {
		$key=$db.$table.$order;
		if(! mysql_select_db($db, $this->mCon)) return null;
		$list = array();
		$result = mysql_query("SELECT * FROM " . $table . " ORDER BY " . $this->str_safe($order) . " ASC"); 
		while($row = mysql_fetch_row($result)) {
			$list[] = $row;
		}
		return $list;	
	}
	
	public function fetch_datas_asc_where($db, $table, $key, $value, $order) {
		if(! mysql_select_db($db, $this->mCon)) return null;
		$list = array();
		$result = mysql_query("SELECT * FROM " . $table .  " WHERE " . $key . ">=" . $this->str_safe($value) . " ORDER BY " . $this->str_safe($order) . " ASC"); 
		while($row = mysql_fetch_row($result)) {
			$list[] = $row;
		}
		return $list;	
	}
	
	public function fetch_datas($db, $table) {
		if(! mysql_select_db($db, $this->mCon)) return null;
		$list = array();
		$result = mysql_query("SELECT * FROM " . $table);
		while($row = mysql_fetch_row($result)) {
			$list[] = $row;
		}
		return $list;
	}
	
	public function clear_datas($db, $table) {
		if(! mysql_select_db($db, $this->mCon)) return;
		mysql_query("DELETE FROM " . $table);
	}
	
	public function get_row($db, $table, $column, $key) {
		if(! mysql_select_db($db, $this->mCon)) return;
		$result = mysql_query("SELECT * FROM " . $table);
		while($row = mysql_fetch_array($result)) {
			if($row[$column] == $this->str_safe($key)){
				return $row;
			}
		}
		return null;
	}
	
	public function get_row_data($db, $table, $column, $key) {
		if(! mysql_select_db($db, $this->mCon)) return;
		$result = mysql_query("SELECT * FROM " . $table);	
		while($row = mysql_fetch_array($result)) {
			if($row[$column] == $this->str_safe($key))
				return $row;
		}
		return null;
	}
	
	public function fetch_datas_limit($db, $table, $offset, $size)
	{
		if(! mysql_select_db($db, $this->mCon)) return null;
		$list = array();
		$result = mysql_query("SELECT * FROM " . $table . " LIMIT " . intval($offset) . "," . intval($size));
		while($row = mysql_fetch_row($result)) {
			$list[] = $row;
		}
		return $list;		
	}
	
	public function fetch_datas_limit_asc($db, $table, $offset, $size, $order)
	{
		if(! mysql_select_db($db, $this->mCon)) return null;
		$list = array();
		$result = mysql_query("SELECT * FROM " . $table . " ORDER BY " . $this->str_safe($order) . " ASC" . " LIMIT " . intval($offset) . "," . intval($size));
		while($row = mysql_fetch_row($result)) {
			$list[] = $row;
		}
		return $list;		
	}
	
	public function fetch_datas_limit_desc($db, $table, $offset, $size, $order)
	{
		if(! mysql_select_db($db, $this->mCon)) return null;
		$list = array();
		$result = mysql_query("SELECT * FROM " . $table . " ORDER BY " . $this->str_safe($order) . " DESC" . " LIMIT " . intval($offset) . "," . intval($size));
		if($result == null)
			return $list;
			
		while($row = mysql_fetch_row($result)) {
			$list[] = $row;
		}
		return $list;		
	}
	
	public function fetch_datas_limit_desc_2($db, $table, $offset, $size, $order, $order2)
	{
		if(! mysql_select_db($db, $this->mCon)) return null;
		$list = array();
		$result = mysql_query("SELECT * FROM " . $table . " ORDER BY " . $this->str_safe($order) . " DESC, " . $this->str_safe($order2) . " DESC" . " LIMIT " . intval($offset) . "," . intval($size));
		while($row = mysql_fetch_row($result)) {
			$list[] = $row;
		}
		return $list;		
	}
	
	public function fetch_datas_where_limit_desc_2($db, $table, $srckey, $value, $offset, $size, $order, $order2)
	{
		if(! mysql_select_db($db, $this->mCon)) return null;
		$value = $this->make_symble($this->str_safe($value));
		$list = array();
		$cmd = "SELECT * FROM " . $table . " WHERE " . $srckey . " = " . $this->str_safe($value) . " ORDER BY " . $this->str_safe($order) . " DESC, " . $this->str_safe($order2) . " DESC" . " LIMIT " . intval($offset) . "," . intval($size);
		//echo $cmd;
		$result = mysql_query($cmd);
		while($row = mysql_fetch_row($result)) {
			$list[] = $row;
		}
		return $list;		
	}
	
	public function fetch_datas_where_limit_desc($db, $table, $srckey, $value, $offset, $size, $order)
	{
		if(! mysql_select_db($db, $this->mCon)) return null;
		$value = $this->make_symble($this->str_safe($value));
		$list = array();
		$cmd = "SELECT * FROM " . $table . " WHERE " . $srckey . " = " . $this->str_safe($value) . " ORDER BY " . $this->str_safe($order) . " DESC" . " LIMIT " . intval($offset) . "," . intval($size);
		//echo $cmd;
		$result = mysql_query($cmd);
		while($row = mysql_fetch_row($result)) {
			$list[] = $row;
		}
		return $list;		
	}
	
	public function fetch_datas_where_no_limit_desc($db, $table, $srckey, $value, $offset, $size, $order)
	{
		if(! mysql_select_db($db, $this->mCon)) return null;
		$value = $this->make_symble($this->str_safe($value));
		$list = array();
		$cmd = "SELECT * FROM " . $table . " WHERE " . $srckey . " = " . intval($value) . " OR " . $srckey . " is NULL" . " ORDER BY " . $this->str_safe($order) . " DESC" . " LIMIT " . intval($offset) . "," . intval($size);
		//echo $cmd;
		$result = mysql_query($cmd);
		while($row = mysql_fetch_row($result)) {
			$list[] = $row;
		}
		return $list;		
	}
	
	public function fetch_datas_limit_desc_cmd($db, $table, $cmd)
	{
		if(! mysql_select_db($db, $this->mCon)) return null;
		$list = array();
		$result = mysql_query($cmd);
		while($row = mysql_fetch_row($result)) {
			$list[] = $row;
		}
		return $list;		
	}
	
	public function sql_run_cmd($db,$cmd)
	{
		if(! mysql_select_db($db, $this->mCon)) return null;
		$list = array();
		$result = mysql_query($cmd);
		return $result;	
	}
	
	public function count_fetch_datas_cmd($db, $table, $cmd)
	{
		if(! mysql_select_db($db, $this->mCon)) return null;
		$result = mysql_query($cmd);
		$results = mysql_fetch_array($result);
		$count = $results['count']; 
		return $count;
	}
	
	public function fetch_datas_where($db, $table, $srckey, $value)
	{
		if(! mysql_select_db($db, $this->mCon)) return null;
		$value = $this->make_symble($value);
		$list = array();
		$result = mysql_query("SELECT * FROM " . $table . " WHERE " . $srckey . " = " . $this->str_safe($value) . "");
		while($row = mysql_fetch_row($result)) {
			$list[] = $row;
		}
		return $list;		
	}
	
	public function fetch_datas_where_or_null($db, $table, $srckey, $value)
	{
		if(! mysql_select_db($db, $this->mCon)) return null;
		$value = $this->make_symble($value);
		$list = array();
		$result = mysql_query("SELECT * FROM " . $table . " WHERE " . $srckey . " = " . $this->str_safe($value) . " OR " . $srckey . " is NULL");
		while($row = mysql_fetch_row($result)) {
			$list[] = $row;
		}
		return $list;		
	}
	
	public function count_fetch_datas_where($db, $table, $srckey, $value)
	{
		if(! mysql_select_db($db, $this->mCon)) return null;
		$value = $this->make_symble($value);
		$result = mysql_query("SELECT COUNT(*) AS count FROM " . $table . " WHERE " . $srckey . " = " . $this->str_safe($value) . "");
		$results = mysql_fetch_array($result);
		$count = $results['count']; 
		return $count;
	}
	
	public function count_fetch_datas_where_2($db, $table, $srckey, $value, $srckey1, $value1)
	{
		if(! mysql_select_db($db, $this->mCon)) return null;
		$value = $this->make_symble($value);
		$value1 = $this->make_symble($value1);
		$result = mysql_query("SELECT COUNT(*) AS count FROM " . $table . " WHERE " . $srckey . " = " . $this->str_safe($value) . " AND " . $srckey1 . " = " . $this->str_safe($value1));
		$results = mysql_fetch_array($result);
		$count = $results['count']; 
		
		return $count;
	}
	
	public function count_fetch_datas_where_no_2($db, $table, $srckey, $value, $srckey1, $value1)
	{
		if(! mysql_select_db($db, $this->mCon)) return null;
		$value = $this->make_symble($value);
		$value1 = $this->make_symble($value1);
		$result = mysql_query("SELECT COUNT(*) AS count FROM " . $table . " WHERE " . "(" . $srckey . " = " . intval($value) . " OR " . $srckey . " is NULL" . ")" . " AND " . $srckey1 . " = " . $this->str_safe($value1));
		$results = mysql_fetch_array($result);
		$count = $results['count']; 
		
		return $count;
	}
	
	public function fetch_datas_where_4_and($db, $table, $srckey0, $value0, $srckey1, $value1, $srckey2, $value2, $srckey3, $value3)
	{
		if(! mysql_select_db($db, $this->mCon)) return null;
		//$value = $this->make_symble($value);
		$value0 = $this->make_symble($value0);	
		$value1 = $this->make_symble($value1);	
		$value2 = $this->make_symble($value2);	
		$value3 = $this->make_symble($value3);	
		
		$list = array(); 
		$cmd = "SELECT * FROM " . $table . " WHERE " . $srckey0 . " = " . $this->str_safe($value0) . " AND " . $srckey1 . " = " . $this->str_safe($value1) . " AND " . $srckey2 . " = " . $this->str_safe($value2) . " AND " . $srckey3 . " = " . $this->str_safe($value3);
		//echo $cmd;
		$result = mysql_query($cmd);
		while($row = mysql_fetch_row($result)) {
			$list[] = $row;
		}
		return $list;		
	}
	
	public function fetch_datas_where_4_and_or($db, $table, $srckey0, $value0, $srckey1, $value1, $srckey2, $value2, $srckey3, $value3)
	{
		if(! mysql_select_db($db, $this->mCon)) return null;
		//$value = $this->make_symble($value);
		$value0 = $this->make_symble($value0);	
		$value1 = $this->make_symble($value1);	
		$value2 = $this->make_symble($value2);	
		$value3 = $this->make_symble($value3);	;	
		
		$list = array(); 
		$cmd = "SELECT * FROM " . $table . " WHERE " . $srckey0 . " = " . $this->str_safe($value0) . " AND " . $srckey1 . " = " . $this->str_safe($value1) . " AND (" . $srckey2 . " = " . $this->str_safe($value2) . " OR " . $srckey3 . " = " . $this->str_safe($value3) . ")";
		//echo $cmd;
		$result = mysql_query($cmd);
		while($row = mysql_fetch_row($result)) {
			$list[] = $row;
		}
		return $list;		
	}
	
	public function fetch_datas_where_5_and_or($db, $table, $srckey0, $value0, $srckey1, $value1, $srckey2, $value2, $srckey3, $value3, $srckey4, $value4)
	{
		if(! mysql_select_db($db, $this->mCon)) return null;
		//$value = $this->make_symble($value);
		$value0 = $this->make_symble($value0);	
		$value1 = $this->make_symble($value1);	
		$value2 = $this->make_symble($value2);	
		$value3 = $this->make_symble($value3);	
		$value4 = $this->make_symble($value4);	
		
		$list = array(); 
		$cmd = "SELECT * FROM " . $table . " WHERE " . $srckey0 . " = " . $this->str_safe($value0) . " AND " . $srckey1 . " = " . $this->str_safe($value1) . " AND " . $srckey2 . " = " . $this->str_safe($value2) . " AND (" . $srckey3 . " = " . $this->str_safe($value3) . " OR " . $srckey4 . " = " . $this->str_safe($value4) . ")";
		//echo $cmd;
		$result = mysql_query($cmd);
		while($row = mysql_fetch_row($result)) {
			$list[] = $row;
		}
		return $list;		
	}
	
	public function count_fetch_datas_where_like($db, $table, $srckey, $value)
	{	
		if(! mysql_select_db($db, $this->mCon)) return null;
		//$value = $this->make_symble($value);
		$cmd = "SELECT COUNT(*) AS count FROM " . $table . " WHERE " . $srckey . " like " . "'%"  . $this->str_safe($value) . "%'";
		$result = mysql_query($cmd);
		$results = mysql_fetch_array($result);
		$count = $results['count']; 
		return $count;
	}
	
	public function count_fetch_datas_where_like_3_or($db, $table, $srckey, $value, $srckey1, $value1, $srckey2, $value2)
	{
		if(! mysql_select_db($db, $this->mCon)) return null;
		//$value = $this->make_symble($value);
		$value1 = $this->make_symble($value1);
		$value2 = $this->make_symble($value2);
		$cmd = "SELECT COUNT(*) AS count FROM " . $table . " WHERE " . $srckey . " like " . "'%"  . $this->str_safe($value) . "%' AND (" . $srckey1 . " = " . $value1 . " OR " . $srckey2 . " = " . $value2 . ")";
		$result = mysql_query($cmd);
		$results = mysql_fetch_array($result);
		$count = $results['count']; 
		
		return $count;
	}
	
	public function count_fetch_datas($db, $table)
	{
		if(! mysql_select_db($db, $this->mCon)) return null;
		//$value = $this->make_symble($value);		
		$result = mysql_query("SELECT COUNT(*) AS count FROM " . $table . "");
		$results = mysql_fetch_array($result);
		$count = $results['count']; 
		return $count;
	}
	
	public function count_fetch_datas_order($db, $table, $order)
	{
		if(! mysql_select_db($db, $this->mCon)) return null;
		//$value = $this->make_symble($value);		
		$result = mysql_query("SELECT COUNT(*) AS count FROM " . $table . " ORDER BY " . $order . " DESC");
		$results = mysql_fetch_array($result);
		$count = $results['count']; 
		
		return $count;
	}
	
	public function fetch_datas_big($db, $table, $srckey, $value)
	{
		if(! mysql_select_db($db, $this->mCon)) return null;
		//$value = $this->make_symble($value);
		$list = array(); 
		$result = mysql_query("SELECT * FROM " . $table . " WHERE " . $srckey . " > " . intval($value) . "");
		while($row = mysql_fetch_row($result)) {
			$list[] = $row;
		}
		return $list;		
	}
	
	public function fetch_datas_where_little($db, $table, $srckey, $value, $srckey0, $value0)
	{
		if(! mysql_select_db($db, $this->mCon)) return null;
		//$value = $this->make_symble($value);
		$list = array(); 
		$result = mysql_query("SELECT * FROM " . $table . " WHERE " . $srckey . " = " . $this->str_safe($value) . " AND (" . $srckey0 . " <= " . intval($value0) . ")");
		while($row = mysql_fetch_row($result)) {
			$list[] = $row;
		}
		return $list;		
	}
	
	public function fetch_datas_where_like($db, $table, $srckey, $value)
	{
		if(! mysql_select_db($db, $this->mCon)) return null;
		//$value = $this->make_symble($value);
		$list = array();
		$result = mysql_query("SELECT * FROM " . $table . " WHERE " . $srckey . " like " . "'%" . $this->str_safe($value) . "%'");
		while($row = mysql_fetch_row($result)) {
			$list[] = $row;
		}
		return $list;		
	}
	
	public function fetch_datas_where_like_asc($db, $table, $srckey, $value, $order)
	{
		if(! mysql_select_db($db, $this->mCon)) return null;
		//$value = $this->make_symble($value);
		$list = array();
		$result = mysql_query("SELECT * FROM " . $table . " WHERE " . $srckey . " like " . "'%" . $this->str_safe($value) . "%'" . " ORDER BY " . $order . " ASC");
		while($row = mysql_fetch_row($result)) {
			$list[] = $row;
		}
		return $list;		
	}
	
	public function fetch_datas_where_like_desc($db, $table, $srckey, $value, $order)
	{
		if(! mysql_select_db($db, $this->mCon)) return null;
		//$value = $this->make_symble($value);
		$list = array();
		$result = mysql_query("SELECT * FROM " . $table . " WHERE " . $srckey . " like " . "'%" . $this->str_safe($value) . "%'" . " ORDER BY " . $order . " DESC");
		while($row = mysql_fetch_row($result)) {
			$list[] = $row;
		}
		return $list;		
	}
	
	public function fetch_datas_where_like_like_2($db, $table, $srckey, $value, $srckey1, $value1)
	{
		if(! mysql_select_db($db, $this->mCon)) return null;
		//$value1 = $this->make_symble($this->str_safe($value1));
		$list = array();
		$cmd = "SELECT * FROM " . $table . " WHERE " . $srckey . " like " . "'%" . $this->str_safe($value) . "%' OR " . $srckey1 . " like " . "'%" . $this->str_safe($value1) . "%'";
		//echo $cmd;
		$result = mysql_query($cmd);
		while($row = mysql_fetch_row($result)) {
			$list[] = $row;
		}
		return $list;		
	}
	
	public function fetch_datas_where_like_like_and_2($db, $table, $srckey, $value, $srckey1, $value1, $srckey2, $value2)
	{
		if(! mysql_select_db($db, $this->mCon)) return null;
		$value2 = $this->make_symble($this->str_safe($value2));
		$list = array();
		$cmd = "SELECT * FROM " . $table . " WHERE (" . $srckey . " like " . "'%" . $this->str_safe($value) . "%' OR " . $srckey1 . " like " . "'%" . $this->str_safe($value1) . "%') AND " . $srckey2 . " = " . $value2;
		$result = mysql_query($cmd);
		//echo $cmd;
		while($row = mysql_fetch_row($result)) {
			$list[] = $row;
		}
		return $list;		
	}
	
	public function fetch_datas_where_like_like_and_no_2($db, $table, $srckey, $value, $srckey1, $value1, $srckey2, $value2)
	{
		if(! mysql_select_db($db, $this->mCon)) return null;
		$value2 = $this->make_symble($this->str_safe($value2));
		$list = array();
		$cmd = "SELECT * FROM " . $table . " WHERE (" . $srckey . " like " . "'%" . $this->str_safe($value) . "%' OR " . $srckey1 . " like " . "'%" . $this->str_safe($value1) . "%') AND " . "(" . $srckey2 . " = " . intval($value2) . " OR " . $srckey2 . " is NULL" . ")";
		$result = mysql_query($cmd);
		//echo $cmd;
		while($row = mysql_fetch_row($result)) {
			$list[] = $row;
		}
		return $list;		
	}
	
	public function fetch_datas_where_like_2($db, $table, $srckey, $value, $srckey1, $value1)
	{
		if(! mysql_select_db($db, $this->mCon)) return null;
		$value1 = $this->make_symble($this->str_safe($value1));
		$list = array();
		$result = mysql_query("SELECT * FROM " . $table . " WHERE " . $srckey . " like " . "'%" . $this->str_safe($value) . "%' AND (" . $srckey1 . " = " . $value1 . ")");
		while($row = mysql_fetch_row($result)) {
			$list[] = $row;
		}
		return $list;		
	}
	
	public function fetch_datas_where_like_2_or($db, $table, $srckey, $value, $srckey1, $value1, $srckey2, $value2)
	{
		if(! mysql_select_db($db, $this->mCon)) return null;
		$value1 = $this->make_symble($this->str_safe($value1));
		$value2 = $this->make_symble($this->str_safe($value2));
		$list = array();
		$result = mysql_query("SELECT * FROM " . $table . " WHERE " . $srckey . " like " . "'%" . $this->str_safe($value) . "%' AND (" . $srckey1 . " = " . $value1 . " OR " . $srckey2 . " = " . $value2 . ")");
		while($row = mysql_fetch_row($result)) {
			$list[] = $row;
		}
		return $list;		
	}
	
	public function fetch_datas_where_like_2_and($db, $table, $srckey, $value, $srckey1, $value1, $srckey2, $value2)
	{
		if(! mysql_select_db($db, $this->mCon)) return null;
		$value1 = $this->make_symble($this->str_safe($value1));
		$value2 = $this->make_symble($this->str_safe($value2));
		$list = array();
		$result = mysql_query("SELECT * FROM " . $table . " WHERE " . $srckey . " like " . "'%" . $this->str_safe($value) . "%' AND (" . $srckey1 . " = " . $value1 . " AND " . $srckey2 . " = " . $value2 . ")");
		while($row = mysql_fetch_row($result)) {
			$list[] = $row;
		}
		return $list;		
	}
	
	public function fetch_datas_where_like_3_or($db, $table, $srckey, $value, $srckey1, $value1, $srckey2, $value2, $srckey3, $value3)
	{
		if(! mysql_select_db($db, $this->mCon)) return null;
		$value1 = $this->make_symble($this->str_safe($value1));
		$value2 = $this->make_symble($this->str_safe($value2));
		$value3 = $this->make_symble($this->str_safe($value3));
		$list = array();
		$cmd = "SELECT * FROM " . $table . " WHERE " . $srckey . " like " . "'%" . $this->str_safe($value) . "%' AND (" . $srckey1 . " = " . $value1 . " OR " . $srckey2 . " = " . $value2 . " OR " . $srckey3 . " = " . $value3 . ")";
		//echo $cmd;
		$result = mysql_query($cmd);
		while($row = mysql_fetch_row($result)) {
			$list[] = $row;
		}
		return $list;		
	}
	
	public function fetch_datas_where_like_3_and($db, $table, $srckey, $value, $srckey1, $value1, $srckey2, $value2, $srckey3, $value3)
	{
		if(! mysql_select_db($db, $this->mCon)) return null;
		$value1 = $this->make_symble($this->str_safe($value1));
		$value2 = $this->make_symble($this->str_safe($value2));
		$value3 = $this->make_symble($this->str_safe($value3));
		$list = array();
		$cmd = "SELECT * FROM " . $table . " WHERE " . $srckey . " like " . "'%" . $this->str_safe($value) . "%' AND (" . $srckey1 . " = " . $value1 . " OR " . $srckey2 . " = " . $value2 . " AND " . $srckey3 . " = " . $value3 . ")";
		//echo $cmd;
		$result = mysql_query($cmd);
		while($row = mysql_fetch_row($result)) {
			$list[] = $row;
		}
		return $list;		
	}
	
	public function fetch_datas_where_like_4_or($db, $table, $srckey, $value, $srckey1, $value1, $srckey2, $value2, $srckey3, $value3)
	{
		if(! mysql_select_db($db, $this->mCon)) return null;
		$value1 = $this->make_symble($this->str_safe($value1));
		$value2 = $this->make_symble($this->str_safe($value2));
		$value3 = $this->make_symble($this->str_safe($value3));
		$list = array();
		$cmd = "SELECT * FROM " . $table . " WHERE " . $srckey . " like " . "'%" . $this->str_safe($value) . "%' OR (" . $srckey1 . " = " . $value1 . " OR " . $srckey2 . " = " . $value2 . " OR " . $srckey3 . " = " . $value3 . ")";
		//echo $cmd;
		$result = mysql_query($cmd);
		while($row = mysql_fetch_row($result)) {
			$list[] = $row;
		}
		return $list;		
	}
	
	public function fetch_datas_where_like_5_or($db, $table, $srckey, $value, $srckey1, $value1)
	{
		if(! mysql_select_db($db, $this->mCon)) return null;
		$list = array();
		$result = mysql_query("SELECT * FROM " . $table . " WHERE (" . $srckey . " like " . "'%" . $this->str_safe($value) . "%') OR (" . $srckey1 . " like '%" . $this->str_safe($value1) . "%')");
		while($row = mysql_fetch_row($result)) {
			$list[] = $row;
		}
		return $list;		
	}
	
	public function fetch_datas_where_desc($db, $table, $srckey, $value, $order)
	{
		if(! mysql_select_db($db, $this->mCon)) return null;
		$value = $this->make_symble($value);
		
		$list = array();
		$result = mysql_query("SELECT * FROM " . $table . " WHERE " . $srckey . " = " .$this->str_safe($value) . " ORDER BY " . $order . " DESC");
		while($row = mysql_fetch_row($result)) {
			$list[] = $row;
		}
		return $list;		
	}
	
	public function fetch_datas_where_2($db, $table, $srckey0, $value0, $srckey1, $value1)
	{
		$key=$db.$table.$srckey0.$value0.$srckey1.$value1;
		if(! mysql_select_db($db, $this->mCon)) return null;
		$value1 = $this->make_symble($value1);
		$value0 = $this->make_symble($value0);
		
		$list = array();
		$result = mysql_query("SELECT * FROM " . $table . " WHERE " . $srckey0 . " = " . $this->str_safe($value0) . "" . " AND " . $srckey1 . " = " . $this->str_safe($value1) . "");
		while($row = mysql_fetch_row($result)) {
			$list[] = $row;
		}
		return $list;		
	}
	
	public function fetch_datas_where_no_2($db, $table, $srckey0, $value0, $srckey1, $value1)
	{
		$key=$db.$table.$srckey0.$value0.$srckey1.$value1;
		if(! mysql_select_db($db, $this->mCon)) return null;
		$value1 = $this->make_symble($value1);
		$value0 = $this->make_symble($value0);
		
		$list = array();
		$cmd = "SELECT * FROM " . $table . " WHERE " . "(" . $srckey0 . " = " . intval($value0) . " OR " . $srckey0 . " is NULL" . ")"  . " AND " . $srckey1 . " = " . $this->str_safe($value1) . "";
		
		$result = mysql_query($cmd);
		while($row = mysql_fetch_row($result)) {
			$list[] = $row;
		}
		return $list;		
	}
	
	public function fetch_datas_where_2_or($db, $table, $srckey0, $value0, $srckey1, $value1, $srckey2, $value2)
	{
		if(! mysql_select_db($db, $this->mCon)) return null;
		$value1 = $this->make_symble($value1);
		$value0 = $this->make_symble($value0);
		
		$list = array();
		$result = mysql_query("SELECT * FROM " . $table . " WHERE " . $srckey0 . " = " . $this->str_safe($value0) . "" . " AND (" . $srckey1 . " = " . $this->str_safe($value1) . " OR " . $srckey2 . " = '" . $this->str_safe($value2) . "')");
		while($row = mysql_fetch_row($result)) {
			$list[] = $row;
		}
		return $list;		
	}
		
	public function fetch_datas_where_3($db, $table, $srckey0, $value0, $srckey1, $value1)
	{
		if(! mysql_select_db($db, $this->mCon)) return null;
		$value1 = $this->make_symble($value1);
		$value0 = $this->make_symble($value0);
		
		$list = array();
		$result = mysql_query("SELECT * FROM " . $table . " WHERE " . $srckey0 . " = " . $this->str_safe($value0) . "" . " OR " . $srckey1 . " = " . $this->str_safe($value1) . "");
		while($row = mysql_fetch_row($result)) {
			$list[] = $row;
		}
		return $list;		
	}
	
	public function fetch_datas_limit_where($db, $table, $offset, $size, $srckey, $value)
	{
		if(! mysql_select_db($db, $this->mCon)) return null;
		$value = $this->make_symble($value);
		$list = array();
		$result = mysql_query("SELECT * FROM " . $table . " WHERE " . $srckey . " = " . $this->str_safe($value) . " LIMIT " . intval($offset) . "," . intval($size));
		while($row = mysql_fetch_row($result)) {
			$list[] = $row;
		}
		return $list;		
	}
	
	public function fetch_datas_limit_where_like($db, $table, $offset, $size, $srckey, $value)
	{
		if(! mysql_select_db($db, $this->mCon)) return null;
		//$value = $this->make_symble($value);
		$list = array();
		$cmd = "SELECT * FROM " . $table . " WHERE " . $srckey . " LIKE " . "'%". $this->str_safe($value) . "%'" . " LIMIT " . intval($offset) . "," . intval($size);
		$result = mysql_query($cmd);
		while($row = mysql_fetch_row($result)) {
			$list[] = $row;
		}
		return $list;		
	}
	
	public function fetch_datas_limit_where_like_and($db, $table, $offset, $size, $srckey, $value, $srckey1, $value1)
	{
		if(! mysql_select_db($db, $this->mCon)) return null;
		//$value = $this->make_symble($value);
		$value1 = $this->make_symble($value1);
		
		$list = array();
		$cmd = "SELECT * FROM " . $table . " WHERE " . $srckey . " LIKE " . "'%". $this->str_safe($value) . "%'" . " AND " . $srckey1 . "=" . $this->str_safe($value1) . " LIMIT " . intval($offset) . "," . intval($size);
		//echo $cmd;
		$result = mysql_query($cmd);
		while($row = mysql_fetch_row($result)) {
			$list[] = $row;
		}
		return $list;		
	}
	
	public function fetch_datas_limit_where_2($db, $table, $offset, $size, $srckey, $value, $srckey1, $value1)
	{
		if(! mysql_select_db($db, $this->mCon)) return null;
		$value = $this->make_symble($value);
		$value1 = $this->make_symble($value1);
		$list = array();
		//echo "SELECT * FROM " . $table . " WHERE " . $srckey . " = " . $value . " OR " . $srckey1 . " = " . $value1 . " LIMIT " . $offset . "," . $size;
		$result = mysql_query("SELECT * FROM " . $table . " WHERE " . $srckey . " = " . $this->str_safe($value) . " AND " . $srckey1 . " = " . $this->str_safe($value1) . " LIMIT " . intval($offset) . "," . intval($size));
		while($row = mysql_fetch_row($result)) {
			$list[] = $row;
		}
		return $list;		
	}
	
	public function fetch_datas_limit_where_desc($db, $table, $offset, $size, $srckey, $value, $order)
	{
		if(! mysql_select_db($db, $this->mCon)) return null;
		$value = $this->make_symble($value);
		$list = array();
		$result = mysql_query("SELECT * FROM " . $table . " WHERE " . $srckey . " = " . $this->str_safe($value) . " ORDER BY " . $order . " DESC" . " LIMIT " . intval($offset) . "," . intval($size));
		while($row = mysql_fetch_row($result)) {
			$list[] = $row;
		}
		return $list;		
	}
	
	public function fetch_datas_limit_where_or($db, $table, $offset, $size, $srckey, $value, $srckey1, $value1)
	{
		if(! mysql_select_db($db, $this->mCon)) return null;
		$value = $this->make_symble($value);
		$value1 = $this->make_symble($value1);
		$list = array();
		//echo "SELECT * FROM " . $table . " WHERE " . $srckey . " = " . $value . " OR " . $srckey1 . " = " . $value1 . " LIMIT " . $offset . "," . $size;
		$result = mysql_query("SELECT * FROM " . $table . " WHERE " . $srckey . " = " . $this->str_safe($value) . " OR " . $srckey1 . " = " . $this->str_safe($value1) . " LIMIT " . intval($offset) . "," . intval($size));
		while($row = mysql_fetch_row($result)) {
			$list[] = $row;
		}
		return $list;		
	}
	
	public function fetch_datas_limit_where_like_2($db, $table, $offset, $size, $srckey)
	{
		if(! mysql_select_db($db, $this->mCon)) return null;
		$value = $this->make_symble($value);
		$list = array();
		$result = mysql_query("SELECT * FROM " . $table . " WHERE mac like '%".$this->str_safe($_GET["find"])."%' LIMIT " . intval($offset) . "," . intval($size));
		while($row = mysql_fetch_row($result)) {
			$list[] = $row;
		}
		return $list;		
	}
	
	public function fetch_datas_where_2_like($db, $table, $srckey0, $value0, $srckey1, $value1)
	{
		if(! mysql_select_db($db, $this->mCon)) return null;
		$value1 = $this->make_symble($value1);
		$value0 = $this->make_symble($value0);
		
		$list = array();
		//echo "SELECT * FROM " . $table . " WHERE mac like '%".$_GET["find"]."%'";
		$result = mysql_query("SELECT * FROM " . $table . " WHERE mac like '%".$this->str_safe($_GET["mac"])."%'");
		while($row = mysql_fetch_row($result)) {
			$list[] = $row;
		}
		return $list;		
	}
		
	public function fetch_datas_where_asc($db, $table, $srckey, $value, $order)
	{
		if(! mysql_select_db($db, $this->mCon)) return null;
		$value = $this->make_symble($value);
		
		$list = array();
		$result = mysql_query("SELECT * FROM " . $table . " WHERE " . $srckey . " = " .$this->str_safe($value) . " ORDER BY " . $order . " ASC");
		while($row = mysql_fetch_row($result)) {
			$list[] = $row;
		}
		return $list;		
	}
	
	public function delete_date_little($db, $table, $srckey, $time)
	{
		if(! mysql_select_db($db, $this->mCon)) 
			return;
		mysql_query("DELETE FROM " . $table . " WHERE TO_DAYS(NOW()) - TO_DAYS(" . $srckey . ") > 180");		
	}
	
	public function insert_where_big($db, $table, $srckey0, $value0)
	{
		if(! mysql_select_db($db, $this->mCon)) return null;
		//$srckey0 = $this->make_symble($srckey0);
		
		$cmd = "UPDATE " . $table . " SET " . $srckey0 . " = " . $srckey0 . "+1" . " WHERE " . $srckey0 . " >= " . $value0;
		//echo $cmd;
		$ret = mysql_query($cmd);
		
		return $ret;
		
	}
	
	
	public function insert_where_big_little($db, $table, $srckey0, $value0, $value1)
	{
		if(! mysql_select_db($db, $this->mCon)) return null;
		//$srckey0 = $this->make_symble($srckey0);
		
		$cmd = "UPDATE " . $table . " SET " . $srckey0 . " = " . $srckey0 . "+1" . " WHERE " . $srckey0 . " >= " . $value0 . " AND " . $srckey0 . " < " . $value1;
		//echo $cmd;
		$ret = mysql_query($cmd);
		
		return $ret;
	}
	
	public function fetch_datas_like_5_or($db, $table, $srckey0, $srckey1, $srckey2, $srckey3, $srckey4, $value)
	{
		if(! mysql_select_db($db, $this->mCon)) return null;
		$value = $this->str_safe($value);
		$list = array();
		$cmd = "SELECT * FROM " . $table . " WHERE " . $srckey0 . " like " . "'%" . $value . "%' OR " . $srckey1 . " like " . "'%" . $value. "%' OR " . $srckey2 . " like " . "'%" . $value. "%' OR " . $srckey3 . " like " . "'%" . $value. "%' OR " . $srckey4 . " like " . "'%" . $value. "%'";
		//echo $cmd;
		$result = mysql_query($cmd);
		while($row = mysql_fetch_row($result)) {
			$list[] = $row;
		}
		return $list;		
		
	}
	
	public function connect_database_default()
	{
		//$this->connect_database("208.81.166.182", "geminidb", "gemini");
		$connect = new DbConnect();
		$this->connect_database($connect->dbIp, $connect->dbUser, $connect->dbPassword);
	}
	
	public function get_database()
	{
		//return "geminidb";
		$connect = new DbConnect();
		return $connect->dbName;
	}
	
	public function login_type()
	{
		if(!isset($_COOKIE["user"]) || !isset($_COOKIE["password"]))
			return -1;
			
		$mydb = $this->get_database();
		$this->connect_database_default();
		$this->create_database($mydb);
		
		$mytable = "proxy_table";
		$this->create_table($mydb, $mytable, "name text, password text");
		if(count($this->fetch_datas_where($mydb, $mytable, "name", $this->str_safe($_COOKIE["user"]))) > 0)
		{
			$this->disconnect_database();
			return 2;
		}
		
		$mytable = "user_two_table";
		$this->create_table($mydb, $mytable, "name text, password text");
		if(count($this->fetch_datas_where($mydb, $mytable, "name", $this->str_safe($_COOKIE["user"]))) > 0)
		{
			$this->disconnect_database();
			return 1;
		}
		
		$mytable = "user_table";
		$this->create_table($mydb, $mytable, "name text, password text");
		if(count($this->fetch_datas_where($mydb, $mytable, "name", $this->str_safe($_COOKIE["user"]))) > 0)
		{
			$this->disconnect_database();
			return 0;			
		}
		
		$this->disconnect_database();
		return -1;
	}
	
	public function login2($user,$password)
	{
		echo $user;
		echo $password;
		
		/*
		if(!isset($_COOKIE["user"]) || !isset($_COOKIE["password"]))
		{
			//
			
			echo "Please login again";
			header("HTTP/1.1 404 Not Found"); 
			echo "<script language='javascript' type='text/javascript'>";
			echo "window.parent.location.href='../admin/index.php'";
			echo "</script>";
			header("Location: ../admin/relogo.php");
			exit;
		}
		
		//include 'common.php';
		//$sql = new DbSql();
		$mydb = $this->get_database();
		$mytable = "user_table";
		$this->connect_database_default();
		$this->create_database($mydb);
		$this->create_table($mydb, $mytable, "name longtext, password longtext");
		$admin_password = $this->query_data($mydb, $mytable, "name", $this->str_safe($_COOKIE["user"]), "password");
		
		$this->disconnect_database();
		
		if(strcmp($admin_password,$this->str_safe($_COOKIE["password"])) == 0)
		{
			
		}
		else
		{
			//header("Location: relogo.php");
			echo "Please login again";
			header("HTTP/1.1 404 Not Found"); 
			echo "<script language='javascript' type='text/javascript'>";
			echo "window.parent.location.href='../admin/index.php'";
			echo "</script>";
			header("Location: ../admin/relogo.php");
			exit;
		}
		*/
	}
	
	public function login()
	{
		if(check_ip() == false)
		{
			header('HTTP/1.1 403 Forbidden');  
        	echo "Access forbidden";  
        	die; 
			exit;
		}
		
		ini_set('display_errors', false);
		set_zone();
		if(!isset($_COOKIE["user"]) || !isset($_COOKIE["password"]) || !isset($_COOKIE["number"]))
		{
			//
			echo "Please login again";
			header("Location: index.php?error=1");
			//header("Location: relogo.php");
			exit;
		}

		if(!isset($_COOKIE["testid"]) || !isset($_COOKIE["vcode"]) || isset($_COOKIE["vcode"]) != isset($_COOKIE["testid"]))
		{
			echo "Please login again";
			header("Location: index.php");
			exit;
			//header("Location: relogo.php");
		}
		
		$num = new Number();
		if(strcmp($num->number,$_COOKIE["number"]) != 0)
		{
			header("Location: index.php?error=1");
			exit;		
		}
		
		if(false)
		{
			$mac = "#D0:50:99:82:DC:7A#";
			$name = "#24IQT39H3YYCMZE#";
			if(strlen($_COOKIE["curmac"]) < 17 || strlen($_COOKIE["curname"]) < 5)
			{
				echo "Please login again";
				header("Location: index.php");
				//header("Location: relogo.php");
				exit;
			}
		}
		//include 'common.php';
		//$sql = new DbSql();
		$mydb = $this->get_database();
		$mytable = "user_table";
		$this->connect_database_default();
		$this->create_database($mydb);
		//$this->create_table($mydb, $mytable, "name longtext, password longtext");
		$this->create_table($mydb, $mytable, "name longtext, password longtext, namemd5 text, passwordmd5 text, needmd5 int");
	
		$admin_password = trim($this->query_data($mydb, $mytable, "name", $this->str_safe($_COOKIE["user"]), "password"));
		$admin_passwordmd5 = trim($this->query_data($mydb, $mytable, "name", $this->str_safe($_COOKIE["user"]), "passwordmd5"));
		$admin_needmd5 = trim($this->query_data($mydb, $mytable, "name", $this->str_safe($_COOKIE["user"]), "needmd5"));
		
		$this->disconnect_database();
		
		
		if($admin_needmd5 == 0)
		{
			if(strcmp($admin_password,$this->str_safe($_COOKIE["password"])) == 0)
			{
				set_time_limit(1000);
			
				return;
			}
			else
			{
				//header("Location: relogo.php");
				echo "Please login again";
				header("Location: index.php");
				//header("Location: relogo.php");
				exit;
			}
		}
		else if($admin_needmd5 == 1)
		{
			//echo $admin_passwordmd5 . "#" . md5($this->str_safe($_COOKIE["password"]));
			if(strcmp($admin_passwordmd5,md5($this->str_safe($_COOKIE["password"]))) == 0)
			{
				set_time_limit(1000);
			
				return;
			}
			else
			{
				echo "Please login again";
				header("Location: index.php");
				exit;
			}			
		}
		else if($admin_needmd5 == 2)
		{
			//echo $admin_passwordmd5 . "#" . md5($this->str_safe($_COOKIE["password"]));
			if(strcmp($admin_passwordmd5,md5(md5($this->str_safe($_COOKIE["password"])))) == 0)
			{
				set_time_limit(1000);
				return;
			}
			else
			{
				echo "Please login again";
				header("Location: index.php");
				exit;
			}			
		}
	}
	
	
	public function login_proxy()
	{
		ini_set('display_errors', false);
		set_zone();
		if(!isset($_COOKIE["user"]) || !isset($_COOKIE["password"]))
		{
			echo "Please login again";
			header("Location: index.php");
			exit;
			//header("Location: relogo.php");
		}
		
		if(!isset($_COOKIE["testid"]) || !isset($_COOKIE["vcode"]) || isset($_COOKIE["vcode"]) != isset($_COOKIE["testid"]))
		{
			echo "Please login again";
			header("Location: index.php");
			exit;
			//header("Location: relogo.php");
		}
		//include 'common.php';
		//$sql = new DbSql();
		$mydb = $this->get_database();
		//$mytable = "user_table";
		$this->connect_database_default();
		$this->create_database($mydb);
		
		$mytable = "proxy_table";
		//$this->create_table($mydb, $mytable, "name text, password text");
		$this->create_table($mydb, $mytable, "name text, password text, ptip text, edit int, watermark text, validity date, allow int, remark text, ccount int, panal int, pwmd5 int");	
		$proxy_password = trim($this->query_data($mydb, $mytable, "name", $this->str_safe($_COOKIE["user"]), "password"));
		$proxy_pwmd5 = trim($this->query_data($mydb, $mytable, "name", $this->str_safe($_COOKIE["user"]), "pwmd5"));
		if($proxy_pwmd5 == null)
			$proxy_pwmd5 = 0;
			
		$this->disconnect_database();
		
		if($proxy_pwmd5 == 0 && (strcmp($proxy_password,$this->str_safe($_COOKIE["password"])) == 0))
		{
			set_time_limit(1000);
		}
		else if($proxy_pwmd5 == 1 && (strcmp($proxy_password,md5($_COOKIE["password"])) == 0))
		{
			set_time_limit(1000);
		}
		else if($proxy_pwmd5 == 2 && (strcmp($proxy_password,md5(md5($_COOKIE["password"]))) == 0))
		{
			set_time_limit(1000);
		}
		else
		{
			echo "Please login again";
			header("Location: index.php");
			exit;
			//header("Location: relogo.php");
		}
	}

	function str_safe1($string)
	{
		return $this->safe0($this->safe1($string));
	}
	
	function safe0($s){ //安全过滤函数
    	if(get_magic_quotes_gpc()){ $s=stripslashes($s); }
    	$s=mysql_real_escape_string($s);
    	return $s;
	}
	
	function str_safe($string)
	{
		$searcharr = array("/(javascript|jscript|js|vbscript|vbs|about):/i","/on(mouse|exit|error|click|dblclick|key|load|unload|change|move|submit|reset|cut|copy|select|start|stop)/i","/<script([^>]*)>/i","/<iframe([^>]*)>/i","/<frame([^>]*)>/i","/<link([^>]*)>/i","/@import/i");
		$replacearr = array("\\1\n:","on\n\\1","&lt;script\\1&gt;","&lt;iframe\\1&gt;","&lt;frame\\1&gt;","&lt;link\\1&gt;","@\nimport");
		$string = preg_replace($searcharr,$replacearr,$string);
		$string = str_replace("&#","&\n#",$string);
		return $string;
	}
}

function check_key_out($version, $key, $dir="")
{

	ini_set('display_errors', false);
	if(intval($version) >= 92 && get_set_xml_file($dir . "setting.xml") > 0)
	{
		set_zone();
	
		set_time_limit(3600);

		$g = new Gemini();
		
		$key2 = $g->j_key($key);
		//echo "key2:" . $key2;
		$key_s=strstr($key2,"gemini#");
		$key_e=strpos($key_s,"#gemini");
		$key3=substr($key_s,strlen("gemini#"),$key_e-strlen("gemini#"));
		$key4 = explode("&",$key3);
		if(count($key4) >= 2)
		{
			$key_time_s=strstr($key4[0],"time#");
			$key_time_e=strpos($key_time_s,"#time");
			//echo "key_time_s:" . $key_time_s . "<br/>";
			//echo "key_time_e:" . $key_time_e . "<br/>";
			$key_time_3=substr($key_time_s,strlen("time#"),$key_time_e-strlen("#time"));
			
			//echo "key_time_3:" . $key_time_3 . "#" . time() . "<br/>";
			if(strcmp($key_time_3,"8888") == 0 || abs(intval($key_time_3)-intval(time())) > 180)
			{
				//echo abs(intval($key_time_3)) . "out" . intval(time());
				echo "out 1";
				exit;			
			}
		}
		else
		{
			echo "out 2";
			exit;
		}
	}	
}

function check_key($version, $key, $dir="")
{
	ini_set('display_errors', false);
	if(intval($version) >= 92 && get_set_xml_file($dir. "setting.xml") > 0)
	{
		set_zone();
	
		set_time_limit(3600);

		$g = new Gemini();
		//echo "key:" . $key;
		$key2 = $g->j_key($key);
		//echo "key2:" . $key2;
		$key_s=strstr($key2,"gemini#");
		$key_e=strpos($key_s,"#gemini");
		$key3=substr($key_s,strlen("gemini#"),$key_e-strlen("gemini#"));
		$key4 = explode("&",$key3);
		if(count($key4) >= 2)
		{
			$key_time_s=strstr($key4[0],"time#");
			$key_time_e=strpos($key_time_s,"#time");
			$key_time_3=substr($key_time_s,strlen("time#"),$key_time_e-strlen("#time"));
			
			//echo "key_time_3:" . $key_time_3;
			if(strcmp($key_time_3,"8888") == 0 || abs(intval($key_time_3)-intval(time())) > 180)
			{
				echo "<script>";
				echo "if(window.Authentication.CTCIsExistsInterface('CTCLoadWebView') == true)";
				echo "window.Authentication.CTCLoadWebView();";
				echo "</script>";
				exit;			
			}

		}
		else
		{
			echo "<script>";
			echo "if(window.Authentication.CTCIsExistsInterface('CTCLoadWebView') == true)";
			echo "window.Authentication.CTCLoadWebView();";
			echo "</script>";
			exit;
		}
	}	
}


function check_key_go_index($version, $key, $mac , $cpuid, $dir)
{
	//ini_set('display_errors', false);
	//echo "check_key_go_index:" . get_set_xml_file($dir . "setting.xml");
	if(get_set_xml_file($dir . "setting.xml") > 0)
	{
		set_zone();
	
		set_time_limit(3600);

		$md5_randomcode = readXML("xmls/".md5($mac.$cpuid).".xml");
		
		if(strlen($md5_randomcode) > 0 && strcmp($md5_randomcode,md5(md5($key))) == 0)
		{
			echo "<script>";
			echo "if(window.Authentication.CTCIsExistsInterface('CTCLoadWebView') == true)";
			echo "window.Authentication.CTCLoadWebView();";
			echo "</script>";
			exit;
		}
		
		$g = new Gemini();
		//echo "key:" . $key;
		$key2 = $g->j_key($key);
		//echo "key2:" . $key2;
		$key_s=strstr($key2,"gemini#");
		$key_e=strpos($key_s,"#gemini");
		$key3=substr($key_s,strlen("gemini#"),$key_e-strlen("gemini#"));
		$key4 = explode("&",$key3);
		if(count($key4) >= 2)
		{
			$key_time_s=strstr($key4[0],"time#");
			$key_time_e=strpos($key_time_s,"#time");
			$key_time_3=substr($key_time_s,strlen("time#"),$key_time_e-strlen("#time"));
			
			if(strcmp($key_time_3,"8888") == 0 || abs(intval($key_time_3)-intval(time())) > 180)
			{
				echo "<script>";
				echo "if(window.Authentication.CTCIsExistsInterface('CTCLoadWebView') == true)";
				echo "window.Authentication.CTCLoadWebView();";
				echo "</script>";
				exit;			
			}
			else
			{
				saveXML("xmls/", md5($mac.$cpuid).".xml", md5(md5($key)));
			}
		}
		else
		{
			echo "<script>";
			echo "if(window.Authentication.CTCIsExistsInterface('CTCLoadWebView') == true)";
			echo "window.Authentication.CTCLoadWebView();";
			echo "</script>";
			exit;
		}
	}	
}

function check_key_init($version, $key, $mac, $cpuid, $dir)
{
	//ini_set('display_errors', false);
	if(get_set_xml_file($dir ."setting.xml") > 0)
	{
		set_zone();
		set_time_limit(3600);

		$g = new Gemini();
		//echo "key:" . $key;
		$key2 = $g->j_key($key);
		//echo "key2:" . $key2;
		$key_s=strstr($key2,"gemini#");
		$key_e=strpos($key_s,"#gemini");
		$key3=substr($key_s,strlen("gemini#"),$key_e-strlen("gemini#"));
		$key4 = explode("&",$key3);
		if(count($key4) >= 2)
		{
			$key_time_s=strstr($key4[0],"time#");
			$key_time_e=strpos($key_time_s,"#time");
			$key_time_3=substr($key_time_s,strlen("time#"),$key_time_e-strlen("#time"));
			
			//echo "key_time_3:" . $key_time_3;
			if(strcmp($key_time_3,"8888") == 0 || abs(intval($key_time_3)-intval(time())) > 180)
			{
				echo "<script>";
				echo "if(window.Authentication.CTCIsExistsInterface('CTCLoadWebView') == true)";
				echo "window.Authentication.CTCLoadWebView();";
				echo "</script>";
				exit;			
			}
		}
		else
		{
			echo "<script>";
			echo "if(window.Authentication.CTCIsExistsInterface('CTCLoadWebView') == true)";
			echo "window.Authentication.CTCLoadWebView();";
			echo "</script>";
			exit;
		}
		
		$md5_randomcode = readXML("xmls/".md5($mac.$cpuid).".xml");
		//echo "xmls/", md5($mac.$cpuid).".xml" . " # ";
		//if(strcmp(trim($md5_randomcode),md5(md5($key))) != 0)
		//echo $md5_randomcode . " # " . md5(md5($key));
		if(strlen($md5_randomcode) > 0 && strcmp(trim($md5_randomcode),md5(md5($key))) != 0)
		{
			echo "<script>";
			echo "if(window.Authentication.CTCIsExistsInterface('CTCLoadWebView') == true)";
			echo "window.Authentication.CTCLoadWebView();";
			echo "</script>";
			exit;
		}
	}
}

function get_set_xml()
{
	$filename = "setting.xml";
	$dom = new DOMDocument('1.0', 'UTF-8');  
	
	if(!file_exists($filename))
		return 0;
		
	$dom->load($filename);  
	$key = $dom->getElementsByTagName("key")->item(0)->nodeValue;  
	return $key;
}

function get_set_xml_file($file)
{
	$filename = $file;
	$dom = new DOMDocument('1.0', 'UTF-8');  
	
	if(!file_exists($filename))
		return 0;
		
	$dom->load($filename);  
	$key = $dom->getElementsByTagName("key")->item(0)->nodeValue;  
	return $key;
}

function get_extension($file)
{
	return substr($file, strrpos($file, '.')+1);
}

function randomkeys()
{
	$key="";
 	$pattern='1234567890abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLOMNOPQRSTUVWXYZ';
 	for($i=0;$i<32;$i++)
 	{
   		$key .= $pattern{mt_rand(0,35)};    //生成php随机数
 	}
 	return $key;
}
 
 
function set_zone()
{
	$zone = "PRC";
	
	if(file_exists("zone.dat"))
	{
		$handle = fopen("zone.dat", "r");
    	$contents = fread($handle, filesize("zone.dat"));
    	fclose($handle);
	
		if(strlen($contents) > 3)
			$zone = $contents;
	}
	/*
	$sql = new DbSql();
	$mydb = $sql->get_database();
	$sql->connect_database_default();
	$sql->create_database($mydb);
	
	$mytable = "system_table";
	$sql->create_table($mydb, $mytable, "name text, value text");
	$zone = $sql->query_data($mydb, $mytable, "name", "zone", "value");
	
	$sql->disconnect_database();
	*/
	
	date_default_timezone_set($zone);
	return $zone;
	//date_default_timezone_set('Etc/GMT-9');
}

function get_server_os()
{
	return PHP_OS;	
}

function get_os(){
	$agent = $_SERVER['HTTP_USER_AGENT'];
    $os = false;
 
    if (preg_match('/win/i', $agent) && strpos($agent, '95'))
    {
      $os = 'Windows 95';
    }
    else if (preg_match('/win 9x/i', $agent) && strpos($agent, '4.90'))
    {
      $os = 'Windows ME';
    }
    else if (preg_match('/win/i', $agent) && preg_match('/98/i', $agent))
    {
      $os = 'Windows 98';
    }
    else if (preg_match('/win/i', $agent) && preg_match('/nt 6.0/i', $agent))
    {
      $os = 'Windows Vista';
    }
    else if (preg_match('/win/i', $agent) && preg_match('/nt 6.1/i', $agent))
    {
      $os = 'Windows 7';
    }
	  else if (preg_match('/win/i', $agent) && preg_match('/nt 6.2/i', $agent))
    {
      $os = 'Windows 8';
    }else if(preg_match('/win/i', $agent) && preg_match('/nt 10.0/i', $agent))
    {
      $os = 'Windows 10';#添加win10判断
    }else if (preg_match('/win/i', $agent) && preg_match('/nt 5.1/i', $agent))
    {
      $os = 'Windows XP';
    }
    else if (preg_match('/win/i', $agent) && preg_match('/nt 5/i', $agent))
    {
      $os = 'Windows 2000';
    }
    else if (preg_match('/win/i', $agent) && preg_match('/nt/i', $agent))
    {
      $os = 'Windows NT';
    }
    else if (preg_match('/win/i', $agent) && preg_match('/32/i', $agent))
    {
      $os = 'Windows 32';
    }
    else if (preg_match('/linux/i', $agent))
    {
      $os = 'Linux';
    }
    else if (preg_match('/unix/i', $agent))
    {
      $os = 'Unix';
    }
    else if (preg_match('/sun/i', $agent) && preg_match('/os/i', $agent))
    {
      $os = 'SunOS';
    }
    else if (preg_match('/ibm/i', $agent) && preg_match('/os/i', $agent))
    {
      $os = 'IBM OS/2';
    }
    else if (preg_match('/Mac/i', $agent) && preg_match('/PC/i', $agent))
    {
      $os = 'Macintosh';
    }
    else if (preg_match('/PowerPC/i', $agent))
    {
      $os = 'PowerPC';
    }
    else if (preg_match('/AIX/i', $agent))
    {
      $os = 'AIX';
    }
    else if (preg_match('/HPUX/i', $agent))
    {
      $os = 'HPUX';
    }
    else if (preg_match('/NetBSD/i', $agent))
    {
      $os = 'NetBSD';
    }
    else if (preg_match('/BSD/i', $agent))
    {
      $os = 'BSD';
    }
    else if (preg_match('/OSF1/i', $agent))
    {
      $os = 'OSF1';
    }
    else if (preg_match('/IRIX/i', $agent))
    {
      $os = 'IRIX';
    }
    else if (preg_match('/FreeBSD/i', $agent))
    {
      $os = 'FreeBSD';
    }
    else if (preg_match('/teleport/i', $agent))
    {
      $os = 'teleport';
    }
    else if (preg_match('/flashget/i', $agent))
    {
      $os = 'flashget';
    }
    else if (preg_match('/webzip/i', $agent))
    {
      $os = 'webzip';
    }
    else if (preg_match('/offline/i', $agent))
    {
      $os = 'offline';
    }
    else
    {
      $os = 'other';
    }
    return $os;  
}

function get_visit_ip() {  
    return isset($_SERVER["HTTP_X_FORWARDED_FOR"])?$_SERVER["HTTP_X_FORWARDED_FOR"]  
    :(isset($_SERVER["HTTP_CLIENT_IP"])?$_SERVER["HTTP_CLIENT_IP"]  
    :$_SERVER["REMOTE_ADDR"]);  
}

function check_ip(){  
    //$ALLOWED_IP=array('192.168.2.*','127.0.0.1','');  
	$ALLOWED_IP=array();  
	if(count($ALLOWED_IP) <= 0)
		return true;
		
    $IP=get_visit_ip();
    $check_ip_arr= explode('.',$IP);//要检测的ip拆分成数组  
    #限制IP  
    if(!in_array($IP,$ALLOWED_IP)) {  
        foreach ($ALLOWED_IP as $val){  
            if(strpos($val,'*')!==false){//发现有*号替代符  
                 $arr=array();//  
                 $arr=explode('.', $val);  
                 $bl=true;//用于记录循环检测中是否有匹配成功的  
                 for($i=0;$i<4;$i++){  
                    if($arr[$i]!='*'){//不等于*  就要进来检测，如果为*符号替代符就不检查  
                        if($arr[$i]!=$check_ip_arr[$i]){  
                            $bl=false;  
                            break;//终止检查本个ip 继续检查下一个ip  
                        }  
                    }  
                 }//end for   
                 if($bl){//如果是true则找到有一个匹配成功的就返回  
                    return true;  
                   // die;  
                 }  
            }  
        }//end foreach  
        //header('HTTP/1.1 403 Forbidden');  
        //echo "Access forbidden";  
        //die;  
		return false;
    }  
	else
		return true; 
	
} 


function deldir($dir)
{
	//删除目录下的文件：
	$dh=opendir($dir);
	while ($file=readdir($dh)) 
	{
		if($file!="." && $file!="..") 
		{
			$fullpath=$dir."/".$file;	
			if(!is_dir($fullpath))
			{
				unlink($fullpath);
			} 
			else
			{
				deldir($fullpath);
			}
		}
	} 
	closedir($dh);
	file_put_contents($dir."/index.html","");
}

function time_tran($the_time) {
    $now_time = date("Y-m-d H:i:s", time());
    $now_time = strtotime($now_time);
    $show_time = strtotime($the_time);
    $dur = $now_time - $show_time;
    if ($dur < 0) {
        return $the_time;
    } else {
        if ($dur < 60) {
            return $dur . '초전';
        } else {
            if ($dur < 3600) {//1小时内
                return floor($dur / 60) . '분전';
            } else {
                if ($dur < 3600*24) {//1天内
                    return floor($dur / 3600) . '시간';
                } else {
                    if ($dur < 3600*24*30) {//1月内
                        return floor($dur / 86400) . '일전';
                    } else {
                         if ($dur < 3600*24*30*12) {//1年内
                              return floor($dur / 2592000) . '개월';
                          } else {
                              return floor($dur / 31104000) . '년전';
                          }
                    }
                }
            }
        }
    }
}

function send($serverip,$cmd)
{
	$g = new Gemini();
	$words = $g->j1(base64_encode($cmd));
	$vkey = $g->get_key();
	
	$opts = array(   
  		'http'=>array(   
    	'method'=>"GET",   
    	'timeout'=>30,//单位秒  
   		)   
	);   
	
	$url = "http://" . trim($serverip) . ":23456/key=" . $vkey . "cmd=" . $words;
	$ret = file_get_contents($url, false, stream_context_create($opts));
	
	return $ret;	

}

function get_distribute($serverip)
{
	$opts = array(   
  		'http'=>array(   
    	'method'=>"GET",   
    	'timeout'=>30,//单位秒  
   		)   
	);   
	
	$url = "http://" . $serverip . ":18006/gp2p-distribution/distribution.php";
	//$url = "http://" . $serverip . ":23456/key=" . $vkey . "cmd=" . $words;
	//echo $url;
	$ret = file_get_contents($url, false, stream_context_create($opts));
	
	return $ret;	

}

function post_distribute($url, $content)
{
	$data = array(
   	 	'name'=>'zhezhao',
    	'age'=>23
    ); 

	$query = http_build_query($content); 

	$options['http'] = array(
     'timeout'=>60,
     'method' => 'POST',
     'header' => 'Content-type:application/x-www-form-urlencoded',
     'content' => $content
    );

	$url = "http://localhost/post.php";
	$context = stream_context_create($options);
	$result = file_get_contents($url, false, $context);

	echo $result;	
}

function createRandomcodeXML($randomcodeXml)
{
    $dom = new DomDocument('1.0', 'UTF-8');
    //如果文件不存在就创建一个
    if(!file_exists($randomcodeXml)){
           header("Content-Type:text/plain");
           $root= $dom->createElement("randomcodes");
           $dom->appendChild($root);
           $dom->save($randomcodeXml);
    } 
    //$dom->saveXML();	
}

function addRandomcodeXML($randomcodeXml, $node, $value)
{
	
	createRandomcodeXML($randomcodeXml);
	$fp = fopen($randomcodeXml, "a+");  
    $dom = new DomDocument('1.0', 'UTF-8');
	$dom->load($randomcodeXml);
	
    $root_randomcodes = $dom->getElementsByTagName("randomcodes");

	$root_randomcode = $dom->getElementsByTagName("randomcode");
	
	for($ii=0; $ii<$root_randomcode->length; $ii++)
	{
		$id_value = $root_randomcode->item($ii)->getAttribute("id"); 
		if($id_value == $node)
		{
			
			$root_randomcode->item($ii)->childNodes->item(0)->nodeValue = $value;
			$dom->save($randomcodeXml); 
			return;	
		}
	}
	
	$root_randomcode =  $root_randomcodes->item(0)->appendChild($dom->createElement("randomcode"));
	
	$root_randomcode->setAttribute("id",$node);
	
	$root_value = $root_randomcode->appendChild($dom->createElement("value",$value));
	
	//$randomcode_node->appendChild($value_node);
	
	//$root_randomcodes->appendChild($randomcode_node);
	
	$dom->save($randomcodeXml); 
	
	$dom->saveXML();
	
	/*
	$value_node = $dom->getElementsByTagName($node);
	if($value_node == false)
	{
		$value_node=$dom->createElement($node,$value);
		$randomcode_node->appendChild($value_node);
		$root_randomcodes->appendChild($randomcode_node);
	}
	else
	{
		$dom->getElementsByTagName($node)->item(0)->nodeValue = $value;
	}
	$dom->save($randomcodeXml); 
    //$dom->saveXML();	
	*/
}


function delRandomcodeXML($randomcodeXml, $node)
{
   	$dom = new DOMDocument("1.0","UTF-8");

	$dom->load($randomcodeXml);

	$randomcode_node=$dom->getElementsByTagName("randomcode");
	for($ii=0; $ii<$randomcode_node->length; $ii++)
	{
		$id_value = $randomcode_node->item($ii)->getAttribute("id"); 
		if($id_value == $node)
		{
			$randomcode_node->item($ii)->parentNode->removeChild($randomcode_node->item($ii));
			$dom->save($randomcodeXml); 
			$dom->saveXML();
			return;	
		}
	}
}

function getRandomcodeXML($randomcodeXml, $node)
{
    $dom = new DomDocument('1.0', 'UTF-8');
	$dom->load($randomcodeXml);
	
    $root_randomcodes = $dom->getElementsByTagName("randomcodes");

	$root_randomcode = $dom->getElementsByTagName("randomcode");
	
	for($ii=0; $ii<$root_randomcode->length; $ii++)
	{
		$id_value = $root_randomcode->item($ii)->getAttribute("id"); 
		if($id_value == $node)
		{
			$dom->saveXML();
			return $root_randomcode->item($ii)->childNodes->item(0)->nodeValue;
		}
	}
	
	$dom->saveXML();
	
	return "error";
}

function saveXML($dir, $file, $context)
{
	if(!file_exists($dir))
		mkdir ($dir,0777,true);

	$myfile = fopen($dir.$file, "w");	
	fwrite($myfile, $context);
	fclose($myfile);
}

function readXML($file)
{
	if(!file_exists($file) && strstr($file,".xml"))
		return "";
		
	$myfile = fopen($file, "r");
	$ret = fread($myfile,filesize($file));
	fclose($myfile);
	
	return $ret;
}

function delXML($file)
{
	if(file_exists($file) && strstr($file,".xml") != false)
	{
		unlink($file);
	}
}
?>
