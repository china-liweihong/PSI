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
													//border : 0,
													items : []
												}, {
													id : "panelCategory",
													xtype : "panel",
													region : "west",
													layout : "fit",
													width : 430,
													split : true,
													collapsible : true,
													header : false,
													//border : 0,
													items : []
												}]
									}]
						});

				me.callParent(arguments);
			},

			getToolbarCmp : function() {
				var me = this;

				return [{
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
			}
		});