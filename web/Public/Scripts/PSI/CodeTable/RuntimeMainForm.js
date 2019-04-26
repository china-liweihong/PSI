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
							tbar : me.getToolbarCmp(),
							layout : "border",
							items : [{
										region : "center",
										layout : "fit",
										border : 0,
										items : []
									}]
						});

				me.callParent(arguments);

			},

			getToolbarCmp : function() {
				var me = this;

				return [{
							text : "新增",
							handler : me.onAddCodeTableRecord,
							scope : me
						}, {
							text : "编辑",
							handler : me.onEditCodeTableRecord,
							scope : me
						}, {
							text : "删除",
							handler : me.onDeleteCodeTableRecord,
							scope : me
						}, "-", {
							text : "关闭",
							handler : function() {
								me.closeWindow();
							}
						}];
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