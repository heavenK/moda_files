/***************************/
//@Author: Adrian "yEnS" Mato Gondelle
//@website: www.yensdesign.com
//@email: yensamg@gmail.com
//@license: Feel free to use it, but keep this credits please!					
/***************************/

//SETTING UP OUR POPUP
//0 means disabled; 1 means enabled;
var popupStatusyy = 0;

//loading popup with jQuery magic!
function loadPopupyy(){
	//loads popup only if it is disabled
	if(popupStatusyy==0){
		$("#backgroundPopupyy").css({
			"opacity": "0.7"
		});
		$("#backgroundPopupyy").fadeIn("slow");
		$("#popupContactyy").fadeIn("slow");
		popupStatusyy = 1;
	}
}

//disabling popup with jQuery magic!
function disablePopupyy(){
	//disables popup only if it is enabled
	if(popupStatusyy==1){
		$("#backgroundPopupyy").fadeOut("slow");
		$("#popupContactyy").fadeOut("slow");
		popupStatusyy = 0;
	}
}

//centering popup
function centerPopupyy(){
	//request data for centering
	var windowWidth = document.documentElement.clientWidth;
	var windowHeight = document.documentElement.clientHeight;
	var popupHeight = $("#popupContactyy").height();
	var popupWidth = $("#popupContactyy").width();
	//centering
	$("#popupContactyy").css({
		"position": "absolute",
		"top": windowHeight/2-popupHeight/2,
		"left": windowWidth/2-popupWidth/2
	});
	//only need force for IE6
	
	$("#backgroundPopupyy").css({
		"height": windowHeight
	});
	
}


//CONTROLLING EVENTS IN jQuery
$(document).ready(function(){
	
	//LOADING POPUP
	//Click the button event!
	$("#buttonyy").click(function(){
		//centering with css
		centerPopupyy();
		//load popup
		loadPopupyy();
	});
	
				
	//CLOSING POPUP
	//Click the x event!
	$("#popupContactCloseyy").click(function(){
		disablePopupyy();
	});
	//Click out event!
	$("#backgroundPopupyy").click(function(){
		disablePopupyy();
	});
	//Press Escape event!
	$(document).keypress(function(e){
		if(e.keyCode==27 && popupStatus==1){
			disablePopupyy();
		}
	});

});