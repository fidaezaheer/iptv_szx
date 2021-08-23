<?PHP
if(Extension_Loaded('zlib')) Ob_Start('ob_gzhandler');
Header("Content-type: text/html");
?> 

<!DOCTYPE html>
<?php
include_once "cn_lang.php";
include_once "common.php";
$sql = new DbSql();
$sql->login_proxy();
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
                                <div class="muted pull-left"><?php echo $lang_array["playlist_edit_text6"] ?></div>
                            </div>
                            <div class="block-content collapse in">
                                <div class="span12">
                                   <form class="form-horizontal" name="authform" method="post" action="proxy_live_playlist_post.php" enctype='multipart/form-data'>
                                   <div class="table-toolbar">
                                   </div>
                                    <table cellpadding="0" cellspacing="0" border="0" id="liveid_table_id" class="table table-bordered">
                                        <thead>
          								<tr>
                                        <th width="10%" style="vertical-align:middle; text-align:center;"><?php echo $lang_array["proxy_live_playlist_edit_text4"] ?></th>
            							<th width="10%" style="vertical-align:middle; text-align:center;"><?php echo $lang_array["proxy_live_playlist_edit_text1"] ?></th>
            							<th width="30%" style="vertical-align:middle; text-align:center;"><?php echo $lang_array["proxy_live_playlist_edit_text2"] ?></th>
            							<th width="40%" style="vertical-align:middle; text-align:center;"><?php echo $lang_array["proxy_live_playlist_edit_text3"] ?></th>
          								</tr>
                                        </thead>	
                                        <tbody>
										</tbody>
                                        
<?php
	$playlist_id = $_GET["id"];

	$sql->connect_database_default();
	$mydb = $sql->get_database();
	$sql->create_database($mydb);	
	
	$mytable = "live_preview_table";
	$sql->create_table($mydb, $mytable, "name longtext, image longtext, url longtext, password longtext, type longtext, preview longtext, id longtext, urlid smallint, hd longtext");
	$namess = $sql->fetch_datas_order_asc($mydb, $mytable,"urlid");
	
	$mytable = "playlist_type_table";
	$sql->create_table($mydb, $mytable, "name text, space longtext, id text, playlist longtext");
	$playlistss = $sql->query_data($mydb, $mytable, "id", $playlist_id, "playlist");
	$playlists  = explode("|",$playlistss);

	$mytable = "proxy_playlist_table";
	$sql->create_table($mydb, $mytable, "proxy text, playlist text, liveorder longtext");
	$liveorder = $sql->query_data($mydb, $mytable, "proxy", $_COOKIE["user"], "liveorder");
	$liveorderss  = explode("@",$liveorder);
	 
	$liveidss = "";
	foreach($liveorderss as $liveorders)
	{
		if(strstr($liveorders,$playlist_id) != false)
		{
			$liveorder = explode("#",$liveorders);
			if(count($liveorder) >= 2)
			{
				$liveidss = $liveorder[1];
				break;
			}
			break;
		}
	}
	
	$sql->disconnect_database();		
	
?>                                       
                                    </table>                               

                                    <div class="form-actions">
   										<button onclick="save()" class="btn btn-primary"><?php echo $lang_array["save_text1"] ?></button>
                                		<button type="reset" class="btn" onclick="back_page()"><?php echo $lang_array["back_text1"] ?></button>
                            		</div>
                                    
                                    <input name="liveids" id="liveids" type="hidden" value=""/>  
                                    <input name="playlistid" id="playlistid" type="hidden" value="<?php echo $_GET["id"] ?>"/>   
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
		
		var livesarray = Array();
		var liveidsarray = Array();
<?php
	$index = 0;		
	foreach($playlists as $playlist) 
	{	
		$live_name = "";
		$live_id = "";
		
		foreach($namess as $names)
		{
			if($names[7] == $playlist)
			{
				$live_id = $names[7];
				$live_name = $names[0];
			}
		}
		
		if(strlen($live_id) > 0 && strlen($live_name) > 0)
		{
			echo "var livearray" . $index . " = Array();";
			echo "livearray" . $index . ".push(" . $index . ");";
			echo "livearray" . $index . ".push(\"" . $live_id . "\");";
			echo "livearray" . $index . ".push(\"" . $live_name . "\");";
			echo "livearray" . $index . ".push(0);";
			
			echo "livesarray.push(livearray" . $index . ");";
			
			$index++;
		}
	}
	
	$liveids = explode("|",$liveidss);
	foreach($liveids as $liveid)
	{
		echo "liveidsarray.push(" . $liveid . ");";
	}
?>
		
		
		initload();
		
        $(function() {
            
        });
		
		function initload()
		{
			var livesarray_new = Array();
			
			for(var ii=0; ii<liveidsarray.length; ii++)
			{
				var index = -1;
				for(var kk=0; kk<livesarray.length; kk++)
				{
					
					if(parseInt(livesarray[kk][1]) == liveidsarray[ii])
					{
						index = kk;
						break;	
					}
				}
				
				if(index >= 0)
				{
					if(livesarray[index][3] == 0)
					{
						livesarray_new.push(livesarray[index]);
						livesarray[index][3] = 1;
					}
				}
			}
			
			for(var ii=0; ii<livesarray.length; ii++)
			{
				if(livesarray[ii][3] == 0)
				{
					livesarray_new.push(livesarray[ii]);
				}
			}
			
			
			for(var ii=0; ii<livesarray_new.length; ii++)
			{
				//alert(livesarray[ii][0] + " " + livesarray[ii][1] + " " + livesarray[ii][2]);
				if(parseInt(livesarray_new[ii][1]) < 10000)
					add_one(ii,livesarray_new[ii][1],livesarray_new[ii][2]);
			}
		}
		
		function idNumClass(id,num) {
			this.id = id;
			this.num = num; 
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
		
	
		function add_one(id,liveid,name)
		{
			var length=document.getElementById("liveid_table_id").rows.length;
			var tr=document.createElement("tr");
			var td0=document.createElement("td");
			td0.width = '10%';
			if(length%2 == 1)
				td0.bgColor = '#ffffff';
			else
				td0.bgColor = '#dedede';
			td0.height = '10px';
			td0.style = 'text-align:center;vertical-align:middle;word-wrap:break-word;word-break:break-all;white-space: pre-wrap;';
			var font0=document.createElement("font");
			font0.size = '4px'; 
			font0.appendChild(document.createTextNode(id));
			td0.appendChild(font0);
			tr.appendChild(td0);
	
	 
			var td1=document.createElement("td"); 
			td1.width = '10%';
			if(length%2 == 1)
				td1.bgColor = '#ffffff';
			else
				td1.bgColor = '#dedede';
			td1.height = '10px';
			td1.style = 'text-align:center;vertical-align:middle;word-wrap:break-word;word-break:break-all;white-space: pre-wrap;';
			var font1=document.createElement("font");
			font1.size = '4px'; 
			font1.appendChild(document.createTextNode(liveid));
			td1.appendChild(font1);
			tr.appendChild(td1);
			
			var td2=document.createElement("td"); 
			td2.width = '50%';
			if(length%2 == 1)
				td2.bgColor = '#ffffff';
			else
				td2.bgColor = '#dedede';
			td2.height = '10px';
			td2.style = 'text-align:center;vertical-align:middle;word-wrap:break-word;word-break:break-all;white-space: pre-wrap;';
			var font2=document.createElement("font");
			font2.size = '4px'; 
			font2.appendChild(document.createTextNode(name));
			td2.appendChild(font2);
			tr.appendChild(td2);
			
			
			var td3=document.createElement("td"); 
			td3.width = '20%';
			if(length%2 == 1)
				td3.bgColor = '#ffffff';
			else
				td3.bgColor = '#dedede';
			td3.height = '10px';
			td3.style = 'text-align:center;vertical-align:middle;word-wrap:break-word;word-break:break-all;white-space: pre-wrap;';
			
			
			
			var table3=document.createElement("table"); 
			table3.width = '100%';
			table3.border = "0px";
			
			var tr3=document.createElement("tr"); 
			tr3.width = '100%';
			tr3.height = '10px';
			tr3.border = "0px";
			tr3.style = 'text-align:center;vertical-align:middle;word-wrap:break-word;word-break:break-all;white-space: pre-wrap;';
			
			var td31=document.createElement("td"); 
			td31.width = '50%';
			td31.height = '10px';
			td31.border = "0px";
			td31.style = 'text-align:center;vertical-align:middle;word-wrap:break-word;word-break:break-all;white-space: pre-wrap;';
			var font31=document.createElement("font");
			font31.size = '4px'; 
			var a31=document.createElement("a");
			a31.href = "#";
			a31.onclick = function updateRow()
			{
				var liveid_table = document.getElementById("liveid_table_id");
				var liveid_length = liveid_table.rows.length;
				var index = this.parentNode.parentNode.parentNode.parentNode.parentNode.parentNode.rowIndex;
				if(index -1 < 1)
					return;
				
				var liveid = liveid_table.rows[index-1].cells[1].childNodes[0].innerHTML;
				var livename = liveid_table.rows[index-1].cells[2].childNodes[0].innerHTML;
				
				liveid_table.rows[index-1].cells[1].childNodes[0].innerHTML = liveid_table.rows[index].cells[1].childNodes[0].innerHTML;
				liveid_table.rows[index-1].cells[2].childNodes[0].innerHTML = liveid_table.rows[index].cells[2].childNodes[0].innerHTML;
				
				liveid_table.rows[index].cells[1].childNodes[0].innerHTML = liveid;
				liveid_table.rows[index].cells[2].childNodes[0].innerHTML = livename;
				
			}
			a31.appendChild(document.createTextNode("<?php echo $lang_array["live_preview_add_text22"] ?>"));
			font31.appendChild(a31);
			td31.appendChild(font31);
			tr3.appendChild(td31);
			
			var td32=document.createElement("td"); 
			td32.width = '50%';
			td32.height = '10px';
			td32.style = 'text-align:center;vertical-align:middle;word-wrap:break-word;word-break:break-all;white-space: pre-wrap;';
			
			var font32=document.createElement("font");
			font32.size = '4px'; 
			var a32=document.createElement("a");
			a32.href = "#";
			a32.onclick = function updateRow()
			{
				var liveid_table = document.getElementById("liveid_table_id");
				var liveid_length = liveid_table.rows.length;
				
				var insert_num = window.prompt("<?php echo $lang_array["proxy_live_playlist_edit_text6"] ?>" + ":");
				insert_num = parseInt(insert_num) + 1;
				if(insert_num > liveid_length)
				{
					alert("<?php echo $lang_array["proxy_live_playlist_edit_text7"] ?>");
					return;	
				}
				
				var index = this.parentNode.parentNode.parentNode.parentNode.parentNode.parentNode.rowIndex;
				
				if(index > insert_num)
				{
					var id = liveid_table.rows[index].cells[1].childNodes[0].innerHTML;
					var name = liveid_table.rows[index].cells[2].childNodes[0].innerHTML;
					for(var ii=index; ii>insert_num; ii--)
					{
						liveid_table.rows[ii].cells[1].childNodes[0].innerHTML = liveid_table.rows[ii-1].cells[1].childNodes[0].innerHTML;
						liveid_table.rows[ii].cells[2].childNodes[0].innerHTML = liveid_table.rows[ii-1].cells[2].childNodes[0].innerHTML;
					}
					
					liveid_table.rows[insert_num].cells[1].childNodes[0].innerHTML = id;
					liveid_table.rows[insert_num].cells[2].childNodes[0].innerHTML = name;
				}
				else if(index < insert_num)
				{
					var id = liveid_table.rows[index].cells[1].childNodes[0].innerHTML;
					var name = liveid_table.rows[index].cells[2].childNodes[0].innerHTML;
					for(var ii=index; ii<insert_num; ii++)
					{
						liveid_table.rows[ii].cells[1].childNodes[0].innerHTML = liveid_table.rows[ii+1].cells[1].childNodes[0].innerHTML;
						liveid_table.rows[ii].cells[2].childNodes[0].innerHTML = liveid_table.rows[ii+1].cells[2].childNodes[0].innerHTML;
					}
					
					liveid_table.rows[insert_num].cells[1].childNodes[0].innerHTML = id;
					liveid_table.rows[insert_num].cells[2].childNodes[0].innerHTML = name;
					
				}
			}
			a32.appendChild(document.createTextNode("<?php echo $lang_array["proxy_live_playlist_edit_text5"] ?>"));
			font32.appendChild(a32);
			td32.appendChild(font32);
			tr3.appendChild(td32);
			
			table3.appendChild(tr3);
			
			td3.appendChild(table3);
			
			tr.appendChild(td3);
			
			document.getElementById("liveid_table_id").appendChild(tr); 
			
		}
		
		function get_live_id_value()
		{
			var value = "";
			var value_array = new Array();
			var type_checkboxs = document.getElementsByName("type_checkbox");
			for(var i = 0; i < type_checkboxs.length; i++)
			{
				if(type_checkboxs[i].type == "checkbox" && type_checkboxs[i].checked)
				{
					value_array.push(type_checkboxs[i].value);
				}
			}
			for(var ii = 0; ii < value_array.length; ii++)
			{
				value = value + pad(value_array[ii],3);
				if(ii < value_array.length - 1)
					value = value + "|";
			}
			return value;
		}
		
		function get_live_id_value()
		{
			var liveid_table = document.getElementById("liveid_table_id");
			var liveid_length = liveid_table.rows.length;
			var allliveid = "";
			for(var ii=1; ii<liveid_length; ii++)
			{
				var liveid = liveid_table.rows[ii].cells[1].childNodes[0].innerHTML;
				allliveid = allliveid + liveid;
				if(ii < liveid_length -1)
					allliveid = allliveid + "|";
			}
			
			return allliveid;
		}
		
		function save()
		{
			var ids_value = get_live_id_value();
			document.authform.liveids.value = ids_value;
			document.authform.submit();
			
		}
		
		function back_page()
		{
			window.location.href = "proxy_live_playlist_list.php";	
			
		}
        </script>
    </body>

</html>

<?PHP
if(Extension_Loaded('zlib')) Ob_End_Flush();
?>