//
// 存货拆分 - 主界面
//
Ext.define("PSI.WSP.WSPMainForm", {
	extend : "PSI.AFX.BaseMainExForm",

	config : {
		permission : null
	},

	initComponent : function() {
		var me = this;

		Ext.apply(me, {
					tbar : me.getToolbarCmp(),
					items : [{
								id : "panelQueryCmp",
								region : "north",
								height : 65,
								layout : "fit",
								border : 0,
								header : false,
								collapsible : true,
								collapseMode : "mini",
								layout : {
									type : "table",
									columns : 4
								},
								items : me.getQueryCmp()
							}, {
								region : "center",
								layout : "border",
								border : 0,
								items : [{
											region : "north",
											height : "40%",
											split : true,
											layout : "fit",
											border : 0,
											items : [me.getMainGrid()]
										}, {
											region : "center",
											layout : "fit",
											border : 0,
											items : []
										}]
							}]
				});

		me.callParent(arguments);
	},

	getToolbarCmp : function() {
		var me = this;
		return [{
					text : "新建拆分单",
					id : "buttonAdd",
					hidden : me.getPermission().add == "0",
					scope : me,
					handler : me.onAddBill
				}, {
					hidden : me.getPermission().add == "0",
					xtype : "tbseparator"
				}, {
					text : "编辑拆分单",
					hidden : me.getPermission().edit == "0",
					id : "buttonEdit",
					scope : me,
					handler : me.onEditBill
				}, {
					hidden : me.getPermission().edit == "0",
					xtype : "tbseparator"
				}, {
					text : "删除拆分单",
					hidden : me.getPermission().del == "0",
					id : "buttonDelete",
					scope : me,
					handler : me.onDeleteBill
				}, {
					hidden : me.getPermission().del == "0",
					xtype : "tbseparator"
				}, {
					text : "提交",
					hidden : me.getPermission().commit == "0",
					id : "buttonCommit",
					scope : me,
					handler : me.onCommit
				}, {
					hidden : me.getPermission().commit == "0",
					xtype : "tbseparator"
				}, {
					text : "导出",
					hidden : me.getPermission().genPDF == "0",
					menu : [{
								text : "单据生成pdf",
								id : "buttonPDF",
								iconCls : "PSI-button-pdf",
								scope : me,
								handler : me.onPDF
							}]
				}, {
					hidden : me.getPermission().genPDF == "0",
					xtype : "tbseparator"
				}, {
					text : "打印",
					hidden : me.getPermission().print == "0",
					menu : [{
								text : "打印预览",
								iconCls : "PSI-button-print-preview",
								scope : me,
								handler : me.onPrintPreview
							}, "-", {
								text : "直接打印",
								iconCls : "PSI-button-print",
								scope : me,
								handler : me.onPrint
							}]
				}, {
					xtype : "tbseparator",
					hidden : me.getPermission().print == "0"
				}, {
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

	getQueryCmp : function() {
		var me = this;
		return [{
					id : "editQueryBillStatus",
					xtype : "combo",
					queryMode : "local",
					editable : false,
					valueField : "id",
					labelWidth : 60,
					labelAlign : "right",
					labelSeparator : "",
					fieldLabel : "状态",
					margin : "5, 0, 0, 0",
					store : Ext.create("Ext.data.ArrayStore", {
								fields : ["id", "text"],
								data : [[-1, "全部"], [0, "待拆分"], [1000, "已拆分"]]
							}),
					value : -1
				}, {
					id : "editQueryRef",
					labelWidth : 60,
					labelAlign : "right",
					labelSeparator : "",
					fieldLabel : "单号",
					margin : "5, 0, 0, 0",
					xtype : "textfield"
				}, {
					id : "editQueryFromDT",
					xtype : "datefield",
					margin : "5, 0, 0, 0",
					format : "Y-m-d",
					labelAlign : "right",
					labelSeparator : "",
					fieldLabel : "业务日期（起）"
				}, {
					id : "editQueryToDT",
					xtype : "datefield",
					margin : "5, 0, 0, 0",
					format : "Y-m-d",
					labelAlign : "right",
					labelSeparator : "",
					fieldLabel : "业务日期（止）"
				}, {
					id : "editQueryFromWarehouse",
					xtype : "psi_warehousefield",
					showModal : true,
					labelAlign : "right",
					labelSeparator : "",
					labelWidth : 60,
					margin : "5, 0, 0, 0",
					fieldLabel : "仓库"
				}, {
					id : "editQueryToWarehouse",
					xtype : "psi_warehousefield",
					showModal : true,
					labelAlign : "right",
					labelSeparator : "",
					labelWidth : 60,
					margin : "5, 0, 0, 0",
					fieldLabel : "拆分后调入仓库"
				}, {
					xtype : "container",
					items : [{
								xtype : "button",
								text : "查询",
								width : 100,
								height : 26,
								margin : "5 0 0 10",
								handler : me.onQuery,
								scope : me
							}, {
								xtype : "button",
								text : "清空查询条件",
								width : 100,
								height : 26,
								margin : "5, 0, 0, 10",
								handler : me.onClearQuery,
								scope : me
							}]
				}, {
					xtype : "container",
					items : [{
								xtype : "button",
								text : "隐藏查询条件栏",
								width : 130,
								height : 26,
								iconCls : "PSI-button-hide",
								margin : "5 0 0 10",
								handler : function() {
									Ext.getCmp("panelQueryCmp").collapse();
								},
								scope : me
							}]
				}];
	},

	onQuery : function() {
		var me = this;

	},

	onClearQuery : function() {
		var me = this;

		me.onQuery();
	},

	getQueryParam : function() {
		var me = this;

		var result = {
			billStatus : Ext.getCmp("editQueryBillStatus").getValue()
		};

		var ref = Ext.getCmp("editQueryRef").getValue();
		if (ref) {
			result.ref = ref;
		}

		var fromWarehouseId = Ext.getCmp("editQueryFromWarehouse").getIdValue();
		if (fromWarehouseId) {
			result.fromWarehouseId = fromWarehouseId;
		}

		var toWarehouseId = Ext.getCmp("editQueryToWarehouse").getIdValue();
		if (toWarehouseId) {
			result.toWarehouseId = toWarehouseId;
		}

		var fromDT = Ext.getCmp("editQueryFromDT").getValue();
		if (fromDT) {
			result.fromDT = Ext.Date.format(fromDT, "Y-m-d");
		}

		var toDT = Ext.getCmp("editQueryToDT").getValue();
		if (toDT) {
			result.toDT = Ext.Date.format(toDT, "Y-m-d");
		}

		return result;
	},

	getMainGrid : function() {
		var me = this;
		if (me.__mainGrid) {
			return me.__mainGrid;
		}

		var modelName = "PSIWSPBill";
		Ext.define(modelName, {
					extend : "Ext.data.Model",
					fields : ["id", "ref", "bizDate", "fromWarehouseName",
							"toWarehouseName", "inputUserName", "bizUserName",
							"billStatus", "dateCreated"]
				});
		var store = Ext.create("Ext.data.Store", {
					autoLoad : false,
					model : modelName,
					data : [],
					pageSize : 20,
					proxy : {
						type : "ajax",
						actionMethods : {
							read : "POST"
						},
						url : PSI.Const.BASE_URL + "Home/WSP/wspbillList",
						reader : {
							root : 'dataList',
							totalProperty : 'totalCount'
						}
					}
				});
		store.on("beforeload", function() {
					store.proxy.extraParams = me.getQueryParam();
				});
		store.on("load", function(e, records, successful) {
					if (successful) {
						me.gotoMainGridRecord(me.__lastId);
					}
				});

		me.__mainGrid = Ext.create("Ext.grid.Panel", {
					cls : "PSI",
					viewConfig : {
						enableTextSelection : true
					},
					border : 1,
					columnLines : true,
					columns : {
						defaults : {
							menuDisabled : true,
							sortable : false
						},
						items : [{
									xtype : "rownumberer",
									width : 50
								}, {
									header : "状态",
									dataIndex : "billStatus",
									width : 60,
									renderer : function(value) {
										return value == "待调拨"
												? "<span style='color:red'>"
														+ value + "</span>"
												: value;
									}
								}, {
									header : "单号",
									dataIndex : "ref",
									width : 110
								}, {
									header : "业务日期",
									dataIndex : "bizDate"
								}, {
									header : "仓库",
									dataIndex : "fromWarehouseName",
									width : 150
								}, {
									header : "拆分后入库仓库",
									dataIndex : "toWarehouseName",
									width : 150
								}, {
									header : "业务员",
									dataIndex : "bizUserName"
								}, {
									header : "制单人",
									dataIndex : "inputUserName"
								}, {
									header : "制单时间",
									dataIndex : "dateCreated",
									width : 140
								}]
					},
					listeners : {
						select : {
							fn : me.onMainGridSelect,
							scope : me
						},
						itemdblclick : {
							fn : me.getPermission().edit == "1"
									? me.onEditBill
									: Ext.emptyFn,
							scope : me
						}
					},
					store : store,
					bbar : ["->", {
								id : "pagingToobar",
								xtype : "pagingtoolbar",
								border : 0,
								store : store
							}, "-", {
								xtype : "displayfield",
								value : "每页显示"
							}, {
								id : "comboCountPerPage",
								xtype : "combobox",
								editable : false,
								width : 60,
								store : Ext.create("Ext.data.ArrayStore", {
											fields : ["text"],
											data : [["20"], ["50"], ["100"],
													["300"], ["1000"]]
										}),
								value : 20,
								listeners : {
									change : {
										fn : function() {
											store.pageSize = Ext
													.getCmp("comboCountPerPage")
													.getValue();
											store.currentPage = 1;
											Ext.getCmp("pagingToobar")
													.doRefresh();
										},
										scope : me
									}
								}
							}, {
								xtype : "displayfield",
								value : "条记录"
							}]
				});

		return me.__mainGrid;
	},

	gotoMainGridRecord : function(id) {
		var me = this;
		var grid = me.getMainGrid();
		grid.getSelectionModel().deselectAll();
		var store = grid.getStore();
		if (id) {
			var r = store.findExact("id", id);
			if (r != -1) {
				grid.getSelectionModel().select(r);
			} else {
				grid.getSelectionModel().select(0);
			}
		} else {
			grid.getSelectionModel().select(0);
		}
	},

	onAddBill : function() {
		var me = this;
		me.showInfo("TODO");
	},

	onEditBill : function() {
		var me = this;
		me.showInfo("TODO");
	},

	onDeleteBill : function() {
		var me = this;
		me.showInfo("TODO");
	},

	onCommit : function() {
		var me = this;
		me.showInfo("TODO");
	},

	onPDF : function() {
		var me = this;
		me.showInfo("TODO");
	},

	onPrintPreview : function() {
		var me = this;
		me.showInfo("TODO");
	},

	onPrint : function() {
		var me = this;
		me.showInfo("TODO");
	}
});