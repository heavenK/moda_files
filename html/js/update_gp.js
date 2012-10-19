	
	
	$(document).ready( 

	function(){
	
		//if($.browser.msie){ 
			$("input[@type='text'], input[@type='password'], textarea").focus(
				
				function() {$(this).css({border:"#990099 1px solid"});}
				
				/*function() {$(this).css({border:"#990000 1px solid"});}*/
			) ;
			$("input[@type='text'], input[@type='password'], textarea").blur(
				function() {
					//alert();
					//
					$(this).css({border:""});
					//$("#backgroundPopup").load("http://www.cnblogs.com/QLeelulu/archive/2008/03/30/1130270.html .post",
//						function (responseText, textStatus, XMLHttpRequest){
//						//this;//在这里this指向的是当前的DOM对象，即$(".ajax.load")[0]
//						alert(responseText);//请求返回的内容
//						//alert(textStatus);//请求状态：success，error
//						//alert(XMLHttpRequest);//XMLHttpRequest对象
//					});
					if($(this).get(0).value	!=$(this).get(0).defaultValue){
						$.ajax({
								type: "post",
								url: "?Controller=ModaIndex&action=PostGerenzhiliao",
								data:"&"+$(this).attr('name')+"="+escape($(this).attr('value'))+"&user_id="+$("#user_id").attr('value'),
								//data:{$(this).attr('name'):$(this).attr('value')},
								//data:{name:"ddddd"}
								beforeSend: function(XMLHttpRequest){
									//ShowLoading();
								},
								success: function(data, textStatus){
									//$(".ajax.ajaxResult").html("");
				//					$("item",data).each(function(i, domEle){
				//						$(".ajax.ajaxResult").append("<li>"+$(domEle).children("title").text()+"</li>");
				//					});
									//alert("dd");
								},
								complete: function(XMLHttpRequest, textStatus){
									//HideLoading();
								},
								error: function(){
									//请求出错处理
								}
						});
					}else{
						
					}
						
				
				/*function() {$(this).css({background:"#FFFFF7"});}*/
				
				}
			); 
			
			
			
	}
	
	
	
); 