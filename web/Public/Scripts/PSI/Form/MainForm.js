/**
 * 自定义表单 - 主界面
 */
Ext.define("PSI.Form.MainForm", {
  extend: "PSI.AFX.BaseMainExForm",

  initComponent: function () {
    var me = this;

    Ext.apply(me, {
      tbar: me.getToolbarCmp(),
      layout: "border",
      items: [{
        region: "center",
        layout: "border",
        border: 0,
        items: [{
          region: "center",
          xtype: "panel",
          layout: "border",
          border: 0,
          items: [{
            region: "center",
            layout: "fit",
            border: 0,
            items: []
          }, {
            region: "south",
            layout: "fit",
            border: 0,
            height: "60%",
            split: true,
            items: []
          }]
        }, {
          id: "panelCategory",
          xtype: "panel",
          region: "west",
          layout: "fit",
          width: 300,
          split: true,
          collapsible: true,
          header: false,
          border: 0,
          items: []
        }]
      }]
    });

    me.callParent(arguments);
  },

  getToolbarCmp: function () {
    var me = this;

    return [{
      text: "新增表单分类",
      handler: me.onAddCategory,
      scope: me
    }, {
      text: "编辑表单分类",
      handler: me.onEditCategory,
      scope: me
    }, {
      text: "删除表单分类",
      handler: me.onDeleteCategory,
      scope: me
    }, "-", {
      text: "新增表单",
      handler: me.onAddForm,
      scope: me
    }, {
      text: "编辑表单",
      handler: me.onEditForm,
      scope: me
    }, {
      text: "删除表单",
      handler: me.onDeleteForm,
      scope: me
    }, "-", {
      text: "帮助",
      handler: function () {
        me.showInfo("TODO");
      }
    }, "-", {
      text: "关闭",
      handler: function () {
        me.closeWindow();
      }
    }];
  },

  onAddCategory: function () {
    var me = this;

    me.showInfo("TODO");
  },

  onEditCategory: function () {
    var me = this;

    me.showInfo("TODO");
  },

  onDeleteCategory: function () {
    var me = this;

    me.showInfo("TODO");
  },

  onAddForm: function () {
    var me = this;

    me.showInfo("TODO");
  },

  onEditForm: function () {
    var me = this;

    me.showInfo("TODO");
  },

  onDeleteForm: function () {
    var me = this;

    me.showInfo("TODO");
  }
});
