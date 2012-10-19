

/*美达用户管理菜单
*/
ModaIndexPanel=function()
{	


	ModaIndexPanel.superclass.constructor.call(this, {
        autoScroll:true,
        animate:true,
        border:false,
        rootVisible:false,
        root:new Ext.tree.TreeNode({
        text: '美达首页',
        draggable:false,
       	expanded:true
   	 })        
    });
   this.root.appendChild(new Ext.tree.TreeNode({
   		text:"新版本扩展信息设置",
   		listeners:{'click':function(){
			window.open('?Controller=ExtAdmin')  
			
   		}}	
   		})); 
   
}
Ext.extend(ModaIndexPanel, Ext.tree.TreePanel,{});


/**
 * 书写新日志或添加日志
 */
ModaConfig=Ext.extend(Ext.Panel,{
	id:"ModaConfig",
	title:"美达广告管理",
	closable: true,
  	autoScroll:true,
  	layout:"fit",  			
	save:function()
	{	
	
	
		var id=this.fp.form.findField("Id").getValue();
		if (this.getComponent('rank_form_add').form.isValid()) {
			this.fp.form.submit({
					waitMsg:'正在保存。。。',
					url:"?Controller=ExtjsAdmin&action=AddModaRank",
					method:'POST',
					success:function(form, action){
						
						//Ext.MessageBox.alert('警告', action.result.message,);

//						var main=Ext.getCmp("main");

						Ext.Msg.alert("提示信息","数据保存成功!",function(){
							main.closeTab(this);
							var panel=Ext.getCmp("NoPassListPanel");
							if(!panel)panel=new NoPassListManage();
							main.openTab(panel);
							panel.refresh();},this);
						
				   },
					failure : function(form, action) {
							
							Ext.MessageBox.alert('警告', "失败");
	
						},
					scope:this
			});
		}
		else
		{
					Ext.MessageBox.alert('警告', "失败");
			}
	},
	createFormPanel:function(){
		return  new Ext.form.FormPanel({
									   
                        id: 'rank_form_add',
                        name: 'rank_form_add',
		buttonAlign:"center",
		labelAlign:"right",
		bodyStyle:'padding:25px',
		defaults:{width:650},
		frame:false,
		fileUpload:true,
		bodyBorder:false,
		border:true,
		labelWidth:60,

		items:[	
			{xtype:"hidden",name:"Id"},	   
		    {xtype:"textfield",
			 name:"rank_title",
			 fieldLabel:"标题"},
  			{
  			xtype:"field",	  			
	  		name:"img",
	  		height:25,
	  		fieldLabel:"标题图片",
	  		inputType:"file"
	  		},	   
		    {xtype:"datefield",
			 name:"overtime",
			 fieldLabel:"结束时间"},
  			{
  			xtype:"field",	  			
	  		name:"first_mark",
	  		height:25,
	  		fieldLabel:"第一名标识",
	  		inputType:"file"
	  		},
  			{
  			xtype:"field",	  			
	  		name:"sec_mark",
	  		height:25,
	  		fieldLabel:"第二名标识",
	  		inputType:"file"
	  		},
  			{
  			xtype:"field",	  			
	  		name:"thr_mark",
	  		height:25,
	  		fieldLabel:"第三名标识",
	  		inputType:"file"
	  		},
  			{
  			xtype:"htmleditor",
  			height:300,
	  		name:"content",
	  		fieldLabel:"排行榜相关信息"
	  		}],
  		buttons:[{text:"提交",
  				  handler:this.save,
  				  scope:this},
  				  {text:"清空",
  				   handler:function(){this.fp.form.reset();},
  				   scope:this  				   
  				  },
  				  {text:"取消",
  				   handler:function(){Ext.getCmp("main").closeTab(this);},
  				   scope:this  				   
  				  }]
   	 });
   	 },
	initComponent : function(){
	ModaConfig.superclass.initComponent.call(this);
	this.fp=this.createFormPanel();
	this.add(this.fp);	
	}
});
