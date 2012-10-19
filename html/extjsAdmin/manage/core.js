/**
 * 用户信息列表
 */
Ext.namespace("GaoP.Ext");

GaoP.Ext.CrudPanel=Ext.extend(Ext.Panel,{
	closable: true,
  	autoScroll:true,
  	layout:"fit",
  	gridViewConfig:{},   
	
  	linkRenderer:function(v)
  	{
  		if(!v)return "";
  		else return String.format("<a href='{0}' target='_blank'>{0}</a>",v);
  	},
	dateRender:function(format)
    {
    	format=format||"Y-m-d h:i";
    	return Ext.util.Format.dateRenderer(format);
    },
    search:function()
    {   
    	this.refresh(); 
    },
    refresh:function()
    {
    	this.store.removeAll();
   		this.store.reload();
    },    
    initWin:function(width,height,title)
    {
    	var win=new Ext.Window({
			width:width,
			height:height,
			buttonAlign:"center",
			title:title,
			modal:true,
			shadow:true,
			closeAction:"hide",
			items:[this.fp],
			buttons:[{text:"保存",
					  handler:this.save,
					  scope:this},
					  {text:"清空",
					   handler:this.reset,
					   scope:this},
					  {text:"取消",
					   handler:this.closeWin,
					   scope:this}
					   	]					  
		});
		return win;
    },
    showWin:function()
	{	
		if(!this.win){
			if(!this.fp){
				this.fp=this.createForm();
			}
		this.win=this.createWin();
		this.win.on("close",function(){this.win=null;this.fp=null;},this);
		}
		this.win.show();
	},
	create:function()
	{
		this.showWin();
		this.reset();
	},
	save:function()
	{
		var id=this.fp.form.findField("Id").getValue();		
		this.fp.form.submit({
				waitMsg:'正在保存。。。',
	            //url:this.baseUrl+"?cmd="+(id?"Update":"Save"),
	            method:'POST',
	            success:function(){
	           	this.closeWin();
	           	this.store.reload();          	
	            },
	            scope:this
		});	
	},
	reset:function()
	{
	if(this.win)this.fp.form.reset();
	},
	closeWin:function()
	{
		if(this.win)this.win.close();
		this.win=null;
		this.fp=null;
	},
	edit:function()
	{
//		var record=this.grid.getSelectionModel().getSelected();
//		if(!record){
//			Ext.Msg.alert("提示","请先选择要编辑的行!");
//			return;
//		}
//	    var id=record.get("id");
//	    this.showWin();
//	    this.fp.form.loadRecord(record); 
	},	
	removeData:function()
	{
			var record=this.grid.getSelectionModel().getSelected();
			if(!record){
				Ext.Msg.alert("提示","请选择要删除的行!");
				return;
			}
			var m=Ext.MessageBox.confirm("删除提示","是否真的要删除数据？",function(ret){
			if(ret=="yes"){
			  Ext.Ajax.request({
	            //url:this.baseUrl+'?cmd=Remove',
	            params:{
	                'Id':record.get("Id")
	            },
	            method:'POST',
	            success:function(response){
	            var r=Ext.decode(response.responseText);
	            if(!r.success)Ext.Msg.alert("提示信息","数据删除失败，由以下原因所致：<br/>"+(r.errors.msg?r.errors.msg:"未知原因"));
	            else{
	            Ext.Msg.alert("提示信息","成功删除数据!",function(){
	            this.store.reload();	
	            },this);
	            }
	            },
	            scope:this
			  });
			}},this);
	},
    initComponent : function(){   

//       this.store=new Ext.data.JsonStore({
//			url: '/_grid_json.php',
//			root:"results",
//			totalProperty:"total",
//			remoteSort:true,  		
//			fields:this.storeMapping});


//		this.store=new Ext.data.JsonStore({
//			id:"Id",
//			url: '?Controller=ExtjsAdmin&action=test',
//			root:"rows",
//			totalProperty:"totalCount",
//			remoteSort:true,  		
//			fields:this.storeMapping});
  
  
      	this.store.paramNames.sort="OrderBy";
	 	this.store.paramNames.dir="OrderType";	  
      	this.cm.defaultSortable=true; 
		
        GaoP.Ext.CrudPanel.superclass.initComponent.call(this);
	  
        var viewConfig=Ext.apply({forceFit:true},this.gridViewConfig); 
		
        this.grid=new Ext.grid.GridPanel({
			store: this.store,
			cm: this.cm,
			trackMouseOver:false,    
			loadMask: true,
			viewConfig:viewConfig,
			tbar:this.topbar,
			
//			tbar: ['   ',
//				 {    
//					text: '添加',  
//					pressed: true,           
//					handler: this.create,
//					scope:this
//				},'   ',
//				{    
//					text: '修改',  
//					pressed: true,            
//					handler: this.edit,
//					scope:this
//				},'   ',
//				{    
//					text: '删除',  
//					pressed: true,           
//					handler: this.removeData,
//					scope:this
//				},'   ',
//				 {    
//					text: '刷新',  
//					pressed: true,           
//					handler: this.refresh,
//					scope:this
//				}
//				,new Ext.Toolbar.Fill(),
//				'Search: ',
//				{    
//					xtype:"textfield",
//					width:100,
//					pressed: true, 
//					scope:this
//				},
//				{    
//					text: '查询',   
//					pressed: true,           
//					handler: this.search,
//					scope:this
//				},'   '
//			],
			bbar: new Ext.PagingToolbar({
				pageSize: 10,
				store: this.store,
				displayInfo: true,
				displayMsg: 'Displaying topics {0} - {1} of {2}',
				emptyMsg: "No topics to display"
			})
   		});   		   		
   		this.grid.on("celldblclick",this.edit,this);       
   		this.add(this.grid);
        this.store.load();				
        }
});


/**
 * 	树状下拉框
 */
GaoP.Ext.TreeComboField=Ext.extend(Ext.form.TriggerField,{
	 valueField:"Id",
	 displayField:"Name",
	 haveShow:false,
	 editable:false,
	 onTriggerClick : function(){
	 	if(!this.tree.rendered)
	 	{
	 	this.treeId = Ext.id();
        var panel = document.createElement('div');
       	panel.id = this.treeId;
       	this.getEl().dom.parentNode.appendChild(panel);
	 	this.tree.render(this.treeId);
	 	this.tree.setWidth(this.width);
	 	this.tree.getEl().alignTo(this.getEl(), "tl-bl");	
	 	}	 
	 	this.tree.show();
	 },
	 initComponent : function(){
        GaoP.Ext.TreeComboField.superclass.initComponent.call(this);
       
	 },
	/* tree:new Ext.tree.TreePanel({
 			root:new Ext.tree.AsyncTreeNode({
 				id:"root",
   				text:"相册分类",   	
   				expanded:true,
   				loader:Global.topicCategoryLoader
   				})
 			}),*/
	 onRender : function(ct, position){
	 	GaoP.Ext.TreeComboField.superclass.onRender.call(this, ct, position);	 	
 		this.tree.on("click",this.choice,this);
 		//this.tree.on("dblclick",function(){this.tree.hide();},this);
 		if(this.hiddenName){
            this.hiddenField = this.el.insertSibling({tag:'input', type:'hidden', name: this.hiddenName, id: (this.hiddenId||this.hiddenName)},
                    'before', true);
            this.hiddenField.value =
                this.hiddenValue !== undefined ? this.hiddenValue :this.value !== undefined ? this.value : '';
            this.el.dom.removeAttribute('name');
        }
         if(!this.editable){
            this.editable = true;
            this.setEditable(false);
        }
	 },
	 getValue : function(){       
       return typeof this.value != 'undefined' ? this.value : '';        
    },
	clearValue : function(){
        if(this.hiddenField){
            this.hiddenField.value = '';
        }
        this.setRawValue('');
        this.lastSelectionText = '';
        this.applyEmptyText();
    },
    readPropertyValue:function(obj,p)
    {
    	var v=null;
    	for(var o in obj)
    	{
    		if(o==p)v=obj[o];
    	}
    	return v;
    },
    setValue : function(obj){   
    	if(!obj){
    		this.clearValue();
    		return;
    	}
    	var v=obj;	
        var text = v;
        var value=this.valueField||this.displayField;
       if(typeof v=="object" && this.readPropertyValue(obj,value)){
        	text=obj[this.displayField||this.valueField];
        	v=obj[value];      	
        }
       	var node = this.tree.getNodeById(v);      
        if(node){
                text = node.text;
            }else if(this.valueNotFoundText !== undefined){
                text = this.valueNotFoundText;
            }
        this.lastSelectionText = text;
        if(this.hiddenField){
            this.hiddenField.value = v;
        }
       GaoP.Ext.TreeComboField.superclass.setValue.call(this, text);
       this.value = v;
    },
     setEditable : function(value){
        if(value == this.editable){
            return;
        }
        this.editable = value;
        if(!value){
            this.el.dom.setAttribute('readOnly', true);
            this.el.on('mousedown', this.onTriggerClick,  this);
            this.el.addClass('x-combo-noedit');
        }else{
            this.el.dom.setAttribute('readOnly', false);
            this.el.un('mousedown', this.onTriggerClick,  this);
            this.el.removeClass('x-combo-noedit');
        }
    },
	choice:function(node,eventObject)
	{
	if(node.id!="root")	this.setValue(node.id);
	else this.clearValue();
	this.tree.hide();
	},		
	onDestroy : function(){
    if(this.tree.rendered){
       this.tree.getEl().remove();
      }
    GaoP.Ext.TreeComboField.superclass.onDestroy.call(this);
    }
});
Ext.reg('treecombo', GaoP.Ext.TreeComboField);


