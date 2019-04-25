//
// 主菜单维护 - 主界面
//
Ext.define("PSI.MainMenu.MainForm", {
			extend : "PSI.AFX.BaseMainExForm",
			border : 0,

			/**
			 * 初始化组件
			 */
			initComponent : function() {
				var me = this;

				Ext.apply(me, {
							tbar : me.getToolbarCmp(),
							layout : "border",
							items : [{
										region : "center",
										layout : "fit",
										border : 0,
										items : [me.getMainGrid()]
									}]
						});

				me.callParent(arguments);
			},

			getToolbarCmp : function() {
				var me = this;

				return [{
							text : "新增菜单",
							handler : me.onAddMenu,
							scope : me
						}, {
							text : "编辑菜单",
							handler : me.onEditMenu,
							scope : me
						}, {
							text : "删除菜单",
							handler : me.onDeleteMenu,
							scope : me
						}, "-", {
							text : "帮助",
							handler : function() {
								PSI.MsgBox.showInfo("TODO");
							}
						}, "-", {
							text : "关闭",
							handler : function() {
								me.closeWindow();
							}
						}];
			},

			getMainGrid : function() {
				var me = this;
				if (me.__mainGrid) {
					return me.__mainGrid;
				}

				var modelName = "PSIMainMenu";
				Ext.define(modelName, {
							extend : "Ext.data.Model",
							fields : ["id", "caption", "fid", "showOrder",
									"leaf", "children"]
						});

				var store = Ext.create("Ext.data.TreeStore", {
							model : modelName,
							proxy : {
								type : "ajax",
								actionMethods : {
									read : "POST"
								},
								url : me
										.URL("Home/MainMenu/allMenuItemsForMaintain")
							}

						});

				me.__mainGrid = Ext.create("Ext.tree.Panel", {
							cls : "PSI",
							header : {
								height : 30,
								title : me.formatGridHeaderTitle("主菜单")
							},
							store : store,
							rootVisible : false,
							useArrows : true,
							viewConfig : {
								loadMask : true
							},
							columns : {
								defaults : {
									sortable : false,
									menuDisabled : true,
									draggable : false
								},
								items : [{
											xtype : "treecolumn",
											text : "标题",
											dataIndex : "caption",
											width : 220
										}, {
											text : "fid",
											dataIndex : "fid",
											width : 220
										}, {
											text : "显示排序",
											dataIndex : "showOrder",
											width : 80
										}]
							}
						});

				return me.__mainGrid;
			},

			onAddMenu : function() {
				var me = this;

				var form = Ext.create("PSI.MainMenu.MenuItemEditForm", {
							parentForm : me
						});
				form.show();
			},

			onEditMenu : function() {
				var me = this;
				me.showInfo("TODO");
			},

			onDeleteMenu : function() {
				var me = this;
				me.showInfo("TODO");
			},

			refreshMainGrid : function() {
				// 这里用reload，是为同时刷新主界面中的主菜单的偷懒的写法
				window.location.reload();
			}
		});