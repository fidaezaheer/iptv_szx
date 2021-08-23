<!DOCTYPE html>
<?php
	include_once "cn_lang.php";
	include_once "common.php";
	$sql = new DbSql();
	$sql->login();
	
	$size = 20;
	
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
		remarks text, startime date, model text, remotelogin int, limitmodel text, 
		modelerror int, limittimes int, limitarea text, ghost int, password text, 
		evernumber longtext, prekey text, cpuinfo text");
		
	$numrows = $sql->count_fetch_datas($mydb, $mytable);
	$pagetotal = intval($numrows/$size);
	if($numrows%$size > 0)
		$pagetotal = intval($pagetotal) + 1;
		
	//echo $_POST["selbox"];	
	
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
    
    <body>
        <div class="container-fluid">
            <div class="row-fluid">
              <div class="span13" id="content">
                <div class="row-fluid">
                   <!-- block -->
						<div class="block">
                            <div class="navbar navbar-inner block-header">
                                <div class="muted pull-left"><?php echo $lang_array["custom_batch_list_text1"] ?></div>
                            </div>
                            
                            <div class="block-content collapse in">
                                <div class="span12">
                                	<form class="form-horizontal" method="post" action="user_post.php" enctype='multipart/form-data'>
                                    	<fieldset><br/>
                                   			<button type="button" class="btn btn-primary" onClick="add_leftday()"><?php echo $lang_array["custom_batch_list_text2"] ?></button>
                                            <button type="button" class="btn btn-primary" onClick="delect_invalid_user()"><?php echo $lang_array["custom_batch_list_text3"] ?></button>
                                            <button type="button" class="btn btn-primary" onClick="delect_timeout_user()"><?php echo $lang_array["custom_batch_list_text4"] ?></button>
                                            <button type="button" class="btn btn-primary" onClick="batch_playlist()"><?php echo $lang_array["custom_batch_list_text5"] ?></button>
                                            <button type="button" class="btn btn-primary" onClick="batch_scrolltext()"><?php echo $lang_array["custom_batch_list_text16"] ?></button>
                                            <button type="button" class="btn btn-primary" onClick="delect_test_user()"><?php echo $lang_array["custom_batch_list_text17"] ?></button>
                                            <button type="button" class="btn btn-primary" onClick="batch_authorization()"><?php echo $lang_array["custom_batch_list_text22"] ?></button>
                                            <br/>
                                            <br/>
                                            <label id="tip" style="color:#F00"><?php echo $lang_array["custom_batch_list_text19"] ?></label>
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

        <script src="vendors/jquery-1.9.1.js"></script>
        <script src="bootstrap/js/bootstrap.min.js"></script>
        <script src="vendors/datatables/js/jquery.dataTables.min.js"></script>


        <script src="assets/scripts.js"></script>
        <script src="assets/DT_bootstrap.js"></script>
        <script>
        $(function() {
            
        });
		
		var loadtime = 0;
		function loadXMLDoc(offset)
		{
			var xmlhttp;
			if (window.XMLHttpRequest)
			{
				//  IE7+, Firefox, Chrome, Opera, Safari 浏览器执行代码
				xmlhttp=new XMLHttpRequest();
			}
			else
			{
				// IE6, IE5 浏览器执行代码
				xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
			}
			xmlhttp.onreadystatechange=function()
			{
				//alert(xmlhttp.status);
				if(xmlhttp.readyState==4 && xmlhttp.status==200)
				{
					if(xmlhttp.responseText.indexOf("continue"))
						document.getElementById("tip").innerHTML = xmlhttp.responseText + ":" + offset.toString() + "/" + <?php echo $pagetotal ?> + " <?php echo $lang_array["custom_batch_list_text20"] ?>";
					else if(xmlhttp.responseText.indexOf("finish"))
						document.getElementById("tip").innerHTML = xmlhttp.responseText + " <?php echo $lang_array["custom_batch_list_text21"] ?>";
				}
			}
			//alert("custom_timeout_del3.php?offset=" + offset + "&size=<?php echo $size ?>");
			xmlhttp.open("GET","custom_timeout_del3.php?offset=" + offset + "&size=<?php echo $size ?>",true);
			xmlhttp.send();
		}

		function back_page()
		{
			window.location.href = "user_list.php";
				
		}
		
		function add_leftday()
		{
			var r=confirm("<?php echo $lang_array["custom_batch_list_text6"] ?>" + "？");
			if(r==true)
  			{	
				var day= window.prompt("<?php echo $lang_array["custom_batch_list_text7"] ?>");
				var rr=confirm("<?php echo $lang_array["custom_batch_list_text8"] ?>" + "：" + day + " 天 " + "," + "<?php echo $lang_array["custom_batch_list_text9"] ?>" +"?");
				if(rr == true)
				{
					var cmd = "custom_add_leftday.php?" + "addday=" + day;
					window.location.href = cmd;
				}
			}	
		}
		
		function delect_timeout_user()
		{
			if(confirm("<?php echo $lang_array["custom_batch_list_text10"] ?>" + "?") == true)
  			{
				if(confirm("<?php echo $lang_array["custom_batch_list_text11"] ?>" + "?") == true)
				{
					var total = <?php echo $pagetotal ?>;
					var repeat = <?php echo $pagetotal ?>;  // 限制执行次数为5次
					var timer = setInterval(function() {    
    					if (repeat == 0) {
        					clearInterval(timer);
    					} else {
        					repeat--;
        					loadXMLDoc(repeat);
    					}
					}, 2000);

					
					//window.location.href = "custom_timeout_del2.php";
				}
			}
		}

		function batch_playlist()
		{
			if(confirm("<?php echo $lang_array["custom_batch_list_text12"] ?>" + "?") == true)
  			{
				window.location.href = "batch_playlist_list.php";
			}	
		}


		function delect_invalid_user()
		{
			if(confirm("<?php echo $lang_array["custom_batch_list_text13"] ?>" + "?") == true)
  			{
				if(confirm("<?php echo $lang_array["custom_batch_list_text14"] ?>" + "?") == true)
					window.location.href = "custom_invalid_del.php";
			}
		}
		
		function delect_test_user()
		{
			if(confirm("<?php echo $lang_array["custom_batch_list_text17"] ?>" + "?") == true)
  			{
				if(confirm("<?php echo $lang_array["custom_batch_list_text18"] ?>" + "?") == true)
					window.location.href = "custom_test_del.php";
			}			
		}
		
		function batch_scrolltext()
		{
			if(confirm("<?php echo $lang_array["custom_batch_list_text15"] ?>" + "?") == true)
  			{
				window.location.href = "batch_scrolltext_del.php";
			}			
		}
		
		function openWindowWithPost(url,name,keys,values)  
    	{  
        	var newWindow = window.open(url, name , 'height=450, width=1050, top=300, left=300, toolbar=no, menubar=no, scrollbars=no, resizable=no,location=n o, status=no');  
        	if (!newWindow)  
            	return false;  
              
        	var html = "";  
        	html += "<html><head></head><body><form id='formid' method='post' action='" + url + "'>";  
        	if (keys && values)  
        	{  
           		html += "<input type='hidden' name='" + keys + "' value='" + values + "'/>";  
        	}  
          
        	html += "</form><script type='text/javascript'>document.getElementById('formid').submit();";  
        	html += "<\/script></body></html>".toString().replace(/^.+?\*|\\(?=\/)|\*.+?$/gi, "");   
        	newWindow.document.write(html);  
          
        	return newWindow;  
    	} 
		
		function batch_authorization()
		{
			var selbox = "<?php echo $_POST["selbox"] ?>";
			if(selbox.length < 17)
			{
				alert("<?php echo $lang_array["custom_batch_list_text23"] ?>");
			}
			else
			{
				//window.open("batch_authorization_edit.php", 'newwindow', 'height=780, width=750, top=300, left=300, toolbar=no, menubar=no, scrollbars=no, resizable=no,location=n o, status=no');	
				openWindowWithPost("custom_batch_authorization_edit.php","newwindow2","selbox","<?php echo $_POST["selbox"] ?>");
			}
		}
        </script>
    </body>

</html>