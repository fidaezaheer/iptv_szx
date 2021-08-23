<!DOCTYPE html>
<?php
	include_once "cn_lang.php";
	include_once "common.php";
	$sql = new DbSql();
	$sql->login();
	
	$sql->connect_database_default();
	$mydb = $sql->get_database();
	$sql->create_database($mydb);
	
	$mytable = "custom_table";
	$sql->create_table($mydb, $mytable, 
	"mac text,cpu text,ip text,space text, date text,
	time text,allow text, playlist text, online text, allocation text,
	proxy text, balance float,showtime text,contact text,member text,
	panal text,number text,ips text, onescrolltext text, onescrolltexttimes int, 
	numberdate int, scrollcontent text, scrolldate text, scrolltimes text, controlurl text, 		
	controltime int, unbundling int, accessdate timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP, mv text, isupdate int,
	remarks text");
	
	$row = $sql->fetch_datas_where_2($mydb, $mytable,"mac",$_GET["mac"],"cpu",$_GET["cpuid"]);
	$allow = $row[0][6];
	$sdate = $row[0][4];
	$edate = $row[0][5];
	$allocation = $row[0][9];
	$playlist = $row[0][7];
	$showtime = $row[0][12];
	$contact = $row[0][13];
	$member = $row[0][14];
	$panel = $row[0][15];
	if($panel == null)
		$panel = 1;
		
	$proxy = $row[0][10];
	$number = $row[0][16];
	$remarks = $row[0][30];
	$scrollcontent = $row[0][21];
	
	/*
	$allow = $sql->query_data_2($mydb, $mytable,"mac",$_GET["mac"],"cpu",$_GET["cpuid"],"allow");
	$sdate = $sql->query_data_2($mydb, $mytable,"mac",$_GET["mac"],"cpu",$_GET["cpuid"],"date");
	$edate = $sql->query_data_2($mydb, $mytable,"mac",$_GET["mac"],"cpu",$_GET["cpuid"],"time");
	
	$allocation = $sql->query_data_2($mydb, $mytable,"mac",$_GET["mac"],"cpu",$_GET["cpuid"],"allocation");
	$playlist = $sql->query_data_2($mydb, $mytable,"mac",$_GET["mac"],"cpu",$_GET["cpuid"],"playlist");
	
	$showtime = $sql->query_data_2($mydb, $mytable,"mac",$_GET["mac"],"cpu",$_GET["cpuid"],"showtime");
	$contact = $sql->query_data_2($mydb, $mytable,"mac",$_GET["mac"],"cpu",$_GET["cpuid"],"contact");
	
	$member = $sql->query_data_2($mydb, $mytable,"mac",$_GET["mac"],"cpu",$_GET["cpuid"],"member");
	$panel = $sql->query_data_2($mydb, $mytable,"mac",$_GET["mac"],"cpu",$_GET["cpuid"],"panal");
	if($panel == null)
		$panel = 1;
		
	$proxy = $sql->query_data_2($mydb, $mytable,"mac",$_GET["mac"],"cpu",$_GET["cpuid"],"proxy");
	$number = $sql->query_data_2($mydb, $mytable,"mac",$_GET["mac"],"cpu",$_GET["cpuid"],"number");
	$remarks = $sql->query_data_2($mydb, $mytable,"mac",$_GET["mac"],"cpu",$_GET["cpuid"],"remarks");
	$scrollcontent = $sql->query_data_2($mydb, $mytable,"mac",$_GET["mac"],"cpu",$_GET["cpuid"],"scrollcontent");
	*/
	
	$lefttime = getChaBetweenTwoDate($edate,date('Y-m-d',time()));
	if($lefttime < 0)
		$lefttime = 0;
?>

<?php
function getChaBetweenTwoDate($date1,$date2)
{
	
	$Date_List_a1=explode("-",$date1);
	$Date_List_a2=explode("-",$date2);
	if(count($Date_List_a1) < 3 || count($Date_List_a2) < 3)
		return 0;
	if(is_numeric($Date_List_a1[1]) && is_numeric($Date_List_a1[0]) && is_numeric($Date_List_a1[2])
		&& is_numeric($Date_List_a2[1]) && is_numeric($Date_List_a2[0]) && is_numeric($Date_List_a2[2])) 	
	{
		$d1=mktime(0,0,0,intval($Date_List_a1[1]),intval($Date_List_a1[2]),intval($Date_List_a1[0]));
		$d2=mktime(0,0,0,intval($Date_List_a2[1]),intval($Date_List_a2[2]),intval($Date_List_a2[0]));
		$Days=round(($d1-$d2)/3600/24);
		return $Days;
	}
	else
	{
		return 0;
	}
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
                                <div class="muted pull-left"><?php echo $lang_array["custom_edit_text4"] ?></div>
                            </div>
                            
                            <div class="block-content collapse in">
                                <div class="span12">
                                	<form class="form-horizontal" method="post" action="custom_get.php" enctype='multipart/form-data'>
                                    <fieldset><br/>
                                    <div class="control-group">
                                       <label class="control-label" for="focusedInput" style="text-shadow:#0CF"><?php echo $lang_array["custom_edit_text4"] ?></label>
                                       <div class="controls">
                                          <input name="radio" type="radio" id="radio0" value="no" style="width:15px;height:28px;" <?php if(strcmp($allow,"no")==0) echo "checked"; ?> onclick="changeradio0()" ><?php echo $lang_array["custom_edit_text1"] ?>&nbsp;&nbsp;&nbsp;&nbsp;
                                          <input name="radio" type="radio" id="radio3" value="pause" style="width:15px;height:28px;" <?php if(strcmp($allow,"pause")==0) echo "checked"; ?> onclick="changeradio3()" ><?php echo $lang_array["custom_edit_text28"] ?>&nbsp;&nbsp;&nbsp;&nbsp;
                                          <input name="radio" type="radio" id="radio1" value="allway" style="width:15px;height:28px;" <?php if(strcmp($allow,"yes")==0 && $edate == -1) echo "checked"; ?> onclick="changeradio2()" ><?php echo $lang_array["custom_edit_text3"] ?>&nbsp;&nbsp;&nbsp;&nbsp;
                                          <input name="radio" type="radio" id="radio2" value="yes" style="width:15px;height:28px;" <?php if((strcmp($allow,"yes")==0) || strcmp($allow,"pre")==0) echo "checked"; ?> onclick="changeradio1()"><?php echo $lang_array["custom_edit_text2"] ?>&nbsp;&nbsp;&nbsp;&nbsp;
                                          <!--
                                          <select id="tYEAR" size="1" onChange="createSelect()" style="width:72px;" ></select><?php //echo $lang_array["custom_edit_text22"] ?>
										  <select id="tMON" size="1" onChange="createSelect()" style="width:54px;"></select><?php //echo $lang_array["custom_edit_text23"] ?>
										  <select id="tDAY" size="1" style="width:54px;"></select><?php //echo $lang_array["custom_edit_text24"] ?>
                                          -->
                                          &nbsp;&nbsp;<?php echo $lang_array["custom_edit_text19"] ?>:<font id="admin_leftday_text"><?php echo $lefttime; ?></font>&nbsp;&nbsp;&nbsp;&nbsp;<?php echo $lang_array["custom_edit_text20"] ?>:<input name="" type="text" value="0" size="5" maxlength="5" id="admin_allowday_text" oninput="endDate()" style="width:36px;"/><?php echo $lang_array["custom_edit_text25"] ?>&nbsp;&nbsp;&nbsp;&nbsp;<?php echo $lang_array["custom_edit_text21"] ?>:<font id="endtime_label"><?php echo $edate; ?></font><?php echo $lang_array["custom_edit_text25"] ?>
                                       </div>
                                   	</div>

                                    <div class="control-group">
										<label class="control-label" ><?php echo $lang_array["custom_edit_text5"] ?></label>
                                        <div class="controls">
											 <input name="allocation" type="radio" id="allocation0" value="all" style="width:15px;height:28px;" <?php if(strcmp($allocation,"all")==0 || strcmp($allocation,"ERROR")==0 || strcmp($allocation,"me")==0 || strlen($allocation) <= 0) echo "checked"; ?>><?php echo $lang_array["custom_edit_text6"] ?>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                             <input name="allocation" type="radio" id="allocation1" value="auto" style="width:15px;height:28px;"<?php if(strcmp($allocation,"auto")==0) echo "checked"; ?>><?php echo $lang_array["custom_edit_text7"] ?>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                             <input name="allocation" type="radio" id="allocation2" value="manually" style="width:15px;height:28px;" <?php if(strcmp($allocation,"manually")==0) echo "checked" ?>><?php echo $lang_array["custom_edit_text8"] ?>  
                                             <select id="playlist_select_id" name="">
   	 										 <?php
												$mytable = "playlist_type_table";
												$sql->create_table($mydb, $mytable, "name text, space longtext, id text, playlist longtext");	
												$namess = $sql->fetch_datas($mydb, $mytable);
												foreach($namess as $names) {
													if(strcmp($playlist,$names[2]) == 0)
													{
														echo "<option value='" . $names[2] . "' selected='selected'>" . $names[0] . "</option>";
													}
													else
													{
														echo "<option value='" . $names[2] . "'>" . $names[0] . "</option>";
													}
												}
											?>
  											</select>
                                        </div>    
                                    </div>
                                    
                                    <div class="control-group">
										<label class="control-label" ><?php echo $lang_array["custom_edit_text9"] ?></label>
                                        <div class="controls">
											<input name="proxy" type="radio" id="proxy0" value="admin" style="width:15px;height:28px;" <?php if(strcmp($proxy,"admin")==0) echo "checked" ?>><?php echo $lang_array["custom_edit_text10"] ?>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                            <input name="proxy" type="radio" id="proxy1" value="proxy" style="width:15px;height:28px;" <?php if(strcmp($proxy,"admin")!=0) echo "checked" ?>><?php echo $lang_array["custom_edit_text11"] ?>  
											<select id="proxy_select_id" name="">
											<?php
												/*
												$mytable = "proxy_table";
												$sql->create_table($mydb, $mytable, "name text, password text");
	
												$namess = $sql->fetch_datas($mydb, $mytable);
												foreach($namess as $names) {
													if(strcmp($proxy,$names[0]) == 0)
													{
														echo "<option value='" . $names[0] . "' selected='selected'>" . $names[0] . "</option>";
													}
													else
													{
														echo "<option value='" . $names[0] . "'>" . $names[0] . "</option>";
													}
												}
												*/
												$mytable = "proxy_table";
												$sql->create_table($mydb, $mytable, "name text, password text, ptip text, edit int, watermark text, validity date, allow int, remark text, ccount int, panal int");
												$namess = $sql->fetch_datas($mydb, $mytable);
												foreach($namess as $names) {
													if(strcmp($proxy,$names[0]) == 0)
													{
														if(strlen($names[7]) > 0)
															echo "<option value='" . $names[0] . "' selected='selected'>" . $names[0] ."(". $names[7] .")". "</option>";
														else
															echo "<option value='" . $names[0] . "' selected='selected'>" . $names[0] . "</option>";
													}
													else
													{
														if(strlen($names[7]) > 0)
															echo "<option value='" . $names[0] . "'>" . $names[0] . "(". $names[7] .")". "</option>";
														else
															echo "<option value='" . $names[0] . "'>" . $names[0] . "</option>";
													}
												}
											?>
											</select>
                                        </div>    
                                    </div>                                   

                                    <div class="control-group">
										<label class="control-label" ><?php echo $lang_array["custom_edit_text12"] ?></label>
                                        <div class="controls">
											<input name="show" type="radio" id="show0" value="yes" style="width:15px;height:28px;" <?php if(strcmp($showtime,"no")!=0) echo "checked"; ?>><?php echo $lang_array["yes_text1"] ?>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                            <input name="show" type="radio" id="show1" value="no" style="width:15px;height:28px;" <?php if(strcmp($showtime,"no")==0) echo "checked"; ?>><?php echo $lang_array["no_text1"] ?>  
                                        </div>    
                                    </div>
                                    
									<div class="control-group">
										<label class="control-label" ><?php echo $lang_array["custom_edit_text13"] ?></label>
                                        <div class="controls">
											<input class="input-xxlarge focused" id="contact" name="" type="text" value="<?php echo $contact;?>">
                                        </div>    
                                    </div>
                                    
                                    <div class="control-group">
										<label class="control-label" ><?php echo $lang_array["custom_edit_text14"] ?></label>
                                        <div class="controls">
											<input class="input-medium focused" id="member" name="" type="text" value="<?php echo $member;?>">
                                        </div>    
                                    </div>   
                                             
                                             
                                    <div class="control-group">
										<label class="control-label" ><?php echo $lang_array["custom_edit_text26"] ?></label>
                                        <div class="controls">
											<input class="input-xxlarge focused" id="remarks" name="" type="text" value="<?php echo $remarks;?>">
                                        </div>    
                                    </div> 
                                    
                                    <div class="control-group">
										<label class="control-label" ><?php echo $lang_array["custom_edit_text27"] ?></label>
                                        <div class="controls">
											<input class="input-xxlarge focused" id="scrollcontent" name="" type="text" value="<?php echo $scrollcontent;?>">
                                        </div>    
                                    </div> 
                                    
                                    <div class="control-group">
                                       <label class="control-label" for="focusedInput"><?php echo $lang_array["custom_edit_text15"] ?></label>
                                       <div class="controls">
                                          <input name="panel" type="radio" id="panel0" value="0" style="width:15px;height:28px;" <?php if(strcmp($panel,"0")==0 || strcmp($panel,"null") == 0 || strlen($panel) == 0) echo "checked"; ?>><?php echo $lang_array["custom_edit_text16"] ?>&nbsp;&nbsp;&nbsp;&nbsp;
                                          <input name="panel" type="radio" id="panel2" value="2" style="width:15px;height:28px;" <?php if(strcmp($panel,"2")==0) echo "checked"; ?>><?php echo $lang_array["custom_edit_text17"] ?>&nbsp;&nbsp;&nbsp;&nbsp;
                                          <input name="panel" type="radio" id="panel3" value="3" style="width:15px;height:28px;" <?php if(strcmp($panel,"3")==0) echo "checked"; ?>><?php echo $lang_array["custom_edit_text30"] ?>&nbsp;&nbsp;&nbsp;&nbsp;
                                          <input name="panel" type="radio" id="panel1" value="1" style="width:15px;height:28px;" <?php if(strcmp($panel,"1")==0) echo "checked"; ?>><?php echo $lang_array["custom_edit_text18"] ?>&nbsp;&nbsp;&nbsp;&nbsp;
                                       </div>
                                   	</div>
                                                                                                                                     
                                    <div class="form-actions">
   										<button type="button" class="btn btn-primary" onclick="save()"><?php echo $lang_array["save_text1"] ?></button>
                                		<button type="reset" class="btn" onclick="back_page()"><?php echo $lang_array["back_text1"] ?></button>
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
        
        <FORM name="authform_edit" action="" method="post">  
    	<input name="scrollcontent" id="scrollcontent" type="hidden" value=""/>
    	</Form>
        
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
	
		Date.prototype.pattern=function(fmt) {         
    	var o = {         
    	"M+" : this.getMonth()+1, //月份         
    	"d+" : this.getDate(), //日         
    	"h+" : this.getHours()%12 == 0 ? 12 : this.getHours()%12, //小时         
    	"H+" : this.getHours(), //小时         
    	"m+" : this.getMinutes(), //分         
    	"s+" : this.getSeconds(), //秒         
    	"q+" : Math.floor((this.getMonth()+3)/3),    
    	"S" : this.getMilliseconds() //毫秒         
    	};   
		      
    	var week = {         
    		"0" : "/u65e5",         
    		"1" : "/u4e00",         
    		"2" : "/u4e8c",         
    		"3" : "/u4e09",         
    		"4" : "/u56db",         
    		"5" : "/u4e94",         
    		"6" : "/u516d"        
    	};         
    	
		if(/(y+)/.test(fmt)){         
       	 	fmt=fmt.replace(RegExp.$1, (this.getFullYear()+"").substr(4 - RegExp.$1.length));         
    	}        
		 
    	if(/(E+)/.test(fmt)){         
        	fmt=fmt.replace(RegExp.$1, ((RegExp.$1.length>1) ? (RegExp.$1.length>2 ? "/u661f/u671f" : "/u5468") : "")+week[this.getDay()+""]);         
    	}     
		    
   	 	for(var k in o){         
        	if(new RegExp("("+ k +")").test(fmt)){         
            	fmt = fmt.replace(RegExp.$1, (RegExp.$1.length==1) ? (o[k]) : (("00"+ o[k]).substr((""+ o[k]).length)));         
        	}         
    	}         
    	return fmt;         
		} 
	
        $(function() {
            $(".datepicker").datepicker();
            $(".uniform_on").uniform();
            $(".chzn-select").chosen();
            $('.textarea').wysihtml5();

            $('#rootwizard').bootstrapWizard({onTabShow: function(tab, navigation, index) {
                var $total = navigation.find('li').length;
                var $current = index+1;
                var $percent = ($current/$total) * 100;
                $('#rootwizard').find('.bar').css({width:$percent+'%'});
                // If it's the last tab then hide the last button and show the finish instead
                if($current >= $total) {
                    $('#rootwizard').find('.pager .next').hide();
                    $('#rootwizard').find('.pager .finish').show();
                    $('#rootwizard').find('.pager .finish').removeClass('disabled');
                } else {
                    $('#rootwizard').find('.pager .next').show();
                    $('#rootwizard').find('.pager .finish').hide();
                }
            }});
            $('#rootwizard .finish').click(function() {
                alert('Finished!, Starting over!');
                $('#rootwizard').find("a[href*='tab1']").trigger('click');
            });
        });

        $(function() {
            
        });
		
		function create2Select(year, month, day) 
		{ 
			//var selYear = document.getElementById("tYEAR"); 
			//var selMonth = document.getElementById("tMON"); 
			//var selDay = document.getElementById("tDAY"); 
			
			var selYear = "<?php echo date("Y") ?>";
			var selMonth = "<?php echo date("m") ?>";
			var selDay = "<?php echo date("d") ?>";
			
			var dt = new Date(year,month-1,day); 

			//if(ActionFlag == 1) 
			{ 
				MinYear = dt.getFullYear(); 
				MaxYear = dt.getFullYear()+10; 

				for(var i = MinYear; i <= MaxYear; i++) 
				{ 
					var op = document.createElement("OPTION"); 
					op.value = i; 
					op.innerHTML = i; 
					selYear.appendChild(op); 
				} 
				selYear.selectedIndex = 0; 

				for(var i = 1; i < 13; i++) 
				{ 
					var op = document.createElement("OPTION"); 
					op.value = i; 
					op.innerHTML = i; 
					selMonth.appendChild(op); 
				} 
				selMonth.selectedIndex = dt.getMonth(); 
			} 

			var date = new Date(selYear.value, selMonth.value, 0); 
			var daysInMonth = date.getDate(); 
			selDay.options.length = 0; 

			for(var i = 1; i <= daysInMonth ; i++) 
			{ 
				var op = document.createElement("OPTION"); 
				op.value = i; 
				op.innerHTML = i; 
				selDay.appendChild(op); 
			} 
			selDay.selectedIndex = dt.getDate() - 1; 
		} 

		function endSelect(ActionFlag) 
		{ 
			var selYear = document.getElementById("eYEAR"); 
			var selMonth = document.getElementById("eMON"); 
			var selDay = document.getElementById("eDAY"); 
			var dt = new Date(); 

			if(ActionFlag == 1) { 
				MinYear = dt.getFullYear(); 
				MaxYear = dt.getFullYear()+10; 

				for(var i = MinYear; i <= MaxYear; i++) 
				{ 
					var op = document.createElement("OPTION"); 
					op.value = i; 
					op.innerHTML = i; 
					selYear.appendChild(op); 
				} 
				selYear.selectedIndex = 1; 

				for(var i = 1; i < 13; i++) 
				{ 
					var op = document.createElement("OPTION"); 
					op.value = i; 
					op.innerHTML = i; 
					selMonth.appendChild(op); 
				} 
				selMonth.selectedIndex = dt.getMonth(); 
			} 

			var date = new Date(selYear.value, selMonth.value, 0); 
			var daysInMonth = date.getDate(); 
			selDay.options.length = 0; 

			for(var i = 1; i <= daysInMonth ; i++) 
			{ 
				var op = document.createElement("OPTION"); 
				op.value = i; 
				op.innerHTML = i; 
				selDay.appendChild(op); 
			} 
			selDay.selectedIndex = dt.getDate(); 
		}
		
		function createSelect(ActionFlag) 
		{ 
			//var selYear = document.getElementById("tYEAR"); 
			//var selMonth = document.getElementById("tMON"); 
			//var selDay = document.getElementById("tDAY"); 
			
			var selYear = "<?php echo date("Y") ?>";
			var selMonth = "<?php echo date("m") ?>";
			var selDay = "<?php echo date("d") ?>";
			
			var dt = new Date(); 

			if(ActionFlag == 1) { 
				MinYear = dt.getFullYear(); 
				MaxYear = dt.getFullYear()+10; 

				for(var i = MinYear; i <= MaxYear; i++) 
				{ 
					var op = document.createElement("OPTION"); 
					op.value = i; 
					op.innerHTML = i; 
					selYear.appendChild(op); 
				} 
				selYear.selectedIndex = 0; 

				for(var i = 1; i < 13; i++) 
				{ 
					var op = document.createElement("OPTION"); 
					op.value = i; 
					op.innerHTML = i; 
					selMonth.appendChild(op); 
				} 
				selMonth.selectedIndex = dt.getMonth(); 
			} 

			var date = new Date(selYear.value, selMonth.value, 0); 
			var daysInMonth = date.getDate(); 
			selDay.options.length = 0; 

			for(var i = 1; i <= daysInMonth ; i++) 
			{ 
				var op = document.createElement("OPTION"); 
				op.value = i; 
				op.innerHTML = i; 
				selDay.appendChild(op); 
			} 
			selDay.selectedIndex = dt.getDate() - 1; 
		} 
		
		function endDate()
		{
			/*
			var sy = document.getElementById("tYEAR").value;
			var sm = document.getElementById("tMON").value;
			var sd = document.getElementById("tDAY").value;
			*/
			
			var sy = "<?php echo date("Y") ?>";
			var sm = "<?php echo date("m") ?>";
			var sd = "<?php echo date("d") ?>";
			
			var ed = document.getElementById("admin_allowday_text").value;
			var _date = new Date(parseInt(sy), parseInt(sm)-1, parseInt(sd));
			var d = _date.setDate(_date.getDate() + (parseInt(<?php echo $lefttime ?>) + parseInt(ed)));
			var end_day = new Date(d).pattern("yyyy-MM-dd");	
			document.getElementById("endtime_label").innerHTML = end_day;
		}
		
		function changeradio3()
		{
			
		}
		
		function changeradio2()
		{
			document.getElementById("admin_leftday_text").innerHTML = -1;
			document.getElementById("admin_allowday_text").value = -1;
			document.getElementById("endtime_label").innerHTML = -1;
			document.getElementById("allowday_text").value = -1;
		}

		function changeradio0()
		{
			document.getElementById("admin_leftday_text").innerHTML = 0;
			document.getElementById("admin_allowday_text").value = 0;
			document.getElementById("endtime_label").innerHTML = 0;
		}

		function changeradio1()
		{
			document.getElementById("admin_leftday_text").innerHTML = <?php echo $lefttime ?>;
			document.getElementById("admin_allowday_text").value = 0;
			//document.getElementById("allowday_text").value = 366;
			endDate();
		}
		
		function save()
		{
			var radio_value = GetRadioValue("radio");
			var panel = GetRadioValue("panel");
			
			if(radio_value == "yes")
			{
				//var radio_allowday = GetRadioValue("radio_allowday");
				var radio_allowday = "admin";
				
				var start_day;
				var end_day;
		
				if(radio_allowday == "admin")
				{
			
					<?php
					$d = date('Y-m-d',time());
					$ds=explode("-",$d);
					echo "var sy = " . $ds[0] . ";";
					echo "var sm = " . $ds[1] . ";";
					echo "var sd = " . $ds[2] . ";";
					?>

					var ld = document.getElementById("admin_leftday_text").value;
					var ed = document.getElementById("admin_allowday_text").value;
					var _date = new Date(parseInt(sy), parseInt(sm)-1, parseInt(sd));
					var d = _date.setDate(_date.getDate() + (parseInt(<?php echo $lefttime ?>) + parseInt(ed)));
			
					start_day = sy + "-" + sm + "-" + sd;
					end_day = new Date(d).pattern("yyyy-MM-dd");
				}
				else
				{
					var allowday_text = document.getElementById("allowday_text").value;
					start_day = "proxy";
					end_day = allowday_text;
				}
		
				var playlist;
				var allocation_value = GetRadioValue("allocation");
		
				if(allocation_value == "manually")
				{
					playlist = document.getElementById("playlist_select_id").value;
					if(playlist == null || playlist.length <= 0)
					{
						alert("无有效列表，请添加播放列表！");
						return;
					}
				}
				else if(allocation_value == "auto")
				{
					playlist = "auto";
				}
				else if(allocation_value == "all")
				{
					playlist = "all";
				}
		
				var proxy;
				var proxy_value = GetRadioValue("proxy");
				if(proxy_value == "proxy")
				{
					proxy = document.getElementById("proxy_select_id").value;
					if(proxy == null || proxy.length <= 0)
					{
						alert("<?php echo $lang_array["custom_edit_text29"] ?>");
						return;
					}
				}
				else
				{
					proxy = "admin";
				}
		
				var show_value = GetRadioValue("show");

				var contact = document.getElementById("contact").value;
		
				var member = document.getElementById("member").value;
		
				var remarks = document.getElementById("remarks").value.replace("'","");
				
				var scrollcontent = document.getElementById("scrollcontent").value;
				
				var cmd = "custom_get.php?mac=<?php echo $_GET["mac"] ?>&cpuid=<?php echo $_GET["cpuid"] ?>&date=" + start_day + "&time=" + end_day + "&allow=" + radio_value + "&playlist=" + playlist + "&allocation=" + allocation_value + "&proxy=" + proxy + "&show=" + show_value + "&contact=" + contact + "&member=" + member + "&panel=" + panel + "&remarks=" + remarks;
				//alert(cmd);
				//window.open(cmd, "_self");
				document.authform_edit.action = cmd;
				document.authform_edit.scrollcontent.value = scrollcontent;
				document.authform_edit.submit();	
				
			}
			else if(radio_value == "no")
			{
				//var radio_allowday = GetRadioValue("radio_allowday");
				var radio_allowday = "admin";	
				if(radio_allowday == "admin")
				{
			
					<?php
					$d = date('Y-m-d',time());
					$ds=explode("-",$d);
					echo "var sy = " . $ds[0] . ";";
					echo "var sm = " . $ds[1] . ";";
					echo "var sd = " . $ds[2] . ";";
					?>

					var ed = document.getElementById("admin_allowday_text").value;
					var _date = new Date(parseInt(sy), parseInt(sm)-1, parseInt(sd));
					var d = _date.setDate(_date.getDate() + (parseInt(<?php echo $lefttime ?>) + parseInt(ed)));
			
					start_day = sy + "-" + sm + "-" + sd;
					end_day = new Date(d).pattern("yyyy-MM-dd");
				}
				else
				{
					var allowday_text = document.getElementById("allowday_text").value;
					start_day = "proxy";
					end_day = allowday_text;
				}
		
				var playlist;
				var allocation_value = GetRadioValue("allocation");
				if(allocation_value == "manually")
				{
					playlist = document.getElementById("playlist_select_id").value;
			
				}
				else if(allocation_value == "auto")
				{
					playlist = "auto";
				}
				else if(allocation_value == "all")
				{
					playlist = "all";
				}
		
				var proxy;
				var proxy_value = GetRadioValue("proxy");
				if(proxy_value == "proxy")
				{
					proxy = document.getElementById("proxy_select_id").value;
				}
				else
				{
					proxy = "admin";
				}
		
				var show;
				var show_value = GetRadioValue("show");

				var contact = document.getElementById("contact").value;
		
				var member = document.getElementById("member").value;
				
				var remarks = document.getElementById("remarks").value.replace("'","");

				var scrollcontent = document.getElementById("scrollcontent").value;
				
				var cmd = "custom_get.php?mac=<?php echo $_GET["mac"] ?>&cpuid=<?php echo $_GET["cpuid"] ?>&date=" + start_day + "&time=" + end_day + "&allow=" + radio_value + "&playlist=" + playlist + "&allocation=" + allocation_value + "&proxy=" + proxy + "&show=" + show_value + "&contact=" + contact + "&member=" + member + "&panel=" + panel + "&remarks=" + remarks;
				
				document.authform_edit.action = cmd;
				document.authform_edit.scrollcontent.value = scrollcontent;
				document.authform_edit.submit();	
				
				//window.open("custom_get.php?mac=<?php echo $_GET["mac"] ?>&cpuid=<?php echo $_GET["cpuid"] ?>&date=" + start_day + "&time=" + end_day + "&allow=" + radio_value + "&playlist=" + playlist + "&allocation=" + allocation_value + "&proxy=" + proxy + "&show=" + show_value + "&contact=" + contact + "&member=" + member + "&panel=" + panel + "&remarks=" + remarks, "_self");		
	
			}
			else if(radio_value == "pause")
			{
				var radio_allowday = "admin";
				
				var start_day;
				var end_day;
		
				if(radio_allowday == "admin")
				{
			
					<?php
					$d = date('Y-m-d',time());
					$ds=explode("-",$d);
					echo "var sy = " . $ds[0] . ";";
					echo "var sm = " . $ds[1] . ";";
					echo "var sd = " . $ds[2] . ";";
					?>

					var ld = document.getElementById("admin_leftday_text").value;
					var ed = document.getElementById("admin_allowday_text").value;
					var _date = new Date(parseInt(sy), parseInt(sm)-1, parseInt(sd));
					var d = _date.setDate(_date.getDate() + (parseInt(<?php echo $lefttime ?>) + parseInt(ed)));
			
					start_day = sy + "-" + sm + "-" + sd;
					end_day = new Date(d).pattern("yyyy-MM-dd");
				}
				else
				{
					var allowday_text = document.getElementById("allowday_text").value;
					start_day = "proxy";
					end_day = allowday_text;
				}
		
				var playlist;
				var allocation_value = GetRadioValue("allocation");
		
				if(allocation_value == "manually")
				{
					playlist = document.getElementById("playlist_select_id").value;
					if(playlist == null || playlist.length <= 0)
					{
						alert("无有效列表，请添加播放列表！");
						return;
					}
				}
				else if(allocation_value == "auto")
				{
					playlist = "auto";
				}
				else if(allocation_value == "all")
				{
					playlist = "all";
				}
		
				var proxy;
				var proxy_value = GetRadioValue("proxy");
				if(proxy_value == "proxy")
				{
					proxy = document.getElementById("proxy_select_id").value;
					if(proxy == null || proxy.length <= 0)
					{
						alert("<?php echo $lang_array["custom_edit_text29"] ?>");
						return;
					}
				}
				else
				{
					proxy = "admin";
				}
		
				var show_value = GetRadioValue("show");

				var contact = document.getElementById("contact").value;
		
				var member = document.getElementById("member").value;
		
				var remarks = document.getElementById("remarks").value.replace("'","");
				
				var scrollcontent = document.getElementById("scrollcontent").value;
				
				var cmd = "custom_get.php?mac=<?php echo $_GET["mac"] ?>&cpuid=<?php echo $_GET["cpuid"] ?>&date=" + start_day + "&time=" + end_day + "&allow=" + radio_value + "&playlist=" + playlist + "&allocation=" + allocation_value + "&proxy=" + proxy + "&show=" + show_value + "&contact=" + contact + "&member=" + member + "&panel=" + panel + "&remarks=" + remarks;
				//alert(cmd);
				//window.open(cmd, "_self");
				document.authform_edit.action = cmd;
				document.authform_edit.scrollcontent.value = scrollcontent;
				document.authform_edit.submit();
				
			}
			else
			{
				radio_value = "yes";
		
				//var radio_allowday = GetRadioValue("radio_allowday");
				var radio_allowday = "admin";
				if(radio_allowday == "admin")
				{
					//var sy = document.getElementById("tYEAR").value;
					//var sm = document.getElementById("tMON").value;
					//var sd = document.getElementById("tDAY").value;
					
					var sy = "<?php echo date("Y") ?>";
					var sm = "<?php echo date("m") ?>";
					var sd = "<?php echo date("d") ?>";
					
					start_day = sy + "-" + sm + "-" + sd;
					end_day = -1;
				}
				else
				{
					//var allowday_text = document.getElementById("allowday_text").value;
					start_day = "proxy";
					end_day = -1;
				}
		
				var playlist;
				var allocation_value = GetRadioValue("allocation");
				if(allocation_value == "manually")
				{
					playlist = document.getElementById("playlist_select_id").value;
				}
				else if(allocation_value == "auto")
				{
					playlist = "auto";
				}
				else if(allocation_value == "all")
				{
					playlist = "all";
				}
		
				var proxy;
				var proxy_value = GetRadioValue("proxy");
				if(proxy_value == "proxy")
				{
					proxy = document.getElementById("proxy_select_id").value;
				}
				else
				{
					proxy = "admin";
				}
				//alert(proxy);
				var show;
				var show_value = GetRadioValue("show");

				var contact = document.getElementById("contact").value;
		
				var member = document.getElementById("member").value;
				
				var remarks = document.getElementById("remarks").value;
				
				var scrollcontent = document.getElementById("scrollcontent").value;
				
				var cmd = "custom_get.php?mac=<?php echo $_GET["mac"] ?>&cpuid=<?php echo $_GET["cpuid"] ?>&date=" + start_day + "&time=" + end_day + "&allow=" + radio_value + "&playlist=" + playlist + "&allocation=" + allocation_value + "&proxy=" + proxy + "&show=" + show_value + "&contact=" + contact + "&member=" + member + "&panel=" + panel + "&remarks=" + remarks;
				
				document.authform_edit.action = cmd;
				document.authform_edit.scrollcontent.value = scrollcontent;
				document.authform_edit.submit()	
				
				//window.open(cmd, "_self");			
			}
		}
		
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