/**
* 
* @author Charles Zhou<fidy.watcher@gmail.com>
*
*/



function showAjaxLoadingGif(target)
{
	if(target==null || target==""){
		return false;
	}
	$(target).innerHTML = '<img src="/images/ajax-loader.gif" /> Loading......';
}


function onlyNum(e) {
	var key;
	key = window.event?e.keyCode:e.which;
	if((key>=48 && key<=57) || key==8 || key==37 || key==39 || key==46 || (key>=96 && key<=105)){ //only number
		return true;
	}else {
		return false;
	}

}

function displayError(msg, target){
	$('#'+target+"_msg").html(msg);
}

function testUsername(input)
{
	var theInput = $(input);
	if(theInput.attr("value")=="" || theInput.attr("value")==null){
		displayError("�û�������Ϊ3-12λ","username");
		theInput.css("border-color","red"); 
		return false;
	}
	if(theInput.attr("value").length<3 || theInput.attr("value").length>12){
		displayError("�û�������Ϊ3-12λ","username");
		theInput.css("border-color","red"); 
		return false;
	}else if(!isTrueName(theInput.attr("value"))){
		displayError("�û������������ַ�","username");
		theInput.css("border-color","red"); 
		return false;
	}else{
		$.get("/Index/exist/username/"+theInput.attr("value"),function(data){
			var result = eval(data);
			if(result==true){
				displayError("�û����Ѿ�����","username");
				theInput.css("border-color","red"); 
				return false;
			}
		});
	}
	displayError("OK","username");
	theInput.css("border-color","#66cc66"); 
	return true;
}

function reloadAuthImag(){
	$("#authImage").html('<img src="/index/authcode/'+Math.random()+'" onclick="reloadAuthImag();" />');
}

function testNickname(input)
{
	var theInput = $(input);
	if(theInput.attr("value")=="" || theInput.attr("value")==null){
		displayError("�ǳƳ��ȴ���","nickname");
		theInput.css("border-color","red"); 
		return false;
	}
	if(theInput.attr("value").length<1 || theInput.attr("value").length>12){
		displayError("�ǳƳ��ȴ���","nickname");
		theInput.css("border-color","red"); 
		return false;
	}else if(!isTrueName(theInput.attr("value"))){
		displayError("�ǳư��������ַ�","nickname");
		theInput.css("border-color","red"); 
		return false;
	}else{
		$.get("/Index/exist/nickname/"+theInput.attr("value"),function(data){
			var result = eval(data);
			if(result==true){
				displayError("�ǳ��Ѿ�����","nickname");
				theInput.css("border-color","red"); 
				return false;
			}
		});
	}
	displayError("OK","nickname");
	theInput.css("border-color","#66cc66"); 
	return true;
}

function testEmail(input)
{
	var theInput = $(input);
	if(theInput.attr("value")=="" || theInput.attr("value")==null){
		displayError("�ʼ���ַ����","email");
		theInput.css("border-color","red"); 
		return false;
	}
	if(!isEmail(theInput.attr("value"))){
		displayError("�ʼ���ַ����","email");
		theInput.css("border-color","red"); 
		return false;
	}else{
		$.get("/Index/exist/email/"+theInput.attr("value"),function(data){
			var result = eval(data);
			if(result==true){
				displayError("�ʼ���ַ�Ѿ�����","email");
				theInput.css("border-color","red"); 
				return false;
			}
		});
	}
	displayError("OK","email");
	theInput.css("border-color","#66cc66"); 
	return true;
}

function testRepassword(input1, input2)
{
	var theInput1 = $(input1);
	var theInput2 = $(input2);
	if(theInput2.attr("value")=="" || theInput2.attr("value")==null){
		displayError("������������벻һ��","repassword");
		theInput2.css("border-color","red"); 
		return false;
	}
	
	
	if(theInput1.attr("value") != theInput2.attr("value")){
		displayError("������������벻һ��","repassword");
		theInput2.css("border-color","red"); 
		return false;
	}
	displayError("OK","repassword");
	theInput2.css("border-color","#66cc66"); 
	return true;
}

function testPassword(input)
{
	var theInput = $(input);
	if(theInput.attr("value")=="" || theInput.attr("value")==null){
		displayError("���볤�ȴ���������λ","password");
		theInput.css("border-color","red"); 
		return false;
	}
	if(!isPasswd(theInput.attr("value"))){
		displayError("���볤�ȴ��󣬳���ҪΪ6-18λ","password");
		theInput.css("border-color","red"); 
		return false;
	}
	displayError("OK","password");
	theInput.css("border-color","#66cc66"); 
	return true;
}

function testAll(){
	var result = (testUsername('#username') && testPassword('#password') && testRepassword('#password','#re-password') && testNickname('#nickname') && testEmail('#email'));
	if(result==true){
		$('#passportRegisterForm').submit();
	}else{
		return false;
	}
}

function isTrueName(s) {
	 var patrn=/[>&*<\$\r\n\t '\"`\\]/; 
	 if (!patrn.exec(s)){
	 	return true;
	 }else{
	 	return false;
	 }
}

function isPasswd(s) {
	if(s.length<6){
		return false;
	}else{
		if(s.length>18){
			return false;
		}else{
			return true;	
		}
		
	}
}

function isEmail(s){
	var patrn=/^(.+)@([^@]+)$/; 
	 if (!patrn.exec(s)){
	 	return false;
	 }else{
	 	return true;
	 }
}

function checkStrLen(value){
	var str, Num = 0;
	for (var i = 0; i < value.length; i++) {
		str = value.substring(i, i + 1);
		if (str <= "~") { //�ж��Ƿ�˫�ֽ�
			Num += 1;
		}
		else {
			Num += 2;
		}
	}
	return Num;
}






function loadIntoMain(url)
{
	var myAjax = new Ajax.Updater(
										"main",
										"/Admin/"+url,
										{
                    						method: 'get',
											evalScripts:true,
											onLoading:showAjaxLoadingGif("main")
                						}
                					);
}

function submitAdminFormK(form,K){
	if(K != null){
		var content = FCKeditorAPI.GetInstance(K).GetXHTML(); // retrieve the fck field
		$(K).value = content;
	}
	var jsonedValue = {};
	var theForm = $(form);
	if(form == null){
		return false;
	}else{
		var result = theForm.getElements();
		result.each(function(item){
			if(item.name != "submit" && item.name != "reset"){
				jsonedValue[item.name] = item.value;
			}
		});
		
	}
	
	var myAjax = new Ajax.Updater(
										"main",
										theForm.action,
										{
                    						method: 'post',
											evalScripts:true,
											parameters:{'k':Object.toJSON(jsonedValue)},
											onLoading:showAjaxLoadingGif("main")
                						}
                					);
}


function convertB(Bnum, Brate, Bdest){
	var dest = Bdest;
	if(Bnum=="" || Bnum == null){
		Bnum = 0;
	}
	
	if(Bnum == 0){
		dest.html("��������");
	}else{
		Bnum = Bnum * 10000;
		Brate = Brate * 10000;
		if(Bnum * Math.ceil(Brate)%100000000 != 0){
			dest.html("�������ǻ��ʵ�������");
		}else{
			dest.html(Bnum * Brate / 100000000);
		}
	}
	
	return true;
	
}

function initDivWindow(){
	var originalDiv = '\
	<div id="window">\
		<div id="window_handle">\
		</div>\
		<div id="window_content">\
		</div>\
	</div>';
	$(document.body).append(originalDiv);
	
	$('#window').Draggable(
		{
			zIndex: 	80,
			ghosting:	false,
			opacity: 	0.7,
			handle:	'#window_handle'
		}
	);	
			
	$("#window").hide();
				
}

function showDivWindow(title, content){
	$("#window").show();
	$("#window_handle").html('<a id="close" onclick="return hideDivWindow();">[ x ]</a>'+title);
	$("#window_content").html(content);
}

function hideDivWindow(){
	$("#window").hide();
	return false;
}




function testLeaguename(input)
{
	var theInput = $(input);
	if(theInput.attr("value")=="" || theInput.attr("value")==null){
		displayError("����Ϊ3-12λ","league_name");
		theInput.css("border-color","red"); 
		return false;
	}
	if(theInput.attr("value").length<3 || theInput.attr("value").length>12){
		displayError("����Ϊ3-12λ","league_name");
		theInput.css("border-color","red"); 
		return false;
	}else if(!isTrueName(theInput.attr("value"))){
		displayError("��Ҫ�������ַ�","league_name");
		theInput.css("border-color","red"); 
		return false;
	}else{
		$.get("/league/Index/exist/league_name/"+theInput.attr("value"),function(data){
			var result = eval(data);
			if(result==true){
				displayError("�����Ѿ�����","league_name");
				theInput.css("border-color","red"); 
				return false;
			}
		});
	}
	displayError("OK","league_name");
	theInput.css("border-color","#66cc66"); 
	return true;

}






function testLeagueAll(){
	
	//document.leagueRegisterForm.submit();
	
	var result = (testLeaguename('#league_name'));

	if(result==true){
		$('#leagueRegisterForm').submit();
	}else{
		return false;
	}

}


function testLeaguelogo(regForm)
{

		if (window.ActiveXObject) { 
		
					var img=document.leagueRegisterForm.upload.value;
					document.leagueRegisterForm.imgUrl.src=img;
		
					if(img=="")
					{
					   //alert("����ѡ��ͼƬ�ļ�");
					   return true;
					}
			
				var point = img.lastIndexOf(".");
				var type = img.substr(point);
				if(type==".jpg"||type==".gif"||type==".JPG"||type==".GIF"||type==".PNG"||type==".png"||type==".bmp"||type==".BMP")
				{			
						
						window.setTimeout(checkPicSize,300);
						
				}
				else
				{
				   alert("ֻ������jpg����gif��ʽ��ͼƬ");
				   return false;
				}
			return true;
			}
	
		if (window.XMLHttpRequest) {
		
					document.getElementById("imgUrl").src = regForm.files[0].getAsDataURL();
					return true;
		}
				
			

}  

function checkPicSize()
{
	   
	if(document.leagueRegisterForm.imgUrl.fileSize>102400)
	{
			alert("ͼƬ�ߴ��벻Ҫ����100KB");
		return false;
	}
		else
			return true;

			
}