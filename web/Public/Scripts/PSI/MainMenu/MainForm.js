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
										items : []
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

			onAddMenu : function() {
				var me = this;
				me.showInfo("TODO");
			},

			onEditMenu : function() {
				var me = this;
				me.showInfo("TODO");
			},

			onDeleteMenu : function() {
				var me = this;
				me.showInfo("TODO");
			}
		});