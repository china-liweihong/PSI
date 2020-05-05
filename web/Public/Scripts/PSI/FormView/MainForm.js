﻿/**
 * 视图开发助手 - 主页面
 */
Ext.define("PSI.FormView.MainForm", {
  extend: "PSI.AFX.BaseMainExForm",

  initComponent: function () {
    var me = this;

    Ext.apply(me, {
      tbar: me.getToolbarCmp(),
      items: [{
        border: 0,
        split: true,
        region: "west",
        width: 370,
        layout: "fit",
        items: me.getCategoryGrid()
      }, {
        border: 0,
        region: "center",
        layout: "border",
        items: [{
          region: "center",
          border: 0,
          layout: "fit",
          items: [me.getMainGrid()]
        }, {
          region: "south",
          border: 0,
          height: "60%",
          split: true,
          layout: "fit",
          xtype: "tabpanel",
          items: [{ title: "列" }, { title: "查询条件" }, { title: "业务按钮" }]
        }]
      }]
    });

    me.callParent(arguments);

    me.refreshCategoryGrid();
  },

  getToolbarCmp: function () {
    var me = this;
    return [{
      text: "新增视图分类",
      handler: me.onAddCategory,
      scope: me
    }, {
      text: "编辑视图分类",
      handler: me.onEditCategory,
      scope: me
    }, {
      text: "删除视图分类",
      handler: me.onDeleteCategory,
      scope: me
    }, "-", {
      text: "关闭",
      handler: function () {
        me.closeWindow();
      },
      scope: me
    }];
  },

  getCategoryGrid: function () {
    var me = this;

    if (me.__categoryGrid) {
      return me.__categoryGrid;
    }

    var modelName = "PSIFvCategory";

    Ext.define(modelName, {
      extend: "Ext.data.Model",
      fields: ["id", "code", "name", "isSystem", "isSystemCaption"]
    });

    me.__categoryGrid = Ext.create("Ext.grid.Panel", {
      cls: "PSI",
      viewConfig: {
        enableTextSelection: true
      },
      header: {
        height: 30,
        title: me.formatGridHeaderTitle("视图分类")
      },
      tools: [{
        type: "close",
        handler: function () {
          Ext.getCmp("panelCategory").collapse();
        }
      }],
      columnLines: true,
      columns: [{
        header: "分类编码",
        dataIndex: "code",
        width: 80,
        menuDisabled: true,
        sortable: false
      }, {
        header: "视图分类",
        dataIndex: "name",
        width: 200,
        menuDisabled: true,
        sortable: false
      }, {
        header: "系统固有",
        dataIndex: "isSystemCaption",
        menuDisabled: true,
        width: 80,
        align: "center",
        sortable: false
      }],
      store: Ext.create("Ext.data.Store", {
        model: modelName,
        autoLoad: false,
        data: []
      }),
      listeners: {
        select: {
          fn: me.onCategoryGridSelect,
          scope: me
        }
      }
    });

    return me.__categoryGrid;
  },

  refreshCategoryGrid: function (id) {
    var me = this;
    var grid = me.getCategoryGrid();
    var el = grid.getEl() || Ext.getBody();
    el.mask(PSI.Const.LOADING);
    var r = {
      url: me.URL("Home/FormView/categoryList"),
      callback: function (options, success, response) {
        var store = grid.getStore();

        store.removeAll();

        if (success) {
          var data = me.decodeJSON(response.responseText);
          store.add(data);

          if (store.getCount() > 0) {
            if (id) {
              var r = store.findExact("id", id);
              if (r != -1) {
                grid.getSelectionModel().select(r);
              }
            } else {
              grid.getSelectionModel().select(0);
            }
          }
        }

        el.unmask();
      }
    };

    me.ajax(r);
  },

  onAddCategory: function () {
    var me = this;

    var form = Ext.create("PSI.FormView.CategoryEditForm", {
      parentForm: me
    });

    form.show();
  },

  onEditCategory: function () {
    var me = this;

    var item = me.getCategoryGrid().getSelectionModel().getSelection();
    if (item == null || item.length != 1) {
      me.showInfo("请选择要编辑的视图分类");
      return;
    }

    var category = item[0];

    if (category.get("isSystem") == 1) {
      me.showInfo("不能编辑系统分类");
      return;
    }

    var form = Ext.create("PSI.FormView.CategoryEditForm", {
      parentForm: me,
      entity: category
    });

    form.show();
  },

  onDeleteCategory: function () {
    var me = this;
    var item = me.getCategoryGrid().getSelectionModel()
      .getSelection();
    if (item == null || item.length != 1) {
      me.showInfo("请选择要删除的视图分类");
      return;
    }

    var category = item[0];
    if (category.get("isSystem") == 1) {
      me.showInfo("不能删除系统分类");
      return;
    }

    var store = me.getCategoryGrid().getStore();
    var index = store.findExact("id", category.get("id"));
    index--;
    var preIndex = null;
    var preItem = store.getAt(index);
    if (preItem) {
      preIndex = preItem.get("id");
    }

    var info = "请确认是否删除视图分类: <span style='color:red'>"
      + category.get("name") + "</span>";

    var funcConfirm = function () {
      var el = Ext.getBody();
      el.mask("正在删除中...");

      var r = {
        url: me.URL("Home/FormView/deleteViewCategory"),
        params: {
          id: category.get("id")
        },
        callback: function (options, success, response) {
          el.unmask();

          if (success) {
            var data = me.decodeJSON(response.responseText);
            if (data.success) {
              me.tip("成功完成删除操作");
              me.refreshCategoryGrid(preIndex);
            } else {
              me.showInfo(data.msg);
            }
          } else {
            me.showInfo("网络错误");
          }
        }
      };

      me.ajax(r);
    };

    me.confirm(info, funcConfirm);
  },

  getMainGrid: function () {
    var me = this;
    if (me.__mainGrid) {
      return me.__mainGrid;
    }

    var modelName = "PSIGoodsCategory";
    Ext.define(modelName, {
      extend: "Ext.data.Model",
      fields: ["id", "text", "code", "fid", "memo", "mdVersion", "isFixed",
        "moduleName", "leaf", "children"]
    });

    var store = Ext.create("Ext.data.TreeStore", {
      model: modelName,
      proxy: {
        type: "ajax",
        actionMethods: {
          read: "POST"
        },
        url: me.URL("Home/FormView/fvList")
      },
      listeners: {
        beforeload: {
          fn: function () {
            store.proxy.extraParams = me.getQueryParamForMainGrid();
          },
          scope: me
        }
      }

    });

    store.on("load", me.onMainGridStoreLoad, me);

    me.__mainGrid = Ext.create("Ext.tree.Panel", {
      cls: "PSI",
      header: {
        height: 30,
        title: me.formatGridHeaderTitle("视图列表")
      },
      store: store,
      rootVisible: false,
      useArrows: true,
      viewConfig: {
        loadMask: true
      },
      columns: {
        defaults: {
          sortable: false,
          menuDisabled: true,
          draggable: false
        },
        items: [{
          xtype: "treecolumn",
          text: "名称",
          dataIndex: "text",
          width: 220
        }, {
          text: "编码",
          dataIndex: "code",
          width: 100
        }, {
          text: "fid",
          dataIndex: "fid",
          width: 100
        }, {
          text: "版本",
          dataIndex: "mdVersion",
          width: 100
        }, {
          text: "系统固有",
          dataIndex: "isFixed",
          width: 100
        }, {
          text: "模块名称",
          dataIndex: "moduleName",
          width: 100
        }]
      },
      listeners: {
        select: {
          fn: function (rowModel, record) {
            me.onMainGridNodeSelect(record);
          },
          scope: me
        }
      }
    });

    return me.__mainGrid;
  },

  getQueryParamForMainGrid: function () {
    return {};
  },

  onMainGridStoreLoad: function () { }
});
