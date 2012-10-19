/**
 * 
 * 使用Ext2.0构造一个客户登陆面板
 */
 Ext.BLANK_IMAGE_URL = '../ext/resources/images/default/s.gif';
 Ext.QuickTips.init();
 LoginWindow=Ext.extend(Ext.Window,{
 	title : '登陆系统',		
	width : 265,			
	height : 110,		
	collapsible : true,
	defaults : {			
		border : false
	},
	buttonAlign : 'center',	
	createFormPanel :function() {
		return new Ext.form.FormPanel( {
			bodyStyle : 'padding-top:6px',
			defaultType : 'textfield',
			labelAlign : 'right',
			labelWidth : 55,
			labelPad : 0,
			frame : true,
			defaults : {
				allowBlank : false,
				width : 158
			},
			items : [
					 
				{
					cls : 'key',
					name : 'userName',
					fieldLabel : '口令',
					blankText : '口令不能为空'
				}
//				{
//					cls : 'user',
//					name : 'userName',
//					fieldLabel : '帐号',
//					blankText : '帐号不能为空'
//				}
//				, {
//					cls : 'key',
//					name : 'password',
//					fieldLabel : '密码',
//					blankText : '密码不能为空',
//					inputType : 'password'
//				}
				
				]
		});
	},					
	login:function() {
			this.fp.form.submit({
					waitMsg : '正在登录......',
					url : '?Controller=ExtjsLogin&action=AdminLogin',
					success : function(form, action) {
						Ext.Msg.alert('警告', "成功");
						window.location.href = '?Controller=ExtjsAdmin&action=MainView';
					},
					failure : function(form, action) {
						
						//Ext.MessageBox.alert('警告', "用户名或密码错误");
						Ext.Msg.alert("提示",action.result.message);
					}
				});
		},
	initComponent : function(){
        LoginWindow.superclass.initComponent.call(this);       
        this.fp=this.createFormPanel();
        this.add(this.fp);
        this.addButton('登陆',this.login,this);
        this.addButton('重置', function(){this.fp.form.reset();},this);
	 } 	
 }); 

Ext.onReady(function()
{
	var win=new LoginWindow();
	win.show();
	setTimeout(function() {
				Ext.get('loading-mask').fadeOut( {
					remove : true
				});
			}, 300);
}
);