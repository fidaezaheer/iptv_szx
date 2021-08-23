<?php
    error_reporting(0);
	header("Content-Type: text/html; charset=utf-8");
	include_once 'common.php';
	include_once 'gemini.php';
	include_once "get_pingying.php";
	set_zone();
	
    class  FnCJ{
		// 采集内核
        public function fn_file_get_contents($url, $timeout=10, $referer='', $post_data=''){
	        if(function_exists('curl_init')){
		        $ch = curl_init();
		        curl_setopt ($ch, CURLOPT_URL, $url);
		        curl_setopt ($ch, CURLOPT_HEADER, 0);
		        curl_setopt ($ch, CURLOPT_TIMEOUT, $timeout);
		        curl_setopt ($ch, CURLOPT_RETURNTRANSFER, 1); 
		        curl_setopt ($ch, CURLOPT_CONNECTTIMEOUT, $timeout);
		        curl_setopt ($ch, CURLOPT_REFERER, $referer);
		        curl_setopt ($ch, CURLOPT_FOLLOWLOCATION, 1);
		        //post
		        if($post_data){
			        curl_setopt($ch, CURLOPT_POST, 1);
			        curl_setopt($ch, CURLOPT_POSTFIELDS, $post_data);
		        }
		        //https
		        $http = parse_url($url);
		        if($http['scheme'] == 'https'){
			        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
			        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);
		        }
		        $content = curl_exec($ch);
		        curl_close($ch);
		        if($content){
			        return $content;
		        }
	        }
	        $ctx = stream_context_create(array('http'=>array('timeout'=>$timeout)));
	        $content = @file_get_contents($url, 0, $ctx);
	        if($content){
		        return $content;
	        }
	        return false;
        }
		// 分页采集跳转
	    public function jump($page, $pagemax, $pagelink){
			$collect_time = 1;
		    if($page < $pagemax){
			    //缓存断点续采并跳转到下一页
			    $jumpurl = str_replace('FFLINK',($page+1), $pagelink);
			    echo '<meta http-equiv="refresh" content='.$collect_time.';url='.$jumpurl.'>';
			    echo '<h5>'.$collect_time.'秒后将自动采集下一页!</h5>';
		    }else{
			    //清除断点续采
			    echo '<h5>恭喜您，所有采集任务已经完成。</h5>';
		    }
	    }
	    //视频库采集
	    public function vod($admin, $params){
		    $params['g'] = 'plus';
		    $params['m'] = 'api';
		    $params['a'] = 'json';
		    $params['p'] = $admin['page'];
		    $params['key'] = $admin['apikey'];
		    ksort($params);
		    $url = base64_decode($admin['xmlurl']).'?'.http_build_query($params);
		    if($admin['xmltype'] == 'xml'){
			    return $this->vod_xml($admin, $params);
		    }elseif($admin['xmltype'] == 'json'){
			    return $this->vod_json($admin, $params);
		    }else{
			    $data = $this->vod_json($admin, $params);
			    if($data['status'] == 200){
				    return $data;
			    }else{
				    return $this->vod_xml($admin, $params);
			    }
		    }
	    }
		private function vod_json($admin, $params){
		    $url = base64_decode($admin['xmlurl']).'?'.http_build_query($params);
		    $html = $this->fn_file_get_contents($url);
		    //是否采集到数据
		    if(!$html){
			    return array('status'=>601, 'infos'=>'连接API资源库失败，通常为服务器网络不稳定或禁用了采集。');
		    }
		    //数据包验证
		    $json = json_decode($html, true);
		    if( is_null($json) ){
			    return array('status'=>602, 'type'=>'json', 'infos'=>'JSON格式不正确，不支持采集。');
		    }
		    //资源库返回的状态501 502 503 3.3版本前没有status字段
		    if($json['status'] && $json['status'] != 200){
			    return array('status'=>$json['status'], 'type'=>'json', 'infos'=>$json['data']);
		    }
		    //不是Gemini-iptv的格式
		    if(!$json['list']){
			    return array('status'=>602, 'type'=>'json', 'infos'=>'不是Gemini-iptv系统的接口，不支持采集。');
		    }
		    //返回正确的数据集合
		    return array('status'=>200, 'type'=>'json', 'infos'=>$json);
	    }
	    private function vod_xml($admin, $params){
		    return $this->vod_xml_caiji($admin, $params);
	    }
		public function vod_update($admin, $params, $json){
		    echo'<style type="text/css">
			    ul{margin:0 auto; width:60%;border:1px solid #666;}
			    h5{text-align:center;}
			    li{font-size:12px;color:#333;line-height:21px}
			    li.p{color:#666;list-style:none;}
			    span{font-weight:bold;color:#FF0000}
			    </style><ul>
			    <h5>API视频采集 共有<span>'.$json['page']['recordcount'].'</span>个数据，需要采集<span>'.$json['page']['pagecount'].'</span>次，正在执行第<span color=green>'.$admin['page'].'</span>次采集任务，每一次采集<span>'.$json['page']['pagesize'].'</span>个。</h5>';
			foreach($json['data'] as $key=>$vod){
				echo '<li>第<span>'.(($admin['page']-1)*$json['page']['pagesize']+$key+1).'</span>个影片  '.$vod['vod_name'].'</li>';
				$vod['vod_inputer'] = 'xml_'.$admin['cjid'];
				$this->vod_db($vod);
				ob_flush();flush();
			}
		    //是否分页采集
		    if( in_array($admin['action'], array('days','all','post')) ){
			    $admin['g'] = 'admin';
			    $admin['m'] = 'cj';
			    $admin['a'] = 'apis';
			    $admin['page'] = 'FFLINK';
			    $page_link = '?'.http_build_query(array_merge($admin, $params));
			    $this->jump($json['page']["pageindex"], $json['page']['pagecount'], $page_link);
		    }
		    echo'</ul>';
	    }
		//视频采集入库调用接口，必需要有vod_reurl字段
	    public function vod_db($vod){
		    //去除资源站视频ID与写入资源站编辑标识
		    unset($vod["vod_id"]);
		    //必填字段验证
		    if(empty($vod['vod_name']) || empty($vod['vod_url'])){
			    echo '<li class="p">影片名称或播放地址为空，不做处理!</li>';
			    return false;
		    }
			$vod_url = explode('$$$',$vod['vod_url']);
			$list = array();
            foreach($vod_url as $k =>$v){
	            if(strstr($v, 'm3u8') !== false){
		            array_push($list, $v);
	            }	
            }
			if(count($list)==0){
			    echo '<li class="p">播放地址不包含M3U8，不做处理!</li>';
			    return false;
		    }
		    //是否绑定分类验证
		    if(!$vod['vod_cid']){
			    echo '<li class="p">未匹配到对应栏目分类，不做处理，请先转换分类!</li>';
			    return false;
		    }
		    //来源标识验证
		    if(!$vod['vod_reurl']){
			    echo '<li class="p">来源标识为空，不做处理!</li>';
			    return false;
		    }
		    //3.5后改为一次性修改不再单独一个一个检查
		    $array_vod_old = $this->vod_db_find($vod);
		    if($array_vod_old['vod_id']){
			    $status = '<li class="p"><strong>编辑：</strong>'.$this->vod_db_update($vod, $array_vod_old).'</li>';
		    }else{
			    $status = '<li class="p"><strong>新增：</strong>'.$this->vod_db_insert($vod).'</li>';
		    }
		    echo $status;
	    }
		//检测是否已存在相同影片
	    private function vod_db_find($vod){
			$sql = new DbSql();
		    $sql->connect_database_default();
	        $mydb = $sql->get_database();
			$type = explode("_",$vod['vod_cid']);
	        $v_id = $type[0];
		    $mytable = "vod_table_".$v_id;
		    $sql->create_database($mydb);
		    $sql->create_table($mydb, $mytable, "name text, image text, url text, area text, year text, 
			    type text, intro1 longtext, intro2 longtext, intro3 longtext, intro4 longtext,
			    id int, clickrate int, recommend tinyint, chage float, updatetime int, 
			    firstletter text, status int");
			
			if(count($sql->fetch_datas_where($mydb,$mytable, "name", $vod['vod_name'])) > 0)
	        {
				$namess = $sql->fetch_datas_where($mydb,$mytable, "name", $vod['vod_name']);
		        $array = array("vod_id"=>intval($namess[0][10]),"vod_cid"=>$vod['vod_cid'],"vod_name"=>$vod['vod_name'],"vod_url"=>$vod['vod_url'],"vod_addtime"=>$vod['vod_addtime']);
				$sql->disconnect_database();
				return $array;
	        }
		    $sql->disconnect_database();
			return $vod;
	    }
		//新增影片
	    private function vod_db_insert($vod){
		    $sql = new DbSql();
		    $sql->connect_database_default();
	        $mydb = $sql->get_database();
			$type = explode("_",$vod['vod_cid']);
	        $v_id = $type[0];
			$livetype = sprintf("%03d",$type[1]);
			$mytable = "vod_type_table_".$v_id;
	        $sql->create_table($mydb, $mytable, "value longtext, id smallint"); 
	        $value1 = $sql->query_data($mydb, $mytable, "id", 1 , "value"); 
	        $value2 = $sql->query_data($mydb, $mytable, "id", 2 , "value"); 
			
			$type_value1s = explode("|",$value1);
	        $type_value2s = explode("|",$value2);
			
			if($vod['vod_year']=='0' || $vod['vod_year']==''){
				$vod['vod_year'] = '更早';
			}
	        foreach($type_value1s as $k =>$v){
				if(strstr($v, $vod['vod_year']) !== false){
		            $year = $vod['vod_year'];
		            break;
	            }
                $year = '更早';	
			}
			
			$areas = str_replace("中国大陆","大陆",$vod['vod_area']);
			$areas = str_replace("内地","大陆",$areas);
			foreach($type_value2s as $k =>$v){
				if(strstr($v, $areas) !== false){
		            $area = $k+1;
		            break;
	            }
                $area = '14';	
			}

		    $firstletter = pinyin1(trim($vod['vod_name']));
			$recommend = rand(1,5);
			$actor = str_replace('/',',',$vod['vod_actor']);
			$image = str_replace('https://','http://',$vod['vod_pic']);
			$url = $this->vod_url_m3u8($vod['vod_url']);
			$status = 0;
			$clickrate = mt_rand(0, 99);
			if( empty($vod['vod_addtime']) ){
			    $vod['vod_addtime'] = time();
		    }else{
			    $vod['vod_addtime'] = strtotime($vod['vod_addtime']);
		    }
	     	$mytable = "vod_table_".$v_id;
		    $sql->create_database($mydb);
		    $sql->create_table($mydb, $mytable, "name text, image text, url text, area text, year text, 
			    type text, intro1 longtext, intro2 longtext, intro3 longtext, intro4 longtext,
			    id int, clickrate int, recommend tinyint, chage float, updatetime int, 
			    firstletter text, status int");
			$namess = $sql->fetch_datas_order($mydb, $mytable, "id");
		    $count = count($namess);
		    $id = 1;
		    if($count >= 1)
			    $id = intval($namess[0][10]) + 1;
		    $sql->insert_data($mydb, $mytable, "name, image, url, area, year, type, intro1, intro2, intro3, intro4, id, clickrate, recommend, chage, updatetime, firstletter, status", 
		        $vod['vod_name'].", ". $image .", ".$url. ", ". $area . ", ". $year . ", " . $livetype . ", " . $this->char_to_char($vod['vod_director']).", ".$this->char_to_char($vod['vod_director']).", ". $this->char_to_char($actor).", ". $this->char_to_char($vod['vod_content']) .", " . $id . ", ". $clickrate . ", ". $recommend. ", ". 0 . ", ". $vod['vod_addtime']. ", ". strtolower($firstletter).", ".intval($status));	
			$sql->disconnect_database();
			
			return '视频添加成功('.$id.')';
		}
	    //根据影片ID更新数据
		private function vod_db_update($vod, $vod_old){
			$sql = new DbSql();
		    $sql->connect_database_default();
	        $mydb = $sql->get_database();
			$type = explode("_",$vod['vod_cid']);
	        $v_id = $type[0];
			$url = $this->vod_url_m3u8($vod['vod_url']);
			if( empty($vod['vod_addtime']) ){
			    $vod['vod_addtime'] = time();
		    }else{
			    $vod['vod_addtime'] = strtotime($vod['vod_addtime']);
		    }
			$firstletter = pinyin1(trim($vod['vod_name']));
		    $mytable = "vod_table_".$v_id;
		    $sql->create_database($mydb);
		    $sql->create_table($mydb, $mytable, "name text, image text, url text, area text, year text, 
			    type text, intro1 longtext, intro2 longtext, intro3 longtext, intro4 longtext,
			    id int, clickrate int, recommend tinyint, chage float, updatetime int, 
			    firstletter text, status int");
			$row = $sql->get_row($mydb, $mytable,"id",$vod_old['vod_id']);
			if($url == $row[2]){
			    return '<font color="blue">播放地址未变化，不需要更新</font>';
		    }
			$sql->update_data_2($mydb, $mytable, "id", $vod_old['vod_id'], "firstletter", $firstletter);
			$sql->update_data_2($mydb, $mytable, "id", $vod_old['vod_id'], "url", $url);
			$sql->update_data_2($mydb, $mytable, "id", $vod_old['vod_id'], "updatetime", $vod['vod_addtime']);
			$sql->disconnect_database();
		    return '<font color="red">旧播放地址已更新</font>';
	    }
		//xml资源库采集
	    private function vod_xml_caiji($admin, $params){
		    $url = array();
		    if($admin['action']=='show' && $params['wd']){ 
			    $url['ac'] = 'list'; 
		    }else{
			    $url['ac'] = 'videolist';
		    }
		    $url['wd'] = $params['wd'];
		    $url['t'] = $params['cid'];
		    $url['h'] = $params['h'];
		    $url['rid'] = $params['play'];
		    $url['ids'] = $params['vodids'];
		    $url['pg'] = $admin['page'];
		    $url_detail = base64_decode($admin['xmlurl']).'?'.http_build_query($url);
		    $url_list   = base64_decode($admin['xmlurl']).'?ac=list&t=9999';
		    $xml_detail = $this->fn_file_get_contents($url_detail);
		    if(!$xml_detail){
			    return array('status'=>601, 'infos'=>'连接API资源库失败，通常为服务器网络不稳定或禁用了采集。');
		    }
		    $xml = simplexml_load_string($xml_detail);
		    if( is_null($xml) ){
			    return array('status'=>602, 'type'=>'xml', 'infos'=>'XML格式不正确，不支持采集。');
		    }
		    $key = 0;
		    $array_vod = array();
		    foreach($xml->list->video as $video){
			    $array_vod[$key]['vod_id'] = (string)$video->id;
			    $array_vod[$key]['vod_cid'] = (string)$video->tid;
			    $array_vod[$key]['vod_name'] = (string)$video->name;
			    $array_vod[$key]['vod_title'] = (string)$video->note;
			    $array_vod[$key]['list_name'] = (string)$video->type;
			    $array_vod[$key]['vod_pic'] = (string)$video->pic;
			    $array_vod[$key]['vod_language'] = (string)$video->lang;
			    $array_vod[$key]['vod_area'] = (string)$video->area;
			    $array_vod[$key]['vod_year'] = (string)$video->year;
			    $array_vod[$key]['vod_continu'] = (string)$video->state;
			    $array_vod[$key]['vod_actor'] = (string)$video->actor;
			    $array_vod[$key]['vod_director'] = (string)$video->director;
			    $array_vod[$key]['vod_content'] = (string)$video->des;
			    $array_vod[$key]['vod_reurl'] = base64_decode($admin['xmlurl']).'?id='.(string)$video->id;
			    $array_vod[$key]['vod_status'] = 1;
			    $array_vod[$key]['vod_type'] = str_replace('片','',$array_vod[$key]['list_name']);
			    $array_vod[$key]['vod_addtime'] = (string)$video->last;
			    $array_vod[$key]['vod_total'] = 0;
			    $array_vod[$key]['vod_isend'] = 1;
			    if($array_vod[$key]['vod_continu']){
				    $array_vod[$key]['vod_isend'] = 0;
			    }
			    //格式化地址与播放器
			    $array_play = array();
			    $array_url = array();
			    //videolist|list播放列表不同
			    if($count=count($video->dl->dd)){
				    for($i=0; $i<$count; $i++){
					    $array_play[$i] = str_replace('qiyi','iqiyi',(string)$video->dl->dd[$i]['flag']);
					    $array_url[$i] = $this->vod_xml_replace((string)$video->dl->dd[$i]);
				    }
			    }else{
				    $array_play[]=(string)$video->dt;
			    }
			    $array_vod[$key]['vod_play'] = implode('$$$', $array_play);
			    $array_vod[$key]['vod_url'] = implode('$$$', $array_url);
			    $key++;
		    }
	        //分页信息
		    preg_match('<list page="([0-9]+)" pagecount="([0-9]+)" pagesize="([0-9]+)" recordcount="([0-9]+)">', $xml_detail, $page_array);
		    $array_page = array('pageindex'=>$page_array[1], 'pagecount'=>$page_array[2], 'pagesize'=>$page_array[3], 'recordcount'=>$page_array[4]);
		    //栏目分类
		    $array_list = array();
		    if($admin['action'] == 'show'){
			    $xml = simplexml_load_string($this->fn_file_get_contents($url_list));
			    $key = 0;
			    foreach($xml->class->ty as $list){
				    $array_list[$key]['list_id'] = (int)$xml->class->ty[$key]['id'];
				    $array_list[$key]['list_name'] = (string)$list;
				    $key++;
			    }
		    }
		    return array('status'=>200,'type'=>'xml', 'infos'=>array('page'=>$array_page,'list'=>$array_list,'data'=>$array_vod));
	    }
	    //xml资源库播放地址格式化
	    private function vod_xml_replace($playurl){
		    $array_url = array();
		    $arr_ji = explode('#',str_replace('||','//',$playurl));
		    foreach($arr_ji as $key=>$value){
			    $urlji = explode('$',$value);
			    if( count($urlji) > 1 ){
				    $array_url[$key] = $urlji[0].'$'.trim($urlji[1]);
			    }else{
				    $array_url[$key] = trim($urlji[0]);
			    }
		    }
		    return implode(chr(13),$array_url);	
	    }
		private function vod_url_m3u8($vod_url){
			$vod_url = explode('$$$',$vod_url);
			$list = array();
            foreach($vod_url as $k =>$v){
	            if(strstr($v, 'm3u8') !== false){
		            array_push($list, $v);
	            }	
            }
			$g = new Gemini();
			$url = '';		
		    $n = explode("\r\n",$list[0]);
		    for($c=0;$c<count($n);$c++){
			    $p = explode("$",$n[$c]);
			    $url = $url.($c+1).'#'.$p[1];
			    if($c<count($n)-1){
				    $url = $url . "|";
			    }
		    }
		    $url = str_replace(" ","%20",$url); 
		    $url = str_replace("&amp;","&",$url); 
		    $url = trim($url);
			return $g->j3($url);
		}
		private function char_to_char($v1){
	        $v2 = $v1;
	
	        $v2 = str_replace('\\',"/",$v2);
	        $v2 = str_replace('“',"",$v2);
	        $v2 = str_replace('”',"",$v2);
	        $v2 = str_replace(' ',"\ ",$v2);
	        $v2 = str_replace('"',"\"",$v2);
	        $v2 = str_replace('\'',"",$v2);
	        $v2 = str_replace('nk','n\k',$v2);
          	$v2 = str_replace("&nbsp;","",$v2);
          	$v2 = strip_tags($v2);
	
	        return $v2;
        }
	}
	
	//获取绑定分类对应ID值
    function fn_bind_id($key){
	    $bindcache = F('cj/bind');
	    return $bindcache[$key];
    }
	//采集入口
	function apis(){
		$admin = array();
		$admin['cjid']   = $_REQUEST['cjid'];//采集项目ID
		$admin['action'] = $_REQUEST['action'];//all/ids/days/post/show
		$admin['xmlurl'] = $_REQUEST['xmlurl'];//采集网址
		$admin['xmltype'] = $_REQUEST['xmltype'];//资源站类型 josn|xml
		$admin['apikey'] = $_REQUEST['apikey'];//APIKEY参数
		$admin['page']   = !empty($_REQUEST['page'])?intval($_GET['page']):1;
		$params = array();
		//采集模块分支
		$params['h'] = $_REQUEST['h'];//指定时间
		$params['cid'] = $_REQUEST['cid'];//指定视频分类
		$params['play'] = $_REQUEST['play']; //指定播放器组
		$params['wd'] = $_REQUEST['wd'];//指定关键字
		$params['limit'] = $_REQUEST['limit'];//每页采集的数量
		if($admin['action'] == 'ids'){
			$params['vodids'] = implode(',',$_REQUEST['ids']);//采集选中视频ID
		}
		vod($admin, $params);
	}
	//视频采集接口
	function vod($admin, $params){
		$Cj = new FnCJ;
		$vod = $Cj->vod($admin, $params);
		if($vod['status'] != 200){
			
		}
		//格式化部份数据字段
		$admin['xmltype'] = $vod['type'];
		$json = $vod['infos'];
		unset($vod);
		// 获取到的远程栏目数据增加对应的绑定ID
		foreach($json['list'] as $key=>$value){
			$json['list'][$key]['bind_key'] = $admin['cjid'].'_'.$value['list_id'];
		}
		// 获取到的远程视频列表数据格式化处理
		foreach($json['data'] as $key=>$value){
			$json['data'][$key]['vod_cid'] = fn_bind_id($admin['cjid'].'_'.$value['vod_cid']);
			if(!$json['data'][$key]['vod_reurl']){
				$json['data'][$key]['vod_reurl'] = base64_decode($admin['xmlurl']).$json['data'][$key]['vod_id'];
			}
		}
		// 是否采集入库
		if( in_array($admin['action'], array('ids','days','all','post')) ){
			$Cj->vod_update($admin, $params, $json);
		}else{
			$admin['g'] = 'admin';
			$admin['m'] = 'cj';
			$admin['a'] = 'apis';
			$admin['page'] = 'FFLINK';
			$page_link = '?'.http_build_query(array_merge($admin, $params));
			$page_list = '共'.$json['page']['recordcount'].'条数据&nbsp;页次:'.$json['page']['pageindex'].'/'.$json['page']['pagecount'].'页&nbsp;'.getpage($json['page']['pageindex'],$json['page']['pagecount'], 5, $page_link, 'pagego(\''.$page_link.'\','.$json['page']['pagecount'].')');
			show($admin,$params,$json['list'],$json['data'],$page_list);
		}
	}
	
	function F($name, $value='', $path='') {
        static $_cache = array();
        $filename = $path . $name . '.php';
        if ('' !== $value) {
            if (is_null($value)) {
                // 删除缓存
                return unlink($filename);
            } else {
                // 缓存数据
                $dir = dirname($filename);
                // 目录不存在则创建
                if (!is_dir($dir))
                    mkdir($dir);
                    return file_put_contents($filename, "<?php\nreturn " . var_export($value, true) . ";\n?>");
            }
        }
        if (isset($_cache[$name]))
            return $_cache[$name];
            // 获取缓存数据
        if (is_file($filename)) {
            $value = include $filename;
            $_cache[$name] = $value;
        } else {
            $value = false;
        }
        return $value;
    }

	
	function getpage($currentPage,$totalPages,$halfPer=5,$url,$pagego){
        $linkPage .= ( $currentPage > 1 )
        ? '<a href="'.str_replace('FFLINK',1,$url).'" class="btn btn-primary">首页</a>&nbsp;<a href="'.str_replace('FFLINK',($currentPage-1),$url).'" class="btn btn-primary">上一页</a>&nbsp;' 
        : '<em class="btn btn-success">首页</em>&nbsp;<em class="btn btn-success">上一页</em>&nbsp;';
        for($i=$currentPage-$halfPer,$i>1||$i=1,$j=$currentPage+$halfPer,$j<$totalPages||$j=$totalPages;$i<$j+1;$i++){
            $linkPage .= ($i==$currentPage)?'<span class="btn btn-success">'.$i.'</span>&nbsp;':'<a href="'.str_replace('FFLINK',$i,$url).'" class="btn btn-primary">'.$i.'</a>&nbsp;'; 
        }
        $linkPage .= ( $currentPage < $totalPages )
        ? '<a href="'.str_replace('FFLINK',($currentPage+1),$url).'" class="btn btn-primary">下一页</a>&nbsp;<a href="'.str_replace('FFLINK',$totalPages,$url).'" class="btn btn-primary">尾页</a>'
        : '<em class="btn btn-success">下一页</em>&nbsp;<em class="btn btn-success">尾页</em>';
	    if(!empty($pagego)){
		    $linkPage .='&nbsp;<input type="input" name="page" id="page" size=4 class="input-mini focused"/>&nbsp;<input type="button" value="跳 转" onclick="'.$pagego.'" class="btn btn-primary" />';
	    }
		return $linkPage;
    }
	
	function setbind(){
		$sql = new DbSql();
		$sql->connect_database_default();
	    $mydb = $sql->get_database();
		$setbind = "<select name=\"cid\" id=\"cid\">\n\r<option value=\"\">请选择分类</option>\n\r";
		for($i=0;$i<4;$i++){
			$mytable = "vod_type_table_".$i;
			$sql->create_table($mydb, $mytable, "value longtext, id smallint");
			$type = $sql->query_data($mydb, $mytable, "id", 0 ,"value");
            $types = explode("|", $type);
			foreach($types as $k => $v){
				if(fn_bind_id($_GET['key']) == ($i."_".($k+1)))
					$setbind = $setbind."<option value='" . $i."_".($k+1) . "' selected>". $v ."</option>";
				else
					$setbind = $setbind."<option value='" . $i."_".($k+1) . "'>". $v ."</option>";
			}
		}
        $setbind = $setbind."<option value=\"\">取消绑定</option>\n\r</select>\n\r";
		$setbind = $setbind."<input class=\"btn btn-primary\" type=\"button\" value=\"提 交\" onClick=\"submitbind('{$_GET['key']}', $('#cid').val());\">\n\r<input class=\"btn btn-primary\" type=\"button\" value=\"取 消\" onClick=\"hidebind();\">";
		echo $setbind;
	}
	
	function insertbind(){
		$bindcache = F('cj/bind');
		if (!is_array($bindcache)) {
			$bindcache = array();
			$bindcache['1_1'] = '';
		}
		$bindinsert[$_GET['key']] = $_GET['val'];
		//合并
		$bindarray = array_merge($bindcache, $bindinsert);
		//保存
		F('cj/bind',$bindarray);
		if(extension_loaded("Zend OPcache")){  //判断是否开启
        	opcache_reset();  //清除PHP的脚本缓存
        }  
		exit('ok');
	}
	
	$sql = new DbSql();
	$sql->login();
	if($_GET['s']=='setbind'){
		return setbind();
	}if($_GET['s']=='Insertbind'){
		return insertbind();
	}else{
	    echo apis();
	}
	
	function show($admin,$params,$cjlist,$cjdata,$cjpage){
		include_once "cn_lang.php";
		$sql = new DbSql();
		$sql->connect_database_default();
		$mydb = $sql->get_database();
		$sql->create_database($mydb);
		print_r($v4);
?>
<!DOCTYPE html>
<html>
    <META http-equiv=Content-Type content="text/html; charset=utf-8">
    <head>
        <title><?php echo $lang_array["left_title"] ?></title>
        <!-- Bootstrap -->
        <link href="bootstrap/css/bootstrap.min.css" rel="stylesheet" media="screen">
        <link href="bootstrap/css/bootstrap-responsive.min.css" rel="stylesheet" media="screen">
        <link href="assets/styles.css" rel="stylesheet" media="screen">
        <link href="assets/DT_bootstrap.css" rel="stylesheet" media="screen">
        <script src="vendors/modernizr-2.6.2-respond-1.1.0.min.js"></script>
    </head>    
    <body>
        <div class="container-fluid">
		    <div id="setbind" style="position:absolute;display:none;background:#efefef;padding:20px;z-index:9;"></div>
            <div class="row-fluid">
              <div class="span13" id="content">
                <div class="row-fluid">
                   <!-- block -->
						<div class="block">
                            <div class="navbar navbar-inner block-header">
                                <div><span style="float:left">个性采集 </span><span style="float:right"><a href="cj_list.php">返回资源库列表</a></span></div>
                            </div>
                            
                            <div class="block-content collapse in">
                                <div class="span12">
                                	<form class="form-horizontal" method="post" action="cj_show.php?g=admin&m=cj&a=apis&action=post" name="formapi" id="formapi" target="_blank">
									    <input name="cjid" type="hidden" value="<?=$admin['cjid']?>" />
                                        <input name="xmlurl" type="hidden" value="<?=$admin['xmlurl']?>" />
                                        <input name="xmltype" type="hidden" value="<?=$admin['xmltype']?>" />
                                    	<fieldset><br/>
                                   	<div class="control-group">
                                       <label class="control-label" for="focusedInput">分类ID：</label>
                                       <div class="controls">
                                          <input class="input-mini focused" name="cid" id="cid" maxlength="8" type="text" value="<?=$params['cid']?>">&nbsp;不限制，请留空
                                       </div>
                                   	</div>
                                    <div class="control-group">
                                       <label class="control-label" for="focusedInput">更新时间：</label>
                                       <div class="controls">
                                          <input class="input-mini focused" name="h" id="h" maxlength="8" type="text" value="<?php if($params['h']=='')echo 24; else echo $params['h'];?>">&nbsp;单位：小时，不限制时间留空
                                       </div>
                                   	</div>
                                    <div class="control-group">
                                       <label class="control-label" for="focusedInput">每页采集数量：</label>
                                       <div class="controls">
                                          <input class="input-mini focused" name="limit" id="limit" maxlength="3" type="text" value="<?php if($params['limit']=='')echo 50; else echo $params['limit'];?>">&nbsp;最大100
                                       </div>
                                   	</div>
									<div class="control-group">
                                        <table border="0" cellpadding="0" cellspacing="0" class="table table-bordered">
                                            <tr>
                                                <td width="15%" style="vertical-align:middle; text-align:center;">资源站分类转换：</td>
												<td style='padding-left:5px;'><?php
												foreach($cjlist as $row){
													echo "<li style='display:block;float:left;width:25%;'><a href=\"?g=admin&m=cj&a=apis&cjid=".$admin['cjid']."&action=show&xmlurl=".$admin['xmlurl']."&cid=".$row['list_id']."&wd=\">".$row['list_name']."（".$row['list_id']."）</a>&nbsp;<a class=\"cj-bind\" href=\"javascript:;\" data-key=\"".$row['bind_key']."\" data-val=\"".$row['list_id']."\" id=\"bind_".$row['bind_key']."\">".((fn_bind_id($row['bind_key']))? "<font color=\"green\">已转换</font>" : "<font color=\"red\">未转换</font>")."</a></li>";
												}
                                                ?></td>
                                            </tr>
                                            <tr>
                                                <td width="15%" style="vertical-align:middle; text-align:center;">相关操作：</td>
                                                <td style='align:center; vertical-align:middle; text-align:center;'>
                                                    <input type="submit" value="开始采集" class="btn btn-primary">
													<?php if($params['cid']) echo "<input type=\"button\" value=\"采集本类\" class=\"btn btn-primary\" id=\"vod-list\">";?>
                                                    <input type="button" value="采集当天" class="btn btn-primary" id="vod-day">
                                                    <input type="button" value="采集所有" class="btn btn-primary" id="vod-all">
                                                </td>
                                            </tr>
                                        </table>
                                   	</div>
                                   	</fieldset>
									</form>
									<form action="?g=admin&m=cj&a=apis&action=ids" method="post" name="myform" id="myform">
                                        <input name="cjid" type="hidden" value="<?=$admin['cjid']?>" />
                                        <input name="xmlurl" type="hidden" value="<?=$admin['xmlurl']?>" />
                                        <input name="xmltype" type="hidden" value="<?=$admin['xmltype']?>" />
									<table cellpadding="0" cellspacing="0" border="0" class="table table-striped table-bordered" style="table-layout:fixed;">
                                        <thead>
                                            <tr>
          									<th width="35%" style="vertical-align:middle; text-align:center;">片名、状态</th>
            								<th width="7%" style="vertical-align:middle; text-align:center;">入库分类</th>
            								<th width="10%" style="vertical-align:middle; text-align:center;">来源</th>
          									<th width="13%" style="vertical-align:middle; text-align:center;">更新时间</th>
                                            </tr>
                                        </thead>
                                        <tbody>
										<?php										
										    foreach($cjdata as $row){
												echo "<tr>";
												echo "<td><input class='uniform_on'  name='ids[]' type='checkbox' style='width:15px;height:25px;' value='".$row['vod_id']."'>『".$row['list_name']."』".$row['vod_name']."&nbsp;&nbsp;<sup><font color='green'>".(($row['vod_continu'])?$row['vod_continu']:$row['vod_title'])."</sup></font></td>";
												echo "<td style='vertical-align:middle; text-align:center;'>".(($row['vod_cid'])? "<font color=\"green\">已转换</font>" : "<font color=\"red\">未转换</font>")."</td>";
												echo "<td style='vertical-align:middle; text-align:center;'>".str_replace('$$$','  ',$row['vod_play'])."</td>";
												echo "<td style='vertical-align:middle; text-align:center;'>".$row['vod_addtime']."</td>";
												echo "</tr>";
											}
										?>
										</tbody>
										<tr>
										    <?php 
											    $page_link = '?'.http_build_query(array_merge($admin, $params));
			                                    $page_list = '共'.$cjpage['recordcount'].'条数据&nbsp;页次:'.$cjpage['pageindex'].'/'.$cjpage['pagecount'].'页&nbsp;'.getpage($cjpage['pageindex'],$cjpage['pagecount'], 5, $page_link, 'pagego(\''.$page_link.'\','.$cjpage['pagecount'].')');
											?>
                                            <td colspan="4" style='vertical-align:middle; text-align:center;'><?=$cjpage?></td>
										</tr>	
                                        <tr>
                                            <td colspan="3" style='vertical-align:middle; text-align:center;'>
                                                <input type="button" value="全选" class="btn btn-primary" onClick="checkall('all');">
                                                <input type="button" value="反选" class="btn btn-primary" onClick="checkall();">
                                                <input type="submit" value="采集选中" class="btn btn-primary">
                                            </td>
                                        </tr>  
                                    </table>
                                    </form>

                                    
                                 
                                </div>
                            </div>
                            
                            
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!--/.fluid-container-->

        <!--/.fluid-container-->
        <link href="vendors/datepicker.css" rel="stylesheet" media="screen">
        <link href="vendors/uniform.default.css" rel="stylesheet" media="screen">
        <link href="vendors/chosen.min.css" rel="stylesheet" media="screen">

        <link href="vendors/wysiwyg/bootstrap-wysihtml5.css" rel="stylesheet" media="screen">
        
        <script src="vendors/jquery-1.9.1.js"></script>
        <script src="bootstrap/js/bootstrap.min.js"></script>
        <script src="vendors/jquery.uniform.min.js"></script>
        <script src="vendors/chosen.jquery.min.js"></script>
        <script src="vendors/bootstrap-datepicker.js"></script>

        <script src="vendors/wysiwyg/wysihtml5-0.3.0.js"></script>
        <script src="vendors/wysiwyg/bootstrap-wysihtml5.js"></script>

        <script src="vendors/wizard/jquery.bootstrap.wizard.min.js"></script>

		<script type="text/javascript" src="vendors/jquery-validation/dist/jquery.validate.min.js"></script>
		<script src="assets/form-validation.js"></script>
        
		<script src="assets/scripts.js"></script>

        <script>
		$(function() {
            
        });

		$(document).ready(function(){
			$('.cj-bind').on('click',function(e){
		        setbind(e, 1, $(this).attr('data-key') ,$(this).attr('data-val'));
	        });

			$('#vod-day').on('click',function(){
		        $("input[name='ids[]']").each(function(){
			        this.checked = false;
		        });
		        $('#myform').attr('action','?g=admin&m=cj&a=apis&action=days&h=24');
		        $('#myform').submit();
	        });
			
			$('#vod-list').on('click',function(){
		        $("input[name='ids[]']").each(function(){
			        this.checked = false;
		        });
		        $('#myform').attr('action','?g=admin&m=cj&a=apis&action=all&cid=<?=$params['cid']?>');
		        $('#myform').submit();
	        });
			
	        $('#vod-all').on('click',function(){
		        $("input[name='ids[]']").each(function(){
			        this.checked = false;
		        });
		        $('#myform').attr('action','?g=admin&m=cj&a=apis&action=all');
		        $('#myform').submit();
	        });

        });
		function setbind(event, sid, key, val){
	        $('#showbg').css({width:$(window).width(),height:$(window).height()});	
	        var left = event.pageX-120;
	        var top = event.pageY+20;
	        $.ajax({
		        url: '?s=setbind&sid='+sid+'&key='+key+'&val='+val,
		        cache: false,
		        async: false,
		        success: function(res){
			        if(res.indexOf('status') > 0){
				        alert('对不起,您没有该功能的管理权限!');
			        }else{
				        $("#setbind").css({left:left,top:top,display:""});			
				        $("#setbind").html(res);
			        }
		        }
	        });
        }
        //提交绑定分类
       function submitbind (bind_key, bind_val){
	        $.ajax({
		        url: '?s=Insertbind&key='+bind_key+'&val='+bind_val,
		        success: function(res){
			        if(bind_val){
				        $("#bind_"+bind_key).html('<font color="green">已转换</font>');
			        }else{
				        $("#bind_"+bind_key).html('<font color="red">未转换</font>');
			        }
			        hidebind();
		        }
	        });	
        }
        //取消绑定
        function hidebind(){
	        $('#showbg').css({width:0,height:0});
	        $('#setbind').hide();
        }
		
		jQuery(document).ready(function() {   
	   		FormValidation.init();
		});
		function checkall($all){
	        if($all){
		        $("input[name='ids[]']").each(function(){
				    this.checked = true;
		        });
	        }else{
		        $("input[name='ids[]']").each(function(){
			        if(this.checked == false)
				        this.checked = true;
			        else
			            this.checked = false;
		            });		
	        }
        }
        //分页跳转
        function pagego($url,$total){
	        $page=document.getElementById('page').value;
	        if($page>0&&($page<=$total)){
		        $url=$url.replace('FFLINK',$page);
		        location.href=$url;
	        }
	        return false;
        }
        </script>
    </body>

</html>
<?php }?>