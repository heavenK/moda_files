/***************************/
//@Author: Adrian "yEnS" Mato Gondelle
//@website: www.yensdesign.com
//@email: yensamg@gmail.com
//@license: Feel free to use it, but keep this credits please!					
/***************************/

//SETTING UP OUR POPUP
//0 means disabled; 1 means enabled;
var popupStatus = 0;
var popupzhujian = 0;
//loading popup with jQuery magic!
function loadPopup(zhujian){
	//loads popup only if it is disabled
	if(popupStatus==0){
		$("#backgroundPopup").css({
			"opacity": "0.7"
		});
		$("#backgroundPopup").fadeIn("slow");
		$(zhujian).fadeIn("slow");
		popupStatus = 1;
	}
}

//disabling popup with jQuery magic!
function disablePopup(zhujian){
	//disables popup only if it is enabled
	if(popupStatus==1){
		$("#backgroundPopup").fadeOut("slow");
		$(zhujian).fadeOut("slow");
		popupStatus = 0;
	}
}

//centering popup
function centerPopup(zhujian){
	
	//request data for centering
	var windowWidth = document.documentElement.clientWidth;
	var windowHeight = document.documentElement.clientHeight;
	var popupHeight = $(zhujian).height();
	var popupWidth = $(zhujian).width();
	
	//alert(document.documentElement.scrollHeight);
	
	//var hhhh = document.body.clientHeight;
	//alert(document.body.clientHeight);
	var hight = document.documentElement.scrollHeight - windowHeight + popupHeight;
	//alert(hight) ;
	
	//var hight = hhhh - popupHeight -150;
	if(hight < 0)
		hight = windowHeight/2-popupHeight/2;
	
	//centering
	$(zhujian).css({
		"position": "absolute",
		//"top": windowHeight/2-popupHeight/2,
		//"top": hight,
		//"left": windowWidth/2-popupWidth/2 - 50
		
		"top": hight,
		"left": windowWidth/2-popupWidth/2
		
	});
	//only need force for IE6
	
	$("#backgroundPopup").css({
		"height": windowHeight
	});
	
}

function inputName(divname){
		
		var tname = "#"+divname;
		//alert(tname);
		
		$(tname).click(function(){
		
		//alert("aaaa");
		//centering with css
		centerPopup("#popupContactAAA");
		//load popup
		loadPopup("#popupContactAAA");
	});
	
	

}



//CONTROLLING EVENTS IN jQuery
$(document).ready(function(){
	
	//LOADING POPUP
	//Click the button event!
	$("#popAAA").click(function(){
		//alert("gggg");
		
		popupzhujian = 0;
		//centering with css
		centerPopup("#popupContactAAA");
		//load popup
		loadPopup("#popupContactAAA");
	});
		
	$("#popone").click(function(){
		popupzhujian = 1;
		//centering with css
		centerPopup("#popupContactBBB");
		//load popup
		loadPopup("#popupContactBBB");
	});
	$("#poptow").click(function(){
		popupzhujian =2;
		//centering with css
		centerPopup("#popupContactCCC");
		//load popup
		loadPopup("#popupContactCCC");
	});
	

				
	//CLOSING POPUP
	//Click the x event!
	$("#popupContactClose").click(function(){
		if(popupzhujian == 0)
		disablePopup("#popupContactAAA");
		if(popupzhujian == 1)
		disablePopup("#popupContactBBB");
		if(popupzhujian == 2)
		disablePopup("#popupContactCCC");
	});
	//Click out event!
	$("#backgroundPopup").click(function(){
		if(popupzhujian == 0)
		disablePopup("#popupContactAAA");
		if(popupzhujian == 1)
		disablePopup("#popupContactBBB");
		if(popupzhujian == 2)
		disablePopup("#popupContactCCC");
	});
	//Press Escape event!
	$(document).keypress(function(e){
		if(e.keyCode==27 && popupStatus==1){
			if(popupzhujian == 0)
			disablePopup("#popupContactAAA");
			if(popupzhujian == 1)
			disablePopup("#popupContactBBB");
			if(popupzhujian == 2)
			disablePopup("#popupContactCCC");
		}
	});
	

});