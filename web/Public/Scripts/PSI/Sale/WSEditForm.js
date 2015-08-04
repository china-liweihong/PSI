// 销售出库 - 新建或编辑界面
Ext.define("PSI.Sale.WSEditForm", {
    extend: "Ext.window.Window",
    config: {
        parentForm: null,
        entity: null
    },
    initComponent: function () {
        var me = this;
        var entity = me.getEntity();
        this.adding = entity == null;

        Ext.apply(me, {title: entity == null ? "新建销售出库单" : "编辑销售出库单",
            modal: true,
            onEsc: Ext.emptyFn,
            maximized: true,
            width: 1000,
            height: 600,
            layout: "border",
            defaultFocus: "editCustomer",
            tbar: ["-", {
                text: "保存",
                iconCls: "PSI-button-ok",
                handler: me.onOK,
                scope: me
            },"-", {
                text: "取消", 
                iconCls: "PSI-button-cancel",
                handler: function () {
                	PSI.MsgBox.confirm("请确认是否取消当前操作？", function(){
                		me.close();
                	});
                }, scope: me
            }],
            items: [{
                    region: "center",
                    border: 0,
                    bodyPadding: 10,
                    layout: "fit",
                    items: [me.getGoodsGrid()]
                },
                {
                    region: "north",
                    border: 0,
                    layout: {
                        type: "table",
                        columns: 2
                    },
                    height: 100,
                    bodyPadding: 10,
                    items: [
                        {
                            xtype: "hidden",
                            id: "hiddenId",
                            name: "id",
                            value: entity == null ? null : entity.get("id")
                        },
                        {
                            id: "editRef",
                            fieldLabel: "单号",
                            labelWidth: 60,
                            labelAlign: "right",
                            labelSeparator: "",
                            xtype: "displayfield",
                            value: "<span style='color:red'>保存后自动生成</span>"
                        },
                        {
                            id: "editBizDT",
                            fieldLabel: "业务日期",
                            allowBlank: false,
                            blankText: "没有输入业务日期",
                            labelWidth: 60,
                            labelAlign: "right",
                            labelSeparator: "",
                            beforeLabelTextTpl: PSI.Const.REQUIRED,
                            xtype: "datefield",
                            format: "Y-m-d",
                            value: new Date(),
                            name: "bizDT",
                            listeners: {
                                specialkey: {
                                    fn: me.onEditBizDTSpecialKey,
                                    scope: me
                                }
                            }
                        },
                        {
                            xtype: "hidden",
                            id: "editCustomerId",
                            name: "customerId"
                        },
                        {
                            id: "editCustomer",
                            xtype: "psi_customerfield",
                            parentCmp: me,
                            fieldLabel: "客户",
                            allowBlank: false,
                            labelWidth: 60,
                            labelAlign: "right",
                            labelSeparator: "",
                            colspan: 2,
                            width: 430,
                            blankText: "没有输入客户",
                            beforeLabelTextTpl: PSI.Const.REQUIRED,
                            listeners: {
                                specialkey: {
                                    fn: me.onEditCustomerSpecialKey,
                                    scope: me
                                }
                            }
                        },
                        {
                            xtype: "hidden",
                            id: "editWarehouseId",
                            name: "warehouseId"
                        },
                        {
                            id: "editWarehouse",
                            fieldLabel: "出库仓库",
                            labelWidth: 60,
                            labelAlign: "right",
                            labelSeparator: "",
                            xtype: "psi_warehousefield",
                            parentCmp: me,
                            fid: "2002",
                            allowBlank: false,
                            blankText: "没有输入出库仓库",
                            beforeLabelTextTpl: PSI.Const.REQUIRED,
                            listeners: {
                                specialkey: {
                                    fn: me.onEditWarehouseSpecialKey,
                                    scope: me
                                }
                            }
                        },
                        {
                            xtype: "hidden",
                            id: "editBizUserId",
                            name: "bizUserId"
                        },
                        {
                            id: "editBizUser",
                            fieldLabel: "业务员",
                            xtype: "psi_userfield",
                            labelWidth: 60,
                            labelAlign: "right",
                            labelSeparator: "",
                            parentCmp: me,
                            allowBlank: false,
                            blankText: "没有输入业务员",
                            beforeLabelTextTpl: PSI.Const.REQUIRED,
                            listeners: {
                                specialkey: {
                                    fn: me.onEditBizUserSpecialKey,
                                    scope: me
                                }
                            }
                        }
                    ]
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
        var me = this;
        me.__canEditGoodsPrice = false;
        var el = me.getEl() || Ext.getBody();
        el.mask(PSI.Const.LOADING);
        Ext.Ajax.request({
            url: PSI.Const.BASE_URL + "Home/Sale/wsBillInfo",
            params: {
                id: Ext.getCmp("hiddenId").getValue()
            },
            method: "POST",
            callback: function (options, success, response) {
                el.unmask();

                if (success) {
                    var data = Ext.JSON.decode(response.responseText);

                    if (data.canEditGoodsPrice) {
                        me.__canEditGoodsPrice = true;
                        Ext.getCmp("columnGoodsPrice").setEditor({xtype: "numberfield",
                            allowDecimals: true,
                            hideTrigger: true});
                    }
                    
                    if (data.ref) {
                        Ext.getCmp("editRef").setValue(data.ref);
                    }

                    Ext.getCmp("editCustomerId").setValue(data.customerId);
                    Ext.getCmp("editCustomer").setValue(data.customerName);

                    Ext.getCmp("editWarehouseId").setValue(data.warehouseId);
                    Ext.getCmp("editWarehouse").setValue(data.warehouseName);

                    Ext.getCmp("editBizUserId").setValue(data.bizUserId);
                    Ext.getCmp("editBizUser").setValue(data.bizUserName);
                    if (data.bizDT) {
                        Ext.getCmp("editBizDT").setValue(data.bizDT);
                    }

                    var store = me.getGoodsGrid().getStore();
                    store.removeAll();
                    if (data.items) {
                        store.add(data.items);
                    }
                    if (store.getCount() == 0) {
                        store.add({});
                    }

                } else {
                    PSI.MsgBox.showInfo("网络错误")
                }
            }
        });
    },
    // private
    onOK: function () {
        var me = this;
        Ext.getBody().mask("正在保存中...");
        Ext.Ajax.request({
            url: PSI.Const.BASE_URL + "Home/Sale/editWSBill",
            method: "POST",
            params: {jsonStr: me.getSaveData()},
            callback: function (options, success, response) {
                Ext.getBody().unmask();

                if (success) {
                    var data = Ext.JSON.decode(response.responseText);
                    if (data.success) {
                        PSI.MsgBox.showInfo("成功保存数据", function () {
                            me.close();
                            me.getParentForm().refreshWSBillGrid(data.id);
                        });
                    } else {
                        PSI.MsgBox.showInfo(data.msg);
                    }
                }
            }
        });
    },
    onEditBizDTSpecialKey: function (field, e) {
        if (e.getKey() == e.ENTER) {
            Ext.getCmp("editCustomer").focus();
        }
    },
    onEditCustomerSpecialKey: function (field, e) {
        if (e.getKey() == e.ENTER) {
            Ext.getCmp("editWarehouse").focus();
        }
    },
    onEditWarehouseSpecialKey: function (field, e) {
        if (e.getKey() == e.ENTER) {
            Ext.getCmp("editBizUser").focus();
        }
    },
    onEditBizUserSpecialKey: function (field, e) {
        if (e.getKey() == e.ENTER) {
            var me = this;
            var store = me.getGoodsGrid().getStore();
            if (store.getCount() == 0) {
                store.add({});
            }
            me.getGoodsGrid().focus();
            me.__cellEditing.startEdit(0, 1);
        }
    },
    // CustomerField回调此方法
    __setCustomerInfo: function (data) {
        Ext.getCmp("editCustomerId").setValue(data.id);
    },
    // WarehouseField回调此方法
    __setWarehouseInfo: function (data) {
        Ext.getCmp("editWarehouseId").setValue(data.id);
    },
    // UserField回调此方法
    __setUserInfo: function (data) {
        Ext.getCmp("editBizUserId").setValue(data.id);
    },
    getGoodsGrid: function () {
        var me = this;
        if (me.__goodsGrid) {
            return me.__goodsGrid;
        }
        Ext.define("PSIWSBillDetail_EditForm", {
            extend: "Ext.data.Model",
            fields: ["id", "goodsId", "goodsCode", "goodsName", "goodsSpec", "unitName", "goodsCount",
                "goodsMoney", "goodsPrice"]
        });
        var store = Ext.create("Ext.data.Store", {
            autoLoad: false,
            model: "PSIWSBillDetail_EditForm",
            data: []
        });

        me.__cellEditing = Ext.create("Ext.grid.plugin.CellEditing", {
            clicksToEdit: 1,
            listeners: {
                edit: {
                    fn: me.cellEditingAfterEdit,
                    scope: me
                }
            }
        });
        Ext.apply(me.__cellEditing, {
            onSpecialKey: function (ed, field, e) {
                var sm;

                if (e.getKey() === e.TAB || e.getKey() == e.ENTER) {
                    e.stopEvent();

                    if (ed) {
                        ed.onEditorTab(e);
                    }

                    sm = ed.up('tablepanel').getSelectionModel();
                    if (sm.onEditorTab) {
                        return sm.onEditorTab(ed.editingPlugin, e);
                    }
                }
            }
        });
        me.__goodsGrid = Ext.create("Ext.grid.Panel", {
            plugins: [me.__cellEditing],
            columnLines: true,
            columns: [
                Ext.create("Ext.grid.RowNumberer", {text: "序号", width: 30}),
                {header: "商品编码", dataIndex: "goodsCode", menuDisabled: true,
                    sortable: false, editor: {xtype: "psi_goods_with_saleprice_field", parentCmp: me}},
                {header: "商品名称", dataIndex: "goodsName", menuDisabled: true, sortable: false, width: 200},
                {header: "规格型号", dataIndex: "goodsSpec", menuDisabled: true, sortable: false, width: 200},
                {header: "销售数量", dataIndex: "goodsCount", menuDisabled: true,
                    sortable: false, align: "right", width: 100,
                    editor: {xtype: "numberfield",
                        allowDecimals: false,
                        hideTrigger: true}
                },
                {header: "单位", dataIndex: "unitName", menuDisabled: true, sortable: false, width: 60},
                {header: "销售单价", dataIndex: "goodsPrice", menuDisabled: true,
                    sortable: false, align: "right", xtype: "numbercolumn",
                    width: 100, id: "columnGoodsPrice"},
                {header: "销售金额", dataIndex: "goodsMoney", menuDisabled: true,
                    sortable: false, align: "right", xtype: "numbercolumn", width: 120},
                {
                    header: "",
                    align: "center",
                    menuDisabled: true,
                    width: 50,
                    xtype: "actioncolumn",
                    items: [
                        {
                            icon: PSI.Const.BASE_URL + "Public/Images/icons/delete.png",
                            handler: function (grid, row) {
                                var store = grid.getStore();
                                store.remove(store.getAt(row));
                                if (store.getCount() == 0) {
									store.add({});
								}
                            }, scope: me
                        }
                    ]
                }
            ],
            store: store,
            listeners: {
            }
        });

        return me.__goodsGrid;
    },
    cellEditingAfterEdit: function (editor, e) {
        var me = this;
        if (e.colIdx == 4) {
            me.calcMoney();
            if (!me.__canEditGoodsPrice) {
                var store = me.getGoodsGrid().getStore();
                if (e.rowIdx == store.getCount() - 1) {
                    store.add({});
                }
                e.rowIdx += 1;
                me.getGoodsGrid().getSelectionModel().select(e.rowIdx);
                me.__cellEditing.startEdit(e.rowIdx, 1);
            }
        } else if (e.colIdx == 6) {
            me.calcMoney();
            var store = me.getGoodsGrid().getStore();
            if (e.rowIdx == store.getCount() - 1) {
                store.add({});
            }
            e.rowIdx += 1;
            me.getGoodsGrid().getSelectionModel().select(e.rowIdx);
            me.__cellEditing.startEdit(e.rowIdx, 1);
        }
    },
    calcMoney: function () {
        var me = this;
        var item = me.getGoodsGrid().getSelectionModel().getSelection();
        if (item == null || item.length != 1) {
            return;
        }
        var goods = item[0];
        goods.set("goodsMoney", goods.get("goodsCount") * goods.get("goodsPrice"));
    },
    __setGoodsInfo: function (data) {
        var me = this;
        var item = me.getGoodsGrid().getSelectionModel().getSelection();
        if (item == null || item.length != 1) {
            return;
        }
        var goods = item[0];

        goods.set("goodsId", data.id);
        goods.set("goodsCode", data.code);
        goods.set("goodsName", data.name);
        goods.set("unitName", data.unitName);
        goods.set("goodsSpec", data.spec);
        goods.set("goodsPrice", data.salePrice);
    },
    getSaveData: function () {
        var result = {
            id: Ext.getCmp("hiddenId").getValue(),
            bizDT: Ext.Date.format(Ext.getCmp("editBizDT").getValue(), "Y-m-d"),
            customerId: Ext.getCmp("editCustomerId").getValue(),
            warehouseId: Ext.getCmp("editWarehouseId").getValue(),
            bizUserId: Ext.getCmp("editBizUserId").getValue(),
            items: []
        };

        var store = this.getGoodsGrid().getStore();
        for (var i = 0; i < store.getCount(); i++) {
            var item = store.getAt(i);
            result.items.push({
                id: item.get("id"),
                goodsId: item.get("goodsId"),
                goodsCount: item.get("goodsCount"),
                goodsPrice: item.get("goodsPrice")
            });
        }

        return Ext.JSON.encode(result);
    }

});