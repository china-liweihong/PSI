/**
 * 视图开发助手 - 主页面
 */
Ext.define("PSI.FormView.DevMainForm", {
  extend: "PSI.AFX.BaseMainExForm",

  initComponent: function () {
    var me = this;

    Ext.apply(me, {
      tbar: me.getToolbarCmp(),
      items: [{
        border: 0,
        split: true,
        region: "west",
        width: "40%",
        layout: "fit",
        items: []
      }, {
        border: 0,
        region: "center",
        layout: "fit",
        items:[]
      }]
    });

    me.callParent(arguments);
  },

  getToolbarCmp: function () {
    var me = this;
    return [{
      text: "关闭",
      handler: function () {
        me.closeWindow();
      },
      scope: me
    }];
  }
});
