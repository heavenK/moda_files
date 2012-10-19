

/*美达用户管理菜单
*/
ModaTalkPanel=function()
{	
	ModaTalkPanel.superclass.constructor.call(this, {
        autoScroll:true,
        animate:true,
        border:false,
        rootVisible:false,
        root:new Ext.tree.TreeNode({
        text: '用户管理菜单',
        draggable:false,
       	expanded:true
   	 })        
    });
   this.root.appendChild(new Ext.tree.TreeNode({
   		text:"添加访谈",
		icon:'./extjsAdmin/images/20070417191601340.gif',
   		listeners:{'click':function(){
				window.showModalDialog('?Controller=ExtjsAdmin&action=ModaNews','ModaNews','dialogWidth:880px;dialogHeight:700px;center:yes;help:yes;resizable:yes;status:yes')  
			
   		}}	
   		})); 
   
   
   this.root.appendChild(new Ext.tree.TreeNode({
   		text:"查看所有访谈",
		icon:'./extjsAdmin/images/20070417191603968.gif',
   		listeners:{'click':function(){

			var panel=Ext.getCmp("ModaTalkList");
			if(!panel){
					panel=new ModaTalkList();

			}		
			
			main.openTab(panel);
			
				
   			}}	
   		})); 
   
//   this.root.appendChild(new Ext.tree.TreeNode({
//   		text:"分类管理",
//   		listeners:{'click':function(){
//   			var panel=Ext.getCmp("topicCategoryPanel");
//   			if(!panel){
//   				panel=new TopicCategoryManage();
//   				//removeTopicCategory=function(id){panel.removeCategory(id);};
//   			}
//   			main.openTab(panel);
//   			}
//   		}	
//   		}));
   
}
Ext.extend(ModaTalkPanel, Ext.tree.TreePanel,{});



/**
 * 书写新日志或添加日志
 */
//AddModaTalk=Ext.extend(Ext.Panel,{
//	id:"AddModaTalk",
//	title:"添加美达访谈",
//	closable: true,
//  	autoScroll:true,
//  	layout:"fit",  			
//	save:function()
//	{	
//	
//	
//		var id=this.fp.form.findField("Id").getValue();
//		if (this.getComponent('rank_form_add').form.isValid()) {
//			this.fp.form.submit({
//					waitMsg:'正在保存。。。',
//					url:"?Controller=ExtjsAdmin&action=AddModaRank",
//					method:'POST',
//					success:function(form, action){
//						
//						//Ext.MessageBox.alert('警告', action.result.message,);
//
////						var main=Ext.getCmp("main");
//
//						Ext.Msg.alert("提示信息","数据保存成功!",function(){
//							main.closeTab(this);
//							var panel=Ext.getCmp("NoPassListPanel");
//							if(!panel)panel=new NoPassListManage();
//							main.openTab(panel);
//							panel.refresh();},this);
//						
//				   },
//					failure : function(form, action) {
//							
//							Ext.MessageBox.alert('警告', "失败");
//	
//						},
//					scope:this
//			});
//		}
//		else
//		{
//					Ext.MessageBox.alert('警告', "失败");
//			}
//	},
//	createFormPanel:function(){
//		return  new Ext.form.FormPanel({
//									   
//                        id: 'rank_form_add',
//                        name: 'rank_form_add',
//		buttonAlign:"center",
//		labelAlign:"right",
//		bodyStyle:'padding:25px',
//		defaults:{width:650},
//		frame:false,
//		fileUpload:true,
//		bodyBorder:false,
//		border:true,
//		labelWidth:60,
//
//		items:[	
//			{xtype:"hidden",name:"Id"},	   
//		    {xtype:"textfield",
//			 name:"rank_title",
//			 fieldLabel:"标题"},
//  			{
//  			xtype:"field",	  			
//	  		name:"img",
//	  		height:25,
//	  		fieldLabel:"标题图片",
//	  		inputType:"file"
//	  		},	   
//		    {xtype:"datefield",
//			 name:"overtime",
//			 fieldLabel:"结束时间"},
//  			{
//  			xtype:"field",	  			
//	  		name:"first_mark",
//	  		height:25,
//	  		fieldLabel:"第一名标识",
//	  		inputType:"file"
//	  		},
//  			{
//  			xtype:"field",	  			
//	  		name:"sec_mark",
//	  		height:25,
//	  		fieldLabel:"第二名标识",
//	  		inputType:"file"
//	  		},
//  			{
//  			xtype:"field",	  			
//	  		name:"thr_mark",
//	  		height:25,
//	  		fieldLabel:"第三名标识",
//	  		inputType:"file"
//	  		},
//  			{
//  			xtype:"htmleditor",
//  			height:300,
//	  		name:"content",
//	  		fieldLabel:"排行榜相关信息"
//	  		}],
//  		buttons:[{text:"提交",
//  				  handler:this.save,
//  				  scope:this},
//  				  {text:"清空",
//  				   handler:function(){this.fp.form.reset();},
//  				   scope:this  				   
//  				  },
//  				  {text:"取消",
//  				   handler:function(){Ext.getCmp("main").closeTab(this);},
//  				   scope:this  				   
//  				  }]
//   	 });
//   	 },
//	initComponent : function(){
//	AddModaTalk.superclass.initComponent.call(this);
//	this.fp=this.createFormPanel();
//	this.add(this.fp);	
//	}
//});



/*查看所有榜
*/
ModaTalkList=Ext.extend(GaoP.Ext.CrudPanel,{
	id:"ModaTalkList",
	title:"查看所有访谈",

	showHeight:function (value) {
		return value+"/CM";
		
	},
	
	showImage:function (value) {
		return '<div class="thumb-wrap" ><div class="thumb"><img src="'+value+'" ></div></div>';
		
	},
		
    operationRender:function(obj){
		return !obj||obj=="-1"?"":"<a href='javascript:showDetail("+obj+")'>[详图]</a>";
		
	}, 
	
	
	showDate:function (value) {
   		 return new Date(parseInt(value) * 1000).toLocaleDateString().replace("年", "/").replace("月", "/").replace("日", ""); 
	},

//	showEmail:function (value){
//         return "<a href='mailto:" + value + "'>" + value + "</a>";
//	},
	modaNewsDel:function (value) {
		  var record=this.grid.getSelectionModel().getSelected();
		  if(!record){
			  Ext.Msg.alert("提示","aaa");
			  return;
		  }
		store = this.store;  
		Ext.Msg.prompt('提示!', '输入口令', function(btn, text){
			if (btn == 'ok'){
				if(text == "我爱美达")
				{
					  Ext.Ajax.request({
							 url: '?Controller=ExtjsAdmin&action=TalkDel',
							  params: {
								  
								  news_id : record.get("news_id")
							  },
							 success:function(request){                   //发送成功的回调函数
								 var message = request.responseText;  //取得从JSP文件out.print(...)传来的文本
								 Ext.Msg.alert('信息',message);        //弹出对话框
					  			 store.reload();
							}
					  });
						
					}
				else
				{
					Ext.Msg.alert('SORRY!', '口令错误');
					}
			}
		});
		  
		  
		  
		 
	},


	
    on_talkEdit:function(obj){
		
		var record=this.grid.getSelectionModel().getSelected();
		if(!record){
			Ext.Msg.alert("提示","请先选择要编辑的行!");
			return;
		}
		else
		{
			//Ext.Msg.alert("提示",record.get("username"));
			modaTalkEdit(record);
			return;
		}
		
	}, 
    on_RankEdit:function(obj){
		
		var record=this.grid.getSelectionModel().getSelected();
		if(!record){
			Ext.Msg.alert("提示","请先选择要编辑的行!");
			return;
		}
		else
		{
			//Ext.Msg.alert("提示",record.get("username"));
			RankEdit(record);
			return;
		}
		
	}, 

	storeMapping:["news_id","news_st","news_title","news_connet","created_on","url","img_url","content"],	

    initComponent : function(){
	
	
		this.store=new Ext.data.JsonStore({
			id:"Id",
			url: '?Controller=ExtjsAdmin&action=ModaTalkList',
			root:"rows",
			totalProperty:"totalCount",
			remoteSort:true,  		
			fields:this.storeMapping});
	
	
	
	
  		this.cm=new Ext.grid.ColumnModel([
									  
			new Ext.grid.RowNumberer({header:"序",width:25,sortable:true}),	
			
			{        
			   header: "访谈ID",
			   dataIndex: 'news_id',
			   width:50
			},{
			   header: "访谈标题",
			   dataIndex: 'news_title',
			   width: 50
			},{
			   header: "访谈副标题",
			   dataIndex: 'news_st',
			   width: 50
			},{
			   header: "访谈首页图",
			   dataIndex: 'url',
			   width: 50,
			   renderer:this.showImage
			},{
			   header: "访谈大图",
			   dataIndex: 'img_url',
			   width: 50,
			   renderer:this.showImage
			},{
			   header: "创建时间",
			   dataIndex: 'created_on',
			   width: 50
			}
		]);  
	
	
		this.topbar = ['   ',
				 {    
					text: '修改',  
					pressed: true,           
					handler: function(){
							var record=this.grid.getSelectionModel().getSelected();
							if(!record){
								Ext.Msg.alert("提示","请先选择要编辑的行!");
								return;
							}
							else
							{
//								window.open ('?controller=Admin&action=EditNews&id='+record.get("news_id"),'newwindow','height=700,width=880,top=0,left=0,toolbar=no,menubar=no,scrollbars=no, resizable=no,location=no, status=no') 
								window.showModalDialog('?controller=ExtjsAdmin&action=EditNews&news_id='+record.get("news_id"),'EditNews','dialogWidth:880px;dialogHeight:700px;center:yes;help:yes;resizable:yes;status:yes')  
								
								
								return;
							}
						},
					scope:this
				},'   ',
				 {    
					text: '刷新',  
					pressed: true,           
					handler: this.refresh,
					scope:this
				},'   ',
				{    
					text: '删除',  
					pressed: true,           
					handler: this.modaNewsDel,
					scope:this
				}
				,new Ext.Toolbar.Fill(),
				'Search: ',
				{    
					xtype:"textfield",
					width:100,
					pressed: true, 
					scope:this
				},'   ',
				{    
					text: '查询',   
					pressed: true,           
					handler: this.search,
					scope:this
				},'   '
			];
	
		ModaTalkList.superclass.initComponent.call(this);
	}

	});

