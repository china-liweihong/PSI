//
// 视图运行- 主界面
//
Ext.define("PSI.FormView.RuntimeMainForm", {
  extend: "PSI.AFX.BaseMainExForm",
  border: 0,

  config: {
    fid: null
  },

  initComponent: function () {
    var me = this;

    Ext.apply(me, {
      tbar: {
        id: "PSI_FormView_RuntimeMainForm_toolBar",
        xtype: "toolbar"
      },
      layout: "border",
      items: []
    });

    me.callParent(arguments);

    me.__toolBar = Ext.getCmp("PSI_FormView_RuntimeMainForm_toolBar");

    me.fetchMeatData();
  },

  getMetaData: function () {
    return this.__md;
  },

  fetchMeatData: function () {
    var me = this;
    var el = me.getEl();
    el && el.mask(PSI.Const.LOADING);
    me.ajax({
      url: me.URL("Home/FormView/fetchMetaDataForRuntime"),
      params: {
        fid: me.getFid()
      },
      callback: function (options, success, response) {
        if (success) {
          var data = me.decodeJSON(response.responseText);

          me.__md = data;

          me.initUI();
        }

        el && el.unmask();
      }
    });
  },

  initUI: function () {
    var me = this;

    var md = me.getMetaData();
    if (!md) {
      return;
    }

    // 按钮
    var toolBar = me.__toolBar;

    toolBar.add(["-", {
      text: "关闭",
      handler: function () {
        me.closeWindow();
      }
    }]);
  }
});
