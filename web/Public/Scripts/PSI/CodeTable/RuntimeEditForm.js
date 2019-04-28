//
// 码表运行 - 新增或编辑界面
//
Ext.define("PSI.CodeTable.RuntimeEditForm", {
	extend : "PSI.AFX.BaseDialogForm",

	config : {
		metaData : null
	},

	initComponent : function() {
		var me = this;

		var md = me.getMetaData();

		var entity = me.getEntity();

		me.adding = entity == null;

		var buttons = [];
		if (!entity) {
			var btn = {
				text : "保存并继续新增",
				formBind : true,
				handler : function() {
					me.onOK(true);
				},
				scope : me
			};

			buttons.push(btn);
		}

		var btn = {
			text : "保存",
			formBind : true,
			iconCls : "PSI-button-ok",
			handler : function() {
				me.onOK(false);
			},
			scope : me
		};
		buttons.push(btn);

		var btn = {
			text : entity == null ? "关闭" : "取消",
			handler : function() {
				me.close();
			},
			scope : me
		};
		buttons.push(btn);

		var t = entity == null ? "新增" + md.name : "编辑" + md.name;
		var f = entity == null
				? "edit-form-create.png"
				: "edit-form-update.png";
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
								id : "PSI_CodeTable_RuntimeEditForm_editForm",
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
								items : me.getEditItems(),
								buttons : buttons
							}]
				});

		me.callParent(arguments);

		me.editForm = Ext.getCmp("PSI_CodeTable_RuntimeEditForm_editForm");
	},

	getEditItems : function() {
		var me = this;

		var entity = me.getEntity();

		var md = me.getMetaData();
		if (!md) {
			return [];
		}

		var result = [{
					xtype : "hidden",
					name : "id",
					value : entity == null ? null : entity.get("id")
				}];

		var colsMd = md.cols;
		var colsCount = colsMd.length;
		for (var i = 0; i < colsCount; i++) {
			var colMd = colsMd[i];

			if (colMd.isVisible) {
				var item = {
					fieldLabel : colMd.caption,
					xtype : "textfield"
				};
				if (colMd.mustInput) {
					// 必录项
					Ext.apply(item, {
								allowBlank : false,
								blankText : "没有输入" + colMd.caption,
								beforeLabelTextTpl : PSI.Const.REQUIRED
							});
				}

				result.push(item);
			}
		}

		return result;
	},

	/**
	 * 保存
	 */
	onOK : function(thenAdd) {
		var me = this;

		var f = me.editForm;
		var el = f.getEl();
		el && el.mask(PSI.Const.SAVING);
		var sf = {
			url : me.URL("/Home/CodeTable/editCodeTableRecord"),
			method : "POST",
			success : function(form, action) {
				me.__lastId = action.result.id;

				el && el.unmask();

				PSI.MsgBox.tip("数据保存成功");
				me.focus();
				if (thenAdd) {
					me.clearEdit();
				} else {
					me.close();
				}
			},
			failure : function(form, action) {
				el.unmask();
				PSI.MsgBox.showInfo(action.result.msg, function() {
						});
			}
		};
		f.submit(sf);
	},

	clearEdit : function() {
		var me = this;
	},

	onWindowBeforeUnload : function(e) {
		return (window.event.returnValue = e.returnValue = '确认离开当前页面？');
	},

	onWndClose : function() {
		var me = this;

		Ext.get(window).un('beforeunload', me.onWindowBeforeUnload);

		if (me.__lastId) {
			if (me.getParentForm()) {
				me.getParentForm().refreshMainGrid(me.__lastId);
			}
		}
	},

	onWndShow : function() {
		var me = this;

		Ext.get(window).on('beforeunload', me.onWindowBeforeUnload);
	}
});