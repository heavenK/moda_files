function SetCookie(name,value){
	var exp=new Date();
	document.cookie=name+'='+escape(CodeCookie(value))+' ; maxage=-1 ; path=/ ; domain=zcool.com.cn';
}
//是否已经登陆
function isLogon(){
	var islogon = false;
	var allcookie = document.cookie.split('; ');
    for(var i=0;i<allcookie.length;i++){
        var cookiename = allcookie[i].split('=')[0];
        if(cookiename=='zcool_logon'){
            islogon = true;
        }
    }
	return islogon;
}
//是否自动登陆
function checkAutoLogin(){
    var allcookie = document.cookie.split('; ');
    var isautologin = false;
    for(var i=0;i<allcookie.length;i++){
        var cookiename = allcookie[i].split('=')[0];
        if(cookiename=='zcool_auto_login'){
            isautologin = true;
        }
    }
	return isautologin;
}

//下载记录
function getDownTotal()
{
	var allcookie = document.cookie.split('; ');
    for(var i=0;i<allcookie.length;i++){
		var arr = allcookie[i].split('=');
        var cookiename = arr[0];        
       if(cookiename == 'dt'){
          return decodeURI(arr[1]);
        }
    }
	return "0";
}
//得到基本信息。
function getUserInfo(){	
	
	var varUserStatus='';
	var allcookie = document.cookie.split('; ');
    for(var i=0;i<allcookie.length;i++){
		var arr = allcookie[i].split('=');
        var cookiename = arr[0];        
        if(cookiename == 'zcool_logon'){
          var tmpString = decodeURI(arr[1]);
		  varUserStatus = tmpString;
        }
    }
	return varUserStatus;
}
var status = '';
var userCookieUID="";			//用户uid
var userCookieLoginname="";			//用户loginname
var userCookieRealname="";		//用户昵称
var userCookieUserMail="";		//用户mail
var userCookieUserFace="";		//用户face

if(isLogon()){
	status=getUserInfo();
	status.replace('?','O').replace('?','O').replace('?','O');
	try{
	    var stat = status.split("|");
	    userCookieUID = stat[0];
	    userCookieLoginname = decodeURI(stat[1]);
	    userCookieRealname = decodeURI(stat[2]);
	    userCookieUserMail = stat[3];
		userCookieUserFace = unescape(stat[4]);
	}catch(e){
	}
}


function getHelloW(){
	var hr = (new Date()).getHours( )
	if (( hr >= 0 ) && (hr <= 4 ))
		return "深夜好！"
	if (( hr >= 4 ) && (hr < 7))
		return "清晨好！"
	if (( hr >= 7 ) && (hr < 12))
		return "早上好！"
	if (( hr >= 12) && (hr <= 13))
		return "中午好！"
	if (( hr >= 13) && (hr <= 17))
		return "下午好！ "
	if (( hr >= 17) && (hr <= 19))
		return "傍晚好！"
	if ((hr >= 19) && (hr <= 23))
		return "晚上好！"
}
function dw(s){
	document.write(s);
}
if(!isLogon() && checkAutoLogin()){
	try{
		window.location='http://www.zcool.com.cn/AutoLogin.do?forward='+document.URL;
	}catch(e){
		window.location.href='http://www.zcool.com.cn/AutoLogin.do?forward='+document.URL;
	}
}

function reload_status()
{
	if(isLogon()){
		status=getUserInfo();
		status.replace('?','O').replace('?','O').replace('?','O');
		try{
			var stat = status.split("|");
			userCookieUID = stat[0];
			userCookieLoginname = decodeURI(stat[1]);
			userCookieRealname = decodeURI(stat[2]);
			userCookieUserMail = stat[3];
			userCookieUserFace = unescape(stat[4]);
		}catch(e){
		}
	}

	var html = "<a href=\"/u/"+userCookieUID+"\" target=\"_blank\" class=\"b\">"+userCookieLoginname+"</a>|<a href=\"/LogOut?backUrl="+document.URL+"\">退出</a>|<a href=\"/my/\">我的站酷</a>|<a href=\"/u/"+userCookieUID+"/\" target=\"_blank\">我的主页</a>";
	$("#status_logon").html(html);

	get_message_count();

}

function get_message_count()
{
	$.get("/my/status_message.jsp",{t:Math.random()},function(data)
	{
		$("#msg_num").html($.trim(data));
	});
}