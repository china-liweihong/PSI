/**
 * 银行账户 - 主界面
 */
Ext.define("PSI.Bank.MainForm", {
			extend : "PSI.AFX.BaseMainExForm",

			/**
			 * 初始化组件
			 */
			initComponent : function() {
				var me = this;

				Ext.apply(me, {
							tbar : me.getToolbarCmp(),
							items : [{
										region : "west",
										width : 300,
										layout : "fit",
										border : 0,
										split : true,
										items : [me.getCompanyGrid()]
									}, {
										region : "center",
										xtype : "panel",
										layout : "fit",
										border : 0,
										html : "模块待开发"
									}]
						});

				me.callParent(arguments);

				me.refreshCompanyGrid();
			},

			getToolbarCmp : function() {
				var me = this;
				return [{
							text : "新增银行账户",
							handler : me.onAddBank,
							scope : me
						}, "-", {
							text : "编辑银行账户",
							handler : me.onEditBank,
							scope : me
						}, "-", {
							text : "删除银行账户",
							handler : me.onDeleteBank,
							scope : me
						}, "-", {
							text : "关闭",
							handler : function() {
								me.closeWindow();
							}
						}];
			},

			refreshCompanyGrid : function() {
				var me = this;
				var el = Ext.getBody();
				var store = me.getCompanyGrid().getStore();
				el.mask(PSI.Const.LOADING);
				var r = {
					url : me.URL("Home/Bank/companyList"),
					callback : function(options, success, response) {
						store.removeAll();

						if (success) {
							var data = me.decodeJSON(response.responseText);
							store.add(data);
							if (store.getCount() > 0) {
								me.getCompanyGrid().getSelectionModel()
										.select(0);
							}
						}

						el.unmask();
					}
				};
				me.ajax(r);
			},

			getCompanyGrid : function() {
				var me = this;
				if (me.__companyGrid) {
					return me.__companyGrid;
				}

				var modelName = "PSI_Bank_CompanyModel";

				Ext.define(modelName, {
							extend : "Ext.data.Model",
							fields : ["id", "code", "name"]
						});

				me.__companyGrid = Ext.create("Ext.grid.Panel", {
							cls : "PSI",
							header : {
								height : 30,
								title : me.formatGridHeaderTitle("公司")
							},
							forceFit : true,
							columnLines : true,
							columns : [{
										header : "公司编码",
										dataIndex : "code",
										menuDisabled : true,
										sortable : false,
										width : 70
									}, {
										header : "公司名称",
										dataIndex : "name",
										flex : 1,
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
									fn : me.onCompanyGridSelect,
									scope : me
								}
							}
						});
				return me.__companyGrid;
			},

			onCompanyGridSelect : function() {
				var me = this;
			},

			onAddBank : function() {
				var me = this;
				me.showInfo("TODO");
			},

			onEditBank : function() {
				var me = this;
				me.showInfo("TODO");
			},

			onDeleteBank : function() {
				var me = this;
				me.showInfo("TODO");
			}
		});