/***************************/
//@Author: Adrian "yEnS" Mato Gondelle
//@website: www.yensdesign.com
//@email: yensamg@gmail.com
//@license: Feel free to use it, but keep this credits please!					
/***************************/

//SETTING UP OUR POPUP
//0 means disabled; 1 means enabled;
var popupStatusa = 0;

//loading popup with jQuery magic!
function loadPopupa(){
	//loads popup only if it is disabled
	if(popupStatusa==0){
		$("#backgroundPopupa").css({
			"opacity": "0.7"
		});
		$("#backgroundPopupa").fadeIn("slow");
		$("#popupContacta").fadeIn("slow");
		popupStatusa = 1;
	}
}

//disabling popup with jQuery magic!
function disablePopupa(){
	//disables popup only if it is enabled
	if(popupStatusa==1){
		$("#backgroundPopupa").fadeOut("slow");
		$("#popupContacta").fadeOut("slow");
		popupStatusa = 0;
	}
}

//centering popup
function centerPopupa(){
	//request data for centering
	var windowWidth = document.documentElement.clientWidth;
	var windowHeight = document.documentElement.clientHeight;
	var popupHeight = $("#popupContacta").height();
	var popupWidth = $("#popupContacta").width();
	//centering
	$("#popupContacta").css({
		"position": "absolute",
		"top": windowHeight/2-popupHeight/2,
		"left": windowWidth/2-popupWidth/2
	});
	//only need force for IE6
	
	$("#backgroundPopupa").css({
		"height": windowHeight*2.4
	});
	
}


//CONTROLLING EVENTS IN jQuery
$(document).ready(function(){
	
	//LOADING POPUP
	//Click the button event!
	$("#s").click(function(){
		//centering with css
		centerPopupa();
		//load popup
		loadPopupa();
	});
	
				
	//CLOSING POPUP
	//Click the x event!
	$("#popupContactClosea").click(function(){
		disablePopupa();
	});
	//Click out event!
	$("#backgroundPopupa").click(function(){
		disablePopupa();
	});
	//Press Escape event!
	$(document).keypress(function(e){
		if(e.keyCode==27 && popupStatusa==1){
			disablePopupa();
		}
	});

});