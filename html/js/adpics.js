

//����ͼ��ʼ���ݣ���start
var slidespeed=3000	//速度

var slidesanjiaoimages=new Array("http://www.moko.cc/images/index_girl/bian2.gif","123/bian1.gif");
var slidesanjiaoimagesname=new Array("xiaosan1","xiaosan2","xiaosan3","xiaosan4","xiaosan5","xiaosan6","xiaosan7");

var filterArray=new Array();
filterArray[0]="progid:DXImageTransform.Microsoft.Pixelate (enabled=false,duration=2,maxSquare=25 )";
filterArray[1]="progid:DXImageTransform.Microsoft.Stretch (duration=1,stretchStyle=PUSH)";
filterArray[2]="progid:DXImageTransform.Microsoft.Stretch(duration=1)";
filterArray[3]="progid:DXImageTransform.Microsoft.Slide(bands=8, duration=1)";
filterArray[4]="progid:DXImageTransform.Microsoft.Fade ( duration=1,overlap=0.25 )";
filterArray[5]="progid:DXImageTransform.Microsoft.Fade ( duration=0,overlap=0 )";
filterArray[6]="progid:DXImageTransform.Microsoft.Fade ( duration=1,overlap=0.25 )";
filterArray[7]="progid:DXImageTransform.Microsoft.Fade ( duration=1,overlap=0.25 )";
filterArray[8]="progid:DXImageTransform.Microsoft.Fade ( duration=1,overlap=0.25 )";
filterArray[9]="progid:DXImageTransform.Microsoft.Fade ( duration=1,overlap=0.25 )";
filterArray[10]="progid:DXImageTransform.Microsoft.Fade ( duration=1,overlap=0.25 )";
filterArray[11]="progid:DXImageTransform.Microsoft.Fade ( duration=1,overlap=0.25 )";
filterArray[12]="progid:DXImageTransform.Microsoft.Fade ( duration=1,overlap=0.25 )";
filterArray[13]="progid:DXImageTransform.Microsoft.Fade ( duration=1,overlap=0.25 )";
filterArray[14]="progid:DXImageTransform.Microsoft.Fade ( duration=1,overlap=0.25 )";

var imageholder=new Array()
var ie55=window.createPopup
for (i=0;i<slideimages.length;i++){
imageholder[i]=new Image()
imageholder[i].src=slideimages[i];
imageholder[i].alt=slidetext[i];
}

function tu_ove(){clearTimeout(setID)}
function ou(){slideit()}

				var whichlink=0
				var whichimage=0
				
				function gotoshow(){
						window.open(slidelinks[whichlink]);
				}
				
				function slideit(){
				
				document.images.slide.style.filter=filterArray[5];
				pixeldelay=(ie55)? (document.images.slide.filters[0].duration*1000) : 0
				if (!document.images) return
				
				if (ie55) {
						document.images.slide.filters[0].apply();
						document.images.slide.filters[0].play();
						
				}
				document.images.slide.src=imageholder[whichimage].src;
				document.images.slide.alt=imageholder[whichimage].alt;
				document.images.slide.parentNode.href=slidelinks[whichimage];
				
				document.getElementById("textslide").innerHTML=slidetext[whichimage];
				document.getElementById("textslide").href=slidelinks[whichimage];
					
					document.getElementById("xiaosan1").parentNode.className="l font12";
					document.getElementById("xiaosan2").parentNode.className="l font12";
					document.getElementById("xiaosan3").parentNode.className="l font12";
					document.getElementById("xiaosan4").parentNode.className="l font12";
					document.getElementById("xiaosan5").parentNode.className="l font12";
					document.getElementById("xiaosan6").parentNode.className="l font12";
  					document.getElementById("xiaosan7").parentNode.className="l font12";
					document.getElementById(slidesanjiaoimagesname[whichimage]).parentNode.className="l font12 alive";
				
				
				if (ie55) document.images.slide.filters[0].play()
				whichlink=whichimage
				whichimage=(whichimage<slideimages.length-1)? whichimage+1 : 0
				setID=setTimeout("slideit()",slidespeed+pixeldelay)
				}
				slideit()
				function ove(n){
					clearTimeout(setID)
					whichimage=n;
					document.images.slide.src=imageholder[whichimage].src;
					document.images.slide.alt=imageholder[whichimage].alt;
				document.images.slide.parentNode.href=slidelinks[whichimage];
						
					document.getElementById("textslide").innerHTML=slidetext[whichimage];
				document.getElementById("textslide").href=slidelinks[whichimage];
					
					document.getElementById("xiaosan1").parentNode.className="l font12";
					document.getElementById("xiaosan2").parentNode.className="l font12";
					document.getElementById("xiaosan3").parentNode.className="l font12";
					document.getElementById("xiaosan4").parentNode.className="l font12";
					document.getElementById("xiaosan5").parentNode.className="l font12";
					document.getElementById("xiaosan6").parentNode.className="l font12";
 					document.getElementById("xiaosan7").parentNode.className="l font12";
					document.getElementById(slidesanjiaoimagesname[whichimage]).parentNode.className="l font12 alive";
				slideit()
				}
		
