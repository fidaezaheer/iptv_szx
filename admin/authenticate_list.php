<!DOCTYPE html>
<?php
	include_once "cn_lang.php";
	include_once "common.php";
	$sql = new DbSql();
	$sql->login();
	
	$mac = $_GET["mac"];
	$cpuid = $_GET["cpuid"];

	$sql->connect_database_default();
	$mydb = $sql->get_database();
	$sql->create_database($mydb);
	
	$mytable = "authenticate_table";
	$sql->create_table($mydb, $mytable, "mac text, cpuid text, akey text, state text, time text");
	
	$key = $sql->query_data_2($mydb, $mytable, "mac", $mac, "cpuid", $cpuid, "akey");
	$state = $sql->query_data_2($mydb, $mytable, "mac", $mac, "cpuid", $cpuid, "state");
	
	$akey = j3_2($key);
	$akey = base64_decode($akey);
		
	$keyok = 0;
	
	$key1 = explode("#geminicpuid#",$akey);
	if(count($key1) >= 2)
	{
		$key2 = explode("#geminitime#",$key1[1]);
		if(count($key2) >= 2)
		{
			$key3 = explode("#geminiout#",$key2[1]);
			if(count($key3) >= 2)
			{
				if(abs(intval($key3[0])-time()) <= $key3[1] && strcmp($key2[0],$cpuid) == 0)
				{
					$keyok = 1;
				}
				
			}
		}
	}

	$mytable = "authenticate_table_stop";
	$sql->create_table($mydb, $mytable, "mac text, cpuid text, state text");
	$stop_state = $sql->query_data_2($mydb, $mytable, "mac", $mac, "cpuid", $cpuid, "state");	
	
	
	$mytable = "custom_table";
	$sql->create_table($mydb, $mytable, 
		"mac text,cpu text,ip text,space text, date text,
		time text,allow text, playlist text, online text, allocation text,
		proxy text, balance float,showtime text,contact text,member text,
		panal text,number text,ips text, onescrolltext text, onescrolltexttimes int, 
		numberdate int, scrollcontent text, scrolldate text, scrolltimes text, controlurl text, 		
		controltime int, unbundling int, accessdate timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP, mv text, isupdate int,
		remarks text, startime date, model text, remotelogin int, limitmodel text, 
		modelerror int, limittimes int");
		
	$cdkey = $sql->query_data_2($mydb, $mytable,"mac",$mac,"cpu",$cpuid,"number");
	$unbundling = $sql->query_data_2($mydb, $mytable,"mac",$mac,"cpu",$cpuid,"unbundling");
	
	
	$isupdate = $sql->query_data_2($mydb, $mytable,"mac",$mac,"cpu",$cpuid,"isupdate");
	$model = $sql->query_data_2($mydb, $mytable,"mac",$mac,"cpu",$cpuid,"model");
	$limitmodel = $sql->query_data_2($mydb, $mytable,"mac",$mac,"cpu",$cpuid,"limitmodel");
	$limittimes = $sql->query_data_2($mydb, $mytable,"mac",$mac,"cpu",$cpuid,"limittimes");
	if($limittimes == null)
	 	$limittimes = 0;
?>


<?php
	include "libs/iplocation.class.php";
			
	function char_int_2($val)
	{
		if($val == "0") return 0;
		else if($val == "1") return 1;
		else if($val == "2") return 2;
		else if($val == "3") return 3;
		else if($val == "4") return 4;
		else if($val == "5") return 5;
		else if($val == "6") return 6;
		else if($val == "7") return 7;
		else if($val == "8") return 8;
		else if($val == "9") return 9;
	}
	
	function scroll_int_2($val,$vals)
	{
		$textArray_j3 = array("G","U","Z","p","3","f","R","6","L","J","8","b","d","I","W","P","g","h","E","n","k","l","j","m","D","5","r","q","s","t","u","v","Y","w","y","A","O","N","C","o","i","F","a","S","c","9","K","0","e","M","z","4","Q","H","7","T","1","V","B","X","x","2");
		
		$length = count($textArray_j3);
		$ii=0;
		for($ii=0; $ii<$length; $ii++)
		{
			if(strcmp($textArray_j3[$ii],$val) == 0)
			{
				$offset = 0;
				if($ii-$vals < 0)
				{
					$offset = $length + ($ii-intval($vals));
				}
				else
				{
					$offset = $ii-intval($vals);
				}
				//alert(array[offset]);
	
				return $textArray_j3[$offset];			
			}
		}

		return $val;
	}
	
	function j3_2($val)
	{
		$vals = "872356";
		
		$url_tmp = "";
		$url_decoder = "";
	
		$password_tmp = $vals;
	
		$url_len = strlen($val);
		$password_len = strlen($vals);
		$password_len_count = floor($url_len/$password_len);
		if($url_len%$password_len>0)
			$password_len_count++;
			
		//$ii=0;
		for($ii=0;$ii<$password_len_count;$ii++)
		{
			$password_tmp = $password_tmp . $vals;
		}
		
		for($ii=0;$ii<$url_len;$ii++)
		{
			$c = substr($val,$ii,1);
			$n = char_int_2(substr($password_tmp,$ii,1));
			$url_decoder = $url_decoder . scroll_int_2($c,$n);
		}	
		
		return $url_decoder;	
	}
	
	function getContent($url, $method = 'GET', $postData = array()) {
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_USERAGENT, 'Mozilla/5.0 (X11; U; Linux i686; zh-CN; rv:1.9.1.2) Gecko/20120829 Firefox/3.5.2 GTB5');
        curl_setopt($curl, CURLOPT_HEADER, 0);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($curl, CURLOPT_REFERER, $url);
        $content = curl_exec($curl);

        $httpCode = curl_getinfo($curl, CURLINFO_HTTP_CODE);
        curl_close($curl);


        if ($httpCode == 200) {
                $content = mb_convert_encoding($content, "UTF-8", "GBK");
        }
		
        return $content;
        
    }
	
	function getSpace($ip)
	{

		#需要查询的IP
		//$ip = "175.191.142.54";
		//返回格式
		$format = "text";//默认text,json,xml,js
		//返回编码
		$charset = "utf8"; //默认utf-8,gbk或gb2312
		#实例化(必须)
		$ip_l=new ipLocation();
		$address=$ip_l->getaddress($ip);

		$address["area1"] = iconv('GB2312','utf-8//ignore',$address["area1"]);
		$address["area2"] = iconv('GB2312','utf-8//ignore',$address["area2"]);

		$add=$address["area1"]." ".$address["area2"];

		//echo $add;	
	
		return $add;
	}
?>
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
    
    <body onLoad="createSelect(1)">
        <div class="container-fluid">
            <div class="row-fluid">
              	<div class="span13" id="content">
                <div class="row-fluid">
                   <!-- block -->
						<div class="block">
                          <div class="navbar navbar-inner block-header">
                                <div class="muted pull-left"><?php echo $lang_array["authenticate_list_text1"] ?></div>
                            </div>
                            
                            <div class="block-content collapse in">
                                <div class="span12">
                                	<form class="form-horizontal" id="form1" name="authform" method="post" action="authenticate_post.php?mac=<?php echo $_GET["mac"]?>&cpuid=<?php echo $_GET["cpuid"]?>" enctype='multipart/form-data'>
                                    <fieldset><br/>
                                    <div class="control-group">
                                       <label class="control-label" for="focusedInput" style="text-shadow:#0CF"><?php echo $lang_array["authenticate_list_text2"] ?></label>
                                       <div class="controls">
   									   <?php 
											if(strcmp($state,"0") == 0)
											{
												echo $lang_array["authenticate_list_text5"];
											}
											else if(strcmp($state,"1") == 0)
											{
												echo $lang_array["authenticate_list_text6"];
											}
											else if(strcmp($state,"2") == 0)
											{
												echo $lang_array["authenticate_list_text7"];
											}
											else
											{
												echo $lang_array["authenticate_list_text8"];
											}
										?>   
                                       &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;                                     
									   <?php echo $lang_array["authenticate_list_text30"] ?>&nbsp;&nbsp;<input class="input-xxlarge focused" id="key_id" name="key_id" type="text" value="<?php echo $key;?>">
                                       <button type="button" class="btn btn-primary" onclick="save()"><?php echo $lang_array["authenticate_list_text4"] ?></button>
                                       </div>
                                   	</div>
                                    
                                    <div class="control-group">
                                       	<label class="control-label" for="focusedInput" style="text-shadow:#0CF"><?php echo $lang_array["authenticate_list_text10"] ?></label>
                                       	<div class="controls">
										<?php
											if($stop_state == null || $stop_state == 1)
											{
												echo $lang_array["authenticate_list_text11"];
												echo "&nbsp;&nbsp;&nbsp;";
												echo "<input class='btn btn-primary' name='' type='button' value='" . $lang_array["authenticate_list_text12"] . "' onclick='authenticate_stop(0)'/>";
											}
											else
											{
												echo $lang_array["authenticate_list_text12"];
												echo "&nbsp;&nbsp;&nbsp;";
												echo "<input class='btn btn-primary'  name='' type='button' value='" . $lang_array["authenticate_list_text11"] . "' onclick='authenticate_stop(1)''/>";
											}

										?>
                                       </div>
                                   	</div>                                    
                                    
                                    <div class="control-group">
                                       	<label class="control-label" for="focusedInput" style="text-shadow:#0CF"><?php echo $lang_array["authenticate_list_text13"] ?></label>
                                       	<div class="controls" style="vertical-align:middle;font-size:20px;">
                                        	<?php if(strlen($cdkey) > 4) echo $cdkey; else echo $lang_array["authenticate_list_text14"]; ?>
                                            &nbsp;&nbsp;&nbsp
                                            <!--
                                            <button type="button" class="btn btn-primary" onclick=""><?php //echo $lang_array["authenticate_list_text15"] ?></button>
                                            -->
                                       	</div>
                                   	</div>
                                    
                                    <div class="control-group">
                                       	<label class="control-label" for="focusedInput" style="text-shadow:#0CF"><?php echo $lang_array["authenticate_list_text16"] ?></label>
                                       	<div class="controls" style="vertical-align:middle;" >
                                        	<?php if($unbundling != null && $unbundling == 0) echo $lang_array["authenticate_list_text17"]; else echo $lang_array["authenticate_list_text18"]; ?>&nbsp;&nbsp;
                                            <input class="btn btn-primary"  name="" type="button" value='<?php if($unbundling != null && $unbundling == 0) echo $lang_array["authenticate_list_text18"]; else echo $lang_array["authenticate_list_text17"]; ?>' onclick="unbundling_number(<?php if($unbundling != null && $unbundling == 0) echo 1; else echo 0; ?>)"/>
                                       	</div>
                                   	</div>
                                    
                                    
                                    <div class="control-group">
                                       	<label class="control-label" for="focusedInput" style="text-shadow:#0CF"><?php echo $lang_array["authenticate_list_text24"] ?></label>
                                       	<div class="controls" style="vertical-align:middle;" >
                                            <?php if($isupdate == 0) echo $lang_array["authenticate_list_text21"]; else echo $lang_array["authenticate_list_text20"]; ?>
                                            &nbsp;&nbsp;
                                            <input class="btn btn-primary"  name="" type="button" value='<?php echo $lang_array["authenticate_list_text22"] ?>' onclick="isupdate(1)"/>&nbsp;&nbsp;
                                            <input class="btn btn-primary"  name="" type="button" value='<?php echo $lang_array["authenticate_list_text23"] ?>' onclick="isupdate(0)"/>
                                       	</div>
                                   	</div>
                                    
                                    <div class="control-group">
                                       	<label class="control-label" for="focusedInput" style="text-shadow:#0CF"><?php echo $lang_array["authenticate_list_text31"] ?></label>
                                       	<div class="controls" style="vertical-align:middle;">
                                            <label><?php if(strlen($model) > 4) echo $model; else echo $lang_array["authenticate_list_text32"]; ?></label>
                                            
                                       	</div>
                                   	</div>
                                    
                                    <div class="control-group">
                                       	<label class="control-label" for="focusedInput" style="text-shadow:#0CF"><?php echo $lang_array["authenticate_list_text35"] ?></label>
                                       	<div class="controls" style="vertical-align:middle;">
                                        	<input style="width:15px;height:28px;" type="radio" name="limitmodel_radio" value="0" id="limitmodel0_radio" <?php if($limitmodel == null || strlen($limitmodel)==0) echo "checked"?>  onChange="limitmodel_change(0)"/><?php echo $lang_array["no_text1"] ?>&nbsp;
                                       		<input style="width:15px;height:28px;" type="radio" name="limitmodel_radio" value="1" id="limitmodel1_radio" <?php if($limitmodel != null || strlen($limitmodel)>0) echo "checked"?> onChange="limitmodel_change(1)"/><?php echo $lang_array["yes_text1"] ?>&nbsp;
                                            <input class="input-large focused" id="limitmodel_id" name="limitmodel_id" type="text" value="<?php if($limitmodel != null || strlen($limitmodel)>0) echo $limitmodel; else echo $model;?>">
                                            <button type='button' class='btn btn-primary' onclick='limitmodel()'> . <?php echo $lang_array['sure_text1'] ?>. </button>
                                            
                                       	</div>
                                   	</div>
                                    
                                    <div class="control-group">
                                       	<label class="control-label" for="focusedInput" style="text-shadow:#0CF"><?php echo $lang_array["authenticate_list_text37"] ?></label>
                                       	<div class="controls" style="vertical-align:middle;">
                                        	<input style="width:15px;height:28px;" type="radio" name="limittimes_radio" value="0" id="limittimes0_radio" <?php if($limittimes == null || $limittimes==0) echo "checked"?>  onChange="limittimes_change(0)"/><?php echo $lang_array["no_text1"] ?>&nbsp;
                                       		<input style="width:15px;height:28px;" type="radio" name="limittimes_radio" value="1" id="limittimes1_radio" <?php if($limittimes != null && $limittimes>0) echo "checked"?> onChange="limittimes_change(1)"/><?php echo $lang_array["yes_text1"] ?>&nbsp;
                                            <?php echo $lang_array["authenticate_list_text38"] ?>:<input class="input-mini focused" id="limittimes_id" name="limittimes_id" type="text" value="<?php echo $limittimes; ?>">
                                            <button type='button' class='btn btn-primary' onclick='limittimes()'> . <?php echo $lang_array['sure_text1'] ?>. </button>
                                            
                                       	</div>
                                   	</div>
                                    
                                    <table cellpadding="0" cellspacing="0" border="0" class="table table-striped table-bordered" style="table-layout:fixed;">
                                        <thead>
                                            <tr>
            									<th width="10%" style="vertical-align:middle; text-align:center;"><?php echo $lang_array["authenticate_list_text26"] ?></th>
          										<th width="20%" style="vertical-align:middle; text-align:center;"><?php echo $lang_array["authenticate_list_text27"] ?></th>
            									<th width="20%" style="vertical-align:middle; text-align:center;"><?php echo $lang_array["authenticate_list_text28"] ?></th>
                                                
                                                <th width="20%" style="vertical-align:middle; text-align:center;"><?php echo $lang_array["authenticate_list_text33"] ?></th>
                                                
                                                <th width="20%" style="vertical-align:middle; text-align:center;"><?php echo $lang_array["authenticate_list_text34"] ?></th>
                                                <th width="20%" style="vertical-align:middle; text-align:center;"><?php echo $lang_array["authenticate_list_text36"] ?></th>
            									<th width="50%" style="vertical-align:middle; text-align:center;"><?php echo $lang_array["authenticate_list_text29"] ?></th>
                                            </tr>
                                        </thead>
                                        
                                        <?php
											$login_ipss = $sql->query_data_2($mydb, $mytable,"mac",$mac,"cpu",$cpuid,"ips");
											
											if(strlen($login_ipss)  > 7)
											{	
												$login_ips = explode("#",$login_ipss);
												echo "<tbody>";
												for($ii=0; $ii<count($login_ips); $ii++)
												{
													$login_ip = $login_ips[$ii];
													if(strlen($login_ip) > 7)
													{
														$loginmodel = "";
														$login = explode("&",$login_ip);
														if(count($login) >= 3)
															$loginmodel = $login[2];
														echo "<tr class='odd gradeA'>";
														echo "<td style='vertical-align:middle; text-align:center;word-wrap:break-word;'>" . $ii . "</td>";
														echo "<td style='vertical-align:middle; text-align:center;word-wrap:break-word;'>" . $login[1] . "</td>";
														echo "<td style='vertical-align:middle; text-align:center;word-wrap:break-word;'>" . $login[0] . "</td>";
														echo "<td style='vertical-align:middle; text-align:center;word-wrap:break-word;'>" . $mac . "</td>";
														echo "<td style='vertical-align:middle; text-align:center;word-wrap:break-word;'>" . $cpuid . "</td>";
														echo "<td style='vertical-align:middle; text-align:center;word-wrap:break-word;'>" . $loginmodel . "</td>";
														echo "<td style='vertical-align:middle; text-align:center;word-wrap:break-word;'>" . getSpace($login[0]) . "</td>";
														echo "</tr>";
													}
												}
												echo "</tbody>";
											}
										?>
                                    </table>
                                    
                                    <div class="form-actions">
                                    	<input name="" class="btn btn-primary" type="button" value="<?php echo $lang_array["back_text1"] ?>" onclick="back_page()"/>
                                    </div>
                                    
                                   	</fieldset>
									</form>
                                </div>
                            </div>
                         </div>
                    </div>
				</div>
            </div>
        </div>
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

		jQuery(document).ready(function() {   
	   		FormValidation.init();
		});
		
		
		function GetRadioValue(RadioName){
    		var obj;
   			obj=document.getElementsByName(RadioName);
    		if(obj!=null){
        		var i;
        		for(i=0;i<obj.length;i++){
            		if(obj[i].checked){
                		return obj[i].value;
            		}
        		}
    		}
    		return null;
		}
		
		function save()
		{
			var value = document.getElementById("key_id").value;
			
			if(value.length < 1)
			{
				alert("<?php echo $lang_array["authenticate_list_text9"] ?>");
			}
			else
			{
				document.authform.submit();
			}
		}
		
		function authenticate_stop(value)
		{
			window.location.href = "authenticate_stop.php?mac=<?php echo $_GET["mac"]?>&cpuid=<?php echo $_GET["cpuid"]?>&state=" + value;	
		}
		
		function unbundling_number(value)
		{
			window.location.href = "unbundling_post.php?mac=<?php echo $_GET["mac"]?>&cpuid=<?php echo $_GET["cpuid"]?>&value="+value;
		}

		function isupdate(value)
		{
			window.location.href = "isupdate_post.php?mac=<?php echo $_GET["mac"]?>&cpuid=<?php echo $_GET["cpuid"]?>&value="+value;
		}
		
		function limitmodel()
		{
			var value = document.getElementById("limitmodel_id").value;
			window.location.href = "limitmodel_post.php?mac=<?php echo $_GET["mac"]?>&cpuid=<?php echo $_GET["cpuid"]?>&value="+value;
		}

		function limitmodel_change(value)
		{
			if(value == 0)
				document.getElementById("limitmodel_id").value = "";
			else if(value == 1)
			{
				document.getElementById("limitmodel_id").value = "<?php if($limitmodel != null || strlen($limitmodel)>0) echo $limitmodel; else echo $model;?>";
			}
		}
		
		function limittimes()
		{
			var value = document.getElementById("limittimes_id").value;
			if(GetRadioValue("limittimes_radio") == "1" && value <= 0)
			{
				alert("<?php echo $lang_array["authenticate_list_text39"]; ?>");
				return;
			}
			
			window.location.href = "limittimes_post.php?mac=<?php echo $_GET["mac"]?>&cpuid=<?php echo $_GET["cpuid"]?>&value="+value;
		}
		
		function limittimes_change(value)
		{
			if(value == 0)
				document.getElementById("limittimes_id").value = "";
			else if(value == 1)
			{
				document.getElementById("limittimes_id").value = "<?php if($limittimes != null || $limittimes>0) echo $limittimes; else echo 0;?>";
			}
		}
		
		function back_page()
		{
			window.location.href = "custom_list.php";
				
		}
        </script>
    </body>
</html>

<?php
	$sql->disconnect_database();
?>