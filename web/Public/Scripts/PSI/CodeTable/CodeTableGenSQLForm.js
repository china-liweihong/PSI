/**
 * 导出SQL
 */
Ext.define("PSI.CodeTable.CodeTableGenSQLForm", {
  extend: "Ext.window.Window",
  config: {
    CodeTable: null
  },

  initComponent: function () {
    var me = this;

    Ext.apply(me, {
      title: "导出SQL",
      modal: true,
      onEsc: Ext.emptyFn,
      width: 800,
      height: 420,
      layout: "fit",
      defaultFocus: "editData",
      items: [{
        id: "editForm",
        xtype: "form",
        layout: "form",
        height: "100%",
        bodyPadding: 5,
        defaultType: 'textfield',
        fieldDefaults: {
          labelWidth: 60,
          labelAlign: "right",
          labelSeparator: "",
          msgTarget: 'side'
        },
        items: [{
          id: "editSQL",
          fieldLabel: "SQL",
          xtype: "textareafield",
          height: 300,
          readOnly: true
        }],
        buttons: [{
          text: "关闭",
          handler: function () {
            me.close();
          },
          scope: me
        }]
      }],
      listeners: {
        show: {
          fn: me.onWndShow,
          scope: me
        }
      }
    });

    me.callParent(arguments);
  },

  onWndShow: function () {
  }
});
