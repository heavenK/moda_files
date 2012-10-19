function g(o){return document.getElementById(o);} 
function HoverLi(n){ 
for(var i=1;i<=7;i++){g('tb_'+i).className='normaltab';g('tbc_0'+i).className='undis';}g('tbc_0'+n).className='dis';g('tb_'+n).className='hovertab'; 
} 

function reloadcode(){
	var verify=document.getElementById('safecode');
	verify.setAttribute('src','?Controller=ShowIndex&action=AuthCode&oo='+Math.random());
	//这里必须加入随机数不然地址相同我发重新加载
}