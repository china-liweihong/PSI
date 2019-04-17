//
// 码表设置 - 主界面
//
Ext.define("PSI.CodeTable.MainForm", {
			extend : "PSI.AFX.BaseMainExForm",
			border : 0,

			initComponent : function() {
				var me = this;

				Ext.apply(me, {
							tbar : me.getToolbarCmp(),
							layout : "border",
							items : [{
										region : "center",
										layout : "border",
										border : 0,
										items : [{
													region : "center",
													xtype : "panel",
													layout : "fit",
													border : 0,
													items : [me.getMainGrid()]
												}, {
													id : "panelCategory",
													xtype : "panel",
													region : "west",
													layout : "fit",
													width : 300,
													split : true,
													collapsible : true,
													header : false,
													border : 0,
													items : [me
															.getCategoryGrid()]
												}]
									}]
						});

				me.callParent(arguments);

				me.refreshCategoryGrid();
			},

			getToolbarCmp : function() {
				var me = this;

				return [{
							text : "新增码表分类",
							handler : me.onAddCategory,
							scope : me
						}, {
							text : "编辑码表分类",
							handler : me.onEditCategory,
							scope : me
						}, "-", {
							text : "帮助",
							handler : function() {
								me.showInfo("TODO");
							}
						}, "-", {
							text : "关闭",
							handler : function() {
								me.closeWindow();
							}
						}];
			},

			getCategoryGrid : function() {
				var me = this;

				if (me.__categoryGrid) {
					return me.__categoryGrid;
				}

				var modelName = "PSICodeTableCategory";

				Ext.define(modelName, {
							extend : "Ext.data.Model",
							fields : ["id", "code", "name"]
						});

				me.__categoryGrid = Ext.create("Ext.grid.Panel", {
							cls : "PSI",
							viewConfig : {
								enableTextSelection : true
							},
							header : {
								height : 30,
								title : me.formatGridHeaderTitle("码表分类")
							},
							tools : [{
										type : "close",
										handler : function() {
											Ext.getCmp("panelCategory")
													.collapse();
										}
									}],
							columnLines : true,
							columns : [{
										header : "分类编码",
										dataIndex : "code",
										width : 80,
										menuDisabled : true,
										sortable : false
									}, {
										header : "码表分类",
										dataIndex : "name",
										width : 200,
										menuDisabled : true,
										sortable : false
									}],
							store : Ext.create("Ext.data.Store", {
										model : modelName,
										autoLoad : false,
										data : []
									}),
							listeners : {
								select : {
									fn : me.onCategoryGridSelect,
									scope : me
								}
							}
						});

				return me.__categoryGrid;
			},

			getMainGrid : function() {
				var me = this;

				if (me.__mainGrid) {
					return me.__mainGrid;
				}

				var modelName = "PSICodeTable";

				Ext.define(modelName, {
							extend : "Ext.data.Model",
							fields : ["id", "code", "name", "tableName", "memo"]
						});

				me.__mainGrid = Ext.create("Ext.grid.Panel", {
							cls : "PSI",
							viewConfig : {
								enableTextSelection : true
							},
							header : {
								height : 30,
								title : me.formatGridHeaderTitle("码表")
							},
							columnLines : true,
							columns : [{
										header : "编码",
										dataIndex : "code",
										width : 80,
										menuDisabled : true,
										sortable : false
									}, {
										header : "码表名称",
										dataIndex : "name",
										width : 200,
										menuDisabled : true,
										sortable : false
									}, {
										header : "数据库名",
										dataIndex : "tableName",
										width : 200,
										menuDisabled : true,
										sortable : false
									}, {
										header : "备注",
										dataIndex : "memo",
										width : 300,
										menuDisabled : true,
										sortable : false
									}],
							store : Ext.create("Ext.data.Store", {
										model : modelName,
										autoLoad : false,
										data : []
									}),
							listeners : {
								select : {
									fn : me.onMainGridSelect,
									scope : me
								}
							}
						});

				return me.__mainGrid;
			},

			onAddCategory : function() {
				var me = this;

				var form = Ext.create("PSI.CodeTable.CategoryEditForm", {
							parentForm : me
						});

				form.show();
			},

			refreshCategoryGrid : function(id) {
				var me = this;
				var grid = me.getCategoryGrid();
				var el = grid.getEl() || Ext.getBody();
				el.mask(PSI.Const.LOADING);
				var r = {
					url : me.URL("Home/CodeTable/categoryList"),
					callback : function(options, success, response) {
						var store = grid.getStore();

						store.removeAll();

						if (success) {
							var data = me.decodeJSON(response.responseText);
							store.add(data);

							if (store.getCount() > 0) {
								if (id) {
									var r = store.findExact("id", id);
									if (r != -1) {
										grid.getSelectionModel().select(r);
									}
								} else {
									grid.getSelectionModel().select(0);
								}
							}
						}

						el.unmask();
					}
				};

				me.ajax(r);
			},

			onEditCategory : function() {
				var me = this;

				var item = me.getCategoryGrid().getSelectionModel()
						.getSelection();
				if (item == null || item.length != 1) {
					me.showInfo("请选择要编辑的码表分类");
					return;
				}

				var category = item[0];

				var form = Ext.create("PSI.CodeTable.CategoryEditForm", {
							parentForm : me,
							entity : category
						});

				form.show();
			}
		});