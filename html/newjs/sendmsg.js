	Ext.onReady(function(){
		Ext.QuickTips.init();
		Ext.util.CSS.swapStyleSheet('window','./ext-3.1.0/resources/css/xtheme-gray.css');
	 });
var msg = {
	
	poll : function(show_id) 
	{
		  Ext.Ajax.request({
			  url: '?Controller=SecondACT&action=ForShowTicket',
			  params : {  
                          show_id : show_id
                    },
			  success:function(request)
			  {  
					 var message = request.responseText; 
					 Ext.Msg.alert('信息',message);
			 }
		  });
	},
	
	abc : function(user_id) {

		var window_form_edit = new Ext.FormPanel({ 
			frame: true,
			width: 450,
			labelWidth:40,
			bodyStyle: 'padding:5px 5px 0',
			baseCls: 'x-plain',
			items: [
			{
				xtype: 'hidden',
				name: 'user_id',
				blankText:'错误',
				allowBlank: false,
				value:user_id
		    },{
				xtype: 'htmleditor',
				//xtype: 'textarea',
				name: 'description',
				hideLabel: true,
				style: 'background:url(/images/inputlogo.jpg); background-repeat:no-repeat;background-color:#FFF ;',
				height: 200,
				anchor: '100%',
				blankText:'内容不能为空！',
				allowBlank: false
		    },
			{
			autoWidth: true,									 
			frame: true,
			width: 450,
			labelWidth:40,
			baseCls: 'x-plain',
				items: [
				{
						xtype: 'textfield',
                        emptyText: '输入验证码',
						autoWidth:true,
                        name: 'chknumber',
                        allowBlank: false,
                        blankText: '验证码不能为空'
              	 },{
						xtype: 'textfield',
						labelStyle:'width:0px;',
						width: 50,
                        style: 'background:url(/chknumber.php);background-repeat: no-repeat;',
						readOnly:true
             	 }
				]
			}	
			],
			buttons: [{
				text: '留言',
				handler: function(){
					if (window_form_edit.form.isValid()) {
						window_form_edit.form.submit({
							waitTitle: '请稍候',
							waitMsg: '正在提交数据,请稍候....',
							url: '?Controller=SecondACT&action=SendShortMsg',
							method: 'POST',
							success: function(form, action){
								  Ext.MessageBox.alert('提示', action.result.message);
								  w.close();
							},
							failure: function(form, action){
								if (action.failureType === Ext.form.Action.CONNECT_FAILURE){
									Ext.Msg.alert('Failure', 'Server reported:'+a.response.status+' '+a.response.statusText);
								}
								Ext.MessageBox.alert('提示', action.result.message);
							}
						})
					}
				}
			}, {
				text: '取消',
				handler: function(){
					w.close();
				}
			}]
		});
		
		
		
		var w = new Ext.Window({
			layout: 'form',
			title: '发送短消息',
			width :465,
			items:window_form_edit
		});
		
		w.show();
	}
}
