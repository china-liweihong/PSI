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
			width : 800,
			height : 350,
			layout : "border",
			items : [{
						region : "north",
						border : 0,
						height : 90,
						html : logoHtml
					}, {
						region : "center",
						border : 0,
						id : "PSI_CodeTable_CodeTableColEditForm_editForm",
						xtype : "form",
						layout : {
							type : "table",
							columns : 3
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
									id : "PSI_CodeTable_CodeTableColEditForm_editName",
									fieldLabel : "码表名称",
									readOnly : true,
									value : me.getCodeTable().get("name")
								}, {
									id : "PSI_CodeTable_CodeTableColEditForm_editTableName",
									fieldLabel : "数据库表名",
									readOnly : true,
									colspan : 2,
									width : 510,
									value : me.getCodeTable().get("tableName")
								}, {
									id : "PSI_CodeTable_CodeTableColEditForm_editCaption",
									fieldLabel : "列标题",
									allowBlank : false,
									blankText : "没有输入列标题",
									beforeLabelTextTpl : PSI.Const.REQUIRED,
									listeners : {
										specialkey : {
											fn : me.onEditSpecialKey,
											scope : me
										}
									},
									name : "caption"
								}, {
									id : "PSI_CodeTable_CodeTableColEditForm_editFieldName",
									fieldLabel : "列数据库名",
									allowBlank : false,
									blankText : "没有输入列数据库名",
									beforeLabelTextTpl : PSI.Const.REQUIRED,
									listeners : {
										specialkey : {
											fn : me.onEditSpecialKey,
											scope : me
										}
									},
									colspan : 2,
									width : 510,
									name : "fieldName"
								}, {
									id : "PSI_CodeTable_CodeTableColEditForm_editFieldType",
									xtype : "combo",
									queryMode : "local",
									editable : false,
									valueField : "id",
									labelAlign : "right",
									labelSeparator : "",
									fieldLabel : "列数据类型",
									allowBlank : false,
									blankText : "没有输入列数据类型",
									beforeLabelTextTpl : PSI.Const.REQUIRED,
									store : Ext.create("Ext.data.ArrayStore", {
												fields : ["id", "text"],
												data : [["varchar", "varchar"],
														["int", "int"],
														["decimal", "decimal"]]
											}),
									value : "varchar",
									name : "fieldType",
									listeners : {
										change : {
											fn : me.onFieldTypeChange,
											scope : me
										}
									}
								}, {
									id : "PSI_CodeTable_CodeTableColEditForm_editFieldLength",
									fieldLabel : "列数据长度",
									listeners : {
										specialkey : {
											fn : me.onEditSpecialKey,
											scope : me
										}
									},
									xtype : "numberfield",
									hideTrigger : true,
									allowDecimal : false,
									minValue : 1,
									value : 255,
									name : "fieldLength"
								}, {
									id : "PSI_CodeTable_CodeTableColEditForm_editFieldDec",
									fieldLabel : "列小数位数",
									listeners : {
										specialkey : {
											fn : me.onEditSpecialKey,
											scope : me
										}
									},
									xtype : "numberfield",
									hideTrigger : true,
									allowDecimal : false,
									minValue : 0,
									value : 0,
									name : "fieldDec",
									disabled : true
								}, {
									id : "PSI_CodeTable_CodeTableColEditForm_editValueFrom",
									xtype : "combo",
									queryMode : "local",
									editable : false,
									valueField : "id",
									labelAlign : "right",
									labelSeparator : "",
									fieldLabel : "值来源",
									allowBlank : false,
									blankText : "没有输入值来源",
									beforeLabelTextTpl : PSI.Const.REQUIRED,
									store : Ext.create("Ext.data.ArrayStore", {
												fields : ["id", "text"],
												data : [[1, "直接录入"],
														[2, "引用系统数据字典"],
														[3, "引用其他码表"]]
											}),
									value : 1,
									name : "valueFrom",
									listeners : {
										change : {
											fn : me.onValueFromChange,
											scope : me
										}
									}
								}, {
									id : "PSI_CodeTable_CodeTableColEditForm_editValueFromTableName",
									fieldLabel : "引用表名",
									disabled : true,
									name : "valueFromTableName"
								}, {
									id : "PSI_CodeTable_CodeTableColEditForm_editValueFromColName",
									fieldLabel : "引用列名",
									disabled : true,
									name : "valueFromColName"
								}, {
									id : "PSI_CodeTable_CodeTableColEditForm_editIsVisible",
									xtype : "combo",
									queryMode : "local",
									editable : false,
									valueField : "id",
									labelAlign : "right",
									labelSeparator : "",
									fieldLabel : "对用户可见",
									allowBlank : false,
									blankText : "没有输入对用户可见",
									beforeLabelTextTpl : PSI.Const.REQUIRED,
									store : Ext.create("Ext.data.ArrayStore", {
												fields : ["id", "text"],
												data : [[1, "对用户可见"],
														[2, "对用户不可见"]]
											}),
									value : 1,
									name : "isVisible"
								}, {
									id : "PSI_CodeTable_CodeTableColEditForm_editMustInput",
									xtype : "combo",
									queryMode : "local",
									editable : false,
									valueField : "id",
									labelAlign : "right",
									labelSeparator : "",
									fieldLabel : "必须录入",
									allowBlank : false,
									blankText : "没有输入必须录入",
									beforeLabelTextTpl : PSI.Const.REQUIRED,
									store : Ext.create("Ext.data.ArrayStore", {
												fields : ["id", "text"],
												data : [[1, "非必须录入项"],
														[2, "必须录入"]]
											}),
									value : 1,
									name : "mustInput"
								}, {
									id : "PSI_CodeTable_CodeTableColEditForm_editWidthInView",
									fieldLabel : "列视图宽度(px)",
									xtype : "numberfield",
									hideTrigger : true,
									allowDecimal : false,
									minValue : 10,
									value : 120,
									allowBlank : false,
									blankText : "没有输入列视图宽度",
									beforeLabelTextTpl : PSI.Const.REQUIRED,
									name : "widthInView"
								}, {
									id : "PSI_CodeTable_CodeTableColEditForm_editShowOrder",
									fieldLabel : "显示次序",
									xtype : "numberfield",
									hideTrigger : true,
									allowDecimal : false,
									name : "showOrder"
								}, {
									id : "PSI_CodeTable_CodeTableColEditForm_editMemo",
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
									width : 510, // 770,
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

		me.editForm = Ext.getCmp("PSI_CodeTable_CodeTableColEditForm_editForm");

		me.editName = Ext.getCmp("PSI_CodeTable_CodeTableColEditForm_editName");
		me.editTableName = Ext
				.getCmp("PSI_CodeTable_CodeTableColEditForm_editTableName");
		me.editCaption = Ext
				.getCmp("PSI_CodeTable_CodeTableColEditForm_editCaption");
		me.editFieldName = Ext
				.getCmp("PSI_CodeTable_CodeTableColEditForm_editFieldName");
		me.editFieldType = Ext
				.getCmp("PSI_CodeTable_CodeTableColEditForm_editFieldType");
		me.editFieldLength = Ext
				.getCmp("PSI_CodeTable_CodeTableColEditForm_editFieldLength");
		me.editFieldDec = Ext
				.getCmp("PSI_CodeTable_CodeTableColEditForm_editFieldDec");
		me.editValueFrom = Ext
				.getCmp("PSI_CodeTable_CodeTableColEditForm_editValueFrom");
		me.editValueFromTableName = Ext
				.getCmp("PSI_CodeTable_CodeTableColEditForm_editValueFromTableName");
		me.editValueFromColName = Ext
				.getCmp("PSI_CodeTable_CodeTableColEditForm_editValueFromColName");
		me.editMemo = Ext.getCmp("PSI_CodeTable_CodeTableColEditForm_editMemo");

		me.__editorList = [me.editName, me.editTableName, me.editMemo];
	},

	onWndShow : function() {
		var me = this;

		Ext.get(window).on('beforeunload', me.onWindowBeforeUnload);

		if (me.adding) {
			// 新建
			me.editCaption.focus();
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

								me.editCaption.focus();
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
	},

	onFieldTypeChange : function() {
		var me = this;
		var v = me.editFieldType.getValue();
		if (v == "varchar") {
			me.editFieldLength.setDisabled(false);
			me.editFieldDec.setDisabled(true);
		} else if (v == "int") {
			me.editFieldLength.setDisabled(true);
			me.editFieldDec.setDisabled(true);
		} else if (v == "decimal") {
			me.editFieldLength.setDisabled(false);
			me.editFieldDec.setDisabled(false);
		}
	},

	onValueFromChange : function() {
		var me = this;
		var v = me.editValueFrom.getValue();
		if (v == 1) {
			me.editValueFromTableName.setDisabled(true);
			me.editValueFromColName.setDisabled(true);
		} else if (v == 2) {
			me.editValueFromTableName.setDisabled(false);
			me.editValueFromColName.setDisabled(false);
		} else if (v == 3) {
			me.editValueFromTableName.setDisabled(false);
			me.editValueFromColName.setDisabled(false);
		}
	}
});