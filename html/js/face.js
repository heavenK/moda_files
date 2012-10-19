function insertSmiley(smilieid) {

	var src2 = document.getElementById("smilie_"+smilieid).src;

		
		var name = src2.substring(30,100);
		var p= name.indexOf(".");
		var name = name.substring(0,p);

		
		var code = ':'+name+' '
		AddText(code);

}


function AddText(txt) {

	obj = document.form1.content

	selection = document.selection;
	obj.value += txt;


}

