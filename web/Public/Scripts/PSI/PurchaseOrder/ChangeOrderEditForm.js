//
// 订单变更界面
//
Ext.define("PSI.PurchaseOrder.ChangeOrderEditForm", {
	extend : "PSI.AFX.BaseDialogForm",

	initComponent : function() {
		var me = this;

		var entity = me.getEntity();

		var buttons = [];

		var btn = {
			text : "保存",
			formBind : true,
			iconCls : "PSI-button-ok",
			handler : function() {
				me.onOK();
			},
			scope : me
		};
		buttons.push(btn);

		var btn = {
			text : "取消",
			handler : function() {
				me.close();
			},
			scope : me
		};
		buttons.push(btn);

		var t = "订单变更";
		var f = "edit-form-update.png";
		var logoHtml = "<img style='float:left;margin:10px 20px 0px 10px;width:48px;height:48px;' src='"
				+ PSI.Const.BASE_URL
				+ "Public/Images/"
				+ f
				+ "'></img>"
				+ "<h2 style='color:#196d83'>"
				+ t
				+ "</h2>"
				+ "<p style='color:#196d83'>标记 <span style='color:red;font-weight:bold'>*</span>的是必须录入数据的字段</p>";
		Ext.apply(me, {
					header : {
						title : me.formatTitle(PSI.Const.PROD_NAME),
						height : 40
					},
					width : 400,
					height : 270,
					layout : "border",
					listeners : {
						show : {
							fn : me.onWndShow,
							scope : me
						},
						close : {
							fn : me.onWndClose,
							scope : me
						}
					},
					items : [{
								region : "north",
								height : 90,
								border : 0,
								html : logoHtml
							}, {
								region : "center",
								border : 0,
								id : "PSI_PurchaseOrder_ChangeOrderEditForm_editForm",
								xtype : "form",
								layout : {
									type : "table",
									columns : 1
								},
								height : "100%",
								bodyPadding : 5,
								defaultType : 'textfield',
								fieldDefaults : {
									labelWidth : 60,
									labelAlign : "right",
									labelSeparator : "",
									msgTarget : 'side',
									width : 370,
									margin : "5"
								},
								items : [{
											xtype : "hidden",
											name : "id",
											value : entity.get("id")
										}],
								buttons : buttons
							}]
				});

		me.callParent(arguments);

		me.editForm = Ext
				.getCmp("PSI_PurchaseOrder_ChangeOrderEditForm_editForm");
	},

	onOK : function(thenAdd) {
		var me = this;

		var f = me.editForm;
		var el = f.getEl();
		el.mask(PSI.Const.SAVING);
		var sf = {
			url : me.URL("/Home/Purchase/changePurchaseOrder"),
			method : "POST",
			success : function(form, action) {
				me.__lastId = action.result.id;

				el.unmask();

				PSI.MsgBox.tip("数据保存成功");
				me.focus();
				me.close();
			},
			failure : function(form, action) {
				el.unmask();
				PSI.MsgBox.showInfo(action.result.msg, function() {
						});
			}
		};
		f.submit(sf);
	},

	onWindowBeforeUnload : function(e) {
		return (window.event.returnValue = e.returnValue = '确认离开当前页面？');
	},

	onWndClose : function() {
		var me = this;

		Ext.get(window).un('beforeunload', me.onWindowBeforeUnload);

		if (me.__lastId) {
			if (me.getParentForm()) {
				me.getParentForm().refreshDetailGrid(me.__lastId);
			}
		}
	},

	onWndShow : function() {
		var me = this;

		Ext.get(window).on('beforeunload', me.onWindowBeforeUnload);
	}
});