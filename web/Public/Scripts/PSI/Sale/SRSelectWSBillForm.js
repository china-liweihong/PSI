Ext.define("PSI.Sale.SRSelectWSBillForm", {
    extend: "Ext.window.Window",
    config: {
        parentForm: null
    },
    initComponent: function () {
        var me = this;
        Ext.apply(me, {title: "选择销售出库单",
            modal: true,
            onEsc: Ext.emptyFn,
            width: 800,
            height: 500,
            layout: "border",
            items: [{
                    region: "center",
                    border: 0,
                    bodyPadding: 10,
                    layout: "fit",
                    items: [me.getWSBillGrid()]
                },
                {
                    region: "north",
                    border: 0,
                    layout: {
                        type: "table",
                        columns: 2
                    },
                    height: 150,
                    bodyPadding: 10,
                    items: [
                        {
                            html: "<h1>选择销售出库单</h1>",
                            border: 0,
                            colspan: 2
                        },
                        {
                            xtype: "textfield",
                            labelAlign: "right",
                            labelSeparator: "",
                            fieldLabel: "销售出库单单号"
                        },{
                            xtype: "psi_customerfield",
                            labelAlign: "right",
                            labelSeparator: "",
                            fieldLabel: "客户"
                        },{
                            xtype: "datefield",
                            format: "Y-m-d",
                            labelAlign: "right",
                            labelSeparator: "",
                            fieldLabel: "业务日期（起）"
                        },{
                            xtype: "datefield",
                            format: "Y-m-d",
                            labelAlign: "right",
                            labelSeparator: "",
                            fieldLabel: "业务日期（止）"
                        },{
                            xtype: "psi_warehousefield",
                            labelAlign: "right",
                            labelSeparator: "",
                            fieldLabel: "仓库"
                        },{
                            xtype: "button",
                            text: "查询",
                            width: 100,
                            margin: "0 0 0 10",
                            iconCls: "PSI-button-refresh",
                            handler: me.onQuery,
                            scope: me
                        }
                    ]
                }],
            listeners: {
                show: {
                    fn: me.onWndShow,
                    scope: me
                }
            },
            buttons: [{
                    text: "选择",
                    iconCls: "PSI-button-ok",
                    formBind: true,
                    handler: me.onOK,
                    scope: me
                }, {
                    text: "取消", handler: function () {
                        me.close();
                    }, scope: me
                }]
        });

        me.callParent(arguments);
    },
    onWndShow: function () {
        var me = this;
    },
    // private
    onOK: function () {
        var me = this;
        
        var item = me.getWSBillGrid().getSelectionModel().getSelection();
        if (item == null || item.length != 1) {
            PSI.MsgBox.showInfo("请选择销售出库单");
            return;
        }
        var wsBill = item[0];
        me.close();
        me.getParentForm().getWSBillInfo(wsBill.get("id"));
    },
    getWSBillGrid: function() {
        var me = this;
        
        if (me.__wsBillGrid) {
            return me.__wsBillGrid;
        }
        
        var modelName = "PSIWSBill_SRSelectForm";
        Ext.define(modelName, {
            extend: "Ext.data.Model",
            fields: ["id", "ref", "bizDate", "customerName", "warehouseName",
                "inputUserName", "bizUserName", "billStatus", "amount"]
        });
        var storeWSBill = Ext.create("Ext.data.Store", {
            autoLoad: false,
            model: modelName,
            data: [],
            pageSize: 20,
            proxy: {
                type: "ajax",
                actionMethods: {
                    read: "POST"
                },
                url: PSI.Const.BASE_URL + "Home/Sale/selectWSBillList",
                reader: {
                    root: 'dataList',
                    totalProperty: 'totalCount'
                }
            }
        });
        storeWSBill.on("load", function (e, records, successful) {
            if (successful) {
            }
        });


        me.__wsBillGrid = Ext.create("Ext.grid.Panel", {
            columnLines: true,
            columns: [
                Ext.create("Ext.grid.RowNumberer", {text: "序号", width: 50}),
                {
                    header: "状态",
                    dataIndex: "billStatus",
                    menuDisabled: true,
                    sortable: false,
                    width: 60
                }, {
                    header: "单号",
                    dataIndex: "ref",
                    width: 110,
                    menuDisabled: true,
                    sortable: false
                }, {
                    header: "业务日期",
                    dataIndex: "bizDate",
                    menuDisabled: true,
                    sortable: false
                }, {
                    header: "客户",
                    dataIndex: "customerName",
                    width: 200,
                    menuDisabled: true,
                    sortable: false
                }, {
                    header: "销售金额",
                    dataIndex: "amount",
                    menuDisabled: true,
                    sortable: false,
                    align: "right",
                    xtype: "numbercolumn",
                    width: 80
                }, {
                    header: "出库仓库",
                    dataIndex: "warehouseName",
                    menuDisabled: true,
                    sortable: false
                }, {
                    header: "业务员",
                    dataIndex: "bizUserName",
                    menuDisabled: true,
                    sortable: false
                }, {
                    header: "录单人",
                    dataIndex: "inputUserName",
                    menuDisabled: true,
                    sortable: false
                }],
            listeners: {
                itemdblclick: {
                    fn: me.onOK,
                    scope: me
                }
            },
            store: storeWSBill,
            bbar: [{
                    id: "srbill_selectform_pagingToobar",
                    xtype: "pagingtoolbar",
                    border: 0,
                    store: storeWSBill
                }, "-", {
                    xtype: "displayfield",
                    value: "每页显示"
                }, {
                    id: "srbill_selectform_comboCountPerPage",
                    xtype: "combobox",
                    editable: false,
                    width: 60,
                    store: Ext.create("Ext.data.ArrayStore", {
                        fields: ["text"],
                        data: [["20"], ["50"], ["100"], ["300"], ["1000"]]
                    }),
                    value: 20,
                    listeners: {
                        change: {
                            fn: function () {
                                storeWSBill.pageSize = Ext.getCmp("srbill_selectform_comboCountPerPage").getValue();
                                storeWSBill.currentPage = 1;
                                Ext.getCmp("srbill_selectform_pagingToobar").doRefresh();
                            },
                            scope: me
                        }
                    }
                }, {
                    xtype: "displayfield",
                    value: "条记录"
                }]
        });
        
        return me.__wsBillGrid;
    },
    onQuery: function() {
        Ext.getCmp("srbill_selectform_pagingToobar").doRefresh();
    }
});