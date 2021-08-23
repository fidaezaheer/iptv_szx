<?php


class GMemCache 
{
	public $memcache;
	public $use = 1;
	public $timeout = 180;
	public $usefile = 0;
	
	public function used()
	{
		return $this->use;	
	}
	
	public function connect()
	{
		
		if($this->use == 1)
		{
			if($this->usefile == 1)
			{
				return true;
			}
			else
			{
				ini_set('display_errors', false);
				$this->memcache = new Memcache();
				$ret = $this->memcache->connect('127.0.0.1', 11211);
				if($ret == false)
				{
					//echo "connect fail";
					$this->use = 0;
					return false;
				}
				$this->memcache_timeout = 180;
				return true;
			}
		}	
		else
			return false;
		//ob_end_clean();
	}
	
	public function get($key)
	{
		
		if($this->use == 0)
			return false;
		else
		{
			if($this->usefile == 1)
			{
				$file = fopen(md5($key), "r");
				$content = fread($file,filesize(md5($key)));
				fclose($file);
				return $content;
			}
			else
			{
				return $this->memcache->get($key);
			}
		}
	}
	
	public function set($key,$value)
	{
		if($this->use == 0)
			return;
		else if($this->use == 1)
		{
			if($this->usefile == 1)
			{
				$file = fopen(md5($key), "w");
				fwrite($file,$value);
				fclose($file);
			}
			else
			{
				if($this->memcache->get($key) == false)
					$this->memcache->set($key,$value,0);
				else
					$this->memcache->replace($key,$value,0);
			}
		}
	}
	
	public function set_timeout($key,$value,$timeout=60)
	{
		if($this->use == 0)
			return;
		else if($this->use == 1)
		{
			if($this->usefile == 1)
			{
				$file = fopen(md5($key), "w");
				fwrite($file,$value);
				fclose($file);
			}
			else
			{
				if($this->memcache->get($key) == false)
					$this->memcache->set($key,$value,0,$timeout);
				else
					$this->memcache->replace($key,$value,0,$timeout);
			}
		}
	}
		
	public function delete($key)
	{
		if($this->use == 1)
		{
			if($this->usefile == 1)
			{
				
			}
			else
			{
				$this->memcache->delete($key);
			}
		}
	}
		
	public function memcache_delete_all()
	{
		if($this->use == 1)
		{
			if($this->usefile == 1)
			{
				
			}
			else
			{
				$this->memcache->flush();
			}
		}
	}
	
	public function memcache_flush()
	{
		if($this->use == 1)
		{
			if($this->usefile == 1)
			{
				
			}
			else
			{
				$this->memcache->flush();
			}
		}
	}
	
	public function close()
	{
		if($this->use == 1)
		{
			if($this->usefile == 1)
			{
				
			}
			else
			{
				$this->memcache->close();
			}
		}
	}
	
	public function step1($val,$timeout=1)
	{
		if($this->use == 1)
		{
			$this->connect();
			$gtime = $this->get(md5($val));
			$ntime = time();
			$this->set(md5($val),time());
			$this->close();
			if($gtime != false && $this->use == 1)
			{
				if(abs(intval($ntime) - intval($gtime)) <= $timeout)
				{
					return false;
				}
			}
		}
        return true;		
	}
	
	public function step_connect_update()
	{
		if($this->use == 1)
		{
			$this->connect();
		}
	}
	
	public function step_in_update($cmd)
	{
		if($this->use == 1)
		{
			$in_index = $this->get("in_index");
			if($in_index == null)
				$in_index = 0;
			$this->set("update".$in_index,$cmd);
		
			$in_index = floatval($in_index)+1;
			$this->set("in_index",$in_index);
		}
		//echo "in_index:" . $in_index . "</br>";
		//echo "out_index:" . $out_index;
		 
		//$this->close();
	}
	
	public function step_out_update()
	{
		if($this->use == 1)
		{
			$in_index = $this->get("in_index");
			$out_index = $this->get("out_index");
		
			if($out_index == null)
				$out_index = 0;
			if($in_index == null)
				$in_index = 0;	
			
			//echo "in_index:" . $in_index;
			//echo "out_index:" . $out_index;
		
			if($in_index <= $out_index)
				return null;
			
			$cmd = $this->get("update".$out_index);
		
			$out_index = floatval($out_index)+1;
			$this->set("out_index",$out_index);
		
			//$this->close();
		
			return $cmd;
		}
	}
	
	public function step_count_update()
	{
		$count = 0;
		if($this->use == 1)
		{
			$in_index = $this->get("in_index");
			$out_index = $this->get("out_index");
			
			$count = floatval($in_index)-floatval($out_index);
		}
		
		return $count;
	}
	
	public function step_close_update()
	{
		if($this->use == 1)
		{
			$this->close();
		}
	}
}
?>