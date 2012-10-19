	function getScreenSize(){
		var w = 0;
		var h = 0;
		if( typeof( window.innerWidth ) == 'number' ) {
			w = window.innerWidth;
			h = window.innerHeight;
		} else if( document.documentElement && ( document.documentElement.clientWidth || document.documentElement.clientHeight ) ) {
			w = document.documentElement.clientWidth;
			h = document.documentElement.clientHeight;
		} else if( document.body && ( document.body.clientWidth || document.body.clientHeight ) ) {
			w = document.body.clientWidth;
			h = document.body.clientHeight;
		}
		return {width:w,height:h};
	}
	function fullScreen(){
		if(!orginFlash.init){			
			orginFlash.position = document.getElementById("flashcontent").style.position;
			orginFlash.top = document.getElementById("flashcontent").style.top;
			orginFlash.left = document.getElementById("flashcontent").style.left;
			orginFlash.width = document.getElementById("flashcontent").style.width;
			orginFlash.height = document.getElementById("flashcontent").style.height;
		}
		orginFlash.init = true;
		orginFlash.isFullScreen = true;
		var screenSize = getScreenSize();
		try{
			document.getElementById("flashcontent").style.position = "absolute";
			document.getElementById("flashcontent").style.top = "0px";
			document.getElementById("flashcontent").style.left = "0px";
			document.getElementById("flashcontent").style.width = screenSize.width +"px";
			document.getElementById("flashcontent").style.height = screenSize.height +"px";
			document.body.style.overflow="hidden";
			window.scrollTo(0,0);
		}catch(e){
		}
	}
	function normal(){
		if(orginFlash.init){
			orginFlash.isFullScreen = false;
			try{
				document.getElementById("flashcontent").style.position = orginFlash.position;
				document.getElementById("flashcontent").style.top = orginFlash.top;
				document.getElementById("flashcontent").style.left = orginFlash.left;
				document.getElementById("flashcontent").style.width = orginFlash.width;
				document.getElementById("flashcontent").style.height = orginFlash.height;
				document.body.style.overflow="auto";
			}catch(e){				
			}
		}
	}
	function reSize(){
		if(orginFlash.isFullScreen){
			fullScreen();
		}
	}

	var orginFlash = {init:false,isFullScreen:false,position:"",top:"",left:"",width:"",height:""};
	function writeFlash(t,to){
		if(to)
		{
				var name = to;
				var to1 = new SWFObject("bofangqi.swf", "fplayer", "640", "390", 8, "#29242b");
				to1.addParam("quality", "high");
				to1.addParam("swLiveConnect", "true");
				to1.addParam("menu", "false");
				to1.addParam("allowScriptAccess", "sameDomain");
				to1.addParam("allowFullScreen", "true");
				to1.addVariable("file",t); 
				to1.write(name);
		}
		else
		{
				var so = new SWFObject("bofangqi.swf", "fplayer", "640", "390", 8, "#29242b");
				so.addParam("quality", "high");
				so.addParam("swLiveConnect", "true");
				so.addParam("menu", "false");
				so.addParam("allowScriptAccess", "sameDomain");
				so.addParam("allowFullScreen", "true");
				so.addVariable("file",t); 
				so.write("flashcontent");
		}
	}
	