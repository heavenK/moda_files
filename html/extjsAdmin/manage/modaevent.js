

/*美达用户管理菜单
*/
ModaEventPanel=function()
{	
	ModaEventPanel.superclass.constructor.call(this, {
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
   		text:"添加活动",
		icon:'./extjsAdmin/images/20070417191604705.gif',
   		listeners:{'click':function(){
			
			
				//parent.open ('?Controller=Admin&action=EventAdd','newwindow','height=800,width=880,top=0,left=0,toolbar=no,menubar=no,scrollbars=no, resizable=no,location=no, status=no') 
			window.showModalDialog('?Controller=ExtjsAdmin&action=EventAdd','addEvent','dialogWidth:880px;center:yes;help:yes;resizable:yes;status:yes')  
			/*
			  status：   {yes   |   no   |   1   |   0   }   是否有状态栏，对于非模式对话框窗口，默认值是“yes”；对于模式对话框窗口，默认值是   “no”。
			  center：   {yes   |   no   |   1   |   0   }   指定是否将对话框在桌面上居中，默认值是“yes”。   
			  edge：{   sunken   |   raised   }   指定对话框边框的样式。默认为raised。     
			  unadorned：{   yes   |   no   |   1   |   0   |   on   |   off   }   制定是否在对话框中显示边框。仅模式对话框可用。默认为no。     
			  dialogHide：{   yes   |   no   |   1   |   0   |   on   |   off   }   设置对话框在打印或者打印预览时是否为隐藏。仅模式对话框可用。默认为no。
			*/   		
			
			}}	
   		})); 
   
   
   this.root.appendChild(new Ext.tree.TreeNode({
   		text:"查看所有活动",
		icon:'./extjsAdmin/images/20070417191603968.gif',
   		listeners:{'click':function(){

			var panel=Ext.getCmp("modaEventList");
			if(!panel){
					panel=new modaEventList();

			}		
			
			main.openTab(panel);
			
				
   			}}	
   		})); 
   
   
}
Ext.extend(ModaEventPanel, Ext.tree.TreePanel,{});



/*查看所有榜
*/
modaEventList=Ext.extend(GaoP.Ext.CrudPanel,{
	id:"modaEventList",
	title:"查看所有活动",

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

	modaEventDel:function (value) {
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
								 url: '?Controller=ExtjsAdmin&action=EventDel',
								  params: {
									  
									  event_id : record.get("event_id")
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
			RankEdit(record);
			return;
		}
		
	}, 
	storeMapping:["event_id","home_img","index_img","header_img","title","dateline"],	

    initComponent : function(){
	
	
		this.store=new Ext.data.JsonStore({
			id:"Id",
			url: '?Controller=ExtjsAdmin&action=ModaEventList',
			root:"rows",
			totalProperty:"totalCount",
			remoteSort:true,  		
			fields:this.storeMapping});
	
	
	
	
  		this.cm=new Ext.grid.ColumnModel([
									  
			new Ext.grid.RowNumberer({header:"序",width:25,sortable:true}),	
			
			{        
			   header: "活动ID",
			   dataIndex: 'event_id',
			   width:50
			},{
			   header: "活动标题",
			   dataIndex: 'title',
			   width: 50
			},{
			   header: "最后修改时间",
			   dataIndex: 'dateline',
			   width: 50
			},{
			   header: "首页图片",
			   dataIndex: 'home_img',
			   width: 50,
			   renderer:this.showImage
			},{
			   header: "列表图片",
			   dataIndex: 'index_img',
			   width: 50,
			   renderer:this.showImage
			},{
			   header: "首页头图片",
			   dataIndex: 'header_img',
			   width: 50,
			   renderer:this.showImage
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
								window.showModalDialog('?Controller=ExtjsAdmin&action=EventEditView&event_id='+record.get("event_id"),'EventEdit','dialogWidth:880px;dialogHeight:610px;center:yes;help:yes;resizable:yes;status:yes');
								
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
					handler:this.modaEventDel,
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
	
		modaEventList.superclass.initComponent.call(this);
	}

	});

