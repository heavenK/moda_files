//var removeTopicCategory,removeTopic;
//var topicCategoryLoader=Global.topicCategoryLoader;

/*美达用户管理菜单
*/
ModaTopGirlPanel=function()
{	
	ModaTopGirlPanel.superclass.constructor.call(this, {
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
   		text:"添加美达榜",
		icon:'./extjsAdmin/images/20070417191601330.gif',
		
   		listeners:{'click':function(){
   			var panel=Ext.getCmp("addNewRankPanel");
   			if(!panel){
   				panel=new AddNewRankPanel();
   			}
   			main.openTab(panel);
   			}}	
   		})); 
   
   
   this.root.appendChild(new Ext.tree.TreeNode({
   		text:"查看所有榜",
		icon:'./extjsAdmin/images/20070417191559432.gif',
		
   		listeners:{'click':function(){

			var panel=Ext.getCmp("ModaTopGirlList");
			if(!panel){
					panel=new ModaTopGirlList();

			}		
			
			main.openTab(panel);
			
				
   			}}	
   		})); 
   
}
Ext.extend(ModaTopGirlPanel, Ext.tree.TreePanel,{});



/*查看所有榜
*/
ModaTopGirlList=Ext.extend(GaoP.Ext.CrudPanel,{
	id:"ModaTopGirlList",
	title:"查看所有榜",


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
//	sortByTicket:function (value) {
//		
//		var record=this.grid.getSelectionModel().getSelected();
//		if(!record){
//			Ext.Msg.alert("提示","aaa");
//			return;
//		}
//		
//		  Ext.Ajax.request({
//				 url: '?Controller=ExtjsAdmin&action=SortByTicket',
//				  params: {
//					  rank_id : record.get("rank_id")
//				  },
//				 success:function(request){                   //发送成功的回调函数
//					 var message = request.responseText;  //取得从JSP文件out.print(...)传来的文本
//					 Ext.Msg.alert('信息',message);        //弹出对话框
//				}
//		  });
//		  
//		  this.store.reload();
//		 
//	},
	modaRankDel:function (value) {
		var record=this.grid.getSelectionModel().getSelected();
		if(!record){
			Ext.Msg.alert("提示","aaa");
			return;
		}
		store = this.store;
		Ext.Msg.prompt('提示!', '输入口令', function(btn, text){
			if (btn == 'ok'){
				if(text == "11")
				{
		
					  Ext.Ajax.request({
							 url: '?Controller=ExtjsAdmin&action=RankDel',
							  params: {
								  rank_id : record.get("rank_id")
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

	
    on_RankMangeView:function(obj){
		
		var record=this.grid.getSelectionModel().getSelected();
		if(!record){
			Ext.Msg.alert("提示","请先选择要编辑的行!");
			return;
		}
		else
		{
			//Ext.Msg.alert("提示",record.get("username"));
			RankMangeView(record.get("rank_id"));
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
			RankEdit(record,this.store);
			return;
		}
		
	}, 

	storeMapping:["rank_id","rank_title","created_on","overtime","content","img","first_mark","sec_mark","thr_mark",{name:"op",mapping:"rank_id"}
	],	

    initComponent : function(){
	
	
		this.store=new Ext.data.JsonStore({
			id:"Id",
			url: '?Controller=ExtjsAdmin&action=GetModaTopGirlList',
			root:"rows",
			totalProperty:"totalCount",
			remoteSort:true,  		
			fields:this.storeMapping});
	
	
	
	
  		this.cm=new Ext.grid.ColumnModel([
									  
			new Ext.grid.RowNumberer({header:"序",width:25,sortable:true}),	
			
			{        
			   header: "排行榜ID",
			   dataIndex: 'rank_id',
			   width:50
			},{
			   header: "排行榜名称",
			   dataIndex: 'rank_title',
			   width: 50
			},{
			   header: "相关信息",
			   dataIndex: 'content',
			   width: 50
			},{
			   header: "创建时间",
			   dataIndex: 'created_on',
			   width: 50
			},{
			   header: "结束日期",
			   dataIndex: 'overtime',
			   width: 50
			},{
			   header: "头图片",
			   dataIndex: 'img',
			   width: 50,
			   renderer:this.showImage
			},{
			   header: "第一标识",
			   dataIndex: 'first_mark',
			   width: 50,
			   renderer:this.showImage
			},{
			   header: "第二标识",
			   dataIndex: 'sec_mark',
			   width: 50,
			   renderer:this.showImage
			},{
			   header: "第三标识",
			   dataIndex: 'thr_mark',
			   width: 50,
			   renderer:this.showImage
			}
		]);  
	
	
		this.topbar = ['   ',
				 {    
					text: '展示管理',  
					pressed: true,           
					handler: this.on_RankMangeView,
					scope:this
				},'   ',
				{    
					text: '修改',  
					pressed: true,           
					handler: this.on_RankEdit,
					scope:this
				},'   ',
//				 {    
//					text: '按票排名',  
//					pressed: true,           
//					handler: this.sortByTicket,
//					scope:this
//				},'   ',
//				 {    
//					text: '取消排名',  
//					pressed: true,           
//					handler: this.refresh,
//					scope:this
//				},'   ',
				 {    
					text: '删除',  
					pressed: true,           
					handler: this.modaRankDel,
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
	
		ModaTopGirlList.superclass.initComponent.call(this);
	}

	});










/**
 * 书写新日志或添加日志
 */
AddNewRankPanel=Ext.extend(Ext.Panel,{
	id:"addNewRankPanel",
	title:"添加美达榜",
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
						
							Ext.MessageBox.alert('提示', action.result.message);
							var panel=Ext.getCmp("ModaTopGirlList");
							if(panel)
								panel.store.reload();
   							Ext.getCmp("main").closeTab(this);
					

/*
						Ext.Msg.alert("提示信息","数据保存成功!",function(){
							main.closeTab(this);
							var panel=Ext.getCmp("NoPassListPanel");
//							if(!panel)panel=new NoPassListManage();
//							main.openTab(panel);
//							panel.refresh();
							
							},this);
*/						
						
				   },
					failure : function(form, action) {
							Ext.MessageBox.alert('警告', action.result.message);
						},
					scope:this
			});
		}
		else
		{
					Ext.MessageBox.alert('警告', "未知错误");
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
			 format: 'Y-m-d',
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
	AddNewRankPanel.superclass.initComponent.call(this);
	this.fp=this.createFormPanel();
	this.add(this.fp);	
	}
});
