//
// 码表列 - 新建或编辑界面
//
Ext.define("PSI.CodeTable.CodeTableColEditForm", {
	extend : "PSI.AFX.BaseDialogForm",

	config : {
		codeTable : null
	},

	initComponent : function() {
		var me = this;
		var entity = me.getEntity();
		this.adding = entity == null;

		var buttons = [];

		buttons.push({
					text : "保存",
					formBind : true,
					iconCls : "PSI-button-ok",
					handler : function() {
						me.onOK(false);
					},
					scope : me
				}, {
					text : entity == null ? "关闭" : "取消",
					handler : function() {
						me.close();
					},
					scope : me
				});

		var t = entity == null ? "新增码表列" : "编辑码表列";
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
			width : 550,
			height : 300,
			layout : "border",
			items : [{
						region : "north",
						border : 0,
						height : 90,
						html : logoHtml
					}, {
						region : "center",
						border : 0,
						id : "PSI_CodeTable_CodeTableEditForm_editForm",
						xtype : "form",
						layout : {
							type : "table",
							columns : 2
						},
						height : "100%",
						bodyPadding : 5,
						defaultType : 'textfield',
						fieldDefaults : {
							labelWidth : 100,
							labelAlign : "right",
							labelSeparator : "",
							msgTarget : 'side'
						},
						items : [{
									xtype : "hidden",
									name : "id",
									value : entity == null ? null : entity
											.get("id")
								}, {
									xtype : "hidden",
									name : "codeTableId",
									value : me.getCodeTable().get("id")
								}, {
									id : "PSI_CodeTable_CodeTableEditForm_editName",
									fieldLabel : "码表名称",
									readOnly : true,
									listeners : {
										specialkey : {
											fn : me.onEditSpecialKey,
											scope : me
										}
									},
									colspan : 2,
									width : 510,
									value : me.getCodeTable().get("name")
								}, {
									id : "PSI_CodeTable_CodeTableEditForm_editTableName",
									fieldLabel : "数据库表名",
									allowBlank : false,
									blankText : "没有输入数据库表名",
									beforeLabelTextTpl : PSI.Const.REQUIRED,
									readOnly : true,
									listeners : {
										specialkey : {
											fn : me.onEditSpecialKey,
											scope : me
										}
									},
									colspan : 2,
									width : 510,
									value : me.getCodeTable().get("tableName")
								}, {
									id : "PSI_CodeTable_CodeTableEditForm_editMemo",
									fieldLabel : "备注",
									name : "memo",
									value : entity == null ? null : entity
											.get("note"),
									listeners : {
										specialkey : {
											fn : me.onEditLastSpecialKey,
											scope : me
										}
									},
									width : 510,
									colspan : 2
								}],
						buttons : buttons
					}],
			listeners : {
				show : {
					fn : me.onWndShow,
					scope : me
				},
				close : {
					fn : me.onWndClose,
					scope : me
				}
			}
		});

		me.callParent(arguments);

		me.editForm = Ext.getCmp("PSI_CodeTable_CodeTableEditForm_editForm");

		me.editName = Ext.getCmp("PSI_CodeTable_CodeTableEditForm_editName");
		me.editTableName = Ext
				.getCmp("PSI_CodeTable_CodeTableEditForm_editTableName");
		me.editMemo = Ext.getCmp("PSI_CodeTable_CodeTableEditForm_editMemo");

		me.__editorList = [me.editName, me.editTableName, me.editMemo];
	},

	onWndShow : function() {
		var me = this;

		Ext.get(window).on('beforeunload', me.onWindowBeforeUnload);

		if (me.adding) {
			// 新建
		} else {
			// 编辑
			var el = me.getEl();
			el && el.mask(PSI.Const.LOADING);
			Ext.Ajax.request({
						url : me.URL("/Home/CodeTable/codeTableColInfo"),
						params : {
							id : me.getEntity().get("id")
						},
						method : "POST",
						callback : function(options, success, response) {
							if (success) {
								var data = Ext.JSON
										.decode(response.responseText);
								me.editName.setValue(data.name);
								me.editTableName.setValue(data.tableName);
								me.editTableName.setReadOnly(true);
								me.editMemo.setValue(data.memo);
							}

							el && el.unmask();
						}
					});
		}
	},

	onOK : function() {
		var me = this;

		var f = me.editForm;
		var el = f.getEl();
		el && el.mask(PSI.Const.SAVING);
		f.submit({
					url : me.URL("/Home/CodeTable/editCodeTableCol"),
					method : "POST",
					success : function(form, action) {
						el && el.unmask();
						PSI.MsgBox.tip("数据保存成功");
						me.focus();
						me.__lastId = action.result.id;
						me.close();
					},
					failure : function(form, action) {
						el && el.unmask();
						PSI.MsgBox.showInfo(action.result.msg, function() {
								});
					}
				});
	},

	onEditSpecialKey : function(field, e) {
		var me = this;

		if (e.getKey() === e.ENTER) {
			var id = field.getId();
			for (var i = 0; i < me.__editorList.length; i++) {
				var edit = me.__editorList[i];
				if (id == edit.getId()) {
					var edit = me.__editorList[i + 1];
					edit.focus();
					edit.setValue(edit.getValue());
				}
			}
		}
	},

	onEditLastSpecialKey : function(field, e) {
		var me = this;

		if (e.getKey() === e.ENTER) {
			var f = me.editForm;
			if (f.getForm().isValid()) {
				me.onOK();
			}
		}
	},

	onWindowBeforeUnload : function(e) {
		return (window.event.returnValue = e.returnValue = '确认离开当前页面？');
	},

	onWndClose : function() {
		var me = this;

		Ext.get(window).un('beforeunload', me.onWindowBeforeUnload);

		if (me.__lastId) {
			if (me.getParentForm()) {
				me.getParentForm().refreshColsGrid(me.__lastId);
			}
		}
	}
});