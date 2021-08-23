<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>无标题文档</title>
</head>

<script type="text/javascript">
	
function iplayer()
{
	var Agent = "okhttp/3.10.0";
	var FwUrl = window.Authentication.CTCGetConfig("Server");
	var UID = window.Authentication.CTCGetConfig("MyD");
	var UPW = window.Authentication.CTCGetConfig("MyW");
	var UMC = window.Authentication.CTCGetConfig("MyM");
	var token = window.Authentication.CTCGetConfig("token");
	var Appid = window.Authentication.CTCGetConfig("Appid");
	var Post = window.Authentication.CTCGetConfig("Post");
	var SServer = window.Authentication.CTCGetConfig("SServer");
	var starttime = Date.parse(new Date())/1000;
	var UTime = starttime.toString();
	SServer = SServer.replace("UMC", UMC);
	SServer = SServer.replace("UTime", UTime);
	var Sign = window.Authentication.CTCSendServerCmd(SServer+"1","",5000);
	Post = window.atob(Post);
	Post = Post.replace("UID",UID);
	Post = Post.replace("UPW",UPW);
	Post = Post.replace("UMC",UMC);
	Post = Post.replace("Token",token);
	Post = Post.replace("Time",UTime);
	Post = Post.replace("Sign",Sign);
	Post = Post.replace(/[\r\n]/g, "");
	var FwData = window.Authentication.CTCPostServerCmdAndCookies(FwUrl,Post,Agent,"",5000);
	if(FwData.indexOf("token") != -1) 
	{
	   	var arr = new Array();
		var arr = FwData.split("@#@");
		var jxdata = arr[1]
	    var obj = JSON.parse(jxdata);
	    var tokens = obj.data.client.token;
		window.Authentication.CTCSetConfig("token",tokens);
	    window.Authentication.CTCSetConfig("usertoken",tokens);
	}
}
	iplayer();


	
</script>

<body>
</body>
</html>