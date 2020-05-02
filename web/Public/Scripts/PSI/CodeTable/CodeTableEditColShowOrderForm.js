//
// 码表 - 调整编辑界面字段显示次序
//
Ext.define("PSI.CodeTable.CodeTableEditColShowOrderForm", {
  extend: "PSI.AFX.BaseDialogForm",

  config: {
    fid: null
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
      width: 550,
      height: 340,
      layout: "border",
      items: [{ region: "center" }],
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
  },

  onWndShow: function () {
    var me = this;

    Ext.get(window).on('beforeunload', me.onWindowBeforeUnload);
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
  }
});
