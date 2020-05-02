//
// 码表 - 调整编辑界面字段显示次序
//
Ext.define("PSI.CodeTable.CodeTableEditColShowOrderForm", {
  extend: "PSI.AFX.BaseDialogForm",

  config: {
    codeTable: null
  },

  initComponent: function () {
    var me = this;
    var entity = me.getEntity();
    this.adding = entity == null;

    var buttons = [];

    buttons.push({
      text: "保存",
      formBind: true,
      iconCls: "PSI-button-ok",
      handler: function () {
        me.onOK(false);
      },
      scope: me
    }, {
      text: "取消",
      handler: function () {
        me.close();
      },
      scope: me
    });


    Ext.apply(me, {
      header: {
        title: me.formatTitle("调整编辑界面字段显示次序"),
        height: 40
      },
      width: 900,
      height: 200,
      layout: "border",
      items: [{
        region: "north",
        border: 0,
        html: "<h1>拖动列来调整显示次序</h1>"
      }, {
        region: "center",
        layout: "fit",
        border: 0,
        id: "CodeTableEditColShowOrderForm_panelMain",
        items: []
      }],
      buttons: buttons,
      listeners: {
        show: {
          fn: me.onWndShow,
          scope: me
        },
        close: {
          fn: me.onWndClose,
          scope: me
        }
      }
    });

    me.callParent(arguments);

    me.__mainPanel = Ext.getCmp("CodeTableEditColShowOrderForm_panelMain");
  },

  onWndShow: function () {
    var me = this;

    Ext.get(window).on('beforeunload', me.onWindowBeforeUnload);

    me.__mainPanel.add(me.createMainGrid());
  },

  onOK: function () {
    var me = this;
  },

  onWindowBeforeUnload: function (e) {
    return (window.event.returnValue = e.returnValue = '确认离开当前页面？');
  },

  onWndClose: function () {
    var me = this;

    Ext.get(window).un('beforeunload', me.onWindowBeforeUnload);
  },

  createMainGrid: function (md) {
    var me = this;

    var modelName = "PSICodeTableEditColShowOrder";

    Ext.define(modelName, {
      extend: "Ext.data.Model",
      fields: []
    });

    var columns = [];
    if (!md) {
      columns.push({});
    } else {

    }

    return Ext.create("Ext.grid.Panel", {
      columnLines: true,
      columns: {
        defaults: {
          menuDisabled: true,
          sortable: false

        }, items: columns
      }
    });
  }
});
