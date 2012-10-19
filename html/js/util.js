function sbjMenu(key,ico){
	
	if(document.all[key].style.display=="none"){
		document.all[key].style.display="block";
		document.all[ico].src="/images/ico_min.gif";
		}else{
		document.all[key].style.display="none";
		document.all[ico].src="/images/ico_max.gif";
	   }
		
	}


function doselect(form,el,val)
{
	var t = document.forms[form].elements[el].options;
	for (var i=0;i<t.length ;i++ )
	{
		if (t[i].value==val)
		{
			t[i].selected=true;
			break;
		}
	}
}
function docheck(form,el,val)
{
	var t = document.forms[form].elements[el];
	for (var i=0;i<t.length ;i++ )
	{
		if (t[i].value==val)
		{
			t[i].checked=true;
			break;
		}
	}
}
function checkAll(form,el,val)
{
	var t = document.forms[form].elements[el];

		var arr = val.split(",");
		for (var i=0;i<t.length ;i++ )
		{
			
			for (var j=0;j<arr.length ;j++ )
			{
				if (t[i].value==arr[j])
				{
					t[i].checked=true;
					break;
				}
			}
		}
	
}

function selectAll(form,el)
{
	var element = document.forms[form].elements[el];

	for (var i=0;i<element.length;i++)
	{
		if (element[i].checked==true)
		{
			element[i].checked=false;
		}
		else
		{
			element[i].checked=true;
		}
	}
}




var copytoclip=1;
function copyToClipboard(theField,isalert) {		
	var tempval=document.getElementById(theField);		
	if (navigator.appVersion.match(/\bMSIE\b/)){
		tempval.select();		
		if (copytoclip==1){
			therange=tempval.createTextRange();
			therange.execCommand("Copy");
			if(isalert!=false)alert("复制成功。现在您可以粘贴（Ctrl+v）到Blog 或BBS中了。");
		}
		return;
	}else{
		alert("您使用的浏览器不支持此复制功能，请使用Ctrl+C或鼠标右键。");
		tempval.select();		
	}
}

function smileToCode(s)
{
var re=/(?:<(?:[^ ]*) src=\"[http:\/\/www\.zcool\.com\.cn]*\/images\/smiles\/)([^\.]*)(?:.gif\">)/g; 
return s.replace(re,"/：$1*");
}


//添加表情图片


var lang = new Array();
var userAgent = navigator.userAgent.toLowerCase();
var is_opera = userAgent.indexOf('opera') != -1 && opera.version();
var is_moz = (navigator.product == 'Gecko') && userAgent.substr(userAgent.indexOf('firefox') + 8, 3);
var is_ie = (userAgent.indexOf('msie') != -1 && !is_opera) && userAgent.substr(userAgent.indexOf('msie') + 5, 3);



function isUndefined(variable) {
	return typeof variable == 'undefined' ? true : false;
}


function strlen(str) {
	return (is_ie && str.indexOf('\n') != -1) ? str.replace(/\r?\n/g, '_').length : str.length;
}

function updatestring(str1, str2, clear) {
	str2 = '_' + str2 + '_';
	return clear ? str1.replace(str2, '') : (str1.indexOf(str2) == -1 ? str1 + str2 : str1);
}


var postSubmited = false;
var smdiv = new Array();
var codecount = '-1';
var codehtml = new Array();

function AddText(textEl,txt) {
	selection = document.selection;
	checkFocus(textEl);
	if(!isUndefined(textEl.selectionStart)) {
		var opn = textEl.selectionStart;
		textEl.value = textEl.value.substr(0, textEl.selectionStart) + txt + textEl.value.substr(textEl.selectionEnd);
	} else if(selection && selection.createRange) {
		var sel = selection.createRange();
		sel.text = txt;
		sel.moveStart('character', -strlen(txt));
	} else {
		textEl.value += txt;
	}
}

function checkFocus(textEl) {
	if(!textEl.hasfocus) {
		textEl.focus();
	}
}

function ctlent(event) {
	
}



function insertSmile(textEl,smile) {
	checkFocus(textEl);

	smile = "/："+ smile +"*";

	AddText(textEl,smile);
}


//=点击展开关闭效果=
function openShutManager(oSourceObj,oTargetObj,shutAble,oOpenTip,oShutTip){
var sourceObj = typeof oSourceObj == "string" ? document.getElementById(oSourceObj) : oSourceObj;
var targetObj = typeof oTargetObj == "string" ? document.getElementById(oTargetObj) : oTargetObj;
var openTip = oOpenTip || "";
var shutTip = oShutTip || "";
if(targetObj.style.display!="none"){
   if(shutAble) return;
   targetObj.style.display="none";
   if(openTip  &&  shutTip){
    sourceObj.innerHTML = shutTip; 
   }
} else {
   targetObj.style.display="block";
   if(openTip  &&  shutTip){
    sourceObj.innerHTML = openTip; 
   }
}
}

function dologin()
{
	var name = $("#t_loginname").parent().val();
	var pass = $("#t_password").parent().val();
	var auto = $("#t_auto").parent().val();
	var url =  $("#t_url").parent().val();

	if (name == "" || pass == "")
	{
		if($('#facebox .iserror').html()==null)
			$('#facebox .f_body').children('.f_content').append('<div class=iserror align=center><font color=red>用户名和密码都要填写</font></div>');
		else
			$('#facebox .iserror').html("<font color=red>用户名和密码都要填写</font>");
		return false;
	}

	if($('#facebox .iserror').html()==null)
		$('#facebox .f_body').children('.f_content').append('<div class=iserror align=center><font color=red>正在登录...</font></div>');
	else
		$('#facebox .iserror').html("<font color=red>正在登录...</font>");


	$.post("/login-status.do", { loginname: name, password: pass,auto:auto ,url:url},
	  function(data){
		if (data == "0"){
			$('#facebox .iserror').html("<font color=red>用户名或密码不正确</font>");
		 }
		else{
			reload_status();
			$('#facebox').fadeOut("slow"); 
		}
	  }); 
		return false;
}
function current(id){
	/**
	if (id=="i_work"){
		doselect("searchform","s_type",1);
	}
	else if (id=="i_zcooler"){
		doselect("searchform","s_type",3);
	}
	else if (id=="i_show"){
		doselect("searchform","s_type",5);
	}	
	else if (id=="i_folder"){
		id="i_gfx";
		doselect("searchform","s_type",4);
	}	
	else
		doselect("searchform","s_type",2);

		**/
doselect("searchform","s_type",2);
	$("#"+id).addClass("current");

}

function dosearch(form)
{
	var _form = document.forms[form];
	var type = _form.elements["s_type"].value;
	if ($.trim(_form.elements["k"].value)=="")
	{
		alert("没有输入关键字");
		_form.elements["k"].focus();
		return false;
	}

	_form.action=getSearchUrl(type);
	_form.submit();

	return false;
}

function getSearchUrl(type)
{
	if (type==1)
		return "/search_art.do";
	else if (type==2)
		 return "/search_gfx.do";
	else if (type==3)
		return "/search_designer.do";
	else if (type==5)
		return "/search_show.do";
}

function ChangeCate(formName,sourceName, targetName)
{
	var form = document.forms[formName];
	var sourceElement = form.elements[sourceName];
	var targetElement = form.elements[targetName];
	
	var selValue = sourceElement.value;
	
	clean(targetElement);
	
	if (selValue=="0")
	{
		return;
	}

	var j = 0;
	for (i =0;i<catelist.length;i++){
		if (catelist[i][2]==selValue){
			targetElement.options[j]= new Option(catelist[i][0],catelist[i][1]);
			j++;
		}
	}
	targetElement.options[0].selected = true;

}
function clean(targetElement)
{
	len = targetElement.options.length;
	for (i = 0;i<len;i++){
		targetElement.options[0] = null;
	}
	targetElement.options[0]= new Option("","");
}

