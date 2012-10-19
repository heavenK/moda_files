
/*添加美达用户
*/
function addNewModaUser(store){//
				
                    Ext.form.Field.prototype.msgTarget = 'side';//设置提示信息位置为边上
                    var window_form_add = new Ext.FormPanel({//初始化表单面板
                        id: 'window_form_add',
                        name: 'window_form_add',
                        labelWidth: 60, // 默认标签宽度板
                        labelAlign: 'right',
                        width: 320,
                        frame: true,
                        defaultType: 'textfield',//默认字段类型
                        items: [{
                            xtype: 'fieldset',
                            title: '添加美达用户',
                            defaults: {
                                xtype: 'textfield',
                                width: 200
                            },
                            items: [{
                                name: 'username',
                                fieldLabel: '用户名',
                                allowBlank: false,
                                blankText: '用户名不能为空'
                            }, {
                                name: 'truename',
                                fieldLabel: '真实姓名',
                                allowBlank: false,
                                blankText: '姓名不能为空'
                            }]
                        }],
                        buttons: [{
                            text: '确定',
                            handler: function(){
                                if (add_widow.getComponent('window_form_add').form.isValid()) {
                                    add_widow.getComponent('window_form_add').form.submit({
                                        waitTitle: '请稍候',
                                        waitMsg: '正在提交数据,请稍候....',
                                        url: '?Controller=ExtjsAdmin&action=AddModaUserByAdmin',
                                        method: 'POST',
                                        success: function(form, action){
                                            var Result = action.result.success;
                                                Ext.MessageBox.alert('提示', action.result.message);
												add_widow.close();
												store.reload();
                                        },
                                        failure: function(form, action){
                                            Ext.MessageBox.alert('警告', action.result.message);
                                        }
                                        
                                    })
                                }
                            }
                        }, {
                            text: '取消',
                            handler: function(){
								add_widow.close();
                            }
                        }]
                    });
                    var add_widow = new Ext.Window({
                        title: "MODA-添加美达用户",
																width: 334,
										height: 175,
						//autoHeight:true,
                        modal: true,
                        items: window_form_add
                    });
                    add_widow.show();
                    
}


function	perInfo(record,store)
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
											width: 300,
											height: 260
										},
										defaultType: 'textfield',//默认字段类型
										items: [{
											xtype: 'fieldset',
											title: record.get("truename")+'-私人信息修改',
											defaults: {
												xtype: 'textfield',
												width: 200
											},
											items: [{
												name: ' user_id',
												value: record.get("user_id"),
												xtype: 'hidden'
											},{
												name: 'truename',
												fieldLabel: '姓名',
												value: record.get("truename")
											}, {
												fieldLabel: '用户名',
												value: record.get("username"),
												readOnly:true
											}, {
												fieldLabel: '昵称',
												value: record.get("nickname"),
												readOnly:true												
											}, {
												name: 'mobile',
												fieldLabel: '联系电话',
												value: record.get("mobile")
											}, {
												name: 'qq',
												fieldLabel: 'QQ号',
												value: record.get("qq")
											}, {
												name: 'email',
												fieldLabel: 'EMAIL地址',
												value: record.get("email")
											}, {
												name: 'height',
												fieldLabel: '身高',
												value: record.get("height")
											}, {
												name: 'weight',
												fieldLabel: '体重',
												value: record.get("weight")
											}]
										}],
										buttons: [{
											text: '修改',
											handler: function(){
												if (form_info_widow.getComponent('form_info').form.isValid()) {
													form_info_widow.getComponent('form_info').form.submit({
														waitTitle: '请稍候',
														waitMsg: '正在提交数据,请稍候....',
														url: '?Controller=ExtjsAdmin&action=ModaerPerInfoEdit',
														method: 'POST',
														success: function(form, action){
															var Result = action.result.success;
																Ext.MessageBox.alert('提示', action.result.message);
																form_info_widow.close();
																store.reload();
														},
														failure: function(form, action){
															Ext.MessageBox.alert('提示', action.result.message);
															store.reload();
														}
														
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
										title: "MODA-私人信息",
										width: 330,
										height: 350,
										modal: true,
										maximizable: true,
										items: form_info
									});
				
				
									form_info_widow.show();

			
		}





                   

function	modaUserInfo(record,store)
	{
		

									var form_info = new Ext.FormPanel({//初始化表单面板
																	  
										id: 'form_info',
										name: 'form_info',
										labelWidth: 60, // 默认标签宽度板
										labelAlign: 'right',
										width: 320,
										frame: true,
										defaultType: 'textfield',//默认字段类型
										items: [{
											xtype: 'fieldset',
											title: record.get("truename")+'-美达信息',
											defaults: {
												xtype: 'textfield',
												width: 200
											},
											items: [{
												name: ' user_id',
												value: record.get("user_id"),
												xtype: 'hidden'
											},{
												name: 'truename',
												fieldLabel: '姓名',
												value: record.get("truename"),
												allowBlank: false,
                               					blankText: '姓名不能为空'

											}, {
												name: 'pageviews',
												fieldLabel: '页面显示浏览量',
												value: record.get("pageviews"),
												allowBlank: false,
                               					blankText: '页面显示浏览量不能为空'
											}, {
												name: 'show_id',
												fieldLabel: '前置展示ID',
												value: record.get("show_id"),
												allowBlank: false,
                               					blankText: '前置展示ID不能为空'
											}]
										}],
										buttons: [{
											text: '修改',
											handler: function(){
												if (form_info_widow.getComponent('form_info').form.isValid()) {
													form_info_widow.getComponent('form_info').form.submit({
														waitTitle: '请稍候',
														waitMsg: '正在提交数据,请稍候....',
														url: '?Controller=ExtjsAdmin&action=ModaerInfoEdit',
														method: 'POST',
														success: function(form, action){
															var Result = action.result.success;
															Ext.MessageBox.alert('提示', action.result.message);
															form_info_widow.close();
															store.reload();
														},
														failure: function(form, action){
															Ext.MessageBox.alert('提示', action.result.message);
															store.reload();
														}
														
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
										title: "MODA-美达用户修改",
										modal: true,
										maximizable: true,
										items: form_info
									});
				
				
									form_info_widow.show();

			
		}



/*展示管理
*/

function ModaerShowManage(user_id,truename){//显示选择行

			
			var panel=Ext.getCmp("ModaerShowsInfo");
			
			
			if(!panel){
					panel=new ModaerShowsInfo({user_id:user_id});
					
			}		

			var rank_view = new Ext.Window({
				title: truename+"-展示管理",
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

var ModaerShowsInfo = function(config){
	this.config = config;
}
/*展示管理
*/
ModaerShowsInfo=Ext.extend(GaoP.Ext.CrudPanel,{
	id:"ModaerShowsInfo",
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

	inttochar:function (value){
		
		if(value == 1)
			return "是";
		else
			return "NO";
         //return "<a href='mailto:" + value + "'>" + value + "</a>";
	},


	storeMapping:["show_id","user_id","title","username","created_on","show_img","temp_img","text","show_face","views","discuss_count","public","available","main"],	

    initComponent : function(){
	
	
		this.store=new Ext.data.JsonStore({
			id:"Id",
			url: '?Controller=ExtjsAdmin&action=ModaerShowManange&user_id='+this.user_id,
			root:"rows",
			totalProperty:"totalCount",
			remoteSort:true,  		
			fields:this.storeMapping});
	
	
  		this.cm=new Ext.grid.ColumnModel([
									  
			new Ext.grid.RowNumberer({header:"序",width:25,sortable:true}),	
			
			{
			   header: "展示ID",
			   dataIndex: 'show_id',
			   width: 50
			},{
			   header: "标题",
			   dataIndex: 'title',
			   width: 50
			},{
			   header: "创建时间",
			   dataIndex: 'created_on',
			   width: 50
			},{
			   header: "封面图片",
			   dataIndex: 'show_img',
			   width: 50,
			   renderer:this.showImage
			},{
			   header: "简介",
			   dataIndex: 'text',
			   width: 50
			},{
			   header: "浏览量",
			   dataIndex: 'views',
			   width: 50
			},{
			   header: "评论数",
			   dataIndex: 'discuss_count',
			   width: 50
			},{
			   header: "公开",
			   dataIndex: 'public',
			   width: 50,
			   renderer:this.inttochar
			},{
			   header: "前置展示",
			   dataIndex: 'main',
			   width: 50,
			   renderer:this.inttochar
			}
		]);  
	
	
		this.topbar = ['   ',
				 
				{    
					text: '修改',  
					pressed: true,           
					handler: function(){ 
						var record=this.grid.getSelectionModel().getSelected();
						if(!record)
							Ext.Msg.alert("提示","请先选择要编辑的行!");
						else
							ModaerShowEdit(record,this.store) ;
					},
					scope:this
				},'   ',

				{    
					text: '删除',  
					pressed: true,           
					handler: this.removeData,
					scope:this
				},'   ',
				 {    
					text: '刷新',  
					pressed: true,           
					handler: this.refresh,
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
	
	
		ModaerShowsInfo.superclass.initComponent.call(this);
	}
	

	

	});


function	ModaerShowEdit(record,store)
	{
		

									var rankshow_form_info = new Ext.FormPanel({//初始化表单面板
																	  
										id: 'rankshow_form_info',
										name: 'rankshow_form_info',
										labelWidth: 60, // 默认标签宽度板
										labelAlign: 'right',
										baseCls: 'x-plain',
										bodyStyle: 'padding:5px 5px 0',
										width: 320,
										
										frame: true,
										defaults: {
											width: 300,
											height: 260
										},
										defaultType: 'textfield',//默认字段类型
										items: [{
											xtype: 'fieldset',
											title: "ID号"+record.get("show_id")+'展示修改',
											defaults: {
												xtype: 'textfield',
												width: 200
											},
											items: [{
												name: 'show_id',
												xtype:"hidden",
												value: record.get("show_id")
											},{
												name: 'user_id',
												xtype:"hidden",
												value: record.get("user_id")
											},{
												name: 'title',
												fieldLabel: '标题',
												value: record.get("title")
											}, {
												name: 'views',
												fieldLabel: '浏览量',
												value: record.get("views")
											}, {
												name: 'public',
												fieldLabel: '公开',
												xtype:"checkbox",
												inputValue:1
											}, {
												name: 'main',
												fieldLabel: '前置',
												xtype:"checkbox",
												inputValue:1
											}, {
												name: 'text',
												fieldLabel: '简介',
												xtype: 'htmleditor',
												value: record.get("text")
											}]
										}],
										
										
										buttons: [{
											text: '修改',
											handler: function(){
												if (form_info_widow.getComponent('rankshow_form_info').form.isValid()) {
													form_info_widow.getComponent('rankshow_form_info').form.submit({
														waitTitle: '请稍候',
														waitMsg: '正在提交数据,请稍候....',
														url: '?Controller=ExtjsAdmin&action=ModaerShowEdit',
														method: 'POST',
														success:function(form, action){
															
																Ext.MessageBox.alert('提示', action.result.message);
																store.reload();
																form_info_widow.close();
															
													   },
														failure : function(form, action) {
																Ext.MessageBox.alert('警告',action.result.message);
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
										title: "展示修改",
										width: 330,
										height: 350,
										modal: true,
										items: rankshow_form_info
									});
				
				
									form_info_widow.show();
									
									function setFormSit(){ 
											if(record.get("public") == 1)
												form_info_widow.getComponent('rankshow_form_info').form.findField('public').setValue(true);
											if(record.get("main") == 1)
												form_info_widow.getComponent('rankshow_form_info').form.findField('main').setValue(true);
									};
									
									setFormSit();
		}


