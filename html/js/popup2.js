/***************************/
//@Author: Adrian "yEnS" Mato Gondelle
//@website: www.yensdesign.com
//@email: yensamg@gmail.com
//@license: Feel free to use it, but keep this credits please!					
/***************************/

//SETTING UP OUR POPUP
//0 means disabled; 1 means enabled;
var popupStatuss = 0;

//loading popup with jQuery magic!
function loadPopups(){
	//loads popup only if it is disabled
	if(popupStatuss==0){
		$("#backgroundPopups").css({
			"opacity": "0.7"
		});
		$("#backgroundPopups").fadeIn("slow");
		$("#popupContacts").fadeIn("slow");
		popupStatuss = 1;
	}
}

//disabling popup with jQuery magic!
function disablePopups(){
	//disables popup only if it is enabled
	if(popupStatuss==1){
		$("#backgroundPopups").fadeOut("slow");
		$("#popupContacts").fadeOut("slow");
		popupStatuss = 0;
	}
}

//centering popup
function centerPopups(){
	//request data for centering
	var windowWidth = document.documentElement.clientWidth;
	var windowHeight = document.documentElement.clientHeight;
	var popupHeight = $("#popupContacts").height();
	var popupWidth = $("#popupContacts").width();
	//centering
	$("#popupContacts").css({
		"position": "absolute",
		"top": windowHeight/2-popupHeight/2,
		"left": windowWidth/2-popupWidth/2
	});
	//only need force for IE6
	
	$("#backgroundPopups").css({
		"height": windowHeight*2.4
	});
	
}


//CONTROLLING EVENTS IN jQuery
$(document).ready(function(){
	
	//LOADING POPUP
	//Click the button event!
	$("#buttons").click(function(){
		//centering with css
		centerPopups();
		//load popup
		loadPopups();
	});
	
				
	//CLOSING POPUP
	//Click the x event!
	$("#popupContactCloses").click(function(){
		disablePopups();
	});
	//Click out event!
	$("#backgroundPopups").click(function(){
		disablePopups();
	});
	//Press Escape event!
	$(document).keypress(function(e){
		if(e.keyCode==27 && popupStatuss==1){
			disablePopups();
		}
	});

});