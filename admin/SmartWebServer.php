<?PHP
/**************************************
 * 
 * 类名: SmartWebServer
 * 
 * 说明: 此类是单件模式封装,保证程序运行中只实例化一个类减少系统开销.getInstance()函数为全局访问点,
 * 		 在你第一次调用这个方法的时候,它创建一个示例,把它存在一个私有的静态变量中,并且给你返回示例,
 *		 下一次,它将仅仅给你返回那个已经创建的实例的句柄
 * 
 **************************************/
class SmartWebServer {

	static private $_dog = null;

	private $AppID = "IPTV";
	private $password1 = 1575685721;
	private $password2 = 1166577932;
	private $password3 = 1259495479;
	private $password4 = -320880461;
	
	private function __construct()
	{
		if (extension_loaded('smartserver'))
		{
			$ret = smartserver_find($this->AppID);
			if(0!=$ret)
			{
				echo '查找加密锁失败! 错误码:'.smartserver_get_lasterror();
				return ;
			}
			$ret = smartserver_open($this->password1, $this->password2, $this->password3, $this->password4);
			if(0!=$ret)
			{
				echo '打开加密锁失败! 错误码:'.getlasterror();
				return ;
			}
		} else {
			echo '未开启smartserver扩展';
		}
	}

	//获取错误码
	public function getlasterror()
	{
		return smartserver_get_lasterror();
	}
	
	
	//查找并打开加密锁
	public function open()
	{
		if (extension_loaded('smartserver'))
		{
			$ret = smartserver_find($this->AppID);
			if(0!=$ret)
			{
				echo '查找加密锁失败! 错误码:'.smartserver_get_lasterror();
				return ;
			}
			
			$ret = smartserver_open($this->password1, $this->password2, $this->password3, $this->password4);
			if(0!=$ret)
			{
				echo '打开加密锁失败! 错误码:'.getlasterror();
				return ;
			}
			
			echo '打开加密锁成功';
		} else {
			echo '未开启smartserver扩展';
			return ;
		}
	}


	//获取硬件ID
	public function GetUid()
	{
		return smartserver_get_uid();
	}


	//获取加密锁所有模块状态
	public function GetAllModuleStates($allStates)
	{
		return smartserver_get_all_module_states($allStates);
	}


	//获取指定模块状态
	public function getModuleState($number)
	{
		return smartserver_get_module_state($number);
	}


	//计算SHA1值
	public function SHA1WithSeed($password)
	{
		return smartserver_sha1_with_seed($password);
	}


	//获取用户数
	public function getUserNumber()
	{
		return smartserver_get_user_number();
	}


	//生成远程修改请求
	public function ChangeRequest()
	{
		return smartservet_change_request();
	}


	//根据数据修改加密锁
	public function Change($response)
	{
		return smartserver_change($response);
	}


	//读存储区
	public function readStorage($start, $len, $data)
	{
		return smartserver_read_storage($start, $len, $data);
	}


	//写存储区
	public function WriteStorage($start, $data)
	{
		return smartserver_write_storage($start, $data);
	}


	//3DES加密
	public function TriDesEncryptBase64($encrypt)
	{
		return smartserver_3des_encrypt_base64($encrypt);
	}
	

	//3DES解密
	public function TriDesBase64Decrypt($encb64)
	{
		
		return smartserver_3des_base64_decrypt($encb64);
	}


	//关闭加密锁
	public function Close()
	{
		
	}


	//不带参实例化  ,  参数为默认
	public static function getInstance()
	{
		if(!self::$_dog instanceof self){
			self::$_dog = new self();
		}
		return self::$_dog;
	}


	private function __clone()
	{
		
	}
	
} 

?>
