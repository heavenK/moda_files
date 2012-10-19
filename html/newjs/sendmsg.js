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
					 Ext.Msg.alert('��Ϣ',message);
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
				blankText:'����',
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
				blankText:'���ݲ���Ϊ�գ�',
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
                        emptyText: '������֤��',
						autoWidth:true,
                        name: 'chknumber',
                        allowBlank: false,
                        blankText: '��֤�벻��Ϊ��'
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
				text: '����',
				handler: function(){
					if (window_form_edit.form.isValid()) {
						window_form_edit.form.submit({
							waitTitle: '���Ժ�',
							waitMsg: '�����ύ����,���Ժ�....',
							url: '?Controller=SecondACT&action=SendShortMsg',
							method: 'POST',
							success: function(form, action){
								  Ext.MessageBox.alert('��ʾ', action.result.message);
								  w.close();
							},
							failure: function(form, action){
								if (action.failureType === Ext.form.Action.CONNECT_FAILURE){
									Ext.Msg.alert('Failure', 'Server reported:'+a.response.status+' '+a.response.statusText);
								}
								Ext.MessageBox.alert('��ʾ', action.result.message);
							}
						})
					}
				}
			}, {
				text: 'ȡ��',
				handler: function(){
					w.close();
				}
			}]
		});
		
		
		
		var w = new Ext.Window({
			layout: 'form',
			title: '���Ͷ���Ϣ',
			width :465,
			items:window_form_edit
		});
		
		w.show();
	}
}
