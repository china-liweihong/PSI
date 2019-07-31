/**
 * 自定义表单 - 主界面
 */
Ext.define("PSI.Form.MainForm", {
  extend: "PSI.AFX.BaseMainExForm",

  initComponent: function () {
    var me = this;

    Ext.apply(me, {
      border: 0,
      layout: "fit",
      items: [
        {
          border: 0,
          html: "<h1>TODO</h1>"
        }
      ]
    });

    me.callParent(arguments);
  }
});
