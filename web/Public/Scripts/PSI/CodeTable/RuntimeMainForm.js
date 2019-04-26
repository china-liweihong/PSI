//
// 码表运行- 主界面
//
Ext.define("PSI.CodeTable.RuntimeMainForm", {
			extend : "PSI.AFX.BaseMainExForm",
			border : 0,

			config : {
				fid : null
			},

			initComponent : function() {
				var me = this;

				Ext.apply(me, {
							tbar : {
								id : "PSI_CodeTable_RuntimeMainForm_toolBar",
								xtype : "toolbar"
							},
							layout : "border",
							items : [{
										region : "center",
										layout : "fit",
										border : 0,
										items : []
									}]
						});

				me.callParent(arguments);

				me.__toolBar = Ext
						.getCmp("PSI_CodeTable_RuntimeMainForm_toolBar");

				me.fetchMeatData();
			},

			getMetaData : function() {
				return this.__md;
			},

			fetchMeatData : function() {
				var me = this;
				var el = me.getEl();
				el && el.mask(PSI.Const.LOADING);
				me.ajax({
							url : me
									.URL("Home/CodeTable/getMetaDataForRuntime"),
							params : {
								fid : me.getFid()
							},
							callback : function(options, success, response) {
								if (success) {
									var data = me
											.decodeJSON(response.responseText);

									me.__md = data;

									me.initUI();
								}

								el && el.unmask();
							}
						});
			},

			initUI : function() {
				var me = this;

				var md = me.getMetaData();
				if (!md) {
					return;
				}

				var name = md.name;
				if (!name) {
					return;
				}

				// 按钮
				var toolBar = me.__toolBar;
				toolBar.add([{
							text : "新增" + name,
							id : "buttonAddCodeTableRecord",
							handler : me.onAddCodeTableRecord,
							scope : me
						}, {
							text : "编辑" + name,
							id : "buttonEditCodeTableRecord",
							handler : me.onEditCodeTableRecord,
							scope : me
						}, {
							text : "删除" + name,
							id : "buttonDeleteCodeTableRecord",
							handler : me.onDeleteCodeTableRecord,
							scope : me
						}, "-", {
							text : "关闭",
							handler : function() {
								me.closeWindow();
							}
						}]);
			},

			onAddCodeTableRecord : function() {
				var me = this;
				me.showInfo("TODO");
			},

			onEditCodeTableRecord : function() {
				var me = this;
				me.showInfo("TODO");
			},

			onDeleteCodeTableRecord : function() {
				var me = this;
				me.showInfo("TODO");
			}
		});