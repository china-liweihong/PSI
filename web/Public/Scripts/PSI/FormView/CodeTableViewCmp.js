/**
 * 码表视图自定义控件
 */
Ext.define("PSI.FormView.CodeTableViewCmp", {
  extend: "Ext.panel.Panel",
  alias: "widget.psi_codetable_view_cmp",

  config: {
    fid: null
  },

  initComponent: function () {
    var me = this;

    me.callParent(arguments);
  }
});
