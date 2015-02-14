Ext.define("PSI.User.MainForm", {
    extend: "Ext.panel.Panel",
    getBaseURL: function () {
        return PSI.Const.BASE_URL;
    },
    initComponent: function () {
        var me = this;

        Ext.define("PSIOrgModel", {
            extend: "Ext.data.Model",
            fields: ["id", "text", "fullName", "orgCode", "leaf", "children"]
        });

        var orgStore = Ext.create("Ext.data.TreeStore", {
            model: "PSIOrgModel",
            proxy: {
                type: "ajax",
                url: me.getBaseURL() + "Home/User/allOrgs"
            }
        });

        orgStore.on("load", me.onOrgStoreLoad, me);

        var orgTree = Ext.create("Ext.tree.Panel", {
            title: "组织机构",
            store: orgStore,
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
                        dataIndex: "orgCode",
                        flex: 1
                    }]
            }
        });
        me.orgTree = orgTree;

        orgTree.on("select", function (rowModel, record) {
            me.onOrgTreeNodeSelect(record);
        }, me);

        orgTree.on("itemdblclick", me.onEditOrg, me);

        Ext.define("PSIUser", {
            extend: "Ext.data.Model",
            fields: ["id", "loginName", "name", "enabled", "orgCode"]
        });
        var storeGrid = Ext.create("Ext.data.Store", {
            autoLoad: false,
            model: "PSIUser",
            data: []
        });

        var grid = Ext.create("Ext.grid.Panel", {
            title: "人员列表",
            viewConfig: {
                enableTextSelection: true
            },
            columnLines: true,
            columns: [
                Ext.create("Ext.grid.RowNumberer", {text: "序号", width: 40}),
                {header: "登录名", dataIndex: "loginName", menuDisabled: true, sortable: false},
                {header: "姓名", dataIndex: "name", menuDisabled: true, sortable: false},
                {header: "编码", dataIndex: "orgCode", menuDisabled: true, sortable: false},
                {
                    header: "是否允许登录", dataIndex: "enabled", menuDisabled: true, sortable: false,
                    renderer: function (value) {
                        return value == 1 ? "允许登录" : "<span style='color:red'>禁止登录</span>";
                    }
                }
            ],
            store: storeGrid,
            listeners: {
                itemdblclick: {
                    fn: me.onEditUser,
                    scope: me
                }
            }
        });

        this.grid = grid;

        Ext.apply(me, {
            border: 0,
            layout: "border",
            tbar: [
                {text: "新增组织机构", iconCls: "PSI-button-add", handler: me.onAddOrg, scope: me},
                {text: "编辑组织机构", iconCls: "PSI-button-edit", handler: me.onEditOrg, scope: me},
                {text: "删除组织机构", iconCls: "PSI-button-delete", handler: me.onDeleteOrg, scope: me}, "-",
                {text: "新增用户", iconCls: "PSI-button-add-user", handler: me.onAddUser, scope: me},
                {text: "修改用户", iconCls: "PSI-button-edit-user", handler: me.onEditUser, scope: me},
                {text: "删除用户", iconCls: "PSI-button-delete-user", handler: me.onDeleteUser, scope: me}, "-",
                {text: "修改用户密码", iconCls: "PSI-button-change-password", handler: me.onEditUserPassword, scope: me}, "-",
                {
                    text: "帮助",
                    iconCls: "PSI-help",
                    handler: function() {
                        window.open("http://my.oschina.net/u/134395/blog/374303");
                    }
                },
                "-",
                {
                    text: "关闭", iconCls: "PSI-button-exit", handler: function () {
                        location.replace(me.getBaseURL());
                    }
                }
            ],
            items: [{
                    region: "center", xtype: "panel", layout: "fit", border: 0,
                    items: [grid]
                }, {
                    xtype: "panel",
                    region: "west",
                    layout: "fit",
                    width: 300,
                    minWidth: 200,
                    maxWidth: 350,
                    split: true,
                    border: 0,
                    items: [orgTree]
                }]
        });

        me.callParent(arguments);
    },
    getGrid: function () {
        return this.grid;
    },
    onAddOrg: function () {
        var form = Ext.create("PSI.User.OrgEditForm", {
            parentForm: this
        });
        form.show();
    },
    onEditOrg: function () {
        var tree = this.orgTree;
        var item = tree.getSelectionModel().getSelection();
        if (item === null || item.length !== 1) {
            PSI.MsgBox.showInfo("请选择要编辑的组织机构");
            return;
        }

        var org = item[0].getData();

        var form = Ext.create("PSI.User.OrgEditForm", {
            parentForm: this,
            entity: org
        });
        form.show();
    },
    onDeleteOrg: function () {
        var me = this;
        var tree = me.orgTree;
        var item = tree.getSelectionModel().getSelection();
        if (item === null || item.length !== 1) {
            PSI.MsgBox.showInfo("请选择要删除的组织机构");
            return;
        }

        var org = item[0].getData();

        PSI.MsgBox.confirm("请确认是否删除组织机构 <span style='color:red'>" + org.fullName + "</span> ?",
                function () {
                    Ext.getBody().mask("正在删除中...");
                    Ext.Ajax.request({
                        url: me.getBaseURL() + "Home/User/deleteOrg",
                        method: "POST",
                        params: {id: org.id},
                        callback: function (options, success, response) {
                            Ext.getBody().unmask();

                            if (success) {
                                var data = Ext.JSON.decode(response.responseText);
                                if (data.success) {
                                    PSI.MsgBox.showInfo("成功完成删除操作", function () {
                                        me.freshOrgGrid();
                                    });
                                } else {
                                    PSI.MsgBox.showInfo(data.msg);
                                }
                            }
                        }
                    });
                });
    },
    freshOrgGrid: function () {
        this.orgTree.getStore().reload();
    },
    freshUserGrid: function () {
        var tree = this.orgTree;
        var item = tree.getSelectionModel().getSelection();
        if (item === null || item.length !== 1) {
            return;
        }

        this.onOrgTreeNodeSelect(item[0]);
    },
    // private
    onAddUser: function () {
        var editFrom = Ext.create("PSI.User.UserEditForm", {
            parentForm: this
        });
        editFrom.show();
    },
    onEditUser: function () {
        var item = this.grid.getSelectionModel().getSelection();
        if (item === null || item.length !== 1) {
            PSI.MsgBox.showInfo("请选择要编辑的用户");
            return;
        }

        var user = item[0].data;

        var tree = this.orgTree;
        var node = tree.getSelectionModel().getSelection();
        if (node && node.length === 1) {
            var org = node[0].data;

            user.orgId = org.id;
            user.orgName = org.fullName;
        }

        var editFrom = Ext.create("PSI.User.UserEditForm", {
            parentForm: this,
            entity: user
        });
        editFrom.show();
    },
    onEditUserPassword: function () {
        var item = this.grid.getSelectionModel().getSelection();
        if (item === null || item.length !== 1) {
            PSI.MsgBox.showInfo("请选择要修改密码的用户");
            return;
        }

        var user = item[0].getData();
        var editFrom = Ext.create("PSI.User.ChangeUserPasswordForm", {
            entity: user
        });
        editFrom.show();
    },
    onDeleteUser: function () {
        var me = this;
        var item = me.grid.getSelectionModel().getSelection();
        if (item === null || item.length !== 1) {
            PSI.MsgBox.showInfo("请选择要删除的用户");
            return;
        }

        var user = item[0].getData();

        PSI.MsgBox.confirm("请确认是否删除用户 <span style='color:red'>" + user.name + "</span> ?",
                function () {
                    Ext.getBody().mask("正在删除中...");
                    Ext.Ajax.request({
                        url: me.getBaseURL() + "Home/User/deleteUser",
                        method: "POST",
                        params: {id: user.id},
                        callback: function (options, success, response) {
                            Ext.getBody().unmask();

                            if (success) {
                                var data = Ext.JSON.decode(response.responseText);
                                if (data.success) {
                                    PSI.MsgBox.showInfo("成功完成删除操作", function () {
                                        me.freshUserGrid();
                                    });
                                } else {
                                    PSI.MsgBox.showInfo(data.msg);
                                }
                            }
                        }
                    });
                });
    },
    // private
    onOrgTreeNodeSelect: function (rec) {
        if (!rec) {
            return;
        }

        var org = rec.data;
        if (!org) {
            return;
        }

        var me = this;
        var grid = me.getGrid();

        grid.setTitle(org.fullName + " - 人员列表");

        grid.getEl().mask("数据加载中...");

        Ext.Ajax.request({
            url: me.getBaseURL() + "Home/User/users",
            params: {orgId: org.id},
            method: "POST",
            callback: function (options, success, response) {
                var store = grid.getStore();

                store.removeAll();

                if (success) {
                    var data = Ext.JSON.decode(response.responseText);
                    store.add(data);
                }

                grid.getEl().unmask();
            }
        });
    },
    // private
    onOrgStoreLoad: function () {
        var tree = this.orgTree;
        var root = tree.getRootNode();
        if (root) {
            var node = root.firstChild;
            if (node) {
                this.onOrgTreeNodeSelect(node);
            }
        }
    }
});