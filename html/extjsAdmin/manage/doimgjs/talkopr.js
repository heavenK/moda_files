

function	modaTalkEdit(record)
	{
		

									var form_info = new Ext.Panel({//初始化表单面板
											autoLoad:{url:'/ubb/editor.html',scripts:true}
									});
									
									var form_info_widow = new Ext.Window({
										title: "美达榜修改",
										width: 360,
										height: 350,
										modal: true,
										maximizable: true,
										items: form_info
									});
				
				
									form_info_widow.show();
			
		}

