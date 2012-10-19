//Global = {
//	topicCategoryLoader : new Ext.tree.TreeLoader( {
//		url : "./extjsAdmin/manage/type_topic.php",
//		listeners : {
//			'beforeload' : function(treeLoader, node) {
//				treeLoader.baseParams.id = (node.id != 'root' ? node.id : "");
//			}
//		}
//	}),
//	UserCheckInfoLoader : new Ext.tree.TreeLoader( {
//		url : "./extjsAdmin/manage/type_topic.php",
//		listeners : {
//			'beforeload' : function(treeLoader, node) {
//				treeLoader.baseParams.id = (node.id != 'root' ? node.id : "");
//			}
//		}
//	}),
//	albumCategoryLoader : new Ext.tree.TreeLoader( {
//		url : "./manage/type_album.php",
//		listeners : {
//			'beforeload' : function(treeLoader, node) {
//				treeLoader.baseParams.id = (node.id != 'root' ? node.id : "");
//			}
//		}
//	})
//};


/*左侧主菜单
*/
MenuPanel = function() {
	MenuPanel.superclass.constructor.call(this, {
		id : 'menu',
		region : 'west',
		title : '<span style="color:#333"><img src="./extjsAdmin/images/20070417191604728.gif">系统菜单</span>',
		split : true,
		width : 200,
		minSize : 175,
		maxSize : 500,
		collapsible : true,
		margins : '0 0 5 5',
		cmargins : '0 0 0 0',		
		layout : "",
		defaults:{collapsed:false},
		layoutConfig : {
				titleCollapse : true,
				animate : true
			},
			items : [ {
				title : '<span><img src="./extjsAdmin/images/user.gif">美达用户管理</span>',
				items : [new ModaUserPanel()]
			}, {
				title : '<span><img src="./extjsAdmin/images/20070417191601429.gif">美达TOP榜</span>',
				items : [new ModaTopGirlPanel()]
			}, {
				title : '<span><img src="./extjsAdmin/images/20070417191602248.gif">美达访谈管理</span>',
				items : [new ModaTalkPanel()]
			}, {
				title : '<span><img src="./extjsAdmin/images/20070417191603848.gif">美达活动管理</span>',
				items : [new ModaEventPanel()]
			}
			
			, {
				title : "美达系统设置",
				items : [new ModaIndexPanel()]
			}
			
			]
		});
};
Ext.extend(MenuPanel, Ext.Panel);

/*主窗口
*/
MainPanel = function() {
	this.openTab = function(panel, id) {
		var o = (typeof panel == "string" ? panel : id || panel.id);
		var tab = this.getComponent(o);		
		if (tab) {
			this.setActiveTab(tab);
		} else if(typeof panel!="string"){
			panel.id = o;
			var p = this.add(panel);
			this.setActiveTab(p);
		}
	};
	this.closeTab = function(panel, id) {
		var o = (typeof panel == "string" ? panel : id || panel.id);
		var tab = this.getComponent(o);
		if (tab) {
			this.remove(tab);
		}
	};
	MainPanel.superclass.constructor.call(this, {
		id : 'main',
		region : 'center',
		margins : '0 5 5 0',
		resizeTabs : true,
		minTabWidth : 135,
		tabWidth : 135,
		enableTabScroll : true,
		activeTab : 0,
		items : {
			id : 'homePage',
			title : '欢迎页面',
			closable : false,
//			autoLoad : {
//				url : 'index.php'
//			},
			autoScroll : true,
			tbar : ['欢迎使用美达后台管理系统，如有问题请联系维护人员',{text:'联系维护人员!',pressed: true,handler:function(){Ext.MessageBox.alert('提示', '<img src="./extjsAdmin/images/designer_avatar.png" border=0 />隔壁高鹏喊一声~~');}}],
			html : '<img src="./extjsAdmin/images/mark1.jpg" border=0 /> 友情提示：请不要对选手进行惨无人道的围观~~'
		}
	});
};
Ext.extend(MainPanel, Ext.TabPanel);

/*开始
*/
var main, menu, header;
Ext.onReady(function() {
					 
			Ext.QuickTips.init();//开启表单提示		 
					 
			header = new Ext.Panel( {
				border : true,
				region : 'north',
				height : 65,
				items : [{
					layout : "column",
					border : false,
					defaults : {
						border : false,
						bodyStyle : 'padding-top:5px;'
					},
					items : [
							{
								columnWidth : .44,
								html : '<a href=http://moda.we54.com target=_blank><img src="./extjsAdmin/images/mark2.jpg" border=0 height="60px" /></a>'
							},
							{
								columnWidth : .4
							},
							{
								columnWidth : .16,
								cls : 'link',
								html : '<a style="background:url(./extjsAdmin/images/user.gif) no-repeat left top; padding:0 0 0 18px;" href="http://passport.we54.com/index/logout">注销用户</a>&nbsp;'
										+ '<a style="background:url(./extjsAdmin/images/key.gif) no-repeat left top; padding:0 0 0 17px;" href="?Controller=Admin">跳转后台</a><br />'
										+ '<a style="background:url(./extjsAdmin/images/home.gif) no-repeat left top; padding:4px 0 0 18px;" href="/">返回首页</a>&nbsp;'
										+ '<select onchange="changeSkin(this.value)">'
										+ '<option value="ext-all">默认风格</option>'
										+ '<option value="xtheme-gray">银白风格</option>'

										+ '</select>'
							}]
				}]
			});
			changeSkin = function(value) {
				Ext.util.CSS
						.swapStyleSheet('window',
								'./ext-3.1.0/resources/css/' + value
										+ '.css');
			};
			menu = new MenuPanel();
			main = new MainPanel();
			var viewport = new Ext.Viewport( {
				layout : 'border',
				items : [header, menu, main]
			});

			setTimeout(function() {
				Ext.get('loading').remove();
				Ext.get('loading-mask').fadeOut( {
					remove : true
				});
			}, 300);
		})
