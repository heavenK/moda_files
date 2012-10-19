


function RankMangeView(rank_id){//显示选择行

			
			//Ext.Msg.alert("提示",rank_id);
			
			
			var panel=Ext.getCmp("RankViewsInfo");
			
			
			if(!panel){
					panel=new RankViewsInfo({rank_id:rank_id});
					
			}		

			var rank_view = new Ext.Window({
				title: "展示管理",
				width: 1000,
				maximizable: true,
				autoHeight:true,
				modal: true,
				items: [panel],
				buttons: [{
					text: '关闭',
					handler: function(){ rank_view.close(); }
				}]

			});
			rank_view.show();
                    
}

var RankViewsInfo = function(config){
	this.config = config;
}
/*展示管理
*/
RankViewsInfo=Ext.extend(GaoP.Ext.CrudPanel,{
	id:"RankViewsInfo",
	title:"展示管理",
	height:500,
	
	
	
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
	sortByTicket:function (value) {
		
//		var record=this.grid.getSelectionModel().getSelected();
//		if(!record){
//			Ext.Msg.alert("提示","aaa");
//			return;
//		}
		
		  Ext.Ajax.request({
				 url: '?Controller=ExtjsAdmin&action=SortByTicket',
				  params: {
					  rank_id : this.rank_id
				  },
				 success:function(request){                   //发送成功的回调函数
					 var message = request.responseText;  //取得从JSP文件out.print(...)传来的文本
					 Ext.Msg.alert('信息',message);        //弹出对话框
				}
		  });
		  
		  this.store.reload();
		
		 //Ext.Msg.alert("提示","执行待审核操作");
		 
	},
	noTicketSort:function (value) {
		
		  Ext.Ajax.request({
				 url: '?Controller=ExtjsAdmin&action=NOTicketSort',
				  params: {
					  rank_id : this.rank_id
				  },
				 success:function(request){                   //发送成功的回调函数
					 var message = request.responseText;  //取得从JSP文件out.print(...)传来的文本
					 Ext.Msg.alert('信息',message);        //弹出对话框
				}
		  });
		  
		  this.store.reload();
		 
	},
	rankShowDel:function (value) {
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
							 url: '?Controller=ExtjsAdmin&action=RankShowDel',
							  params: {
								  rank_id : record.get("rank_id"),
								  show_id : record.get("show_id")
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
					  
		this.store.reload();
	},

	storeMapping:["ID","rank_id","rank_title","user_id","show_id","title","head_img","ticket","mingci","truename","username","views","truename"],	

    initComponent : function(){
	
	
		this.store=new Ext.data.JsonStore({
			id:"Id",
			url: '?Controller=ExtjsAdmin&action=RankShowManange&rank_id='+this.rank_id,
			root:"rows",
			totalProperty:"totalCount",
			remoteSort:true,  		
			fields:this.storeMapping});
	
	
  		this.cm=new Ext.grid.ColumnModel([
									  
			new Ext.grid.RowNumberer({header:"序",width:25,sortable:true}),	
			
			{
			   header: "姓名",
			   dataIndex: 'truename',
			   width: 50
			},{
			   header: "用户名",
			   dataIndex: 'username',
			   width: 50
			},{
			   header: "用户美达ID",
			   dataIndex: 'user_id',
			   width: 50
			},{
			   header: "展示ID",
			   dataIndex: 'show_id',
			   width: 50
			},{
			   header: "展示封面",
			   dataIndex: 'head_img',
			   width: 50,
			   renderer:this.showImage
			},{
			   header: "展示别名",
			   dataIndex: 'title',
			   width: 50
			},{
			   header: "展示浏览量",
			   dataIndex: 'views',
			   width: 50
			},{
			   header: "票数",
			   dataIndex: 'ticket',
			   width: 50
			},{
			   header: "排名",
			   dataIndex: 'mingci',
			   width: 50
			}
		]);  
	
	
		this.topbar = ['   ',
				 
				{    
					text: '添加展示',  
					pressed: true,           
					handler: function(){ 
					
						AddTopGirlShow(this.store,this.rank_id);						
					},
					scope:this
				},'   ',				
				{    
					text: '修改',  
					pressed: true,           
					handler: function(){ 
						var record=this.grid.getSelectionModel().getSelected();
						if(!record)
							Ext.Msg.alert("提示","请先选择要编辑的行!");
						else
							rankShowUserInfo(record,this.store) ;
					},
					scope:this
				},'   ',

				{    
					text: '按票排名',  
					pressed: true,           
					handler: this.sortByTicket,
					scope:this
				},'   ',
				{    
					text: '取消排名',  
					pressed: true,           
					handler:this.noTicketSort,
					scope:this
				},'   ',
				 {    
					text: '删除',  
					pressed: true,           
					handler: this.rankShowDel,
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
	
	
		RankViewsInfo.superclass.initComponent.call(this);
	}
	

	

	});


//
//function RankShowEdit(rank_id){
//
//			
//			var panel=Ext.getCmp("rankshow_form_info");
//			
//			
//			if(!panel){
//					panel=new rankShowUserInfo({rank_id:rank_id});
//			}		
//
//			var rankShow_view = new Ext.Window({
//				title: "展示管理",
//				width: 1000,
//				height: 260,
//				maximizable: true,
//				modal: true,
//				items: [panel],
//				buttons: [{
//					text: '关闭',
//					handler: function(){ rankShow_view.close(); }
//				}]
//
//			});
//			rankShow_view.show();
//
//                    
//                    
//}


function	rankShowUserInfo(record,store)
	{
		

									var rankshow_form_info = new Ext.FormPanel({//初始化表单面板
																	  
										id: 'rankshow_form_info',
										name: 'rankshow_form_info',
										labelWidth: 60, // 默认标签宽度板
										labelAlign: 'right',
										baseCls: 'x-plain',
										bodyStyle: 'padding:5px 5px 0',
										width: 350,
										
										frame: true,
										//border: false,
										defaults: {
											width: 300,
											height: 260
										},
										defaultType: 'textfield',//默认字段类型
										items: [{
											xtype: 'fieldset',
											title: '美达Top展示修改',
											defaults: {
												xtype: 'textfield',
												width: 200
											},
											items: [{
												name: 'rank_id',
												xtype:"hidden",
												value: record.get("rank_id")
											},{
												fieldLabel: '姓名',
												value: record.get("truename"),
												readOnly:true
											}, {
												name: 'show_id',
												fieldLabel: '展示ID',
												value: record.get("show_id"),
												readOnly:true
											}, {
												name: 'title',
												fieldLabel: '显示标题',
												value: record.get("title"),
											}, {
												name: 'ticket',
												fieldLabel: '票数',
												value: record.get("ticket")
											}, {
												name: 'views',
												fieldLabel: '浏览量',
												value: record.get("views")
											}]
										}],
										buttons: [{
											text: '修改',
											handler: function(){//添加网站
												if (form_info_widow.getComponent('rankshow_form_info').form.isValid()) {
													form_info_widow.getComponent('rankshow_form_info').form.submit({
														waitTitle: '请稍候',
														waitMsg: '正在提交数据,请稍候....',
														url: '?Controller=ExtjsAdmin&action=TopGirlShowEdit',
														method: 'POST',
														success:function(form, action){
															
																Ext.MessageBox.alert('提示', action.result.message);
																store.reload();
																form_info_widow.close();
															
													   },
														failure : function(form) {
																Ext.MessageBox.alert('警告',"未知错误");
															},
														scope:this
														
													})
												}
											}
										}, {
											text: '关闭',
											handler: function(){
												form_info_widow.close();
											}
										}]
									});
									
									var form_info_widow = new Ext.Window({
										title: "美达Top展示修改",
										width: 360,
										height: 350,
										modal: true,
										maximizable: true,
										items: rankshow_form_info
									});
				
				
									form_info_widow.show();
			
		}

function	RankEdit(record,store)
	{
		
		

			  var form_info = new Ext.form.FormPanel({
													 
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
						  {xtype:"hidden",
						   name:"rank_id",
						   value:record.get("rank_id")
						   },
						  {xtype:"textfield",
						   name:"rank_title",
						   fieldLabel:"标题",
						   value:record.get("rank_title")
						   },
						  {
						  xtype:"field",	  			
						  name:"img",
						  height:25,
						  fieldLabel:"标题图片",
						  inputType:"file"
						  },
			  //  			{
			  //				xtype:'panel', 
			  //				html:'<img src="modanewspic/img14431262590021.png"/>' 
			  //	  		},	   
						  {xtype:"datefield",
						   name:"overtime",
			 			   format: 'Y-m-d',
						   value:record.get("overtime"),
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
						  fieldLabel:"排行榜相关信息",
						  value:record.get("content")
						  }],
					  buttons:[{text:"提交",
								handler:save,
								scope:this},
								{text:"取消",
								 handler:function(){form_info_widow.close();},
								 scope:this  				   
								}]
				   });
									
					var form_info_widow = new Ext.Window({
						title: "美达榜修改",
						width: 830,
						height: 650,
						modal: true,
						maximizable: true,
						items: form_info
					});
				
					form_info_widow.show();
					
					
					
					function save()
					{	
					
						if (form_info_widow.getComponent('rank_form_add').form.isValid()) {
							form_info_widow.getComponent('rank_form_add').form.submit({
									waitMsg:'正在保存。。。',
									url:"?Controller=ExtjsAdmin&action=AddModaRank",
									method:'POST',
									success:function(form, action){
										
											Ext.MessageBox.alert('提示', action.result.message);
											store.reload();
											form_info_widow.close();
											//Ext.getCmp("main").closeTab(this);
										
								   },
									failure : function(form) {
											Ext.MessageBox.alert('警告',"未知错误");
										},
									scope:this
							});
						}
						else
						{
									Ext.MessageBox.alert('警告', "未知错误");
							}
					}		
		
					
					
			
		}





function	AddTopGirlShow(store,rank_id)
	{
		

									var form_info = new Ext.FormPanel({//初始化表单面板
																	  
										id: 'form_info',
										name: 'form_info',
										labelWidth: 60, // 默认标签宽度板
										labelAlign: 'right',
										baseCls: 'x-plain',
										bodyStyle: 'padding:5px 5px 0',
										width: 320,
										
										frame: true,
										//border: false,
										defaults: {
											width: 300
										},
										defaultType: 'textfield',//默认字段类型
										items: [{
											xtype: 'fieldset',
											title: '添加展示',
											defaults: {
												xtype: 'textfield',
												width: 200
											},
											items: [{
												name: 'rank_id',
												xtype:"hidden",
												value: rank_id
											}, {
												name: 'show_id',
												fieldLabel: '展示ID'
											}, {
												name: 'title',
												fieldLabel: '展示别名'
											}]
										}],
										buttons: [{
											text: '添加',
											handler: function(){//添加网站
												if (form_info_widow.getComponent('form_info').form.isValid()) {
													form_info_widow.getComponent('form_info').form.submit({
														waitTitle: '请稍候',
														waitMsg: '正在提交数据,请稍候....',
														url: '?Controller=ExtjsAdmin&action=AddTopGirlShow',
														method: 'POST',
														success:function(form, action){
															
																Ext.MessageBox.alert('提示', action.result.message);
																store.reload();
																form_info_widow.close();
																//Ext.getCmp("main").closeTab(this);
															
													   },
														failure : function(form, action) {
																Ext.MessageBox.alert('警告', action.result.message);
															},
														scope:this
														
													})
												}
											}
										}, {
											text: '取消',
											handler: function(){
												form_info_widow.close();
											}
										}]
									});
									
									var form_info_widow = new Ext.Window({
										title: "添加美女榜展示",
										width: 330,
										height: 175,
										modal: true,
										items: form_info
									});
				
				
									form_info_widow.show();
			
		}