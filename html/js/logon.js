document.writeln("<span id=\"status_logon\">");
if(userCookieLoginname == ""){
	document.writeln("Hi，"+getHelloW()+"<a href=\"#denglu\" relbox=\"facebox\">登陆</a>|<a href=/register.do>注册</a>|<a href=\"/getpassword.do\">忘记密码</a>");
}else{
	document.write("<a href=\"/u/"+userCookieUID+"/\" target=\"_blank\" class=\"b\">"+userCookieLoginname+"</a>|<a href=\"/LogOut.do?forward="+document.URL+"\">退出</a>|<a href=\"/my/\">我的站酷</a>|<a href=\"/u/"+userCookieUID+"/\">我的主页</a>");
}
document.write("</span>");
document.writeln("<span id=\"msg_num\"></span>");


if(userCookieLoginname != ""){
	get_message_count();
}
else
	$("#msg_num").html("");

