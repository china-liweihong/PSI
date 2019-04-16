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
													// border : 0,
													items : []
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
			},

			getToolbarCmp : function() {
				var me = this;

				return [{
							text : "新增码表分类",
							handler : me.onAddCategory,
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

			onAddCategory : function() {
				var me = this;
				me.showInfo("TODO");
			}
		});