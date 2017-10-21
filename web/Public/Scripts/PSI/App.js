/**
 * PSI的应用容器：承载主菜单、其他模块的UI
 */
Ext.define("PSI.App", {
	config : {
		userName : "",
		productionName : "PSI"
	},

	constructor : function(config) {
		var me = this;

		me.initConfig(config);

		me.createMainUI();
		
		if (config.appHeaderInfo) {
			me.setAppHeader(config.appHeaderInfo);
		}
	},

	createMainUI : function() {
		var me = this;

		me.mainPanel = Ext.create("Ext.panel.Panel", {
					border : 0,
					layout : "fit"
				});

		Ext.define("PSIFId", {
					extend : "Ext.data.Model",
					fields : ["fid", "name"]
				});

		var storeRecentFid = Ext.create("Ext.data.Store", {
					autoLoad : false,
					model : "PSIFId",
					data : []
				});

		me.gridRecentFid = Ext.create("Ext.grid.Panel", {
					title : "常用功能",
					forceFit : true,
					hideHeaders : true,
					columns : [{
						dataIndex : "name",
						menuDisabled : true,
						menuDisabled : true,
						sortable : false,
						renderer : function(value, metaData, record) {
							var fid = record.get("fid");
							var fileName = PSI.Const.BASE_URL
									+ "Public/Images/fid/fid" + fid + ".png";
							return "<img src='"
									+ fileName
									+ "'><a href='#' style='text-decoration:none'>"
									+ value + "</a></img>";
						}
					}],
					store : storeRecentFid
				});

		me.gridRecentFid.on("itemclick", function(v, r) {
					var fid = r.get("fid");

					var url = PSI.Const.BASE_URL
							+ "Home/MainMenu/navigateTo/fid/" + fid;

					if (fid === "-9999") {
						PSI.MsgBox.confirm("请确认是否重新登录", function() {
									location.replace(url);
								});
					} else {
						if (PSI.Const.MOT == "0") {
							location.replace(url);

						} else {
							window.open(url);
						}
					}
				}, me);

		var year = new Date().getFullYear();

		me.vp = Ext.create("Ext.container.Viewport", {
			layout : "fit",
			items : [{
				id : "__PSITopPanel",
				xtype : "panel",
				border : 0,
				layout : "border",
				header : {
					height : 40,
					tools : [{
						xtype : "displayfield",
						value : "<span style='color:#04408c;font-weight:bold'>当前用户："
								+ me.getUserName() + "&nbsp;</span>"
					}]
				},
				items : [{
							region : "center",
							border : 0,
							layout : "fit",
							xtype : "panel",
							items : [me.mainPanel]
						}, {
							xtype : "panel",
							region : "east",
							width : 250,
							maxWidth : 250,
							split : true,
							collapsible : true,
							collapseMode : "mini",
							collapsed : me.getRecentFidPanelCollapsed(),
							header : false,
							border : 0,
							layout : "fit",
							items : [me.gridRecentFid],
							listeners : {
								collapse : {
									fn : me.onRecentFidPanelCollapse,
									scope : me
								},
								expand : {
									fn : me.onRecentFidPanelExpand,
									scope : me
								}
							}
						}, {
							xtype : "panel",
							region : "south",
							height : 25,
							border : 0,
							header : {
								titleAlign : "center",
								title : "Copyright &copy; 2015-" + year
										+ " PSI Team, All Rights Reserved"
							}
						}]
			}]
		});

		var el = Ext.getBody();
		el.mask("系统正在加载中...");

		Ext.Ajax.request({
					url : PSI.Const.BASE_URL + "Home/MainMenu/mainMenuItems",
					method : "POST",
					callback : function(opt, success, response) {
						if (success) {
							var data = Ext.JSON.decode(response.responseText);
							me.createMainMenu(data);
							me.refreshRectFidGrid();
						}

						el.unmask();
					},
					scope : me
				});
	},

	refreshRectFidGrid : function() {
		var me = this;

		var el = me.gridRecentFid.getEl() || Ext.getBody();
		el.mask("系统正在加载中...");
		var store = me.gridRecentFid.getStore();
		store.removeAll();

		Ext.Ajax.request({
					url : PSI.Const.BASE_URL + "Home/MainMenu/recentFid",
					method : "POST",
					callback : function(opt, success, response) {
						if (success) {
							var data = Ext.JSON.decode(response.responseText);
							store.add(data);
						}
						el.unmask();
					},
					scope : me
				});
	},

	createMainMenu : function(root) {
		var me = this;

		var menuItemClick = function() {
			var fid = this.fid;

			if (fid == "-9995") {
				window.open(PSI.Const.BASE_URL + "/Home/Help/index");
			} else if (fid == "-9993") {
				var url = "https://zb.oschina.net/service/10565810c1d93056";
				window.open(url);
			} else if (fid === "-9999") {
				// 重新登录
				PSI.MsgBox.confirm("请确认是否重新登录", function() {
							location.replace(PSI.Const.BASE_URL
									+ "Home/MainMenu/navigateTo/fid/-9999");

						});
			} else {
				me.vp.focus();

				var url = PSI.Const.BASE_URL + "Home/MainMenu/navigateTo/fid/"
						+ fid;
				if (PSI.Const.MOT == "0") {
					location.replace(url);

				} else {
					window.open(url);
				}
			}
		};

		var mainMenu = [];
		for (var i = 0; i < root.length; i++) {
			var m1 = root[i];

			var menuItem = Ext.create("Ext.menu.Menu");
			for (var j = 0; j < m1.children.length; j++) {
				var m2 = m1.children[j];

				if (m2.children.length === 0) {
					// 只有二级菜单
					if (m2.fid) {
						menuItem.add({
									text : m2.caption,
									fid : m2.fid,
									handler : menuItemClick,
									iconCls : "PSI-fid" + m2.fid
								});
					}
				} else {
					var menuItem2 = Ext.create("Ext.menu.Menu");

					menuItem.add({
								text : m2.caption,
								menu : menuItem2
							});

					// 三级菜单
					for (var k = 0; k < m2.children.length; k++) {
						var m3 = m2.children[k];
						menuItem2.add({
									text : m3.caption,
									fid : m3.fid,
									handler : menuItemClick,
									iconCls : "PSI-fid" + m3.fid
								});
					}
				}
			}

			if (m1.children.length > 0) {
				mainMenu.push({
							text : m1.caption,
							menu : menuItem
						});
			}
		}

		var mainToolbar = Ext.create("Ext.toolbar.Toolbar", {
					dock : "top"
				});
		mainToolbar.add(mainMenu);

		me.vp.getComponent(0).addDocked(mainToolbar);
	},

	// 设置模块的标题
	setAppHeader : function(header) {
		if (!header) {
			return;
		}
		var panel = Ext.getCmp("__PSITopPanel");
		var title = "<span style='font-size:160%'>" + header.title + " - "
				+ this.getProductionName() + "</span>";
		panel.setTitle(title);
		panel.setIconCls(header.iconCls);
	},

	add : function(comp) {
		this.mainPanel.add(comp);
	},

	onRecentFidPanelCollapse : function() {
		Ext.util.Cookies.set("PSI_RECENT_FID", "1", Ext.Date.add(new Date(),
						Ext.Date.YEAR, 1));
	},

	onRecentFidPanelExpand : function() {
		Ext.util.Cookies.clear("PSI_RECENT_FID");
	},

	getRecentFidPanelCollapsed : function() {
		var v = Ext.util.Cookies.get("PSI_RECENT_FID");
		return v === "1";
	}
});