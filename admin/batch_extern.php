<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" 
"http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Gemini-Iptv</title>
<link href="styles/style.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="scripts/jquery.min.js"></script>
<script type="text/javascript">
$(function(){
	$('tbody tr:odd').addClass("trLight");
	$(".select-all").click(function(){
		if($(this).attr("checked")){
			$(".checkBox input[type=checkbox]").each(function(){
				$(this).attr("checked", true);  
			});
			}else{
				$(".checkBox input[type=checkbox]").each(function(){
				$(this).attr("checked", false);  
			});
		}
	});
})

function add()
{
	var high_url = document.getElementById("high_url").value;
	
	add_selected_one(high_url);	
}

function add_selected_one(url)
{
	var length=document.getElementById("table_id").rows.length;
	var tr=document.createElement("tr");
	var td0=document.createElement("td"); 
	td0.width = '15%';
	td0.bgColor = '#ffffd9';
	td0.height = '17px';
	var font0=document.createElement("font");
	font0.size = '2px'; 
	font0.appendChild(document.createTextNode(url.replace("&amp;","&")));
	td0.appendChild(font0);
	tr.appendChild(td0);
	
	
	var td2=document.createElement("td"); 
	td2.width = '8%';
	td2.bgColor = '#ffffd9';
	td2.height = '17px';
	var font2=document.createElement("font");
	font2.size = '2px'; 
	var a2=document.createElement("a");
	a2.href = "#";
	td2.onclick = function deleteRow()
	{
		var index=this.parentNode.rowIndex;
 		var table = document.getElementById("table_id");
		table.deleteRow(index);
	}
	a2.appendChild(document.createTextNode("删除"));
	font2.appendChild(a2);
	td2.appendChild(font2);
	
	tr.appendChild(td2);

	document.getElementById("newbody").appendChild(tr); 		
}

function save_url()
{
	var table = document.getElementById("table_id");
   	var rows = table.rows.length;
	var ii = 0;
	var option = "";
	for(ii = 1; ii<rows; ii++)
	{
		var url = document.getElementById("table_id").rows[ii].cells[0].childNodes[0].innerHTML;
		option = option + url.replace("&amp;","&");
		if(ii<rows-1)
			option = option + "|";		
	}	
	document.authform2.url.value = option;
	document.authform2.submit();
}

function save()
{
	
}
</script>

<?php
	include 'common.php';
	$sql = new DbSql();
	$sql->login();
?>

<body >
<div id="contentWrap">
  <div id="widget table-widget">
    <div class="pageTitle">寰宇使用-批量修改</div>
    <div class="pageColumn">
    <form id="form0" name="authform0" method="post" action="batch_extern_post.php" enctype='multipart/form-data' >
    请输入指定的P2P服务器域名或IP:<input name="url" id="url" type="text" size="64" /> 如：p2pd.huanyuiptv.com:9906,前面不需要p2p://<br/><br/>
    请输入代替的IP或域名:<input name="ip" id="ip" type="text" size="64" /> 如：www.huanyuiptv.com:9906,前面不需要p2p://<br/>
    请输入代替的LINK:<input name="ps" id="ps" type="text" size="64" /><br/>
    请输入代替的USERID:<input name="userid" id="userid" type="text" size="64" /><br/><br/>
    <input name=""  type="submit" value="确定" />
    </form>
    <br/>
    <br/>
    
    <form id="form1" name="authform1" method="post" action="batch_extern2_post.php" enctype='multipart/form-data' >
    批量批除客户列表记录<br/>
    输入MAC:<input type="text" id="mac" name="mac"/>
    <input name=""  type="submit" value="确定" />
    </form>
    <br/>
    
    <form id="form2" name="authform2" method="post" action="batch_extern3_post.php" enctype='multipart/form-data' >
    添加认证地址：<input name="high_url" type="text" id="high_url" size="96"  /><input name="" type="button" value="添加" onclick="add()"/><br/>
	<table width="85%" id="table_id">
	<thead border="2">
		<tr >
		<th width="80%">地址</th>
		<th width="20%">操作</th>
		</tr>
	</thead>
    
    <tbody id="newbody"> 
	</tbody>
	
    </table>
    <input name=""  type="button" value="确定" onclick="save_url()" />
    
    <input name="url" id="url" type="hidden" value=""/>
    </form>
    <br/>
    </div>
  </div>
</div>    
</body>

<script>
<?php
	$mytable = "head_authentication_table";
	$sql->connect_database_default();
	$mydb = $sql->get_database();
	$sql->create_database($mydb);
	$sql->create_table($mydb, $mytable, "id int,url text");
	$names = $sql->fetch_datas($mydb, $mytable);
	foreach($names as $name) 
	{
		if(strlen($name[1]) > 7)
			echo "add_selected_one('" . $name[1] . "');";
	}
	$sql->disconnect_database();
?>

</script>
</html>